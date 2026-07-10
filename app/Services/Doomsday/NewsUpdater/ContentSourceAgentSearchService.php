<?php

declare(strict_types=1);

namespace App\Services\Doomsday\NewsUpdater;

use App\Models\ContentSource;
use App\Support\ContentSourceAgentRunContext;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

final class ContentSourceAgentSearchService
{
    public function __construct(private readonly ContentSourceNewsRefreshService $refreshService) {}

    /** @param array<int, string> $sourceKeys @return array<int, array<string, mixed>> */
    public function availableSources(array $sourceKeys = [], ?string $language = null): array
    {
        $sources = ContentSource::query()
            ->where('is_active', true)
            ->orderByDesc('weight')
            ->orderBy('name')
            ->get();

        $normalizedKeys = collect($sourceKeys)
            ->map(fn (mixed $key): string => mb_strtolower(trim((string) $key)))
            ->filter()
            ->values()
            ->all();
        $normalizedLanguage = $language !== null && trim($language) !== '' ? mb_strtolower(trim($language)) : null;

        return $sources
            ->filter(function (ContentSource $source) use ($normalizedKeys, $normalizedLanguage): bool {
                if ($normalizedLanguage !== null) {
                    $sourceLanguage = mb_strtolower((string) ($source->language ?? ''));
                    if ($sourceLanguage !== $normalizedLanguage) {
                        return false;
                    }
                }

                if ($normalizedKeys === []) {
                    return true;
                }

                return in_array(mb_strtolower($this->sourceKey($source)), $normalizedKeys, true)
                    || in_array(mb_strtolower((string) $source->name), $normalizedKeys, true)
                    || in_array(mb_strtolower((string) $source->external_id), $normalizedKeys, true);
            })
            ->map(fn (ContentSource $source): array => $this->sourcePayload($source))
            ->values()
            ->all();
    }

    /** @return array<int, string> */
    public function allowedSourceKeys(): array
    {
        return collect($this->availableSources())->pluck('source_key')->filter()->values()->all();
    }

    /** @param array<string, mixed> $source @return array<string, mixed> */
    public function searchCatalogSource(array $source, string $query, string $fromDate, string $toDate, int $limit = 10): array
    {
        $contentSource = new ContentSource;
        $contentSource->forceFill([
            'type' => $source['type'] ?? null,
            'provider' => $source['provider'] ?? null,
            'name' => $source['name'] ?? null,
            'external_id' => $source['external_id'] ?? null,
            'source_url' => $source['source_url'] ?? null,
            'feed_url' => $source['feed_url'] ?? null,
            'language' => $source['language'] ?? null,
            'topics' => $source['topics'] ?? [],
            'keywords' => $source['keywords'] ?? [],
            'metadata' => $source['metadata'] ?? [],
            'weight' => $source['weight'] ?? 100,
            'is_global' => $source['is_global'] ?? false,
            'is_active' => true,
        ]);

        return $this->searchContentSource($contentSource, $query, $fromDate, $toDate, $limit, false);
    }

    /** @return array<string, mixed> */
    public function search(string $source, string $query, string $fromDate, string $toDate, int $limit = 10): array
    {
        $limit = max(1, min($limit, 20));
        Log::channel('news_retrieval')->info('agent_search.started', ContentSourceAgentRunContext::context([
            'source' => $source,
            'query' => $query,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'limit' => $limit,
        ]));

        $contentSource = $this->resolveSource($source);
        if (! $contentSource instanceof ContentSource) {
            Log::channel('news_retrieval')->warning('agent_search.unknown_source', ContentSourceAgentRunContext::context([
                'source' => $source,
                'available_sources_count' => count($this->availableSources()),
            ]));

            return [
                'ok' => false,
                'source' => null,
                'query' => $query,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'limit' => $limit,
                'items_count' => 0,
                'items' => [],
                'errors' => ['unknown_source'],
                'available_sources' => $this->availableSources(),
            ];
        }

        return $this->searchContentSource($contentSource, $query, $fromDate, $toDate, $limit, true);
    }

