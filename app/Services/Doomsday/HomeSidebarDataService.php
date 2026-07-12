<?php

declare(strict_types=1);

namespace App\Services\Doomsday;

use App\Data\Doomsday\HomeSidebarData;
use App\Data\Doomsday\LatestNewsItemData;
use App\Data\Doomsday\NewsActivityData;
use App\Enums\NewsLocale;
use App\Models\Countdown;
use App\Models\News;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class HomeSidebarDataService
{
    private const LATEST_LIMIT = 10;

    private const LATEST_BATCH_SIZE = 25;

    private const ACTIVITY_WEEKS = 12;

    private const SECONDS_PER_WEEK = 604800;

    public function __construct(private readonly PublicContentResolver $contentResolver) {}

    public function compose(string $locale, CarbonImmutable $at): HomeSidebarData
    {
        return new HomeSidebarData(
            latest_news: $this->latestNews($locale, $at),
            signal_activity: $this->activity($locale, $at),
        );
    }

    public function empty(CarbonImmutable $at): HomeSidebarData
    {
        $windowStart = $this->windowStart($at);

        return new HomeSidebarData(
            latest_news: [],
            signal_activity: new NewsActivityData(
                window_start: $windowStart,
                window_end: $at,
                bucket_labels: $this->bucketLabels($windowStart),
                bucket_counts: array_fill(0, self::ACTIVITY_WEEKS, 0),
                total_items: 0,
                unique_sources: 0,
                latest_published_at: null,
                top_countdown_slug: null,
                top_countdown_title: null,
                top_countdown_count: 0,
            ),
        );
    }

    public function earliestFutureVisiblePublication(string $locale, CarbonImmutable $at): ?CarbonImmutable
    {
        $news = News::query()
            ->whereHas('countdown', fn (Builder $query): Builder => $query->published())
            ->whereIn('locale', [NewsLocale::All->value, $locale])
            ->whereNotNull('published_at')
            ->where('published_at', '>', $at)
            ->orderBy('published_at')
            ->orderBy('id')
            ->first(['published_at']);

        return $news?->published_at?->toImmutable()->utc();
    }

    /** @return array<int, LatestNewsItemData> */
    private function latestNews(string $locale, CarbonImmutable $at): array
    {
        $latest = [];
        $seen = [];
        $cursorAt = null;
        $cursorId = null;

        do {
            $query = $this->visibleNewsQuery($locale, $at);

            if ($cursorAt instanceof CarbonImmutable && is_int($cursorId)) {
                $query->where(function (Builder $builder) use ($cursorAt, $cursorId): void {
                    $builder
                        ->where('published_at', '<', $cursorAt)
                        ->orWhere(function (Builder $sameTimestamp) use ($cursorAt, $cursorId): void {
                            $sameTimestamp
                                ->where('published_at', '=', $cursorAt)
                                ->where('id', '<', $cursorId);
                        });
                });
            }

            /** @var Collection<int, News> $batch */
            $batch = $query->limit(self::LATEST_BATCH_SIZE)->get();

            foreach ($batch as $news) {
                $key = $this->dedupeKey($news);
                if (isset($seen[$key]) || ! $news->countdown instanceof Countdown || $news->published_at === null) {
                    continue;
                }

                $seen[$key] = true;
                $latest[] = $this->toLatestNewsItem($news, $locale);

                if (count($latest) === self::LATEST_LIMIT) {
                    break 2;
                }
            }

            $last = $batch->last();
            $cursorAt = $last?->published_at?->toImmutable();
            $cursorId = $last?->id;
        } while ($batch->count() === self::LATEST_BATCH_SIZE && $cursorAt instanceof CarbonImmutable && is_int($cursorId));

        return $latest;
    }

    private function activity(string $locale, CarbonImmutable $at): NewsActivityData
    {
        $windowStart = $this->windowStart($at);
        $counts = array_fill(0, self::ACTIVITY_WEEKS, 0);
        $seen = [];
        $sources = [];
        $countdowns = [];
        $latestPublishedAt = null;

        /** @var Collection<int, News> $newsItems */
        $newsItems = $this->visibleNewsQuery($locale, $at)
            ->where('published_at', '>=', $windowStart)
            ->get();

        foreach ($newsItems as $news) {
            $key = $this->dedupeKey($news);
            if (isset($seen[$key]) || ! $news->countdown instanceof Countdown || $news->published_at === null) {
                continue;
            }

            $seen[$key] = true;
            $publishedAt = $news->published_at->toImmutable()->utc();
            $bucketStart = $publishedAt->startOfWeek(CarbonInterface::MONDAY)->startOfDay();
            $bucketIndex = intdiv($bucketStart->getTimestamp() - $windowStart->getTimestamp(), self::SECONDS_PER_WEEK);

            if ($bucketIndex < 0 || $bucketIndex >= self::ACTIVITY_WEEKS) {
                continue;
            }

            $counts[$bucketIndex]++;
            $latestPublishedAt ??= $publishedAt;

            $sourceKey = $this->sourceKey($news);
            if ($sourceKey !== null) {
                $sources[$sourceKey] = true;
            }

            $countdown = $news->countdown;
            $countdowns[$countdown->id] ??= [
                'slug' => $countdown->slug,
                'title' => $this->localizedText($countdown->title, $locale),
                'count' => 0,
                'sort_order' => $countdown->sort_order,
                'id' => $countdown->id,
            ];
            $countdowns[$countdown->id]['count']++;
        }

        $topCountdown = $this->topCountdown(array_values($countdowns));

        return new NewsActivityData(
            window_start: $windowStart,
            window_end: $at,
            bucket_labels: $this->bucketLabels($windowStart),
            bucket_counts: $counts,
            total_items: array_sum($counts),
            unique_sources: count($sources),
            latest_published_at: $latestPublishedAt,
            top_countdown_slug: $topCountdown['slug'] ?? null,
            top_countdown_title: $topCountdown['title'] ?? null,
            top_countdown_count: $topCountdown['count'] ?? 0,
        );
    }

    private function toLatestNewsItem(News $news, string $locale): LatestNewsItemData
    {
        $countdown = $news->countdown;

        return new LatestNewsItemData(
            id: $news->id,
            title: $news->title,
            excerpt: $this->contentResolver->excerpt($news->excerpt),
            content_type: $news->content_type ?: 'article',
            source_name: $news->source_name,
            source_url: $this->contentResolver->httpsUrl($news->source_url),
            image_url: $this->contentResolver->imageUrl($news->preview_image_url, $news->image_path, $countdown->image_path),
            published_at: $news->published_at->toImmutable(),
            countdown_slug: $countdown->slug,
            countdown_title: $this->localizedText($countdown->title, $locale),
            countdown_url: '/countdowns/'.$countdown->slug.'?lang='.$locale,
        );
    }

    /** @return Builder<News> */
    private function visibleNewsQuery(string $locale, CarbonImmutable $at): Builder
    {
        return News::query()
            ->with(['countdown:id,slug,title,image_path,sort_order'])
            ->whereHas('countdown', fn (Builder $query): Builder => $query->published())
            ->whereIn('locale', [NewsLocale::All->value, $locale])
            ->whereNotNull('published_at')
            ->where('published_at', '<=', $at)
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }

    private function dedupeKey(News $news): string
    {
        $hash = trim((string) $news->canonical_source_hash);
        if ($hash !== '') {
            return 'hash:'.$hash;
        }

        $canonicalUrl = trim((string) $news->canonical_source_url);
        if ($canonicalUrl !== '') {
            return 'canonical:'.$canonicalUrl;
        }

        $sourceUrl = trim((string) $news->source_url);
        if ($sourceUrl !== '') {
            return 'source:'.$sourceUrl;
        }

        return 'news:'.$news->id;
    }

    private function sourceKey(News $news): ?string
    {
        $url = $this->contentResolver->httpsUrl($news->canonical_source_url)
            ?? $this->contentResolver->httpsUrl($news->source_url);
        $host = $url !== null ? strtolower((string) parse_url($url, PHP_URL_HOST)) : '';
        if ($host !== '') {
            return 'host:'.$host;
        }

        $sourceName = mb_strtolower(trim((string) $news->source_name));
        if ($sourceName !== '') {
            return 'name:'.$sourceName;
        }

        $hash = trim((string) $news->canonical_source_hash);

        return $hash !== '' ? 'hash:'.$hash : null;
    }

    private function windowStart(CarbonImmutable $at): CarbonImmutable
    {
        return $at->utc()
            ->startOfWeek(CarbonInterface::MONDAY)
            ->startOfDay()
            ->subWeeks(self::ACTIVITY_WEEKS - 1);
    }

    /** @return array<int, string> */
    private function bucketLabels(CarbonImmutable $windowStart): array
    {
        return array_map(
            static fn (int $week): string => $windowStart->addWeeks($week)->format('Y-m-d'),
            range(0, self::ACTIVITY_WEEKS - 1),
        );
    }

    /**
     * @param  array<int, array{slug: string, title: string, count: int, sort_order: int, id: int}>  $countdowns
     * @return array{slug: string, title: string, count: int, sort_order: int, id: int}|null
     */
    private function topCountdown(array $countdowns): ?array
    {
        usort($countdowns, static fn (array $left, array $right): int => [
            -$left['count'],
            $left['sort_order'],
            $left['id'],
            $left['slug'],
        ] <=> [
            -$right['count'],
            $right['sort_order'],
            $right['id'],
            $right['slug'],
        ]);

        return $countdowns[0] ?? null;
    }

    /** @param array<string, mixed>|null $value */
    private function localizedText(?array $value, string $locale): string
    {
        if ($value === null || $value === []) {
            return '';
        }

        $fallback = $value['en'] ?? reset($value);

        return (string) ($value[$locale] ?? $fallback ?? '');
    }
}
