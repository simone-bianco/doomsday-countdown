<?php

declare(strict_types=1);

namespace App\Services\Doomsday;

use App\Data\Doomsday\AboutPageData;
use App\Data\Doomsday\CountdownForecastsData;
use App\Data\Doomsday\CountdownIndexData;
use App\Data\Doomsday\CountdownInitiativesSectionData;
use App\Data\Doomsday\CountdownNewsSectionData;
use App\Data\Doomsday\CountdownOverviewData;
use App\Data\Doomsday\CountdownStatisticsData;
use App\Data\Doomsday\CountdownTimerData;
use App\Data\Doomsday\InitiativeData;
use App\Data\Doomsday\LanguageOptionData;
use App\Data\Doomsday\NewsData;
use App\Data\Doomsday\ProjectionData;
use App\Data\Doomsday\VisualizationData;
use App\Enums\InitiativeLocale;
use App\Enums\NewsLocale;
use App\Enums\ProjectionType;
use App\Models\Countdown;
use App\Models\Initiative;
use App\Models\News;
use App\Models\Projection;
use App\Models\Visualization;
use App\Services\Doomsday\Copy\AboutPageCopy;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

final class CountdownPublicDataService
{
    public function __construct(private readonly AboutPageCopy $aboutPageCopy) {}

    /** @var array<string, array{label: string, native: string, flag: string}> */
    private const LANGUAGES = [
        'en' => ['label' => 'English', 'native' => 'English', 'flag' => '🇬🇧'],
        'it' => ['label' => 'Italiano', 'native' => 'Italiano', 'flag' => '🇮🇹'],
        'fr' => ['label' => 'Français', 'native' => 'Français', 'flag' => '🇫🇷'],
        'de' => ['label' => 'Deutsch', 'native' => 'Deutsch', 'flag' => '🇩🇪'],
        'es' => ['label' => 'Español', 'native' => 'Español', 'flag' => '🇪🇸'],
        'nl' => ['label' => 'Nederlands', 'native' => 'Nederlands', 'flag' => '🇳🇱'],
        'sv' => ['label' => 'Svenska', 'native' => 'Svenska', 'flag' => '🇸🇪'],
        'pl' => ['label' => 'Polski', 'native' => 'Polski', 'flag' => '🇵🇱'],
    ];

    /** @return array<int, string> */
    public function supportedLocales(): array
    {
        return array_keys(self::LANGUAGES);
    }

    public function normalizeLocale(?string $locale): string
    {
        return in_array($locale, $this->supportedLocales(), true) ? (string) $locale : 'en';
    }

    /** @return array<string, mixed> */
    public function indexPayload(string $locale): array
    {
        $locale = $this->normalizeLocale($locale);
        $countdowns = Countdown::query()
            ->published()
            ->with(['projections'])
            ->orderBy('sort_order')
            ->get();

        return [
            'app_name' => 'Doomsday Countdown',
            'current_locale' => $locale,
            'hero' => $this->hero($locale),
            'countdowns' => $countdowns
                ->map(fn (Countdown $countdown): array => $this->toIndexItem($countdown, $locale, false)->toArray())
                ->values()
                ->all(),
        ];
    }

    /** @param array<string, mixed> $indexPayload @param array<string, mixed>|null $overview */
    public function pageFromPayload(string $locale, string $currentPath, array $indexPayload, ?array $overview): array
    {
        $locale = $this->normalizeLocale($locale);
        $selectedSlug = is_array($overview) ? (string) ($overview['slug'] ?? '') : null;

        $countdowns = collect($indexPayload['countdowns'] ?? [])
            ->map(function (array $countdown) use ($selectedSlug): array {
                $countdown['is_selected'] = $selectedSlug !== null && $selectedSlug !== '' && $countdown['slug'] === $selectedSlug;

                return $countdown;
            })
            ->values()
            ->all();

        return [
            'app_name' => $indexPayload['app_name'] ?? 'Doomsday Countdown',
            'current_locale' => $locale,
            'languages' => $this->languageOptionArrays($locale, $currentPath),
            'hero' => $indexPayload['hero'] ?? $this->hero($locale),
            'countdowns' => $countdowns,
            'selected_countdown' => $overview,
        ];
    }

    /** @return array<string, mixed> */
    public function aboutPayload(string $locale): array
    {
        $locale = $this->normalizeLocale($locale);
        $copy = $this->aboutCopy($locale);

        return array_merge([
            'app_name' => 'Doomsday Countdown',
            'current_locale' => $locale,
        ], $copy);
    }

