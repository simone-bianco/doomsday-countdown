<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use RuntimeException;
use SimoneBianco\Patches\Facades\Patches;

final class DoomsdaySeeder extends Seeder
{
    /** @var array<int, string> */
    private const TAIWAN_INVASION_PATCHES = [
        'countdowns/taiwan_invasion/2026_07_09_010000_prepare_taiwan_invasion_seed',
        'countdowns/taiwan_invasion/2026_07_09_010010_seed_taiwan_invasion_countdown',
        'countdowns/taiwan_invasion/2026_07_09_010020_seed_taiwan_invasion_projections',
        'countdowns/taiwan_invasion/2026_07_09_010030_seed_taiwan_invasion_visualizations',
        'countdowns/taiwan_invasion/2026_07_09_010040_seed_taiwan_invasion_news',
        'countdowns/taiwan_invasion/2026_07_09_010050_seed_taiwan_invasion_content_sources',
        'countdowns/taiwan_invasion/2026_07_09_010060_seed_taiwan_invasion_initiatives',
    ];

    public function run(): void
    {
        foreach (self::TAIWAN_INVASION_PATCHES as $patch) {
            $this->runSinglePatch($patch);
        }
    }

    private function runSinglePatch(string $patch): void
    {
        $success = Patches::runSinglePatch(
            $patch,
            fn (string $message): null => $this->command?->line($message),
        );
        if (! $success) {
            throw new RuntimeException("Failed to run Doomsday data patch [{$patch}].");
        }
    }
}
