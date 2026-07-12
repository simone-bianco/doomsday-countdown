<?php

declare(strict_types=1);

namespace App\Services\Doomsday\Locale;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

final class PublicLocaleCookie
{
    public const NAME = 'doomsday_locale';

    public const MINUTES = 525600;

    /** @param array<int, string> $supportedLocales */
    public function read(Request $request, array $supportedLocales): ?string
    {
        $value = $request->cookie(self::NAME);
        if (! is_string($value)) {
            return null;
        }

        $locale = strtolower(trim($value));

        return in_array($locale, $supportedLocales, true) ? $locale : null;
    }

    public function make(Request $request, string $locale): Cookie
    {
        return Cookie::create(
            name: self::NAME,
            value: $locale,
            expire: CarbonImmutable::now('UTC')->addMinutes(self::MINUTES),
            path: '/',
            domain: null,
            secure: $request->isSecure() || app()->environment('production'),
            httpOnly: true,
            raw: false,
            sameSite: Cookie::SAMESITE_LAX,
        );
    }
}
