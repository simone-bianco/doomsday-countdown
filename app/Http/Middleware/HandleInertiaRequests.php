<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Backoffice\Doomsday\BackofficeDashboardService;
use Illuminate\Http\Request;
use Inertia\Middleware;

final class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    /**
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user()?->only('id', 'name', 'email'),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'app' => [
                'name' => 'Doomsday Countdown',
                'backoffice_path' => '/' . config('ai-starter.backoffice_path'),
                'backoffice_counts' => fn (): ?array => $this->backofficeCounts($request),
            ],
        ]);
    }

    /** @return array<string, int>|null */
    private function backofficeCounts(Request $request): ?array
    {
        if ($request->user() === null) {
            return null;
        }

        $backofficePath = trim((string) config('ai-starter.backoffice_path'), '/');
        if ($backofficePath === '' || (! $request->is($backofficePath) && ! $request->is($backofficePath . '/*'))) {
            return null;
        }

        return app(BackofficeDashboardService::class)->counts();
    }
}
