<?php

declare(strict_types=1);

namespace App\Services\Doomsday\NewsUpdater;

use App\Support\ContentSourceAgentRunContext;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

final class NewsArticlePreviewImageService
{
    /** @return array{http_status: int|null, image_source: string|null, image_url: string|null, error: string|null} */
    public function preview(string $url): array
    {
        try {
            $response = Http::timeout(10)
                ->retry(1, 250)
                ->withHeaders([
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'User-Agent' => 'Mozilla/5.0 DoomsdayCountdownNewsArticlePreview/0.1',
                ])
                ->get($url);
        } catch (Throwable $exception) {
            Log::channel('news_retrieval')->warning('article_preview.fetch.failed', ContentSourceAgentRunContext::context([
                'url' => $url,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]));

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
                    'image_url' => $this->absoluteUrl($finalUrl !== '' ? $finalUrl : $url, $imageUrl),
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
                '/<meta\s+[^>]*(?:property|name)=["\']'.$quoted.'["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i',
                '/<meta\s+[^>]*content=["\']([^"\']+)["\'][^>]*(?:property|name)=["\']'.$quoted.'["\'][^>]*>/i',
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
            return 'https:'.$url;
        }

        $scheme = parse_url($baseUrl, PHP_URL_SCHEME) ?: 'https';
        $host = parse_url($baseUrl, PHP_URL_HOST) ?: '';
        if ($host === '') {
            return $url;
        }

        if (str_starts_with($url, '/')) {
            return $scheme.'://'.$host.$url;
        }

        $path = parse_url($baseUrl, PHP_URL_PATH) ?: '/';
        $dir = rtrim(str_replace('\\', '/', dirname($path)), '/');

        return $scheme.'://'.$host.($dir === '' ? '' : $dir).'/'.$url;
    }
}
