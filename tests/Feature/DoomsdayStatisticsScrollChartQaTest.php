<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdayStatisticsScrollChartQaTest extends TestCase
{
    use RefreshDatabase;

    public function test_selected_desktop_shell_and_detail_panel_have_internal_scroll_contract(): void
    {
        $selectedMaster = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SelectedMasterDetail.vue'));
        $detail = (string) file_get_contents(base_path('resources/js/Components/Doomsday/DetailPanel.vue'));

        $this->assertStringContainsString('h-[calc(100vh-64px)] min-h-0', $selectedMaster);
        $this->assertStringContainsString('overflow-hidden px-5 py-4 lg:grid', $selectedMaster);
        $this->assertStringContainsString('doomsday-scrollbar grid min-h-0 min-w-0 content-start gap-5 overflow-y-auto', $selectedMaster);
        $this->assertStringContainsString('min-h-0 min-w-0 self-stretch', $selectedMaster);
        $this->assertStringContainsString("isDetailExpanded ? 'grid-cols-1'", $selectedMaster);
        $this->assertStringContainsString('v-if="!isDetailExpanded"', $selectedMaster);
        $this->assertStringNotContainsString('xl:sticky', $selectedMaster);
        $this->assertStringNotContainsString('xl:max-h-[calc(100vh-7rem)]', $selectedMaster);

        $this->assertStringContainsString('doomsday-card flex h-full min-h-0 flex-col overflow-hidden', $detail);
        $this->assertStringContainsString('grid min-w-0 shrink-0 gap-4', $detail);
        $this->assertStringContainsString('doomsday-scrollbar flex shrink-0', $detail);
        $this->assertStringContainsString('doomsday-scrollbar grid min-h-0 min-w-0 flex-1 auto-rows-max gap-5 overflow-y-auto overscroll-contain', $detail);
        $this->assertStringContainsString("activeTab === 'statistics'", $detail);
        $this->assertStringContainsString('<StatisticsSection v-if="statisticsSection" :section="statisticsSection" />', $detail);
        $this->assertStringNotContainsString('max-h-[calc(100vh-5.25rem)]', $detail);
        $this->assertStringNotContainsString('absolute right-5 top-5', $detail);
    }

    public function test_statistics_chart_sizing_and_card_clipping_contracts_are_current(): void
    {
        $chart = (string) file_get_contents(base_path('resources/js/Components/Doomsday/VisualizationChart.vue'));
        $statistics = (string) file_get_contents(base_path('resources/js/Components/Doomsday/StatisticsSection.vue'));

        $this->assertStringContainsString('const height = 360;', $chart);
        $this->assertStringContainsString('bottom: 286', $chart);
        $this->assertStringContainsString('pb-6', $chart);
        $this->assertStringContainsString('h-[22rem] min-w-[600px] w-full', $chart);
        $this->assertStringContainsString('RawSeries', $chart);
        $this->assertStringContainsString('paddedMax', $chart);
        $this->assertStringContainsString('paddedMin', $chart);
        $this->assertStringContainsString('v-for="item in series"', $chart);
        $this->assertStringContainsString('legend', $chart);
        $this->assertStringNotContainsString('h-72', $chart);
        $this->assertStringNotContainsString('min-w-[620px]', $chart);
        $this->assertStringNotContainsString('Math.max(100', $chart);
        $this->assertStringNotContainsString('overflow-visible', $chart);

        $this->assertStringContainsString("root: 'doomsday-card min-w-0 rounded-xl'", $statistics);
        $this->assertStringContainsString("body: 'overflow-visible p-5 sm:p-6'", $statistics);
        $this->assertStringContainsString("visualization.type === 'line' || visualization.type === 'area'", $statistics);
    }

    public function test_taiwan_statistics_payload_and_clickable_rows_remain_intact(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $statistics = $this->getJson(route('countdowns.data.statistics', ['slug' => 'taiwan-invasion', 'lang' => 'en']))
            ->assertOk()
            ->json('data');

        $this->assertSame('taiwan-invasion', $statistics['countdown_slug']);
        $keys = array_column($statistics['visualizations'], 'key');
        foreach (['key_indicators', 'pla_pressure_trend', 'economic_exposure', 'scenario_gdp_shock', 'energy_resilience'] as $requiredKey) {
            $this->assertContains($requiredKey, $keys);
        }

        $forecasts = $this->getJson(route('countdowns.data.forecasts', ['slug' => 'taiwan-invasion', 'lang' => 'en']))
            ->assertOk()
            ->json('data');
        $this->assertCount(3, $forecasts['projections']);
        $this->assertSame('taiwan-invasion', $forecasts['countdown_slug']);

        $news = (string) file_get_contents(base_path('resources/js/Components/Doomsday/NewsSection.vue'));
        $initiatives = (string) file_get_contents(base_path('resources/js/Components/Doomsday/InitiativesSection.vue'));
        $skeleton = (string) file_get_contents(base_path('resources/js/Components/Doomsday/DoomsdaySkeletonBlock.vue'));

        $this->assertStringContainsString('grid grid-cols-1 gap-4', $news);
        $this->assertStringContainsString(':href="item.source_url ??', $news);
        $this->assertStringContainsString('target="_blank"', $news);
        $this->assertStringContainsString('rel="noopener noreferrer"', $news);
        $this->assertStringNotContainsString('sm:grid-cols-2', $news);

        $this->assertStringContainsString('grid grid-cols-1 gap-4', $initiatives);
        $this->assertStringContainsString(':href="item.url"', $initiatives);
        $this->assertStringContainsString('ExternalLink', $initiatives);
        $this->assertStringContainsString('target="_blank"', $initiatives);
        $this->assertStringContainsString('rel="noopener noreferrer"', $initiatives);
        $this->assertStringNotContainsString('sm:grid-cols-2', $initiatives);
        $this->assertStringContainsString("variant === 'initiatives'", $skeleton);
        $this->assertStringContainsString('grid grid-cols-1 gap-4', $skeleton);
    }

    public function test_statistics_scroll_fix_preserves_no_url_selection_runtime_contract(): void
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
            $this->assertStringNotContainsString($forbidden, $runtime);
        }
    }
}
