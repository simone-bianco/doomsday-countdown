<?php

declare(strict_types=1);

namespace App\Services\Doomsday\Seo;

use App\Data\Doomsday\SeoImageData;
use App\Data\Doomsday\SeoOpenGraphData;
use App\Data\Doomsday\SeoPageData;
use App\Data\Doomsday\SeoTwitterData;
use App\Models\Countdown;
use App\Services\Doomsday\Cache\CountdownCache;
use App\Services\Doomsday\Copy\AboutPageCopy;
use App\Services\Doomsday\CountdownPublicDataService;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Http\Request;

final class PublicSeoService
{
    public const COUNTDOWN_ATTRIBUTE = 'public_seo_countdown';

    public const COUNTDOWN_SLUG_ATTRIBUTE = 'public_seo_countdown_slug';

    /** @var array<string, array{title: string, description: string}> */
    private const HOME_META = [
        'en' => ['title' => 'Doomsday Clock — Global risk clocks', 'description' => 'Track evidence-based scenario windows for conflict, climate, biodiversity, technology and public-health risks.'],
        'it' => ['title' => 'Doomsday Clock — Orologi del rischio globale', 'description' => 'Segui finestre di scenario basate su evidenze per conflitti, clima, biodiversità, tecnologia e salute pubblica.'],
        'fr' => ['title' => 'Doomsday Clock — Horloges des risques mondiaux', 'description' => 'Suivez des fenêtres de scénario fondées sur des preuves pour les conflits, le climat, la biodiversité, la technologie et la santé publique.'],
        'de' => ['title' => 'Doomsday Clock — Globale Risikouhren', 'description' => 'Verfolgen Sie evidenzbasierte Szenariofenster für Konflikte, Klima, Biodiversität, Technologie und öffentliche Gesundheit.'],
        'es' => ['title' => 'Doomsday Clock — Relojes de riesgo global', 'description' => 'Sigue ventanas de escenarios basadas en evidencias sobre conflictos, clima, biodiversidad, tecnología y salud pública.'],
        'nl' => ['title' => 'Doomsday Clock — Wereldwijde risicoklokken', 'description' => 'Volg onderbouwde scenariovensters voor conflict, klimaat, biodiversiteit, technologie en volksgezondheid.'],
        'sv' => ['title' => 'Doomsday Clock — Globala riskklockor', 'description' => 'Följ evidensbaserade scenariofönster för konflikt, klimat, biologisk mångfald, teknik och folkhälsa.'],
        'pl' => ['title' => 'Doomsday Clock — Globalne zegary ryzyka', 'description' => 'Śledź oparte na dowodach okna scenariuszy dla konfliktów, klimatu, bioróżnorodności, technologii i zdrowia publicznego.'],
    ];

    /** @var array<string, string> */
    private const OG_LOCALES = [
        'en' => 'en_GB',
        'it' => 'it_IT',
        'fr' => 'fr_FR',
        'de' => 'de_DE',
        'es' => 'es_ES',
        'nl' => 'nl_NL',
        'sv' => 'sv_SE',
        'pl' => 'pl_PL',
    ];

    public function __construct(
        private readonly PublicUrlBuilder $urlBuilder,
        private readonly CountdownPublicDataService $publicDataService,
        private readonly CountdownCache $countdownCache,
        private readonly AboutPageCopy $aboutPageCopy,
    ) {}

    public function forRequest(Request $request, string $locale): SeoPageData
    {
        $locale = $this->publicDataService->normalizeLocale($locale);
        $routeName = $request->route()?->getName();

        return match ($routeName) {
            'home' => $this->home($locale),
            'about' => $this->about($locale),
            'countdowns.show' => $this->countdown($request, $locale),
            'privacy' => $this->legal('/privacy', $locale, 'Privacy Policy'),
            'cookie-policy' => $this->legal('/cookie-policy', $locale, 'Cookie Policy'),
            default => $this->noindex($request->getPathInfo(), $locale),
        };
    }

    public function rememberCountdown(Request $request, Countdown $countdown): void
    {
        $request->attributes->set(self::COUNTDOWN_ATTRIBUTE, $countdown);
        $request->attributes->set(self::COUNTDOWN_SLUG_ATTRIBUTE, $countdown->slug);
    }

