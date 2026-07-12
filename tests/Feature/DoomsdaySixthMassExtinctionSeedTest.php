<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\InitiativeType;
use App\Enums\ProjectionType;
use App\Enums\VisualizationType;
use App\Models\ContentSource;
use App\Models\Countdown;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use SimoneBianco\Patches\Facades\Patches;
use Tests\TestCase;

final class DoomsdaySixthMassExtinctionSeedTest extends TestCase
{
    use RefreshDatabase;

    /** @var array<int, string> */
    private const PATCHES = [
        'countdowns/sixth_mass_extinction/2026_07_11_022000_prepare_sixth_mass_extinction_seed',
        'countdowns/sixth_mass_extinction/2026_07_11_022010_seed_sixth_mass_extinction_countdown',
        'countdowns/sixth_mass_extinction/2026_07_11_022020_seed_sixth_mass_extinction_projections',
        'countdowns/sixth_mass_extinction/2026_07_11_022030_seed_sixth_mass_extinction_visualizations',
        'countdowns/sixth_mass_extinction/2026_07_11_022040_seed_sixth_mass_extinction_news',
        'countdowns/sixth_mass_extinction/2026_07_11_022050_seed_sixth_mass_extinction_content_sources',
        'countdowns/sixth_mass_extinction/2026_07_11_022060_seed_sixth_mass_extinction_initiatives',
    ];

    /** @var array<int, string> */
    private const LOCALES = ['en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl'];

