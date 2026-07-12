<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use RuntimeException;
use SimoneBianco\Patches\Facades\Patches;

final class DoomsdaySeeder extends Seeder
{
    /** @var array<int, array<int, string>> */
    private const PATCH_GROUPS = [
        [
            'countdowns/taiwan_invasion/2026_07_09_010000_prepare_taiwan_invasion_seed',
            'countdowns/taiwan_invasion/2026_07_09_010010_seed_taiwan_invasion_countdown',
            'countdowns/taiwan_invasion/2026_07_09_010020_seed_taiwan_invasion_projections',
            'countdowns/taiwan_invasion/2026_07_09_010030_seed_taiwan_invasion_visualizations',
            'countdowns/taiwan_invasion/2026_07_09_010040_seed_taiwan_invasion_news',
            'countdowns/taiwan_invasion/2026_07_09_010050_seed_taiwan_invasion_content_sources',
            'countdowns/taiwan_invasion/2026_07_09_010060_seed_taiwan_invasion_initiatives',
        ],
        [
            'countdowns/europe_war_countdown/2026_07_11_020000_prepare_europe_war_countdown',
            'countdowns/europe_war_countdown/2026_07_11_020010_seed_europe_war_countdown',
            'countdowns/europe_war_countdown/2026_07_11_020020_seed_europe_war_countdown_projections',
            'countdowns/europe_war_countdown/2026_07_11_020030_seed_europe_war_countdown_visualizations',
            'countdowns/europe_war_countdown/2026_07_11_020040_seed_europe_war_countdown_news',
            'countdowns/europe_war_countdown/2026_07_11_020050_seed_europe_war_countdown_content_sources',
            'countdowns/europe_war_countdown/2026_07_11_020060_seed_europe_war_countdown_initiatives',
        ],
        [
            'countdowns/ai_job_apocalypse/2026_07_11_021000_prepare_ai_job_apocalypse',
            'countdowns/ai_job_apocalypse/2026_07_11_021010_seed_ai_job_apocalypse_countdown',
            'countdowns/ai_job_apocalypse/2026_07_11_021020_seed_ai_job_apocalypse_projections',
            'countdowns/ai_job_apocalypse/2026_07_11_021030_seed_ai_job_apocalypse_visualizations',
            'countdowns/ai_job_apocalypse/2026_07_11_021040_seed_ai_job_apocalypse_news',
            'countdowns/ai_job_apocalypse/2026_07_11_021050_seed_ai_job_apocalypse_content_sources',
            'countdowns/ai_job_apocalypse/2026_07_11_021060_seed_ai_job_apocalypse_initiatives',
        ],
        [
            'countdowns/sixth_mass_extinction/2026_07_11_022000_prepare_sixth_mass_extinction_seed',
            'countdowns/sixth_mass_extinction/2026_07_11_022010_seed_sixth_mass_extinction_countdown',
            'countdowns/sixth_mass_extinction/2026_07_11_022020_seed_sixth_mass_extinction_projections',
            'countdowns/sixth_mass_extinction/2026_07_11_022030_seed_sixth_mass_extinction_visualizations',
            'countdowns/sixth_mass_extinction/2026_07_11_022040_seed_sixth_mass_extinction_news',
            'countdowns/sixth_mass_extinction/2026_07_11_022050_seed_sixth_mass_extinction_content_sources',
            'countdowns/sixth_mass_extinction/2026_07_11_022060_seed_sixth_mass_extinction_initiatives',
        ],
        [
            'countdowns/antibiotic_apocalypse/2026_07_11_023000_prepare_antibiotic_apocalypse',
            'countdowns/antibiotic_apocalypse/2026_07_11_023010_seed_antibiotic_apocalypse_countdown',
            'countdowns/antibiotic_apocalypse/2026_07_11_023020_seed_antibiotic_apocalypse_projections',
            'countdowns/antibiotic_apocalypse/2026_07_11_023030_seed_antibiotic_apocalypse_visualizations',
            'countdowns/antibiotic_apocalypse/2026_07_11_023040_seed_antibiotic_apocalypse_news',
            'countdowns/antibiotic_apocalypse/2026_07_11_023050_seed_antibiotic_apocalypse_content_sources',
            'countdowns/antibiotic_apocalypse/2026_07_11_023060_seed_antibiotic_apocalypse_initiatives',
        ],
        [
            'countdowns/unlivable_heat/2026_07_11_024000_prepare_unlivable_heat',
            'countdowns/unlivable_heat/2026_07_11_024010_seed_unlivable_heat_countdown',
            'countdowns/unlivable_heat/2026_07_11_024020_seed_unlivable_heat_projections',
            'countdowns/unlivable_heat/2026_07_11_024030_seed_unlivable_heat_visualizations',
            'countdowns/unlivable_heat/2026_07_11_024040_seed_unlivable_heat_news',
            'countdowns/unlivable_heat/2026_07_11_024050_seed_unlivable_heat_content_sources',
            'countdowns/unlivable_heat/2026_07_11_024060_seed_unlivable_heat_initiatives',
        ],
    ];

    public function run(): void
    {
        foreach (self::PATCH_GROUPS as $patches) {
            foreach ($patches as $patch) {
                $this->runSinglePatch($patch);
            }
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
