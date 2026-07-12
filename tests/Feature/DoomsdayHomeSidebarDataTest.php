<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use App\Enums\NewsLocale;
use App\Models\Countdown;
use App\Models\News;
use App\Services\Doomsday\Cache\CountdownCache;
use App\Services\Doomsday\Cache\DoomsdayCacheKeys;
use App\Services\Doomsday\CountdownPublicDataService;
use App\Services\Doomsday\HomeSidebarDataService;
use Carbon\CarbonImmutable;
use Illuminate\Cache\ArrayStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class DoomsdayHomeSidebarDataTest extends TestCase
{
    use RefreshDatabase;

    private string $originalCacheStore;

    private bool $originalDoomsdayCacheEnabled;

    private int $originalDoomsdayCacheTtl;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalCacheStore = (string) config('cache.default');
        $this->originalDoomsdayCacheEnabled = (bool) config('doomsday.cache.enabled');
        $this->originalDoomsdayCacheTtl = (int) config('doomsday.cache.ttl');
    }

    protected function tearDown(): void
    {
        Cache::flush();
        config([
            'cache.default' => $this->originalCacheStore,
            'doomsday.cache.enabled' => $this->originalDoomsdayCacheEnabled,
            'doomsday.cache.ttl' => $this->originalDoomsdayCacheTtl,
        ]);
        Cache::setDefaultDriver($this->originalCacheStore);
        $this->travelBack();

        parent::tearDown();
    }

    public function test_latest_news_deduplicates_before_take_across_keyset_batches(): void
    {
        $at = CarbonImmutable::parse('2026-07-15 12:00:00', 'UTC');
        $this->travelTo($at);

        for ($index = 0; $index < 25; $index++) {
            $countdown = $this->countdown('duplicate-'.$index, $index + 1);
            $this->news(
                $countdown,
                'Duplicate '.$index,
                $at->subMinutes($index),
                'https://same.example/shared-article',
            );
        }

        $uniqueCountdown = $this->countdown('unique-news', 30);
        for ($index = 0; $index < 11; $index++) {
            $this->news(
                $uniqueCountdown,
                'Unique '.$index,
                $at->subMinutes(30 + $index),
                'https://unique.example/article-'.$index,
            );
        }

        $sidebar = app(HomeSidebarDataService::class)->compose('en', $at);
        $items = collect($sidebar->latest_news);

        $this->assertCount(10, $items);
        $this->assertSame('Duplicate 0', $items->first()->title);
        $this->assertCount(10, $items->pluck('source_url')->unique()->all());
        $this->assertSame(
            $items->pluck('published_at')->map(fn (CarbonImmutable $date): int => $date->getTimestamp())->sortDesc()->values()->all(),
            $items->pluck('published_at')->map(fn (CarbonImmutable $date): int => $date->getTimestamp())->all(),
        );
    }

    public function test_latest_news_applies_visibility_and_image_link_fallbacks(): void
    {
        $at = CarbonImmutable::parse('2026-07-15 12:00:00', 'UTC');
        $this->travelTo($at);
        $originalLimit = config('doomsday.content.preview_excerpt_limit');
        config(['doomsday.content.preview_excerpt_limit' => 12]);

        try {
            $countdown = $this->countdown('fallbacks', 1, true, 'images/doomsday/fallback-countdown.png');
            $remote = $this->news($countdown, 'Remote', $at->subMinute(), 'https://source.example/remote', [
                'excerpt' => 'A deliberately long excerpt for preview truncation.',
                'preview_image_url' => 'https://images.example/remote.jpg',
            ]);
            $local = $this->news($countdown, 'Local', $at->subMinutes(2), 'http://unsafe.example/local', [
                'image_path' => 'images/doomsday/local-news.png',
            ]);
            $fallback = $this->news($countdown, 'Countdown fallback', $at->subMinutes(3), null);
            $this->news($countdown, 'Italian only', $at->subMinutes(4), 'https://source.example/it', [
                'locale' => NewsLocale::It,
            ]);
            $this->news($countdown, 'Future', $at->addMinute(), 'https://source.example/future');
            $hidden = $this->countdown('hidden-news', 2, false);
            $this->news($hidden, 'Hidden', $at->subMinute(), 'https://source.example/hidden');

            $items = collect(app(HomeSidebarDataService::class)->compose('en', $at)->latest_news)->keyBy('id');

            $this->assertCount(3, $items);
            $this->assertSame('https://images.example/remote.jpg', $items[$remote->id]->image_url);
            $this->assertSame('https://source.example/remote', $items[$remote->id]->source_url);
            $this->assertSame(asset('images/doomsday/local-news.png'), $items[$local->id]->image_url);
            $this->assertNull($items[$local->id]->source_url);
            $this->assertSame(asset('images/doomsday/fallback-countdown.png'), $items[$fallback->id]->image_url);
            $this->assertNull($items[$fallback->id]->source_url);
            $this->assertSame('/countdowns/fallbacks?lang=en', $items[$fallback->id]->countdown_url);
            $this->assertLessThanOrEqual(13, mb_strlen($items[$remote->id]->excerpt));
        } finally {
            config(['doomsday.content.preview_excerpt_limit' => $originalLimit]);
        }
    }

    public function test_signal_activity_reconstructs_exact_deduplicated_twelve_week_fixture(): void
    {
        $at = CarbonImmutable::parse('2026-07-15 12:00:00', 'UTC');
        $this->travelTo($at);
        $windowStart = $at->startOfWeek()->startOfDay()->subWeeks(11);
        $alpha = $this->countdown('alpha', 1);
        $beta = $this->countdown('beta', 2);

        $this->news($alpha, 'Duplicate older', $windowStart->addDay(), 'https://alpha.example/shared');
        $this->news($alpha, 'Week ten', $windowStart->addWeeks(10)->addDay(), 'https://beta.example/week-ten');
        $this->news($alpha, 'Latest alpha', $windowStart->addWeeks(11)->addDays(2)->setTime(9, 0), 'https://gamma.example/latest');
        $this->news($beta, 'Duplicate newer', $windowStart->addWeeks(11)->addDay(), 'https://alpha.example/shared');
        $this->news($alpha, 'Italian excluded', $windowStart->addWeeks(11)->addDay(), 'https://locale.example/it', [
            'locale' => NewsLocale::It,
        ]);
        $this->news($alpha, 'Future excluded', $at->addMinute(), 'https://future.example/article');
        $hidden = $this->countdown('hidden-activity', 3, false);
        $this->news($hidden, 'Hidden excluded', $at->subHour(), 'https://hidden.example/article');

        $activity = app(HomeSidebarDataService::class)->compose('en', $at)->signal_activity;
        $expectedCounts = array_fill(0, 12, 0);
        $expectedCounts[10] = 1;
        $expectedCounts[11] = 2;

        $this->assertSame($windowStart->format('Y-m-d'), $activity->bucket_labels[0]);
        $this->assertSame($at->startOfWeek()->format('Y-m-d'), $activity->bucket_labels[11]);
        $this->assertSame($expectedCounts, $activity->bucket_counts);
        $this->assertSame(3, $activity->total_items);
        $this->assertSame(array_sum($activity->bucket_counts), $activity->total_items);
        $this->assertSame(3, $activity->unique_sources);
        $this->assertSame('alpha', $activity->top_countdown_slug);
        $this->assertSame('Alpha', $activity->top_countdown_title);
        $this->assertSame(2, $activity->top_countdown_count);
        $this->assertSame('2026-07-15 09:00:00', $activity->latest_published_at?->utc()->format('Y-m-d H:i:s'));
    }

    public function test_earliest_future_visible_publication_filters_scope_and_orders_ties_by_id(): void
    {
        $at = CarbonImmutable::parse('2026-07-15 12:00:00', 'UTC');
        $this->travelTo($at);
        $published = $this->countdown('scheduled-visible', 1);
        $hidden = $this->countdown('scheduled-hidden', 2, false);
        $service = app(HomeSidebarDataService::class);

        $this->news($published, 'Already published', $at, 'https://schedule.example/already');
        $this->news($published, 'Italian future', $at->addSeconds(5), 'https://schedule.example/italian', [
            'locale' => NewsLocale::It,
        ]);
        $this->news($hidden, 'Hidden countdown future', $at->addSeconds(3), 'https://schedule.example/hidden');

        $this->assertNull($service->earliestFutureVisiblePublication('en', $at));
        $this->assertSame(
            '2026-07-15 12:00:05',
            $service->earliestFutureVisiblePublication('it', $at)?->format('Y-m-d H:i:s'),
        );

        $firstTie = $this->news($published, 'First tie', $at->addSeconds(10), 'https://schedule.example/first-tie');
        $secondTie = $this->news($published, 'Second tie', $at->addSeconds(10), 'https://schedule.example/second-tie');
        $this->news($published, 'Later English', $at->addSeconds(20), 'https://schedule.example/later', [
            'locale' => NewsLocale::En,
        ]);

        DB::flushQueryLog();
        DB::enableQueryLog();
        $earliest = $service->earliestFutureVisiblePublication('en', $at);
        $query = collect(DB::getQueryLog())->last();
        DB::disableQueryLog();

        $this->assertLessThan($secondTie->id, $firstTie->id);
        $this->assertSame('2026-07-15 12:00:10', $earliest?->format('Y-m-d H:i:s'));
        $normalizedSql = str_replace(['"', '`', '[', ']'], '', strtolower((string) ($query['query'] ?? '')));
        $this->assertStringContainsString('order by published_at asc, id asc', $normalizedSql);
    }

    public function test_index_cache_uses_future_publication_boundary_and_configured_ttl_cap(): void
    {
        $at = CarbonImmutable::parse('2026-07-15 12:00:00', 'UTC');
        $publication = $at->addMinutes(10);
        $this->enableArrayCache(86400);
        $this->travelTo($at);
        $countdown = $this->countdown('scheduled-cache', 1);
        $this->news($countdown, 'Scheduled cache item', $publication, 'https://schedule.example/cache');

        app(CountdownCache::class)->page('en', null, '/');

        $store = Cache::getStore();
        $this->assertInstanceOf(ArrayStore::class, $store);
        $entry = $store->all(false)[DoomsdayCacheKeys::index('en')] ?? null;
        $this->assertIsArray($entry);
        $this->assertEqualsWithDelta($publication->getTimestamp(), $entry['expiresAt'], 0.01);

        Cache::flush();
        config(['doomsday.cache.ttl' => 5]);
        app(CountdownCache::class)->page('en', null, '/');

        $ttlEntry = $store->all(false)[DoomsdayCacheKeys::index('en')] ?? null;
        $this->assertIsArray($ttlEntry);
        $now = CarbonImmutable::now('UTC')->getTimestamp();
        $this->assertGreaterThan($now, $ttlEntry['expiresAt']);
        $this->assertLessThanOrEqual($now + 5, $ttlEntry['expiresAt']);
        $this->assertLessThan($publication->getTimestamp(), $ttlEntry['expiresAt']);
    }

    public function test_public_order_handles_future_expired_null_and_tie_breaks(): void
    {
        $at = CarbonImmutable::parse('2026-07-15 12:00:00', 'UTC');
        $this->travelTo($at);

        $futureHigherSort = $this->countdown('future-higher-sort', 2, true, targetDate: $at->addDay());
        $futureLowerSort = $this->countdown('future-lower-sort', 1, true, targetDate: $at->addDay());
        $recentExpired = $this->countdown('recent-expired', 1, true, targetDate: $at->subMinute());
        $olderExpired = $this->countdown('older-expired', 1, true, targetDate: $at->subDay());
        $nullTarget = $this->countdown('null-target', 1, true, targetDate: null);

        $payload = app(CountdownPublicDataService::class)->indexPayload('en');

        $this->assertSame([
            $futureLowerSort->slug,
            $futureHigherSort->slug,
            $recentExpired->slug,
            $olderExpired->slug,
            $nullTarget->slug,
        ], array_column($payload['countdowns'], 'slug'));
    }

    public function test_news_observer_invalidates_index_and_countdown_cache_and_refreshes_home(): void
    {
        $this->enableArrayCache();
        $at = CarbonImmutable::parse('2026-07-15 12:00:00', 'UTC');
        $this->travelTo($at);
        $countdown = $this->countdown('observer', 1);
        $cache = app(CountdownCache::class);

        $before = $cache->page('en', null, '/');
        $this->assertSame([], $before['sidebar']['latest_news']);
        $this->primeObservedKeys($countdown);

        $news = $this->news($countdown, 'Created publication', $at->subMinute(), 'https://observer.example/created');
        $this->assertObservedKeysForgotten($countdown);
        $afterCreate = $cache->page('en', null, '/');
        $this->assertSame(['Created publication'], array_column($afterCreate['sidebar']['latest_news'], 'title'));

        $this->primeObservedKeys($countdown);
        $news->update(['title' => 'Updated publication']);
        $this->assertObservedKeysForgotten($countdown);

        $this->primeObservedKeys($countdown);
        $news->delete();
        $this->assertObservedKeysForgotten($countdown);
        $afterDelete = $cache->page('en', null, '/');
        $this->assertSame([], $afterDelete['sidebar']['latest_news']);
    }

    private function enableArrayCache(int $ttl = 86400): void
    {
        config([
            'cache.default' => 'array',
            'doomsday.cache.enabled' => true,
            'doomsday.cache.ttl' => $ttl,
        ]);
        Cache::setDefaultDriver('array');
        Cache::flush();
    }

    private function primeObservedKeys(Countdown $countdown): void
    {
        foreach (DoomsdayCacheKeys::indexKeys() as $key) {
            Cache::put($key, ['primed' => true], 60);
        }
        foreach (DoomsdayCacheKeys::allSectionsForSlug($countdown->slug) as $key) {
            Cache::put($key, ['primed' => true], 60);
        }
    }

    private function assertObservedKeysForgotten(Countdown $countdown): void
    {
        foreach (DoomsdayCacheKeys::indexKeys() as $key) {
            $this->assertNull(Cache::get($key), $key);
        }
        foreach (DoomsdayCacheKeys::allSectionsForSlug($countdown->slug) as $key) {
            $this->assertNull(Cache::get($key), $key);
        }
    }

    private function countdown(
        string $slug,
        int $sortOrder,
        bool $published = true,
        string $imagePath = 'images/doomsday/society_collapse_separate.png',
        ?CarbonImmutable $targetDate = null,
    ): Countdown {
        return Countdown::query()->create([
            'slug' => $slug,
            'title' => ['en' => str($slug)->replace('-', ' ')->title()->toString()],
            'summary' => ['en' => $slug.' summary'],
            'description' => ['en' => $slug.' description'],
            'severity' => CountdownSeverity::High,
            'status' => CountdownStatus::Active,
            'target_date' => $targetDate,
            'image_path' => $imagePath,
            'sort_order' => $sortOrder,
            'is_published' => $published,
        ]);
    }

    /** @param array<string, mixed> $overrides */
    private function news(
        Countdown $countdown,
        string $title,
        CarbonImmutable $publishedAt,
        ?string $sourceUrl,
        array $overrides = [],
    ): News {
        return $countdown->news()->create(array_merge([
            'locale' => NewsLocale::All,
            'title' => $title,
            'excerpt' => $title.' excerpt',
            'content_type' => 'article',
            'source_name' => parse_url((string) $sourceUrl, PHP_URL_HOST) ?: null,
            'source_url' => $sourceUrl,
            'published_at' => $publishedAt,
        ], $overrides));
    }
}
