<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use App\Enums\InitiativeType;
use App\Enums\ProjectionType;
use App\Enums\VisualizationType;
use App\Models\ContentSource;
use App\Models\Countdown;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use SimoneBianco\Patches\Patch;
use Tests\TestCase;

final class DoomsdayUnlivableHeatSeedTest extends TestCase
{
    use RefreshDatabase;

    private const SLUG = 'unlivable-heat';

    private const ASSET = 'images/doomsday/extreme_heat_breakpoint_separate.png';

    /** @var array<int, string> */
    private const LOCALES = ['en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl'];

    /** @var array<int, string> */
    private const NEWS_VIDEO_IDS = ['iwoijGguBFM', 'jb1OcQogLfY'];

    /** @var array<int, string> */
    private const INITIATIVE_VIDEO_IDS = ['KdI5c8f8hZI'];

    /** @var array<int, string> */
    private const LEGACY_NEWS_URLS = [
        'https://www.who.int/europe/news/item/11-06-2026-heat-action-day--new-who-guidance-helps-authorities-better-protect-people-from-the-effects-of-heat',
        'https://wmo.int/news/media-centre/wmo-confirms-2023-smashes-global-temperature-record',
    ];

    /** @var array<int, string> */
    private const LEGACY_INITIATIVE_URLS = [
        'https://www.c40.org/networks/cool-cities-network/',
    ];