    /** @param array<string, mixed> $aboutPayload */
    public function aboutFromPayload(string $locale, string $currentPath, array $aboutPayload): array
    {
        $locale = $this->normalizeLocale($locale);

        return array_merge($aboutPayload, [
            'current_locale' => $locale,
            'languages' => $this->languageOptionArrays($locale, $currentPath),
        ]);
    }

    public function about(string $locale, string $currentPath): AboutPageData
    {
        $payload = $this->aboutFromPayload($locale, $currentPath, $this->aboutPayload($locale));

        return new AboutPageData(
            app_name: (string) $payload['app_name'],
            current_locale: (string) $payload['current_locale'],
            languages: $payload['languages'],
            title: (string) $payload['title'],
            subtitle: (string) $payload['subtitle'],
            eyebrow: (string) $payload['eyebrow'],
            hero_badge: (string) $payload['hero_badge'],
            filter_watch_label: (string) $payload['filter_watch_label'],
            visual_label: (string) $payload['visual_label'],
            pipeline_label: (string) $payload['pipeline_label'],
            faq_title: (string) $payload['faq_title'],
            faq_subtitle: (string) $payload['faq_subtitle'],
            closing_label: (string) $payload['closing_label'],
            intro: $payload['intro'],
            stats: $payload['stats'],
            sections: $payload['sections'],
            timeline: $payload['timeline'],
            faq: $payload['faq'],
            closing_title: (string) $payload['closing_title'],
            closing_body: (string) $payload['closing_body'],
        );
    }

    public function publicCountdownBySlug(string $slug): ?Countdown
    {
        return Countdown::query()->published()->where('slug', $slug)->first();
    }

    /** @return array<string, mixed>|null */
    public function overview(string $slug, string $locale): ?array
    {
        $countdown = Countdown::query()
            ->published()
            ->where('slug', $slug)
            ->with(['projections', 'visualizations'])
            ->first();

        return $countdown instanceof Countdown ? $this->toOverview($countdown, $this->normalizeLocale($locale))->toArray() : null;
    }

    /** @return array<string, mixed>|null */
    public function forecasts(string $slug, string $locale): ?array
    {
        $countdown = Countdown::query()
            ->published()
            ->where('slug', $slug)
            ->with(['projections.visualizations'])
            ->first();

        if (! $countdown instanceof Countdown) {
            return null;
        }

        $locale = $this->normalizeLocale($locale);
        $mainProjection = $this->mainProjection($countdown);
        $projectionChart = $mainProjection instanceof Projection
            ? $mainProjection->visualizations->firstWhere('key', 'projection_curve')
            : null;

        return (new CountdownForecastsData(
            countdown_slug: $countdown->slug,
            projections: $countdown->projections
                ->sortBy('sort_order')
                ->map(fn (Projection $projection): ProjectionData => $this->toProjection($projection, $locale, true))
                ->values()
                ->all(),
            projection_chart: $projectionChart instanceof Visualization ? $this->toVisualization($projectionChart, $locale) : null,
        ))->toArray();
    }

    /** @return array<string, mixed>|null */
    public function statistics(string $slug, string $locale): ?array
    {
        $countdown = Countdown::query()
            ->published()
            ->where('slug', $slug)
            ->with(['visualizations'])
            ->first();

        return $countdown instanceof Countdown
            ? (new CountdownStatisticsData($countdown->slug, $this->toVisualizations($countdown->visualizations, $this->normalizeLocale($locale))))->toArray()
            : null;
    }

    /** @return array<string, mixed>|null */
    public function newsSection(string $slug, string $locale): ?array
    {
        $countdown = Countdown::query()
            ->published()
            ->where('slug', $slug)
            ->with(['news'])
            ->first();

        return $countdown instanceof Countdown
            ? (new CountdownNewsSectionData($countdown->slug, $this->localizedNews($countdown, $this->normalizeLocale($locale))))->toArray()
            : null;
    }

    /** @return array<string, mixed>|null */
    public function initiativesSection(string $slug, string $locale): ?array
    {
        $countdown = Countdown::query()
            ->published()
            ->where('slug', $slug)
            ->with(['initiatives'])
            ->first();

        return $countdown instanceof Countdown
            ? (new CountdownInitiativesSectionData($countdown->slug, $this->localizedInitiatives($countdown, $this->normalizeLocale($locale))))->toArray()
            : null;
    }

