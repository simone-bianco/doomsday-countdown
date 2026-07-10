<?php

declare(strict_types=1);

namespace App\Services\Doomsday\NewsUpdater;

use App\Enums\NewsLocale;
use App\Models\ContentSource;
use App\Models\Countdown;
use App\Models\News;
use App\Support\ContentSourceAgentRunContext;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleXMLElement;
use Throwable;

final class ContentSourceNewsRefreshService
{
    public function __construct(
        private readonly NewsUrlNormalizer $urls,
        private readonly YouTubeVideoUrl $youTubeUrls,
        private readonly YouTubeOEmbedService $oEmbed,
        private readonly GoogleNewsRssSearchService $googleNews,
    ) {}

    /** @return array<string, mixed> */
    public function run(Countdown $countdown, ?string $query = null, int $daysBack = 30, int $limit = 5, bool $insert = false): array
    {
        $daysBack = max(1, min($daysBack, 120));
        $limit = max(1, min($limit, 20));
        $sources = $this->sourcesForCountdown($countdown);
        Log::channel('news_retrieval')->info('content_source_refresh.started', ContentSourceAgentRunContext::context([
            'countdown_slug' => (string) $countdown->slug,
            'query' => $query,
            'days_back' => $daysBack,
            'limit' => $limit,
            'insert' => $insert,
            'sources_count' => $sources->count(),
        ]));

        $existingVideoIds = $this->existingVideoIds($countdown);
        $existingHashes = $this->existingHashes($countdown);
        $batchVideoIds = [];
        $rows = [];
        $insertedIds = [];
        $accepted = 0;

        foreach ($sources as $source) {
            foreach ($this->candidatesForSource($countdown, $source, $query, $daysBack) as $candidate) {
                $decision = $this->decide($candidate, $existingVideoIds, $existingHashes, $batchVideoIds, $accepted, $limit);
                $row = [
                    'status' => $decision['status'],
                    'reason' => $decision['reason'],
                    'source_name' => $source->name,
                    'candidate' => $candidate,
                ];

                if ($decision['status'] === 'new') {
                    $accepted++;
                    $batchVideoIds[] = (string) $candidate['external_id'];

                    if ($insert) {
                        $news = $this->insertCandidate($countdown, $candidate);
                        $insertedIds[] = (int) $news->getKey();
                        $row['status'] = 'inserted';
                        $row['reason'] = 'created news #'.$news->getKey();
                    }
                }

                $rows[] = $row;
            }
        }

        $summary = [
            'mode' => $insert ? 'insert' : 'dry-run',
            'countdown_slug' => $countdown->slug,
            'query' => $query,
            'days_back' => $daysBack,
            'limit' => $limit,
            'sources_count' => $sources->count(),
            'fetched_count' => count($rows),
            'new_count' => collect($rows)->whereIn('status', ['new', 'inserted'])->count(),
            'duplicate_count' => collect($rows)->where('status', 'duplicate')->count(),
            'skipped_count' => collect($rows)->where('status', 'skipped')->count(),
            'inserted_count' => count($insertedIds),
            'inserted_ids' => $insertedIds,
            'rows' => $rows,
        ];

        Log::channel('news_retrieval')->info('content_source_refresh.completed', ContentSourceAgentRunContext::context([
            'countdown_slug' => (string) $countdown->slug,
            'fetched_count' => $summary['fetched_count'],
            'new_count' => $summary['new_count'],
            'duplicate_count' => $summary['duplicate_count'],
            'skipped_count' => $summary['skipped_count'],
            'inserted_count' => $summary['inserted_count'],
        ]));

        return $summary;
    }