    public function test_owned_patches_create_complete_evidence_rich_heat_countdown(): void
    {
        $this->runOwnedPatches();

        $countdown = Countdown::query()
            ->where('slug', self::SLUG)
            ->with(['projections.visualizations', 'visualizations', 'news', 'initiatives', 'contentSources'])
            ->firstOrFail();

        $this->assertSame('The Unlivable Heat Countdown', $countdown->title['en']);
        $this->assertSame('Il conto alla rovescia del caldo invivibile', $countdown->title['it']);
        $this->assertSame(self::LOCALES, array_keys($countdown->title));
        $this->assertSame(self::LOCALES, array_keys($countdown->summary));
        $this->assertSame(self::LOCALES, array_keys($countdown->description));
        $this->assertSame(self::LOCALES, array_keys($countdown->causes));
        $this->assertSame(self::LOCALES, array_keys($countdown->consequences));
        $this->assertSame(self::LOCALES, array_keys($countdown->recommended_actions));
        $this->assertSame(CountdownSeverity::Severe, $countdown->severity);
        $this->assertSame(CountdownStatus::Active, $countdown->status);
        $this->assertSame('2050-12-31 23:59:59', $countdown->target_date?->utc()->format('Y-m-d H:i:s'));
        $this->assertSame(self::ASSET, $countdown->image_path);
        $this->assertSame(6, $countdown->sort_order);
        $this->assertTrue($countdown->is_published);
        $this->assertFileExists(public_path(self::ASSET));

        $projections = $countdown->projections->sortBy('sort_order')->values();
        $this->assertCount(3, $projections);
        $this->assertSame(
            [ProjectionType::Optimistic, ProjectionType::Neutral, ProjectionType::Pessimistic],
            $projections->pluck('type')->all(),
        );
        $this->assertSame(100, $projections->sum('probability_score'));
        $this->assertSame([33, 34, 33], $projections->pluck('probability_score')->all());
        $this->assertSame([50, 50, 50], $projections->pluck('confidence_score')->all());
        $this->assertSame(['SSP1-1.9', 'SSP2-4.5', 'SSP5-8.5'], $projections->pluck('methodology.scenario')->all());
        $this->assertSame([1.4, 2, 1.6], $projections->pluck('methodology.endpoint_celsius')->all());
        $this->assertSame(['2081-2100', '2041-2060', '2021-2040'], $projections->pluck('methodology.scenario_period')->all());

        $targetByType = [
            ProjectionType::Optimistic->value => '2100-12-31 23:59:59',
            ProjectionType::Neutral->value => '2050-12-31 23:59:59',
            ProjectionType::Pessimistic->value => '2040-12-31 23:59:59',
        ];
        foreach ($projections as $projection) {
            $methodology = $projection->methodology;
            $type = $projection->type->value;
            $this->assertSame(self::LOCALES, array_keys($projection->title));
            $this->assertSame(self::LOCALES, array_keys($projection->summary));
            $this->assertSame($targetByType[$type], $projection->target_date?->utc()->format('Y-m-d H:i:s'));
            $this->assertSame((int) substr($targetByType[$type], 0, 4), $methodology['target_year']);
            $this->assertSame('1850-1900', $methodology['reference_period']);
            $this->assertSame('editorial target date anchored to assessed climate windows; not a threshold-crossing forecast', $methodology['classification']);
            $this->assertSame('2026-07-11', $methodology['assessed_at']);
            $this->assertSame('editorial scenario anchor; not an official event forecast', $methodology['nature']);
            $this->assertSame('legacy display field only; scenario dates are not probabilistic event forecasts', $methodology['probability_role']);
            $this->assertSame('editorial equal-weight prior; not an IPCC probability', $methodology['score_method']);
            $this->assertNotSame('', trim($methodology['target_basis']));
            $this->assertNotSame('', trim($methodology['reasoning']));
            $this->assertCount(3, $methodology['drivers']);
            $this->assertCount(4, $methodology['milestones']);
            $this->assertCount(3, $methodology['limits']);
            $this->assertCount(3, $methodology['stop_conditions']);
            $this->assertCount(12, $methodology['sources']);
            foreach ($methodology['sources'] as $source) {
                $this->assertStringStartsWith('https://', $source);
            }
            $this->assertSame(39.4, $methodology['physiology_references']['heat_index']['nws_danger_starts_celsius']);
            $this->assertSame(35, $methodology['physiology_references']['wet_bulb']['theoretical_upper_limit_celsius']);
            $this->assertSame(30.55, $methodology['physiology_references']['wet_bulb']['experimental_humid_mean_celsius']);
            $this->assertStringContainsString('not interchangeable', $methodology['physiology_references']['limit']);
        }

        $neutral = $projections->firstWhere('type', ProjectionType::Neutral);
        $pessimistic = $projections->firstWhere('type', ProjectionType::Pessimistic);
        $optimistic = $projections->firstWhere('type', ProjectionType::Optimistic);
        $this->assertNotNull($neutral);
        $this->assertNotNull($pessimistic);
        $this->assertNotNull($optimistic);

        $localizedCopy = [
            'countdown.summary' => $countdown->summary,
            'countdown.description' => $countdown->description,
            'projection.neutral.summary' => $neutral->summary,
        ];
        $obsoleteMarkers = [
            'en' => ['the main 2050 planning timer', 'The timer shows the neutral 2050 planning horizon', 'The main timer is 2050'],
            'it' => ['timer principale di pianificazione al 2050', 'Il timer mostra l’orizzonte neutrale di pianificazione al 2050', 'Il timer principale è il 2050'],
            'fr' => ['minuterie principale de planification en 2050', 'La minuterie affiche l’horizon neutre de planification 2050', 'La minuterie principale est 2050'],
            'de' => ['der zentrale Planungstimer 2050', 'Der Timer zeigt den neutralen Planungshorizont 2050', 'Der Haupttimer ist 2050'],
            'es' => ['temporizador principal de planificación en 2050', 'El temporizador muestra el horizonte neutral de planificación de 2050', 'El temporizador principal es 2050'],
            'nl' => ['de hoofdplanningstimer in 2050', 'De timer toont de neutrale planningshorizon 2050', 'De hoofdtimer is 2050'],
            'sv' => ['huvudtimern för planering 2050', 'Timern visar den neutrala planeringshorisonten 2050', 'Huvudtimern är 2050'],
            'pl' => ['główny horyzont planowania 2050', 'Licznik pokazuje neutralny horyzont planowania 2050', 'Główny licznik wskazuje 2050'],
        ];
        foreach (self::LOCALES as $locale) {
            foreach ($localizedCopy as $key => $copy) {
                $this->assertNotSame('', trim($copy[$locale]), $key.' '.$locale);
            }
            foreach (['2040', '2050', '2100'] as $year) {
                $this->assertStringContainsString($year, $countdown->summary[$locale], 'summary '.$locale);
                $this->assertStringContainsString($year, $countdown->description[$locale], 'description '.$locale);
            }
            $this->assertStringContainsString('Pessimistic → Neutral → Optimistic', $countdown->summary[$locale]);
            $this->assertStringContainsString('Pessimistic → Neutral → Optimistic', $countdown->description[$locale]);
            $this->assertStringContainsString('2050', $neutral->summary[$locale]);
            $combinedCopy = implode(' ', array_column($localizedCopy, $locale));
            foreach ($obsoleteMarkers[$locale] as $marker) {
                $this->assertStringNotContainsString($marker, $combinedCopy, $locale.' '.$marker);
            }
        }

        $this->assertTrue($pessimistic->target_date->lt($neutral->target_date));
        $this->assertTrue($neutral->target_date->lt($optimistic->target_date));

        $projectionChart = $neutral->visualizations->firstWhere('key', 'projection_curve');
        $this->assertNotNull($projectionChart);
        $this->assertSame(VisualizationType::Line, $projectionChart->type);
        $this->assertSame(['2025', '2030', '2050', '2090'], $projectionChart->payload['labels']);
        $this->assertSame('°C', $projectionChart->payload['axes']['y']['unit']);
        $this->assertVisualizationEvidence($projectionChart);

        $expectedSeries = [
            'SSP1-1.9' => ['values' => [1.44, 1.5, 1.6, 1.4], 'target' => 2100],
            'SSP2-4.5' => ['values' => [1.44, 1.5, 2.0, 2.7], 'target' => 2050],
            'SSP5-8.5' => ['values' => [1.44, 1.6, 2.4, 4.4], 'target' => 2040],
        ];
        foreach ($projectionChart->payload['series'] as $series) {
            $actual = array_map(static fn (int|float $value): float => (float) $value, $series['values']);
            $this->assertSame($expectedSeries[$series['name']]['values'], $actual, $series['name']);
            $this->assertSame($expectedSeries[$series['name']]['target'], $series['editorial_target_year']);
            $this->assertSame(['observed', '2021-2040', '2041-2060', '2081-2100'], $series['assessment_windows']);
        }

        $this->travelTo(CarbonImmutable::parse('2026-07-12 00:00:00', 'UTC'));
        try {
            $overview = $this->getJson(route('countdowns.data.overview', ['slug' => self::SLUG, 'lang' => 'en']))
                ->assertOk()
                ->json('data');
            $this->assertSame(ProjectionType::Pessimistic->value, $overview['main_projection']['type']);
            $this->assertStringStartsWith('2040-12-31T23:59:59', $overview['timer']['target_date']);
            $this->assertSame($countdown->summary['en'], $overview['summary']);
            $this->assertSame($countdown->description['en'], $overview['description']);
            foreach (['2040', '2050', '2100', 'Pessimistic → Neutral → Optimistic'] as $marker) {
                $this->assertStringContainsString($marker, $overview['summary'].' '.$overview['description']);
            }

            $forecasts = $this->getJson(route('countdowns.data.forecasts', ['slug' => self::SLUG, 'lang' => 'en']))
                ->assertOk()
                ->json('data');
            $this->assertSame('projection_curve', $forecasts['projection_chart']['key']);
            $publicDates = collect($forecasts['projections'])->mapWithKeys(
                static fn (array $projection): array => [$projection['type'] => $projection['target_date']],
            );
            foreach ($targetByType as $type => $target) {
                $this->assertStringStartsWith(str_replace(' ', 'T', $target), $publicDates[$type]);
            }
        } finally {
            $this->travelBack();
        }

        $this->assertCount(6, $countdown->visualizations);
        $types = $countdown->visualizations->mapWithKeys(
            static fn ($visualization): array => [$visualization->key => $visualization->type],
        )->all();
        $this->assertSame([
            'key_indicators' => VisualizationType::Kpi,
            'global_temperature_anomaly' => VisualizationType::Line,
            'tropical_night_area_share' => VisualizationType::Area,
            'southeast_heat_stress_days' => VisualizationType::Bar,
            'southeast_tropical_nights' => VisualizationType::Bar,
            'eu_cooling_degree_days_2022' => VisualizationType::Bar,
        ], $types);

        foreach ($countdown->visualizations as $visualization) {
            $this->assertVisualizationEvidence($visualization);
            if (! in_array($visualization->type, [VisualizationType::Line, VisualizationType::Area, VisualizationType::Bar], true)) {
                continue;
            }
            $this->assertSame(2, $visualization->schema_version, $visualization->key);
            $this->assertArrayHasKey('axes', $visualization->payload, $visualization->key);
            $this->assertCount(count($visualization->payload['labels']), $visualization->payload['series'], $visualization->key);
        }

        $this->assertSame([1.45, 1.55, 1.44], $countdown->visualizations->firstWhere('key', 'global_temperature_anomaly')?->payload['series']);
        $this->assertSame([20, 34, 35], $countdown->visualizations->firstWhere('key', 'tropical_night_area_share')?->payload['series']);
        $this->assertSame([29, 66], $countdown->visualizations->firstWhere('key', 'southeast_heat_stress_days')?->payload['series']);
        $this->assertSame([8, 16, 23], $countdown->visualizations->firstWhere('key', 'southeast_tropical_nights')?->payload['series']);
        $this->assertSame([842, 698, 384, 375, 372], $countdown->visualizations->firstWhere('key', 'eu_cooling_degree_days_2022')?->payload['series']);
        $this->assertSame(
            ['+1.44°C', '66 days', '23 nights', '>175,000'],
            collect($countdown->visualizations->firstWhere('key', 'key_indicators')?->payload['items'] ?? [])->pluck('value')->all(),
        );

        $this->assertCount(10, $countdown->news);
        $this->assertCount(8, $countdown->initiatives);
        $this->assertCount(10, $countdown->contentSources);
        $this->assertPersistedNewsMedia($countdown);
        $this->assertPersistedInitiativeMedia($countdown);
        $this->assertPublicMediaPayloads($countdown);

        foreach ($countdown->contentSources as $source) {
            $this->assertSame(ContentSource::TYPE_WEBSITE, $source->type);
            $this->assertStringStartsWith('https://', (string) $source->source_url);
            $this->assertStringStartsWith('unlivable-heat:', (string) $source->external_id);
            $this->assertTrue((bool) $source->pivot->is_active);
            $this->assertGreaterThan(0, (int) $source->pivot->weight);
            $keywords = json_decode((string) $source->pivot->keywords, true, 512, JSON_THROW_ON_ERROR);
            $this->assertNotEmpty($keywords);
        }
        $this->assertCount(10, $countdown->contentSources->pluck('external_id')->unique());
    }

