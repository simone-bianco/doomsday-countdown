<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Doomsday\Cache\CountdownCache;
use App\Services\Doomsday\Locale\PublicLocaleResolver;
use App\Services\Doomsday\Seo\PublicSeoService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CountdownShowPageController extends Controller
{
    public function __invoke(
        string $slug,
        Request $request,
        PublicLocaleResolver $localeResolver,
        PublicSeoService $seoService,
        CountdownCache $cache,
    ): Response {
        if ($seoService->publishedCountdown($request, $slug) === null) {
            throw new NotFoundHttpException;
        }

        $locale = $localeResolver->locale($request);

        return Inertia::render('Doomsday/Home', [
            'page' => $cache->page($locale, null, $request->path()),
            'selected_countdown' => $cache->overview($slug, $locale),
            'forecast_section' => Inertia::optional(fn (): ?array => $cache->forecasts($slug, $locale)),
            'statistics_section' => Inertia::optional(fn (): ?array => $cache->statistics($slug, $locale)),
            'news_section' => Inertia::optional(fn (): ?array => $cache->news($slug, $locale)),
            'initiatives_section' => Inertia::optional(fn (): ?array => $cache->initiatives($slug, $locale)),
        ]);
    }
}
