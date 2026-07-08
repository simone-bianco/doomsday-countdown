<?php

declare(strict_types=1);

namespace App\Services\Doomsday\Cache;

use App\Models\Countdown;
use App\Services\Doomsday\CountdownPublicDataService;
use Closure;
use Illuminate\Support\Facades\Cache;

final class CountdownCache
{
    public function __construct(private readonly CountdownPublicDataService $service)
    {
    }

    /** @return array<string, mixed> */
    public function page(string $locale, ?string $selectedSlug, string $currentPath): array
    {
        $locale = $this->service->normalizeLocale($locale);
        $indexPayload = $this->remember(DoomsdayCacheKeys::index($locale), fn (): array => $this->service->indexPayload($locale));

        return $this->service->pageFromPayload($locale, $currentPath, $indexPayload, null);
    }

    /** @return array<string, mixed> */
    public function about(string $locale, string $currentPath): array
    {
        $locale = $this->service->normalizeLocale($locale);
        $key = DoomsdayCacheKeys::about($locale);
        $payload = $this->remember($key, fn (): array => $this->service->aboutPayload($locale));

        if (! $this->isCompleteAboutPayload($payload)) {
            Cache::forget($key);
            $payload = $this->service->aboutPayload($locale);
        }

        return $this->service->aboutFromPayload($locale, $currentPath, $payload);
    }

    /** @return array<string, mixed>|null */
    public function overview(string $slug, string $locale): ?array
    {
        return $this->rememberNullable(DoomsdayCacheKeys::overview($slug, $this->service->normalizeLocale($locale)), fn (): ?array => $this->service->overview($slug, $locale));
    }

    /** @return array<string, mixed>|null */
    public function forecasts(string $slug, string $locale): ?array
    {
        return $this->rememberNullable(DoomsdayCacheKeys::forecasts($slug, $this->service->normalizeLocale($locale)), fn (): ?array => $this->service->forecasts($slug, $locale));
    }

    /** @return array<string, mixed>|null */
    public function statistics(string $slug, string $locale): ?array
    {
        return $this->rememberNullable(DoomsdayCacheKeys::statistics($slug, $this->service->normalizeLocale($locale)), fn (): ?array => $this->service->statistics($slug, $locale));
    }

    /** @return array<string, mixed>|null */
    public function news(string $slug, string $locale): ?array
    {
        return $this->rememberNullable(DoomsdayCacheKeys::news($slug, $this->service->normalizeLocale($locale)), fn (): ?array => $this->service->newsSection($slug, $locale));
    }

    /** @return array<string, mixed>|null */
    public function initiatives(string $slug, string $locale): ?array
    {
        return $this->rememberNullable(DoomsdayCacheKeys::initiatives($slug, $this->service->normalizeLocale($locale)), fn (): ?array => $this->service->initiativesSection($slug, $locale));
    }

    public function forgetIndex(): void
    {
        $this->forgetMany(DoomsdayCacheKeys::indexKeys());
    }

    public function forgetAbout(): void
    {
        $this->forgetMany(DoomsdayCacheKeys::aboutKeys());
    }

    public function forgetCountdown(?Countdown $countdown): void
    {
        if (! $countdown instanceof Countdown || $countdown->slug === '') {
            return;
        }

        $this->forgetCountdownSlug($countdown->slug);
    }

    public function forgetCountdownSlug(string $slug): void
    {
        $this->forgetMany(DoomsdayCacheKeys::allSectionsForSlug($slug));
    }

    public function flushDoomsday(): void
    {
        $this->forgetIndex();
        $this->forgetAbout();
        Countdown::query()->pluck('slug')->each(fn (string $slug): bool => Cache::forget(DoomsdayCacheKeys::overview($slug, 'en')) || true);
        Countdown::query()->pluck('slug')->each(fn (string $slug): null => $this->forgetCountdownSlug($slug));
    }

    /** @param array<int, string> $keys */
    public function forgetMany(array $keys): void
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /** @param array<string, mixed> $payload */
    private function isCompleteAboutPayload(array $payload): bool
    {
        foreach (['title', 'subtitle', 'eyebrow', 'hero_badge', 'filter_watch_label', 'visual_label', 'pipeline_label', 'faq_title', 'faq_subtitle', 'closing_label', 'intro', 'stats', 'sections', 'timeline', 'faq', 'closing_title', 'closing_body'] as $key) {
            if (! array_key_exists($key, $payload)) {
                return false;
            }
        }

        return true;
    }

    /** @return array<string, mixed> */
    private function remember(string $key, Closure $callback): array
    {
        if (! $this->enabled()) {
            return $callback();
        }

        return Cache::remember($key, $this->ttl(), $callback);
    }

    /** @return array<string, mixed>|null */
    private function rememberNullable(string $key, Closure $callback): ?array
    {
        if (! $this->enabled()) {
            return $callback();
        }

        $sentinel = ['__doomsday_null' => true];
        $value = Cache::remember($key, $this->ttl(), fn (): array => $callback() ?? $sentinel);

        return ($value['__doomsday_null'] ?? false) === true ? null : $value;
    }

    private function enabled(): bool
    {
        return (bool) config('doomsday.cache.enabled', true);
    }

    private function ttl(): int
    {
        return max(1, (int) config('doomsday.cache.ttl', 86400));
    }
}