    public function test_local_patch_list_creates_complete_countdown_without_shared_seeder_changes(): void
    {
        $this->runLocalPatches();

        $countdown = $this->countdown();
        $this->assertSame('The Sixth Mass Extinction', $countdown->title['en']);
        $this->assertSame('La sesta estinzione di massa', $countdown->title['it']);
        $this->assertSame('2030-12-31 23:59:59', $countdown->target_date?->utc()->format('Y-m-d H:i:s'));
        $this->assertSame(4, $countdown->sort_order);
        $this->assertTrue($countdown->is_published);
        $this->assertSame('images/doomsday/uninhabitable_earth_separate.png', $countdown->image_path);
        $this->assertFileExists(public_path($countdown->image_path));
        $this->assertIsArray(getimagesize(public_path($countdown->image_path)) ?: null);

        foreach (['title', 'summary', 'description', 'causes', 'consequences', 'recommended_actions'] as $field) {
            $this->assertSame(self::LOCALES, array_keys($countdown->{$field}), $field);
        }

        $this->assertCount(3, $countdown->projections);
        $this->assertSame(
            [ProjectionType::Optimistic, ProjectionType::Neutral, ProjectionType::Pessimistic],
            $countdown->projections->sortBy('sort_order')->pluck('type')->values()->all(),
        );
        $this->assertSame([25, 50, 25], $countdown->projections->sortBy('sort_order')->pluck('probability_score')->values()->all());
        $projectionsByType = $countdown->projections->keyBy(fn ($projection): string => $projection->type->value);
        $this->assertSame('2029-06-30 23:59:59', $projectionsByType['pessimistic']->target_date?->utc()->format('Y-m-d H:i:s'));
        $this->assertSame('2030-12-31 23:59:59', $projectionsByType['neutral']->target_date?->utc()->format('Y-m-d H:i:s'));
        $this->assertSame('2050-12-31 23:59:59', $projectionsByType['optimistic']->target_date?->utc()->format('Y-m-d H:i:s'));
        $this->assertTrue($projectionsByType['pessimistic']->target_date->lt($projectionsByType['neutral']->target_date));
        $this->assertTrue($projectionsByType['neutral']->target_date->lt($projectionsByType['optimistic']->target_date));
        $this->assertSame(
            $projectionsByType['neutral']->target_date?->utc()->format('Y-m-d H:i:s'),
            $countdown->target_date?->utc()->format('Y-m-d H:i:s'),
        );
        foreach ($countdown->projections as $projection) {
            $this->assertSame(self::LOCALES, array_keys($projection->title), $projection->type->value.' title');
            $this->assertSame(self::LOCALES, array_keys($projection->summary), $projection->type->value.' summary');
            foreach (['classification', 'assessment_date', 'drivers', 'policy_deadline', 'measured_trajectory', 'implementation_delay', 'ecological_threshold', 'baseline', 'coverage_formula', 'probability_note', 'milestone', 'date_semantics', 'reasoning', 'date_basis', 'closure_fraction', 'coverage_checkpoint', 'limits', 'stop_condition', 'sources'] as $field) {
                $this->assertArrayHasKey($field, $projection->methodology, $projection->type->value.' '.$field);
            }
            $this->assertGreaterThanOrEqual(6, count($projection->methodology['sources']));
            foreach ($projection->methodology['sources'] as $source) {
                $this->assertStringStartsWith('https://', $source);
            }
        }

        $forbiddenMarkers = [
            'en' => ['the timer', 'main timer', 'current timer'],
            'it' => ['il timer', 'timer principale', 'timer corrente'],
            'fr' => ['le minuteur', 'minuteur principal', 'minuteur actuel'],
            'de' => ['der timer', 'haupttimer', 'aktueller timer'],
            'es' => ['el temporizador', 'temporizador principal', 'temporizador actual'],
            'nl' => ['de timer', 'hoofdtimer', 'huidige timer'],
            'sv' => ['timern', 'huvudtimern', 'aktuell timer'],
            'pl' => ['licznik', 'główny licznik', 'bieżący licznik'],
        ];
        $this->travelTo(CarbonImmutable::parse('2026-07-12 00:00:00', 'UTC'));
        foreach (self::LOCALES as $locale) {
            $description = $countdown->description[$locale];
            $this->assertNotSame('', trim($description));
            foreach (['2029', '2030', '2050'] as $year) {
                $this->assertStringContainsString($year, $description, $locale);
            }
            foreach ($forbiddenMarkers[$locale] as $marker) {
                $this->assertStringNotContainsString($marker, mb_strtolower($description), $locale);
            }

            $overview = $this->getJson(route('countdowns.data.overview', ['slug' => 'sixth-mass-extinction', 'lang' => $locale]))
                ->assertOk()
                ->json('data');
            $this->assertSame('pessimistic', $overview['main_projection']['type'], $locale);
            $this->assertStringStartsWith('2029-06-30T23:59:59', $overview['timer']['target_date'], $locale);
            $this->assertSame($description, $overview['description'], $locale);

            $localizedForecasts = $this->getJson(route('countdowns.data.forecasts', ['slug' => 'sixth-mass-extinction', 'lang' => $locale]))
                ->assertOk()
                ->json('data');
            $neutralSummary = collect($localizedForecasts['projections'])->firstWhere('type', 'neutral')['summary'];
            foreach (['2029', '2030', '2050'] as $year) {
                $this->assertStringContainsString($year, $neutralSummary, $locale);
            }
            foreach ($forbiddenMarkers[$locale] as $marker) {
                $this->assertStringNotContainsString($marker, mb_strtolower($neutralSummary), $locale);
            }
        }
        $this->travelBack();

        $forecasts = $this->getJson(route('countdowns.data.forecasts', ['slug' => 'sixth-mass-extinction', 'lang' => 'en']))
            ->assertOk()
            ->json('data');
        $publicDates = collect($forecasts['projections'])->mapWithKeys(
            fn (array $projection): array => [$projection['type'] => $projection['target_date']],
        );
        $this->assertStringStartsWith('2029-06-30T23:59:59', $publicDates['pessimistic']);
        $this->assertStringStartsWith('2030-12-31T23:59:59', $publicDates['neutral']);
        $this->assertStringStartsWith('2050-12-31T23:59:59', $publicDates['optimistic']);
        $this->assertSame('projection_curve', $forecasts['projection_chart']['key']);
        $this->assertSame(['2029-06-30', '2030-12-31', '2050-12-31'], $forecasts['projection_chart']['payload']['labels']);
        $this->assertNotEmpty($forecasts['projection_chart']['sources']);
        $this->assertNotSame('', trim((string) $forecasts['projection_chart']['reasoning']));

        $projectionChart = $countdown->projections
            ->flatMap(fn ($projection) => $projection->visualizations)
            ->firstWhere('key', 'projection_curve');
        $this->assertNotNull($projectionChart);
        $this->assertCount(6, $countdown->visualizations);
        $this->assertCount(11, $countdown->news);
        $this->assertCount(9, $countdown->initiatives);
        $this->assertCount(10, $countdown->contentSources);
        $this->assertSame(10, $countdown->contentSources->where('pivot.is_active', true)->count());
    }

