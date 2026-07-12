<?php

declare(strict_types=1);

use App\Models\Countdown;
use App\Models\Projection;
use App\Models\Visualization;
use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    /** @var array<string, string> */
    private const COUNTDOWN_DATA = [
        'ai-job-apocalypse' => 'ai_job_apocalypse/2026_07_11_021010_seed_ai_job_apocalypse_countdown/data.php',
        'antibiotic-apocalypse' => 'antibiotic_apocalypse/2026_07_11_023010_seed_antibiotic_apocalypse_countdown/data.php',
        'europe-war-countdown' => 'europe_war_countdown/2026_07_11_020010_seed_europe_war_countdown/data.php',
        'sixth-mass-extinction' => 'sixth_mass_extinction/2026_07_11_022010_seed_sixth_mass_extinction_countdown/data.php',
        'taiwan-invasion' => 'taiwan_invasion/2026_07_09_010010_seed_taiwan_invasion_countdown/data.php',
        'unlivable-heat' => 'unlivable_heat/2026_07_11_024010_seed_unlivable_heat_countdown/data.php',
    ];

    /** @var array<string, string> */
    private const VISUALIZATION_DATA = [
        'ai-job-apocalypse' => 'ai_job_apocalypse/2026_07_11_021030_seed_ai_job_apocalypse_visualizations/data.php',
        'antibiotic-apocalypse' => 'antibiotic_apocalypse/2026_07_11_023030_seed_antibiotic_apocalypse_visualizations/data.php',
        'europe-war-countdown' => 'europe_war_countdown/2026_07_11_020030_seed_europe_war_countdown_visualizations/data.php',
        'sixth-mass-extinction' => 'sixth_mass_extinction/2026_07_11_022030_seed_sixth_mass_extinction_visualizations/data.php',
        'taiwan-invasion' => 'taiwan_invasion/2026_07_09_010030_seed_taiwan_invasion_visualizations/data.php',
        'unlivable-heat' => 'unlivable_heat/2026_07_11_024030_seed_unlivable_heat_visualizations/data.php',
    ];

    public function up(): void
    {
        foreach ($this->copy() as $slug => $copy) {
            $countdown = Countdown::query()->where('slug', $slug)->first();
            if (! $countdown instanceof Countdown) {
                throw new RuntimeException("Countdown [{$slug}] must exist before applying editorial copy.");
            }

            $countdown->update([
                'summary' => $this->mergeLocalized($countdown->summary, $copy['summary'] ?? []),
                'description' => $this->mergeLocalized($countdown->description, $copy['description'] ?? []),
            ]);

            $visualizations = $copy['visualizations'] ?? [];
            foreach ($this->visualizations($countdown) as $visualization) {
                $replacement = $visualizations[$visualization->key] ?? null;
                if (! is_array($replacement)) {
                    continue;
                }

                $visualization->update([
                    'description' => $this->mergeLocalized($visualization->description, $replacement['description'] ?? []),
                    'explanation' => $this->mergeLocalized($visualization->explanation, $replacement['explanation'] ?? []),
                ]);
            }
        }
    }

    public function down(): void
    {
        foreach (array_keys($this->copy()) as $slug) {
            $countdown = Countdown::query()->where('slug', $slug)->first();
            if (! $countdown instanceof Countdown) {
                continue;
            }

            $legacyCountdown = $this->legacyCountdown($slug);
            $countdown->update([
                'summary' => $legacyCountdown['summary'],
                'description' => $legacyCountdown['description'],
            ]);

            $legacyVisualizations = $this->legacyVisualizations($slug);
            foreach ($this->visualizations($countdown) as $visualization) {
                $legacy = $legacyVisualizations[$visualization->key] ?? null;
                if (! is_array($legacy)) {
                    continue;
                }

                $visualization->update([
                    'description' => $legacy['description'] ?? null,
                    'explanation' => null,
                ]);
            }
        }
    }

    /** @return array<string, array<string, mixed>> */
    private function copy(): array
    {
        $json = file_get_contents(__DIR__.'/data.json');
        if ($json === false) {
            throw new RuntimeException('Editorial copy data could not be read.');
        }

        $decoded = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        return is_array($decoded) ? $decoded : [];
    }

    /** @return array<int, Visualization> */
    private function visualizations(Countdown $countdown): array
    {
        $items = $countdown->visualizations()->get()->all();
        $countdown->projections()->with('visualizations')->get()->each(
            static function (Projection $projection) use (&$items): void {
                array_push($items, ...$projection->visualizations->all());
            },
        );

        return $items;
    }

    /** @param array<string, mixed>|null $current @param array<string, mixed> $replacement @return array<string, mixed> */
    private function mergeLocalized(?array $current, array $replacement): array
    {
        return array_replace($current ?? [], $replacement);
    }

    /** @return array<string, mixed> */
    private function legacyCountdown(string $slug): array
    {
        $relative = self::COUNTDOWN_DATA[$slug] ?? null;
        if ($relative === null) {
            throw new RuntimeException("Legacy countdown data path is missing for [{$slug}].");
        }

        $data = require dirname(__DIR__, 2).'/'.$relative;

        return $data->countdown();
    }

    /** @return array<string, array<string, mixed>> */
    private function legacyVisualizations(string $slug): array
    {
        $relative = self::VISUALIZATION_DATA[$slug] ?? null;
        if ($relative === null) {
            throw new RuntimeException("Legacy visualization data path is missing for [{$slug}].");
        }

        $data = require dirname(__DIR__, 2).'/'.$relative;
        $items = method_exists($data, 'visualizations') ? $data->visualizations() : [];
        if (method_exists($data, 'projectionCurveVisualization')) {
            array_unshift($items, $data->projectionCurveVisualization());
        }

        $indexed = [];
        foreach ($items as $item) {
            $indexed[(string) $item['key']] = $item;
        }

        return $indexed;
    }
};
