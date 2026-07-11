<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class DoomsdayPublicCopyTest extends TestCase
{
    public function test_public_doomsday_sources_do_not_contain_prohibited_visible_copy(): void
    {
        $paths = [
            __DIR__.'/../../resources/js/Pages/Doomsday/Home.vue',
            __DIR__.'/../../resources/js/Pages/Doomsday/About.vue',
            __DIR__.'/../../resources/js/Layouts/PublicLayout.vue',
            __DIR__.'/../../resources/js/Components/Doomsday',
            __DIR__.'/../../resources/js/i18n/index.ts',
            __DIR__.'/../../database/seeders/DoomsdaySeeder.php',
            __DIR__.'/../../database/patches/countdowns/taiwan_invasion/_shared.php',
            ...glob(__DIR__.'/../../database/patches/countdowns/taiwan_invasion/*/patch.php'),
            ...glob(__DIR__.'/../../database/patches/countdowns/taiwan_invasion/*/data.php'),
        ];
        $content = '';
        foreach ($paths as $path) {
            if (is_dir($path)) {
                foreach (glob($path.'/*.vue') ?: [] as $file) {
                    $content .= (string) file_get_contents($file);
                }

                continue;
            }
            $content .= (string) file_get_contents($path);
        }
        $this->assertStringNotContainsString('Artificial Intelligence', $content);
        $this->assertStringNotContainsString('OpenAI', $content);
        $this->assertStringNotContainsString('AI ', $content);
        $this->assertStringNotContainsString('AI<', $content);
        $this->assertStringNotContainsString('Agent Debug', $content);
        $this->assertStringNotContainsString('Backoffice', $content);
        $this->assertStringNotContainsString('Login', $content);
        $this->assertStringNotContainsString('sample data', strtolower($content));
        $this->assertStringNotContainsString('sample scenario', strtolower($content));
        $this->assertStringNotContainsString('dati campione', strtolower($content));
        $this->assertStringNotContainsString('example.org', strtolower($content));
        $this->assertStringNotContainsString('daily monitor', strtolower($content));
        $this->assertStringNotContainsString('global desk', strtolower($content));
    }

    public function test_public_layout_menu_contains_only_home_and_about_links(): void
    {
        $layout = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/SiteHeader.vue');
        $this->assertStringContainsString('homeUrl', $layout);
        $this->assertStringContainsString('aboutUrl', $layout);
        $this->assertStringContainsString('fixed inset-x-0 top-0', $layout);
        $this->assertStringContainsString('pb-2 pt-1', $layout);
        $this->assertStringNotContainsString('sticky top-0', $layout);
        $this->assertStringNotContainsString('py-5', $layout);
        $this->assertStringNotContainsString('href="/login"', $layout);
        $this->assertStringNotContainsString('backoffice', strtolower($layout));
    }

    public function test_doomsday_components_do_not_pass_string_icons_to_ui_buttons(): void
    {
        $componentsPath = __DIR__.'/../../resources/js/Components/Doomsday';
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($componentsPath));
        foreach ($iterator as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'vue') {
                continue;
            }
            $content = (string) file_get_contents($file->getPathname());
            $this->assertDoesNotMatchRegularExpression(
                '/\\sicon="[a-z][a-z0-9-]*"/',
                $content,
                'Doomsday component uses a string icon instead of a lucide component binding: '.$file->getPathname(),
            );
        }
    }

    public function test_countdown_card_uses_art_only_image_overlay_widget(): void
    {
        $cardImagePath = __DIR__.'/../../resources/js/Components/Doomsday/CountdownCardImage.vue';
        $this->assertFileExists($cardImagePath);
        $card = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/CountdownCard.vue');
        $cardImage = (string) file_get_contents($cardImagePath);
        $this->assertStringContainsString("import CountdownCardImage from './CountdownCardImage.vue';", $card);
        $this->assertStringContainsString('<CountdownCardImage', $card);
        $this->assertStringContainsString(':image-url="countdown.image_url"', $card);
        $this->assertStringContainsString(':title="countdown.title"', $card);
        $this->assertStringContainsString(':subtitle="countdown.summary"', $card);
        $this->assertStringContainsString(':compact="compact"', $card);
        $this->assertStringNotContainsString('countdown.icon', $card);
        $this->assertStringNotContainsString('import { Card, Image }', $card);
        $this->assertDoesNotMatchRegularExpression('/<Image\\b/', $card);
        $this->assertStringNotContainsString('<Link', $card);
        $this->assertStringNotContainsString('prefetch cache-for="2m"', $card);
        $this->assertStringContainsString("import { Image } from '@simone-bianco/vue-ui-components';", $cardImage);
        $this->assertStringContainsString('readonly title: string', $cardImage);
        $this->assertStringContainsString('readonly subtitle: string', $cardImage);
        $this->assertStringContainsString('absolute inset-x-0 bottom-0', $cardImage);
        $this->assertStringContainsString('doomsday-display', $cardImage);
        $this->assertStringContainsString('h-[220px]', $cardImage);
        $this->assertStringContainsString('sm:h-[240px]', $cardImage);
        $this->assertStringContainsString('xl:h-[260px]', $cardImage);
        $this->assertStringContainsString('h-[150px]', $cardImage);
        $this->assertStringNotContainsString('h-[300px]', $cardImage);
        $this->assertStringContainsString('line-clamp-2', $cardImage);
        $this->assertStringNotContainsString('aspect-ratio="100%"', $cardImage);
        $this->assertStringContainsString('font-bold', $cardImage);
        $this->assertStringContainsString('tracking-[0.06em]', $cardImage);
        $this->assertStringContainsString('p-3 sm:p-4', $cardImage);
        $this->assertStringContainsString('p-4 sm:p-5', $cardImage);
        $this->assertStringNotContainsString('icon', strtolower($cardImage));
    }

    public function test_mobile_detail_image_is_in_flow_and_global_background_can_be_hidden(): void
    {
        $layout = (string) file_get_contents(__DIR__.'/../../resources/js/Layouts/PublicLayout.vue');
        $home = (string) file_get_contents(__DIR__.'/../../resources/js/Pages/Doomsday/Home.vue');
        $mobileDetail = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/MobileDetailView.vue');
        $mobileSkeleton = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/MobileDetailSkeleton.vue');
        $this->assertStringContainsString('readonly hideBackground?: boolean', $layout);
        $this->assertStringContainsString('hideBackground: false', $layout);
        $this->assertStringContainsString('v-if="!hideBackground"', $layout);
        $this->assertStringNotContainsString(':hide-background="selection.detailOpen.value"', $home);
        foreach ([$mobileDetail, $mobileSkeleton] as $source) {
            $this->assertStringContainsString('relative h-[220px]', $source);
            $this->assertStringContainsString('sm:h-[260px]', $source);
            $this->assertStringContainsString('mt-4 px-4', $source);
            $this->assertStringNotContainsString('-mt-8', $source);
            $this->assertDoesNotMatchRegularExpression('/<div class="[^\"]*fixed[^\"]*h-\\[(220|260)px\\]/', $source);
        }
        $this->assertStringContainsString('object-cover object-center', $mobileDetail);
    }

    public function test_selection_ux_uses_axios_ziggy_local_state_and_stale_section_guard(): void
    {
        $home = (string) file_get_contents(__DIR__.'/../../resources/js/Pages/Doomsday/Home.vue');
        $masterDetail = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/SelectedMasterDetail.vue');
        $mobileDetail = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/MobileDetailView.vue');
        $detail = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/DetailPanel.vue');
        $countdownList = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/CountdownList.vue');
        $countdownCard = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/CountdownCard.vue');
        $selection = (string) file_get_contents(__DIR__.'/../../resources/js/Composables/useDoomsdaySelection.ts');
        $lazy = (string) file_get_contents(__DIR__.'/../../resources/js/Composables/useDoomsdayLazySections.ts');
        $timer = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/CountdownTimer.vue');
        $cardImage = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/CountdownCardImage.vue');
        $app = (string) file_get_contents(__DIR__.'/../../resources/js/app.js');
        $loader = (string) file_get_contents(__DIR__.'/../../resources/js/Components/App/AppNavigationLoader.vue');
        $sidebar = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/SidebarCards.vue');
        $this->assertStringContainsString('readonly selected_countdown?: CountdownOverviewData | null', $home);
        $this->assertStringContainsString('useDoomsdaySelection', $home);
        $this->assertStringContainsString('selection.selectedCountdown.value', $home);
        $this->assertStringContainsString('MobileDetailSkeleton', $home);
        $this->assertStringContainsString('SelectedMasterDetail', $home);
        $this->assertStringNotContainsString('<DetailPanel', $home);
        $this->assertStringContainsString('axios.get<{ data: CountdownOverviewData }>', $selection);
        $this->assertStringContainsString("route('countdowns.data.overview'", $selection);
        $this->assertStringContainsString('activeSelectedSlug.value === countdown.slug', $selection);
        $this->assertStringContainsString('closeSelectedCountdown', $selection);
        $this->assertStringContainsString('selectedCountdown.value = null', $selection);
        $this->assertStringContainsString('pendingSelectedSlug.value === requestedSlug', $selection);
        foreach (['router.visit', 'router.reload', 'history.pushState', 'window.location'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $selection);
        }
        $this->assertStringContainsString('defineEmits', $countdownList);
        $this->assertStringContainsString('@select="emit', $countdownList);
        $this->assertStringContainsString('items-start max-w-[1760px]', $home);
        $this->assertStringContainsString('content-start items-start', $home);
        $this->assertStringContainsString('content-start items-start', $countdownList);
        $this->assertStringContainsString('@click="emit', $countdownCard);
        $this->assertStringNotContainsString('prefetch cache-for="2m"', $countdownCard);
        $this->assertStringNotContainsString('<Link', $countdownCard);
        $this->assertStringContainsString('readonly selectedSlug?: string | null', $countdownCard);
        $this->assertStringContainsString('readonly pendingSlug?: string | null', $countdownCard);
        $this->assertStringContainsString('h-fit self-start', $countdownCard);
        $this->assertStringContainsString('min-w-0', $countdownCard);
        $this->assertStringContainsString("'grid min-w-0 grid-cols-1 gap-0'", $countdownCard);
        $this->assertStringContainsString('border-t border-white/10', $countdownCard);
        $this->assertStringNotContainsString('grid-cols-[minmax(0,1fr)_minmax(', $countdownCard);
        $this->assertStringNotContainsString('sm:grid-cols-[minmax(0,1fr)_minmax', $countdownCard);
        $this->assertStringNotContainsString('xl:grid-cols-[minmax(0,1fr)_minmax', $countdownCard);
        $this->assertStringNotContainsString('2xl:grid-cols-[minmax(0,1fr)_minmax', $countdownCard);
        $this->assertStringNotContainsString('border-l', $countdownCard);
        $this->assertStringNotContainsString('ChevronRight', $countdownCard);
        $this->assertStringNotContainsString('255px', $countdownCard);
        $this->assertStringNotContainsString('countdown.timer.estimated_label', $countdownCard);
        $this->assertStringNotContainsString('overflow-hidden', $timer);
        $this->assertStringContainsString('clamp(', $timer);
        $this->assertStringContainsString('SEC', $timer);
        $this->assertStringContainsString('tabular-nums', $timer);
        $this->assertStringContainsString('line-clamp-2', $cardImage);
        $this->assertStringContainsString('font-bold', $cardImage);
        $this->assertStringNotContainsString('minmax(500px', $masterDetail);
        $this->assertStringNotContainsString('minmax(720px', $masterDetail);
        $this->assertStringContainsString('grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]', $masterDetail);
        $this->assertStringContainsString('isDetailExpanded', $masterDetail);
        $this->assertStringContainsString("isDetailExpanded ? 'grid-cols-1'", $masterDetail);
        $this->assertStringContainsString('v-if="!isDetailExpanded"', $masterDetail);
        $this->assertStringContainsString('@toggle-expanded="isDetailExpanded = !isDetailExpanded"', $masterDetail);
        $this->assertStringContainsString('min-w-0', $masterDetail);
        $this->assertStringNotContainsString('xl:sticky', $masterDetail);
        $this->assertStringNotContainsString('sticky top-28', $masterDetail);
        $this->assertStringContainsString(':compact="true"', $masterDetail);
        $this->assertStringContainsString('@close="emit', $masterDetail);
        $this->assertStringContainsString('ChevronLeft', $mobileDetail);
        $this->assertStringContainsString('Share2', $mobileDetail);
        $this->assertStringContainsString('CountdownTimer', $mobileDetail);
        $this->assertStringContainsString('DoomsdaySkeletonBlock', $mobileDetail);
        $this->assertStringContainsString('InitiativesSection', $mobileDetail);
        $this->assertStringContainsString('fixed inset-x-0 bottom-0', $mobileDetail);
        $newsSection = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/NewsSection.vue');
        $initiativesSection = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/InitiativesSection.vue');
        $previewCard = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/ContentPreviewCard.vue');
        $chart = (string) file_get_contents(__DIR__.'/../../resources/js/Components/Doomsday/VisualizationChart.vue');
        $this->assertStringContainsString(':href="item.source_url"', $newsSection);
        $this->assertStringContainsString('grid grid-cols-1 gap-4', $newsSection);
        $this->assertStringNotContainsString('sm:grid-cols-2', $newsSection);
        $this->assertStringContainsString(':href="item.url"', $initiativesSection);
        $this->assertStringContainsString('grid grid-cols-1 gap-4', $initiativesSection);
        $this->assertStringNotContainsString('sm:grid-cols-2', $initiativesSection);
        $this->assertStringContainsString(':href="href || undefined"', $previewCard);
        $this->assertStringContainsString(":target=\"href ? '_blank' : undefined\"", $previewCard);
        $this->assertStringContainsString(":rel=\"href ? 'noopener noreferrer' : undefined\"", $previewCard);
        $this->assertStringContainsString('ExternalLink', $previewCard);
        $this->assertStringContainsString('RawSeries', $chart);
        $this->assertStringContainsString('min-w-[600px]', $chart);
        $this->assertStringContainsString('h-[22rem]', $chart);
        $this->assertStringContainsString('pb-6', $chart);
        $this->assertStringContainsString('paddedMax', $chart);
        $this->assertStringNotContainsString('Math.max(100', $chart);
        $this->assertStringNotContainsString('countdown.timer.estimated_label', $detail);
        $this->assertStringNotContainsString('countdown.timer.estimated_label', $mobileDetail);
        $this->assertStringContainsString('All countdowns', $detail);
        $this->assertStringContainsString('Maximize2', $detail);
        $this->assertStringContainsString('Minimize2', $detail);
        $this->assertStringContainsString('readonly expanded?: boolean', $detail);
        $this->assertStringContainsString('toggleExpanded', $detail);
        $this->assertStringContainsString('flex h-full min-h-0 flex-col overflow-hidden', $detail);
        $this->assertStringContainsString('auto-rows-max', $detail);
        $this->assertStringContainsString('overscroll-contain', $detail);
        $this->assertStringContainsString('h-[calc(100vh-64px)] min-h-0', $masterDetail);
        $this->assertStringContainsString('self-stretch', $masterDetail);
        $this->assertStringContainsString('doomsday-scrollbar', $detail);
        $this->assertStringContainsString('overflow-y-auto', $detail);
        $this->assertStringContainsString('shrink-0', $detail);
        $this->assertStringNotContainsString('sm:grid-cols-[1fr_auto]', $detail);
        $this->assertStringNotContainsString('min-w-72', $detail);
        $this->assertStringNotContainsString('absolute right-5 top-5', $detail);
        $this->assertStringContainsString('axios.get<{ data: SectionDataByKey[K] }>', $lazy);
        $this->assertStringContainsString('route(sectionRouteByKey[key]', $lazy);
        $this->assertStringContainsString('countdownSlug.value === requestedSlug', $lazy);
        $this->assertStringNotContainsString('router.reload', $lazy);
        $this->assertStringNotContainsString('router.prefetch', $lazy);
        $this->assertStringNotContainsString('window.fetch', $lazy);
        $this->assertStringContainsString("import { ZiggyVue } from '../../vendor/tightenco/ziggy';", $app);
        $this->assertLessThan(strpos($app, '.use(plugin)'), strpos($app, '.use(ZiggyVue)'));
        $this->assertStringContainsString('h(AppNavigationLoader)', $app);
        $this->assertStringContainsString('block w-full sm:w-fit', $sidebar);
        $this->assertStringContainsString('size="md"', $sidebar);
        $this->assertStringContainsString('doomsday-display w-full border-ui-primary/50 bg-ui-primary/10', $sidebar);
        $this->assertStringContainsString('hover:shadow-[0_0_26px_rgba(255,42,35,0.28)]', $sidebar);
        $this->assertStringContainsString('group-hover:translate-x-0.5', $sidebar);
        $this->assertStringContainsString('router.on(\'start\'', $loader);
        $this->assertStringContainsString('fixed inset-x-0 top-0', $loader);
    }
}