    /** @return array<string, mixed> */
    private function searchContentSource(ContentSource $contentSource, string $query, string $fromDate, string $toDate, int $limit, bool $includeAvailableSourcesOnError): array
    {
        $limit = max(1, min($limit, 20));

        try {
            $from = CarbonImmutable::parse($fromDate, 'UTC')->startOfDay();
            $to = CarbonImmutable::parse($toDate, 'UTC')->endOfDay();
        } catch (Throwable) {
            Log::channel('news_retrieval')->warning('agent_search.invalid_date_range', ContentSourceAgentRunContext::context([
                'source_key' => $this->sourceKey($contentSource),
                'from_date' => $fromDate,
                'to_date' => $toDate,
            ]));

            return array_filter([
                'ok' => false,
                'source' => $this->sourcePayload($contentSource),
                'query' => $query,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'limit' => $limit,
                'items_count' => 0,
                'items' => [],
                'errors' => ['invalid_date_range'],
                'available_sources' => $includeAvailableSourcesOnError ? $this->availableSources() : null,
            ], static fn (mixed $value): bool => $value !== null);
        }

        if ($from->gt($to)) {
            Log::channel('news_retrieval')->warning('agent_search.from_date_after_to_date', ContentSourceAgentRunContext::context([
                'source_key' => $this->sourceKey($contentSource),
                'from_date' => $from->toDateString(),
                'to_date' => $to->toDateString(),
            ]));

            return array_filter([
                'ok' => false,
                'source' => $this->sourcePayload($contentSource),
                'query' => $query,
                'from_date' => $from->toDateString(),
                'to_date' => $to->toDateString(),
                'limit' => $limit,
                'items_count' => 0,
                'items' => [],
                'errors' => ['from_date_after_to_date'],
                'available_sources' => $includeAvailableSourcesOnError ? $this->availableSources() : null,
            ], static fn (mixed $value): bool => $value !== null);
        }

        $items = collect($this->refreshService->searchSourceCandidates($contentSource, $query, $from, $to, $limit))
            ->map(fn (array $candidate): array => $this->candidatePayload($contentSource, $candidate))
            ->values()
            ->all();

        Log::channel('news_retrieval')->info('agent_search.completed', ContentSourceAgentRunContext::context([
            'source_key' => $this->sourceKey($contentSource),
            'items_count' => count($items),
            'from_date' => $from->toDateString(),
            'to_date' => $to->toDateString(),
        ]));

        return [
            'ok' => true,
            'source' => $this->sourcePayload($contentSource),
            'query' => $query,
            'from_date' => $from->toDateString(),
            'to_date' => $to->toDateString(),
            'limit' => $limit,
            'items_count' => count($items),
            'items' => $items,
            'errors' => [],
        ];
    }

    private function resolveSource(string $source): ?ContentSource
    {
        $needle = mb_strtolower(trim($source));
        if ($needle === '') {
            return null;
        }

        /** @var ContentSource|null $match */
        $match = ContentSource::query()
            ->where('is_active', true)
            ->get()
            ->first(function (ContentSource $candidate) use ($needle): bool {
                return in_array($needle, [
                    mb_strtolower($this->sourceKey($candidate)),
                    mb_strtolower((string) $candidate->name),
                    mb_strtolower((string) $candidate->external_id),
                ], true);
            });

        return $match;
    }

    /** @return array<string, mixed> */
    private function sourcePayload(ContentSource $source): array
    {
        return [
            'source_key' => $this->sourceKey($source),
            'name' => (string) $source->name,
            'type' => (string) $source->type,
            'provider' => (string) $source->provider,
            'external_id' => $source->external_id !== null ? (string) $source->external_id : null,
            'source_url' => $source->source_url !== null ? (string) $source->source_url : null,
            'feed_url' => $source->feed_url !== null ? (string) $source->feed_url : null,
            'language' => $source->language !== null ? (string) $source->language : null,
            'topics' => is_array($source->topics) ? $source->topics : [],
            'keywords' => is_array($source->keywords) ? $source->keywords : [],
            'metadata' => is_array($source->metadata) ? $source->metadata : [],
            'weight' => (int) $source->weight,
            'is_global' => (bool) $source->is_global,
            'is_active' => (bool) $source->is_active,
        ];
    }

    /** @param array<string, mixed> $candidate @return array<string, mixed> */
    private function candidatePayload(ContentSource $source, array $candidate): array
    {
        $publishedAt = $candidate['published_at'] ?? null;

        return [
            'source_key' => $this->sourceKey($source),
            'locale' => (string) ($candidate['locale'] ?? $source->language ?? 'all'),
            'content_type' => (string) ($candidate['content_type'] ?? 'youtube_video'),
            'title' => (string) ($candidate['title'] ?? ''),
            'excerpt' => Str::limit((string) ($candidate['excerpt'] ?? ''), 500, ''),
            'source_name' => (string) ($candidate['source_name'] ?? $source->name),
            'source_url' => (string) ($candidate['source_url'] ?? ''),
            'canonical_source_url' => (string) ($candidate['canonical_source_url'] ?? $candidate['source_url'] ?? ''),
            'canonical_source_hash' => (string) ($candidate['canonical_source_hash'] ?? ''),
            'external_provider' => (string) ($candidate['external_provider'] ?? $source->provider ?? ''),
            'external_id' => (string) ($candidate['external_id'] ?? ''),
            'embed_url' => (string) ($candidate['embed_url'] ?? ''),
            'preview_image_url' => (string) ($candidate['preview_image_url'] ?? ''),
            'published_at' => $publishedAt instanceof CarbonImmutable ? $publishedAt->toDateTimeString() : null,
        ];
    }

    private function sourceKey(ContentSource $source): string
    {
        $base = Str::slug((string) $source->name);

        return $base !== '' ? $base : 'source-'.$source->getKey();
    }
}
