<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProjectionType;
use App\Enums\VisualizationType;
use App\Models\ContentSource;
use App\Models\Countdown;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use SimoneBianco\Patches\Facades\Patches;
use Tests\TestCase;

final class DoomsdayAiJobApocalypseSeedTest extends TestCase
{
    use RefreshDatabase;

    private const SLUG = 'ai-job-apocalypse';

    private const LOCALES = ['en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl'];

    private const PATCHES = [
        'countdowns/ai_job_apocalypse/2026_07_11_021000_prepare_ai_job_apocalypse',
        'countdowns/ai_job_apocalypse/2026_07_11_021010_seed_ai_job_apocalypse_countdown',
        'countdowns/ai_job_apocalypse/2026_07_11_021020_seed_ai_job_apocalypse_projections',
        'countdowns/ai_job_apocalypse/2026_07_11_021030_seed_ai_job_apocalypse_visualizations',
        'countdowns/ai_job_apocalypse/2026_07_11_021040_seed_ai_job_apocalypse_news',
        'countdowns/ai_job_apocalypse/2026_07_11_021050_seed_ai_job_apocalypse_content_sources',
        'countdowns/ai_job_apocalypse/2026_07_11_021060_seed_ai_job_apocalypse_initiatives',
    ];

    public function test_local_patches_create_complete_evidence_rich_ai_countdown(): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-07-12 00:00:00', 'UTC'));
        $this->runAiPatches();

        $countdown = $this->aiCountdown();
        $projectionData = require base_path('database/patches/countdowns/ai_job_apocalypse/2026_07_11_021020_seed_ai_job_apocalypse_projections/data.php');
        $targetDates = $projectionData->targetDates();

        $this->assertSame('AI Job Apocalypse', $countdown->title['en']);
        $this->assertSame('images/doomsday/ai_job_apocalypse.png', $countdown->image_path);
        $this->assertFileExists(public_path($countdown->image_path));
        $this->assertSame(3, $countdown->sort_order);
        $this->assertTrue($countdown->is_published);
        $this->assertSame($targetDates['neutral'], $countdown->target_date?->utc()->format('Y-m-d H:i:s'));
        $this->assertTrue(CarbonImmutable::parse($targetDates['neutral'], 'UTC')->lessThan(CarbonImmutable::parse($targetDates['optimistic'], 'UTC')));
        $this->assertLocalized($countdown->title);
        $this->assertLocalized($countdown->summary);
        $this->assertLocalized($countdown->description);
        $neutralSummary = $countdown->projections->firstWhere('type', ProjectionType::Neutral)?->summary ?? [];
        $obsoleteMarkers = ['en' => ['displays the neutral', 'main timer'], 'it' => ['mostra il checkpoint neutrale', 'timer principale'], 'fr' => ['affiche le jalon neutre', 'minuteur principal'], 'de' => ['zeigt den neutralen', 'haupttimer'], 'es' => ['muestra el punto neutral', 'temporizador principal'], 'nl' => ['toont het neutrale', 'hoofdtimer'], 'sv' => ['visar den neutrala', 'huvudtimern'], 'pl' => ['pokazuje neutralny punkt', 'główny licznik']];
        foreach (self::LOCALES as $locale) {
            $copy = strtolower($countdown->summary[$locale].' '.$countdown->description[$locale].' '.$neutralSummary[$locale]);
            foreach ($obsoleteMarkers[$locale] as $marker) {
                $this->assertStringNotContainsString($marker, $copy, $locale);
            }
        }
        $this->assertLocalizedLists($countdown->causes);
        $this->assertLocalizedLists($countdown->consequences);
        $this->assertLocalizedLists($countdown->recommended_actions);

