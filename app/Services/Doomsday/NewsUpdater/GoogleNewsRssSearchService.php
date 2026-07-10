<?php

declare(strict_types=1);

namespace App\Services\Doomsday\NewsUpdater;

use App\Models\ContentSource;
use App\Support\ContentSourceAgentRunContext;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleXMLElement;
use Throwable;

final class GoogleNewsRssSearchService
{
    public function __construct(
        private readonly NewsUrlNormalizer $urls,
        private readonly NewsArticlePreviewImageService $previewImages,
    ) {}

    /** @param array<int, string> $keywords @return array<int, array<string, mixed>> */
    public function search(ContentSource $source, ?string $query, array $keywords, CarbonImmutable $fromDate, ?CarbonImmutable $toDate, int $limit = 10): array
    {
        $limit = max(1, min($limit, 20));
        $startedAt = microtime(true);
        $rssUrl = $this->rssUrl($source, $query, $keywords);

        Log::channel('news_retrieval')->info('google_news.fetch.started', ContentSourceAgentRunContext::context([
            'source_name' => (string) $source->name,
            'source_language' => (string) ($source->language ?? ''),
            'rss_url' => $rssUrl,
            'query' => $query,
            'from_date' => $fromDate->toDateString(),
            'to_date' => $toDate?->toDateString(),
            'limit' => $limit,
        ]));

        try {
            $response = Http::timeout(12)
                ->retry(1, 250)
                ->withHeaders([
                    'Accept' => 'application/rss+xml, application/xml;q=0.9, */*;q=0.8',
                    'User-Agent' => 'Mozilla/5.0 DoomsdayCountdownGoogleNewsSearch/0.1',
                ])
                ->get($rssUrl);
        } catch (Throwable $exception) {
            Log::channel('news_retrieval')->error('google_news.fetch.failed', ContentSourceAgentRunContext::context([
                'source_name' => (string) $source->name,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
                'elapsed_ms' => round((microtime(true) - $startedAt) * 1000, 2),
            ]));

            return [];
        }

        if (! $response->successful()) {
            Log::channel('news_retrieval')->warning('google_news.fetch.unsuccessful', ContentSourceAgentRunContext::context([
                'source_name' => (string) $source->name,
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
            Log::channel('news_retrieval')->warning('google_news.invalid_xml', ContentSourceAgentRunContext::context([
                'source_name' => (string) $source->name,
                'body_chars' => mb_strlen($response->body()),
            ]));

            return [];
        }

        $stats = [
            'entries_seen' => 0,
            'outside_date_range' => 0,
            'missing_url_or_title' => 0,
            'accepted' => 0,
        ];
        $items = [];

        foreach ($xml->channel->item ?? [] as $item) {
            $stats['entries_seen']++;
            $candidate = $this->candidateFromItem($item, $source);
            if ($candidate === null) {
                $stats['missing_url_or_title']++;

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

            $candidate['preview_image_url'] = $this->articlePreviewImage($candidate, $source);

            $items[] = $candidate;
            $stats['accepted']++;

            if (count($items) >= $limit) {
                break;
            }
        }

        usort($items, fn (array $a, array $b): int => ($b['published_at']?->getTimestamp() ?? 0) <=> ($a['published_at']?->getTimestamp() ?? 0));

        Log::channel('news_retrieval')->info('google_news.fetch.completed', ContentSourceAgentRunContext::context([
            'source_name' => (string) $source->name,
            'status' => $response->status(),
            'elapsed_ms' => round((microtime(true) - $startedAt) * 1000, 2),
            'entries_seen' => $stats['entries_seen'],
            'accepted' => $stats['accepted'],
            'outside_date_range' => $stats['outside_date_range'],
            'missing_url_or_title' => $stats['missing_url_or_title'],
        ]));

        return $items;
    }

    /** @param array<int, string> $keywords */
    private function rssUrl(ContentSource $source, ?string $query, array $keywords): string
    {
        $metadata = is_array($source->metadata) ? $source->metadata : [];
        $terms = trim((string) $query);
        if ($terms === '') {
            $terms = collect($keywords)
                ->map(fn (mixed $keyword): string => trim((string) $keyword))
                ->filter(fn (string $keyword): bool => $keyword !== '')
                ->take(8)
                ->implode(' ');
        }

        $site = trim((string) ($metadata['site'] ?? ''));
        if ($site !== '') {
            $terms = trim($terms.' site:'.$site);
        }

        $baseUrl = (string) ($source->feed_url ?: 'https://news.google.com/rss/search');
        $parts = parse_url($baseUrl);
        $existing = [];
        parse_str((string) ($parts['query'] ?? ''), $existing);

        $params = array_merge($existing, [
            'q' => $terms !== '' ? $terms : trim((string) $source->name),
        ]);

        $scheme = (string) ($parts['scheme'] ?? 'https');
        $host = (string) ($parts['host'] ?? 'news.google.com');
        $path = (string) ($parts['path'] ?? '/rss/search');

        return $scheme.'://'.$host.$path.'?'.http_build_query($params, '', '&', PHP_QUERY_RFC3986);
    }

    /** @return array<string, mixed>|null */
    private function candidateFromItem(SimpleXMLElement $item, ContentSource $source): ?array
    {
        $sourceName = trim((string) ($item->source ?? '')) ?: (string) $source->name;
        $sourceAttributes = $item->source?->attributes();
        $publisherUrl = $sourceAttributes !== null ? trim((string) ($sourceAttributes['url'] ?? '')) : '';
        $title = $this->cleanTitle((string) ($item->title ?? ''), $sourceName);
        $description = (string) ($item->description ?? '');
        $url = $this->extractFirstNonGoogleUrl($description) ?: trim((string) ($item->link ?? ''));
        $url = html_entity_decode($url, ENT_QUOTES | ENT_HTML5);

        if ($title === '' || $url === '') {
            return null;
        }

        $publishedAt = null;
        try {
            $publishedAt = CarbonImmutable::parse((string) ($item->pubDate ?? ''), 'UTC');
        } catch (Throwable) {
            // Keep null; caller will decide whether date-less items are acceptable.
        }

        $canonical = $this->urls->canonicalize($url) ?: $url;
        $hash = $this->urls->hash($canonical) ?: hash('sha256', $canonical);
        $language = is_string($source->language) && $source->language !== '' ? $source->language : null;

        return [
            'content_type' => 'article',
            'locale' => $language ?: 'all',
            'title' => $title,
            'excerpt' => Str::limit($this->cleanExcerpt($description, $title), 1000, ''),
            'source_name' => $sourceName,
            'source_url' => $url,
            'publisher_url' => $publisherUrl,
            'canonical_source_url' => $canonical,
            'canonical_source_hash' => $hash,
            'external_provider' => ContentSource::PROVIDER_GOOGLE_NEWS,
            'external_id' => $hash,
            'embed_url' => '',
            'preview_image_url' => '',
            'published_at' => $publishedAt,
        ];
    }

    /** @param array<string, mixed> $candidate */
    private function articlePreviewImage(array $candidate, ContentSource $source): string
    {
        foreach ($this->previewLookupUrls($candidate, $source) as $url) {
            $preview = $this->previewImages->preview($url);
            if (is_string($preview['image_url']) && $preview['image_url'] !== '') {
                return $preview['image_url'];
            }
        }

        return $this->fallbackFaviconUrl($candidate, $source);
    }

    /** @param array<string, mixed> $candidate @return array<int, string> */
    private function previewLookupUrls(array $candidate, ContentSource $source): array
    {
        return collect([
            $candidate['source_url'] ?? null,
            $candidate['publisher_url'] ?? null,
            $source->source_url,
        ])
            ->map(fn (mixed $url): string => trim((string) $url))
            ->filter(fn (string $url): bool => $url !== '' && str_starts_with($url, 'http'))
            ->unique()
            ->values()
            ->all();
    }

    /** @param array<string, mixed> $candidate */
    private function fallbackFaviconUrl(array $candidate, ContentSource $source): string
    {
        foreach ($this->previewLookupUrls($candidate, $source) as $url) {
            $host = strtolower((string) parse_url($url, PHP_URL_HOST));
            if ($host !== '') {
                return 'https://www.google.com/s2/favicons?domain='.rawurlencode($host).'&sz=256';
            }
        }

        return '';
    }

    private function cleanTitle(string $title, ?string $sourceName): string
    {
        $title = trim(html_entity_decode(strip_tags($title), ENT_QUOTES | ENT_HTML5));
        $title = preg_replace('/\s+/u', ' ', $title) ?? $title;

        if ($sourceName !== null && str_ends_with($title, ' - '.$sourceName)) {
            $title = Str::beforeLast($title, ' - '.$sourceName);
        }

        return trim($title);
    }

    private function cleanExcerpt(string $description, string $fallback): string
    {
        $text = trim(html_entity_decode(strip_tags($description), ENT_QUOTES | ENT_HTML5));
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return $text !== '' ? $text : $fallback;
    }

    private function extractFirstNonGoogleUrl(string $html): ?string
    {
        if (! preg_match_all('/<a\s+[^>]*href=["\']([^"\']+)["\']/i', $html, $matches)) {
            return null;
        }

        foreach ($matches[1] as $href) {
            $href = html_entity_decode((string) $href, ENT_QUOTES | ENT_HTML5);
            $host = strtolower((string) parse_url($href, PHP_URL_HOST));

            if ($host === '' || str_contains($host, 'google.com') || str_contains($host, 'news.google.com')) {
                continue;
            }

            return $href;
        }

        return null;
    }
}
