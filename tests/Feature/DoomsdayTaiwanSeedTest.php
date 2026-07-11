<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProjectionType;
use App\Enums\VisualizationType;
use App\Models\Countdown;
use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdayTaiwanSeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_seed_creates_only_taiwan_invasion_countdown(): void
    {
        $this->seed(DoomsdaySeeder::class);
        $this->assertSame(['taiwan-invasion'], Countdown::query()->pluck('slug')->all());
        $this->assertSame(1, Countdown::query()->published()->count());
        foreach (['society-collapse', 'fall-of-europe', 'extreme-heat-breakpoint', 'uninhabitable-earth'] as $oldSlug) {
            $this->assertFalse(Countdown::query()->where('slug', $oldSlug)->exists(), 'Old sample countdown still exists: '.$oldSlug);
        }
    }

    public function test_taiwan_seed_has_required_real_data_relations_and_asset(): void
    {
        $this->seed(DoomsdaySeeder::class);
        $countdown = Countdown::query()
            ->where('slug', 'taiwan-invasion')
            ->with(['projections.visualizations', 'visualizations', 'news', 'initiatives'])
            ->firstOrFail();
        $this->assertSame('Taiwan Invasion', $countdown->title['en']);
        $this->assertSame('Invasione di Taiwan', $countdown->title['it']);
        $this->assertSame('images/doomsday/taiwan_invasion.png', $countdown->image_path);
        $this->assertFileExists(public_path('images/doomsday/taiwan_invasion.png'));
        $this->assertCount(3, $countdown->projections);
        $this->assertSame([ProjectionType::Optimistic, ProjectionType::Neutral, ProjectionType::Pessimistic], $countdown->projections->sortBy('sort_order')->pluck('type')->values()->all());
        $projectionChart = $countdown->projections
            ->flatMap(fn ($projection) => $projection->visualizations)
            ->firstWhere('key', 'projection_curve');
        $this->assertNotNull($projectionChart);
        $this->assertSame(2, $projectionChart->schema_version);
        $this->assertSame('temporal', $projectionChart->payload['axes']['x']['type']);
        $this->assertSame('%', $projectionChart->payload['axes']['y']['unit']);
        $this->assertSame(['Pessimistic', 'Optimistic', 'Neutral'], collect($projectionChart->payload['series'])->pluck('name')->all());
        $this->assertSame(['#ff2a23', '#22c55e', '#38bdf8'], collect($projectionChart->payload['series'])->pluck('color')->all());
        $this->assertGreaterThanOrEqual(6, $countdown->visualizations->count());

        foreach ($countdown->visualizations as $visualization) {
            if (! in_array($visualization->type, [VisualizationType::Line, VisualizationType::Area, VisualizationType::Bar], true)) {
                continue;
            }

            $this->assertSame(2, $visualization->schema_version, $visualization->key);
            $this->assertArrayHasKey('axes', $visualization->payload, $visualization->key);
            $this->assertArrayHasKey('sources', $visualization->payload, $visualization->key);
            $this->assertNotEmpty($visualization->payload['sources'], $visualization->key);
            foreach ($visualization->payload['sources'] as $source) {
                $this->assertStringStartsWith('https://', $source, $visualization->key);
            }
            foreach ($visualization->payload['series'] as $series) {
                if (is_array($series)) {
                    $this->assertArrayNotHasKey('unit', $series, $visualization->key);
                    $this->assertArrayNotHasKey('format', $series, $visualization->key);
                }
            }
        }

        $this->assertSame(VisualizationType::Bar, $countdown->visualizations->firstWhere('key', 'economic_exposure')?->type);
        $this->assertSame(VisualizationType::Bar, $countdown->visualizations->firstWhere('key', 'scenario_gdp_shock')?->type);
        $energyResilience = $countdown->visualizations->firstWhere('key', 'energy_resilience');
        $this->assertSame(VisualizationType::Kpi, $energyResilience?->type);
        $this->assertSame(['95%', '99%', '12 days'], collect($energyResilience?->payload['items'] ?? [])->pluck('value')->all());
        $this->assertGreaterThanOrEqual(6, $countdown->news->whereNotNull('source_url')->count());
        $this->assertGreaterThanOrEqual(5, $countdown->initiatives->count());
        foreach ($countdown->news as $news) {
            $this->assertNotNull($news->source_url);
            $this->assertStringStartsWith('https://', (string) $news->source_url);
            $this->assertStringNotContainsString('example.org', (string) $news->source_url);
        }
        foreach ($countdown->initiatives as $initiative) {
            $this->assertStringStartsWith('https://', $initiative->url);
            $this->assertStringNotContainsString('example.org', $initiative->url);
        }
    }

    public function test_public_seed_sources_do_not_contain_old_sample_content(): void
    {
        $paths = [
            base_path('database/seeders/DoomsdaySeeder.php'),
            base_path('database/patches/countdowns/taiwan_invasion/_shared.php'),
            ...glob(base_path('database/patches/countdowns/taiwan_invasion/*/patch.php')),
            ...glob(base_path('database/patches/countdowns/taiwan_invasion/*/data.php')),
            base_path('app/Services/Doomsday/CountdownPublicDataService.php'),
            base_path('resources/js/Pages/Doomsday/Home.vue'),
            base_path('resources/js/Pages/Doomsday/About.vue'),
            base_path('resources/js/Components/Doomsday/NewsSection.vue'),
        ];
        $content = '';
        foreach ($paths as $path) {
            $content .= strtolower((string) file_get_contents($path));
        }
        foreach (['society-collapse', 'fall-of-europe', 'extreme-heat-breakpoint', 'uninhabitable-earth', 'example.org', 'daily monitor', 'global desk', 'sample data', 'sample scenario', 'dati campione'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $content);
        }
    }
}
