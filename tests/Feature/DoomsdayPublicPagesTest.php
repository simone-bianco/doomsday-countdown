<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

final class DoomsdayPublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_public_doomsday_dashboard_from_seeded_database(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $this->get('/?lang=en')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Doomsday/Home')
                ->where('page.app_name', 'Doomsday Countdown')
                ->where('page.current_locale', 'en')
                ->has('page.countdowns', 1)
                ->where('page.countdowns.0.slug', 'taiwan-invasion')
                ->where('page.countdowns.0.title', 'Taiwan Invasion')
                ->where('page.selected_countdown', null)
                ->where('selected_countdown', null)
                ->missing('forecast_section')
                ->missing('statistics_section')
                ->missing('news_section')
                ->missing('initiatives_section'));
    }

    public function test_detail_route_renders_top_level_selected_countdown_overview_only(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $this->get('/countdowns/taiwan-invasion?lang=it')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Doomsday/Home')
                ->where('page.current_locale', 'it')
                ->where('page.selected_countdown', null)
                ->where('selected_countdown.slug', 'taiwan-invasion')
                ->where('selected_countdown.title', 'Invasione di Taiwan')
                ->has('selected_countdown.key_indicators')
                ->missing('selected_countdown.projections')
                ->missing('selected_countdown.visualizations')
                ->missing('selected_countdown.news')
                ->missing('forecast_section')
                ->missing('statistics_section')
                ->missing('news_section')
                ->missing('initiatives_section'));
    }

    public function test_about_page_renders_public_methodology(): void
    {
        $this->get('/about?lang=en')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Doomsday/About')
                ->where('page.app_name', 'Doomsday Countdown')
                ->has('page.languages', 8)
                ->has('page.sections'));
    }
}