    public function test_owned_patches_are_idempotent_and_roll_back_only_owned_records(): void
    {
        $this->runOwnedPatches();
        $countdown = Countdown::query()->where('slug', self::SLUG)->firstOrFail();
        $expectedCounts = [
            'projections' => $countdown->projections()->count(),
            'visualizations' => $countdown->visualizations()->count(),
            'news' => $countdown->news()->count(),
            'initiatives' => $countdown->initiatives()->count(),
            'sources' => $countdown->contentSources()->count(),
        ];

        $this->runOwnedPatches();
        $countdown->refresh();
        $this->assertSame($expectedCounts['projections'], $countdown->projections()->count());
        $this->assertSame($expectedCounts['visualizations'], $countdown->visualizations()->count());
        $this->assertSame($expectedCounts['news'], $countdown->news()->count());
        $this->assertSame($expectedCounts['initiatives'], $countdown->initiatives()->count());
        $this->assertSame($expectedCounts['sources'], $countdown->contentSources()->count());

        foreach (self::LEGACY_NEWS_URLS as $index => $legacyUrl) {
            $countdown->news()->create([
                'locale' => 'all',
                'title' => 'Legacy owned heat news '.($index + 1),
                'excerpt' => 'Owned record from the superseded media payload.',
                'source_url' => $legacyUrl,
                'image_path' => self::ASSET,
            ]);
        }
        foreach (self::LEGACY_INITIATIVE_URLS as $legacyUrl) {
            $countdown->initiatives()->create([
                'locale' => 'all',
                'type' => InitiativeType::Campaign,
                'title' => 'Legacy owned heat initiative',
                'excerpt' => 'Owned record from the superseded media payload.',
                'url' => $legacyUrl,
                'image_path' => self::ASSET,
            ]);
        }

        $newsPatch = $this->ownedPatch('2026_07_11_024040_seed_unlivable_heat_news');
        $sourcePatch = $this->ownedPatch('2026_07_11_024050_seed_unlivable_heat_content_sources');
        $initiativePatch = $this->ownedPatch('2026_07_11_024060_seed_unlivable_heat_initiatives');
        $newsPatch->up();
        $initiativePatch->up();
        $this->assertSame($expectedCounts['news'], $countdown->news()->count());
        $this->assertSame($expectedCounts['initiatives'], $countdown->initiatives()->count());
        $this->assertFalse($countdown->news()->whereIn('source_url', self::LEGACY_NEWS_URLS)->exists());
        $this->assertFalse($countdown->initiatives()->whereIn('url', self::LEGACY_INITIATIVE_URLS)->exists());

        $unownedCountdown = Countdown::query()->create([
            'slug' => 'rollback-sentinel',
            'title' => ['en' => 'Rollback sentinel'],
            'summary' => ['en' => 'Unowned record'],
            'image_path' => self::ASSET,
            'is_published' => false,
        ]);
        $unownedNews = $countdown->news()->create([
            'locale' => 'all',
            'title' => 'Unowned heat news',
            'excerpt' => 'Outside the patch-owned URL set.',
            'source_url' => 'https://unowned.test/heat-news',
            'image_path' => self::ASSET,
        ]);
        $unownedInitiative = $countdown->initiatives()->create([
            'locale' => 'all',
            'type' => InitiativeType::Resource,
            'title' => 'Unowned heat initiative',
            'excerpt' => 'Outside the patch-owned URL set.',
            'url' => 'https://unowned.test/heat-initiative',
            'image_path' => self::ASSET,
        ]);
        $unownedSource = ContentSource::query()->create([
            'type' => ContentSource::TYPE_WEBSITE,
            'provider' => 'rollback-qa',
            'name' => 'Unowned heat source',
            'external_id' => 'rollback-sentinel:heat-source',
            'source_url' => 'https://unowned.test/heat-source',
            'language' => 'en',
        ]);
        $countdown->contentSources()->attach($unownedSource->getKey(), ['is_active' => true, 'weight' => 1]);

        $sharedOwnedSource = $countdown->contentSources()->firstOrFail();
        $unownedCountdown->contentSources()->attach($sharedOwnedSource->getKey(), [
            'keywords' => $sharedOwnedSource->pivot->keywords,
            'excluded_keywords' => $sharedOwnedSource->pivot->excluded_keywords,
            'weight' => $sharedOwnedSource->pivot->weight,
            'is_active' => true,
        ]);

        $initiativePatch->down();
        $sourcePatch->down();
        $newsPatch->down();

        $this->assertTrue($countdown->news()->whereKey($unownedNews->getKey())->exists());
        $this->assertTrue($countdown->initiatives()->whereKey($unownedInitiative->getKey())->exists());
        $this->assertTrue($countdown->contentSources()->whereKey($unownedSource->getKey())->exists());
        $this->assertTrue(ContentSource::query()->whereKey($sharedOwnedSource->getKey())->exists());
        $this->assertTrue($unownedCountdown->contentSources()->whereKey($sharedOwnedSource->getKey())->exists());
        $this->assertSame(1, $countdown->news()->count());
        $this->assertSame(1, $countdown->initiatives()->count());
        $this->assertSame(1, $countdown->contentSources()->count());

        $newsPatch->up();
        $sourcePatch->up();
        $initiativePatch->up();

        foreach (array_reverse($this->patchDirectories()) as $directory) {
            $this->ownedPatch($directory)->down();
        }

        $this->assertFalse(Countdown::query()->where('slug', self::SLUG)->exists());
        $this->assertTrue(Countdown::query()->whereKey($unownedCountdown->getKey())->exists());
        $this->assertTrue(ContentSource::query()->whereKey($unownedSource->getKey())->exists());
        $this->assertTrue(ContentSource::query()->whereKey($sharedOwnedSource->getKey())->exists());
        $this->assertTrue($unownedCountdown->contentSources()->whereKey($sharedOwnedSource->getKey())->exists());
        $this->assertSame(1, ContentSource::query()->where('external_id', 'like', 'unlivable-heat:%')->count());
    }

