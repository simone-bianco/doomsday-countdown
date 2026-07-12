<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

final class ProductionValidationCommandTest extends TestCase
{
    private string $sandbox;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sandbox = storage_path('framework/testing/production-validation-'.Str::uuid());
        File::ensureDirectoryExists($this->sandbox);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->sandbox);

        parent::tearDown();
    }

    public function test_it_passes_a_complete_non_mutating_release_contract_with_analytics_inactive(): void
    {
        $files = $this->configurePassingContract();
        $hashesBefore = collect($files)->mapWithKeys(
            static fn (string $path): array => [$path => hash_file('sha256', $path)],
        );

        $exitCode = Artisan::call('production:validate');
        $output = Artisan::output();

        $this->assertSame(0, $exitCode, $output);
        $this->assertStringContainsString('Production validation passed', $output);
        $this->assertStringContainsString('analytics remains inactive', $output);
        $this->assertStringContainsString('Approved release source: Community links', $output);
        $this->assertStringContainsString('Backup package and schedule', $output);

        foreach ($hashesBefore as $path => $hash) {
            $this->assertSame($hash, hash_file('sha256', $path), 'Validator mutated '.$path);
        }
    }

    public function test_it_fails_environment_artifact_exclusion_and_route_contracts(): void
    {
        $this->configurePassingContract();

        config()->set('app.env', 'local');
        config()->set('app.debug', true);
        config()->set('app.url', 'http://localhost');
        config()->set('session.secure', false);
        config()->set('cache.prefix', 'local-cache-');

        $ssrManifest = (string) data_get(config('production.artifacts.manifests'), 'SSR manifest.path');
        File::delete($ssrManifest);

        $forbiddenPath = $this->sandbox.'/release/.env';
        File::ensureDirectoryExists(dirname($forbiddenPath));
        File::put($forbiddenPath, 'APP_KEY=not-a-real-secret');
        config()->set('production.release_exclusions.paths', [$forbiddenPath]);

        Route::get('/production-validation-forbidden', static fn (): string => 'forbidden')
            ->name('production.validation.forbidden');
        config()->set('production.route_deny_markers', ['production-validation-forbidden']);

        $exitCode = Artisan::call('production:validate');
        $output = Artisan::output();

        $this->assertSame(1, $exitCode);
        foreach (['APP_ENV', 'APP_DEBUG', 'APP_URL', 'Secure session cookie', 'Cache prefix', 'SSR manifest', 'Release exclusions', 'Production route deny markers'] as $failedCheck) {
            $this->assertStringContainsString($failedCheck, $output);
        }
        $this->assertStringContainsString('Production validation failed', $output);
    }

    public function test_it_fails_closed_when_backup_is_local_unencrypted_unmonitored_or_mis_scheduled(): void
    {
        $this->configurePassingContract();

        config()->set('production.backup.require_non_windows_server', false);
        config()->set('backup.backup.destination.disks', ['backup']);
        config()->set('backup.backup.password', '');
        config()->set('backup.notifications.email_configured', false);
        config()->set('backup.schedule.time', '04:00');
        config()->set('backup.schedule.timezone', 'UTC');

        $exitCode = Artisan::call('production:validate');
        $output = Artisan::output();

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('Backup package and schedule', $output);
        $this->assertStringContainsString('off-host destination disk', $output);
        $this->assertStringContainsString('archive encryption password', $output);
        $this->assertStringContainsString('notification email', $output);
        $this->assertStringContainsString('daily backup time must be 03:00', $output);
        $this->assertStringContainsString('backup timezone must be Europe/Rome', $output);
    }

    public function test_it_fails_closed_when_analytics_is_configured_with_placeholder_legal_facts(): void
    {
        $this->configurePassingContract();

        config()->set('production.analytics.google_analytics_id', 'G-2L9QPGWKVL');
        config()->set('production.analytics.legal_facts', [
            'controller' => '[DA VERIFICARE]',
            'contact' => 'privacy@example.com',
            'vendor' => 'TODO',
            'retention' => '',
        ]);
        File::put(
            (string) config('production.analytics.legal_policy_path'),
            'Final privacy contact details must be completed by the controller before production launch.',
        );

        $exitCode = Artisan::call('production:validate');
        $output = Artisan::output();

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('Analytics legal fail-closed', $output);
        $this->assertStringContainsString('legal fact unresolved', $output);
        $this->assertStringContainsString('legal policy still contains placeholder copy', $output);
    }

    public function test_it_accepts_a_valid_analytics_id_only_after_all_legal_facts_are_complete(): void
    {
        $this->configurePassingContract();

        config()->set('production.analytics.google_analytics_id', 'G-2L9QPGWKVL');
        config()->set('production.analytics.legal_facts', [
            'controller' => 'Doomsday Clock legal controller',
            'contact' => 'privacy@doomsday-clock.com',
            'vendor' => 'Google Analytics 4',
            'retention' => '14 months',
        ]);
        File::put(
            (string) config('production.analytics.legal_policy_path'),
            'Controller, privacy contact, analytics vendor and retention are complete and approved.',
        );

        $exitCode = Artisan::call('production:validate');
        $output = Artisan::output();

        $this->assertSame(0, $exitCode, $output);
        $this->assertStringContainsString('complete non-placeholder legal facts', $output);
    }

    /** @return array<int, string> */
    private function configurePassingContract(): array
    {
        $files = [
            'client_manifest' => $this->sandbox.'/public/build/manifest.json',
            'ssr_manifest' => $this->sandbox.'/bootstrap/ssr/ssr-manifest.json',
            'ssr_bundle' => $this->sandbox.'/bootstrap/ssr/ssr.js',
            'community' => $this->sandbox.'/resources/CommunityLinks.vue',
            'site_header' => $this->sandbox.'/resources/SiteHeader.vue',
            'about' => $this->sandbox.'/resources/AboutClosingBand.vue',
            'types' => $this->sandbox.'/resources/generated.d.ts',
            'rules' => $this->sandbox.'/resources/form-rules.ts',
            'messages' => $this->sandbox.'/resources/validation-messages.ts',
            'policy' => $this->sandbox.'/resources/LegalPolicy.vue',
        ];

        foreach ($files as $path) {
            File::ensureDirectoryExists(dirname($path));
        }

        File::put($files['client_manifest'], json_encode(['resources/js/app.js' => ['file' => 'assets/app.js']], JSON_THROW_ON_ERROR));
        File::put($files['ssr_manifest'], json_encode(['resources/js/ssr.js' => ['file' => 'ssr.js']], JSON_THROW_ON_ERROR));
        File::put($files['ssr_bundle'], 'export default {};');
        File::put($files['community'], "readonly placement?: 'header' | 'about'; https://discord.gg/NmKXDzwzK https://t.me/doomsdayclockofficial rel=\"noopener noreferrer\" h-9 w-9 v-if=\"!isHeader\"");
        File::put($files['site_header'], "import CommunityLinks from './CommunityLinks.vue'; <nav class=\"primary\"></nav> <CommunityLinks placement=\"header\" />");
        File::put($files['about'], "import CommunityLinks from './CommunityLinks.vue'; <PatreonSupportLink placement=\"about\" /> <CommunityLinks />");
        File::put($files['types'], 'export type CountdownPageData = { sidebar: HomeSidebarData; };');
        File::put($files['rules'], '// AUTO-GENERATED by form-bridge:generate export const SaveCountdownDataRules = {};');
        File::put($files['messages'], '// AUTO-GENERATED by form-bridge:generate export const validationMessages = {};');
        File::put($files['policy'], 'Controller, contact, vendor and retention facts are complete.');

        config()->set('app.env', 'production');
        config()->set('app.debug', false);
        config()->set('app.url', 'https://doomsday-clock.com');
        config()->set('app.key', 'base64:production-validation-test-key');
        config()->set('session.secure', true);
        config()->set('session.http_only', true);
        config()->set('cache.prefix', 'doomsday-clock-production-');
        config()->set('production.expected_origin', 'https://doomsday-clock.com');
        config()->set('production.cache_prefix_required_fragment', 'production');
        config()->set('production.artifacts.manifests', [
            'Client Vite manifest' => ['path' => $files['client_manifest'], 'json' => true],
            'SSR manifest' => ['path' => $files['ssr_manifest'], 'json' => true],
            'SSR bundle' => ['path' => $files['ssr_bundle'], 'json' => false],
        ]);
        config()->set('production.artifacts.approved_runtime_sources', [
            'Community links component' => [
                'path' => $files['community'],
                'markers' => ["readonly placement?: 'header' | 'about';", 'https://discord.gg/NmKXDzwzK', 'https://t.me/doomsdayclockofficial', 'rel="noopener noreferrer"', 'h-9 w-9', 'v-if="!isHeader"'],
            ],
            'Site header community integration' => [
                'path' => $files['site_header'],
                'markers' => ["import CommunityLinks from './CommunityLinks.vue';", '<CommunityLinks placement="header" />', '<nav class='],
            ],
            'About closing-band integration' => [
                'path' => $files['about'],
                'markers' => ["import CommunityLinks from './CommunityLinks.vue';", '<PatreonSupportLink placement="about" />', '<CommunityLinks />'],
            ],
        ]);
        config()->set('production.artifacts.generated', [
            'Generated TypeScript DTOs' => [
                'path' => $files['types'],
                'markers' => ['export type CountdownPageData = {', 'sidebar: HomeSidebarData;'],
            ],
            'Generated form rules' => [
                'path' => $files['rules'],
                'markers' => ['AUTO-GENERATED by form-bridge:generate', 'export const SaveCountdownDataRules'],
            ],
            'Generated validation messages' => [
                'path' => $files['messages'],
                'markers' => ['AUTO-GENERATED by form-bridge:generate', 'export const validationMessages'],
            ],
        ]);
        config()->set('production.release_exclusions', [
            'paths' => [$this->sandbox.'/absent/.env', $this->sandbox.'/absent/public/hot'],
            'bytecode_roots' => [$this->sandbox.'/scan'],
            'local_key_patterns' => [$this->sandbox.'/keys/*.key'],
            'dev_packages' => [],
        ]);
        File::ensureDirectoryExists($this->sandbox.'/scan');
        config()->set('production.route_deny_markers', []);
        config()->set('production.backup.required_package', 'spatie/laravel-backup');
        config()->set('production.backup.expected_time', '03:00');
        config()->set('production.backup.expected_timezone', 'Europe/Rome');
        config()->set('production.backup.local_only_disks', ['local', 'public', 'backup']);
        config()->set('production.backup.require_non_windows_server', false);
        config()->set('filesystems.disks.s3', ['driver' => 's3']);
        config()->set('backup.backup.destination.disks', ['s3']);
        config()->set('backup.backup.password', 'test-backup-password');
        config()->set('backup.notifications.email_configured', true);
        config()->set('backup.notifications.mail.to', 'backups@doomsday-clock.com');
        config()->set('backup.schedule.time', '03:00');
        config()->set('backup.schedule.timezone', 'Europe/Rome');
        config()->set('production.analytics.google_tag_manager_id', '');
        config()->set('production.analytics.google_analytics_id', '');
        config()->set('production.analytics.legal_facts', [
            'controller' => '[DA VERIFICARE]',
            'contact' => '[DA VERIFICARE]',
            'vendor' => '[DA VERIFICARE]',
            'retention' => '[DA VERIFICARE]',
        ]);
        config()->set('production.analytics.placeholder_markers', ['[da verificare]', 'example.com', 'placeholder', 'tbd', 'todo']);
        config()->set('production.analytics.legal_policy_path', $files['policy']);
        config()->set('production.analytics.legal_policy_placeholder_markers', [
            'final privacy contact details must be completed by the controller before production launch',
            'details must be updated when real service ids are added',
        ]);

        return array_values($files);
    }
}
