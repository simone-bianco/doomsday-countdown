<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Doomsday\Cache\CountdownCache;
use App\Services\Doomsday\Cache\DoomsdayCacheKeys;
use Carbon\CarbonImmutable;
use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

final class DoomsdayPublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        $this->travelBack();

        parent::tearDown();
    }

    public function test_home_page_renders_active_pessimistic_projection_for_all_seeded_countdowns(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-07-12 00:00:00', 'UTC'));
        $this->seed(DoomsdaySeeder::class);

        $this->get('/?lang=en')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Doomsday/Home')
                ->where('page.app_name', 'Doomsday Countdown')
                ->where('page.current_locale', 'en')
                ->has('page.countdowns', 6)
                ->where('page.countdowns.0.slug', 'taiwan-invasion')
                ->where('page.countdowns.0.title', 'Taiwan Invasion')
                ->where('page.countdowns.0.timer.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2027-12-31 23:59:59'))
                ->where('page.countdowns.0.main_projection.type', 'pessimistic')
                ->where('page.countdowns.0.main_projection.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2027-12-31 23:59:59'))
                ->where('page.countdowns.1.slug', 'europe-war-countdown')
                ->where('page.countdowns.1.timer.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2027-03-31 23:59:59'))
                ->where('page.countdowns.1.main_projection.type', 'pessimistic')
                ->where('page.countdowns.1.main_projection.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2027-03-31 23:59:59'))
                ->where('page.countdowns.2.slug', 'ai-job-apocalypse')
                ->where('page.countdowns.2.timer.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2027-12-02 23:59:59'))
                ->where('page.countdowns.2.main_projection.type', 'pessimistic')
                ->where('page.countdowns.2.main_projection.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2027-12-02 23:59:59'))
                ->where('page.countdowns.3.slug', 'sixth-mass-extinction')
                ->where('page.countdowns.3.timer.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2029-06-30 23:59:59'))
                ->where('page.countdowns.3.main_projection.type', 'pessimistic')
                ->where('page.countdowns.3.main_projection.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2029-06-30 23:59:59'))
                ->where('page.countdowns.4.slug', 'antibiotic-apocalypse')
                ->where('page.countdowns.4.timer.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2029-12-31 23:59:59'))
                ->where('page.countdowns.4.main_projection.type', 'pessimistic')
                ->where('page.countdowns.4.main_projection.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2029-12-31 23:59:59'))
                ->where('page.countdowns.5.slug', 'unlivable-heat')
                ->where('page.countdowns.5.timer.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2040-12-31 23:59:59'))
                ->where('page.countdowns.5.main_projection.type', 'pessimistic')
                ->where('page.countdowns.5.main_projection.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2040-12-31 23:59:59'))
                ->where('page.selected_countdown', null)
                ->where('selected_countdown', null)
                ->missing('forecast_section')
                ->missing('statistics_section')
                ->missing('news_section')
                ->missing('initiatives_section'));
    }

    public function test_detail_route_uses_same_active_pessimistic_projection_as_home(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-07-12 00:00:00', 'UTC'));
        $this->seed(DoomsdaySeeder::class);

        $this->get('/countdowns/taiwan-invasion?lang=it')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Doomsday/Home')
                ->where('page.current_locale', 'it')
                ->where('page.selected_countdown', null)
                ->where('selected_countdown.slug', 'taiwan-invasion')
                ->where('selected_countdown.title', 'Invasione di Taiwan')
                ->where('selected_countdown.main_projection.type', 'pessimistic')
                ->where('selected_countdown.main_projection.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2027-12-31 23:59:59'))
                ->where('selected_countdown.timer.target_date', fn (?string $target): bool => $this->isUtcTimestamp($target, '2027-12-31 23:59:59'))
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

    private function isUtcTimestamp(?string $actual, string $expected): bool
    {
        return $actual !== null
            && CarbonImmutable::parse($actual)->utc()->format('Y-m-d H:i:s') === $expected;
    }
}
