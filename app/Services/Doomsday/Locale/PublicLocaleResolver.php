<?php

declare(strict_types=1);

namespace App\Services\Doomsday\Locale;

use App\Services\Doomsday\CountdownPublicDataService;
use Illuminate\Http\Request;

final class PublicLocaleResolver
{
    public const RESOLUTION_ATTRIBUTE = 'public_locale_resolution';

    public const LOCALE_ATTRIBUTE = 'public_locale';

    public function __construct(
        private readonly CountdownPublicDataService $publicDataService,
        private readonly PublicLocaleCookie $localeCookie,
        private readonly TrustedCountryLocaleAdapter $countryAdapter,
    ) {}

    public function resolve(Request $request): PublicLocaleResolution
    {
        $existing = $request->attributes->get(self::RESOLUTION_ATTRIBUTE);
        if ($existing instanceof PublicLocaleResolution) {
            return $existing;
        }

        $supported = $this->publicDataService->supportedLocales();
        $resolution = $this->queryLocale($request, $supported)
            ?? $this->cookieLocale($request, $supported)
            ?? $this->browserLocale($request, $supported)
            ?? $this->countryLocale($request, $supported)
            ?? new PublicLocaleResolution(in_array('en', $supported, true) ? 'en' : $supported[0], 'default');

        $request->attributes->set(self::RESOLUTION_ATTRIBUTE, $resolution);
        $request->attributes->set(self::LOCALE_ATTRIBUTE, $resolution->locale);

        return $resolution;
    }

    public function locale(Request $request): string
    {
        return $this->resolve($request)->locale;
    }

    public function apply(Request $request, PublicLocaleResolution $resolution): void
    {
        app()->setLocale($resolution->locale);
        $request->setRequestLocale($resolution->locale);
        $request->attributes->set(self::RESOLUTION_ATTRIBUTE, $resolution);
        $request->attributes->set(self::LOCALE_ATTRIBUTE, $resolution->locale);
    }

    /** @param array<int, string> $supported */
    private function queryLocale(Request $request, array $supported): ?PublicLocaleResolution
    {
        $locale = $this->candidate($request->query('lang'), $supported);

        return $locale !== null ? new PublicLocaleResolution($locale, 'query') : null;
    }

    /** @param array<int, string> $supported */
    private function cookieLocale(Request $request, array $supported): ?PublicLocaleResolution
    {
        $locale = $this->localeCookie->read($request, $supported);

        return $locale !== null ? new PublicLocaleResolution($locale, 'cookie') : null;
    }

    /** @param array<int, string> $supported */
    private function browserLocale(Request $request, array $supported): ?PublicLocaleResolution
    {
        if (! $request->headers->has('Accept-Language')) {
            return null;
        }

        foreach ($request->getLanguages() as $language) {
            $normalized = strtolower(str_replace('_', '-', $language));
            $exact = $this->candidate($normalized, $supported);
            if ($exact !== null) {
                return new PublicLocaleResolution($exact, 'accept-language');
            }

            $base = explode('-', $normalized, 2)[0];
            $fallback = $this->candidate($base, $supported);
            if ($fallback !== null) {
                return new PublicLocaleResolution($fallback, 'accept-language');
            }
        }

        return null;
    }

    /** @param array<int, string> $supported */
    private function countryLocale(Request $request, array $supported): ?PublicLocaleResolution
    {
        $locale = $this->countryAdapter->resolve($request, $supported);

        return $locale !== null ? new PublicLocaleResolution($locale, 'trusted-country') : null;
    }

    /** @param array<int, string> $supported */
    private function candidate(mixed $value, array $supported): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $locale = strtolower(trim($value));

        return in_array($locale, $supported, true) ? $locale : null;
    }
}
