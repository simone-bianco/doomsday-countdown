<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Doomsday\CountdownPublicDataService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class HomePageController extends Controller
{
    public function __invoke(Request $request, CountdownPublicDataService $service): Response
    {
        $locale = $service->normalizeLocale($request->query('lang'));

        return Inertia::render('Doomsday/Home', [
            'page' => $service->index($locale, null, $request->path())->toArray(),
        ]);
    }
}
