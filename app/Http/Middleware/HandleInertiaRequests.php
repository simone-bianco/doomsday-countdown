<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Backoffice\Doomsday\BackofficeDashboardService;
use App\Services\Doomsday\Locale\PublicLocaleResolver;
use App\Services\Doomsday\Seo\PublicSeoService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Inertia\Middleware;

final class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function __construct(
        private readonly PublicLocaleResolver $localeResolver,
        private readonly PublicSeoService $seoService,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $resolution = $this->localeResolver->resolve($request);
        $this->localeResolver->apply($request, $resolution);
        $renderedAt = CarbonImmutable::now('UTC')->toIso8601String();

        return array_merge(parent::share($request), [
            'locale' => $resolution->locale,
            'rendered_at' => $renderedAt,
            'seo' => fn (): array => $this->seoService->forRequest($request, $resolution->locale)->toArray(),
            'auth' => [
                'user' => $request->user()?->only('id', 'name', 'email'),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'app' => [
                'name' => 'Doomsday Clock',
                'backoffice_path' => '/'.config('ai-starter.backoffice_path'),
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
        if ($backofficePath === '' || (! $request->is($backofficePath) && ! $request->is($backofficePath.'/*'))) {
            return null;
        }

        return app(BackofficeDashboardService::class)->counts();
    }
}
