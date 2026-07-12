<?php

declare(strict_types=1);

namespace App\Services\Doomsday\Seo;

use App\Data\Doomsday\SeoAlternateData;
use App\Services\Doomsday\CountdownPublicDataService;
use LogicException;

final class PublicUrlBuilder
{
    public function __construct(private readonly CountdownPublicDataService $publicDataService) {}

    public function origin(): string
    {
        $configured = trim((string) config('app.url'));
        $parts = parse_url($configured);

        if (! is_array($parts) || ! isset($parts['scheme'], $parts['host'])) {
            throw new LogicException('APP_URL must be an absolute application origin.');
        }

        $scheme = strtolower((string) $parts['scheme']);
        if (! in_array($scheme, ['http', 'https'], true)) {
            throw new LogicException('APP_URL must use HTTP or HTTPS.');
        }

        if (app()->environment('production') && $scheme !== 'https') {
            throw new LogicException('Production APP_URL must use HTTPS.');
        }

        $path = (string) ($parts['path'] ?? '');
        if ($path !== '' && $path !== '/') {
            throw new LogicException('APP_URL must not contain an application path.');
        }

        if (isset($parts['query']) || isset($parts['fragment']) || isset($parts['user']) || isset($parts['pass'])) {
            throw new LogicException('APP_URL must contain only the public origin.');
        }

        $port = isset($parts['port']) ? ':'.(int) $parts['port'] : '';

        return $scheme.'://'.$parts['host'].$port;
    }

    /** @return array<int, string> */
    public function supportedLocales(): array
    {
        return $this->publicDataService->supportedLocales();
    }

    public function localeUrl(string $path, string $locale): string
    {
        $locale = $this->publicDataService->normalizeLocale($locale);

        return $this->origin().$this->normalizePath($path).'?'.http_build_query(
            ['lang' => $locale],
            '',
            '&',
            PHP_QUERY_RFC3986,
        );
    }

    public function neutralUrl(string $path): string
    {
        return $this->origin().$this->normalizePath($path);
    }

    public function assetUrl(string $path): string
    {
        return $this->origin().'/'.ltrim($path, '/');
    }

    public function sitemapUrl(): string
    {
        return $this->origin().'/sitemap.xml';
    }

    /** @return array<int, SeoAlternateData> */
    public function alternates(string $path): array
    {
        return array_map(
            fn (string $locale): SeoAlternateData => new SeoAlternateData($locale, $this->localeUrl($path, $locale)),
            $this->supportedLocales(),
        );
    }

    private function normalizePath(string $path): string
    {
        $normalized = '/'.ltrim($path, '/');

        return $normalized === '//' ? '/' : $normalized;
    }
}