    public function test_visualization_evidence_axes_and_formulas_are_reproducible(): void
    {
        $this->runLocalPatches();
        $countdown = $this->countdown();
        $projectionChart = $countdown->projections
            ->flatMap(fn ($projection) => $projection->visualizations)
            ->firstWhere('key', 'projection_curve');
        $this->assertNotNull($projectionChart);

        $allVisualizations = $countdown->visualizations->push($projectionChart);
        foreach ($allVisualizations as $visualization) {
            $this->assertSame(self::LOCALES, array_keys($visualization->title), $visualization->key.' title');
            $this->assertSame(self::LOCALES, array_keys($visualization->description), $visualization->key.' description');
            $this->assertNotEmpty($visualization->sources, $visualization->key);
            $this->assertSame(self::LOCALES, array_keys($visualization->reasoning), $visualization->key);
            $this->assertNotSame('', trim((string) $visualization->reasoning['en']), $visualization->key);
            foreach ($visualization->sources as $source) {
                $this->assertStringStartsWith('https://', $source, $visualization->key);
            }
            $this->assertArrayNotHasKey('sources', $visualization->payload, $visualization->key);
            $this->assertArrayNotHasKey('reasoning', $visualization->payload, $visualization->key);

            if (! in_array($visualization->type, [VisualizationType::Line, VisualizationType::Area, VisualizationType::Bar], true)) {
                continue;
            }

            $this->assertSame(2, $visualization->schema_version, $visualization->key);
            $this->assertArrayHasKey('axes', $visualization->payload, $visualization->key);
            $this->assertNotSame('', trim((string) $visualization->payload['axes']['x']['label']), $visualization->key);
            $this->assertNotSame('', trim((string) $visualization->payload['axes']['y']['unit']), $visualization->key);
            $labels = $visualization->payload['labels'];
            $series = $visualization->payload['series'];
            $xAxisType = $visualization->payload['axes']['x']['type'];
            $this->assertNotEmpty($labels, $visualization->key);
            $this->assertNotEmpty($series, $visualization->key);

            if ($visualization->type === VisualizationType::Bar) {
                $this->assertSame('category', $xAxisType, $visualization->key);
            } else {
                $this->assertContains($xAxisType, ['temporal', 'ordinal'], $visualization->key);
            }

            $nestedSeries = array_filter(
                $series,
                static fn (mixed $item): bool => is_array($item) && array_key_exists('values', $item),
            );
            if (count($nestedSeries) === count($series)) {
                foreach ($nestedSeries as $item) {
                    $this->assertCount(count($labels), $item['values'], $visualization->key);
                }
            } else {
                $this->assertCount(count($labels), $series, $visualization->key);
            }
        }

        $this->assertSame(VisualizationType::Bar, $projectionChart->type);
        $this->assertSame(['2029-06-30', '2030-12-31', '2050-12-31'], $projectionChart->payload['labels']);
        $this->assertSame('category', $projectionChart->payload['axes']['x']['type']);
        $this->assertSame('%', $projectionChart->payload['axes']['y']['unit']);
        $expectedCoverage = array_map(
            static fn (float $fraction): float => round(17.6 + $fraction * (30.0 - 17.6), 2),
            [0.2, 0.5, 1.0],
        );
        $this->assertEquals($expectedCoverage, array_map('floatval', $projectionChart->payload['series']));

        $livingPlanetIndex = $countdown->visualizations->firstWhere('key', 'living_planet_index');
        $this->assertEquals([100.0, round(100 * (1 - 0.73), 1)], array_map('floatval', $livingPlanetIndex?->payload['series'] ?? []));

        $wetlandExtent = $countdown->visualizations->firstWhere('key', 'wetland_extent_index');
        $this->assertEquals([100.0, round(100 * (1 - 0.22), 1)], array_map('floatval', $wetlandExtent?->payload['series'] ?? []));

        $protectedCoverage = $countdown->visualizations->firstWhere('key', 'protected_area_coverage');
        $this->assertEquals([17.6, 8.4], $protectedCoverage?->payload['series'][0]['values']);
        $this->assertEquals([30, 30], $protectedCoverage?->payload['series'][1]['values']);
        $this->assertSame('%', $protectedCoverage?->payload['axes']['y']['unit']);

        $threatenedGroups = $countdown->visualizations->firstWhere('key', 'threatened_vertebrate_groups');
        $this->assertEquals([41, 37, 27, 21, 13], $threatenedGroups?->payload['series']);
        $this->assertSame('Comprehensively assessed taxonomic group', $threatenedGroups?->payload['axes']['x']['label']);

        $forestLoss = $countdown->visualizations->firstWhere('key', 'forest_net_loss_rate');
        $this->assertEquals([7.8, 5.2, 4.7], $forestLoss?->payload['series']);
        $this->assertSame('million ha/year', $forestLoss?->payload['axes']['y']['unit']);
    }

