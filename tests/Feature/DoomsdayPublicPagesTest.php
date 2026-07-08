<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Doomsday\Cache\CountdownCache;
use App\Services\Doomsday\Cache\DoomsdayCacheKeys;
use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
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
        $this->get('/about?lang=it')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Doomsday/About')
                ->where('page.app_name', 'Doomsday Countdown')
                ->where('page.current_locale', 'it')
                ->where('page.eyebrow', 'Un sistema di allerta per un secolo fragile')
                ->where('page.pipeline_label', 'Pipeline degli scenari')
                ->has('page.languages', 8)
                ->has('page.intro', 2)
                ->has('page.stats', 3)
                ->has('page.sections', 3)
                ->has('page.timeline', 3)
                ->has('page.faq', 5)
                ->where('page.closing_label', 'Segnale finale'));
    }

    public function test_about_cache_refreshes_incomplete_legacy_payload(): void
    {
        Cache::put(DoomsdayCacheKeys::about('it'), [
            'app_name' => 'Doomsday Countdown',
            'current_locale' => 'it',
            'title' => 'Legacy cached About',
            'subtitle' => 'Legacy partial payload',
            'sections' => [],
        ], 60);

        $page = app(CountdownCache::class)->about('it', 'about');

        $this->assertSame('Doomsday Countdown', $page['app_name']);
        $this->assertSame('it', $page['current_locale']);
        $this->assertSame('Un sistema di allerta per un secolo fragile', $page['eyebrow']);
        $this->assertSame('Pipeline degli scenari', $page['pipeline_label']);
        $this->assertCount(3, $page['stats']);
        $this->assertCount(3, $page['timeline']);
        $this->assertCount(5, $page['faq']);
    }
}