    private function toIndexItem(Countdown $countdown, string $locale, bool $isSelected): CountdownIndexData
    {
        $mainProjection = $this->mainProjection($countdown);

        return new CountdownIndexData(
            id: $countdown->id,
            slug: $countdown->slug,
            title: $this->text($countdown->title, $locale),
            summary: $this->text($countdown->summary, $locale),
            image_url: asset($countdown->image_path),
            status: $countdown->status->value,
            severity: $countdown->severity->value,
            sort_order: $countdown->sort_order,
            timer: $this->timer($mainProjection?->target_date ?? $countdown->target_date, $locale),
            main_projection: $mainProjection instanceof Projection ? $this->toProjection($mainProjection, $locale, false) : null,
            url: '/countdowns/'.$countdown->slug.'?lang='.$locale,
            is_selected: $isSelected,
        );
    }

    private function toOverview(Countdown $countdown, string $locale): CountdownOverviewData
    {
        $mainProjection = $this->mainProjection($countdown);
        $keyIndicators = $countdown->visualizations->firstWhere('key', 'key_indicators');

        return new CountdownOverviewData(
            id: $countdown->id,
            slug: $countdown->slug,
            title: $this->text($countdown->title, $locale),
            summary: $this->text($countdown->summary, $locale),
            description: $this->text($countdown->description, $locale),
            image_url: asset($countdown->image_path),
            severity: $countdown->severity->value,
            timer: $this->timer($mainProjection?->target_date ?? $countdown->target_date, $locale),
            main_projection: $mainProjection instanceof Projection ? $this->toProjection($mainProjection, $locale, false) : null,
            causes: $this->textList($countdown->causes, $locale),
            consequences: $this->textList($countdown->consequences, $locale),
            recommended_actions: $this->textList($countdown->recommended_actions, $locale),
            key_indicators: $keyIndicators instanceof Visualization ? $this->toVisualization($keyIndicators, $locale) : null,
        );
    }

    private function mainProjection(Countdown $countdown): ?Projection
    {
        $priority = [
            ProjectionType::Neutral->value => 0,
            ProjectionType::Pessimistic->value => 1,
            ProjectionType::Optimistic->value => 2,
            ProjectionType::Other->value => 3,
        ];

        return $countdown->projections
            ->sortBy(fn (Projection $projection): array => [$priority[$projection->type->value] ?? 4, $projection->sort_order, $projection->id])
            ->first();
    }

    private function toProjection(Projection $projection, string $locale, bool $includeVisualizations): ProjectionData
    {
        $visualizations = $includeVisualizations && $projection->relationLoaded('visualizations')
            ? $this->toVisualizations($projection->visualizations, $locale)
            : [];

        return new ProjectionData($projection->type->value, $projection->target_date?->toImmutable(), $this->text($projection->title, $locale), $this->text($projection->summary, $locale), $projection->confidence_score, $projection->probability_score, $projection->trend, $visualizations);
    }

    /** @param Collection<int, Visualization> $visualizations @return array<int, VisualizationData> */
    private function toVisualizations(Collection $visualizations, string $locale): array
    {
        return $visualizations->sortBy('sort_order')->map(fn (Visualization $visualization): VisualizationData => $this->toVisualization($visualization, $locale))->values()->all();
    }

    private function toVisualization(Visualization $visualization, string $locale): VisualizationData
    {
        return new VisualizationData($visualization->key, $visualization->type->value, $this->text($visualization->title, $locale), $this->text($visualization->description, $locale), $visualization->payload ?? [], $visualization->schema_version, $visualization->sort_order);
    }

    /** @return array<int, NewsData> */
    private function localizedNews(Countdown $countdown, string $locale): array
    {
        return $countdown->news
            ->filter(fn (News $news): bool => in_array($news->locale->value, [NewsLocale::All->value, $locale], true))
            ->sortByDesc(fn (News $news): string => (string) $news->published_at)
            ->values()
            ->map(function (News $news) use ($countdown): NewsData {
                return new NewsData(
                    $news->locale->value,
                    $news->title,
                    $this->previewExcerpt($news->excerpt),
                    $news->content_type ?: 'article',
                    $news->source_name,
                    $news->source_url,
                    $this->resolvedPreviewImageUrl($news->preview_image_url, $news->image_path, $countdown->image_path),
                    $news->embed_url,
                    $news->external_provider,
                    $news->published_at?->toImmutable(),
                    $news->is_featured,
                );
            })
            ->all();
    }

