<?php

declare(strict_types=1);

namespace App\Services\Doomsday\Locale;

use Illuminate\Http\Request;

final class TrustedCountryLocaleAdapter
{
    /** @var array<string, string> */
    private const COUNTRY_LOCALES = [
        'AT' => 'de',
        'DE' => 'de',
        'ES' => 'es',
        'FR' => 'fr',
        'IT' => 'it',
        'NL' => 'nl',
        'PL' => 'pl',
        'SE' => 'sv',
        'SM' => 'it',
        'VA' => 'it',
    ];

    /** @param array<int, string> $supportedLocales */
    public function resolve(Request $request, array $supportedLocales): ?string
    {
        if (! (bool) config('doomsday.locale.trusted_country.enabled', false)) {
            return null;
        }

        $attribute = (string) config('doomsday.locale.trusted_country.attribute', 'trusted_country_code');
        $country = strtoupper(trim((string) $request->attributes->get($attribute, '')));
        $locale = self::COUNTRY_LOCALES[$country] ?? null;

        return $locale !== null && in_array($locale, $supportedLocales, true) ? $locale : null;
    }
}
