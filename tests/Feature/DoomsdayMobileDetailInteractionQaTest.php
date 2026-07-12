<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class DoomsdayMobileDetailInteractionQaTest extends TestCase
{
    public function test_mobile_overview_read_more_is_an_accessible_expand_collapse_control(): void
    {
        $mobile = (string) file_get_contents(base_path('resources/js/Components/Doomsday/MobileDetailView.vue'));
        $i18n = (string) file_get_contents(base_path('resources/js/i18n/index.ts'));

        $this->assertStringContainsString("const isOverviewExpanded = ref(false)", $mobile);
        $this->assertStringContainsString("isOverviewExpanded ? '' : 'line-clamp-5'", $mobile);
        $this->assertStringContainsString(':aria-expanded="isOverviewExpanded"', $mobile);
        $this->assertStringContainsString('@click="isOverviewExpanded = !isOverviewExpanded"', $mobile);
        $this->assertStringContainsString("t(isOverviewExpanded ? 'readLess' : 'readMore')", $mobile);
        $this->assertStringContainsString("readLess: 'Read less'", $i18n);
        $this->assertStringContainsString("readLess: 'Mostra meno'", $i18n);
    }

    public function test_mobile_tabs_fit_the_viewport_and_support_horizontal_swipe_navigation(): void
    {
        $mobile = (string) file_get_contents(base_path('resources/js/Components/Doomsday/MobileDetailView.vue'));

        $this->assertStringContainsString('grid w-full grid-cols-5', $mobile);
        $this->assertStringContainsString('min-w-0 w-full justify-center whitespace-normal', $mobile);
        $this->assertStringContainsString('role="tablist"', $mobile);
        $this->assertStringContainsString('role="tab"', $mobile);
        $this->assertStringContainsString('role="tabpanel"', $mobile);
        $this->assertStringContainsString('@touchstart.passive="handleTouchStart"', $mobile);
        $this->assertStringContainsString('@touchend.passive="handleTouchEnd"', $mobile);
        $this->assertStringContainsString('Math.abs(horizontalDistance) >= 56', $mobile);
        $this->assertStringContainsString("horizontalDistance > 0 ? 1 : -1", $mobile);
        $this->assertStringNotContainsString('mt-5 flex gap-6 overflow-x-auto', $mobile);
    }

    public function test_mobile_charts_fit_without_horizontal_scroll_and_can_expand_full_screen(): void
    {
        $mobile = (string) file_get_contents(base_path('resources/js/Components/Doomsday/MobileDetailView.vue'));
        $forecasts = (string) file_get_contents(base_path('resources/js/Components/Doomsday/ForecastsSection.vue'));
        $statistics = (string) file_get_contents(base_path('resources/js/Components/Doomsday/StatisticsSection.vue'));
        $chart = (string) file_get_contents(base_path('resources/js/Components/Doomsday/VisualizationChart.vue'));

        $this->assertStringContainsString('<ForecastsSection v-if="forecastSection" :section="forecastSection" mobile />', $mobile);
        $this->assertStringContainsString('<StatisticsSection v-if="statisticsSection" :section="statisticsSection" mobile />', $mobile);
        $this->assertStringContainsString(':mobile="mobile"', $forecasts);
        $this->assertStringContainsString(':mobile="mobile"', $statistics);
        $this->assertStringContainsString('readonly mobile?: boolean', $chart);
        $this->assertStringContainsString("mobile && !isZoomed ? 'relative w-full overflow-hidden border-0 bg-transparent px-0 py-2'", $chart);
        $this->assertStringContainsString("'block h-auto w-full max-w-full'", $chart);
        $this->assertStringContainsString("'fixed inset-0 z-[100] flex h-[100dvh] w-[100dvw]", $chart);
        $zoom = (string) file_get_contents(base_path('resources/js/Composables/useMobileChartZoom.ts'));

        $this->assertStringContainsString('surface.requestFullscreen()', $zoom);
        $this->assertStringContainsString("t('chartZoomHint')", $chart);
        $this->assertStringContainsString('MAX_CHART_SCALE = 4', $zoom);
        $this->assertStringContainsString("mode: 'pinch'", $zoom);
        $this->assertStringContainsString('touchDistance(first, second)', $zoom);
        $this->assertStringContainsString("mode: 'pan'", $zoom);
        $this->assertStringContainsString('toggleChartMagnification()', $zoom);
        $this->assertStringContainsString('@touchmove="handleChartTouchMove"', $chart);
        $this->assertStringContainsString(':style="isZoomed ? chartTransformStyle : undefined"', $chart);
        $this->assertStringContainsString("t('zoomInChart')", $chart);
        $this->assertStringContainsString("t('zoomOutChart')", $chart);
        $this->assertStringContainsString("t('resetChartZoom')", $chart);
    }

    public function test_desktop_chart_and_detail_contracts_remain_unchanged_by_mobile_mode(): void
    {
        $detail = (string) file_get_contents(base_path('resources/js/Components/Doomsday/DetailPanel.vue'));
        $chart = (string) file_get_contents(base_path('resources/js/Components/Doomsday/VisualizationChart.vue'));

        $this->assertStringContainsString('<ForecastsSection v-if="forecastSection" :section="forecastSection" />', $detail);
        $this->assertStringContainsString('<StatisticsSection v-if="statisticsSection" :section="statisticsSection" />', $detail);
        $this->assertStringContainsString("!mobile ? 'doomsday-scrollbar w-full overflow-x-auto rounded-lg border border-white/10 bg-black/35 py-4 pb-6'", $chart);
        $this->assertStringContainsString("'h-[22rem] min-w-[600px] w-full'", $chart);
        $this->assertStringContainsString('useMobileChartZoom(computed(() => props.mobile))', $chart);
    }
}
