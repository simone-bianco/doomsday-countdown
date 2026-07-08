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
        $this->assertStringContainsString('border-t border-white/10', $card);
        $this->assertStringContainsString('py-2.5', $card);
        $this->assertStringContainsString('py-3', $card);
        $this->assertStringContainsString('<CountdownTimer :target-date="countdown.timer.target_date" :compact="true" :dense="compact" />', $card);

        foreach (['grid-cols-[minmax(0,1fr)_minmax(', 'sm:grid-cols-[', 'xl:grid-cols-[', '2xl:grid-cols-[', 'border-l', 'ChevronRight', '<Link', 'prefetch cache-for="2m"', 'router.visit', 'router.reload', 'router.prefetch', 'countdown.icon', 'countdown.timer.estimated_label', 'icon="'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $card, 'CountdownCard regression: ' . $forbidden);
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
        $this->assertStringContainsString('doomsday-card flex max-h-[calc(100vh-5.25rem)] min-h-0 flex-col', $detail);
        $this->assertStringContainsString('grid min-w-0 shrink-0 gap-4', $detail);
        $this->assertStringContainsString('p-4 sm:p-5', $detail);
        $this->assertStringContainsString('text-xl leading-tight text-white sm:text-2xl 2xl:text-3xl', $detail);
        $this->assertStringContainsString('doomsday-scrollbar flex shrink-0', $detail);
        $this->assertStringContainsString('doomsday-scrollbar grid min-h-0 min-w-0 flex-1 gap-5 overflow-y-auto', $detail);

        foreach (['sm:grid-cols-[1fr_auto]', 'min-w-72', 'countdown.timer.estimated_label', 'absolute right-5 top-5'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $detail, 'DetailPanel regression: ' . $forbidden);
        }

        $this->assertStringContainsString('const isDetailExpanded = ref(false)', $selectedMaster);
        $this->assertStringContainsString("isDetailExpanded ? 'grid-cols-1'", $selectedMaster);
        $this->assertStringContainsString('v-if="!isDetailExpanded"', $selectedMaster);
        $this->assertStringContainsString(':expanded="isDetailExpanded"', $selectedMaster);
        $this->assertStringContainsString('@toggle-expanded="isDetailExpanded = !isDetailExpanded"', $selectedMaster);
        $this->assertStringNotContainsString('xl:sticky', $selectedMaster);
    }

    public function test_auto_fit_widgets_latest_update_columns_and_no_estimated_label_surfaces(): void
    {
        $home = (string) file_get_contents(base_path('resources/js/Pages/Doomsday/Home.vue'));
        $overview = (string) file_get_contents(base_path('resources/js/Components/Doomsday/OverviewSection.vue'));
        $mobile = (string) file_get_contents(base_path('resources/js/Components/Doomsday/MobileDetailView.vue'));
        $detail = (string) file_get_contents(base_path('resources/js/Components/Doomsday/DetailPanel.vue'));
        $card = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownCard.vue'));

        $this->assertStringContainsString('lg:grid-cols-[minmax(0,1fr)_560px]', $home);
        $this->assertStringContainsString('2xl:grid-cols-[minmax(0,1fr)_600px]', $home);
        $this->assertStringContainsString('grid-cols-[repeat(auto-fit,minmax(120px,1fr))]', $overview);
        $this->assertStringContainsString('grid-cols-[repeat(auto-fit,minmax(96px,1fr))]', $mobile);
        $this->assertStringNotContainsString('grid-cols-3', $overview . $mobile);

        foreach (['countdown.timer.estimated_label', 'Estimated target'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $card . $detail . $mobile, 'Estimated label surface regression: ' . $forbidden);
        }
    }

    public function test_overflow_visual_fix_preserves_no_url_selection_runtime_contract(): void
    {
        $selection = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdaySelection.ts'));
        $lazy = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdayLazySections.ts'));
        $runtime = $selection . $lazy;

        $this->assertStringContainsString('axios.get<{ data: CountdownOverviewData }>', $selection);
        $this->assertStringContainsString("route('countdowns.data.overview'", $selection);
        $this->assertStringContainsString('pendingSelectedSlug.value === requestedSlug', $selection);
        $this->assertStringContainsString('axios.get<{ data: SectionDataByKey[K] }>', $lazy);
        $this->assertStringContainsString('route(sectionRouteByKey[key]', $lazy);
        $this->assertStringContainsString('countdownSlug.value === requestedSlug', $lazy);

        foreach (['router.visit', 'router.reload', 'router.prefetch', 'history.pushState', 'window.location', 'window.fetch'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $runtime, 'URL-changing runtime regression: ' . $forbidden);
        }
    }
}
