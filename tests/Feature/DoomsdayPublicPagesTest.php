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
                ->has('page.countdowns', 4)
                ->where('page.selected_countdown', null));
    }

    public function test_detail_route_renders_selected_countdown(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $this->get('/countdowns/fall-of-europe?lang=it')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Doomsday/Home')
                ->where('page.current_locale', 'it')
                ->where('page.selected_countdown.slug', 'fall-of-europe')
                ->where('page.selected_countdown.title', 'Caduta dell’Europa'));
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