        $this->assertCount(3, $countdown->projections);
        $this->assertSame(
            [ProjectionType::Optimistic, ProjectionType::Neutral, ProjectionType::Pessimistic],
            $countdown->projections->sortBy('sort_order')->pluck('type')->values()->all(),
        );
        foreach ($countdown->projections as $projection) {
            $scenario = $projection->type->value;
            $this->assertLocalized($projection->title);
            $this->assertLocalized($projection->summary);
            $this->assertSame(0, $projection->probability_score, $scenario);
            $this->assertSame($targetDates[$scenario], $projection->target_date?->utc()->format('Y-m-d H:i:s'), $scenario);
            $this->assertSame('Editorial temporal milestone, not an outcome probability or official forecast.', $projection->methodology['nature']);
            $this->assertSame('2026-07-11', $projection->methodology['assessed_at']);
            $this->assertSame($scenario, $projection->methodology['scenario']);
            $this->assertNotSame('', trim($projection->methodology['target_date_basis']), $scenario);
            $this->assertNotSame('', trim($projection->methodology['semantics']), $scenario);
            $this->assertNotEmpty($projection->methodology['drivers'], $scenario);
            $this->assertNotSame('', trim($projection->methodology['reasoning']), $scenario);
            $this->assertNotEmpty($projection->methodology['limits'], $scenario);
            $this->assertNotEmpty($projection->methodology['stop_conditions'], $scenario);
            $this->assertNotEmpty($projection->methodology['sources'], $scenario);
            foreach ($projection->methodology['sources'] as $source) {
                $this->assertStringStartsWith('https://', $source, $scenario);
            }
        }

        $overview = $this->getJson(route('countdowns.data.overview', ['slug' => self::SLUG, 'lang' => 'en']))
            ->assertOk()
            ->json('data');
        $this->assertSame('pessimistic', $overview['main_projection']['type']);
        $this->assertSame($targetDates['pessimistic'], CarbonImmutable::parse($overview['timer']['target_date'])->utc()->format('Y-m-d H:i:s'));
        foreach (['2027', '2030', '2035'] as $year) {
            $this->assertStringContainsString($year, $overview['summary']);
            $this->assertStringContainsString($year, $overview['description']);
        }
        foreach (['Pessimistic', 'Neutral', 'Optimistic', 'mass unemployment', 'occupational exposure', 'observed automation', 'augmentation', 'displacement', 'job creation'] as $marker) {
            $this->assertStringContainsString($marker, $overview['description']);
        }
        $forecasts = $this->getJson(route('countdowns.data.forecasts', ['slug' => self::SLUG, 'lang' => 'en']))
            ->assertOk()
            ->json('data');
        foreach ($forecasts['projections'] as $projection) {
            $this->assertSame($targetDates[$projection['type']], CarbonImmutable::parse($projection['target_date'])->utc()->format('Y-m-d H:i:s'), $projection['type']);
        }
        $neutralForecast = collect($forecasts['projections'])->firstWhere('type', 'neutral');
        $this->assertStringContainsString('2030', $neutralForecast['summary']);
        $this->assertStringNotContainsString('main timer', strtolower($neutralForecast['summary']));
        $this->assertSame('projection_curve', $forecasts['projection_chart']['key']);

        $projectionChart = $countdown->projections
            ->firstWhere('type', ProjectionType::Neutral)
            ?->visualizations
            ->firstWhere('key', 'projection_curve');
        $this->assertNotNull($projectionChart);
        $this->assertCount(7, $countdown->visualizations);

        $allVisualizations = $countdown->visualizations->push($projectionChart);
        foreach ($allVisualizations as $visualization) {
            $this->assertNotEmpty($visualization->sources, $visualization->key);
            foreach ($visualization->sources as $source) {
                $this->assertStringStartsWith('https://', $source, $visualization->key);
            }
            $this->assertLocalized($visualization->title, $visualization->key.' title');
            $this->assertLocalized($visualization->description, $visualization->key.' description');
            $this->assertLocalized($visualization->reasoning, $visualization->key.' reasoning');
            $this->assertArrayNotHasKey('sources', $visualization->payload, $visualization->key);
            $this->assertArrayNotHasKey('reasoning', $visualization->payload, $visualization->key);

            if (! in_array($visualization->type, [VisualizationType::Line, VisualizationType::Area, VisualizationType::Bar], true)) {
                continue;
            }

            $this->assertSame(2, $visualization->schema_version, $visualization->key);
            $this->assertArrayHasKey('axes', $visualization->payload, $visualization->key);
            $this->assertNotSame('', trim((string) $visualization->payload['axes']['x']['label']), $visualization->key);
            $this->assertNotSame('', trim((string) $visualization->payload['axes']['y']['label']), $visualization->key);
            $this->assertNotSame('', trim((string) $visualization->payload['axes']['y']['unit']), $visualization->key);
            $this->assertContains($visualization->payload['axes']['y']['format'], ['integer', 'decimal', 'percent', 'currency']);
            $this->assertNotEmpty($visualization->payload['labels'], $visualization->key);
            $this->assertNotEmpty($visualization->payload['series'], $visualization->key);

            if ($visualization->type === VisualizationType::Bar) {
                $this->assertSame('category', $visualization->payload['axes']['x']['type'], $visualization->key);
            } else {
                $this->assertContains($visualization->payload['axes']['x']['type'], ['temporal', 'ordinal'], $visualization->key);
            }

            $labelCount = count($visualization->payload['labels']);
            foreach ($visualization->payload['series'] as $series) {
                $values = is_array($series) ? $series['values'] : $visualization->payload['series'];
                $this->assertCount($labelCount, $values, $visualization->key);
                if (! is_array($series)) {
                    break;
                }
                $this->assertArrayNotHasKey('unit', $series, $visualization->key);
                $this->assertArrayNotHasKey('format', $series, $visualization->key);
            }
        }