    /** @return array<int, InitiativeData> */
    private function localizedInitiatives(Countdown $countdown, string $locale): array
    {
        return $countdown->initiatives
            ->filter(fn (Initiative $initiative): bool => in_array($initiative->locale->value, [InitiativeLocale::All->value, $locale], true))
            ->sortBy(fn (Initiative $initiative): array => [$initiative->sort_order, $initiative->id])
            ->values()
            ->map(function (Initiative $initiative) use ($countdown): InitiativeData {
                return new InitiativeData(
                    $initiative->locale->value,
                    $initiative->type->value,
                    $initiative->title,
                    $this->previewExcerpt($initiative->excerpt, $initiative->body),
                    $initiative->body,
                    $initiative->organization,
                    $initiative->url,
                    $initiative->content_type ?: 'article',
                    $this->resolvedPreviewImageUrl($initiative->preview_image_url, $initiative->image_path, $countdown->image_path),
                    $initiative->embed_url,
                    $initiative->external_provider,
                    $initiative->cta_label,
                    $initiative->starts_at?->toImmutable(),
                    $initiative->ends_at?->toImmutable(),
                    $initiative->is_featured,
                    $initiative->sort_order,
                );
            })
            ->all();
    }

    private function previewExcerpt(?string $excerpt, ?string $fallback = null): string
    {
        $source = trim((string) $excerpt);
        if ($source === '') {
            $source = trim((string) $fallback);
        }

        $configuredLimit = config('doomsday.content.preview_excerpt_limit', 220);
        $limit = is_numeric($configuredLimit) && (int) $configuredLimit > 0 ? (int) $configuredLimit : 220;

        return Str::limit($source, $limit, '…');
    }

    private function resolvedPreviewImageUrl(?string $previewImageUrl, ?string $imagePath, string $fallbackPath): string
    {
        $previewImageUrl = trim((string) $previewImageUrl);
        if ($this->isHttpsUrl($previewImageUrl)) {
            return $previewImageUrl;
        }

        $imagePath = trim((string) $imagePath);
        if ($imagePath !== '' && parse_url($imagePath, PHP_URL_SCHEME) === null && ! str_starts_with($imagePath, '//')) {
            return asset($imagePath);
        }

        return asset($fallbackPath);
    }

    private function isHttpsUrl(string $url): bool
    {
        return $url !== ''
            && filter_var($url, FILTER_VALIDATE_URL) !== false
            && strtolower((string) parse_url($url, PHP_URL_SCHEME)) === 'https';
    }

    private function timer(?CarbonInterface $targetDate, string $locale): CountdownTimerData
    {
        return new CountdownTimerData($targetDate?->toImmutable(), $locale === 'it' ? 'Obiettivo stimato' : 'Estimated target', $targetDate !== null && $targetDate->isPast());
    }

    /** @param array<string, mixed>|null $value */
    private function text(?array $value, string $locale): string
    {
        if ($value === null || $value === []) {
            return '';
        }

        $fallback = $value['en'] ?? reset($value);

        return (string) ($value[$locale] ?? $fallback ?? '');
    }

    /** @param array<string, mixed>|null $value @return array<int, string> */
    private function textList(?array $value, string $locale): array
    {
        $localized = $value[$locale] ?? $value['en'] ?? [];

        return is_array($localized) ? array_values(array_map('strval', $localized)) : [];
    }

    /** @return array<int, array<string, mixed>> */
    private function languageOptionArrays(string $currentLocale, string $currentPath): array
    {
        return array_map(fn (LanguageOptionData $option): array => $option->toArray(), $this->languageOptions($currentLocale, $currentPath));
    }

    /** @return array<int, LanguageOptionData> */
    private function languageOptions(string $currentLocale, string $currentPath): array
    {
        $path = '/'.ltrim($currentPath, '/');
        if ($path === '/') {
            $path = '/';
        }

        return collect(self::LANGUAGES)->map(fn (array $language, string $code): LanguageOptionData => new LanguageOptionData($code, $language['label'], $language['native'], $language['flag'], $path.'?lang='.$code, $code === $currentLocale))->values()->all();
    }

    /** @return array<string, mixed> */
    private function aboutCopy(string $locale): array
    {
        return $this->aboutPageCopy->forLocale($locale);
    }

    /** @return array<string, string> */
    private function hero(string $locale): array
    {
        $copy = [
            'en' => ['headline_prefix' => 'THE CLOCK IS TICKING.', 'headline_middle' => 'STAY INFORMED. STAY', 'headline_accent' => 'AWARE.', 'subtitle' => 'Real-time countdowns to critical global tipping points.'],
            'it' => ['headline_prefix' => 'IL TEMPO SCORRE.', 'headline_middle' => 'RESTA INFORMATO. RESTA', 'headline_accent' => 'VIGILE.', 'subtitle' => 'Countdown in tempo reale verso punti critici globali.'],
        ];

        return array_merge($copy[$locale] ?? $copy['en'], [
            'desktop_image' => asset('images/doomsday/doomsday_hero_background_desktop.png'),
            'mobile_image' => asset('images/doomsday/doomsday_hero_background_mobile.png'),
        ]);
    }
}
