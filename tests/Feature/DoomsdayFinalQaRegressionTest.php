<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Doomsday\Cache\CountdownCache;
use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdayFinalQaRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_selected_route_language_options_preserve_current_path_and_active_locale(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $page = app(CountdownCache::class)->page('it', 'taiwan-invasion', 'countdowns/taiwan-invasion');
        $languages = collect($page['languages']);

        $this->assertCount(8, $languages);
        $this->assertSame('it', $page['current_locale']);
        $this->assertTrue($languages->firstWhere('code', 'it')['is_current']);
        $this->assertFalse($languages->firstWhere('code', 'en')['is_current']);
        $this->assertSame('/countdowns/taiwan-invasion?lang=it', $languages->firstWhere('code', 'it')['url']);
        $this->assertSame('/countdowns/taiwan-invasion?lang=en', $languages->firstWhere('code', 'en')['url']);
    }

    public function test_public_payload_copy_has_no_prohibited_legacy_terms(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $cache = app(CountdownCache::class);
        $payload = json_encode([
            'page' => $cache->page('en', 'taiwan-invasion', 'countdowns/taiwan-invasion'),
            'selected_countdown' => $cache->overview('taiwan-invasion', 'en'),
            'forecast_section' => $cache->forecasts('taiwan-invasion', 'en'),
            'statistics_section' => $cache->statistics('taiwan-invasion', 'en'),
            'news_section' => $cache->news('taiwan-invasion', 'en'),
            'initiatives_section' => $cache->initiatives('taiwan-invasion', 'en'),
        ], JSON_THROW_ON_ERROR);

        foreach (['Artificial Intelligence', 'OpenAI', 'AI ', 'AI<', 'Agent Debug', 'Backoffice', 'Login'] as $prohibited) {
            $this->assertStringNotContainsString($prohibited, $payload);
        }
    }

    public function test_selected_layout_uses_ajax_sections_keeps_split_and_guards_stale_sections(): void
    {
        $layout = (string) file_get_contents(base_path('resources/js/Layouts/PublicLayout.vue'));
        $siteHeader = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SiteHeader.vue'));
        $home = (string) file_get_contents(base_path('resources/js/Pages/Doomsday/Home.vue'));
        $css = (string) file_get_contents(base_path('resources/css/app.css'));
        $mobile = (string) file_get_contents(base_path('resources/js/Components/Doomsday/MobileDetailView.vue'));
        $desktop = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SelectedMasterDetail.vue'));
        $detail = (string) file_get_contents(base_path('resources/js/Components/Doomsday/DetailPanel.vue'));
        $overview = (string) file_get_contents(base_path('resources/js/Components/Doomsday/OverviewSection.vue'));
        $timer = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownTimer.vue'));
        $card = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownCard.vue'));
        $cardImage = (string) file_get_contents(base_path('resources/js/Components/Doomsday/CountdownCardImage.vue'));
        $selection = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdaySelection.ts'));
        $lazy = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdayLazySections.ts'));

        $selectedStart = strpos($home, '<template v-if="selection.detailOpen.value">');
        $unselectedStart = strpos($home, '<template v-else>');

        $this->assertIsInt($selectedStart);
        $this->assertIsInt($unselectedStart);
        $this->assertGreaterThan($selectedStart, $unselectedStart);

        $selectedBranch = substr($home, $selectedStart, $unselectedStart - $selectedStart);

        $this->assertStringContainsString('readonly hideBackground?: boolean', $layout);
        $this->assertStringContainsString('v-if="!hideBackground"', $layout);
        $this->assertStringContainsString('doomsday-scrollbar', $layout);
        $this->assertStringContainsString('pt-[64px]', $layout);
        $this->assertStringContainsString('fixed inset-x-0 top-0', $siteHeader);
        $this->assertStringContainsString('pb-2 pt-1', $siteHeader);
        $this->assertStringNotContainsString('sticky top-0', $siteHeader);
        $this->assertStringNotContainsString('py-5', $siteHeader);
        $this->assertStringNotContainsString(':hide-background="selection.detailOpen.value"', $home);
        $this->assertStringContainsString('MobileDetailView', $selectedBranch);
        $this->assertStringContainsString('MobileDetailSkeleton', $selectedBranch);
        $this->assertStringContainsString('SelectedMasterDetail', $selectedBranch);
        $this->assertStringNotContainsString('SidebarCards', $selectedBranch);
        $this->assertStringContainsString('lg:hidden', $mobile);
        $this->assertStringContainsString('fixed inset-x-0 bottom-0', $mobile);
        $this->assertStringContainsString('relative h-[220px]', $mobile);
        $this->assertStringContainsString('sm:h-[260px]', $mobile);
        $this->assertStringContainsString('mt-4 px-4', $mobile);
        $this->assertStringContainsString('grid-cols-[repeat(auto-fit,minmax(96px,1fr))]', $mobile);
        $this->assertStringNotContainsString('grid-cols-3', $mobile);
        $this->assertStringNotContainsString('-mt-8', $mobile);
        $this->assertStringContainsString('hidden max-w-[1760px]', $desktop);
        $this->assertStringContainsString('h-[calc(100vh-64px)] min-h-0', $desktop);
        $this->assertStringContainsString('lg:grid', $desktop);
        $this->assertStringNotContainsString('minmax(500px', $desktop);
        $this->assertStringNotContainsString('minmax(720px', $desktop);
        $this->assertStringContainsString('min-w-0', $desktop);
        $this->assertStringContainsString('isDetailExpanded', $desktop);
        $this->assertStringContainsString('v-if="!isDetailExpanded"', $desktop);
        $this->assertStringNotContainsString('xl:sticky', $desktop);
        $this->assertStringNotContainsString('overflow-hidden', $timer);
        $this->assertStringContainsString('clamp(', $timer);
        $this->assertStringNotContainsString('255px', $card);
        $this->assertStringContainsString("'grid min-w-0 grid-cols-1 gap-0'", $card);
        $this->assertStringNotContainsString('grid-cols-[minmax(0,1fr)_minmax(', $card);
        $this->assertStringNotContainsString('border-l', $card);
        $this->assertStringContainsString('min-w-0', $card);
        $this->assertStringContainsString('font-bold', $cardImage);
        $this->assertStringContainsString('h-[220px]', $cardImage);
        $this->assertStringContainsString('xl:h-[260px]', $cardImage);
        $this->assertStringContainsString('h-[150px]', $cardImage);
        $this->assertStringNotContainsString('h-[300px]', $cardImage);
        $this->assertStringNotContainsString('aspect-ratio="100%"', $cardImage);
        $this->assertStringNotContainsString('countdown.timer.estimated_label', $card);
        $this->assertStringContainsString('line-clamp-2', $cardImage);
        $this->assertStringContainsString('axios.get<{ data: CountdownOverviewData }>', $selection);
        $this->assertStringContainsString("route('countdowns.data.overview'", $selection);
        $this->assertStringContainsString('axios.get<{ data: SectionDataByKey[K] }>', $lazy);
        $this->assertStringContainsString('DoomsdaySkeletonBlock', $detail);
        $this->assertStringContainsString('flex h-full min-h-0 flex-col overflow-hidden', $detail);
        $this->assertStringContainsString('auto-rows-max', $detail);
        $this->assertStringContainsString('overscroll-contain', $detail);
        $this->assertStringContainsString('doomsday-scrollbar', $detail);
        $this->assertStringContainsString('overflow-y-auto', $detail);
        $this->assertStringContainsString('toggleExpanded', $detail);
        $this->assertStringNotContainsString('sm:grid-cols-[1fr_auto]', $detail);
        $this->assertStringNotContainsString('min-w-72', $detail);
        $this->assertStringContainsString('grid-cols-[repeat(auto-fit,minmax(120px,1fr))]', $overview);
        $this->assertStringNotContainsString('grid-cols-3', $overview);
        $this->assertStringContainsString('countdownSlug.value === requestedSlug', $lazy);
        $this->assertStringContainsString('doomsday-skeleton', $css);
        $this->assertStringContainsString('.doomsday-scrollbar', $css);
        $this->assertStringContainsString('::-webkit-scrollbar', $css);
        $this->assertStringContainsString('overflow-x: hidden', $css);
    }
}
