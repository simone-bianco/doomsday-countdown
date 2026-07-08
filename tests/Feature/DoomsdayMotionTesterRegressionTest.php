<?php

declare(strict_types=1);

namespace Tests\Feature;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Tests\TestCase;

final class DoomsdayMotionTesterRegressionTest extends TestCase
{
    public function test_app_shell_order_theme_loader_and_global_motion_scope_are_unchanged(): void
    {
        $app = $this->readSource('resources/js/app.js');

        $uiCss = strpos($app, "import '@simone-bianco/vue-ui-components/style.css';");
        $appCss = strpos($app, "import '../css/app.css';");
        $bootstrap = strpos($app, "import './bootstrap';");
        $ziggyUse = strpos($app, '.use(ZiggyVue)');
        $inertiaUse = strpos($app, '.use(plugin)');

        $this->assertIsInt($uiCss);
        $this->assertIsInt($appCss);
        $this->assertIsInt($bootstrap);
        $this->assertLessThan($appCss, $uiCss, 'UI package CSS must stay before app.css so the Doomsday theme override can win.');
        $this->assertLessThan($bootstrap, $appCss, 'app.css must stay before bootstrap import.');

        $this->assertStringContainsString("import { ZiggyVue } from '../../vendor/tightenco/ziggy';", $app);
        $this->assertStringContainsString("defaultTheme: 'doomsday'", $app);
        $this->assertStringContainsString('h(AppNavigationLoader)', $app);
        $this->assertIsInt($ziggyUse);
        $this->assertIsInt($inertiaUse);
        $this->assertLessThan($inertiaUse, $ziggyUse, 'ZiggyVue must remain registered before the Inertia plugin.');

        foreach (['MotionPlugin', 'vMotion', '@formkit/auto-animate', 'AutoAnimate', 'auto-animate', 'gsap', 'unplugin-vue-components'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $app, 'App shell must not register global animation plugins or forbidden animation libraries.');
        }
    }

    public function test_motion_dependency_scope_has_no_autoanimate_gsap_lottie_or_auto_import_plugin(): void
    {
        $package = json_decode($this->readSource('package.json'), true, 512, JSON_THROW_ON_ERROR);
        $lock = json_decode($this->readSource('package-lock.json'), true, 512, JSON_THROW_ON_ERROR);
        $declaredPackages = array_merge($package['dependencies'] ?? [], $package['devDependencies'] ?? []);
        $lockedPackages = $lock['packages'] ?? [];

        $this->assertSame('^2.3.0', $declaredPackages['motion-v'] ?? null);
        $this->assertSame('2.3.0', $lockedPackages['node_modules/motion-v']['version'] ?? null);

        foreach (['@formkit/auto-animate', 'gsap', 'animejs', 'anime.js', 'lottie-web', 'unplugin-vue-components'] as $forbiddenPackage) {
            $this->assertArrayNotHasKey($forbiddenPackage, $declaredPackages, 'Forbidden animation dependency declared: ' . $forbiddenPackage);
            $this->assertArrayNotHasKey('node_modules/' . $forbiddenPackage, $lockedPackages, 'Forbidden animation package locked: ' . $forbiddenPackage);
        }

        $runtimeSources = $this->readSourcesUnder([
            'resources/js/app.js',
            'resources/js/Pages/Doomsday',
            'resources/js/Components/Doomsday',
            'resources/js/Composables',
            'resources/js/animations',
        ]);

        foreach (['@formkit/auto-animate', 'AutoAnimate', 'auto-animate', "from 'gsap'", 'from "gsap"', 'unplugin-vue-components', 'motion.button', '<motion.button'] as $forbiddenRuntimePattern) {
            $this->assertStringNotContainsString($forbiddenRuntimePattern, $runtimeSources, 'Forbidden animation/runtime pattern found: ' . $forbiddenRuntimePattern);
        }
    }

