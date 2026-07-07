<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Doomsday\CountdownPublicDataService;
use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdayFinalQaRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_selected_route_language_options_preserve_current_path_and_active_locale(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $page = app(CountdownPublicDataService::class)->index('it', 'fall-of-europe', 'countdowns/fall-of-europe');
        $languages = collect($page->languages);

        $this->assertCount(8, $languages);
        $this->assertSame('it', $page->current_locale);
        $this->assertTrue($languages->firstWhere('code', 'it')->is_current);
        $this->assertFalse($languages->firstWhere('code', 'en')->is_current);
        $this->assertSame('/countdowns/fall-of-europe?lang=it', $languages->firstWhere('code', 'it')->url);
        $this->assertSame('/countdowns/fall-of-europe?lang=en', $languages->firstWhere('code', 'en')->url);
    }

    public function test_public_payload_copy_has_no_prohibited_legacy_terms(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $page = app(CountdownPublicDataService::class)->index('en', 'fall-of-europe', 'countdowns/fall-of-europe')->toArray();
        $payload = json_encode($page, JSON_THROW_ON_ERROR);

        foreach (['Artificial Intelligence', 'OpenAI', 'AI ', 'AI<', 'Agent Debug', 'Backoffice', 'Login'] as $prohibited) {
            $this->assertStringNotContainsString($prohibited, $payload);
        }
    }

    public function test_selected_layout_keeps_desktop_sidebar_out_of_selected_branch_and_uses_mobile_detail_first(): void
    {
        $home = (string) file_get_contents(base_path('resources/js/Pages/Doomsday/Home.vue'));
        $css = (string) file_get_contents(base_path('resources/css/app.css'));
        $mobile = (string) file_get_contents(base_path('resources/js/Components/Doomsday/MobileDetailView.vue'));
        $desktop = (string) file_get_contents(base_path('resources/js/Components/Doomsday/SelectedMasterDetail.vue'));

        $selectedStart = strpos($home, '<template v-if="selectedCountdown">');
        $unselectedStart = strpos($home, '<template v-else>');

        $this->assertIsInt($selectedStart);
        $this->assertIsInt($unselectedStart);
        $this->assertGreaterThan($selectedStart, $unselectedStart);

        $selectedBranch = substr($home, $selectedStart, $unselectedStart - $selectedStart);

        $this->assertStringContainsString('MobileDetailView', $selectedBranch);
        $this->assertStringContainsString('SelectedMasterDetail', $selectedBranch);
        $this->assertStringNotContainsString('SidebarCards', $selectedBranch);
        $this->assertStringContainsString('lg:hidden', $mobile);
        $this->assertStringContainsString('fixed inset-x-0 bottom-0', $mobile);
        $this->assertStringContainsString('hidden max-w-[1760px]', $desktop);
        $this->assertStringContainsString('lg:grid', $desktop);
        $this->assertStringContainsString('overflow-x: hidden', $css);
    }
}