    private function assertPersistedNewsMedia(Countdown $countdown): void
    {
        $previews = [];
        $videos = [];

        foreach ($countdown->news as $news) {
            $this->assertNotSame('', trim($news->title));
            $this->assertNotSame('', trim($news->excerpt));
            $this->assertNotSame('', trim((string) $news->source_name));
            $this->assertStringStartsWith('https://', (string) $news->source_url);
            $this->assertStringStartsWith('https://', (string) $news->preview_image_url);
            $this->assertSame(self::ASSET, $news->image_path);
            $this->assertFileExists(public_path($news->image_path));
            $this->assertStringNotContainsString('<iframe', strtolower(implode(' ', [
                $news->title,
                $news->excerpt,
                (string) $news->embed_url,
            ])));
            $previews[] = $news->preview_image_url;

            if ($news->content_type !== 'youtube_video') {
                continue;
            }

            $videos[] = $news->external_id;
            $this->assertYoutubeMetadata(
                (string) $news->source_url,
                (string) $news->external_provider,
                (string) $news->external_id,
                (string) $news->embed_url,
                (string) $news->preview_image_url,
            );
        }

        sort($videos);
        $this->assertSame(self::NEWS_VIDEO_IDS, $videos);
        $this->assertCount(10, array_unique($previews));
    }

