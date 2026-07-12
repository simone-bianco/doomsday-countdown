<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Doomsday\Cache\CountdownCache;
use App\Services\Doomsday\Locale\PublicLocaleResolver;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class AboutPageController extends Controller
{
    public function __invoke(Request $request, PublicLocaleResolver $localeResolver, CountdownCache $cache): Response
    {
        $locale = $localeResolver->locale($request);

        return Inertia::render('Doomsday/About', [
            'page' => $cache->about($locale, $request->path()),
        ]);
    }
}
