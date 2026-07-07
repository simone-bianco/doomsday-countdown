<?php

declare(strict_types=1);

namespace App\Services\Doomsday;

use App\Data\Doomsday\AboutPageData;
use App\Data\Doomsday\CountdownDetailData;
use App\Data\Doomsday\CountdownIndexData;
use App\Data\Doomsday\CountdownPageData;
use App\Data\Doomsday\CountdownTimerData;
use App\Data\Doomsday\LanguageOptionData;
use App\Data\Doomsday\NewsData;
use App\Data\Doomsday\ProjectionData;
use App\Data\Doomsday\VisualizationData;
use App\Enums\NewsLocale;
use App\Enums\ProjectionType;
use App\Models\Countdown;
use App\Models\News;
use App\Models\Projection;
use App\Models\Visualization;
use Illuminate\Database\Eloquent\Collection;
use Carbon\CarbonInterface;

final class CountdownPublicDataService
{
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

    public function index(string $locale, ?string $selectedSlug, string $currentPath): CountdownPageData
    {
        $locale = $this->normalizeLocale($locale);
        $countdowns = Countdown::query()
            ->published()
            ->with(['projections.visualizations', 'visualizations', 'news'])
            ->orderBy('sort_order')
            ->get();

        $selected = $selectedSlug !== null
            ? $countdowns->firstWhere('slug', $selectedSlug)
            : null;

        return new CountdownPageData(
            app_name: 'Doomsday Countdown',
            current_locale: $locale,
            languages: $this->languageOptions($locale, $currentPath),
            hero: $this->hero($locale),
            countdowns: $countdowns->map(fn (Countdown $countdown): CountdownIndexData => $this->toIndexItem($countdown, $locale, $countdown->is($selected)))->values()->all(),
            selected_countdown: $selected instanceof Countdown ? $this->toDetail($selected, $locale) : null,
        );
    }

    public function about(string $locale, string $currentPath): AboutPageData
    {
        $locale = $this->normalizeLocale($locale);
        $copy = [
            'en' => [
                'title' => 'About the countdowns',
                'subtitle' => 'A public monitoring interface for critical global risk scenarios.',
                'sections' => [
                    ['title' => 'Methodology', 'body' => 'Each countdown is an editorial forecast based on seeded scenario data, trend indicators, and transparent assumptions. Dates are estimated targets, not certainties.'],
                    ['title' => 'Scenario data', 'body' => 'Phase 1 uses curated sample data from the local database. It does not ingest live feeds or external services.'],
                    ['title' => 'Interpretation', 'body' => 'The interface is designed to make risk signals legible. It should be read as a planning and awareness tool, not as a deterministic prediction.'],
                ],
            ],
            'it' => [
                'title' => 'Informazioni sui countdown',
                'subtitle' => 'Un’interfaccia pubblica di monitoraggio per scenari critici globali.',
                'sections' => [
                    ['title' => 'Metodologia', 'body' => 'Ogni countdown è una previsione editoriale basata su dati scenario, indicatori di tendenza e assunzioni trasparenti. Le date sono obiettivi stimati, non certezze.'],
                    ['title' => 'Dati scenario', 'body' => 'La fase 1 usa dati campione curati dal database locale. Non acquisisce feed live o servizi esterni.'],
                    ['title' => 'Interpretazione', 'body' => 'L’interfaccia rende leggibili i segnali di rischio. Va letta come strumento di pianificazione e consapevolezza, non come previsione deterministica.'],
                ],
            ],
        ];
        $selected = $copy[$locale] ?? $copy['en'];

        return new AboutPageData(
            app_name: 'Doomsday Countdown',
            current_locale: $locale,
            languages: $this->languageOptions($locale, $currentPath),
            title: $selected['title'],
            subtitle: $selected['subtitle'],
            sections: $selected['sections'],
        );
    }

