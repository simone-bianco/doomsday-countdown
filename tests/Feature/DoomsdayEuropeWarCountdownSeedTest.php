<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProjectionType;
use App\Enums\VisualizationType;
use App\Models\ContentSource;
use App\Models\Countdown;
use App\Services\Doomsday\CountdownPublicDataService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdayEuropeWarCountdownSeedTest extends TestCase
{
    use RefreshDatabase;

    private const SLUG = 'europe-war-countdown';

    /** @var list<string> */
    private const LOCALES = ['en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl'];

    /** @var list<string> */
    private const PATCHES = [
        '2026_07_11_020000_prepare_europe_war_countdown',
        '2026_07_11_020010_seed_europe_war_countdown',
        '2026_07_11_020020_seed_europe_war_countdown_projections',
        '2026_07_11_020030_seed_europe_war_countdown_visualizations',
        '2026_07_11_020040_seed_europe_war_countdown_news',
        '2026_07_11_020050_seed_europe_war_countdown_content_sources',
        '2026_07_11_020060_seed_europe_war_countdown_initiatives',
    ];

    protected function tearDown(): void
    {
        $this->travelBack();

        parent::tearDown();
    }

    public function test_europe_patch_set_creates_complete_localized_countdown(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-07-12 00:00:00', 'UTC'));
        $this->runEuropePatches();

        $countdown = Countdown::query()
            ->where('slug', self::SLUG)
            ->with(['projections.visualizations', 'visualizations', 'news', 'initiatives', 'contentSources'])
            ->firstOrFail();

        $this->assertSame(2, $countdown->sort_order);
        $this->assertTrue($countdown->is_published);
        $this->assertSame('2030-12-31 23:59:59', $countdown->target_date?->utc()->format('Y-m-d H:i:s'));
        $this->assertSame('images/doomsday/europe_war_countdown.png', $countdown->image_path);
        $this->assertFileExists(public_path($countdown->image_path));
        $this->assertIsArray(getimagesize(public_path($countdown->image_path)) ?: null);

        foreach (['title', 'summary', 'description', 'causes', 'consequences', 'recommended_actions'] as $field) {
            $value = $countdown->{$field};
            $this->assertIsArray($value, $field);
            $this->assertSame(self::LOCALES, array_keys($value), $field);
            foreach (self::LOCALES as $locale) {
                $this->assertNotEmpty($value[$locale], $field.':'.$locale);
            }
        }

        $this->assertStringContainsString('not a prediction', $countdown->summary['en']);
        $this->assertCount(3, $countdown->projections);
        $this->assertSame(
            [ProjectionType::Optimistic, ProjectionType::Neutral, ProjectionType::Pessimistic],
            $countdown->projections->sortBy('sort_order')->pluck('type')->values()->all(),
        );

        $projectionTargets = [
            ProjectionType::Pessimistic->value => '2027-03-31 23:59:59',
            ProjectionType::Neutral->value => '2030-12-31 23:59:59',
            ProjectionType::Optimistic->value => '2035-12-31 23:59:59',
        ];
        foreach ($countdown->projections as $projection) {
            $this->assertSame($projectionTargets[$projection->type->value], $projection->target_date?->utc()->format('Y-m-d H:i:s'));
            $this->assertSame(self::LOCALES, array_keys($projection->title));
            $this->assertSame(self::LOCALES, array_keys($projection->summary));
            $this->assertStringContainsString('not an official forecast', $projection->methodology['classification']);
            $this->assertStringContainsString('not an empirical likelihood', $projection->methodology['probability_score_note']);
            $this->assertSame('2026-07-11', $projection->methodology['as_of']);
            $this->assertNotEmpty($projection->methodology['target_rationale']);
            $this->assertNotEmpty($projection->methodology['milestones']);
            $this->assertNotEmpty($projection->methodology['limits']);
            $this->assertNotEmpty($projection->methodology['stop_conditions']);
            $this->assertNotEmpty($projection->methodology['sources']);
            foreach ($projection->methodology['sources'] as $source) {
                $this->assertStringStartsWith('https://', $source);
            }
        }
        $pessimistic = $countdown->projections->firstWhere('type', ProjectionType::Pessimistic);
        $neutral = $countdown->projections->firstWhere('type', ProjectionType::Neutral);
        $optimistic = $countdown->projections->firstWhere('type', ProjectionType::Optimistic);
        $this->assertNotNull($pessimistic);
        $this->assertSame(2027, $pessimistic->methodology['scenario_target_year']);
        $this->assertStringContainsString('user-directed editorial pessimistic anchor', $pessimistic->methodology['target_rationale']);
        $this->assertStringContainsString('No cited source identifies 31 March 2027', $pessimistic->methodology['source_alignment']);
        $this->assertNotNull($neutral);
        $this->assertNotNull($optimistic);
        $this->assertTrue($pessimistic->target_date->lessThan($neutral->target_date));
        $this->assertTrue($neutral->target_date->lessThan($optimistic->target_date));
        $this->assertSame($countdown->target_date?->utc()->format('Y-m-d H:i:s'), $neutral->target_date?->utc()->format('Y-m-d H:i:s'));

        $indexItem = collect(app(CountdownPublicDataService::class)->indexPayload('en')['countdowns'])
            ->firstWhere('slug', self::SLUG);
        $this->assertSame(ProjectionType::Pessimistic->value, $indexItem['main_projection']['type'] ?? null);
        $this->assertSame('2027-03-31 23:59:59', CarbonImmutable::parse($indexItem['timer']['target_date'])->utc()->format('Y-m-d H:i:s'));

        $overview = $this->getJson(route('countdowns.data.overview', ['slug' => self::SLUG, 'lang' => 'en']))
            ->assertOk()
            ->json('data');
        $this->assertSame(ProjectionType::Pessimistic->value, $overview['main_projection']['type']);
        $this->assertSame('2027-03-31 23:59:59', CarbonImmutable::parse($overview['timer']['target_date'])->utc()->format('Y-m-d H:i:s'));

        $this->assertCount(6, $countdown->visualizations);
        $projectionCharts = $countdown->projections
            ->flatMap(fn ($projection) => $projection->visualizations)
            ->where('key', 'projection_curve');
        $this->assertCount(1, $projectionCharts);
        $projectionChart = $projectionCharts->first();
        $this->assertNotNull($projectionChart);
        $this->assertNull($countdown->projections
            ->flatMap(fn ($projection) => $projection->visualizations)
            ->firstWhere('key', 'readiness_scenario_curve'));
        $this->assertSame(VisualizationType::Line, $projectionChart->type);
        $this->assertSame(2, $projectionChart->schema_version);
        $this->assertSame('temporal', $projectionChart->payload['axes']['x']['type']);
        $this->assertSame('index points', $projectionChart->payload['axes']['y']['unit']);
        $this->assertSame(['2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033', '2034', '2035'], $projectionChart->payload['labels']);
        $this->assertStringContainsString('time-window pressure index', $projectionChart->reasoning['en']);
        $this->assertStringContainsString('not a probability', $projectionChart->reasoning['en']);
        $this->assertArrayNotHasKey('sources', $projectionChart->payload);
        $this->assertArrayNotHasKey('reasoning', $projectionChart->payload);
        $this->assertProjectionFormula($projectionChart->payload['labels'], $projectionChart->payload['series']);

        $forecasts = $this->getJson(route('countdowns.data.forecasts', ['slug' => self::SLUG, 'lang' => 'en']))
            ->assertOk()
            ->json('data');
        $this->assertSame('projection_curve', $forecasts['projection_chart']['key']);
        $this->assertCount(3, $forecasts['projections']);
        $neutralForecastTarget = collect($forecasts['projections'])->firstWhere('type', ProjectionType::Neutral->value)['target_date'];
        $this->assertSame('2030-12-31 23:59:59', CarbonImmutable::parse($neutralForecastTarget)->utc()->format('Y-m-d H:i:s'));
        $this->assertNotEmpty($forecasts['projection_chart']['payload']);
        $this->assertNotEmpty($forecasts['projection_chart']['sources']);
        $this->assertNotSame('', trim($forecasts['projection_chart']['reasoning']));

        foreach ($countdown->visualizations as $visualization) {
            $this->assertSame(self::LOCALES, array_keys($visualization->title), $visualization->key);
            $this->assertSame(self::LOCALES, array_keys($visualization->description), $visualization->key);
            $this->assertSame(self::LOCALES, array_keys($visualization->reasoning), $visualization->key);
            $this->assertNotSame('', trim($visualization->reasoning['en']), $visualization->key);
            $this->assertNotEmpty($visualization->sources, $visualization->key);
            foreach ($visualization->sources as $source) {
                $this->assertStringStartsWith('https://', $source, $visualization->key);
            }
            $this->assertArrayNotHasKey('sources', $visualization->payload, $visualization->key);
            $this->assertArrayNotHasKey('reasoning', $visualization->payload, $visualization->key);

            if (in_array($visualization->type, [VisualizationType::Line, VisualizationType::Area, VisualizationType::Bar], true)) {
                $this->assertSame(2, $visualization->schema_version, $visualization->key);
                $this->assertArrayHasKey('axes', $visualization->payload, $visualization->key);
                $this->assertCount(count($visualization->payload['labels']), $visualization->payload['series'], $visualization->key);
            }
        }

        $this->assertSame([1.40, 1.40, 1.41, 1.45, 1.48, 1.51, 1.69, 1.63, 1.63, 1.74, 1.99, 2.27], $countdown->visualizations->firstWhere('key', 'nato_spending_share_trend')?->payload['series']);
        $this->assertSame([278589, 283767, 291748, 309146, 321215, 333716, 349702, 358668, 372255, 406766, 482395, 559305], $countdown->visualizations->firstWhere('key', 'nato_real_spending_trend')?->payload['series']);
        $this->assertStringContainsString('Observed', $countdown->visualizations->firstWhere('key', 'key_indicators')?->reasoning['en'] ?? '');

        $this->assertGreaterThanOrEqual(10, $countdown->news->count());
        $newsPreviewUrls = [];
        $newsVideos = [];
        foreach ($countdown->news as $news) {
            $this->assertNotSame('', trim($news->title));
            $this->assertNotSame('', trim($news->excerpt));
            $this->assertNotSame('', trim((string) $news->source_name));
            $this->assertStringStartsWith('https://', (string) $news->source_url);
            $this->assertStringStartsWith('https://', (string) $news->preview_image_url);
            $this->assertSame($countdown->image_path, $news->image_path);
            $newsPreviewUrls[] = $news->preview_image_url;

            if ($news->content_type === 'youtube_video') {
                $this->assertYouTubeMedia($news->source_url, $news->external_id, $news->embed_url, $news->preview_image_url, $news->external_provider);
                $newsVideos[] = $news;
            }
        }
        $this->assertCount($countdown->news->count(), array_unique($newsPreviewUrls));
        $this->assertGreaterThanOrEqual(2, count($newsVideos));

        $publicNews = $this->getJson(route('countdowns.data.news', ['slug' => self::SLUG, 'lang' => 'en']))
            ->assertOk()
            ->json('data.news');
        $this->assertCount($countdown->news->count(), $publicNews);
        foreach ($publicNews as $item) {
            $model = $countdown->news->firstWhere('source_url', $item['source_url']);
            $this->assertNotNull($model);
            $this->assertSame($model->preview_image_url, $item['image_url']);
            $this->assertStringStartsWith('https://', $item['image_url']);
        }

        $this->assertGreaterThanOrEqual(8, $countdown->initiatives->count());
        $initiativePreviewUrls = [];
        $initiativeVideos = [];
        foreach ($countdown->initiatives as $initiative) {
            $this->assertNotSame('', trim($initiative->title));
            $this->assertNotSame('', trim($initiative->excerpt));
            $this->assertNotSame('', trim((string) $initiative->organization));
            $this->assertStringStartsWith('https://', $initiative->url);
            $this->assertStringStartsWith('https://', (string) $initiative->preview_image_url);
            $this->assertSame($countdown->image_path, $initiative->image_path);
            $initiativePreviewUrls[] = $initiative->preview_image_url;

            if ($initiative->content_type === 'youtube_video') {
                $this->assertYouTubeMedia($initiative->url, $initiative->external_id, $initiative->embed_url, $initiative->preview_image_url, $initiative->external_provider);
                $initiativeVideos[] = $initiative;
            }
        }
        $this->assertCount($countdown->initiatives->count(), array_unique($initiativePreviewUrls));
        $this->assertGreaterThanOrEqual(1, count($initiativeVideos));

        $publicInitiatives = $this->getJson(route('countdowns.data.initiatives', ['slug' => self::SLUG, 'lang' => 'en']))
            ->assertOk()
            ->json('data.initiatives');
        $this->assertCount($countdown->initiatives->count(), $publicInitiatives);
        foreach ($publicInitiatives as $item) {
            $model = $countdown->initiatives->firstWhere('url', $item['url']);
            $this->assertNotNull($model);
            $this->assertSame($model->preview_image_url, $item['image_url']);
            $this->assertStringStartsWith('https://', $item['image_url']);
        }

        $this->assertGreaterThanOrEqual(10, $countdown->contentSources->count());
        foreach ($countdown->contentSources as $source) {
            $this->assertSame(ContentSource::TYPE_WEBSITE, $source->type);
            $this->assertSame('official', $source->provider);
            $this->assertNotSame('', trim((string) $source->external_id));
            $this->assertStringStartsWith('https://', (string) $source->source_url);
            $this->assertTrue((bool) $source->pivot->is_active);
            $this->assertGreaterThan(0, (int) $source->pivot->weight);
            $this->assertNotEmpty(json_decode((string) $source->pivot->keywords, true, flags: JSON_THROW_ON_ERROR));
        }
    }

    public function test_europe_patch_set_is_idempotent(): void
    {
        $this->runEuropePatches();
        $before = $this->relationCounts();
        $countdown = Countdown::query()->where('slug', self::SLUG)->firstOrFail();
        $neutralProjection = $countdown->projections()->where('type', ProjectionType::Neutral->value)->firstOrFail();
        $canonicalChart = $neutralProjection->visualizations()->where('key', 'projection_curve')->firstOrFail();
        $legacyChart = $canonicalChart->replicate();
        $legacyChart->key = 'readiness_scenario_curve';
        $neutralProjection->visualizations()->save($legacyChart);
        $countdown->news()->create([
            'locale' => 'all',
            'title' => 'Legacy EDA article',
            'excerpt' => 'Owned legacy URL replaced by the canonical video item.',
            'source_url' => 'https://eda.europa.eu/publications-and-data/thematic-policy-reports/eda-defence-data-2024-2025',
            'image_path' => $countdown->image_path,
        ]);
        $countdown->initiatives()->create([
            'locale' => 'all',
            'type' => 'resource',
            'title' => 'Legacy EDA data portal',
            'excerpt' => 'Owned legacy URL replaced by the canonical video item.',
            'url' => 'https://eda.europa.eu/publications-and-data/defence-data',
            'image_path' => $countdown->image_path,
        ]);

        $this->runEuropePatches();

        $this->assertSame($before, $this->relationCounts());
        $this->assertFalse($neutralProjection->visualizations()->where('key', 'readiness_scenario_curve')->exists());
        $this->assertSame(1, $neutralProjection->visualizations()->where('key', 'projection_curve')->count());
        $this->assertFalse($countdown->news()->where('source_url', 'https://eda.europa.eu/publications-and-data/thematic-policy-reports/eda-defence-data-2024-2025')->exists());
        $this->assertFalse($countdown->initiatives()->where('url', 'https://eda.europa.eu/publications-and-data/defence-data')->exists());
        $this->assertSame(1, Countdown::query()->where('slug', self::SLUG)->count());
    }

    public function test_europe_rollbacks_remove_only_owned_records(): void
    {
        $this->runEuropePatches();
        $countdown = Countdown::query()->where('slug', self::SLUG)->firstOrFail();

        $unownedNews = $countdown->news()->create([
            'locale' => 'all',
            'title' => 'Unowned Europe news sentinel',
            'excerpt' => 'Outside the patch-owned URL set.',
            'source_url' => 'https://unowned.test/europe-news',
            'image_path' => $countdown->image_path,
        ]);
        $unownedInitiative = $countdown->initiatives()->create([
            'locale' => 'all',
            'type' => 'resource',
            'title' => 'Unowned Europe initiative sentinel',
            'excerpt' => 'Outside the patch-owned URL set.',
            'url' => 'https://unowned.test/europe-initiative',
            'image_path' => $countdown->image_path,
        ]);
        $unownedVisualization = $countdown->visualizations()->create([
            'key' => 'unowned_europe_visualization',
            'type' => VisualizationType::Kpi,
            'title' => array_fill_keys(self::LOCALES, 'Unowned'),
            'description' => array_fill_keys(self::LOCALES, 'Unowned sentinel'),
            'sources' => ['https://unowned.test/source'],
            'reasoning' => array_fill_keys(self::LOCALES, 'Unowned sentinel evidence.'),
            'payload' => ['items' => [['label' => 'Sentinel', 'value' => '1']]],
            'schema_version' => 1,
        ]);
        $unownedProjection = $countdown->projections()->create([
            'type' => ProjectionType::Other,
            'target_date' => now()->addYear(),
            'title' => array_fill_keys(self::LOCALES, 'Unowned'),
            'summary' => array_fill_keys(self::LOCALES, 'Unowned sentinel'),
            'methodology' => ['classification' => 'Unowned sentinel'],
        ]);
        $unownedSource = ContentSource::query()->create([
            'type' => ContentSource::TYPE_WEBSITE,
            'provider' => 'official',
            'name' => 'Unowned Europe source sentinel',
            'external_id' => 'unowned-europe-source',
            'source_url' => 'https://unowned.test/europe-source',
            'language' => 'en',
            'is_active' => true,
        ]);
        $sourceConsumer = Countdown::query()->create([
            'slug' => 'shared-source-consumer',
            'title' => ['en' => 'Shared source consumer'],
            'summary' => ['en' => 'Preserves an owned source after Europe rollback.'],
            'image_path' => $countdown->image_path,
        ]);
        $sharedOwnedSource = $countdown->contentSources()->firstOrFail();
        $sourceConsumer->contentSources()->attach($sharedOwnedSource->getKey(), [
            'keywords' => json_encode(['shared'], JSON_THROW_ON_ERROR),
            'excluded_keywords' => json_encode([], JSON_THROW_ON_ERROR),
            'weight' => 100,
            'is_active' => true,
        ]);
        $countdown->contentSources()->attach($unownedSource->getKey(), [
            'keywords' => json_encode(['sentinel'], JSON_THROW_ON_ERROR),
            'excluded_keywords' => json_encode([], JSON_THROW_ON_ERROR),
            'weight' => 100,
            'is_active' => true,
        ]);

        $this->loadEuropePatch(self::PATCHES[6])->down();
        $this->loadEuropePatch(self::PATCHES[5])->down();
        $this->loadEuropePatch(self::PATCHES[4])->down();
        $this->loadEuropePatch(self::PATCHES[3])->down();
        $this->loadEuropePatch(self::PATCHES[2])->down();

        $this->assertTrue($countdown->news()->whereKey($unownedNews->getKey())->exists());
        $this->assertTrue($countdown->initiatives()->whereKey($unownedInitiative->getKey())->exists());
        $this->assertTrue($countdown->visualizations()->whereKey($unownedVisualization->getKey())->exists());
        $this->assertTrue($countdown->projections()->whereKey($unownedProjection->getKey())->exists());
        $this->assertTrue(ContentSource::query()->whereKey($unownedSource->getKey())->exists());
        $this->assertTrue(ContentSource::query()->whereKey($sharedOwnedSource->getKey())->exists());
        $this->assertTrue($sourceConsumer->contentSources()->whereKey($sharedOwnedSource->getKey())->exists());
        $this->assertTrue($countdown->contentSources()->whereKey($unownedSource->getKey())->exists());
        $this->assertSame(1, $countdown->news()->count());
        $this->assertSame(1, $countdown->initiatives()->count());
        $this->assertSame(1, $countdown->visualizations()->count());
        $this->assertSame(1, $countdown->projections()->count());
        $this->assertSame(1, $countdown->contentSources()->count());

        $unrelated = Countdown::query()->create([
            'slug' => 'unrelated-countdown-sentinel',
            'title' => ['en' => 'Unrelated'],
            'summary' => ['en' => 'Unrelated'],
            'image_path' => $countdown->image_path,
        ]);
        $this->loadEuropePatch(self::PATCHES[1])->down();
        $this->loadEuropePatch(self::PATCHES[0])->down();

        $this->assertFalse(Countdown::query()->where('slug', self::SLUG)->exists());
        $this->assertTrue(Countdown::query()->whereKey($unrelated->getKey())->exists());
    }

    private function assertYouTubeMedia(?string $url, ?string $externalId, ?string $embedUrl, ?string $previewImageUrl, ?string $provider): void
    {
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9_-]{11}$/', (string) $externalId);
        $this->assertSame('youtube', $provider);
        $this->assertSame('https://www.youtube.com/watch?v='.$externalId, $url);
        $this->assertSame('https://www.youtube.com/embed/'.$externalId, $embedUrl);
        $this->assertSame('https://i.ytimg.com/vi/'.$externalId.'/hqdefault.jpg', $previewImageUrl);
    }

    /** @param list<string> $labels @param list<array{name: string, values: list<int>}> $series */
    private function assertProjectionFormula(array $labels, array $series): void
    {
        $targetYears = ['Pessimistic' => 2027, 'Neutral' => 2030, 'Optimistic' => 2035];
        foreach ($series as $scenario) {
            $targetYear = $targetYears[$scenario['name']];
            $expected = array_map(
                static fn (string $year): int => min(100, (int) round(50 + (50 * (((int) $year - 2026) / ($targetYear - 2026))))),
                $labels,
            );
            $this->assertSame($expected, $scenario['values'], $scenario['name']);
        }
    }

    /** @return array{projections: int, projection_visualizations: int, visualizations: int, news: int, initiatives: int, sources: int} */
    private function relationCounts(): array
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->firstOrFail();

        return [
            'projections' => $countdown->projections()->count(),
            'projection_visualizations' => $countdown->projections()->withCount('visualizations')->get()->sum('visualizations_count'),
            'visualizations' => $countdown->visualizations()->count(),
            'news' => $countdown->news()->count(),
            'initiatives' => $countdown->initiatives()->count(),
            'sources' => $countdown->contentSources()->count(),
        ];
    }

    private function runEuropePatches(): void
    {
        foreach (self::PATCHES as $patch) {
            $this->loadEuropePatch($patch)->up();
        }
    }

    private function loadEuropePatch(string $directory): object
    {
        return require base_path('database/patches/countdowns/europe_war_countdown/'.$directory.'/patch.php');
    }
}
