<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Doomsday\CountdownPublicDataService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CountdownShowPageController extends Controller
{
    public function __invoke(string $slug, Request $request, CountdownPublicDataService $service): Response
    {
        $countdown = $service->publicCountdownBySlug($slug);
        if ($countdown === null) {
            throw new NotFoundHttpException();
        }

        $locale = $service->normalizeLocale($request->query('lang'));

        return Inertia::render('Doomsday/Home', [
            'page' => $service->index($locale, $slug, $request->path())->toArray(),
        ]);
    }
}