    public function test_news_initiatives_and_sources_have_real_links_images_and_required_media_contract(): void
    {
        $this->runLocalPatches();
        $countdown = $this->countdown();

        $this->assertCount(11, $countdown->news);
        foreach ($countdown->news as $news) {
            $this->assertNotSame('', trim($news->title));
            $this->assertNotSame('', trim($news->excerpt));
            $this->assertStringStartsWith('https://', (string) $news->source_url);
            $this->assertStringStartsWith('https://', (string) $news->preview_image_url);
            $this->assertSame('images/doomsday/uninhabitable_earth_separate.png', $news->image_path);
            $this->assertStringNotContainsString('example.org', (string) $news->source_url);
            $this->assertStringNotContainsString('<iframe', strtolower(json_encode($news->toArray(), JSON_THROW_ON_ERROR)));
        }
        $this->assertCount($countdown->news->count(), $countdown->news->pluck('preview_image_url')->unique());

        $newsVideos = $countdown->news->where('content_type', 'youtube_video')->values();
        $this->assertCount(2, $newsVideos);
        $this->assertSame(['U6oP6Q-hVQI', 'jmvgQ5fBBLg'], $newsVideos->pluck('external_id')->sort()->values()->all());
        foreach ($newsVideos as $video) {
            $this->assertPersistedYoutubeVideo($video, 'source_url');
            $this->assertNotSame('', trim((string) $video->source_name));
        }

        $this->assertCount(9, $countdown->initiatives);
        foreach ($countdown->initiatives as $initiative) {
            $this->assertNotSame('', trim($initiative->title));
            $this->assertNotSame('', trim($initiative->excerpt));
            $this->assertStringStartsWith('https://', $initiative->url);
            $this->assertStringStartsWith('https://', (string) $initiative->preview_image_url);
            $this->assertSame('images/doomsday/uninhabitable_earth_separate.png', $initiative->image_path);
            $this->assertNotSame('', trim((string) $initiative->organization));
            $this->assertStringNotContainsString('<iframe', strtolower(json_encode($initiative->toArray(), JSON_THROW_ON_ERROR)));
        }
        $this->assertCount($countdown->initiatives->count(), $countdown->initiatives->pluck('preview_image_url')->unique());

        $initiativeVideos = $countdown->initiatives->where('content_type', 'youtube_video')->values();
        $this->assertCount(1, $initiativeVideos);
        $this->assertSame('XhjN8Xux2I4', $initiativeVideos->first()?->external_id);
        $this->assertSame(InitiativeType::Resource, $initiativeVideos->first()?->type);
        $this->assertSame('UN Environment Programme', $initiativeVideos->first()?->organization);
        $this->assertPersistedYoutubeVideo($initiativeVideos->first(), 'url');

        $publicNews = $this->getJson(route('countdowns.data.news', ['slug' => 'sixth-mass-extinction', 'lang' => 'en']))
            ->assertOk()
            ->assertJsonPath('data.countdown_slug', 'sixth-mass-extinction')
            ->json('data.news');
        $this->assertCount($countdown->news->count(), $publicNews);
        $persistedNewsByUrl = $countdown->news->keyBy('source_url');
        foreach ($publicNews as $item) {
            $persisted = $persistedNewsByUrl->get($item['source_url']);
            $this->assertNotNull($persisted);
            $this->assertSame($persisted->preview_image_url, $item['image_url']);
            $this->assertStringStartsWith('https://', $item['image_url']);
            if ($item['content_type'] === 'youtube_video') {
                $this->assertPublicYoutubeVideo($item, 'source_url');
            }
        }
        $this->assertCount(count($publicNews), array_unique(array_column($publicNews, 'image_url')));

        $publicInitiatives = $this->getJson(route('countdowns.data.initiatives', ['slug' => 'sixth-mass-extinction', 'lang' => 'en']))
            ->assertOk()
            ->assertJsonPath('data.countdown_slug', 'sixth-mass-extinction')
            ->json('data.initiatives');
        $this->assertCount($countdown->initiatives->count(), $publicInitiatives);
        $persistedInitiativesByUrl = $countdown->initiatives->keyBy('url');
        foreach ($publicInitiatives as $item) {
            $persisted = $persistedInitiativesByUrl->get($item['url']);
            $this->assertNotNull($persisted);
            $this->assertSame($persisted->preview_image_url, $item['image_url']);
            $this->assertStringStartsWith('https://', $item['image_url']);
            if ($item['content_type'] === 'youtube_video') {
                $this->assertPublicYoutubeVideo($item, 'url');
            }
        }
        $this->assertCount(count($publicInitiatives), array_unique(array_column($publicInitiatives, 'image_url')));

        $this->assertCount(10, $countdown->contentSources);
        foreach ($countdown->contentSources as $source) {
            $this->assertStringStartsWith('https://', (string) $source->source_url);
            $this->assertStringStartsWith('https://', (string) $source->feed_url);
            $this->assertNotSame('', trim((string) $source->external_id));
            $this->assertTrue($source->is_active);
            $this->assertTrue((bool) $source->pivot->is_active);
            $this->assertGreaterThanOrEqual(100, (int) $source->pivot->weight);
            $this->assertNotEmpty(json_decode((string) $source->pivot->keywords, true, flags: JSON_THROW_ON_ERROR));
        }
    }

