<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdaySelectedCardAccentQaTest extends TestCase
{
    use RefreshDatabase;

    public function test_selected_card_renders_internal_left_accent_without_layout_border(): void
    {
        $card = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownCard.vue'));

        $this->assertStringContainsString('const isSelected = computed(() => props.countdown.slug === props.selectedSlug);', $card);
        $this->assertStringContainsString(':aria-pressed="isSelected"', $card);
        $this->assertStringContainsString('doomsday-card relative h-fit self-start min-w-0 overflow-hidden rounded-xl', $card);
        $this->assertStringContainsString('isSelected ? \'doomsday-glow border-ui-primary\' : \'\'', $card);
        $this->assertStringContainsString('<span v-if="isSelected"', $card);
        $this->assertStringContainsString('aria-hidden="true"', $card);
        $this->assertStringContainsString('pointer-events-none absolute inset-y-0 left-0 z-20 w-[2px] bg-ui-primary', $card);
        $this->assertStringContainsString('shadow-[0_0_16px_rgba(255,42,35,0.75)]', $card);

        $accentOffset = strpos($card, '<span v-if="isSelected"');
        $contentOffset = strpos($card, '<div :class="gridClass">');
        $this->assertIsInt($accentOffset);
        $this->assertIsInt($contentOffset);
        $this->assertLessThan($contentOffset, $accentOffset, 'Selected accent should render inside Card before flush image/timer content.');
        $this->assertStringNotContainsString('border-l', $card);
    }

    public function test_selected_accent_preserves_stacked_timer_click_contract_and_forbidden_card_patterns(): void
    {
        $card = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownCard.vue'));
        $list = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownList.vue'));
        $home = (string) file_get_contents(base_path('resources/js/Pages/Doomsday/Home.vue'));

        $this->assertStringContainsString("const gridClass = computed(() => 'grid min-w-0 grid-cols-1 gap-0');", $card);
        $this->assertStringContainsString('border-t border-white/10', $card);
        $this->assertStringContainsString('py-2.5', $card);
        $this->assertStringContainsString('py-3', $card);
        $this->assertStringContainsString('<CountdownTimer :target-date="countdown.timer.target_date" :compact="true" :dense="compact" />', $card);
        $this->assertStringContainsString('@click="emit(\'select\', countdown)"', $card);
        $this->assertStringContainsString('@keydown.enter="emit(\'select\', countdown)"', $card);
        $this->assertStringContainsString('@keydown.space.prevent="emit(\'select\', countdown)"', $card);
        $this->assertStringContainsString(':selected-slug="selectedSlug"', $list);
        $this->assertStringContainsString(':pending-slug="pendingSlug"', $list);
        $this->assertStringContainsString(':selected-slug="selection.activeSelectedSlug.value"', $home);
        $this->assertStringContainsString(':pending-slug="selection.pendingSelectedSlug.value"', $home);

        foreach (['grid-cols-[minmax(0,1fr)_minmax(', 'sm:grid-cols-[', 'xl:grid-cols-[', '2xl:grid-cols-[', 'border-l', 'ChevronRight', '<Link', 'prefetch cache-for="2m"', 'router.visit', 'router.reload', 'router.prefetch', 'history.pushState', 'window.location', 'window.fetch', 'countdown.icon', 'countdown.timer.estimated_label', 'icon="'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $card, 'CountdownCard regression: '.$forbidden);
        }
    }

    public function test_prior_statistics_taiwan_and_clickable_row_contracts_remain_intact(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $this->get('/countdowns/taiwan-invasion?lang=en')->assertOk()->assertSee('Taiwan Invasion');
        $this->getJson(route('countdowns.data.statistics', ['slug' => 'taiwan-invasion', 'lang' => 'en']))
            ->assertOk()
            ->assertJsonPath('data.countdown_slug', 'taiwan-invasion');
        $this->getJson(route('countdowns.data.news', ['slug' => 'taiwan-invasion', 'lang' => 'en']))
            ->assertOk()
            ->assertJsonPath('data.countdown_slug', 'taiwan-invasion');
        $this->getJson(route('countdowns.data.initiatives', ['slug' => 'taiwan-invasion', 'lang' => 'en']))
            ->assertOk()
            ->assertJsonPath('data.countdown_slug', 'taiwan-invasion');

        $detail = (string) file_get_contents(base_path('resources/js/Components/Doomsday/DetailPanel.vue'));
        $chart = (string) file_get_contents(base_path('resources/js/Components/Doomsday/VisualizationChart.vue'));
        $news = (string) file_get_contents(base_path('resources/js/Components/Doomsday/NewsSection.vue'));
        $initiatives = (string) file_get_contents(base_path('resources/js/Components/Doomsday/InitiativesSection.vue'));
        $previewCard = (string) file_get_contents(base_path('resources/js/Components/Doomsday/ContentPreviewCard.vue'));

        $this->assertStringContainsString('flex h-full min-h-0 flex-col overflow-hidden', $detail);
        $this->assertStringContainsString('auto-rows-max', $detail);
        $this->assertStringContainsString('overflow-y-auto overscroll-contain', $detail);
        $this->assertStringContainsString('const height = 360;', $chart);
        $this->assertStringContainsString('h-[22rem] min-w-[600px] w-full', $chart);
        $this->assertStringContainsString('pb-6', $chart);
        $this->assertStringNotContainsString('h-72', $chart);
        $this->assertStringNotContainsString('min-w-[620px]', $chart);
        $this->assertStringContainsString(':href="item.source_url"', $news);
        $this->assertStringContainsString(':href="item.url"', $initiatives);
        $this->assertStringContainsString(':href="href || undefined"', $previewCard);
        $this->assertStringContainsString(":target=\"href ? '_blank' : undefined\"", $previewCard);
        $this->assertStringContainsString(":rel=\"href ? 'noopener noreferrer' : undefined\"", $previewCard);
        $this->assertStringContainsString('ExternalLink', $previewCard);
        $this->assertStringNotContainsString('sm:grid-cols-2', $news.$initiatives);
    }

    public function test_selected_card_accent_fix_preserves_no_url_selection_runtime_contract(): void
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
            $this->assertStringNotContainsString($forbidden, $runtime);
        }
    }
}
