<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('backup:run')
    ->dailyAt((string) config('backup.schedule.time', '03:00'))
    ->timezone((string) config('backup.schedule.timezone', 'Europe/Rome'))
    ->withoutOverlapping(360)
    ->name('backup:daily')
    ->appendOutputTo(storage_path('logs/backup.log'));

Schedule::command('backup:clean')
    ->dailyAt((string) config('backup.schedule.cleanup_time', '03:30'))
    ->timezone((string) config('backup.schedule.timezone', 'Europe/Rome'))
    ->withoutOverlapping(120)
    ->name('backup:cleanup')
    ->appendOutputTo(storage_path('logs/backup.log'));

Schedule::command('backup:monitor')
    ->dailyAt((string) config('backup.schedule.monitor_time', '04:00'))
    ->timezone((string) config('backup.schedule.timezone', 'Europe/Rome'))
    ->withoutOverlapping(60)
    ->name('backup:monitor')
    ->appendOutputTo(storage_path('logs/backup.log'));
