<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Composer\InstalledVersions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use JsonException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;

final class ValidateProductionCommand extends Command
{
    protected $signature = 'production:validate';

    protected $description = 'Validate the immutable production release contract without mutating application or infrastructure state.';

    public function handle(): int
    {
        $checks = [
            $this->result(
                'APP_ENV',
                config('app.env') === 'production',
                'Expected production; actual '.var_export(config('app.env'), true),
            ),
            $this->result(
                'APP_DEBUG',
                config('app.debug') === false,
                'Expected false; actual '.var_export(config('app.debug'), true),
            ),
            $this->result(
                'APP_URL',
                config('app.url') === config('production.expected_origin'),
                'Expected '.config('production.expected_origin').'; actual '.var_export(config('app.url'), true),
            ),
            $this->result(
                'APP_KEY',
                is_string(config('app.key')) && trim((string) config('app.key')) !== '',
                is_string(config('app.key')) && trim((string) config('app.key')) !== ''
                    ? 'Application key is present.'
                    : 'Application key must be supplied by the production secret source.',
            ),
            $this->result(
                'Secure session cookie',
                config('session.secure') === true && config('session.http_only') === true,
                'SESSION_SECURE_COOKIE and SESSION_HTTP_ONLY must both resolve to true.',
            ),
            $this->cachePrefixCheck(),
            ...$this->artifactChecks(),
            ...$this->approvedRuntimeSourceChecks(),
            ...$this->generatedArtifactChecks(),
            $this->releaseExclusionCheck(),
            $this->devPackageCheck(),
            $this->routeDenyCheck(),
            $this->backupConfigurationCheck(),
            $this->analyticsLegalCheck(),
        ];

        $this->table(
            ['Check', 'Status', 'Detail'],
            array_map(
                static fn (array $check): array => [$check['label'], $check['passed'] ? 'PASS' : 'FAIL', $check['detail']],
                $checks,
            ),
        );

        $failed = array_filter($checks, static fn (array $check): bool => ! $check['passed']);

        if ($failed !== []) {
            $this->error(sprintf('Production validation failed: %d check(s) require correction or evidence.', count($failed)));

            return self::FAILURE;
        }

        $this->info('Production validation passed for repository and release-artifact checks. External runtime evidence is still reviewed separately.');

        return self::SUCCESS;
    }

    /** @return array{label: string, passed: bool, detail: string} */
    private function result(string $label, bool $passed, string $detail): array
    {
        return compact('label', 'passed', 'detail');
    }

    /** @return array{label: string, passed: bool, detail: string} */
    private function cachePrefixCheck(): array
    {
        $prefix = trim((string) config('cache.prefix'));
        $requiredFragment = strtolower(trim((string) config('production.cache_prefix_required_fragment')));
        $passed = $prefix !== ''
            && $requiredFragment !== ''
            && str_contains(strtolower($prefix), $requiredFragment);

        return $this->result(
            'Cache prefix',
            $passed,
            $passed
                ? 'Explicit environment-isolated prefix configured.'
                : sprintf('Prefix must be non-empty and contain %s; actual %s.', var_export($requiredFragment, true), var_export($prefix, true)),
        );
    }

    /** @return array<int, array{label: string, passed: bool, detail: string}> */
    private function artifactChecks(): array
    {
        $checks = [];

        foreach ((array) config('production.artifacts.manifests', []) as $label => $definition) {
            $path = (string) data_get($definition, 'path', '');
            $isJson = (bool) data_get($definition, 'json', false);
            $passed = is_file($path) && filesize($path) > 0;
            $detail = $passed ? 'Present and non-empty: '.$path : 'Missing or empty: '.$path;

            if ($passed && $isJson) {
                try {
                    $decoded = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
                    $passed = is_array($decoded) && $decoded !== [];
                    $detail = $passed ? 'Present, non-empty and valid JSON: '.$path : 'JSON manifest is empty: '.$path;
                } catch (JsonException $exception) {
                    $passed = false;
                    $detail = 'Invalid JSON manifest: '.$exception->getMessage();
                }
            }

            $checks[] = $this->result((string) $label, $passed, $detail);
        }

        return $checks;
    }

    /** @return array<int, array{label: string, passed: bool, detail: string}> */
    private function approvedRuntimeSourceChecks(): array
    {
        return $this->contractFileChecks(
            (array) config('production.artifacts.approved_runtime_sources', []),
            'Approved release source',
        );
    }

    /** @return array<int, array{label: string, passed: bool, detail: string}> */
    private function generatedArtifactChecks(): array
    {
        return $this->contractFileChecks(
            (array) config('production.artifacts.generated', []),
            'Generated artifact',
        );
    }

