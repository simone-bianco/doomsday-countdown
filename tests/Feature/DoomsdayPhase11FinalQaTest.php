<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdayPhase11FinalQaTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_lazy_sections_are_returned_only_when_requested_by_partial_reload(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $sectionAssertions = [
            'forecast_section' => ['countdown_slug', 'projections'],
            'statistics_section' => ['countdown_slug', 'visualizations'],
            'news_section' => ['countdown_slug', 'news'],
            'initiatives_section' => ['countdown_slug', 'initiatives'],
        ];

        foreach ($sectionAssertions as $requestedSection => $requiredKeys) {
            $response = $this->withHeaders($this->partialHeaders($requestedSection))
                ->get('/countdowns/fall-of-europe?lang=en');

            $response->assertOk();
            $props = $response->json('props');

            $this->assertIsArray($props);
            $this->assertArrayHasKey($requestedSection, $props);

            foreach (array_keys($sectionAssertions) as $otherSection) {
                if ($otherSection !== $requestedSection) {
                    $this->assertArrayNotHasKey($otherSection, $props, 'Unexpected lazy section returned: ' . $otherSection);
                }
            }

            foreach ($requiredKeys as $requiredKey) {
                $this->assertArrayHasKey($requiredKey, $props[$requestedSection]);
            }

            $this->assertSame('fall-of-europe', $props[$requestedSection]['countdown_slug']);
        }
    }

    public function test_phase_1_1_source_contract_uses_axios_ziggy_data_loaders_without_inertia_url_switching(): void
    {
        $routes = (string) file_get_contents(base_path('routes/web.php'));
        $controller = (string) file_get_contents(base_path('app/Http/Controllers/Web/CountdownDataController.php'));
        $selection = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdaySelection.ts'));
        $lazyComposable = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdayLazySections.ts'));
        $countdownCard = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownCard.vue'));
        $app = (string) file_get_contents(base_path('resources/js/app.js'));
        $loader = (string) file_get_contents(base_path('resources/js/Components/App/AppNavigationLoader.vue'));

        foreach (['overview', 'forecasts', 'statistics', 'news', 'initiatives'] as $section) {
            $this->assertStringContainsString("countdowns.data.{$section}", $routes . $selection . $lazyComposable);
        }

        $this->assertStringContainsString('CountdownCache $cache', $controller);
        $this->assertStringContainsString('response()->json([\'data\' => $payload])', $controller);
        $this->assertStringContainsString('axios.get<{ data: CountdownOverviewData }>', $selection);
        $this->assertStringContainsString("route('countdowns.data.overview'", $selection);
        $this->assertStringContainsString('axios.get<{ data: SectionDataByKey[K] }>', $lazyComposable);
        $this->assertStringContainsString('route(sectionRouteByKey[key]', $lazyComposable);
        $this->assertStringContainsString('countdownSlug.value === requestedSlug', $lazyComposable);
        $this->assertStringContainsString('pendingSelectedSlug.value === requestedSlug', $selection);

        foreach (['router.visit', 'router.reload', 'router.prefetch', 'history.pushState', 'window.location'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $selection . $lazyComposable);
        }

        $this->assertStringNotContainsString('prefetch cache-for="2m"', $countdownCard);
        $this->assertStringNotContainsString('<Link', $countdownCard);
        $this->assertStringNotContainsString("'/countdowns/", $selection . $lazyComposable);
        $this->assertStringNotContainsString('`/countdowns/', $selection . $lazyComposable);

        $this->assertStringContainsString("import { ZiggyVue } from '../../vendor/tightenco/ziggy';", $app);
        $this->assertLessThan(strpos($app, '.use(plugin)'), strpos($app, '.use(ZiggyVue)'));
        $this->assertStringContainsString('h(AppNavigationLoader)', $app);
        $this->assertStringContainsString('router.on(\'start\'', $loader);
        $this->assertStringContainsString('router.on(\'finish\'', $loader);
        $this->assertStringContainsString('fixed inset-x-0 top-0', $loader);
        $this->assertStringContainsString("defaultTheme: 'doomsday'", $app);
    }

    public function test_doomsday_cache_calls_are_centralized_in_countdown_cache_service(): void
    {
        $cacheServicePath = realpath(base_path('app/Services/Doomsday/Cache/CountdownCache.php'));
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(base_path('app')));

        foreach ($iterator as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $content = (string) file_get_contents($file->getPathname());
            if (realpath($file->getPathname()) === $cacheServicePath) {
                $this->assertStringContainsString('Cache::remember', $content);
                continue;
            }

            $this->assertStringNotContainsString('Cache::remember', $content, 'Cache::remember must stay centralized in CountdownCache: ' . $file->getPathname());
        }
    }

    /** @return array<string, string> */
    private function partialHeaders(string $only): array
    {
        $manifest = public_path('build/manifest.json');

        return [
            'X-Inertia' => 'true',
            'X-Inertia-Version' => file_exists($manifest) ? hash_file('xxh128', $manifest) : '',
            'X-Inertia-Partial-Component' => 'Doomsday/Home',
            'X-Inertia-Partial-Data' => $only,
        ];
    }
}
