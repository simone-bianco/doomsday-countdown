<?php

declare(strict_types=1);

namespace App\Services\Doomsday\NewsUpdater;

use Illuminate\Support\Facades\Http;
use Throwable;

final class YouTubeOEmbedService
{
    /** @return array{title: ?string, author_name: ?string, thumbnail_url: ?string, provider_name: ?string} */
    public function metadata(string $watchUrl): array
    {
        try {
            $response = Http::timeout(8)
                ->retry(1, 250)
                ->acceptJson()
                ->get('https://www.youtube.com/oembed', [
                    'url' => $watchUrl,
                    'format' => 'json',
                ]);
        } catch (Throwable) {
            return $this->empty();
        }

        if (! $response->successful()) {
            return $this->empty();
        }

        $json = $response->json();
        if (! is_array($json)) {
            return $this->empty();
        }

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
