<?php

declare(strict_types=1);

namespace App\Services\Doomsday\NewsUpdater;

use App\Support\ContentSourceAgentRunContext;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

final class YouTubeOEmbedService
{
    /** @return array{title: ?string, author_name: ?string, thumbnail_url: ?string, provider_name: ?string} */
    public function metadata(string $watchUrl): array
    {
        $startedAt = microtime(true);
        Log::channel('news_retrieval')->debug('youtube_oembed.fetch.started', ContentSourceAgentRunContext::context(['watch_url' => $watchUrl]));

        try {
            $response = Http::timeout(8)->retry(1, 250)->acceptJson()->get('https://www.youtube.com/oembed', [
                'url' => $watchUrl,
                'format' => 'json',
            ]);
        } catch (Throwable $exception) {
            Log::channel('news_retrieval')->warning('youtube_oembed.fetch.failed', ContentSourceAgentRunContext::context([
                'watch_url' => $watchUrl,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
                'elapsed_ms' => round((microtime(true) - $startedAt) * 1000, 2),
            ]));

            return $this->empty();
        }

        if (! $response->successful()) {
            Log::channel('news_retrieval')->warning('youtube_oembed.fetch.unsuccessful', ContentSourceAgentRunContext::context([
                'watch_url' => $watchUrl,
                'status' => $response->status(),
                'elapsed_ms' => round((microtime(true) - $startedAt) * 1000, 2),
            ]));

            return $this->empty();
        }

        $json = $response->json();
        if (! is_array($json)) {
            Log::channel('news_retrieval')->warning('youtube_oembed.invalid_json', ContentSourceAgentRunContext::context([
                'watch_url' => $watchUrl,
                'elapsed_ms' => round((microtime(true) - $startedAt) * 1000, 2),
            ]));

            return $this->empty();
        }

        Log::channel('news_retrieval')->debug('youtube_oembed.fetch.completed', ContentSourceAgentRunContext::context([
            'watch_url' => $watchUrl,
            'elapsed_ms' => round((microtime(true) - $startedAt) * 1000, 2),
            'has_title' => isset($json['title']),
            'has_thumbnail' => isset($json['thumbnail_url']),
        ]));

        return [
            'title' => isset($json['title']) ? (string) $json['title'] : null,
            'author_name' => isset($json['author_name']) ? (string) $json['author_name'] : null,
            'thumbnail_url' => isset($json['thumbnail_url']) ? (string) $json['thumbnail_url'] : null,
            'provider_name' => isset($json['provider_name']) ? (string) $json['provider_name'] : null,
        ];
    }

    /** @return array{title: null, author_name: null, thumbnail_url: null, provider_name: null} */
    private function empty(): array
    {
        return [
            'title' => null,
            'author_name' => null,
            'thumbnail_url' => null,
            'provider_name' => null,
        ];
    }
}
