<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use SimpleXMLElement;
use Throwable;

final class ProbeNewsPreviewImagesCommand extends Command
{
    protected $signature = 'countdowns:probe-news-preview-images
        {query=Taiwan China invasion : Search query to probe.}
        {--limit=8 : Number of article candidates to inspect.}';

    protected $description = 'Probe article preview image metadata from recent news candidates without persisting anything.';

    public function handle(): int
    {
        $query = trim((string) $this->argument('query'));
        $limit = max(1, min((int) $this->option('limit'), 12));

        $candidates = $this->fetchGoogleNewsCandidates($query, $limit);
        $rows = [];
        $found = 0;

        foreach ($candidates as $candidate) {
            $preview = $this->tryExtractPreview((string) $candidate['url']);
            if (is_string($preview['image_url']) && $preview['image_url'] !== '') {
                $found++;
            }

            $rows[] = [
                'source' => Str::limit((string) $candidate['source'], 20),
                'title' => Str::limit((string) $candidate['title'], 62),
                'http' => (string) ($preview['http_status'] ?? '-'),
                'img_src' => (string) ($preview['image_source'] ?? '-'),
                'image' => Str::limit((string) ($preview['image_url'] ?? '-'), 92),
                'error' => Str::limit((string) ($preview['error'] ?? ''), 48),
            ];
        }

        $this->line('Query: ' . $query);
        $this->line(sprintf('Candidates: %d | Images found: %d', count($candidates), $found));

        if ($rows !== []) {
            $this->table(['Source', 'Title', 'HTTP', 'Image source', 'Image URL', 'Error'], $rows);
        }

        return self::SUCCESS;
    }

    /** @return array<int, array{title: string, source: ?string, url: string, published_at: ?string}> */
    private function fetchGoogleNewsCandidates(string $query, int $limit): array
    {
        $url = 'https://news.google.com/rss/search?' . http_build_query([
            'q' => $query . ' when:7d',
            'hl' => 'en-US',
            'gl' => 'US',
            'ceid' => 'US:en',
        ], '', '&', PHP_QUERY_RFC3986);

        $response = Http::timeout(12)
            ->retry(1, 250)
            ->withHeaders([
                'Accept' => 'application/rss+xml, application/xml;q=0.9, */*;q=0.8',
                'User-Agent' => 'Mozilla/5.0 DoomsdayCountdownPreviewProbe/0.1',
            ])
            ->get($url);

        if (! $response->successful()) {
            $this->warn('Google News RSS returned HTTP ' . $response->status());

            return [];
        }

        $previous = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response->body(), SimpleXMLElement::class, LIBXML_NOCDATA | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $xml instanceof SimpleXMLElement) {
            $this->warn('Google News RSS response is not valid XML.');

            return [];
        }

        $rows = [];
        foreach ($xml->channel->item ?? [] as $item) {
            $source = trim((string) ($item->source ?? '')) ?: null;
            $title = $this->cleanTitle((string) ($item->title ?? ''), $source);
            $description = (string) ($item->description ?? '');
            $url = $this->extractFirstNonGoogleUrl($description) ?: trim((string) ($item->link ?? ''));

            if ($title === '' || $url === '') {
                continue;
            }

            $publishedAt = null;
            try {
                $publishedAt = CarbonImmutable::parse((string) ($item->pubDate ?? ''))->toDateTimeString();
            } catch (Throwable) {
                // Ignore invalid dates in this diagnostic command.
            }

            $rows[] = [
                'title' => $title,
                'source' => $source,
                'url' => $url,
                'published_at' => $publishedAt,
            ];

            if (count($rows) >= $limit) {
                break;
            }
        }

