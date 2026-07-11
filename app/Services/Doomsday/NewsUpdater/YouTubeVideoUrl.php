<?php

declare(strict_types=1);

namespace App\Services\Doomsday\NewsUpdater;

final class YouTubeVideoUrl
{
    /** @var list<string> */
    private const YOUTUBE_HOSTS = [
        'youtube.com',
        'www.youtube.com',
        'm.youtube.com',
        'music.youtube.com',
    ];

    /** @var list<string> */
    private const SHORT_HOSTS = [
        'youtu.be',
    ];

    public function videoIdFromUrl(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        $parts = parse_url($url);
        if (! is_array($parts)) {
            return null;
        }

        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        $host = strtolower((string) ($parts['host'] ?? ''));
        $port = $parts['port'] ?? null;

        if ($scheme !== 'https' || $host === '' || $port !== null && $port !== 443 || isset($parts['user']) || isset($parts['pass'])) {
            return null;
        }

        $path = (string) ($parts['path'] ?? '');

        if (in_array($host, self::SHORT_HOSTS, true)) {
            $id = trim($path, '/');

            return $this->validVideoId($id) ? $id : null;
        }

        if (! in_array($host, self::YOUTUBE_HOSTS, true)) {
            return null;
        }

        if (rtrim($path, '/') === '/watch') {
            parse_str((string) ($parts['query'] ?? ''), $query);

            if (isset($query['v']) && is_string($query['v']) && $this->validVideoId($query['v'])) {
                return $query['v'];
            }

            return null;
        }

        if (preg_match('#^/(?:embed|shorts)/([A-Za-z0-9_-]{6,})/?$#', $path, $matches) === 1) {
            return $this->validVideoId($matches[1]) ? $matches[1] : null;
        }

        return null;
    }

    public function watchUrl(string $videoId): string
    {
        return 'https://www.youtube.com/watch?v='.$videoId;
    }

    public function embedUrl(string $videoId): string
    {
        return 'https://www.youtube.com/embed/'.$videoId;
    }

    public function thumbnailUrl(string $videoId): string
    {
        return 'https://i.ytimg.com/vi/'.$videoId.'/hqdefault.jpg';
    }

    private function validVideoId(string $videoId): bool
    {
        return preg_match('/^[A-Za-z0-9_-]{6,}$/', $videoId) === 1;
    }
}
