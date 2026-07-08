<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Doomsday\Cache\CountdownCache;
use App\Services\Doomsday\CountdownPublicDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CountdownDataController extends Controller
{
    public function overview(string $slug, Request $request, CountdownPublicDataService $service, CountdownCache $cache): JsonResponse
    {
        return $this->payload($cache->overview($slug, $this->locale($request, $service)));
    }

    public function forecasts(string $slug, Request $request, CountdownPublicDataService $service, CountdownCache $cache): JsonResponse
    {
        return $this->payload($cache->forecasts($slug, $this->locale($request, $service)));
    }

    public function statistics(string $slug, Request $request, CountdownPublicDataService $service, CountdownCache $cache): JsonResponse
    {
        return $this->payload($cache->statistics($slug, $this->locale($request, $service)));
    }

    public function news(string $slug, Request $request, CountdownPublicDataService $service, CountdownCache $cache): JsonResponse
    {
        return $this->payload($cache->news($slug, $this->locale($request, $service)));
    }

    public function initiatives(string $slug, Request $request, CountdownPublicDataService $service, CountdownCache $cache): JsonResponse
    {
        return $this->payload($cache->initiatives($slug, $this->locale($request, $service)));
    }

    private function locale(Request $request, CountdownPublicDataService $service): string
    {
        return $service->normalizeLocale($request->query('lang'));
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