        return $rows;
    }

    /** @return array{http_status: int|null, image_source: string|null, image_url: string|null, error: string|null} */
    private function tryExtractPreview(string $url): array
    {
        try {
            $response = Http::timeout(10)
                ->retry(1, 250)
                ->withHeaders([
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'User-Agent' => 'Mozilla/5.0 DoomsdayCountdownPreviewProbe/0.1',
                ])
                ->get($url);
        } catch (Throwable $exception) {
            return [
                'http_status' => null,
                'image_source' => null,
                'image_url' => null,
                'error' => $exception->getMessage(),
            ];
        }

        $html = $response->body();
        $finalUrl = method_exists($response, 'effectiveUri') ? (string) $response->effectiveUri() : $url;

        foreach ([
            'og:image' => $this->extractMetaContent($html, ['og:image:secure_url', 'og:image']),
            'twitter:image' => $this->extractMetaContent($html, ['twitter:image', 'twitter:image:src']),
            'jsonld:image' => $this->extractJsonLdImage($html),
        ] as $source => $imageUrl) {
            if (is_string($imageUrl) && trim($imageUrl) !== '') {
                return [
                    'http_status' => $response->status(),
                    'image_source' => $source,
                    'image_url' => $this->absoluteUrl($finalUrl ?: $url, $imageUrl),
                    'error' => null,
                ];
            }
        }

        return [
            'http_status' => $response->status(),
            'image_source' => null,
            'image_url' => null,
            'error' => $response->successful() ? 'no_preview_image_metadata_found' : 'http_not_successful',
        ];
    }

    /** @param array<int, string> $names */
    private function extractMetaContent(string $html, array $names): ?string
    {
        foreach ($names as $name) {
            $quoted = preg_quote($name, '/');
            $patterns = [
                '/<meta\s+[^>]*(?:property|name)=["\']' . $quoted . '["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i',
                '/<meta\s+[^>]*content=["\']([^"\']+)["\'][^>]*(?:property|name)=["\']' . $quoted . '["\'][^>]*>/i',
            ];

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $html, $matches)) {
                    $value = html_entity_decode(trim((string) $matches[1]), ENT_QUOTES | ENT_HTML5);
                    if ($value !== '') {
                        return $value;
                    }
                }
            }
        }

        return null;
    }

    private function extractJsonLdImage(string $html): ?string
    {
        if (! preg_match_all('/<script\s+[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/is', $html, $matches)) {
            return null;
        }

        foreach ($matches[1] as $script) {
            $decoded = json_decode(html_entity_decode(trim((string) $script), ENT_QUOTES | ENT_HTML5), true);
            $image = $this->findJsonLdImage($decoded);
            if (is_string($image) && trim($image) !== '') {
                return trim($image);
            }
        }

        return null;
    }

    private function findJsonLdImage(mixed $value): mixed
    {
        if (! is_array($value)) {
            return null;
        }

        if (array_key_exists('image', $value)) {
            $image = $value['image'];
            if (is_string($image)) {
                return $image;
            }

            if (is_array($image)) {
                if (isset($image['url']) && is_string($image['url'])) {
                    return $image['url'];
                }

                foreach ($image as $item) {
                    if (is_string($item)) {
                        return $item;
                    }

                    if (is_array($item) && isset($item['url']) && is_string($item['url'])) {
                        return $item['url'];
                    }
                }
            }
        }

        foreach ($value as $item) {
            $found = $this->findJsonLdImage($item);
            if (is_string($found) && trim($found) !== '') {
                return $found;
            }
        }

        return null;
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

    private function cleanTitle(string $title, ?string $sourceName): string
    {
        $title = trim(html_entity_decode(strip_tags($title), ENT_QUOTES | ENT_HTML5));
        $title = preg_replace('/\s+/u', ' ', $title) ?? $title;

        if ($sourceName !== null && str_ends_with($title, ' - ' . $sourceName)) {
            $title = Str::beforeLast($title, ' - ' . $sourceName);
        }

        return trim($title);
    }

    private function absoluteUrl(string $baseUrl, string $url): string
    {
        $url = html_entity_decode(trim($url), ENT_QUOTES | ENT_HTML5);

        if ($url === '') {
            return '';
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        if (str_starts_with($url, '//')) {
            return 'https:' . $url;
        }

        $scheme = parse_url($baseUrl, PHP_URL_SCHEME) ?: 'https';
        $host = parse_url($baseUrl, PHP_URL_HOST) ?: '';
        if ($host === '') {
            return $url;
        }

        if (str_starts_with($url, '/')) {
            return $scheme . '://' . $host . $url;
        }

        $path = parse_url($baseUrl, PHP_URL_PATH) ?: '/';
        $dir = rtrim(str_replace('\\', '/', dirname($path)), '/');

        return $scheme . '://' . $host . ($dir === '' ? '' : $dir) . '/' . $url;
    }
}