    private function assertPersistedInitiativeMedia(Countdown $countdown): void
    {
        $previews = [];
        $videos = [];

        foreach ($countdown->initiatives as $initiative) {
            $this->assertNotSame('', trim($initiative->title));
            $this->assertNotSame('', trim($initiative->excerpt));
            $this->assertNotSame('', trim((string) $initiative->organization));
            $this->assertStringStartsWith('https://', $initiative->url);
            $this->assertStringStartsWith('https://', (string) $initiative->preview_image_url);
            $this->assertSame(self::ASSET, $initiative->image_path);
            $this->assertFileExists(public_path($initiative->image_path));
            $this->assertStringNotContainsString('<iframe', strtolower(implode(' ', [
                $initiative->title,
                $initiative->excerpt,
                (string) $initiative->body,
                (string) $initiative->embed_url,
            ])));
            $previews[] = $initiative->preview_image_url;

            if ($initiative->content_type !== 'youtube_video') {
                continue;
            }

            $videos[] = $initiative->external_id;
            $this->assertYoutubeMetadata(
                $initiative->url,
                (string) $initiative->external_provider,
                (string) $initiative->external_id,
                (string) $initiative->embed_url,
                (string) $initiative->preview_image_url,
            );
        }

        sort($videos);
        $this->assertSame(self::INITIATIVE_VIDEO_IDS, $videos);
        $this->assertCount(8, array_unique($previews));
    }

