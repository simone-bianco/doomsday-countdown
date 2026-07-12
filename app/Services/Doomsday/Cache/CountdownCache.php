<?php

declare(strict_types=1);

namespace App\Services\Doomsday\Cache;

use App\Models\Countdown;
use App\Services\Doomsday\CountdownPublicDataService;
use App\Services\Doomsday\HomeSidebarDataService;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Closure;
use Illuminate\Support\Facades\Cache;

final class CountdownCache
{
    private const ROLLOVER_ENVELOPE = '__doomsday_rollover_envelope';

    private const ROLLOVER_PAYLOAD = '__doomsday_rollover_payload';

    private const ROLLOVER_BOUNDARY = '__doomsday_rollover_boundary';

    private const NULL_SENTINEL = '__doomsday_null';

    public function __construct(
        private readonly CountdownPublicDataService $service,
        private readonly HomeSidebarDataService $homeSidebarDataService,
    ) {}

    /** @return array<string, mixed> */
    public function page(string $locale, ?string $selectedSlug, string $currentPath): array
    {
        $locale = $this->service->normalizeLocale($locale);
        $indexPayload = $this->rememberRolloverAware(
            DoomsdayCacheKeys::index($locale),
            fn (): array => $this->service->indexPayload($locale),
            fn (array $payload): array => $this->indexTimerTargets($payload),
            fn (CarbonImmutable $at): ?CarbonImmutable => $this->nearestBoundary([
                $this->nextUtcWeekBoundary($at),
                $this->homeSidebarDataService->earliestFutureVisiblePublication($locale, $at),
            ]),
            fn (?array $payload): bool => $this->isCompleteIndexPayload($payload),
        ) ?? [];

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
        return $this->rememberRolloverAware(
            DoomsdayCacheKeys::overview($slug, $this->service->normalizeLocale($locale)),
            fn (): ?array => $this->service->overview($slug, $locale),
            fn (array $payload): array => $this->overviewTimerTargets($payload),
        );
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

    /**
     * @param  Closure(): array<string, mixed>|null  $callback
     * @param  Closure(array<string, mixed>): array<int, mixed>  $targetResolver
     * @param  Closure(CarbonImmutable): CarbonImmutable|null  $additionalBoundaryResolver
     * @param  Closure(array<string, mixed>|null): bool|null  $payloadValidator
     * @return array<string, mixed>|null
     */
    private function rememberRolloverAware(
        string $key,
        Closure $callback,
        Closure $targetResolver,
        ?Closure $additionalBoundaryResolver = null,
        ?Closure $payloadValidator = null,
    ): ?array {
        if (! $this->enabled()) {
            return $callback();
        }

        $at = CarbonImmutable::now('UTC');
        $cached = Cache::get($key);

        if (is_array($cached) && $this->isRolloverEnvelope($cached)) {
            if ($this->isRolloverEnvelopeValid($cached, $at)) {
                $payload = $this->unwrapRolloverEnvelope($cached);
                if ($payloadValidator === null || $payloadValidator($payload)) {
                    return $payload;
                }
            }

            Cache::forget($key);
        } elseif ($cached !== null) {
            Cache::forget($key);
        }

        $payload = $callback();
        $timerBoundary = is_array($payload)
            ? $this->rolloverBoundary($targetResolver($payload), $at)
            : null;
        $additionalBoundary = $additionalBoundaryResolver?->__invoke($at);
        $boundary = $this->nearestBoundary([$timerBoundary, $additionalBoundary]);
        $storedPayload = $payload ?? [self::NULL_SENTINEL => true];

        Cache::put($key, [
            self::ROLLOVER_ENVELOPE => true,
            self::ROLLOVER_PAYLOAD => $storedPayload,
            self::ROLLOVER_BOUNDARY => $boundary?->toIso8601String(),
        ], $this->expiresAt($at, $boundary));

        return $payload;
    }

    /** @param array<string, mixed> $value */
    private function isRolloverEnvelope(array $value): bool
    {
        return ($value[self::ROLLOVER_ENVELOPE] ?? false) === true
            && array_key_exists(self::ROLLOVER_PAYLOAD, $value)
            && array_key_exists(self::ROLLOVER_BOUNDARY, $value);
    }

    /** @param array<string, mixed> $envelope */
    private function isRolloverEnvelopeValid(array $envelope, CarbonImmutable $at): bool
    {
        $boundary = $envelope[self::ROLLOVER_BOUNDARY] ?? null;

        if ($boundary === null) {
            return true;
        }

        if (! is_string($boundary) || trim($boundary) === '') {
            return false;
        }

        try {
            return $at->lessThan(CarbonImmutable::parse($boundary)->utc());
        } catch (\Throwable) {
            return false;
        }
    }

    /** @param array<string, mixed> $envelope @return array<string, mixed>|null */
    private function unwrapRolloverEnvelope(array $envelope): ?array
    {
        $payload = $envelope[self::ROLLOVER_PAYLOAD] ?? null;

        if (! is_array($payload) || ($payload[self::NULL_SENTINEL] ?? false) === true) {
            return null;
        }

        return $payload;
    }

    /** @param array<string, mixed>|null $payload */
    private function isCompleteIndexPayload(?array $payload): bool
    {
        $sidebar = $payload['sidebar'] ?? null;

        return is_array($sidebar)
            && is_array($sidebar['latest_news'] ?? null)
            && is_array($sidebar['signal_activity'] ?? null);
    }

    /** @param array<string, mixed> $payload @return array<int, mixed> */
    private function indexTimerTargets(array $payload): array
    {
        $targets = [];

        foreach ($payload['countdowns'] ?? [] as $countdown) {
            if (! is_array($countdown)) {
                continue;
            }

            $targets[] = is_array($countdown['timer'] ?? null)
                ? ($countdown['timer']['target_date'] ?? null)
                : null;
        }

        return $targets;
    }

    /** @param array<string, mixed> $payload @return array<int, mixed> */
    private function overviewTimerTargets(array $payload): array
    {
        return [
            is_array($payload['timer'] ?? null)
                ? ($payload['timer']['target_date'] ?? null)
                : null,
        ];
    }

    /** @param array<int, mixed> $targets */
    private function rolloverBoundary(array $targets, CarbonImmutable $at): ?CarbonImmutable
    {
        $nearest = null;

        foreach ($targets as $value) {
            if (! is_string($value) || trim($value) === '') {
                continue;
            }

            try {
                $target = CarbonImmutable::parse($value)->utc();
            } catch (\Throwable) {
                continue;
            }

            if ($target->lessThan($at)) {
                continue;
            }

            if (! $nearest instanceof CarbonImmutable || $target->lessThan($nearest)) {
                $nearest = $target;
            }
        }

        return $nearest?->addSecond();
    }

    /** @param array<int, CarbonImmutable|null> $boundaries */
    private function nearestBoundary(array $boundaries): ?CarbonImmutable
    {
        return collect($boundaries)
            ->filter(fn (?CarbonImmutable $boundary): bool => $boundary instanceof CarbonImmutable)
            ->sortBy(fn (CarbonImmutable $boundary): int => $boundary->getTimestamp())
            ->first();
    }

    private function nextUtcWeekBoundary(CarbonImmutable $at): CarbonImmutable
    {
        return $at->utc()
            ->startOfWeek(CarbonInterface::MONDAY)
            ->startOfDay()
            ->addWeek();
    }

    private function expiresAt(CarbonImmutable $at, ?CarbonImmutable $boundary): CarbonImmutable
    {
        $configuredExpiry = $at->addSeconds($this->ttl());

        return $boundary instanceof CarbonImmutable && $boundary->lessThan($configuredExpiry)
            ? $boundary
            : $configuredExpiry;
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

        $sentinel = [self::NULL_SENTINEL => true];
        $value = Cache::remember($key, $this->ttl(), fn (): array => $callback() ?? $sentinel);

        return ($value[self::NULL_SENTINEL] ?? false) === true ? null : $value;
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