        $this->assertGreaterThanOrEqual(2, $countdown->visualizations->whereIn('type', [VisualizationType::Line, VisualizationType::Area])->count());
        $this->assertGreaterThanOrEqual(1, $countdown->visualizations->where('type', VisualizationType::Bar)->count());
        $this->assertGreaterThanOrEqual(1, $countdown->visualizations->where('type', VisualizationType::Kpi)->count());

        $this->assertCount(11, $countdown->news);
        foreach ($countdown->news as $news) {
            $this->assertNotSame('', trim($news->title));
            $this->assertNotSame('', trim($news->excerpt));
            $this->assertStringStartsWith('https://', (string) $news->source_url);
            $this->assertStringStartsWith('https://', (string) $news->preview_image_url);
            $this->assertSame('images/doomsday/ai_job_apocalypse.png', $news->image_path);
            $this->assertFileExists(public_path($news->image_path));
            $this->assertStringNotContainsString('<iframe', strtolower((string) json_encode($news->getAttributes(), JSON_THROW_ON_ERROR)));
        }
        $this->assertCount(11, $countdown->news->pluck('preview_image_url')->unique());

        $newsVideos = $countdown->news->where('content_type', 'youtube_video')->values();
        $this->assertGreaterThanOrEqual(2, $newsVideos->count());
        foreach ($newsVideos as $video) {
            $this->assertCanonicalYouTubeRecord($video, (string) $video->source_url);
        }

        $this->assertCount(9, $countdown->initiatives);
        foreach ($countdown->initiatives as $initiative) {
            $this->assertNotSame('', trim($initiative->title));
            $this->assertNotSame('', trim($initiative->excerpt));
            $this->assertNotSame('', trim((string) $initiative->organization));
            $this->assertStringStartsWith('https://', $initiative->url);
            $this->assertStringStartsWith('https://', (string) $initiative->preview_image_url);
            $this->assertSame('images/doomsday/ai_job_apocalypse.png', $initiative->image_path);
            $this->assertFileExists(public_path($initiative->image_path));
            $this->assertStringNotContainsString('<iframe', strtolower((string) json_encode($initiative->getAttributes(), JSON_THROW_ON_ERROR)));
        }
        $this->assertCount(9, $countdown->initiatives->pluck('preview_image_url')->unique());

        $initiativeVideos = $countdown->initiatives->where('content_type', 'youtube_video')->values();
        $this->assertGreaterThanOrEqual(1, $initiativeVideos->count());
        foreach ($initiativeVideos as $video) {
            $this->assertCanonicalYouTubeRecord($video, $video->url);
        }

        $newsPayload = $this->getJson(route('countdowns.data.news', ['slug' => self::SLUG, 'lang' => 'en']))
            ->assertOk()
            ->json('data');
        $this->assertSame(self::SLUG, $newsPayload['countdown_slug']);
        $this->assertCount(11, $newsPayload['news']);
        foreach ($newsPayload['news'] as $item) {
            $persisted = $countdown->news->firstWhere('source_url', $item['source_url']);
            $this->assertNotNull($persisted);
            $this->assertSame($persisted->preview_image_url, $item['image_url']);
            $this->assertStringStartsWith('https://', $item['image_url']);
            if ($item['content_type'] === 'youtube_video') {
                $this->assertCanonicalYouTubeDto($item, 'source_url');
            }
        }
        $this->assertCount(11, array_unique(array_column($newsPayload['news'], 'image_url')));
        $this->assertStringNotContainsString('<iframe', strtolower((string) json_encode($newsPayload, JSON_THROW_ON_ERROR)));

