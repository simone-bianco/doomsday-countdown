<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use App\Models\Countdown;
use App\Services\Doomsday\Seo\PublicSeoService;
use Carbon\CarbonImmutable;
use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as LaravelRoute;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class DoomsdayPublicSeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'https://doomsday-clock.com']);
    }

    protected function tearDown(): void
    {
        Cache::flush();
        $this->travelBack();

        parent::tearDown();
    }

    public function test_home_seo_is_localized_self_canonical_and_reciprocal_for_all_locales(): void
    {
        $service = app(PublicSeoService::class);
        $canonicalByLocale = [];
        $titles = [];

        foreach (['en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl'] as $locale) {
            $seo = $service->forRequest($this->routeRequest('/?lang='.$locale, 'home'), $locale);
            $canonicalByLocale[$locale] = $seo->canonical_url;
            $titles[] = $seo->title;

            $this->assertSame('https://doomsday-clock.com/?lang='.$locale, $seo->canonical_url);
            $this->assertSame('https://doomsday-clock.com/', $seo->x_default_url);
            $this->assertSame('index, follow', $seo->robots);
            $this->assertSame($locale, $seo->locale);
            $this->assertCount(8, $seo->alternates);
            $this->assertSame(
                ['WebSite', 'CollectionPage', 'ItemList'],
                array_column($seo->structured_data, '@type'),
            );

            foreach ($seo->structured_data as $block) {
                $this->assertJson((string) json_encode($block, JSON_THROW_ON_ERROR));
            }
        }

        $this->assertCount(8, array_unique($titles));

        foreach ($canonicalByLocale as $locale => $canonical) {
            $seo = $service->forRequest($this->routeRequest('/?lang='.$locale, 'home'), $locale);
            $alternateMap = collect($seo->alternates)->mapWithKeys(fn ($alternate): array => [
                $alternate->hreflang => $alternate->url,
            ])->all();
            $this->assertSame($canonicalByLocale, $alternateMap);
            $this->assertSame($canonical, $alternateMap[$locale]);
        }
    }

    public function test_home_item_list_matches_visible_order_at_exact_target_and_plus_one_without_double_sidebar_composition(): void
    {
        $exactTarget = CarbonImmutable::parse('2027-03-31 23:59:59', 'UTC');
        $this->travelTo($exactTarget);
        $this->seed(DoomsdaySeeder::class);
        Cache::flush();

        $storedOrder = Countdown::query()
            ->published()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('slug')
            ->all();

        $exactVisible = $this->assertHomeAndItemListOrderMatch();
        $this->assertNotSame($storedOrder, $exactVisible);
        $this->assertSame('europe-war-countdown', $exactVisible[0]);

        $this->travelTo($exactTarget->addSecond());
        $plusOneVisible = $this->assertHomeAndItemListOrderMatch();

        $this->assertNotSame($exactVisible, $plusOneVisible);
        $this->assertSame('ai-job-apocalypse', $plusOneVisible[0]);
        $this->assertSame('europe-war-countdown', $plusOneVisible[4]);
    }

    public function test_countdown_seo_uses_direct_published_identity_and_factual_modified_date(): void
    {
        $modified = CarbonImmutable::parse('2026-07-10 08:09:10', 'UTC');
        $countdown = $this->countdown('seo-countdown', true, [
            'en' => 'SEO Countdown',
            'it' => 'Countdown SEO',
        ], [
            'en' => 'An evidence-based scenario summary.',
            'it' => 'Un riepilogo di scenario basato su evidenze.',
        ]);
        DB::table('countdowns')->where('id', $countdown->id)->update(['updated_at' => $modified]);
        $countdown->refresh();

        $request = $this->routeRequest('/countdowns/seo-countdown?lang=it', 'countdowns.show', ['slug' => 'seo-countdown']);
        $seo = app(PublicSeoService::class)->forRequest($request, 'it');

        $this->assertSame('Countdown SEO | Doomsday Clock', $seo->title);
        $this->assertSame('Un riepilogo di scenario basato su evidenze.', $seo->description);
        $this->assertSame('https://doomsday-clock.com/countdowns/seo-countdown?lang=it', $seo->canonical_url);
        $this->assertSame($seo->canonical_url, $seo->open_graph->url);
        $this->assertSame('2026-07-10 08:09:10', $seo->date_modified?->utc()->format('Y-m-d H:i:s'));
        $this->assertStringStartsWith('https://doomsday-clock.com/images/doomsday/', $seo->open_graph->image->url);
        $this->assertSame(['WebPage', 'BreadcrumbList'], array_column($seo->structured_data, '@type'));
        $this->assertStringNotContainsString('Organization', json_encode($seo->structured_data, JSON_THROW_ON_ERROR));
        $this->assertStringNotContainsString('NewsArticle', json_encode($seo->structured_data, JSON_THROW_ON_ERROR));
        $this->assertStringNotContainsString('Dataset', json_encode($seo->structured_data, JSON_THROW_ON_ERROR));
    }

    public function test_unpublished_countdown_and_non_public_routes_are_noindex(): void
    {
        $this->countdown('hidden-seo', false, ['en' => 'Hidden'], ['en' => 'Hidden summary']);
        $service = app(PublicSeoService::class);

        $hidden = $service->forRequest(
            $this->routeRequest('/countdowns/hidden-seo?lang=en', 'countdowns.show', ['slug' => 'hidden-seo']),
            'en',
        );
        $login = $service->forRequest($this->routeRequest('/login', 'login'), 'en');
        $legal = $service->forRequest($this->routeRequest('/privacy?lang=it', 'privacy'), 'it');

        $this->assertSame('noindex, nofollow', $hidden->robots);
        $this->assertSame([], $hidden->structured_data);
        $this->assertSame('noindex, nofollow', $login->robots);
        $this->assertSame([], $login->alternates);
        $this->assertSame('noindex, follow', $legal->robots);
        $this->assertCount(8, $legal->alternates);
        $this->assertSame('https://doomsday-clock.com/privacy?lang=it', $legal->canonical_url);
    }

    public function test_inertia_shared_props_include_request_locale_rendered_at_and_typed_seo(): void
    {
        $response = $this->get('/?lang=it')->assertOk();

        $this->assertSame('it', $response->inertiaProps('locale'));
        $this->assertSame('it', $response->inertiaProps('seo.locale'));
        $this->assertSame('https://doomsday-clock.com/?lang=it', $response->inertiaProps('seo.canonical_url'));
        $this->assertSame('index, follow', $response->inertiaProps('seo.robots'));
        $renderedAt = $response->inertiaProps('rendered_at');
        $this->assertIsString($renderedAt);
        $this->assertSame($renderedAt, CarbonImmutable::parse($renderedAt)->utc()->toIso8601String());
        $this->assertStringContainsString('<html lang="it">', $response->getContent());
    }

    public function test_seo_head_component_renders_all_contract_surfaces_without_raw_json_script_tag(): void
    {
        $source = (string) file_get_contents(resource_path('js/Components/Doomsday/SeoHead.vue'));

        foreach ([
            'name="description"',
            'name="robots"',
            'rel="canonical"',
            'hreflang="x-default"',
            'property="og:title"',
            'name="twitter:card"',
            'type="application/ld+json"',
            "replace(/</g, '\\\\u003C')",
        ] as $marker) {
            $this->assertStringContainsString($marker, $source, $marker);
        }
        $this->assertStringContainsString('<component :is="\'script\'"', $source);
        $this->assertStringNotContainsString('<script type="application/ld+json"', $source);
    }

    /** @return array<int, string> */
    private function assertHomeAndItemListOrderMatch(): array
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->get('/?lang=en')->assertOk();
        $queryLog = DB::getQueryLog();
        DB::disableQueryLog();

        $visibleSlugs = array_column($response->inertiaProps('page.countdowns'), 'slug');
        $itemList = collect($response->inertiaProps('seo.structured_data'))->firstWhere('@type', 'ItemList');
        $this->assertIsArray($itemList);
        $items = $itemList['itemListElement'] ?? [];
        $this->assertIsArray($items);
        $schemaSlugs = array_map(
            static fn (string $url): string => basename((string) parse_url($url, PHP_URL_PATH)),
            array_column($items, 'url'),
        );

        $this->assertSame($visibleSlugs, $schemaSlugs);
        $this->assertSame(range(1, count($visibleSlugs)), array_column($items, 'position'));

        $latestUpdatedAt = Countdown::query()->published()->max('updated_at');
        $this->assertSame(
            CarbonImmutable::parse((string) $latestUpdatedAt)->utc()->toIso8601String(),
            CarbonImmutable::parse((string) $response->inertiaProps('seo.date_modified'))->utc()->toIso8601String(),
        );

        $newsQueries = collect($queryLog)
            ->filter(function (array $query): bool {
                $sql = strtolower(str_replace(['`', '[', ']'], '"', (string) ($query['query'] ?? '')));

                return str_contains($sql, 'from "news"');
            })
            ->map(fn (array $query): string => ($query['query'] ?? '').'|'.json_encode($query['bindings'] ?? [], JSON_THROW_ON_ERROR))
            ->values();

        $this->assertNotEmpty($newsQueries);
        $this->assertSame($newsQueries->count(), $newsQueries->unique()->count(), 'SEO Home cache hit must not repeat sidebar News queries.');

        return $visibleSlugs;
    }

    /** @param array<string, string> $parameters */
    private function routeRequest(string $uri, string $name, array $parameters = []): Request
    {
        $request = Request::create($uri, 'GET');
        $routeUri = ltrim((string) parse_url($uri, PHP_URL_PATH), '/') ?: '/';
        if (isset($parameters['slug'])) {
            $routeUri = 'countdowns/{slug}';
        }
        $route = new LaravelRoute(['GET'], $routeUri, fn (): null => null);
        $route->name($name);
        $route->bind($request);
        foreach ($parameters as $key => $value) {
            $route->setParameter($key, $value);
        }
        $request->setRouteResolver(static fn (): LaravelRoute => $route);

        return $request;
    }

    /** @param array<string, string> $title @param array<string, string> $summary */
    private function countdown(string $slug, bool $published, array $title, array $summary): Countdown
    {
        return Countdown::query()->create([
            'slug' => $slug,
            'title' => $title,
            'summary' => $summary,
            'description' => $summary,
            'severity' => CountdownSeverity::High,
            'status' => CountdownStatus::Active,
            'target_date' => CarbonImmutable::parse('2030-01-01 00:00:00', 'UTC'),
            'image_path' => 'images/doomsday/society_collapse_separate.png',
            'sort_order' => 1,
            'is_published' => $published,
        ]);
    }
}