    /**
     * @param  array<string, mixed>  $definitions
     * @return array<int, array{label: string, passed: bool, detail: string}>
     */
    private function contractFileChecks(array $definitions, string $kind): array
    {
        $checks = [];

        foreach ($definitions as $label => $definition) {
            $path = (string) data_get($definition, 'path', '');
            $markers = array_values(array_filter((array) data_get($definition, 'markers', []), 'is_string'));
            $content = is_file($path) ? (string) file_get_contents($path) : '';
            $missingMarkers = array_values(array_filter(
                $markers,
                static fn (string $marker): bool => ! str_contains($content, $marker),
            ));
            $hasConflictMarker = preg_match('/^(<<<<<<<|=======|>>>>>>>)/m', $content) === 1;
            $passed = $content !== '' && $missingMarkers === [] && ! $hasConflictMarker;

            $detail = match (true) {
                $content === '' => 'Missing or empty: '.$path,
                $hasConflictMarker => 'Conflict marker found: '.$path,
                $missingMarkers !== [] => 'Missing contract marker(s): '.implode(', ', $missingMarkers),
                default => 'Present with required contract markers: '.$path,
            };

            $checks[] = $this->result($kind.': '.(string) $label, $passed, $detail);
        }

        return $checks;
    }

    /** @return array{label: string, passed: bool, detail: string} */
    private function releaseExclusionCheck(): array
    {
        $violations = [];

        foreach ((array) config('production.release_exclusions.paths', []) as $path) {
            if (is_string($path) && file_exists($path)) {
                $violations[] = $path;
            }
        }

        foreach ((array) config('production.release_exclusions.local_key_patterns', []) as $pattern) {
            if (! is_string($pattern)) {
                continue;
            }

            foreach (glob($pattern) ?: [] as $match) {
                $violations[] = $match;
            }
        }

        foreach ((array) config('production.release_exclusions.bytecode_roots', []) as $root) {
            if (! is_string($root) || ! is_dir($root)) {
                continue;
            }

            try {
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS),
                );

                foreach ($iterator as $file) {
                    if ($file->isFile() && str_ends_with(strtolower($file->getFilename()), '.pyc')) {
                        $violations[] = $file->getPathname();
                    }
                }
            } catch (Throwable $exception) {
                $violations[] = $root.' [scan failed: '.$exception->getMessage().']';
            }
        }

        $violations = array_values(array_unique($violations));

        return $this->result(
            'Release exclusions',
            $violations === [],
            $violations === []
                ? 'No excluded env, hot, bytecode, key or development artifact found.'
                : 'Excluded artifact(s) present: '.implode(', ', array_slice($violations, 0, 8)),
        );
    }

    /** @return array{label: string, passed: bool, detail: string} */
    private function devPackageCheck(): array
    {
        $installed = [];

        foreach ((array) config('production.release_exclusions.dev_packages', []) as $package) {
            if (is_string($package) && InstalledVersions::isInstalled($package)) {
                $installed[] = $package;
            }
        }

        return $this->result(
            'Composer no-dev artifact',
            $installed === [],
            $installed === []
                ? 'Configured development packages are absent.'
                : 'Development package(s) installed: '.implode(', ', $installed),
        );
    }

    /** @return array{label: string, passed: bool, detail: string} */
    private function routeDenyCheck(): array
    {
        $markers = array_values(array_filter(
            array_map(static fn (mixed $marker): string => strtolower(trim((string) $marker)), (array) config('production.route_deny_markers', [])),
        ));
        $violations = [];

        foreach (Route::getRoutes() as $route) {
            $identity = strtolower(implode(' ', [
                $route->uri(),
                (string) $route->getName(),
                $route->getActionName(),
            ]));

            foreach ($markers as $marker) {
                if ($marker !== '' && str_contains($identity, $marker)) {
                    $violations[] = $route->methods()[0].' '.$route->uri().' ['.($route->getName() ?? 'unnamed').']';
                    break;
                }
            }
        }

        $violations = array_values(array_unique($violations));

        return $this->result(
            'Production route deny markers',
            $violations === [],
            $violations === []
                ? 'No configured development/demo route marker is registered.'
                : 'Denied route(s) registered: '.implode(', ', array_slice($violations, 0, 8)),
        );
    }

    /** @return array{label: string, passed: bool, detail: string} */
    private function backupConfigurationCheck(): array
    {
        $issues = [];
        $requiredPackage = (string) config('production.backup.required_package');

        if ($requiredPackage === '' || ! InstalledVersions::isInstalled($requiredPackage)) {
            $issues[] = 'required package is not installed';
        }

        if ((bool) config('production.backup.require_non_windows_server', true) && PHP_OS_FAMILY === 'Windows') {
            $issues[] = 'Spatie Laravel Backup v10 requires a non-Windows production server';
        }

        $disks = array_values(array_filter(
            array_map(static fn (mixed $disk): string => trim((string) $disk), (array) config('backup.backup.destination.disks', [])),
        ));
        $knownDisks = array_keys((array) config('filesystems.disks', []));
        $unknownDisks = array_values(array_diff($disks, $knownDisks));
        $localOnlyDisks = (array) config('production.backup.local_only_disks', []);
        $offsiteDisks = array_values(array_diff($disks, $localOnlyDisks));

        if ($disks === []) {
            $issues[] = 'no destination disk configured';
        }
        if ($unknownDisks !== []) {
            $issues[] = 'unknown destination disk(s): '.implode(', ', $unknownDisks);
        }
        if ($offsiteDisks === []) {
            $issues[] = 'at least one off-host destination disk is required';
        }

        $archivePassword = trim((string) config('backup.backup.password'));
        if ($archivePassword === '') {
            $issues[] = 'archive encryption password is missing';
        }

        $notificationEmail = trim((string) config('backup.notifications.mail.to'));
        $emailConfigured = (bool) config('backup.notifications.email_configured');
        if (! $emailConfigured || filter_var($notificationEmail, FILTER_VALIDATE_EMAIL) === false) {
            $issues[] = 'backup notification email is missing or invalid';
        }

        $expectedTime = (string) config('production.backup.expected_time');
        $expectedTimezone = (string) config('production.backup.expected_timezone');
        if ((string) config('backup.schedule.time') !== $expectedTime) {
            $issues[] = 'daily backup time must be '.$expectedTime;
        }
        if ((string) config('backup.schedule.timezone') !== $expectedTimezone) {
            $issues[] = 'backup timezone must be '.$expectedTimezone;
        }

        return $this->result(
            'Backup package and schedule',
            $issues === [],
            $issues === []
                ? 'Spatie backup package, encrypted off-host destination, notifications and daily 03:00 schedule are configured.'
                : 'Backup production gate failed: '.implode('; ', $issues),
        );
    }

    /** @return array{label: string, passed: bool, detail: string} */
    private function analyticsLegalCheck(): array
    {
        $gtmId = trim((string) config('production.analytics.google_tag_manager_id'));
        $gaId = trim((string) config('production.analytics.google_analytics_id'));

        if ($gtmId === '' && $gaId === '') {
            return $this->result(
                'Analytics legal fail-closed',
                true,
                'GA/GTM IDs are blank; analytics remains inactive and legal placeholders do not activate tracking.',
            );
        }

        $issues = [];
        if ($gtmId !== '' && preg_match('/^GTM-[A-Z0-9]+$/', $gtmId) !== 1) {
            $issues[] = 'invalid GTM ID format';
        }
        if ($gaId !== '' && preg_match('/^G-[A-Z0-9]+$/', $gaId) !== 1) {
            $issues[] = 'invalid GA4 ID format';
        }

        $placeholderMarkers = array_map('strtolower', (array) config('production.analytics.placeholder_markers', []));
        foreach ((array) config('production.analytics.legal_facts', []) as $fact => $value) {
            $normalized = strtolower(trim((string) $value));
            $placeholder = $normalized === '';

            foreach ($placeholderMarkers as $marker) {
                if ($marker !== '' && str_contains($normalized, $marker)) {
                    $placeholder = true;
                    break;
                }
            }

            if ($placeholder) {
                $issues[] = 'legal fact unresolved: '.$fact;
            }
        }

        $policyPath = (string) config('production.analytics.legal_policy_path');
        $policy = is_file($policyPath) ? strtolower((string) file_get_contents($policyPath)) : '';
        if ($policy === '') {
            $issues[] = 'legal policy source missing';
        } else {
            foreach ((array) config('production.analytics.legal_policy_placeholder_markers', []) as $marker) {
                if (is_string($marker) && $marker !== '' && str_contains($policy, strtolower($marker))) {
                    $issues[] = 'legal policy still contains placeholder copy';
                    break;
                }
            }
        }

        return $this->result(
            'Analytics legal fail-closed',
            $issues === [],
            $issues === []
                ? 'Configured analytics IDs have complete non-placeholder legal facts and policy copy.'
                : 'Tracking must remain disabled: '.implode('; ', $issues),
        );
    }
}
