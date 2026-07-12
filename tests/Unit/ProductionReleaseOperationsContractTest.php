<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class ProductionReleaseOperationsContractTest extends TestCase
{
    public function test_environment_example_preserves_origin_and_blank_analytics_ids(): void
    {
        $environment = (string) file_get_contents(__DIR__.'/../../.env.example');

        $this->assertStringContainsString('# Production override: APP_URL=https://doomsday-clock.com', $environment);
        $this->assertStringContainsString('# Production GA4: VITE_GOOGLE_ANALYTICS_ID=G-2L9QPGWKVL', $environment);
        $this->assertMatchesRegularExpression('/^VITE_GOOGLE_TAG_MANAGER_ID=$/m', $environment);
        $this->assertMatchesRegularExpression('/^VITE_GOOGLE_ANALYTICS_ID=$/m', $environment);
        $this->assertDoesNotMatchRegularExpression('/^VITE_GOOGLE_TAG_MANAGER_ID=GTM-/m', $environment);
        $this->assertDoesNotMatchRegularExpression('/^VITE_GOOGLE_ANALYTICS_ID=G-/m', $environment);
        $this->assertMatchesRegularExpression('/^BACKUP_TIME=03:00$/m', $environment);
        $this->assertMatchesRegularExpression('/^BACKUP_TIMEZONE=Europe\/Rome$/m', $environment);
        $this->assertMatchesRegularExpression('/^BACKUP_ARCHIVE_PASSWORD=$/m', $environment);
        $this->assertMatchesRegularExpression('/^BACKUP_NOTIFICATION_EMAIL=$/m', $environment);
        $this->assertStringContainsString('Production must include an off-host disk', $environment);
    }

    public function test_validator_is_read_only_and_only_the_approved_backup_tasks_are_scheduled(): void
    {
        $command = (string) file_get_contents(__DIR__.'/../../app/Console/Commands/ValidateProductionCommand.php');
        $consoleRoutes = (string) file_get_contents(__DIR__.'/../../routes/console.php');

        foreach (['file_put_contents(', 'unlink(', 'rename(', 'Artisan::call(', 'Process::run(', 'Http::'] as $mutationMarker) {
            $this->assertStringNotContainsString($mutationMarker, $command);
        }

        foreach (['backup:run', 'backup:clean', 'backup:monitor', "config('backup.schedule.time', '03:00')", "config('backup.schedule.timezone', 'Europe/Rome')", 'withoutOverlapping'] as $scheduledMarker) {
            $this->assertStringContainsString($scheduledMarker, $consoleRoutes);
        }

        $this->assertStringNotContainsString('countdowns:refresh-content-sources', $consoleRoutes);
        $this->assertStringNotContainsString('Schedule::call(', $consoleRoutes);
    }

    public function test_release_documents_exist_and_keep_external_operations_blocked(): void
    {
        $documents = [
            'environment-matrix.md',
            'deploy.md',
            'rollback.md',
            'backup-restore.md',
            'monitoring.md',
            'release-evidence.md',
        ];

        foreach ($documents as $document) {
            $path = __DIR__.'/../../docs/production/'.$document;
            $this->assertFileExists($path);
            $content = (string) file_get_contents($path);
            $this->assertStringContainsString('BLOCKED', $content, $document.' must retain unresolved external gates.');
            $this->assertStringNotContainsString('php artisan migrate:fresh', $content);
            $this->assertStringNotContainsString('php artisan db:seed', $content);
        }
    }

    public function test_release_evidence_acquires_the_approved_community_delta_without_runtime_dependency_claims(): void
    {
        $evidence = (string) file_get_contents(__DIR__.'/../../docs/production/release-evidence.md');

        foreach ([
            'resources/js/Components/Doomsday/CommunityLinks.vue',
            'resources/js/Components/Doomsday/SiteHeader.vue',
            'resources/js/Components/Doomsday/AboutClosingBand.vue',
            'tests/Unit/DoomsdayPublicCopyTest.php',
            'https://discord.gg/NmKXDzwzK',
            'https://t.me/doomsdayclockofficial',
        ] as $approvedEvidence) {
            $this->assertStringContainsString($approvedEvidence, $evidence);
        }

        $this->assertStringContainsString('static HTTPS links', $evidence);
        $this->assertStringContainsString('icon-only controls in the public topbar', $evidence);
        $this->assertStringContainsString('<CommunityLinks placement="header" />', $evidence);
        $this->assertStringContainsString('outside the primary `<nav>`', $evidence);
        $this->assertStringContainsString('not a secret', $evidence);
        $this->assertStringContainsString('not an analytics integration', $evidence);
        $this->assertStringContainsString('not an external health dependency', $evidence);
    }
}