    public function test_motion_surfaces_preserve_reduced_motion_and_fragile_doomsday_contracts(): void
    {
        $motion = $this->readSource('resources/js/animations/doomsdayMotion.ts');
        $home = $this->readSource('resources/js/Pages/Doomsday/Home.vue');
        $card = $this->readSource('resources/js/Components/Doomsday/CountdownCard.vue');
        $selected = $this->readSource('resources/js/Components/Doomsday/SelectedMasterDetail.vue');
        $detail = $this->readSource('resources/js/Components/Doomsday/DetailPanel.vue');
        $sidebar = $this->readSource('resources/js/Components/Doomsday/SidebarCards.vue');

        $this->assertStringContainsString("import { useReducedMotion } from 'motion-v';", $motion);
        $this->assertStringContainsString('export const reducedMotionFallback = {', $motion);
        $this->assertStringContainsString('export function disabledMotionTarget', $motion);
        $this->assertStringContainsString('return prefersReducedMotion ? selectedCardIdle : target;', $motion);

        $this->assertStringContainsString('AnimatePresence mode="wait"', $home);
        $this->assertStringContainsString('<template v-if="selection.detailOpen.value">', $home);
        $this->assertStringContainsString('<template v-else>', $home);
        $this->assertStringContainsString('@select="selection.selectCountdown"', $home);
        $this->assertStringContainsString('@close="selection.closeSelectedCountdown"', $home);

        $this->assertStringContainsString('const selectedCardMotion = computed(() => (isSelected.value ? disabledMotionTarget(selectedCardActive, reducedMotion.value) : selectedCardIdle));', $card);
        $this->assertStringNotContainsString('const selectedCardMotion = computed(() => (isSelected.value ? selectedCardActive : selectedCardIdle));', $card);
        $this->assertStringContainsString('role="button"', $card);
        $this->assertStringContainsString(':aria-pressed="isSelected"', $card);
        $this->assertStringContainsString('@keydown.enter="emit(\'select\', countdown)"', $card);
        $this->assertStringContainsString('@keydown.space.prevent="emit(\'select\', countdown)"', $card);
        $this->assertStringContainsString('<span v-if="isSelected" v-motion', $card);
        $this->assertStringContainsString('pointer-events-none absolute inset-y-0 left-0 z-20 w-[2px] bg-ui-primary', $card);
        $this->assertStringNotContainsString('border-l', $card);

        $this->assertStringContainsString('h-[calc(100vh-64px)] min-h-0', $selected);
        $this->assertStringContainsString('doomsday-scrollbar grid min-h-0 min-w-0 content-start gap-5 overflow-y-auto', $selected);
        $this->assertStringContainsString('doomsday-card flex h-full min-h-0 flex-col overflow-hidden', $detail);
        $this->assertStringContainsString('doomsday-scrollbar grid min-h-0 min-w-0 flex-1 auto-rows-max gap-5 overflow-y-auto overscroll-contain', $detail);
        $this->assertStringContainsString('<StatisticsSection v-if="statisticsSection" :section="statisticsSection" />', $detail);
        $this->assertStringContainsString('tabContentKey', $detail);

        $this->assertStringContainsString("import { Card, Image, Button } from '@simone-bianco/vue-ui-components';", $sidebar);
        $this->assertStringContainsString('<Link :href="featured.url" class="block w-full sm:w-fit">', $sidebar);
        $this->assertStringContainsString('<Button', $sidebar);
        $this->assertStringContainsString(':icon="ChevronRight"', $sidebar);
        $this->assertStringContainsString(':while-hover="ctaHoverMotion"', $sidebar);
    }

    public function test_motion_runtime_sources_do_not_reintroduce_url_changing_apis_or_layout_animation_tokens(): void
    {
        $navigationRuntimeSources = $this->readSourcesUnder([
            'resources/js/Pages/Doomsday/Home.vue',
            'resources/js/Components/Doomsday/HeroSection.vue',
            'resources/js/Components/Doomsday/CountdownList.vue',
            'resources/js/Components/Doomsday/CountdownCard.vue',
            'resources/js/Components/Doomsday/SelectedMasterDetail.vue',
            'resources/js/Components/Doomsday/MobileDetailView.vue',
            'resources/js/Components/Doomsday/DetailPanel.vue',
            'resources/js/Components/Doomsday/SidebarCards.vue',
            'resources/js/Composables/useDoomsdaySelection.ts',
            'resources/js/Composables/useDoomsdayLazySections.ts',
            'resources/js/animations/doomsdayMotion.ts',
        ]);

        foreach (['router.visit', 'router.reload', 'router.prefetch', 'history.pushState', 'window.location', 'window.fetch', 'prefetch cache-for="2m"'] as $forbiddenNavigation) {
            $this->assertStringNotContainsString($forbiddenNavigation, $navigationRuntimeSources, 'Forbidden URL-changing runtime API found: ' . $forbiddenNavigation);
        }

        $motionAnimatedSurfaces = $this->readSourcesUnder([
            'resources/js/Pages/Doomsday/Home.vue',
            'resources/js/Components/Doomsday/HeroSection.vue',
            'resources/js/Components/Doomsday/CountdownList.vue',
            'resources/js/Components/Doomsday/CountdownCard.vue',
            'resources/js/Components/Doomsday/SelectedMasterDetail.vue',
            'resources/js/Components/Doomsday/MobileDetailView.vue',
            'resources/js/Components/Doomsday/DetailPanel.vue',
            'resources/js/Components/Doomsday/SidebarCards.vue',
            'resources/js/animations/doomsdayMotion.ts',
        ]);

        foreach (['height:', 'width:', 'gridTemplate', 'grid-template', 'layout:'] as $forbiddenLayoutAnimation) {
            $this->assertStringNotContainsString($forbiddenLayoutAnimation, $motionAnimatedSurfaces, 'Forbidden layout animation token found in motion-touched surfaces: ' . $forbiddenLayoutAnimation);
        }
    }

    private function readSource(string $path): string
    {
        return (string) file_get_contents(base_path($path));
    }

    /**
     * @param list<string> $paths
     */
    private function readSourcesUnder(array $paths): string
    {
        $content = '';

        foreach ($paths as $path) {
            $absolutePath = base_path($path);

            if (is_file($absolutePath)) {
                $content .= "\n/* {$path} */\n" . (string) file_get_contents($absolutePath);
                continue;
            }

            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($absolutePath));
            foreach ($iterator as $file) {
                if (! $file->isFile() || ! in_array($file->getExtension(), ['js', 'ts', 'vue'], true)) {
                    continue;
                }

                $content .= "\n/* " . $file->getPathname() . " */\n" . (string) file_get_contents($file->getPathname());
            }
        }

        return $content;
    }
}
