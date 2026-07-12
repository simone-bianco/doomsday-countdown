<?php

declare(strict_types=1);

namespace Tests\Feature;

use Composer\InstalledVersions;
use Spatie\Backup\Notifications\Notifications\BackupHasFailedNotification;
use Tests\TestCase;

final class ProductionBackupScheduleTest extends TestCase
{
    public function test_spatie_backup_is_installed_with_safe_local_defaults_and_a_three_am_schedule(): void
    {
        $this->assertTrue(InstalledVersions::isInstalled('spatie/laravel-backup'));
        $this->assertSame('03:00', config('backup.schedule.time'));
        $this->assertSame('Europe/Rome', config('backup.schedule.timezone'));
        $this->assertSame('03:30', config('backup.schedule.cleanup_time'));
        $this->assertSame('04:00', config('backup.schedule.monitor_time'));

        $this->assertSame(['backup'], config('backup.backup.destination.disks'));
        $this->assertSame(storage_path('app/backups'), config('filesystems.disks.backup.root'));
        $this->assertTrue((bool) config('filesystems.disks.backup.throw'));

        $this->assertSame([
            storage_path('app/private'),
            storage_path('app/public'),
        ], config('backup.backup.source.files.include'));
        $this->assertSame([
            storage_path('app/backups'),
            storage_path('app/backup-temp'),
        ], config('backup.backup.source.files.exclude'));
        $this->assertSame(storage_path('app'), config('backup.backup.source.files.relative_path'));
        $this->assertNotContains(base_path(), config('backup.backup.source.files.include'));

        $this->assertFalse((bool) config('backup.notifications.email_configured'));
        $this->assertSame([], config('backup.notifications.notifications.'.BackupHasFailedNotification::class));
    }

    public function test_scheduler_registers_backup_run_cleanup_and_monitor_without_content_refresh(): void
    {
        $consoleRoutes = (string) file_get_contents(base_path('routes/console.php'));

        foreach ([
            "Schedule::command('backup:run')",
            "config('backup.schedule.time', '03:00')",
            "Schedule::command('backup:clean')",
            "Schedule::command('backup:monitor')",
            "config('backup.schedule.timezone', 'Europe/Rome')",
            'withoutOverlapping',
            "storage_path('logs/backup.log')",
        ] as $marker) {
            $this->assertStringContainsString($marker, $consoleRoutes);
        }

        $this->assertStringNotContainsString('countdowns:refresh-content-sources', $consoleRoutes);
    }

    public function test_official_discord_and_telegram_icons_are_public_runtime_assets(): void
    {
        $component = (string) file_get_contents(resource_path('js/Components/Doomsday/CommunityLinks.vue'));

        foreach (['discord', 'telegram'] as $brand) {
            $path = public_path("images/community/{$brand}.png");
            $this->assertFileExists($path);
            $size = getimagesize($path);
            $this->assertIsArray($size);
            $this->assertSame(128, $size[0]);
            $this->assertSame(128, $size[1]);
            $this->assertSame('image/png', $size['mime']);
            $this->assertStringContainsString("/images/community/{$brand}.png", $component);
        }

        $this->assertStringNotContainsString('MessagesSquare', $component);
        $this->assertStringNotContainsString('icon: Send', $component);
        $this->assertStringNotContainsString('z-docs', $component);
    }
}