    /** @return array<int, array<string, mixed>> */
    public function searchSourceCandidates(ContentSource $source, string $query, CarbonImmutable $fromDate, CarbonImmutable $toDate, int $limit = 10): array
    {
        $limit = max(1, min($limit, 20));

        Log::channel('news_retrieval')->info('source_candidates.started', ContentSourceAgentRunContext::context([
            'source_id' => $source->getKey(),
            'source_name' => (string) $source->name,
            'source_type' => (string) $source->type,
            'provider' => (string) $source->provider,
            'query' => $query,
            'from_date' => $fromDate->toDateString(),
            'to_date' => $toDate->toDateString(),
            'limit' => $limit,
        ]));

        if ($this->isYouTubeSource($source)) {
            $candidates = array_slice(
                $this->youtubeFeedCandidatesForDateRange(
                    source: $source,
                    query: $query,
                    keywords: [],
                    excluded: [],
                    fromDate: $fromDate->startOfDay(),
                    toDate: $toDate->endOfDay(),
                    maxAccepted: $limit,
                ),
                0,
                $limit,
            );
        } elseif ($this->isGoogleNewsSource($source)) {
            $candidates = $this->googleNews->search(
                source: $source,
                query: $query,
                keywords: Arr::wrap($source->keywords),
                fromDate: $fromDate->startOfDay(),
                toDate: $toDate->endOfDay(),
                limit: $limit,
            );
        } else {
            Log::channel('news_retrieval')->warning('source_candidates.unsupported_source', ContentSourceAgentRunContext::context([
                'source_id' => $source->getKey(),
                'source_name' => (string) $source->name,
                'source_type' => (string) $source->type,
                'provider' => (string) $source->provider,
            ]));

            return [];
        }

        Log::channel('news_retrieval')->info('source_candidates.completed', ContentSourceAgentRunContext::context([
            'source_id' => $source->getKey(),
            'source_name' => (string) $source->name,
            'items_count' => count($candidates),
        ]));

        return $candidates;
    }

    /** @return EloquentCollection<int, ContentSource> */
    private function sourcesForCountdown(Countdown $countdown): EloquentCollection
    {
        /** @var EloquentCollection<int, ContentSource> $linked */
        $linked = $countdown->contentSources()
            ->wherePivot('is_active', true)
            ->where('content_sources.is_active', true)
            ->orderByDesc('content_source_countdown.weight')
            ->get();

        /** @var EloquentCollection<int, ContentSource> $global */
        $global = ContentSource::query()
            ->where('is_global', true)
            ->where('is_active', true)
            ->whereNotIn('id', $linked->modelKeys())
            ->orderByDesc('weight')
            ->get();

        /** @var EloquentCollection<int, ContentSource> $sources */
        $sources = $linked->concat($global);

        return $sources;
    }

    /** @return array<int, array<string, mixed>> */
    private function candidatesForSource(Countdown $countdown, ContentSource $source, ?string $query, int $daysBack): array
    {
        if ($this->isYouTubeSource($source)) {
            return $this->youtubeFeedCandidates($countdown, $source, $query, $daysBack);
        }

        if ($this->isGoogleNewsSource($source)) {
            return $this->googleNews->search(
                source: $source,
                query: $query,
                keywords: $this->keywordsFor($countdown, $source, $query),
                fromDate: CarbonImmutable::now('UTC')->subDays($daysBack),
                toDate: null,
                limit: 20,
            );
        }

        return [];
    }

    private function isYouTubeSource(ContentSource $source): bool
    {
        return $source->type === ContentSource::TYPE_YOUTUBE_CHANNEL && $source->provider === ContentSource::PROVIDER_YOUTUBE;
    }

    private function isGoogleNewsSource(ContentSource $source): bool
    {
        return $source->type === ContentSource::TYPE_RSS_FEED && $source->provider === ContentSource::PROVIDER_GOOGLE_NEWS;
    }

    /** @return array<int, array<string, mixed>> */
    private function youtubeFeedCandidates(Countdown $countdown, ContentSource $source, ?string $query, int $daysBack): array
    {
        return $this->youtubeFeedCandidatesForDateRange(
            source: $source,
            query: null,
            keywords: $this->keywordsFor($countdown, $source, $query),
            excluded: $this->excludedKeywordsFor($source),
            fromDate: CarbonImmutable::now('UTC')->subDays($daysBack),
            toDate: null,
        );
    }

