<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdayLatestUpdateCtaQaTest extends TestCase
{
    use RefreshDatabase;

    public function test_latest_update_cta_uses_doomsday_button_styling_and_component_icon_contract(): void
    {
        $sidebar = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SidebarCards.vue'));

        $this->assertStringContainsString("import { Link } from '@inertiajs/vue3';", $sidebar);
        $this->assertStringContainsString("import { Card, Image, Button } from '@simone-bianco/vue-ui-components';", $sidebar);
        $this->assertStringContainsString("import { ChevronRight } from 'lucide-vue-next';", $sidebar);
        $this->assertStringContainsString('<Link :href="featured.url" class="block w-full sm:w-fit">', $sidebar);
        $this->assertStringContainsString('variant="secondary"', $sidebar);
        $this->assertStringContainsString('size="md"', $sidebar);
        $this->assertStringContainsString('icon-position="right"', $sidebar);
        $this->assertStringContainsString(':icon="ChevronRight"', $sidebar);
        $this->assertStringContainsString('ui="{', $sidebar);
        $this->assertStringContainsString('group doomsday-display w-full border-ui-primary/50 bg-ui-primary/10', $sidebar);
        $this->assertStringContainsString('text-[0.7rem] font-bold uppercase tracking-[0.12em] text-ui-primary', $sidebar);
        $this->assertStringContainsString('shadow-[0_0_18px_rgba(255,42,35,0.16)]', $sidebar);
        $this->assertStringContainsString('hover:border-ui-primary hover:bg-ui-primary/20 hover:text-white', $sidebar);
        $this->assertStringContainsString('hover:shadow-[0_0_26px_rgba(255,42,35,0.28)]', $sidebar);
        $this->assertStringContainsString('sm:w-auto', $sidebar);
        $this->assertStringContainsString("icon: 'transition-transform group-hover:translate-x-0.5'", $sidebar);
        $this->assertStringContainsString("{{ t('viewDetails') }}", $sidebar);
    }

    public function test_latest_update_cta_scoped_file_has_no_icon_or_navigation_regression_patterns(): void
    {
        $sidebar = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SidebarCards.vue'));

        foreach (['icon="chevron-right"', 'icon="arrow-right"', 'router.visit', 'router.reload', 'router.prefetch', 'history.pushState', 'window.location', 'window.fetch', 'prefetch cache-for="2m"'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $sidebar, 'Sidebar CTA regression: ' . $forbidden);
        }

        $linkOffset = strpos($sidebar, '<Link :href="featured.url" class="block w-full sm:w-fit">');
        $buttonOffset = strpos($sidebar, '<Button');
        $this->assertIsInt($linkOffset);
        $this->assertIsInt($buttonOffset);
        $this->assertLessThan($buttonOffset, $linkOffset, 'CTA Link should wrap the styled Button.');
    }

    public function test_latest_update_cta_does_not_break_public_taiwan_route_or_prior_visual_contracts(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $this->get('/?lang=en')
            ->assertOk()
            ->assertSee('Doomsday Countdown')
            ->assertSee('Taiwan Invasion');

        $this->get('/countdowns/taiwan-invasion?lang=en')
            ->assertOk()
            ->assertSee('Taiwan Invasion');

        $card = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownCard.vue'));
        $detail = (string) file_get_contents(base_path('resources/js/Components/Doomsday/DetailPanel.vue'));
        $chart = (string) file_get_contents(base_path('resources/js/Components/Doomsday/VisualizationChart.vue'));
        $news = (string) file_get_contents(base_path('resources/js/Components/Doomsday/NewsSection.vue'));
        $initiatives = (string) file_get_contents(base_path('resources/js/Components/Doomsday/InitiativesSection.vue'));

        $this->assertStringContainsString('pointer-events-none absolute inset-y-0 left-0 z-20 w-[2px] bg-ui-primary', $card);
        $this->assertStringContainsString('border-t border-white/10', $card);
        $this->assertStringNotContainsString('border-l', $card);
        $this->assertStringNotContainsString('<Link', $card);
        $this->assertStringContainsString('flex h-full min-h-0 flex-col overflow-hidden', $detail);
        $this->assertStringContainsString('overflow-y-auto overscroll-contain', $detail);
        $this->assertStringContainsString('h-[22rem] min-w-[600px] w-full', $chart);
        $this->assertStringNotContainsString('h-72', $chart);
        $this->assertStringContainsString(':href="item.source_url ??', $news);
        $this->assertStringContainsString(':href="item.url"', $initiatives);
    }

    public function test_latest_update_cta_polish_preserves_no_url_selection_runtime_contract(): void
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

