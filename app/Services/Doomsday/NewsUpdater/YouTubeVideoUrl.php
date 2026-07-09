<?php

declare(strict_types=1);

namespace App\Services\Doomsday\NewsUpdater;

final class YouTubeVideoUrl
{
    public function videoIdFromUrl(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $path = (string) parse_url($url, PHP_URL_PATH);

        if (str_contains($host, 'youtu.be')) {
            $id = trim($path, '/');

            return $this->validVideoId($id) ? $id : null;
        }

        if (! str_contains($host, 'youtube.com')) {
            return null;
        }

        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
        if (isset($query['v']) && is_string($query['v']) && $this->validVideoId($query['v'])) {
            return $query['v'];
        }

        if (preg_match('#/(?:embed|shorts)/([A-Za-z0-9_-]{6,})#', $path, $matches)) {
            return $this->validVideoId($matches[1]) ? $matches[1] : null;
        }

        return null;
    }

    public function watchUrl(string $videoId): string
    {
        return 'https://www.youtube.com/watch?v=' . $videoId;
    }

    public function embedUrl(string $videoId): string
    {
        return 'https://www.youtube.com/embed/' . $videoId;
    }

    public function thumbnailUrl(string $videoId): string
    {
        return 'https://i.ytimg.com/vi/' . $videoId . '/hqdefault.jpg';
    }

    private function validVideoId(string $videoId): bool
    {
        return preg_match('/^[A-Za-z0-9_-]{6,}$/', $videoId) === 1;
    }
}
