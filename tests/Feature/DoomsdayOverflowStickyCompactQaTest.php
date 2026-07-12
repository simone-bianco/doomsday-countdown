<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class DoomsdayOverflowStickyCompactQaTest extends TestCase
{
    public function test_fixed_compact_header_has_layout_compensation_and_doomsday_scrollbar(): void
    {
        $header = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SiteHeader.vue'));
        $layout = (string) file_get_contents(base_path('resources/js/Layouts/PublicLayout.vue'));
        $css = (string) file_get_contents(base_path('resources/css/app.css'));

        $this->assertStringContainsString('fixed inset-x-0 top-0 z-50', $header);
        $this->assertStringContainsString('px-4 py-2 sm:px-7', $header);
        $this->assertStringContainsString('pb-2 pt-1', $header);
        $this->assertStringNotContainsString('sticky top-0', $header);
        $this->assertStringNotContainsString('py-5', $header);

        $this->assertStringContainsString('doomsday-scrollbar relative min-h-screen', $layout);
        $this->assertStringContainsString("hideMobileHeader ? 'lg:pt-[64px]' : 'pt-[64px]'", $layout);
        $this->assertStringContainsString('scrollbar-width: thin', $css);
        $this->assertStringContainsString('scrollbar-color: rgba(255, 42, 35', $css);
        $this->assertStringContainsString('.doomsday-scrollbar::-webkit-scrollbar', $css);
        $this->assertStringContainsString('.doomsday-scrollbar::-webkit-scrollbar-thumb', $css);
    }

    public function test_countdown_cards_are_always_stacked_compact_and_without_estimated_labels(): void
    {
        $card = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownCard.vue'));
        $cardImage = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownCardImage.vue'));

        $this->assertStringContainsString("'grid min-w-0 grid-cols-1 gap-0'", $card);
        $this->assertStringContainsString('doomsday-card relative h-fit self-start min-w-0 overflow-hidden', $card);
        $this->assertStringContainsString('v-if="isSelected"', $card);
        $this->assertStringContainsString('absolute inset-y-0 left-0 z-20 w-[2px] bg-ui-primary', $card);
        $this->assertStringContainsString('border-t border-white/10', $card);
        $this->assertStringContainsString('py-2.5', $card);
        $this->assertStringContainsString('py-3', $card);
        $this->assertStringContainsString('<CountdownTimer :target-date="countdown.timer.target_date" :compact="true" :dense="compact" />', $card);

        foreach (['grid-cols-[minmax(0,1fr)_minmax(', 'sm:grid-cols-[', 'xl:grid-cols-[', '2xl:grid-cols-[', 'border-l', 'ChevronRight', '<Link', 'prefetch cache-for="2m"', 'router.visit', 'router.reload', 'router.prefetch', 'countdown.icon', 'countdown.timer.estimated_label', 'icon="'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $card, 'CountdownCard regression: '.$forbidden);
        }

        $this->assertStringContainsString('h-[220px]', $cardImage);
        $this->assertStringContainsString('sm:h-[240px]', $cardImage);
        $this->assertStringContainsString('xl:h-[260px]', $cardImage);
        $this->assertStringContainsString('h-[150px]', $cardImage);
        $this->assertStringContainsString('sm:h-[160px]', $cardImage);
        $this->assertStringContainsString('object-cover object-center', $cardImage);
        $this->assertStringNotContainsString('h-[300px]', $cardImage);
        $this->assertStringNotContainsString('h-[330px]', $cardImage);
        $this->assertStringNotContainsString('h-[360px]', $cardImage);
        $this->assertStringNotContainsString('aspect-ratio="100%"', $cardImage);
    }

    public function test_detail_panel_uses_compact_header_internal_scroll_and_full_width_toggle_contract(): void
    {
        $detail = (string) file_get_contents(base_path('resources/js/Components/Doomsday/DetailPanel.vue'));
        $selectedMaster = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SelectedMasterDetail.vue'));

        $this->assertStringContainsString('readonly expanded?: boolean', $detail);
        $this->assertStringContainsString('toggleExpanded: []', $detail);
        $this->assertStringContainsString('Maximize2', $detail);
        $this->assertStringContainsString('Minimize2', $detail);
        $this->assertStringContainsString('expanded ? Minimize2 : Maximize2', $detail);
        $this->assertStringContainsString("{{ expanded ? 'Collapse' : 'Expand' }}", $detail);
        $this->assertStringContainsString('doomsday-card flex h-full min-h-0 flex-col overflow-hidden', $detail);
        $this->assertStringContainsString('grid min-w-0 shrink-0 gap-4', $detail);
        $this->assertStringContainsString('p-4 sm:p-5', $detail);
        $this->assertStringContainsString('text-xl leading-tight text-white sm:text-2xl 2xl:text-3xl', $detail);
        $this->assertStringContainsString('doomsday-scrollbar flex shrink-0', $detail);
        $this->assertStringContainsString('doomsday-scrollbar grid min-h-0 min-w-0 flex-1 auto-rows-max gap-5 overflow-y-auto overscroll-contain scroll-pb-8', $detail);
        $this->assertStringContainsString('p-4 pb-8 pr-2', $detail);
        $this->assertStringContainsString('sm:scroll-pb-10 sm:p-5 sm:pb-10 sm:pr-3', $detail);

        foreach (['sm:grid-cols-[1fr_auto]', 'min-w-72', 'countdown.timer.estimated_label', 'absolute right-5 top-5'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $detail, 'DetailPanel regression: '.$forbidden);
        }

        $this->assertStringContainsString('const isDetailExpanded = ref(false)', $selectedMaster);
        $this->assertStringContainsString('h-[calc(100dvh-64px)] min-h-0', $selectedMaster);
        $this->assertStringNotContainsString('h-[calc(100vh-64px)]', $selectedMaster);
        $this->assertStringContainsString('min-h-0 min-w-0 self-stretch', $selectedMaster);
        $this->assertStringContainsString("isDetailExpanded ? 'grid-cols-1'", $selectedMaster);
        $this->assertStringContainsString('v-if="!isDetailExpanded"', $selectedMaster);
        $this->assertStringContainsString(':expanded="isDetailExpanded"', $selectedMaster);
        $this->assertStringContainsString('@toggle-expanded="isDetailExpanded = !isDetailExpanded"', $selectedMaster);
        $this->assertStringNotContainsString('xl:sticky', $selectedMaster);
    }

    public function test_key_indicators_and_evidence_are_width_and_viewport_safe(): void
    {
        $indicators = (string) file_get_contents(base_path('resources/js/Components/Doomsday/KeyIndicatorsCard.vue'));
        $evidence = (string) file_get_contents(base_path('resources/js/Components/Doomsday/VisualizationEvidence.vue'));

        $this->assertStringContainsString('doomsday-card min-w-0 rounded-xl', $indicators);
        $this->assertStringContainsString('grid-cols-[minmax(0,1fr)_auto]', $indicators);
        $this->assertStringContainsString('sm:grid-cols-[minmax(0,1fr)_auto_minmax(56px,90px)]', $indicators);
        $this->assertStringContainsString('min-w-0 break-words text-sm', $indicators);
        $this->assertStringContainsString('whitespace-nowrap text-sm text-white', $indicators);
        $this->assertStringContainsString('col-span-2 flex h-5 min-w-0 max-w-full items-end gap-1 overflow-hidden sm:col-span-1', $indicators);
        $this->assertStringContainsString('mt-5 min-w-0 pb-1', $indicators);

        $this->assertStringContainsString('min-w-0 space-y-3 border-t', $evidence);
        $this->assertStringContainsString('class="min-w-0 break-words"', $evidence);
        $this->assertStringContainsString('inline-block max-w-full break-words', $evidence);
        $this->assertStringNotContainsString('break-all', $evidence);
    }

    public function test_home_sidebar_and_auto_fit_surfaces_are_width_safe_without_obsolete_widgets(): void
    {
        $home = (string) file_get_contents(base_path('resources/js/Pages/Doomsday/Home.vue'));
        $sidebar = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SidebarCards.vue'));
        $carousel = (string) file_get_contents(base_path('resources/js/Components/Doomsday/LatestNewsCarousel.vue'));
        $activity = (string) file_get_contents(base_path('resources/js/Components/Doomsday/PublicSignalActivityCard.vue'));
        $overview = (string) file_get_contents(base_path('resources/js/Components/Doomsday/OverviewSection.vue'));
        $mobile = (string) file_get_contents(base_path('resources/js/Components/Doomsday/MobileDetailView.vue'));
        $detail = (string) file_get_contents(base_path('resources/js/Components/Doomsday/DetailPanel.vue'));
        $card = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownCard.vue'));

        $this->assertStringContainsString('lg:grid-cols-[minmax(0,1fr)_560px]', $home);
        $this->assertStringContainsString('2xl:grid-cols-[minmax(0,1fr)_600px]', $home);
        $this->assertStringContainsString(':sidebar="page.sidebar"', $home);
        $this->assertStringContainsString('grid min-w-0 content-start gap-5', $sidebar);
        $this->assertStringContainsString('<LatestNewsCarousel :items="sidebar.latest_news" />', $sidebar);
        $this->assertStringContainsString('<PublicSignalActivityCard :activity="sidebar.signal_activity" />', $sidebar);
        $this->assertStringContainsString('doomsday-card min-w-0 overflow-hidden rounded-xl', $carousel);
        $this->assertStringContainsString("const slideViewportClass = 'relative min-h-[32rem] overflow-hidden sm:min-h-[35.5rem]'", $carousel);
        $this->assertStringContainsString("const slideSurfaceClass = 'grid min-h-[32rem]", $carousel);
        $this->assertStringContainsString('relative aspect-video overflow-hidden bg-black', $carousel);
        $this->assertStringContainsString('line-clamp-2 min-h-[3.5rem]', $carousel);
        $this->assertStringContainsString('line-clamp-3 min-h-[4.5rem]', $carousel);
        $this->assertStringContainsString("const paginationClass = 'flex min-h-11", $carousel);
        $this->assertStringContainsString('pointer-events-none absolute inset-x-0 top-0 z-20 aspect-video', $carousel);
        $this->assertStringContainsString('h-11 min-h-11 w-11 min-w-11', $carousel);
        $this->assertStringContainsString('doomsday-card min-w-0 rounded-xl', $activity);
        $this->assertStringContainsString('grid-cols-[repeat(auto-fit,minmax(120px,1fr))]', $overview);
        $this->assertStringContainsString('grid-cols-[repeat(auto-fit,minmax(96px,1fr))]', $mobile);
        $this->assertStringNotContainsString('grid-cols-3', $overview.$mobile);

        foreach (['18.0', '/100', 'Global Risk Index', 'Daily update', 'page.countdowns[0]'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $home.$sidebar.$activity, 'Obsolete sidebar surface: '.$forbidden);
        }
        foreach (['countdown.timer.estimated_label', 'Estimated target'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $card.$detail.$mobile, 'Estimated label surface regression: '.$forbidden);
        }
    }

    public function test_overflow_visual_fix_preserves_no_url_selection_runtime_contract(): void
    {
        $selection = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdaySelection.ts'));
        $lazy = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdayLazySections.ts'));
        $runtime = $selection.$lazy;

        $this->assertStringContainsString('axios.get<{ data: CountdownOverviewData }>', $selection);
        $this->assertStringContainsString("route('countdowns.data.overview'", $selection);
        $this->assertStringContainsString('pendingSelectedSlug.value === requestedSlug', $selection);
        $this->assertStringContainsString('axios.get<{ data: SectionDataByKey[K] }>', $lazy);
        $this->assertStringContainsString('route(sectionRouteByKey[key]', $lazy);
        $this->assertStringContainsString('countdownSlug.value === requestedSlug', $lazy);

        foreach (['router.visit', 'router.reload', 'router.prefetch', 'history.pushState', 'window.location', 'window.fetch'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $runtime, 'URL-changing runtime regression: '.$forbidden);
        }
    }
}
