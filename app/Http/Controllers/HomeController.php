<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

final class HomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Home', [
            'backofficePath' => '/' . config('ai-starter.backoffice_path'),
            'agentEndpoint' => '/agent/demo',
            'appName' => config('app.name'),
        ]);
    }
}