    private function assertPublicMediaPayloads(Countdown $countdown): void
    {
        $newsPayload = $this->getJson(route('countdowns.data.news', ['slug' => self::SLUG, 'lang' => 'en']))
            ->assertOk()
            ->json('data.news');
        $initiativePayload = $this->getJson(route('countdowns.data.initiatives', ['slug' => self::SLUG, 'lang' => 'en']))
            ->assertOk()
            ->json('data.initiatives');

        $this->assertCount(10, $newsPayload);
        $this->assertCount(8, $initiativePayload);
        $newsByUrl = $countdown->news->keyBy('source_url');
        $initiativeByUrl = $countdown->initiatives->keyBy('url');

        foreach ($newsPayload as $item) {
            $persisted = $newsByUrl->get($item['source_url']);
            $this->assertNotNull($persisted);
            $this->assertSame($persisted->preview_image_url, $item['image_url']);
            $this->assertStringStartsWith('https://', $item['image_url']);
            $this->assertNotSame(asset(self::ASSET), $item['image_url']);
            $this->assertStringNotContainsString('<iframe', strtolower(json_encode($item, JSON_THROW_ON_ERROR)));
            if ($item['content_type'] === 'youtube_video') {
                $this->assertPublicYoutubeMetadata($item['source_url'], $item['external_provider'], $item['embed_url'], $item['image_url']);
            }
        }
        $this->assertCount(10, array_unique(array_column($newsPayload, 'image_url')));
        $this->assertCount(2, array_filter($newsPayload, static fn (array $item): bool => $item['content_type'] === 'youtube_video'));

        foreach ($initiativePayload as $item) {
            $persisted = $initiativeByUrl->get($item['url']);
            $this->assertNotNull($persisted);
            $this->assertSame($persisted->preview_image_url, $item['image_url']);
            $this->assertStringStartsWith('https://', $item['image_url']);
            $this->assertNotSame(asset(self::ASSET), $item['image_url']);
            $this->assertStringNotContainsString('<iframe', strtolower(json_encode($item, JSON_THROW_ON_ERROR)));
            if ($item['content_type'] === 'youtube_video') {
                $this->assertPublicYoutubeMetadata($item['url'], $item['external_provider'], $item['embed_url'], $item['image_url']);
            }
        }
        $this->assertCount(8, array_unique(array_column($initiativePayload, 'image_url')));
        $this->assertCount(1, array_filter($initiativePayload, static fn (array $item): bool => $item['content_type'] === 'youtube_video'));
    }