    public function test_patches_are_idempotent_and_rollbacks_preserve_unowned_records(): void
    {
        $this->runLocalPatches();
        $countdown = $this->countdown();
        $before = [
            'projections' => $countdown->projections()->count(),
            'visualizations' => $countdown->visualizations()->count(),
            'projection_visualizations' => $countdown->projections()->withCount('visualizations')->get()->sum('visualizations_count'),
            'news' => $countdown->news()->count(),
            'initiatives' => $countdown->initiatives()->count(),
            'sources' => $countdown->contentSources()->count(),
        ];

        foreach (self::PATCHES as $identifier) {
            $this->loadPatch($identifier)->up();
        }

        $countdown->refresh();
        $this->assertSame($before['projections'], $countdown->projections()->count());
        $this->assertSame($before['visualizations'], $countdown->visualizations()->count());
        $this->assertSame($before['projection_visualizations'], $countdown->projections()->withCount('visualizations')->get()->sum('visualizations_count'));
        $this->assertSame($before['news'], $countdown->news()->count());
        $this->assertSame($before['initiatives'], $countdown->initiatives()->count());
        $this->assertSame($before['sources'], $countdown->contentSources()->count());

        $otherCountdown = Countdown::query()->create([
            'slug' => 'unowned-biodiversity-sentinel',
            'title' => ['en' => 'Unowned countdown'],
            'summary' => ['en' => 'Outside patch ownership'],
            'image_path' => 'images/doomsday/uninhabitable_earth_separate.png',
            'sort_order' => 999,
        ]);
        $unownedNews = $countdown->news()->create([
            'locale' => 'all', 'title' => 'Unowned news', 'excerpt' => 'Outside patch-owned URLs.',
            'source_url' => 'https://unowned.test/biodiversity-news', 'image_path' => 'images/doomsday/uninhabitable_earth_separate.png',
        ]);
        $unownedInitiative = $countdown->initiatives()->create([
            'locale' => 'all', 'type' => InitiativeType::Resource, 'title' => 'Unowned initiative',
            'excerpt' => 'Outside patch-owned URLs.', 'url' => 'https://unowned.test/biodiversity-initiative',
            'image_path' => 'images/doomsday/uninhabitable_earth_separate.png',
        ]);
        $unownedProjection = $countdown->projections()->create([
            'type' => ProjectionType::Other, 'title' => ['en' => 'Unowned projection'],
            'summary' => ['en' => 'Outside patch types'], 'confidence_score' => 1, 'probability_score' => 1,
            'trend' => 'sentinel', 'sort_order' => 999,
        ]);
        $unownedVisualization = $countdown->visualizations()->create([
            'key' => 'unowned_biodiversity_visualization', 'type' => VisualizationType::Kpi,
            'title' => ['en' => 'Unowned visualization'], 'description' => ['en' => 'Outside patch keys'],
            'sources' => ['https://unowned.test/source'], 'reasoning' => ['en' => 'Sentinel'],
            'payload' => ['items' => [['label' => 'Sentinel', 'value' => '1']]], 'schema_version' => 1, 'sort_order' => 999,
        ]);
        $unownedSource = ContentSource::query()->create([
            'type' => ContentSource::TYPE_RSS_FEED, 'provider' => ContentSource::PROVIDER_GOOGLE_NEWS,
            'name' => 'Unowned biodiversity source', 'external_id' => 'unowned-biodiversity-source',
            'source_url' => 'https://unowned.test/source', 'feed_url' => 'https://unowned.test/feed',
            'weight' => 1, 'is_active' => true,
        ]);
        $countdown->contentSources()->attach($unownedSource->getKey(), ['weight' => 1, 'is_active' => true]);

        $this->loadPatch(self::PATCHES[6])->down();
        $this->assertTrue($countdown->initiatives()->whereKey($unownedInitiative->getKey())->exists());
        $this->assertFalse($countdown->initiatives()->where('url', 'https://www.cbd.int/gbf/targets/2')->exists());
        $this->assertFalse($countdown->initiatives()->where('url', 'https://www.youtube.com/watch?v=XhjN8Xux2I4')->exists());

        $this->loadPatch(self::PATCHES[5])->down();
        $this->assertTrue(ContentSource::query()->whereKey($unownedSource->getKey())->exists());
        $this->assertTrue($countdown->contentSources()->whereKey($unownedSource->getKey())->exists());
        $this->assertFalse(ContentSource::query()->where('external_id', 'biodiversity-cbd-en')->exists());

        $this->loadPatch(self::PATCHES[4])->down();
        $this->assertTrue($countdown->news()->whereKey($unownedNews->getKey())->exists());
        $this->assertFalse($countdown->news()->where('source_url', 'https://www.youtube.com/watch?v=jmvgQ5fBBLg')->exists());
        $this->assertFalse($countdown->news()->where('source_url', 'https://www.youtube.com/watch?v=U6oP6Q-hVQI')->exists());

        $this->loadPatch(self::PATCHES[3])->down();
        $this->assertTrue($countdown->visualizations()->whereKey($unownedVisualization->getKey())->exists());
        $this->assertFalse($countdown->visualizations()->where('key', 'living_planet_index')->exists());

        $this->loadPatch(self::PATCHES[2])->down();
        $this->assertTrue($countdown->projections()->whereKey($unownedProjection->getKey())->exists());
        $this->assertFalse($countdown->projections()->where('type', ProjectionType::Neutral->value)->exists());

        $this->loadPatch(self::PATCHES[1])->down();
        $this->loadPatch(self::PATCHES[0])->down();
        $this->assertFalse(Countdown::query()->where('slug', 'sixth-mass-extinction')->exists());
        $this->assertTrue(Countdown::query()->whereKey($otherCountdown->getKey())->exists());
        $this->assertTrue(ContentSource::query()->whereKey($unownedSource->getKey())->exists());
    }