    /** @param array<int, string> $keywords @param array<int, string> $excluded @return array<int, array<string, mixed>> */
    private function youtubeFeedCandidatesForDateRange(ContentSource $source, ?string $query, array $keywords, array $excluded, CarbonImmutable $fromDate, ?CarbonImmutable $toDate, ?int $maxAccepted = null): array
    {
        $startedAt = microtime(true);
        $feedUrl = $source->feed_url ?: ($source->external_id ? 'https://www.youtube.com/feeds/videos.xml?channel_id='.$source->external_id : null);
        if (! is_string($feedUrl) || trim($feedUrl) === '') {
            Log::channel('news_retrieval')->warning('youtube_feed.missing_feed_url', ContentSourceAgentRunContext::context([
                'source_id' => $source->getKey(),
                'source_name' => (string) $source->name,
            ]));

            return [];
        }

        Log::channel('news_retrieval')->info('youtube_feed.fetch.started', ContentSourceAgentRunContext::context([
            'source_id' => $source->getKey(),
            'source_name' => (string) $source->name,
            'feed_url' => $feedUrl,
            'query' => $query,
            'from_date' => $fromDate->toDateString(),
            'to_date' => $toDate?->toDateString(),
            'keywords_count' => count($keywords),
            'excluded_count' => count($excluded),
        ]));

        try {
            $response = Http::timeout(12)
                ->retry(1, 250)
                ->withHeaders([
                    'Accept' => 'application/atom+xml, application/xml;q=0.9, */*;q=0.8',
                    'User-Agent' => 'Mozilla/5.0 DoomsdayCountdownContentSource/0.1',
                ])
                ->get($feedUrl);
        } catch (Throwable $exception) {
            Log::channel('news_retrieval')->error('youtube_feed.fetch.failed', ContentSourceAgentRunContext::context([
                'source_id' => $source->getKey(),
                'source_name' => (string) $source->name,
                'feed_url' => $feedUrl,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
                'elapsed_ms' => round((microtime(true) - $startedAt) * 1000, 2),
            ]));

            return [];
        }

        if (! $response->successful()) {
            Log::channel('news_retrieval')->warning('youtube_feed.fetch.unsuccessful', ContentSourceAgentRunContext::context([
                'source_id' => $source->getKey(),
                'source_name' => (string) $source->name,
                'feed_url' => $feedUrl,
                'status' => $response->status(),
                'elapsed_ms' => round((microtime(true) - $startedAt) * 1000, 2),
            ]));

            return [];
        }

        $previous = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response->body(), SimpleXMLElement::class, LIBXML_NOCDATA | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $xml instanceof SimpleXMLElement) {
            Log::channel('news_retrieval')->warning('youtube_feed.invalid_xml', ContentSourceAgentRunContext::context([
                'source_id' => $source->getKey(),
                'source_name' => (string) $source->name,
                'feed_url' => $feedUrl,
                'body_chars' => mb_strlen($response->body()),
            ]));

            return [];
        }

        $stats = [
            'entries_seen' => 0,
            'null_candidate' => 0,
            'outside_date_range' => 0,
            'query_mismatch' => 0,
            'keyword_mismatch' => 0,
            'excluded' => 0,
            'accepted' => 0,
        ];
        $candidates = [];
        $feedNamespaces = $xml->getNamespaces(true);
        $atom = $xml->children($feedNamespaces[''] ?? 'http://www.w3.org/2005/Atom');
        $entries = $atom->entry ?? $xml->entry ?? [];

        foreach ($entries as $entry) {
            $stats['entries_seen']++;
            $candidate = $this->candidateFromYouTubeEntry($entry, $source);
            if ($candidate === null) {
                $stats['null_candidate']++;

                continue;
            }

            $publishedAt = $candidate['published_at'];
            if ($publishedAt instanceof CarbonImmutable) {
                if ($publishedAt->lt($fromDate) || ($toDate instanceof CarbonImmutable && $publishedAt->gt($toDate))) {
                    $stats['outside_date_range']++;

                    continue;
                }
            } elseif ($toDate instanceof CarbonImmutable) {
                $stats['outside_date_range']++;

                continue;
            }

            $haystack = mb_strtolower((string) $candidate['title'].' '.(string) $candidate['excerpt']);
            if (is_string($query) && trim($query) !== '') {
                if (! $this->matchesSearchQuery($haystack, $query)) {
                    $stats['query_mismatch']++;

                    continue;
                }
            } elseif (! $this->matchesAnyKeyword($haystack, $keywords)) {
                $stats['keyword_mismatch']++;

                continue;
            }

            if ($excluded !== [] && $this->matchesAnyKeyword($haystack, $excluded)) {
                $stats['excluded']++;

                continue;
            }

            $candidates[] = $this->enrichYouTubeCandidate($candidate);
            $stats['accepted']++;

            if ($maxAccepted !== null && count($candidates) >= $maxAccepted) {
                break;
            }
        }

        usort($candidates, fn (array $a, array $b): int => ($b['published_at']?->getTimestamp() ?? 0) <=> ($a['published_at']?->getTimestamp() ?? 0));