    private function assertYoutubeMetadata(string $url, string $provider, string $id, string $embedUrl, string $previewUrl): void
    {
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9_-]{11}$/', $id);
        $this->assertSame('youtube', $provider);
        $this->assertSame('https://www.youtube.com/watch?v='.$id, $url);
        $this->assertSame('https://www.youtube.com/embed/'.$id, $embedUrl);
        $this->assertSame('https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg', $previewUrl);
    }

    private function assertPublicYoutubeMetadata(string $url, ?string $provider, ?string $embedUrl, string $previewUrl): void
    {
        $this->assertMatchesRegularExpression('#^https://www\.youtube\.com/watch\?v=([A-Za-z0-9_-]{11})$#', $url);
        preg_match('#[?&]v=([A-Za-z0-9_-]{11})$#', $url, $matches);
        $id = $matches[1] ?? '';
        $this->assertSame('youtube', $provider);
        $this->assertSame('https://www.youtube.com/embed/'.$id, $embedUrl);
        $this->assertSame('https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg', $previewUrl);
    }

    private function assertVisualizationEvidence(object $visualization): void
    {
        $this->assertNotEmpty($visualization->sources, $visualization->key);
        foreach ($visualization->sources as $source) {
            $this->assertStringStartsWith('https://', $source, $visualization->key);
        }
        $this->assertSame(self::LOCALES, array_keys($visualization->reasoning), $visualization->key);
        foreach (self::LOCALES as $locale) {
            $this->assertNotSame('', trim((string) $visualization->reasoning[$locale]), $visualization->key.' '.$locale);
        }
        $this->assertArrayNotHasKey('sources', $visualization->payload, $visualization->key);
        $this->assertArrayNotHasKey('reasoning', $visualization->payload, $visualization->key);
    }

    /** @return array<int, string> */
    private function patchDirectories(): array
    {
        return [
            '2026_07_11_024000_prepare_unlivable_heat',
            '2026_07_11_024010_seed_unlivable_heat_countdown',
            '2026_07_11_024020_seed_unlivable_heat_projections',
            '2026_07_11_024030_seed_unlivable_heat_visualizations',
            '2026_07_11_024040_seed_unlivable_heat_news',
            '2026_07_11_024050_seed_unlivable_heat_content_sources',
            '2026_07_11_024060_seed_unlivable_heat_initiatives',
        ];
    }

    private function runOwnedPatches(): void
    {
        foreach ($this->patchDirectories() as $directory) {
            $this->ownedPatch($directory)->up();
        }
    }

    private function ownedPatch(string $directory): Patch
    {
        return require base_path('database/patches/countdowns/unlivable_heat/'.$directory.'/patch.php');
    }
}
