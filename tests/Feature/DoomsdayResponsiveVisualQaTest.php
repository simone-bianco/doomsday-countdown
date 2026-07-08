<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class DoomsdayResponsiveVisualQaTest extends TestCase
{
    public function test_public_layout_keeps_global_background_when_selected_detail_is_open(): void
    {
        $layout = (string) file_get_contents(base_path('resources/js/Layouts/PublicLayout.vue'));
        $siteHeader = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SiteHeader.vue'));
        $home = (string) file_get_contents(base_path('resources/js/Pages/Doomsday/Home.vue'));

        $this->assertStringContainsString('readonly hideBackground?: boolean', $layout);
        $this->assertStringContainsString('hideBackground: false', $layout);
        $this->assertStringContainsString('v-if="!hideBackground"', $layout);
        $this->assertStringContainsString('fixed inset-0', $layout);
        $this->assertStringContainsString('doomsday-scrollbar', $layout);
        $this->assertStringContainsString('pt-[64px]', $layout);
        $this->assertStringContainsString('fixed inset-x-0 top-0', $siteHeader);
        $this->assertStringContainsString('pb-2 pt-1', $siteHeader);
        $this->assertStringNotContainsString('sticky top-0', $siteHeader);
        $this->assertStringNotContainsString('py-5', $siteHeader);
        $this->assertStringNotContainsString(':hide-background="selection.detailOpen.value"', $home);
        $this->assertStringContainsString(':hide-mobile-header="selection.detailOpen.value"', $home);
    }

    public function test_mobile_detail_and_skeleton_use_bounded_in_flow_top_image_without_aggressive_overlap(): void
    {
        $mobileDetail = (string) file_get_contents(base_path('resources/js/Components/Doomsday/MobileDetailView.vue'));
        $mobileSkeleton = (string) file_get_contents(base_path('resources/js/Components/Doomsday/MobileDetailSkeleton.vue'));

        foreach (['MobileDetailView' => $mobileDetail, 'MobileDetailSkeleton' => $mobileSkeleton] as $name => $source) {
            $this->assertStringContainsString('relative h-[220px]', $source, $name);
            $this->assertStringContainsString('sm:h-[260px]', $source, $name);
            $this->assertStringContainsString('overflow-hidden border-b border-white/10', $source, $name);
            $this->assertStringContainsString('mt-4 px-4', $source, $name);
            $this->assertStringNotContainsString('-mt-8', $source, $name);

            $topImageOffset = strpos($source, 'relative h-[220px]');
            $this->assertIsInt($topImageOffset, $name);
            $topImageSnippet = substr($source, $topImageOffset, 260);
            $this->assertStringNotContainsString('fixed', $topImageSnippet, $name . ' top image must stay in document flow');
        }

        $this->assertStringContainsString('object-cover object-center', $mobileDetail);
        $this->assertStringContainsString('sm:object-[center_35%]', $mobileDetail);
        $this->assertStringContainsString('bg-gradient-to-b from-black/10 via-transparent to-black/70', $mobileDetail);
    }

    public function test_selected_master_detail_uses_responsive_min_widths_and_safe_sticky_behavior(): void
    {
        $selectedMasterDetail = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SelectedMasterDetail.vue'));

        $this->assertStringNotContainsString('minmax(500px', $selectedMasterDetail);
        $this->assertStringNotContainsString('minmax(720px', $selectedMasterDetail);
        $this->assertStringNotContainsString('sticky top-28', $selectedMasterDetail);
        $this->assertStringContainsString('grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]', $selectedMasterDetail);
        $this->assertStringContainsString("isDetailExpanded ? 'grid-cols-1'", $selectedMasterDetail);
        $this->assertStringContainsString('v-if="!isDetailExpanded"', $selectedMasterDetail);
        $this->assertStringContainsString('grid min-w-0 content-start', $selectedMasterDetail);
        $this->assertStringContainsString('relative min-w-0 overflow-hidden', $selectedMasterDetail);
        $this->assertStringContainsString('min-w-0 self-start', $selectedMasterDetail);
        $this->assertStringContainsString('@toggle-expanded="isDetailExpanded = !isDetailExpanded"', $selectedMasterDetail);
        $this->assertStringNotContainsString('2xl:grid-cols-[minmax(420px,0.9fr)_minmax(680px,1.2fr)]', $selectedMasterDetail);
        $this->assertStringNotContainsString('xl:sticky', $selectedMasterDetail);
        $this->assertStringNotContainsString('xl:max-h-[calc(100vh-7rem)]', $selectedMasterDetail);
        $this->assertStringNotContainsString('xl:overflow-y-auto', $selectedMasterDetail);
    }

    public function test_countdown_timer_renders_seconds_without_overflow_hidden_truncation(): void
    {
        $timer = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownTimer.vue'));

        $this->assertStringNotContainsString('overflow-hidden', $timer);
        $this->assertStringContainsString("{ label: 'SEC', value: seconds }", $timer);
        $this->assertSame(2, substr_count($timer, "{ label: 'SEC', value: seconds }"));
        $this->assertStringContainsString('text-[clamp(', $timer);
        $this->assertStringContainsString('tabular-nums', $timer);
        $this->assertStringContainsString('flex-nowrap', $timer);
        $this->assertStringContainsString('whitespace-nowrap', $timer);
        $this->assertStringContainsString('gap-x-0.5', $timer);
    }

    public function test_countdown_card_and_overlay_typography_are_responsive_and_keep_no_url_regressions(): void
    {
        $card = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownCard.vue'));
        $cardImage = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownCardImage.vue'));

        foreach (['255px', '<Link', 'prefetch cache-for="2m"', 'router.visit', 'router.reload', 'router.prefetch', 'countdown.icon', 'icon="'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $card, 'CountdownCard regression: ' . $forbidden);
        }

        $this->assertStringContainsString('doomsday-card min-w-0', $card);
        $this->assertStringContainsString('body: \'p-0 min-w-0\'', $card);
        $this->assertStringContainsString('grid min-w-0 grid-cols-1', $card);
        $this->assertStringNotContainsString('minmax(190px,0.42fr)', $card);
        $this->assertStringNotContainsString('minmax(210px,0.42fr)', $card);
        $this->assertStringNotContainsString('grid-cols-[minmax(0,1fr)_minmax(', $card);
        $this->assertStringNotContainsString('border-l', $card);
        $this->assertStringNotContainsString('ChevronRight', $card);
        $this->assertStringContainsString('border-t border-white/10', $card);
        $this->assertStringContainsString('@click="emit(\'select\', countdown)"', $card);
        $this->assertStringContainsString('@keydown.space.prevent="emit(\'select\', countdown)"', $card);
        $this->assertStringContainsString(':title="countdown.title"', $card);
        $this->assertStringContainsString(':subtitle="countdown.summary"', $card);
        $this->assertStringNotContainsString('countdown.timer.estimated_label', $card);

        $this->assertStringContainsString("import { Image } from '@simone-bianco/vue-ui-components';", $cardImage);
        $this->assertStringContainsString('h-[220px]', $cardImage);
        $this->assertStringContainsString('sm:h-[240px]', $cardImage);
        $this->assertStringContainsString('xl:h-[260px]', $cardImage);
        $this->assertStringContainsString('h-[150px]', $cardImage);
        $this->assertStringContainsString('sm:h-[160px]', $cardImage);
        $this->assertStringNotContainsString('h-[300px]', $cardImage);
        $this->assertStringNotContainsString('h-[360px]', $cardImage);
        $this->assertStringNotContainsString('aspect-ratio="100%"', $cardImage);
        $this->assertStringContainsString('line-clamp-2', $cardImage);
        $this->assertStringContainsString('font-bold', $cardImage);
        $this->assertStringContainsString('tracking-[0.06em]', $cardImage);
        $this->assertStringContainsString('text-[clamp(', $cardImage);
        $this->assertStringContainsString('p-3 sm:p-4', $cardImage);
        $this->assertStringContainsString('p-4 sm:p-5', $cardImage);
        $this->assertStringContainsString('from-transparent via-black/35 to-black/95', $cardImage);
        $this->assertStringNotContainsString('icon', strtolower($cardImage));
    }

    public function test_detail_tabs_scroll_internally_and_mini_widgets_do_not_use_fixed_three_columns(): void
    {
        $detail = (string) file_get_contents(base_path('resources/js/Components/Doomsday/DetailPanel.vue'));
        $overview = (string) file_get_contents(base_path('resources/js/Components/Doomsday/OverviewSection.vue'));
        $mobile = (string) file_get_contents(base_path('resources/js/Components/Doomsday/MobileDetailView.vue'));
        $css = (string) file_get_contents(base_path('resources/css/app.css'));

        $this->assertStringContainsString('flex max-h-[calc(100vh-5.25rem)] min-h-0 flex-col', $detail);
        $this->assertStringContainsString('doomsday-scrollbar', $detail);
        $this->assertStringContainsString('overflow-y-auto', $detail);
        $this->assertStringContainsString('shrink-0', $detail);
        $this->assertStringContainsString('Maximize2', $detail);
        $this->assertStringContainsString('Minimize2', $detail);
        $this->assertStringContainsString('toggleExpanded', $detail);
        $this->assertStringNotContainsString('sm:grid-cols-[1fr_auto]', $detail);
        $this->assertStringNotContainsString('min-w-72', $detail);
        $this->assertStringContainsString('grid-cols-[repeat(auto-fit,minmax(120px,1fr))]', $overview);
        $this->assertStringContainsString('grid-cols-[repeat(auto-fit,minmax(96px,1fr))]', $mobile);
        $this->assertStringNotContainsString('grid-cols-3', $overview . $mobile);
        $this->assertStringContainsString('.doomsday-scrollbar', $css);
        $this->assertStringContainsString('::-webkit-scrollbar', $css);
    }

    public function test_visual_responsive_fix_does_not_reintroduce_url_changing_selection_or_tab_loading(): void
    {
        $selection = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdaySelection.ts'));
        $lazy = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdayLazySections.ts'));
        $runtime = $selection . $lazy;

        $this->assertStringContainsString('axios.get<{ data: CountdownOverviewData }>', $selection);
        $this->assertStringContainsString("route('countdowns.data.overview'", $selection);
        $this->assertStringContainsString('axios.get<{ data: SectionDataByKey[K] }>', $lazy);
        $this->assertStringContainsString('route(sectionRouteByKey[key]', $lazy);
        $this->assertStringContainsString('pendingSelectedSlug.value === requestedSlug', $selection);
        $this->assertStringContainsString('countdownSlug.value === requestedSlug', $lazy);

        foreach (['router.visit', 'router.reload', 'router.prefetch', 'history.pushState', 'window.location', 'window.fetch'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $runtime);
        }
    }
}