    public function publishedCountdown(Request $request, string $slug): ?Countdown
    {
        $cachedSlug = $request->attributes->get(self::COUNTDOWN_SLUG_ATTRIBUTE);
        $cached = $request->attributes->get(self::COUNTDOWN_ATTRIBUTE);
        if ($cachedSlug === $slug) {
            return $cached instanceof Countdown ? $cached : null;
        }

        $countdown = Countdown::query()->published()->where('slug', $slug)->first();
        $request->attributes->set(self::COUNTDOWN_SLUG_ATTRIBUTE, $slug);
        $request->attributes->set(self::COUNTDOWN_ATTRIBUTE, $countdown);

        return $countdown;
    }

    private function home(string $locale): SeoPageData
    {
        $page = $this->countdownCache->page($locale, null, '/');
        $countdowns = collect($page['countdowns'] ?? [])
            ->filter(fn (mixed $countdown): bool => is_array($countdown) && is_string($countdown['slug'] ?? null))
            ->values();
        $copy = self::HOME_META[$locale] ?? self::HOME_META['en'];
        $image = $this->image('images/doomsday/doomsday_logo_transparent.png', 'Doomsday Clock');
        $latestUpdatedAt = Countdown::query()
            ->published()
            ->whereNotNull('updated_at')
            ->max('updated_at');
        $dateModified = $latestUpdatedAt instanceof DateTimeInterface || is_string($latestUpdatedAt)
            ? CarbonImmutable::parse($latestUpdatedAt)->utc()
            : null;
        $itemList = $countdowns->map(fn (array $countdown, int $index): array => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => (string) ($countdown['title'] ?? $countdown['slug']),
            'url' => $this->urlBuilder->localeUrl('/countdowns/'.$countdown['slug'], $locale),
        ])->all();