        $initiativesPayload = $this->getJson(route('countdowns.data.initiatives', ['slug' => self::SLUG, 'lang' => 'en']))
            ->assertOk()
            ->json('data');
        $this->assertSame(self::SLUG, $initiativesPayload['countdown_slug']);
        $this->assertCount(9, $initiativesPayload['initiatives']);
        foreach ($initiativesPayload['initiatives'] as $item) {
            $persisted = $countdown->initiatives->firstWhere('url', $item['url']);
            $this->assertNotNull($persisted);
            $this->assertSame($persisted->preview_image_url, $item['image_url']);
            $this->assertStringStartsWith('https://', $item['image_url']);
            if ($item['content_type'] === 'youtube_video') {
                $this->assertCanonicalYouTubeDto($item, 'url');
            }
        }
        $this->assertCount(9, array_unique(array_column($initiativesPayload['initiatives'], 'image_url')));
        $this->assertStringNotContainsString('<iframe', strtolower((string) json_encode($initiativesPayload, JSON_THROW_ON_ERROR)));

        $this->assertCount(18, $countdown->contentSources);
        $this->assertEmpty(array_diff([
            'ec.europa.eu/ai-act-implementation-timeline',
            'ec.europa.eu/digital-decade-2030-targets',
            'eib.org/eibis-2025-ai-adoption',
            'oecd.org/ai-trajectories-2030',
            'cedefop.europa.eu/ai-skills-2035',
            'bls.gov/ai-employment-projections-2033',
        ], $countdown->contentSources->pluck('external_id')->all()));
        foreach ($countdown->contentSources as $source) {
            $this->assertStringStartsWith('https://', (string) $source->source_url);
            $this->assertNotSame('', trim((string) $source->external_id));
            $this->assertTrue((bool) $source->pivot->is_active);
            $this->assertGreaterThan(0, (int) $source->pivot->weight);
            $keywords = json_decode((string) $source->pivot->keywords, true, flags: JSON_THROW_ON_ERROR);
            $this->assertNotEmpty($keywords);
        }
    }

    public function test_derived_visualization_formulas_recalculate_from_declared_inputs(): void
    {
        $this->runAiPatches();
        $countdown = $this->aiCountdown();
        $data = require base_path('database/patches/countdowns/ai_job_apocalypse/2026_07_11_021030_seed_ai_job_apocalypse_visualizations/data.php');

        $projectionChart = $countdown->projections
            ->firstWhere('type', ProjectionType::Neutral)
            ?->visualizations
            ->firstWhere('key', 'projection_curve');
        $this->assertNotNull($projectionChart);
        $this->assertSame('temporal', $projectionChart->payload['axes']['x']['type']);
        $this->assertSame('% of scenario interval', $projectionChart->payload['axes']['y']['unit']);
        $this->assertSame(
            array_map(
                static fn (string $date): string => CarbonImmutable::parse($date, 'UTC')->format('Y-m-d'),
                $data->projectionCheckpoints(),
            ),
            $projectionChart->payload['labels'],
        );

        $baseline = CarbonImmutable::parse($data->projectionBaselineDate(), 'UTC');
        $checkpoints = array_map(
            static fn (string $date): CarbonImmutable => CarbonImmutable::parse($date, 'UTC'),
            $data->projectionCheckpoints(),
        );
        foreach ($data->scenarioTargetDates() as $name => $targetDate) {
            $target = CarbonImmutable::parse($targetDate, 'UTC');
            $duration = max(1, $target->getTimestamp() - $baseline->getTimestamp());
            $expected = array_map(
                static fn (CarbonImmutable $checkpoint): float => round(
                    min(1, max(0, ($checkpoint->getTimestamp() - $baseline->getTimestamp()) / $duration)) * 100,
                    1,
                ),
                $checkpoints,
            );
            $this->assertEquals($expected, collect($projectionChart->payload['series'])->firstWhere('name', $name)['values'], $name);
        }

        $adopters = $data->leadingAdopterInputs();
        $adoptionChart = $countdown->visualizations->firstWhere('key', 'leading_ai_adopters');
        $this->assertNotNull($adoptionChart);
        foreach ($adopters as $country => $input) {
            $expected2024 = round($input['level_2025'] - $input['increase_from_2024'], 1);
            $this->assertEquals([$expected2024, $input['level_2025']], collect($adoptionChart->payload['series'])->firstWhere('name', $country)['values'], $country);
        }

        $weighted = $countdown->visualizations->firstWhere('key', 'weighted_high_exposure');
        $this->assertNotNull($weighted);
        $scores = $data->weightedExposureScores();
        $expectedWeighted = [];
        foreach ($data->weightedExposureInputs() as $input) {
            $expectedWeighted[] = round(($input['g3'] * $scores['g3']) + ($input['g4'] * $scores['g4']), 2);
        }
        $this->assertEquals($expectedWeighted, $weighted->payload['series']);

        $reskilling = $data->reskillingInputs();
        $gapKpi = $countdown->visualizations->firstWhere('key', 'reskilling_gap');
        $this->assertNotNull($gapKpi);
        $expectedGap = $reskilling['employees_reporting_need'] - $reskilling['employees_trained'];
        $this->assertSame($expectedGap.' pp', collect($gapKpi->payload['items'])->firstWhere('label', 'Derived training gap')['value']);
    }

    public function test_local_patch_replay_is_idempotent_and_removes_owned_legacy_media_urls(): void
    {
        $this->runAiPatches();
        $before = $this->relationCounts();
        $countdown = $this->aiCountdown();
        $newsData = require base_path('database/patches/countdowns/ai_job_apocalypse/2026_07_11_021040_seed_ai_job_apocalypse_news/data.php');
        $initiativeData = require base_path('database/patches/countdowns/ai_job_apocalypse/2026_07_11_021060_seed_ai_job_apocalypse_initiatives/data.php');

        foreach ($newsData->legacySourceUrls() as $index => $sourceUrl) {
            $countdown->news()->create([
                'locale' => 'all',
                'title' => 'Legacy AI news '.$index,
                'excerpt' => 'Legacy record replaced by canonical media data.',
                'source_url' => $sourceUrl,
                'image_path' => 'images/doomsday/ai_job_apocalypse.png',
                'sort_order' => 100 + $index,
            ]);
        }
        foreach ($initiativeData->legacyUrls() as $index => $url) {
            $countdown->initiatives()->create([
                'locale' => 'all',
                'type' => 'resource',
                'title' => 'Legacy AI initiative '.$index,
                'excerpt' => 'Legacy record replaced by canonical media data.',
                'url' => $url,
                'image_path' => 'images/doomsday/ai_job_apocalypse.png',
                'sort_order' => 100 + $index,
            ]);
        }

        $this->runAiPatches();

        $this->assertSame($before, $this->relationCounts());
        foreach ($newsData->legacySourceUrls() as $sourceUrl) {
            $this->assertFalse($this->aiCountdown()->news->contains('source_url', $sourceUrl));
        }
        foreach ($initiativeData->legacyUrls() as $url) {
            $this->assertFalse($this->aiCountdown()->initiatives->contains('url', $url));
        }
        $this->assertCount(11, $this->aiCountdown()->news->pluck('source_url')->unique());
        $this->assertCount(9, $this->aiCountdown()->initiatives->pluck('url')->unique());
    }

    public function test_reverse_down_is_scoped_and_preserves_unowned_records_and_shared_sources(): void
    {
        $this->runAiPatches();
        $ai = $this->aiCountdown();
        $sharedSource = $ai->contentSources->firstOrFail();

        $sentinel = Countdown::query()->create([
            'slug' => 'ai-stream-sentinel',
            'title' => array_fill_keys(self::LOCALES, 'Sentinel'),
            'summary' => array_fill_keys(self::LOCALES, 'Sentinel summary'),
            'image_path' => 'images/doomsday/ai_job_apocalypse.png',
            'is_published' => false,
        ]);
        $sentinel->contentSources()->syncWithoutDetaching([
            $sharedSource->getKey() => [
                'keywords' => json_encode(['sentinel'], JSON_THROW_ON_ERROR),
                'excluded_keywords' => json_encode([], JSON_THROW_ON_ERROR),
                'weight' => 1,
                'is_active' => true,
            ],
        ]);
        $unownedSource = ContentSource::query()->create([
            'type' => ContentSource::TYPE_WEBSITE,
            'provider' => 'sentinel',
            'name' => 'Sentinel source',
            'external_id' => 'sentinel/source',
            'source_url' => 'https://example.com/sentinel-source',
            'language' => 'en',
            'weight' => 1,
            'is_active' => true,
        ]);
        $sentinel->contentSources()->syncWithoutDetaching([$unownedSource->getKey()]);

        $this->rollbackAiPatches();

        $this->assertFalse(Countdown::query()->where('slug', self::SLUG)->exists());
        $this->assertTrue(Countdown::query()->where('slug', 'ai-stream-sentinel')->exists());
        $this->assertTrue(ContentSource::query()->whereKey($sharedSource->getKey())->exists());
        $this->assertTrue(ContentSource::query()->whereKey($unownedSource->getKey())->exists());
        $this->assertTrue($sentinel->fresh()->contentSources()->whereKey($sharedSource->getKey())->exists());
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();
        parent::tearDown();
    }

    private function runAiPatches(): void
    {
        foreach (self::PATCHES as $patch) {
            $messages = [];
            $success = Patches::runSinglePatch($patch, static function (string $message) use (&$messages): void {
                $messages[] = $message;
            });
            $this->assertTrue($success, $patch." failed:\n".implode("\n", $messages));
        }
    }

    private function rollbackAiPatches(): void
    {
        foreach (array_reverse(self::PATCHES) as $patch) {
            $instance = require base_path('database/patches/'.$patch.'/patch.php');
            $instance->down();
        }
    }

    private function aiCountdown(): Countdown
    {
        return Countdown::query()
            ->where('slug', self::SLUG)
            ->with(['projections.visualizations', 'visualizations', 'news', 'initiatives', 'contentSources'])
            ->firstOrFail();
    }

    /** @return array<string, int> */
    private function relationCounts(): array
    {
        $countdown = $this->aiCountdown();

        return [
            'countdowns' => Countdown::query()->where('slug', self::SLUG)->count(),
            'projections' => $countdown->projections->count(),
            'projection_visualizations' => $countdown->projections->sum(fn ($projection): int => $projection->visualizations->count()),
            'visualizations' => $countdown->visualizations->count(),
            'news' => $countdown->news->count(),
            'initiatives' => $countdown->initiatives->count(),
            'content_sources' => $countdown->contentSources->count(),
        ];
    }

    private function assertCanonicalYouTubeRecord(object $record, string $watchUrl): void
    {
        $videoId = (string) $record->external_id;
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9_-]{11}$/', $videoId);
        $this->assertSame('youtube', $record->external_provider);
        $this->assertSame('youtube_video', $record->content_type);
        $this->assertSame('https://www.youtube.com/watch?v='.$videoId, $watchUrl);
        $this->assertSame('https://www.youtube.com/embed/'.$videoId, $record->embed_url);
        $this->assertSame('https://i.ytimg.com/vi/'.$videoId.'/hqdefault.jpg', $record->preview_image_url);
    }

    /** @param array<string, mixed> $item */
    private function assertCanonicalYouTubeDto(array $item, string $urlKey): void
    {
        parse_str((string) parse_url((string) $item[$urlKey], PHP_URL_QUERY), $query);
        $videoId = (string) ($query['v'] ?? '');
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9_-]{11}$/', $videoId);
        $this->assertSame('youtube', $item['external_provider']);
        $this->assertSame('youtube_video', $item['content_type']);
        $this->assertSame('https://www.youtube.com/watch?v='.$videoId, $item[$urlKey]);
        $this->assertSame('https://www.youtube.com/embed/'.$videoId, $item['embed_url']);
        $this->assertSame('https://i.ytimg.com/vi/'.$videoId.'/hqdefault.jpg', $item['image_url']);
    }

    /** @param array<string, mixed> $value */
    private function assertLocalized(array $value, string $message = ''): void
    {
        $this->assertSame(self::LOCALES, array_keys($value), $message);
        foreach (self::LOCALES as $locale) {
            $this->assertNotSame('', trim((string) $value[$locale]), $message.' '.$locale);
        }
    }

    /** @param array<string, mixed> $value */
    private function assertLocalizedLists(array $value): void
    {
        $this->assertSame(self::LOCALES, array_keys($value));
        foreach (self::LOCALES as $locale) {
            $this->assertNotEmpty($value[$locale], $locale);
            foreach ($value[$locale] as $item) {
                $this->assertNotSame('', trim((string) $item), $locale);
            }
        }
    }
}
