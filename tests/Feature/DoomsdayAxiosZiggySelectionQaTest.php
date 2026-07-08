<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Tests\TestCase;

final class DoomsdayAxiosZiggySelectionQaTest extends TestCase
{
    use RefreshDatabase;

    public function test_named_json_data_endpoints_exist_and_return_not_found_for_missing_or_unpublished_slugs(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $routes = [
            'countdowns.data.overview',
            'countdowns.data.forecasts',
            'countdowns.data.statistics',
            'countdowns.data.news',
            'countdowns.data.initiatives',
        ];

        foreach ($routes as $routeName) {
            $this->assertTrue(Route::has($routeName), 'Missing named JSON route: ' . $routeName);

            $this->getJson(route($routeName, ['slug' => 'missing-countdown', 'lang' => 'en']))
                ->assertNotFound()
                ->assertJsonPath('message', 'Countdown data not found.');
        }
    }

    public function test_runtime_sources_do_not_use_url_changing_apis_or_hardcoded_data_endpoint_strings(): void
    {
        $runtimeSources = $this->readRuntimeSources();

        foreach (['router.visit', 'router.reload', 'router.prefetch', 'history.pushState', 'window.location', 'prefetch cache-for="2m"'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $runtimeSources, 'Forbidden runtime navigation pattern found: ' . $forbidden);
        }

        $this->assertDoesNotMatchRegularExpression('/(?<!pre)fetch\s*\(/', $runtimeSources, 'Use axios for read-only Doomsday loaders, not fetch().');

        $frontendLoaders = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdaySelection.ts'))
            . (string) file_get_contents(base_path('resources/js/Composables/useDoomsdayLazySections.ts'));

        foreach (['overview-data', 'forecasts-data', 'statistics-data', 'news-data', 'initiatives-data', "'/countdowns/", '`/countdowns/'] as $hardcodedEndpoint) {
            $this->assertStringNotContainsString($hardcodedEndpoint, $frontendLoaders, 'Frontend loaders must use Ziggy route names, not hardcoded endpoint strings.');
        }
    }

    public function test_local_selection_and_lazy_section_sources_have_close_and_stale_response_guards(): void
    {
        $selection = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdaySelection.ts'));
        $lazy = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdayLazySections.ts'));
        $card = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownCard.vue'));
        $home = (string) file_get_contents(base_path('resources/js/Pages/Doomsday/Home.vue'));

        $this->assertStringContainsString('activeSelectedSlug.value === countdown.slug', $selection);
        $this->assertStringContainsString('closeSelectedCountdown();', $selection);
        $this->assertStringContainsString('selectedCountdown.value = null', $selection);
        $this->assertStringContainsString('pendingSelectedSlug.value === requestedSlug', $selection);
        $this->assertStringContainsString('currentLocale.value === requestedLocale', $selection);
        $this->assertStringContainsString('countdownSlug.value === requestedSlug', $lazy);
        $this->assertStringContainsString('initialSection?.countdown_slug === countdownSlug.value', $lazy);
        $this->assertStringContainsString('localSection?.countdown_slug === countdownSlug.value', $lazy);
        $this->assertStringContainsString('@click="emit(\'select\', countdown)"', $card);
        $this->assertStringContainsString('@keydown.enter="emit(\'select\', countdown)"', $card);
        $this->assertStringContainsString('@select="selection.selectCountdown"', $home);
    }

    public function test_ziggy_registration_and_app_loader_are_present_and_scoped_to_inertia_navigation(): void
    {
        $blade = (string) file_get_contents(base_path('resources/views/app.blade.php'));
        $app = (string) file_get_contents(base_path('resources/js/app.js'));
        $loader = (string) file_get_contents(base_path('resources/js/Components/App/AppNavigationLoader.vue'));
        $selection = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdaySelection.ts'));
        $lazy = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdayLazySections.ts'));

        $this->assertStringContainsString('@routes', $blade);
        $this->assertStringContainsString("import { ZiggyVue } from '../../vendor/tightenco/ziggy';", $app);
        $this->assertLessThan(strpos($app, '.use(plugin)'), strpos($app, '.use(ZiggyVue)'));
        $this->assertStringContainsString('h(AppNavigationLoader)', $app);
        $this->assertStringContainsString("defaultTheme: 'doomsday'", $app);
        $this->assertStringContainsString("router.on('start'", $loader);
        $this->assertStringContainsString("router.on('finish'", $loader);
        $this->assertStringContainsString('fixed inset-x-0 top-0', $loader);
        $this->assertStringContainsString('@media (prefers-reduced-motion: reduce)', $loader);
        $this->assertStringNotContainsString('AppNavigationLoader', $selection . $lazy);
    }

    private function readRuntimeSources(): string
    {
        $content = '';
        $roots = [base_path('resources/js/Composables'), base_path('resources/js/Components/Doomsday'), base_path('resources/js/Pages/Doomsday')];

        foreach ($roots as $root) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
            foreach ($iterator as $file) {
                if (! $file->isFile() || ! in_array($file->getExtension(), ['ts', 'vue'], true)) {
                    continue;
                }

                $content .= "\n/* " . $file->getPathname() . " */\n" . (string) file_get_contents($file->getPathname());
            }
        }

        return $content;
    }
}
