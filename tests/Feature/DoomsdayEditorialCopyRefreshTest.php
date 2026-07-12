<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Countdown;
use App\Models\Visualization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

final class DoomsdayEditorialCopyRefreshTest extends TestCase
{
    use RefreshDatabase;

    /** @var array<string, string> */
    private const COUNTDOWN_DIRECTORIES = [
        'ai-job-apocalypse' => 'ai_job_apocalypse',
        'antibiotic-apocalypse' => 'antibiotic_apocalypse',
        'europe-war-countdown' => 'europe_war_countdown',
        'sixth-mass-extinction' => 'sixth_mass_extinction',
        'taiwan-invasion' => 'taiwan_invasion',
        'unlivable-heat' => 'unlivable_heat',
    ];

    public function test_editorial_patch_updates_countdown_subtitles_and_every_visualization_explanation(): void
    {
        $this->seedOriginalCountdowns();
        $copy = $this->copy();
        $this->editorialPatch()->up();

        foreach ($copy as $slug => $expected) {
            $countdown = Countdown::query()
                ->where('slug', $slug)
                ->with(['visualizations', 'projections.visualizations'])
                ->firstOrFail();

            foreach (['en', 'it'] as $locale) {
                $this->assertSame($expected['summary'][$locale], $countdown->summary[$locale], $slug.':summary:'.$locale);
                $this->assertSame($expected['description'][$locale], $countdown->description[$locale], $slug.':description:'.$locale);
                $this->assertGreaterThan(70, mb_strlen($countdown->summary[$locale]), $slug.':summary-length:'.$locale);
                $this->assertGreaterThan(180, mb_strlen($countdown->description[$locale]), $slug.':description-length:'.$locale);
            }

            $visualizations = collect($countdown->visualizations)
                ->merge($countdown->projections->flatMap->visualizations)
                ->keyBy('key');

            foreach ($expected['visualizations'] as $key => $visualizationCopy) {
                $visualization = $visualizations->get($key);
                $this->assertInstanceOf(Visualization::class, $visualization, $slug.':'.$key);

                foreach (['en', 'it'] as $locale) {
                    $description = $visualization->description[$locale] ?? '';
                    $explanation = $visualization->explanation[$locale] ?? '';
                    $reasoning = $visualization->reasoning[$locale] ?? '';

                    $this->assertSame($visualizationCopy['description'][$locale], $description, $slug.':'.$key.':description:'.$locale);
                    $this->assertSame($visualizationCopy['explanation'][$locale], $explanation, $slug.':'.$key.':explanation:'.$locale);
                    $this->assertNotSame($description, $explanation, $slug.':'.$key.':description-vs-explanation:'.$locale);
                    $this->assertNotSame($reasoning, $explanation, $slug.':'.$key.':reasoning-vs-explanation:'.$locale);
                    $this->assertGreaterThan(70, mb_strlen($description), $slug.':'.$key.':description-length:'.$locale);
                    $this->assertGreaterThan(120, mb_strlen($explanation), $slug.':'.$key.':explanation-length:'.$locale);
                }
            }
        }
    }

    public function test_editorial_patch_is_idempotent_and_rollback_restores_original_copy(): void
    {
        $this->seedOriginalCountdowns();
        $before = $this->snapshot();
        $patch = $this->editorialPatch();

        $patch->up();
        $first = $this->snapshot();
        $patch->up();
        $this->assertSame($first, $this->snapshot());

        $patch->down();
        $afterRollback = $this->snapshot();

        foreach (array_keys(self::COUNTDOWN_DIRECTORIES) as $slug) {
            $this->assertSame($before[$slug]['summary'], $afterRollback[$slug]['summary'], $slug.':summary rollback');
            $this->assertSame($before[$slug]['description'], $afterRollback[$slug]['description'], $slug.':description rollback');
            foreach ($afterRollback[$slug]['visualizations'] as $visualization) {
                $this->assertNull($visualization['explanation'], $slug.':'.$visualization['key'].':explanation rollback');
            }
        }
    }

    private function seedOriginalCountdowns(): void
    {
        foreach (self::COUNTDOWN_DIRECTORIES as $directory) {
            $paths = glob(base_path('database/patches/countdowns/'.$directory.'/*/patch.php')) ?: [];
            sort($paths, SORT_STRING);

            if ($paths === []) {
                throw new RuntimeException('No patch modules found for '.$directory);
            }

            foreach ($paths as $path) {
                (require $path)->up();
            }
        }
    }

    /** @return array<string, mixed> */
    private function copy(): array
    {
        $json = file_get_contents(base_path('database/patches/countdowns/zz_editorial_refresh/2026_07_13_000000_refresh_countdown_editorial_copy/data.json'));
        if ($json === false) {
            throw new RuntimeException('Editorial copy fixture is missing.');
        }

        $copy = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

        return is_array($copy) ? $copy : [];
    }

    private function editorialPatch(): object
    {
        return require base_path('database/patches/countdowns/zz_editorial_refresh/2026_07_13_000000_refresh_countdown_editorial_copy/patch.php');
    }

    /** @return array<string, mixed> */
    private function snapshot(): array
    {
        $snapshot = [];
        foreach (array_keys(self::COUNTDOWN_DIRECTORIES) as $slug) {
            $countdown = Countdown::query()
                ->where('slug', $slug)
                ->with(['visualizations', 'projections.visualizations'])
                ->firstOrFail();

            $visualizations = collect($countdown->visualizations)
                ->merge($countdown->projections->flatMap->visualizations)
                ->sortBy('key')
                ->values()
                ->map(static fn (Visualization $visualization): array => [
                    'key' => $visualization->key,
                    'description' => $visualization->description,
                    'explanation' => $visualization->explanation,
                ])
                ->all();

            $snapshot[$slug] = [
                'summary' => $countdown->summary,
                'description' => $countdown->description,
                'visualizations' => $visualizations,
            ];
        }

        return $snapshot;
    }
}
