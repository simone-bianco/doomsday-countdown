<?php

declare(strict_types=1);

namespace App\Services\Doomsday\Cache;

final class DoomsdayCacheKeys
{
    private const PREFIX = 'doomsday:v1:';

    /** @return array<int, string> */
    public static function allLocales(): array
    {
        return ['en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl'];
    }

    public static function index(string $locale): string
    {
        return self::PREFIX . 'index:' . $locale;
    }

    public static function about(string $locale): string
    {
        return self::PREFIX . 'about:' . $locale;
    }

    public static function overview(string $slug, string $locale): string
    {
        return self::PREFIX . 'countdown:' . $slug . ':overview:' . $locale;
    }

    public static function forecasts(string $slug, string $locale): string
    {
        return self::PREFIX . 'countdown:' . $slug . ':forecasts:' . $locale;
    }

    public static function statistics(string $slug, string $locale): string
    {
        return self::PREFIX . 'countdown:' . $slug . ':statistics:' . $locale;
    }

    public static function news(string $slug, string $locale): string
    {
        return self::PREFIX . 'countdown:' . $slug . ':news:' . $locale;
    }

    public static function initiatives(string $slug, string $locale): string
    {
        return self::PREFIX . 'countdown:' . $slug . ':initiatives:' . $locale;
    }

    /** @return array<int, string> */
    public static function indexKeys(): array
    {
        return array_map(fn (string $locale): string => self::index($locale), self::allLocales());
    }

    /** @return array<int, string> */
    public static function aboutKeys(): array
    {
        return array_map(fn (string $locale): string => self::about($locale), self::allLocales());
    }

    /** @return array<int, string> */
    public static function allSectionsForSlug(string $slug): array
    {
        $keys = [];
        foreach (self::allLocales() as $locale) {
            $keys[] = self::overview($slug, $locale);
            $keys[] = self::forecasts($slug, $locale);
            $keys[] = self::statistics($slug, $locale);
            $keys[] = self::news($slug, $locale);
            $keys[] = self::initiatives($slug, $locale);
        }

        return $keys;
    }
}
