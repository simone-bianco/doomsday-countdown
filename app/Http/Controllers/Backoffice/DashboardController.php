<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Services\Backoffice\Doomsday\BackofficeDashboardService;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController extends Controller
{
    public function __construct(private readonly BackofficeDashboardService $dashboard) {}

    public function __invoke(): Response
    {
        Gate::authorize('access-backoffice');

        return Inertia::render('Backoffice/Index', $this->dashboard->dashboard());
    }
}