        return $this->page(
            path: '/',
            locale: $locale,
            title: $copy['title'],
            description: $copy['description'],
            robots: 'index, follow',
            image: $image,
            dateModified: $dateModified,
            structuredData: [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebSite',
                    'name' => 'Doomsday Clock',
                    'url' => $this->urlBuilder->neutralUrl('/'),
                    'inLanguage' => $locale,
                ],
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'CollectionPage',
                    'name' => $copy['title'],
                    'description' => $copy['description'],
                    'url' => $this->urlBuilder->localeUrl('/', $locale),
                    'inLanguage' => $locale,
                ],
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'ItemList',
                    'name' => $copy['title'],
                    'itemListElement' => $itemList,
                ],
            ],
        );
    }

    private function about(string $locale): SeoPageData
    {
        $copy = $this->aboutPageCopy->forLocale($locale);
        $title = (string) ($copy['title'] ?? 'About Doomsday Clock');
        $description = (string) ($copy['subtitle'] ?? self::HOME_META['en']['description']);
        $image = $this->image('images/doomsday/doomsday_logo_transparent.png', $title);

        return $this->page(
            path: '/about',
            locale: $locale,
            title: $title.' | Doomsday Clock',
            description: $description,
            robots: 'index, follow',
            image: $image,
            dateModified: null,
            structuredData: [[
                '@context' => 'https://schema.org',
                '@type' => 'AboutPage',
                'name' => $title,
                'description' => $description,
                'url' => $this->urlBuilder->localeUrl('/about', $locale),
                'inLanguage' => $locale,
            ]],
        );
    }

    private function countdown(Request $request, string $locale): SeoPageData
    {
        $slug = (string) $request->route('slug');
        $countdown = $this->publishedCountdown($request, $slug);
        if (! $countdown instanceof Countdown) {
            return $this->noindex('/countdowns/'.$slug, $locale);
        }

        $title = $this->text($countdown->title, $locale);
        $description = $this->text($countdown->summary, $locale);
        if ($description === '') {
            $description = $this->text($countdown->description, $locale);
        }
        $path = '/countdowns/'.$countdown->slug;
        $image = $this->image($countdown->image_path, $title);
        $dateModified = $countdown->updated_at?->toImmutable()->utc();

        return $this->page(
            path: $path,
            locale: $locale,
            title: $title.' | Doomsday Clock',
            description: $description,
            robots: 'index, follow',
            image: $image,
            dateModified: $dateModified,
            structuredData: [
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    'name' => $title,
                    'description' => $description,
                    'url' => $this->urlBuilder->localeUrl($path, $locale),
                    'inLanguage' => $locale,
                    'dateModified' => $dateModified?->toIso8601String(),
                    'primaryImageOfPage' => [
                        '@type' => 'ImageObject',
                        'url' => $image->url,
                        'width' => $image->width,
                        'height' => $image->height,
                    ],
                ],
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'Doomsday Clock',
                            'item' => $this->urlBuilder->localeUrl('/', $locale),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => $title,
                            'item' => $this->urlBuilder->localeUrl($path, $locale),
                        ],
                    ],
                ],
            ],
        );
    }

    private function legal(string $path, string $locale, string $title): SeoPageData
    {
        return $this->page(
            path: $path,
            locale: $locale,
            title: $title.' | Doomsday Clock',
            description: $title.' for the Doomsday Clock public website.',
            robots: 'noindex, follow',
            image: $this->image('images/doomsday/doomsday_logo_transparent.png', 'Doomsday Clock'),
            dateModified: CarbonImmutable::parse('2026-07-09 00:00:00', 'UTC'),
            structuredData: [],
        );
    }

    private function noindex(string $path, string $locale): SeoPageData
    {
        $title = 'Doomsday Clock';
        $image = $this->image('images/doomsday/doomsday_logo_transparent.png', $title);
        $canonical = $this->urlBuilder->neutralUrl($path);
        $ogLocale = self::OG_LOCALES[$locale] ?? self::OG_LOCALES['en'];

        return new SeoPageData(
            title: $title,
            description: self::HOME_META[$locale]['description'] ?? self::HOME_META['en']['description'],
            canonical_url: $canonical,
            robots: 'noindex, nofollow',
            locale: $locale,
            alternates: [],
            x_default_url: $canonical,
            open_graph: new SeoOpenGraphData(
                title: $title,
                description: self::HOME_META[$locale]['description'] ?? self::HOME_META['en']['description'],
                url: $canonical,
                type: 'website',
                site_name: 'Doomsday Clock',
                locale: $ogLocale,
                alternate_locales: [],
                image: $image,
            ),
            twitter: new SeoTwitterData('summary_large_image', $title, self::HOME_META['en']['description'], $image->url, $image->alt),
            date_modified: null,
            structured_data: [],
        );
    }

    /** @param array<int, array<string, mixed>> $structuredData */
    private function page(
        string $path,
        string $locale,
        string $title,
        string $description,
        string $robots,
        SeoImageData $image,
        ?CarbonImmutable $dateModified,
        array $structuredData,
    ): SeoPageData {
        $canonical = $this->urlBuilder->localeUrl($path, $locale);
        $ogLocale = self::OG_LOCALES[$locale] ?? self::OG_LOCALES['en'];
        $alternateOgLocales = array_values(array_filter(
            self::OG_LOCALES,
            fn (string $candidate): bool => $candidate !== $ogLocale,
        ));

        return new SeoPageData(
            title: $title,
            description: $description,
            canonical_url: $canonical,
            robots: $robots,
            locale: $locale,
            alternates: $this->urlBuilder->alternates($path),
            x_default_url: $this->urlBuilder->neutralUrl($path),
            open_graph: new SeoOpenGraphData(
                title: $title,
                description: $description,
                url: $canonical,
                type: 'website',
                site_name: 'Doomsday Clock',
                locale: $ogLocale,
                alternate_locales: $alternateOgLocales,
                image: $image,
            ),
            twitter: new SeoTwitterData('summary_large_image', $title, $description, $image->url, $image->alt),
            date_modified: $dateModified,
            structured_data: $structuredData,
        );
    }

    private function image(string $path, string $alt): SeoImageData
    {
        $publicPath = public_path(ltrim($path, '/'));
        $size = is_file($publicPath) ? @getimagesize($publicPath) : false;

        return new SeoImageData(
            url: $this->urlBuilder->assetUrl($path),
            width: is_array($size) ? (int) $size[0] : null,
            height: is_array($size) ? (int) $size[1] : null,
            alt: $alt,
        );
    }

    /** @param array<string, mixed>|null $value */
    private function text(?array $value, string $locale): string
    {
        if ($value === null || $value === []) {
            return '';
        }

        $fallback = $value['en'] ?? reset($value);

        return trim((string) ($value[$locale] ?? $fallback ?? ''));
    }
}
