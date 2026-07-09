<?php

declare(strict_types=1);

namespace App\Services\Doomsday\NewsUpdater;

final class NewsUrlNormalizer
{
    /** @var array<int, string> */
    private const TRACKING_PARAMETERS = [
        'fbclid',
        'gclid',
        'mc_cid',
        'mc_eid',
        'msclkid',
        'ocid',
    ];

    public function canonicalize(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        $url = html_entity_decode($url, ENT_QUOTES | ENT_HTML5);
        $url = preg_replace('/[[:cntrl:]]/', '', $url) ?? $url;

        if (str_starts_with($url, '//')) {
            $url = 'https:' . $url;
        }

        $parts = parse_url($url);
        if (! is_array($parts)) {
            return null;
        }

        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        if (! in_array($scheme, ['http', 'https'], true)) {
            return null;
        }

        $host = strtolower((string) ($parts['host'] ?? ''));
        if ($host === '') {
            return null;
        }

        $path = (string) ($parts['path'] ?? '/');
        $path = preg_replace('#/+#', '/', $path) ?: '/';
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        $query = $this->canonicalQuery((string) ($parts['query'] ?? ''));
        $port = isset($parts['port']) && ! in_array([$scheme, (int) $parts['port']], [['http', 80], ['https', 443]], true)
            ? ':' . (int) $parts['port']
            : '';

        return $scheme . '://' . $host . $port . $path . ($query !== '' ? '?' . $query : '');
    }

    public function hash(?string $url): ?string
    {
        $canonical = $this->canonicalize($url);

        return $canonical !== null ? hash('sha256', $canonical) : null;
    }

    private function canonicalQuery(string $query): string
    {
        if ($query === '') {
            return '';
        }

        parse_str($query, $params);
        $filtered = [];
        foreach ($params as $key => $value) {
            $key = (string) $key;
            if (str_starts_with($key, 'utm_') || in_array($key, self::TRACKING_PARAMETERS, true)) {
                continue;
            }

            $filtered[$key] = $value;
        }

        ksort($filtered);

        return http_build_query($filtered, '', '&', PHP_QUERY_RFC3986);
    }
}
