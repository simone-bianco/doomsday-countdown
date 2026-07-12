<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Doomsday\Cache\CountdownCache;
use App\Services\Doomsday\Locale\PublicLocaleResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CountdownDataController extends Controller
{
    public function overview(string $slug, Request $request, PublicLocaleResolver $localeResolver, CountdownCache $cache): JsonResponse
    {
        return $this->payload($cache->overview($slug, $localeResolver->locale($request)));
    }

    public function forecasts(string $slug, Request $request, PublicLocaleResolver $localeResolver, CountdownCache $cache): JsonResponse
    {
        return $this->payload($cache->forecasts($slug, $localeResolver->locale($request)));
    }

    public function statistics(string $slug, Request $request, PublicLocaleResolver $localeResolver, CountdownCache $cache): JsonResponse
    {
        return $this->payload($cache->statistics($slug, $localeResolver->locale($request)));
    }

    public function news(string $slug, Request $request, PublicLocaleResolver $localeResolver, CountdownCache $cache): JsonResponse
    {
        return $this->payload($cache->news($slug, $localeResolver->locale($request)));
    }

    public function initiatives(string $slug, Request $request, PublicLocaleResolver $localeResolver, CountdownCache $cache): JsonResponse
    {
        return $this->payload($cache->initiatives($slug, $localeResolver->locale($request)));
    }

    /** @param array<string, mixed>|null $payload */
    private function payload(?array $payload): JsonResponse
    {
        if ($payload === null) {
            return response()->json(['message' => 'Countdown data not found.'], 404);
        }

        return response()->json(['data' => $payload]);
    }
}
