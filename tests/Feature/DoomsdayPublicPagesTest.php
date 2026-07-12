<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\NewsLocale;
use App\Models\Countdown;
use App\Services\Doomsday\Cache\CountdownCache;
use App\Services\Doomsday\Cache\DoomsdayCacheKeys;
use App\Services\Doomsday\CountdownPublicDataService;
use Carbon\CarbonImmutable;
use Database\Seeders\DoomsdaySeeder;
use Illuminate\Cache\ArrayStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

final class DoomsdayPublicPagesTest extends TestCase
{
    use RefreshDatabase;

    private string $originalCacheStore;

    private bool $originalDoomsdayCacheEnabled;

    private int $originalDoomsdayCacheTtl;

    private bool $usingArrayCache = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalCacheStore = (string) config('cache.default');
        $this->originalDoomsdayCacheEnabled = (bool) config('doomsday.cache.enabled');
        $this->originalDoomsdayCacheTtl = (int) config('doomsday.cache.ttl');
    }

    protected function tearDown(): void
    {
        if ($this->usingArrayCache) {
            Cache::flush();
            config([
                'cache.default' => $this->originalCacheStore,
                'doomsday.cache.enabled' => $this->originalDoomsdayCacheEnabled,
                'doomsday.cache.ttl' => $this->originalDoomsdayCacheTtl,
            ]);
            Cache::setDefaultDriver($this->originalCacheStore);
        }

        $this->travelBack();

        parent::tearDown();
    }

    public function test_home_page_renders_countdowns_by_displayed_timer_with_typed_sidebar(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-07-12 00:00:00', 'UTC'));
        $this->seed(DoomsdaySeeder::class);

        $response = $this->get('/?lang=en')->assertOk();
        $response->assertInertia(fn (Assert $page): Assert => $page
            ->component('Doomsday/Home')
            ->where('page.app_name', 'Doomsday Clock')
            ->where('page.current_locale', 'en')
            ->has('page.countdowns', 6)
            ->has('page.sidebar.latest_news')
            ->has('page.sidebar.signal_activity')
            ->where('page.selected_countdown', null)
            ->where('selected_countdown', null)
            ->missing('forecast_section')
            ->missing('statistics_section')
            ->missing('news_section')
            ->missing('initiatives_section'));

        $countdowns = collect($response->inertiaProps('page.countdowns'));
        $this->assertSame([
            'europe-war-countdown',
            'ai-job-apocalypse',
            'taiwan-invasion',
            'sixth-mass-extinction',
            'antibiotic-apocalypse',
            'unlivable-heat',
        ], $countdowns->pluck('slug')->all());

        $expectedTargets = [
            'europe-war-countdown' => '2027-03-31 23:59:59',
            'ai-job-apocalypse' => '2027-12-02 23:59:59',
            'taiwan-invasion' => '2027-12-31 23:59:59',
            'sixth-mass-extinction' => '2029-06-30 23:59:59',
            'antibiotic-apocalypse' => '2029-12-31 23:59:59',
            'unlivable-heat' => '2040-12-31 23:59:59',
        ];

        foreach ($expectedTargets as $slug => $target) {
            $countdown = $countdowns->firstWhere('slug', $slug);
            $this->assertSame('pessimistic', $countdown['main_projection']['type'] ?? null, $slug);
            $this->assertTrue($this->isUtcTimestamp($countdown['timer']['target_date'] ?? null, $target), $slug);
        }

        $activity = $response->inertiaProps('page.sidebar.signal_activity');
        $this->assertCount(12, $activity['bucket_labels']);
        $this->assertCount(12, $activity['bucket_counts']);
        $this->assertSame(array_sum($activity['bucket_counts']), $activity['total_items']);
    }

    public function test_detail_route_uses_same_active_pessimistic_projection_as_home(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-07-12 00:00:00', 'UTC'));
        $this->seed(DoomsdaySeeder::class);

        $this->get('/countdowns/taiwan-invasion?lang=it')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Doomsday/Home')
                ->where('page.current_locale', 'it')
                ->has('page.sidebar.latest_news')
                ->has('page.sidebar.signal_activity')
                ->where('page.selected_countdown', null)
                ->where('selected_countdown.slug', 'taiwan-invasion')
                ->where('selected_countdown.title', 'Invasione di Taiwan')
                ->where('selected_countdown.main_projection.type', 'pessimistic')
                ->where('selected_countdown.main_projection.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2027-12-31 23:59:59'))
                ->where('selected_countdown.timer.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2027-12-31 23:59:59'))
                ->has('selected_countdown.key_indicators')
                ->missing('selected_countdown.projections')
                ->missing('selected_countdown.visualizations')
                ->missing('selected_countdown.news')
                ->missing('forecast_section')
                ->missing('statistics_section')
                ->missing('news_section')
                ->missing('initiatives_section'));
    }

    public function test_cached_europe_rollover_keeps_exact_target_and_refreshes_at_plus_one_second(): void
    {
        $this->enableArrayCache(86400);
        $this->seed(DoomsdaySeeder::class);

        $this->travelTo(CarbonImmutable::parse('2027-03-31 23:59:58', 'UTC'));
        $this->assertEuropeCachedProjection('pessimistic', '2027-03-31 23:59:59');

        $this->travelTo(CarbonImmutable::parse('2027-03-31 23:59:59', 'UTC'));
        $this->assertEuropeCachedProjection('pessimistic', '2027-03-31 23:59:59');

        $this->travelTo(CarbonImmutable::parse('2027-04-01 00:00:00', 'UTC'));
        $this->assertEuropeCachedProjection('neutral', '2030-12-31 23:59:59');
    }

    public function test_index_cache_recomposes_at_exact_scheduled_news_publication_without_mutation(): void
    {
        $this->enableArrayCache(86400);
        $this->seed(DoomsdaySeeder::class);
        $countdown = Countdown::query()->where('slug', 'europe-war-countdown')->firstOrFail();
        $publication = CarbonImmutable::parse('2026-07-14 12:34:56', 'UTC');
        $news = $countdown->news()->create([
            'locale' => NewsLocale::All,
            'title' => 'Exact scheduled publication',
            'excerpt' => 'Becomes visible at its persisted publication timestamp.',
            'source_name' => 'Scheduled Source',
            'source_url' => 'https://scheduled.example/exact',
            'published_at' => $publication,
        ]);
        $service = app(CountdownPublicDataService::class);

        $this->travelTo($publication->subSecond());
        $directBefore = $service->indexPayload('en');
        $cachedBefore = $this->get('/?lang=en')->assertOk();
        $this->assertNotContains($news->id, array_column($directBefore['sidebar']['latest_news'], 'id'));
        $this->assertNotContains($news->id, array_column($cachedBefore->inertiaProps('page.sidebar.latest_news'), 'id'));

        $this->travelTo($publication);
        $directAtPublication = $service->indexPayload('en');
        $cachedAtPublication = $this->get('/?lang=en')->assertOk();
        $directIds = array_column($directAtPublication['sidebar']['latest_news'], 'id');
        $cachedIds = array_column($cachedAtPublication->inertiaProps('page.sidebar.latest_news'), 'id');

        $this->assertContains($news->id, $directIds);
        $this->assertContains($news->id, $cachedIds);
        $this->assertSame($directIds, $cachedIds);
        $this->assertStringNotContainsString('__doomsday_rollover_', $cachedAtPublication->getContent());
    }

    public function test_index_cache_recomposes_at_next_utc_week_boundary_without_mutation(): void
    {
        $this->enableArrayCache(86400);
        $this->seed(DoomsdaySeeder::class);
        $countdown = Countdown::query()->where('slug', 'europe-war-countdown')->firstOrFail();
        $countdown->news()->create([
            'locale' => NewsLocale::All,
            'title' => 'Monday boundary publication',
            'excerpt' => 'Visible only when the next UTC week begins.',
            'source_name' => 'Boundary Source',
            'source_url' => 'https://boundary.example/news',
            'published_at' => CarbonImmutable::parse('2026-07-13 00:00:00', 'UTC'),
        ]);

        $this->travelTo(CarbonImmutable::parse('2026-07-12 23:59:59', 'UTC'));
        $before = $this->get('/?lang=en')->assertOk();
        $this->assertSame('2026-07-06', collect($before->inertiaProps('page.sidebar.signal_activity.bucket_labels'))->last());
        $this->assertNotContains('Monday boundary publication', collect($before->inertiaProps('page.sidebar.latest_news'))->pluck('title'));

        $this->travelTo(CarbonImmutable::parse('2026-07-13 00:00:00', 'UTC'));
        $after = $this->get('/?lang=en')->assertOk();
        $this->assertSame('2026-07-13', collect($after->inertiaProps('page.sidebar.signal_activity.bucket_labels'))->last());
        $this->assertContains('Monday boundary publication', collect($after->inertiaProps('page.sidebar.latest_news'))->pluck('title'));
    }

    public function test_index_cache_recomposes_legacy_envelope_missing_sidebar_contract(): void
    {
        $this->enableArrayCache(86400);
        Cache::put(DoomsdayCacheKeys::index('en'), [
            '__doomsday_rollover_envelope' => true,
            '__doomsday_rollover_payload' => [
                'app_name' => 'Legacy Doomsday',
                'current_locale' => 'en',
                'hero' => [],
                'countdowns' => [],
            ],
            '__doomsday_rollover_boundary' => null,
        ], 60);

        $response = $this->get('/?lang=en')->assertOk();
        $this->assertIsArray($response->inertiaProps('page.sidebar.latest_news'));
        $this->assertIsArray($response->inertiaProps('page.sidebar.signal_activity'));
        $this->assertStringNotContainsString('__doomsday_rollover_', $response->getContent());
    }

    public function test_configured_ttl_caps_rollover_aware_index_cache(): void
    {
        $this->enableArrayCache(5);
        $this->travelTo(CarbonImmutable::parse('2026-07-12 00:00:00', 'UTC'));
        $this->seed(DoomsdaySeeder::class);

        $this->get('/?lang=en')->assertOk();

        $store = Cache::getStore();
        $this->assertInstanceOf(ArrayStore::class, $store);
        $entry = $store->all(false)[DoomsdayCacheKeys::index('en')] ?? null;
        $this->assertIsArray($entry);
        $now = CarbonImmutable::now('UTC')->getPreciseTimestamp(3) / 1000;
        $this->assertGreaterThan($now, $entry['expiresAt']);
        $this->assertLessThanOrEqual($now + 5, $entry['expiresAt']);

        $this->travelTo(CarbonImmutable::parse('2026-07-12 00:00:04', 'UTC'));
        $this->assertNotNull(Cache::get(DoomsdayCacheKeys::index('en')));

        $this->travelTo(CarbonImmutable::parse('2026-07-12 00:00:05', 'UTC'));
        $this->assertNull(Cache::get(DoomsdayCacheKeys::index('en')));
    }

    public function test_about_page_renders_public_methodology(): void
    {
        $this->get('/about?lang=it')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Doomsday/About')
                ->where('page.app_name', 'Doomsday Clock')
                ->where('page.current_locale', 'it')
                ->where('page.eyebrow', 'Un sistema di allerta per un secolo fragile')
                ->where('page.pipeline_label', 'Pipeline degli scenari')
                ->has('page.languages', 8)
                ->has('page.intro', 2)
                ->has('page.stats', 3)
                ->has('page.sections', 3)
                ->has('page.timeline', 3)
                ->has('page.faq', 5)
                ->where('page.closing_label', 'Segnale finale'));
    }

    public function test_about_cache_refreshes_incomplete_legacy_payload(): void
    {
        Cache::put(DoomsdayCacheKeys::about('it'), [
            'app_name' => 'Doomsday Clock',
            'current_locale' => 'it',
            'title' => 'Legacy cached About',
            'subtitle' => 'Legacy partial payload',
            'sections' => [],
        ], 60);

        $page = app(CountdownCache::class)->about('it', 'about');

        $this->assertSame('Doomsday Clock', $page['app_name']);
        $this->assertSame('it', $page['current_locale']);
        $this->assertSame('Un sistema di allerta per un secolo fragile', $page['eyebrow']);
        $this->assertSame('Pipeline degli scenari', $page['pipeline_label']);
        $this->assertCount(3, $page['stats']);
        $this->assertCount(3, $page['timeline']);
        $this->assertCount(5, $page['faq']);
    }

    private function assertEuropeCachedProjection(string $expectedType, string $expectedTarget): void
    {
        $home = $this->get('/?lang=en')->assertOk();
        $europe = collect($home->inertiaProps('page.countdowns'))->firstWhere('slug', 'europe-war-countdown');
        $this->assertNotNull($europe);
        $this->assertSame($expectedType, $europe['main_projection']['type']);
        $this->assertTrue($this->isUtcTimestamp($europe['main_projection']['target_date'], $expectedTarget));
        $this->assertTrue($this->isUtcTimestamp($europe['timer']['target_date'], $expectedTarget));

        $overviewResponse = $this->getJson(route('countdowns.data.overview', [
            'slug' => 'europe-war-countdown',
            'lang' => 'en',
        ]))->assertOk();
        $overview = $overviewResponse->json('data');

        $this->assertSame($expectedType, $overview['main_projection']['type']);
        $this->assertTrue($this->isUtcTimestamp($overview['main_projection']['target_date'], $expectedTarget));
        $this->assertTrue($this->isUtcTimestamp($overview['timer']['target_date'], $expectedTarget));
        $this->assertStringNotContainsString('__doomsday_rollover_', $home->getContent());
        $this->assertStringNotContainsString('__doomsday_rollover_', $overviewResponse->getContent());
    }

    private function enableArrayCache(int $ttl): void
    {
        config([
            'cache.default' => 'array',
            'doomsday.cache.enabled' => true,
            'doomsday.cache.ttl' => $ttl,
        ]);
        Cache::setDefaultDriver('array');
        Cache::flush();
        $this->usingArrayCache = true;
    }

    private function isUtcTimestamp(?string $actual, string $expected): bool
    {
        return $actual !== null
            && CarbonImmutable::parse($actual)->utc()->format('Y-m-d H:i:s') === $expected;
    }
}
