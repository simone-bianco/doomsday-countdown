<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Doomsday\Seo\PublicRobotsService;
use Illuminate\Http\Response;

final class RobotsController extends Controller
{
    public function __invoke(PublicRobotsService $robots): Response
    {
        return response($robots->render(), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
