<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class DoomsdayMotionQaTest extends TestCase
{
    public function test_motion_dependency_is_scoped_and_forbidden_animation_packages_are_absent(): void
    {
        $package = json_decode((string) file_get_contents(base_path('package.json')), true, 512, JSON_THROW_ON_ERROR);
        $lock = json_decode((string) file_get_contents(base_path('package-lock.json')), true, 512, JSON_THROW_ON_ERROR);
        $dependencies = $package['dependencies'] ?? [];

        $this->assertArrayHasKey('motion-v', $dependencies);
        $this->assertSame('^2.3.0', $dependencies['motion-v']);
        $this->assertArrayHasKey('node_modules/motion-v', $lock['packages'] ?? []);
        $this->assertSame('2.3.0', $lock['packages']['node_modules/motion-v']['version'] ?? null);

        foreach (['@formkit/auto-animate', 'gsap', 'auto-animate'] as $forbiddenDependency) {
            $this->assertArrayNotHasKey($forbiddenDependency, $dependencies, 'Forbidden animation dependency present: '.$forbiddenDependency);
            $this->assertArrayNotHasKey('node_modules/'.$forbiddenDependency, $lock['packages'] ?? [], 'Forbidden animation lock entry present: '.$forbiddenDependency);
        }
    }

    public function test_app_shell_keeps_ziggy_theme_loader_and_no_global_motion_plugin(): void
    {
        $app = (string) file_get_contents(base_path('resources/js/app.js'));

        $this->assertStringContainsString("import { ZiggyVue } from '../../vendor/tightenco/ziggy';", $app);
        $this->assertStringContainsString("defaultTheme: 'doomsday'", $app);
        $this->assertStringContainsString('h(AppNavigationLoader)', $app);
        $this->assertLessThan(strpos($app, '.use(plugin)'), strpos($app, '.use(ZiggyVue)'));

        foreach (['MotionPlugin', '@formkit/auto-animate', 'AutoAnimate', 'auto-animate', 'gsap', 'unplugin-vue-components'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $app, 'Forbidden global animation/app-shell pattern found: '.$forbidden);
        }
    }

    public function test_doomsday_motion_preset_layer_is_centralized_typed_and_reduced_motion_aware(): void
    {
        $motion = (string) file_get_contents(base_path('resources/js/animations/doomsdayMotion.ts'));

        foreach ([
            'doomsdayEase',
            'fastTransition',
            'panelTransition',
            'staggerTransition',
            'fadeUp',
            'fadeIn',
            'panelReveal',
            'cardReveal',
            'tabContent',
            'skeletonFade',
            'selectedAccentPulse',
            'ctaPress',
            'useDoomsdayReducedMotion',
            'resolveMotionPreset',
            'reducedMotionFallback',
            'cardStaggerDelay',
        ] as $requiredExport) {
            $this->assertStringContainsString($requiredExport, $motion, 'Missing motion preset export/helper: '.$requiredExport);
        }

        $this->assertStringContainsString("import { useReducedMotion } from 'motion-v';", $motion);
        $this->assertStringContainsString('satisfies DoomsdayMotionPreset', $motion);
        $this->assertStringNotContainsString(' any', $motion);

        foreach (['height:', 'width:', 'gridTemplate', 'grid-template', 'layout:'] as $forbiddenLayoutAnimation) {
            $this->assertStringNotContainsString($forbiddenLayoutAnimation, $motion, 'Layout animation token found in preset layer: '.$forbiddenLayoutAnimation);
        }
    }

    public function test_animated_surfaces_use_local_motion_imports_and_preserve_ui_contracts(): void
    {
        $home = $this->readSource('resources/js/Pages/Doomsday/Home.vue');
        $hero = $this->readSource('resources/js/Components/Doomsday/HeroSection.vue');
        $list = $this->readSource('resources/js/Components/Doomsday/CountdownList.vue');
        $card = $this->readSource('resources/js/Components/Doomsday/CountdownCard.vue');
        $selected = $this->readSource('resources/js/Components/Doomsday/SelectedMasterDetail.vue');
        $mobile = $this->readSource('resources/js/Components/Doomsday/MobileDetailView.vue');
        $detail = $this->readSource('resources/js/Components/Doomsday/DetailPanel.vue');
        $sidebar = $this->readSource('resources/js/Components/Doomsday/SidebarCards.vue');
        $carousel = $this->readSource('resources/js/Components/Doomsday/LatestNewsCarousel.vue');

        foreach ([$home, $hero, $list, $card, $selected, $mobile, $detail] as $source) {
            $this->assertStringContainsString("from 'motion-v'", $source);
            $this->assertStringContainsString('@/animations/doomsdayMotion', $source);
        }

        $this->assertStringContainsString('AnimatePresence mode="wait"', $home);
        $this->assertStringContainsString('@select="selection.selectCountdown"', $home);
        $this->assertStringContainsString('@close="selection.closeSelectedCountdown"', $home);
        $this->assertStringContainsString('<motion.h1', $hero);
        $this->assertStringContainsString('<motion.div', $list);

        $this->assertStringContainsString('import { Card } from \'@simone-bianco/vue-ui-components\';', $card);
        $this->assertStringContainsString('const selectedCardMotion = computed(() => (isSelected.value ? disabledMotionTarget(selectedCardActive, reducedMotion.value) : selectedCardIdle));', $card);
        $this->assertStringNotContainsString('const selectedCardMotion = computed(() => (isSelected.value ? selectedCardActive : selectedCardIdle));', $card);
        $this->assertStringContainsString('role="button"', $card);
        $this->assertStringContainsString(':aria-pressed="isSelected"', $card);
        $this->assertStringContainsString('@keydown.enter="emit(\'select\', countdown)"', $card);
        $this->assertStringContainsString('@keydown.space.prevent="emit(\'select\', countdown)"', $card);
        $this->assertStringContainsString('<span v-if="isSelected" v-motion', $card);
        $this->assertStringContainsString('pointer-events-none absolute inset-y-0 left-0 z-20 w-[2px] bg-ui-primary', $card);
        $this->assertStringContainsString('<CountdownTimer :target-date="countdown.timer.target_date" :compact="true" :dense="compact" />', $card);

        $this->assertStringContainsString('h-[calc(100dvh-64px)] min-h-0', $selected);
        $this->assertStringNotContainsString('h-[calc(100vh-64px)]', $selected);
        $this->assertStringContainsString('doomsday-scrollbar grid min-h-0 min-w-0 content-start gap-5 overflow-y-auto', $selected);
        $this->assertStringContainsString('min-h-screen bg-black pb-24 lg:hidden', $mobile);
        $this->assertStringContainsString('doomsday-scrollbar grid min-h-0 min-w-0 flex-1 auto-rows-max gap-5 overflow-y-auto overscroll-contain', $detail);
        $this->assertStringContainsString('tabContentKey', $detail);
        $this->assertStringContainsString('<StatisticsSection v-if="statisticsSection" :section="statisticsSection" />', $detail);

        $this->assertStringContainsString('<LatestNewsCarousel :items="sidebar.latest_news" />', $sidebar);
        $this->assertStringContainsString('<PublicSignalActivityCard :activity="sidebar.signal_activity" />', $sidebar);
        $this->assertStringContainsString("import { AnimatePresence, motion } from 'motion-v';", $carousel);
        $this->assertStringContainsString('useDoomsdayReducedMotion', $carousel);
        $this->assertStringContainsString('!reducedMotion.value', $carousel);
        $this->assertStringContainsString('const navigationDirection = ref<SlideDirection>(1)', $carousel);
        $this->assertStringContainsString(': { opacity: 0, x: navigationDirection.value * 56 }', $carousel);
        $this->assertStringContainsString(': { opacity: 0, x: navigationDirection.value * -56 }', $carousel);
        $this->assertStringContainsString('const slideInitial = computed(() => reducedMotion.value', $carousel);
        $this->assertStringContainsString('? { opacity: 0 }', $carousel);
        $this->assertStringContainsString('<AnimatePresence mode="wait" :initial="false">', $carousel);
        $this->assertStringContainsString('<motion.article', $carousel);
        $this->assertStringContainsString(':initial="slideInitial"', $carousel);
        $this->assertStringContainsString(':exit="slideExit"', $carousel);
        $this->assertStringContainsString("document.addEventListener('visibilitychange', handleVisibilityChange)", $carousel);
        $this->assertStringContainsString("document.removeEventListener('visibilitychange', handleVisibilityChange)", $carousel);
        $this->assertStringContainsString('@focusin="focusPaused = true"', $carousel);
        $this->assertStringContainsString('@mouseenter="hoverPaused = true"', $carousel);
        $this->assertStringNotContainsString('v-show', $carousel);
    }

    public function test_motion_changes_do_not_introduce_forbidden_navigation_native_controls_or_layout_animation(): void
    {
        $runtime = $this->readSources([
            'resources/js/Pages/Doomsday/Home.vue',
            'resources/js/Components/Doomsday/HeroSection.vue',
            'resources/js/Components/Doomsday/CountdownList.vue',
            'resources/js/Components/Doomsday/CountdownCard.vue',
            'resources/js/Components/Doomsday/SelectedMasterDetail.vue',
            'resources/js/Components/Doomsday/MobileDetailView.vue',
            'resources/js/Components/Doomsday/DetailPanel.vue',
            'resources/js/Components/Doomsday/SidebarCards.vue',
            'resources/js/Components/Doomsday/LatestNewsCarousel.vue',
            'resources/js/Components/Doomsday/PublicSignalActivityCard.vue',
            'resources/js/Composables/useDoomsdaySelection.ts',
            'resources/js/Composables/useDoomsdayLazySections.ts',
        ]);

        foreach (['router.visit', 'router.reload', 'router.prefetch', 'history.pushState', 'window.location', 'window.fetch', '<motion.button', 'motion.button', '<button', 'icon="chevron', 'icon="arrow'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $runtime, 'Forbidden motion regression pattern found: '.$forbidden);
        }

        foreach (['height:', 'width:', 'gridTemplate', 'grid-template'] as $forbiddenLayoutAnimation) {
            $this->assertStringNotContainsString($forbiddenLayoutAnimation, $runtime, 'Forbidden layout animation pattern found: '.$forbiddenLayoutAnimation);
        }

        $selection = $this->readSource('resources/js/Composables/useDoomsdaySelection.ts');
        $lazy = $this->readSource('resources/js/Composables/useDoomsdayLazySections.ts');
        $this->assertStringContainsString('axios.get<{ data: CountdownOverviewData }>', $selection);
        $this->assertStringContainsString("route('countdowns.data.overview'", $selection);
        $this->assertStringContainsString('pendingSelectedSlug.value === requestedSlug', $selection);
        $this->assertStringContainsString('currentLocale.value === requestedLocale', $selection);
        $this->assertStringContainsString('axios.get<{ data: SectionDataByKey[K] }>', $lazy);
        $this->assertStringContainsString('route(sectionRouteByKey[key]', $lazy);
        $this->assertStringContainsString('countdownSlug.value === requestedSlug', $lazy);
        $this->assertStringContainsString('initialSection?.countdown_slug === countdownSlug.value', $lazy);
    }

    private function readSource(string $path): string
    {
        return (string) file_get_contents(base_path($path));
    }

    /**
     * @param  list<string>  $paths
     */
    private function readSources(array $paths): string
    {
        $content = '';

        foreach ($paths as $path) {
            $content .= "\n/* {$path} */\n".$this->readSource($path);
        }

        return $content;
    }
}