    private function assertPersistedYoutubeVideo(object $item, string $urlField): void
    {
        $id = (string) $item->external_id;
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9_-]{11}$/', $id);
        $this->assertSame('youtube_video', $item->content_type);
        $this->assertSame('youtube', $item->external_provider);
        $this->assertSame('https://www.youtube.com/watch?v='.$id, $item->{$urlField});
        $this->assertSame('https://www.youtube.com/embed/'.$id, $item->embed_url);
        $this->assertSame('https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg', $item->preview_image_url);
    }

    /** @param array<string, mixed> $item */
    private function assertPublicYoutubeVideo(array $item, string $urlField): void
    {
        $query = [];
        parse_str((string) parse_url($item[$urlField], PHP_URL_QUERY), $query);
        $id = (string) ($query['v'] ?? '');

        $this->assertMatchesRegularExpression('/^[A-Za-z0-9_-]{11}$/', $id);
        $this->assertSame('youtube_video', $item['content_type']);
        $this->assertSame('youtube', $item['external_provider']);
        $this->assertSame('https://www.youtube.com/watch?v='.$id, $item[$urlField]);
        $this->assertSame('https://www.youtube.com/embed/'.$id, $item['embed_url']);
        $this->assertSame('https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg', $item['image_url']);
        $this->assertStringNotContainsString('<iframe', strtolower(json_encode($item, JSON_THROW_ON_ERROR)));
    }

    private function runLocalPatches(): void
    {
        foreach (self::PATCHES as $identifier) {
            $messages = [];
            $success = Patches::runSinglePatch($identifier, static function (string $message) use (&$messages): void {
                $messages[] = $message;
            });
            $this->assertTrue($success, $identifier."\n".implode("\n", $messages));
        }
    }

    private function countdown(): Countdown
    {
        return Countdown::query()
            ->where('slug', 'sixth-mass-extinction')
            ->with(['projections.visualizations', 'visualizations', 'news', 'initiatives', 'contentSources'])
            ->firstOrFail();
    }

    private function loadPatch(string $identifier): object
    {
        return require base_path('database/patches/'.$identifier.'/patch.php');
    }
}
