<?php

declare(strict_types=1);

namespace App\Services\Doomsday\NewsUpdater;

use App\Enums\NewsLocale;
use App\Models\ContentSource;
use App\Models\Countdown;
use App\Models\News;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use SimpleXMLElement;
use Throwable;

final class ContentSourceNewsRefreshService
{
    public function __construct(
        private readonly NewsUrlNormalizer $urls,
        private readonly YouTubeVideoUrl $youTubeUrls,
        private readonly YouTubeOEmbedService $oEmbed,
    ) {
    }

    /** @return array<string, mixed> */
    public function run(Countdown $countdown, ?string $query = null, int $daysBack = 30, int $limit = 5, bool $insert = false): array
    {
        $daysBack = max(1, min($daysBack, 120));
        $limit = max(1, min($limit, 20));
        $sources = $this->sourcesForCountdown($countdown);
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
                        $row['reason'] = 'created news #' . $news->getKey();
                    }
                }

                $rows[] = $row;
            }
        }

        return [
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
        if ($source->type !== ContentSource::TYPE_YOUTUBE_CHANNEL || $source->provider !== ContentSource::PROVIDER_YOUTUBE) {
            return [];
        }

        return $this->youtubeFeedCandidates($countdown, $source, $query, $daysBack);
    }

    /** @return array<int, array<string, mixed>> */
    private function youtubeFeedCandidates(Countdown $countdown, ContentSource $source, ?string $query, int $daysBack): array
    {
        $feedUrl = $source->feed_url ?: ($source->external_id ? 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $source->external_id : null);
        if (! is_string($feedUrl) || trim($feedUrl) === '') {
            return [];
        }

        try {
            $response = Http::timeout(12)
                ->retry(1, 250)
                ->withHeaders([
                    'Accept' => 'application/atom+xml, application/xml;q=0.9, */*;q=0.8',
                    'User-Agent' => 'Mozilla/5.0 DoomsdayCountdownContentSource/0.1',
                ])
                ->get($feedUrl);
        } catch (Throwable) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        $previous = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response->body(), SimpleXMLElement::class, LIBXML_NOCDATA | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $xml instanceof SimpleXMLElement) {
            return [];
        }

        $cutoff = CarbonImmutable::now('UTC')->subDays($daysBack);
        $keywords = $this->keywordsFor($countdown, $source, $query);
        $excluded = $this->excludedKeywordsFor($source);
        $candidates = [];
        $feedNamespaces = $xml->getNamespaces(true);
        $atom = $xml->children($feedNamespaces[''] ?? 'http://www.w3.org/2005/Atom');
        $entries = $atom->entry ?? $xml->entry ?? [];

        foreach ($entries as $entry) {
            $candidate = $this->candidateFromYouTubeEntry($entry, $source);
            if ($candidate === null) {
                continue;
            }

            $publishedAt = $candidate['published_at'];
            if ($publishedAt instanceof CarbonImmutable && $publishedAt->lt($cutoff)) {
                continue;
            }

            $haystack = mb_strtolower((string) $candidate['title'] . ' ' . (string) $candidate['excerpt']);
            if (! $this->matchesAnyKeyword($haystack, $keywords)) {
                continue;
            }

            if ($excluded !== [] && $this->matchesAnyKeyword($haystack, $excluded)) {
                continue;
            }

            $candidates[] = $this->enrichYouTubeCandidate($candidate);
        }

        usort($candidates, fn (array $a, array $b): int => ($b['published_at']?->getTimestamp() ?? 0) <=> ($a['published_at']?->getTimestamp() ?? 0));

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

    /** @param array<string, mixed> $candidate @param array<int, string> $existingVideoIds @param array<int, string> $existingHashes @param array<int, string> $batchVideoIds @return array{status: string, reason: string} */
    private function decide(array $candidate, array $existingVideoIds, array $existingHashes, array $batchVideoIds, int $accepted, int $limit): array
    {
        $videoId = (string) ($candidate['external_id'] ?? '');
        $hash = (string) ($candidate['canonical_source_hash'] ?? '');

        if ($videoId === '') {
            return ['status' => 'skipped', 'reason' => 'missing_youtube_video_id'];
        }

        if (in_array($videoId, $existingVideoIds, true) || in_array($videoId, $batchVideoIds, true)) {
            return ['status' => 'duplicate', 'reason' => 'same_youtube_video_exists'];
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
            'locale' => NewsLocale::All->value,
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

    /** @return array<int, string> */
    private function existingVideoIds(Countdown $countdown): array
    {
        return $countdown->news()
            ->where('external_provider', ContentSource::PROVIDER_YOUTUBE)
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