        Log::channel('news_retrieval')->info('youtube_feed.fetch.completed', ContentSourceAgentRunContext::context([
            'source_id' => $source->getKey(),
            'source_name' => (string) $source->name,
            'feed_url' => $feedUrl,
            'status' => $response->status(),
            'elapsed_ms' => round((microtime(true) - $startedAt) * 1000, 2),
            'entries_seen' => $stats['entries_seen'],
            'accepted' => $stats['accepted'],
            'outside_date_range' => $stats['outside_date_range'],
            'query_mismatch' => $stats['query_mismatch'],
            'keyword_mismatch' => $stats['keyword_mismatch'],
            'excluded' => $stats['excluded'],
            'null_candidate' => $stats['null_candidate'],
        ]));

        return $candidates;
    }

    /** @return array<string, mixed>|null */
    private function candidateFromYouTubeEntry(SimpleXMLElement $entry, ContentSource $source): ?array
    {
        $namespaces = $entry->getNamespaces(true);
        $yt = $entry->children($namespaces['yt'] ?? 'http://www.youtube.com/xml/schemas/2015');
        $media = $entry->children($namespaces['media'] ?? 'http://search.yahoo.com/mrss/');
        $videoId = trim((string) ($yt->videoId ?? ''));

        if ($videoId === '') {
            $id = trim((string) ($entry->id ?? ''));
            if (str_starts_with($id, 'yt:video:')) {
                $videoId = Str::after($id, 'yt:video:');
            }
        }

        if ($videoId === '') {
            return null;
        }

        $watchUrl = $this->youTubeUrls->watchUrl($videoId);
        $description = trim((string) ($media->group->description ?? ''));
        $thumbnailUrl = null;
        if (isset($media->group->thumbnail)) {
            $attributes = $media->group->thumbnail->attributes();
            $thumbnailUrl = isset($attributes['url']) ? (string) $attributes['url'] : null;
        }

        $publishedAt = null;
        try {
            $publishedAt = CarbonImmutable::parse((string) ($entry->published ?? $entry->updated ?? ''), 'UTC');
        } catch (Throwable) {
            // Keep null; candidate can still be inspected, but insert will preserve null date.
        }

        return [
            'content_type' => 'youtube_video',
            'title' => trim((string) ($entry->title ?? '')),
            'excerpt' => Str::limit($description !== '' ? $description : trim((string) ($entry->title ?? '')), 1000, ''),
            'source_name' => $source->name,
            'source_url' => $watchUrl,
            'canonical_source_url' => $watchUrl,
            'canonical_source_hash' => $this->urls->hash($watchUrl),
            'external_provider' => ContentSource::PROVIDER_YOUTUBE,
            'external_id' => $videoId,
            'embed_url' => $this->youTubeUrls->embedUrl($videoId),
            'preview_image_url' => $thumbnailUrl ?: $this->youTubeUrls->thumbnailUrl($videoId),
            'published_at' => $publishedAt,
        ];
    }

    /** @param array<string, mixed> $candidate @return array<string, mixed> */
    private function enrichYouTubeCandidate(array $candidate): array
    {
        $metadata = $this->oEmbed->metadata((string) $candidate['source_url']);

        if (is_string($metadata['title']) && $metadata['title'] !== '') {
            $candidate['title'] = $metadata['title'];
        }

        if (is_string($metadata['author_name']) && $metadata['author_name'] !== '') {
            $candidate['source_name'] = $metadata['author_name'];
        }

        if (is_string($metadata['thumbnail_url']) && $metadata['thumbnail_url'] !== '') {
            $candidate['preview_image_url'] = $metadata['thumbnail_url'];
        }

        return $candidate;
    }

    /** @param array<string, mixed> $candidate @param array<int, string> $existingExternalIds @param array<int, string> $existingHashes @param array<int, string> $batchExternalIds @return array{status: string, reason: string} */
    private function decide(array $candidate, array $existingExternalIds, array $existingHashes, array $batchExternalIds, int $accepted, int $limit): array
    {
        $externalId = (string) ($candidate['external_id'] ?? '');
        $hash = (string) ($candidate['canonical_source_hash'] ?? '');

        if ($externalId === '' && $hash === '') {
            return ['status' => 'skipped', 'reason' => 'missing_external_id_and_canonical_hash'];
        }

        if ($externalId !== '' && (in_array($externalId, $existingExternalIds, true) || in_array($externalId, $batchExternalIds, true))) {
            return ['status' => 'duplicate', 'reason' => 'same_external_id_exists'];
        }

        if ($hash !== '' && in_array($hash, $existingHashes, true)) {
            return ['status' => 'duplicate', 'reason' => 'same_canonical_url_exists'];
        }

        if ($accepted >= $limit) {
            return ['status' => 'skipped', 'reason' => 'limit_reached'];
        }

        return ['status' => 'new', 'reason' => 'candidate_not_found_in_existing_news'];
    }

    /** @param array<string, mixed> $candidate */
    private function insertCandidate(Countdown $countdown, array $candidate): News
    {
        $sortOrder = ((int) $countdown->news()->max('sort_order')) + 1;

        return $countdown->news()->create([
            'locale' => (string) ($candidate['locale'] ?? NewsLocale::All->value),
            'title' => Str::limit((string) $candidate['title'], 255, ''),
            'excerpt' => (string) ($candidate['excerpt'] ?: $candidate['title']),
            'content_type' => (string) $candidate['content_type'],
            'source_name' => Str::limit((string) $candidate['source_name'], 255, ''),
            'source_url' => (string) $candidate['source_url'],
            'canonical_source_url' => (string) $candidate['canonical_source_url'],
            'canonical_source_hash' => (string) $candidate['canonical_source_hash'],
            'external_provider' => (string) $candidate['external_provider'],
            'external_id' => (string) $candidate['external_id'],
            'embed_url' => (string) $candidate['embed_url'],
            'preview_image_url' => (string) $candidate['preview_image_url'],
            'image_path' => null,
            'published_at' => $candidate['published_at'],
            'sort_order' => $sortOrder,
            'is_featured' => false,
        ]);
    }

    /** @return array<int, string> */
    private function keywordsFor(Countdown $countdown, ContentSource $source, ?string $query): array
    {
        $values = [];
        if (is_string($query) && trim($query) !== '') {
            $values[] = $query;
        }

        $pivotKeywords = $this->pivotArray($source, 'keywords');
        $values = array_merge($values, $pivotKeywords, Arr::wrap($source->keywords));
        $values[] = (string) data_get($countdown->title, 'en', $countdown->slug);
        $values[] = (string) data_get($countdown->summary, 'en', '');

        return collect($values)
            ->flatMap(fn (mixed $value): array => is_array($value) ? $value : (preg_split('/[,|]/', (string) $value) ?: []))
            ->map(fn (mixed $value): string => mb_strtolower(trim((string) $value)))
            ->filter(fn (string $value): bool => $value !== '' && mb_strlen($value) >= 3)
            ->unique()
            ->values()
            ->all();
    }

    /** @return array<int, string> */
    private function excludedKeywordsFor(ContentSource $source): array
    {
        return collect($this->pivotArray($source, 'excluded_keywords'))
            ->map(fn (mixed $value): string => mb_strtolower(trim((string) $value)))
            ->filter(fn (string $value): bool => $value !== '')
            ->values()
            ->all();
    }

    /** @return array<int, mixed> */
    private function pivotArray(ContentSource $source, string $key): array
    {
        $value = $source->pivot?->{$key} ?? null;
        if (is_string($value)) {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return is_array($value) ? $value : [];
    }

    /** @param array<int, string> $keywords */
    private function matchesAnyKeyword(string $haystack, array $keywords): bool
    {
        if ($keywords === []) {
            return true;
        }

        foreach ($keywords as $keyword) {
            if ($keyword !== '' && str_contains($haystack, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function matchesSearchQuery(string $haystack, string $query): bool
    {
        $groups = preg_split('/[,|]/u', mb_strtolower($query)) ?: [];

        foreach ($groups as $group) {
            $terms = collect(preg_split('/\s+/u', trim((string) $group)) ?: [])
                ->map(fn (mixed $term): string => trim((string) $term))
                ->filter(fn (string $term): bool => $term !== '' && mb_strlen($term) >= 3)
                ->values()
                ->all();

            if ($terms === []) {
                continue;
            }

            foreach ($terms as $term) {
                if (! str_contains($haystack, $term)) {
                    continue 2;
                }
            }

            return true;
        }

        return false;
    }

    /** @return array<int, string> */
    private function existingVideoIds(Countdown $countdown): array
    {
        return $countdown->news()
            ->whereNotNull('external_id')
            ->pluck('external_id')
            ->map(fn (mixed $id): string => (string) $id)
            ->all();
    }

    /** @return array<int, string> */
    private function existingHashes(Countdown $countdown): array
    {
        return $countdown->news()
            ->whereNotNull('canonical_source_hash')
            ->pluck('canonical_source_hash')
            ->map(fn (mixed $hash): string => (string) $hash)
            ->all();
    }
}