    public function publicCountdownBySlug(string $slug): ?Countdown
    {
        return Countdown::query()->published()->where('slug', $slug)->first();
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
            icon: $countdown->icon,
            status: $countdown->status->value,
            severity: $countdown->severity->value,
            sort_order: $countdown->sort_order,
            timer: $this->timer($mainProjection?->target_date ?? $countdown->target_date, $locale),
            main_projection: $mainProjection instanceof Projection ? $this->toProjection($mainProjection, $locale) : null,
            url: '/countdowns/' . $countdown->slug . '?lang=' . $locale,
            is_selected: $isSelected,
        );
    }

    private function toDetail(Countdown $countdown, string $locale): CountdownDetailData
    {
        $mainProjection = $this->mainProjection($countdown);

        return new CountdownDetailData(
            id: $countdown->id,
            slug: $countdown->slug,
            title: $this->text($countdown->title, $locale),
            summary: $this->text($countdown->summary, $locale),
            description: $this->text($countdown->description, $locale),
            image_url: asset($countdown->image_path),
            icon: $countdown->icon,
            severity: $countdown->severity->value,
            timer: $this->timer($mainProjection?->target_date ?? $countdown->target_date, $locale),
            main_projection: $mainProjection instanceof Projection ? $this->toProjection($mainProjection, $locale) : null,
            causes: $this->textList($countdown->causes, $locale),
            consequences: $this->textList($countdown->consequences, $locale),
            recommended_actions: $this->textList($countdown->recommended_actions, $locale),
            projections: $countdown->projections->sortBy('sort_order')->map(fn (Projection $projection): ProjectionData => $this->toProjection($projection, $locale))->values()->all(),
            visualizations: $this->toVisualizations($countdown->visualizations, $locale),
            news: $this->localizedNews($countdown, $locale),
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
            ->sortBy(fn (Projection $projection): array => [
                $priority[$projection->type->value] ?? 4,
                $projection->sort_order,
                $projection->id,
            ])
            ->first();
    }

    private function toProjection(Projection $projection, string $locale): ProjectionData
    {
        return new ProjectionData(
            type: $projection->type->value,
            target_date: $projection->target_date?->toImmutable(),
            title: $this->text($projection->title, $locale),
            summary: $this->text($projection->summary, $locale),
            confidence_score: $projection->confidence_score,
            probability_score: $projection->probability_score,
            trend: $projection->trend,
            visualizations: $this->toVisualizations($projection->visualizations, $locale),
        );
    }

    /**
     * @param Collection<int, Visualization> $visualizations
     * @return array<int, VisualizationData>
     */
    private function toVisualizations(Collection $visualizations, string $locale): array
    {
        return $visualizations
            ->sortBy('sort_order')
            ->map(fn (Visualization $visualization): VisualizationData => new VisualizationData(
                key: $visualization->key,
                type: $visualization->type->value,
                title: $this->text($visualization->title, $locale),
                description: $this->text($visualization->description, $locale),
                payload: $visualization->payload ?? [],
                schema_version: $visualization->schema_version,
                sort_order: $visualization->sort_order,
            ))
            ->values()
            ->all();
    }

    /** @return array<int, NewsData> */
    private function localizedNews(Countdown $countdown, string $locale): array
    {
        return $countdown->news
            ->filter(fn (News $news): bool => in_array($news->locale->value, [NewsLocale::All->value, $locale], true))
            ->sortByDesc(fn (News $news): string => (string) $news->published_at)
            ->values()
            ->map(fn (News $news): NewsData => new NewsData(
                locale: $news->locale->value,
                title: $news->title,
                excerpt: $news->excerpt,
                source_name: $news->source_name,
                source_url: $news->source_url,
                image_url: $news->image_path !== null ? asset($news->image_path) : null,
                published_at: $news->published_at?->toImmutable(),
                is_featured: $news->is_featured,
            ))
            ->all();
    }

    private function timer(?CarbonInterface $targetDate, string $locale): CountdownTimerData
    {
        $estimated = $locale === 'it' ? 'Obiettivo stimato' : 'Estimated target';

        return new CountdownTimerData(
            target_date: $targetDate?->toImmutable(),
            estimated_label: $estimated,
            is_elapsed: $targetDate !== null && $targetDate->isPast(),
        );
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

    /**
     * @param array<string, mixed>|null $value
     * @return array<int, string>
     */
    private function textList(?array $value, string $locale): array
    {
        $localized = $value[$locale] ?? $value['en'] ?? [];

        return is_array($localized) ? array_values(array_map('strval', $localized)) : [];
    }

    /** @return array<int, LanguageOptionData> */
    private function languageOptions(string $currentLocale, string $currentPath): array
    {
        $path = '/' . ltrim($currentPath, '/');
        if ($path === '/') {
            $path = '/';
        }

        return collect(self::LANGUAGES)
            ->map(fn (array $language, string $code): LanguageOptionData => new LanguageOptionData(
                code: $code,
                label: $language['label'],
                native_label: $language['native'],
                flag: $language['flag'],
                url: $path . '?lang=' . $code,
                is_current: $code === $currentLocale,
            ))
            ->values()
            ->all();
    }

    /** @return array<string, string> */
    private function hero(string $locale): array
    {
        $copy = [
            'en' => [
                'headline_prefix' => 'THE CLOCK IS TICKING.',
                'headline_middle' => 'STAY INFORMED. STAY',
                'headline_accent' => 'AWARE.',
                'subtitle' => 'Real-time countdowns to critical global tipping points.',
            ],
            'it' => [
                'headline_prefix' => 'IL TEMPO SCORRE.',
                'headline_middle' => 'RESTA INFORMATO. RESTA',
                'headline_accent' => 'VIGILE.',
                'subtitle' => 'Countdown in tempo reale verso punti critici globali.',
            ],
        ];

        return array_merge($copy[$locale] ?? $copy['en'], [
            'desktop_image' => asset('images/doomsday/doomsday_hero_background_desktop.png'),
            'mobile_image' => asset('images/doomsday/doomsday_hero_background_mobile.png'),
        ]);
    }
}
