<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProjectionType;
use App\Enums\VisualizationType;
use App\Models\Countdown;
use App\Services\Doomsday\CountdownPublicDataService;
use Carbon\CarbonImmutable;
use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdayTaiwanSeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_seed_catalog_contains_taiwan_invasion_and_five_additional_countdowns(): void
    {
        $this->seed(DoomsdaySeeder::class);
        $this->assertSame([
            'taiwan-invasion',
            'europe-war-countdown',
            'ai-job-apocalypse',
            'sixth-mass-extinction',
            'antibiotic-apocalypse',
            'unlivable-heat',
        ], Countdown::query()->orderBy('sort_order')->pluck('slug')->all());
        $this->assertSame(6, Countdown::query()->published()->count());
        foreach (['society-collapse', 'fall-of-europe', 'extreme-heat-breakpoint', 'uninhabitable-earth'] as $oldSlug) {
            $this->assertFalse(Countdown::query()->where('slug', $oldSlug)->exists(), 'Old sample countdown still exists: '.$oldSlug);
        }
    }

    public function test_taiwan_seed_has_required_real_data_relations_and_asset(): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-07-12 00:00:00', 'UTC'));
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

        $optimistic = $countdown->projections->firstWhere('type', ProjectionType::Optimistic);
        $neutral = $countdown->projections->firstWhere('type', ProjectionType::Neutral);
        $pessimistic = $countdown->projections->firstWhere('type', ProjectionType::Pessimistic);
        $this->assertSame('2032-05-20', $optimistic?->target_date?->format('Y-m-d'));
        $this->assertSame('2028-05-20', $neutral?->target_date?->format('Y-m-d'));
        $this->assertSame('2027-12-31', $pessimistic?->target_date?->format('Y-m-d'));
        $this->assertSame($neutral?->target_date?->toDateTimeString(), $countdown->target_date?->toDateTimeString());
        $this->assertTrue($pessimistic?->target_date?->lessThan($neutral?->target_date));
        $this->assertTrue($neutral?->target_date?->lessThan($optimistic?->target_date));
        foreach ($countdown->projections as $projection) {
            $this->assertStringContainsString('scenario anchors', $projection->methodology['assumption']);
            $this->assertStringContainsString('not official or empirical', $projection->methodology['probability_score_note']);
            $this->assertStringContainsString('Pessimistic', $projection->methodology['default_selection']);
            $this->assertStringContainsString('exact target second remains active', $projection->methodology['default_selection']);
            $this->assertGreaterThanOrEqual(10, count($projection->methodology['sources']));
        }

        $index = app(CountdownPublicDataService::class)->indexPayload('en');
        $indexItem = collect($index['countdowns'])->firstWhere('slug', 'taiwan-invasion');
        $this->assertSame('pessimistic', $indexItem['main_projection']['type']);
        $this->assertStringStartsWith('2027-12-31T23:59:59', $indexItem['timer']['target_date']);

        $overview = $this->getJson(route('countdowns.data.overview', ['slug' => 'taiwan-invasion', 'lang' => 'en']))
            ->assertOk()
            ->json('data');
        $this->assertSame('pessimistic', $overview['main_projection']['type']);
        $this->assertStringStartsWith('2027-12-31T23:59:59', $overview['timer']['target_date']);

        $forecasts = $this->getJson(route('countdowns.data.forecasts', ['slug' => 'taiwan-invasion', 'lang' => 'en']))
            ->assertOk()
            ->json('data');
        $publicDates = collect($forecasts['projections'])->mapWithKeys(
            fn (array $projection): array => [$projection['type'] => $projection['target_date']],
        );
        $this->assertStringStartsWith('2027-12-31T23:59:59', $publicDates['pessimistic']);
        $this->assertStringStartsWith('2028-05-20T00:00:00', $publicDates['neutral']);
        $this->assertStringStartsWith('2032-05-20T00:00:00', $publicDates['optimistic']);
        $this->assertNotNull($forecasts['projection_chart']);
        $this->assertSame('projection_curve', $forecasts['projection_chart']['key']);

        $projectionChart = $countdown->projections
            ->flatMap(fn ($projection) => $projection->visualizations)
            ->firstWhere('key', 'projection_curve');
        $this->assertNotNull($projectionChart);
        $this->assertSame(2, $projectionChart->schema_version);
        $this->assertSame('temporal', $projectionChart->payload['axes']['x']['type']);
        $this->assertSame('index points', $projectionChart->payload['axes']['y']['unit']);
        $this->assertSame('decimal', $projectionChart->payload['axes']['y']['format']);
        $this->assertNotEmpty($projectionChart->sources);
        $this->assertNotSame('', trim((string) ($projectionChart->reasoning['en'] ?? '')));
        $this->assertArrayNotHasKey('sources', $projectionChart->payload);
        $this->assertArrayNotHasKey('reasoning', $projectionChart->payload);
        $this->assertSame(['Pessimistic', 'Neutral', 'Optimistic'], collect($projectionChart->payload['series'])->pluck('name')->all());
        $this->assertSame(['#ff2a23', '#38bdf8', '#22c55e'], collect($projectionChart->payload['series'])->pluck('color')->all());
        $this->assertSame(['2026-Q3', '2027-Q4', '2028-Q2', '2030-Q4', '2032-Q2'], $projectionChart->payload['labels']);

        $visualizationData = require base_path('database/patches/countdowns/taiwan_invasion/2026_07_09_010030_seed_taiwan_invasion_visualizations/data.php');
        $riskInputs = $visualizationData->riskInputs();
        $this->assertCount(7, $riskInputs);
        $this->assertStringContainsString('round(clamp(sum', $visualizationData->riskFormula());
        $this->assertEqualsCanonicalizing(array_values(array_unique(array_column($riskInputs, 'source_url'))), $projectionChart->sources);

        $expectedScores = ['Pessimistic' => [], 'Neutral' => [], 'Optimistic' => []];
        foreach ($riskInputs as $input) {
            foreach (['signal_key', 'date', 'date_type', 'source_url', 'source_fact', 'source_value', 'normalization', 'direction', 'weight', 'weight_rationale', 'scenario_spread', 'scenario_adjustment_rule', 'checkpoints'] as $required) {
                $this->assertArrayHasKey($required, $input, $input['signal_key'] ?? $required);
            }
            $this->assertStringStartsWith('https://', $input['source_url']);
            $this->assertContains($input['direction'], ['risk_up', 'risk_down', 'context_only']);
            $this->assertGreaterThan(0, $input['weight']);
            $this->assertNotSame('', trim($input['weight_rationale']));
            $this->assertSame($projectionChart->payload['labels'], array_keys($input['checkpoints']));

            foreach ($input['checkpoints'] as $label => $checkpoint) {
                foreach (['normalized_value', 'directional_risk_value', 'scenario_adjustments', 'calculated_values'] as $required) {
                    $this->assertArrayHasKey($required, $checkpoint, $input['signal_key'].' '.$label);
                }
                foreach (array_keys($expectedScores) as $scenario) {
                    $adjusted = min(100, max(0, $checkpoint['directional_risk_value'] + $checkpoint['scenario_adjustments'][$scenario]));
                    $expectedContribution = round($input['weight'] * $adjusted, 4);
                    $this->assertEquals($expectedContribution, $checkpoint['calculated_values'][$scenario], $input['signal_key'].' '.$label.' '.$scenario);
                }
            }
        }

        foreach ($projectionChart->payload['labels'] as $index => $label) {
            foreach (array_keys($expectedScores) as $scenario) {
                $sum = array_sum(array_map(
                    static fn (array $input): float => (float) $input['checkpoints'][$label]['calculated_values'][$scenario],
                    $riskInputs,
                ));
                $expectedScores[$scenario][] = round(min(100, max(0, $sum)), 1);
            }
            $this->assertGreaterThanOrEqual($expectedScores['Neutral'][$index], $expectedScores['Pessimistic'][$index]);
            $this->assertGreaterThanOrEqual($expectedScores['Optimistic'][$index], $expectedScores['Neutral'][$index]);
        }

        $this->assertEquals($expectedScores, $visualizationData->scenarioScores());
        foreach ($projectionChart->payload['series'] as $series) {
            $this->assertEquals($expectedScores[$series['name']], $series['values'], $series['name']);
            $this->assertCount(count($projectionChart->payload['labels']), $series['values']);
        }
        $chartLanguage = strtolower((string) json_encode([
            $projectionChart->title,
            $projectionChart->description,
            $projectionChart->reasoning,
            $projectionChart->payload['axes']['y'],
        ], JSON_THROW_ON_ERROR));
        $this->assertStringNotContainsString('probability', $chartLanguage);
        $this->assertGreaterThanOrEqual(6, $countdown->visualizations->count());

        foreach ($countdown->visualizations as $visualization) {
            if (! in_array($visualization->type, [VisualizationType::Line, VisualizationType::Area, VisualizationType::Bar], true)) {
                continue;
            }

            $this->assertSame(2, $visualization->schema_version, $visualization->key);
            $this->assertArrayHasKey('axes', $visualization->payload, $visualization->key);
            $this->assertArrayNotHasKey('sources', $visualization->payload, $visualization->key);
            $this->assertArrayNotHasKey('reasoning', $visualization->payload, $visualization->key);
            $this->assertNotEmpty($visualization->sources, $visualization->key);
            $this->assertNotSame('', trim((string) ($visualization->reasoning['en'] ?? '')), $visualization->key);
            foreach ($visualization->sources as $source) {
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
        $this->assertNotEmpty($energyResilience?->sources ?? []);
        $this->assertNotSame('', trim((string) ($energyResilience?->reasoning['en'] ?? '')));
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

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();
        parent::tearDown();
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
