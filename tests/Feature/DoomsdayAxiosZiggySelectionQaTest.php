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
            $this->assertTrue(Route::has($routeName), 'Missing named JSON route: '.$routeName);

            $this->getJson(route($routeName, ['slug' => 'missing-countdown', 'lang' => 'en']))
                ->assertNotFound()
                ->assertJsonPath('message', 'Countdown data not found.');
        }
    }

    public function test_runtime_sources_do_not_use_url_changing_apis_or_hardcoded_data_endpoint_strings(): void
    {
        $runtimeSources = $this->readRuntimeSources();

        foreach (['router.visit', 'router.reload', 'router.prefetch', 'history.pushState', 'window.location', 'prefetch cache-for="2m"'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $runtimeSources, 'Forbidden runtime navigation pattern found: '.$forbidden);
        }

        $this->assertDoesNotMatchRegularExpression('/(?<!pre)fetch\s*\(/', $runtimeSources, 'Use axios for read-only Doomsday loaders, not fetch().');

        $frontendLoaders = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdaySelection.ts'))
            .(string) file_get_contents(base_path('resources/js/Composables/useDoomsdayLazySections.ts'));

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

    public function test_public_internal_navigation_uses_inertia_links_and_preserves_external_anchors(): void
    {
        $header = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SiteHeader.vue'));
        $languageSelector = (string) file_get_contents(base_path('resources/js/Components/Doomsday/LanguageSelector.vue'));
        $home = (string) file_get_contents(base_path('resources/js/Pages/Doomsday/Home.vue'));
        $patreon = (string) file_get_contents(base_path('resources/js/Components/Doomsday/PatreonSupportLink.vue'));
        $community = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CommunityLinks.vue'));
        $languageProducer = (string) file_get_contents(base_path('app/Services/Doomsday/CountdownPublicDataService.php'));
        $app = (string) file_get_contents(base_path('resources/js/app.js'));
        $loader = (string) file_get_contents(base_path('resources/js/Components/App/AppNavigationLoader.vue'));
        $routes = (string) file_get_contents(base_path('routes/web.php'));

        $this->assertTrue(Route::has('home'));
        $this->assertTrue(Route::has('about'));

        $this->assertStringContainsString("import { Link } from '@inertiajs/vue3';", $header);
        $this->assertStringContainsString("import { route } from '../../../../vendor/tightenco/ziggy';", $header);
        $this->assertStringContainsString("const homeUrl = computed(() => route('home', { lang: props.currentLocale }));", $header);
        $this->assertStringContainsString("const aboutUrl = computed(() => route('about', { lang: props.currentLocale }));", $header);
        $this->assertSame(3, substr_count($header, '<Link :href='));
        $this->assertStringContainsString('<Link :href="homeUrl" class="flex items-center gap-3" aria-label="Doomsday Clock home">', $header);
        $this->assertStringContainsString('<Link :href="homeUrl" :class=', $header);
        $this->assertStringContainsString('<Link :href="aboutUrl" :class=', $header);
        $this->assertStringContainsString('<CommunityLinks placement="header" />', $header);
        $this->assertStringContainsString('<PatreonSupportLink placement="header" />', $header);
        $this->assertStringNotContainsString('<a :href="homeUrl"', $header);
        $this->assertStringNotContainsString('<a :href="aboutUrl"', $header);

        $this->assertStringContainsString("import { Link } from '@inertiajs/vue3';", $languageSelector);
        $this->assertStringContainsString('<Link', $languageSelector);
        $this->assertStringContainsString(':href="language.url"', $languageSelector);
        $this->assertStringContainsString('language.is_current', $languageSelector);
        $this->assertStringContainsString('void setLanguage(props.currentLocale);', $languageSelector);
        $this->assertStringNotContainsString('<a', $languageSelector);
        $this->assertStringNotContainsString('@click', $languageSelector);
        $this->assertStringContainsString("\$path.'?lang='.\$code", $languageProducer);

        $this->assertStringContainsString("import { Head, Link } from '@inertiajs/vue3';", $home);
        $this->assertStringContainsString("import { route } from '../../../../vendor/tightenco/ziggy';", $home);
        $this->assertStringContainsString('<Link :href="route(\'about\', { lang: page.current_locale })" class="text-ui-primary">Learn more about our methodology</Link>', $home);
        $this->assertStringNotContainsString('<a :href="`/about?lang=', $home);

        foreach (['window.location', 'location.href', 'history.pushState', 'router.visit', 'router.reload', 'router.prefetch', '/?lang=', '/about?lang='] as $routingHack) {
            $this->assertStringNotContainsString($routingHack, $header.$languageSelector.$home, 'Forbidden public navigation pattern: '.$routingHack);
        }

        $this->assertStringContainsString('<a', $patreon);
        $this->assertStringContainsString('https://www.patreon.com/cw/doomsdayclock', $patreon);
        $this->assertStringContainsString('target="_blank"', $patreon);
        $this->assertStringContainsString('rel="noopener noreferrer"', $patreon);
        $this->assertStringNotContainsString("from '@inertiajs/vue3'", $patreon);
        $this->assertStringNotContainsString('route(', $patreon);

        $this->assertStringContainsString('<a', $community);
        $this->assertStringContainsString('https://discord.gg/NmKXDzwzK', $community);
        $this->assertStringContainsString('https://t.me/doomsdayclockofficial', $community);
        $this->assertStringContainsString('/images/community/discord.png', $community);
        $this->assertStringContainsString('/images/community/telegram.png', $community);
        $this->assertStringContainsString('target="_blank"', $community);
        $this->assertStringContainsString('rel="noopener noreferrer"', $community);
        $this->assertStringNotContainsString("from '@inertiajs/vue3'", $community);
        $this->assertStringNotContainsString('route(', $community);

        $this->assertStringContainsString("import { ZiggyVue } from '../../vendor/tightenco/ziggy';", $app);
        $this->assertLessThan(strpos($app, '.use(plugin)'), strpos($app, '.use(ZiggyVue)'));
        $this->assertSame(1, substr_count($app, 'h(AppNavigationLoader)'));
        $this->assertStringContainsString("router.on('start'", $loader);
        $this->assertStringContainsString("router.on('finish'", $loader);
        $this->assertStringContainsString('removeStartListener?.();', $loader);
        $this->assertStringContainsString('removeFinishListener?.();', $loader);
        $this->assertStringContainsString("->name('home')", $routes);
        $this->assertStringContainsString("->name('about')", $routes);
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
        $this->assertStringNotContainsString('AppNavigationLoader', $selection.$lazy);
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

                $content .= "\n/* ".$file->getPathname()." */\n".(string) file_get_contents($file->getPathname());
            }
        }

        return $content;
    }
}
