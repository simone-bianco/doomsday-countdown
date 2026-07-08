<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use App\Enums\InitiativeLocale;
use App\Enums\InitiativeType;
use App\Enums\ProjectionType;
use App\Enums\VisualizationType;
use App\Models\Countdown;
use App\Models\Initiative;
use App\Models\News;
use App\Models\Projection;
use App\Models\Visualization;
use App\Services\Doomsday\Cache\CountdownCache;
use App\Services\Doomsday\Cache\DoomsdayCacheKeys;
use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

final class DoomsdayLazyCacheInitiativesTest extends TestCase
{
    use RefreshDatabase;

    public function test_partial_reload_returns_requested_forecast_section_only(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $response = $this->withHeaders($this->partialHeaders('forecast_section'))->get('/countdowns/taiwan-invasion?lang=en');

        $response->assertOk();
        $props = $response->json('props');

        $this->assertIsArray($props);
        $this->assertArrayHasKey('forecast_section', $props);
        $this->assertArrayNotHasKey('statistics_section', $props);
        $this->assertArrayNotHasKey('news_section', $props);
        $this->assertArrayNotHasKey('initiatives_section', $props);
        $this->assertSame('taiwan-invasion', $props['forecast_section']['countdown_slug']);
        $this->assertNotEmpty($props['forecast_section']['projections']);
    }

    public function test_partial_reload_returns_initiatives_section_only(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $response = $this->withHeaders($this->partialHeaders('initiatives_section'))->get('/countdowns/taiwan-invasion?lang=en');

        $response->assertOk();
        $props = $response->json('props');

        $this->assertIsArray($props);
        $this->assertArrayHasKey('initiatives_section', $props);
        $this->assertArrayNotHasKey('forecast_section', $props);
        $this->assertArrayNotHasKey('statistics_section', $props);
        $this->assertArrayNotHasKey('news_section', $props);
        $this->assertSame('taiwan-invasion', $props['initiatives_section']['countdown_slug']);
        $this->assertNotEmpty($props['initiatives_section']['initiatives']);
    }

    public function test_partial_reload_returns_selected_countdown_only(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $response = $this->withHeaders($this->partialHeaders('selected_countdown'))->get('/countdowns/taiwan-invasion?lang=en');

        $response->assertOk();
        $props = $response->json('props');

        $this->assertIsArray($props);
        $this->assertArrayHasKey('selected_countdown', $props);
        $this->assertArrayNotHasKey('page', $props);
        $this->assertArrayNotHasKey('forecast_section', $props);
        $this->assertArrayNotHasKey('statistics_section', $props);
        $this->assertArrayNotHasKey('news_section', $props);
        $this->assertArrayNotHasKey('initiatives_section', $props);
        $this->assertSame('taiwan-invasion', $props['selected_countdown']['slug']);
    }

    public function test_partial_reload_closes_selected_countdown_from_home_route(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $response = $this->withHeaders($this->partialHeaders('selected_countdown'))->get('/?lang=en');

        $response->assertOk();
        $props = $response->json('props');

        $this->assertIsArray($props);
        $this->assertArrayHasKey('selected_countdown', $props);
        $this->assertArrayNotHasKey('page', $props);
        $this->assertNull($props['selected_countdown']);
    }

    public function test_json_data_endpoints_return_read_only_payloads(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $overview = $this->getJson(route('countdowns.data.overview', ['slug' => 'taiwan-invasion', 'lang' => 'it']));
        $overview->assertOk()
            ->assertJsonPath('data.slug', 'taiwan-invasion')
            ->assertJsonPath('data.title', 'Invasione di Taiwan');

        $sections = [
            'countdowns.data.forecasts' => ['projections', 'taiwan-invasion'],
            'countdowns.data.statistics' => ['visualizations', 'taiwan-invasion'],
            'countdowns.data.news' => ['news', 'taiwan-invasion'],
            'countdowns.data.initiatives' => ['initiatives', 'taiwan-invasion'],
        ];

        foreach ($sections as $routeName => [$payloadKey, $slug]) {
            $response = $this->getJson(route($routeName, ['slug' => $slug, 'lang' => 'en']));
            $response->assertOk()
                ->assertJsonPath('data.countdown_slug', $slug)
                ->assertJsonStructure(['data' => ['countdown_slug', $payloadKey]]);
        }
    }

    public function test_json_data_endpoint_returns_not_found_for_unpublished_countdown(): void
    {
        $this->countdown('hidden-json')->update(['is_published' => false]);

        $this->getJson(route('countdowns.data.overview', ['slug' => 'hidden-json', 'lang' => 'en']))
            ->assertNotFound()
            ->assertJsonPath('message', 'Countdown data not found.');
    }

    public function test_cache_can_be_disabled_and_ignores_stale_entries(): void
    {
        $this->seed(DoomsdaySeeder::class);
        config(['doomsday.cache.enabled' => false]);
        Cache::put(DoomsdayCacheKeys::index('en'), ['app_name' => 'stale', 'hero' => [], 'countdowns' => []], 60);

        $page = app(CountdownCache::class)->page('en', null, '/');

        $this->assertSame('Doomsday Countdown', $page['app_name']);
        $this->assertNotEmpty($page['countdowns']);
    }

    public function test_countdown_observer_purges_index_current_slug_and_original_slug(): void
    {
        $countdown = $this->countdown('old-slug');
        Cache::put(DoomsdayCacheKeys::index('en'), ['stale' => true], 60);
        Cache::put(DoomsdayCacheKeys::overview('old-slug', 'en'), ['stale' => true], 60);
        Cache::put(DoomsdayCacheKeys::overview('new-slug', 'en'), ['stale' => true], 60);

        $countdown->update(['slug' => 'new-slug']);

        $this->assertFalse(Cache::has(DoomsdayCacheKeys::index('en')));
        $this->assertFalse(Cache::has(DoomsdayCacheKeys::overview('old-slug', 'en')));
        $this->assertFalse(Cache::has(DoomsdayCacheKeys::overview('new-slug', 'en')));
    }

    public function test_related_model_observers_purge_parent_countdown_sections(): void
    {
        $countdown = $this->countdown('observer-check');
        $projection = $countdown->projections()->create($this->projection());
        $news = $countdown->news()->create(['locale' => 'all', 'title' => 'Shared', 'excerpt' => 'Shared note']);
        $initiative = $countdown->initiatives()->create($this->initiative('Shared initiative'));
        $visualization = $countdown->visualizations()->create($this->visualization());
        $projectionVisualization = $projection->visualizations()->create($this->visualization('projection_curve'));

        Cache::put(DoomsdayCacheKeys::forecasts('observer-check', 'en'), ['stale' => true], 60);
        $projection->update(['trend' => 'rising']);
        $this->assertFalse(Cache::has(DoomsdayCacheKeys::forecasts('observer-check', 'en')));

        Cache::put(DoomsdayCacheKeys::news('observer-check', 'en'), ['stale' => true], 60);
        $news->update(['title' => 'Shared updated']);
        $this->assertFalse(Cache::has(DoomsdayCacheKeys::news('observer-check', 'en')));

        Cache::put(DoomsdayCacheKeys::initiatives('observer-check', 'en'), ['stale' => true], 60);
        $initiative->update(['title' => 'Initiative updated']);
        $this->assertFalse(Cache::has(DoomsdayCacheKeys::initiatives('observer-check', 'en')));

        Cache::put(DoomsdayCacheKeys::statistics('observer-check', 'en'), ['stale' => true], 60);
        $visualization->update(['sort_order' => 2]);
        $this->assertFalse(Cache::has(DoomsdayCacheKeys::statistics('observer-check', 'en')));

        Cache::put(DoomsdayCacheKeys::forecasts('observer-check', 'en'), ['stale' => true], 60);
        $projectionVisualization->update(['sort_order' => 2]);
        $this->assertFalse(Cache::has(DoomsdayCacheKeys::forecasts('observer-check', 'en')));
    }

    /** @return array<string, string> */
    private function partialHeaders(string $only): array
    {
        $manifest = public_path('build/manifest.json');

        return [
            'X-Inertia' => 'true',
            'X-Inertia-Version' => file_exists($manifest) ? hash_file('xxh128', $manifest) : '',
            'X-Inertia-Partial-Component' => 'Doomsday/Home',
            'X-Inertia-Partial-Data' => $only,
        ];
    }

    private function countdown(string $slug): Countdown
    {
        return Countdown::query()->create([
            'slug' => $slug,
            'title' => ['en' => 'Sample'],
            'summary' => ['en' => 'Sample summary'],
            'description' => ['en' => 'Sample description'],
            'icon' => 'users',
            'severity' => CountdownSeverity::High,
            'status' => CountdownStatus::Active,
            'target_date' => now()->addYear(),
            'image_path' => 'images/doomsday/society_collapse_separate.png',
            'is_published' => true,
            'sort_order' => 1,
        ]);
    }

    /** @return array<string, mixed> */
    private function projection(): array
    {
        return [
            'type' => ProjectionType::Neutral,
            'target_date' => now()->addYear(),
            'title' => ['en' => 'Neutral'],
            'summary' => ['en' => 'Neutral summary'],
            'confidence_score' => 60,
            'probability_score' => 70,
            'trend' => 'stable',
            'sort_order' => 1,
        ];
    }

    /** @return array<string, mixed> */
    private function initiative(string $title): array
    {
        return [
            'locale' => InitiativeLocale::All,
            'type' => InitiativeType::Campaign,
            'title' => $title,
            'excerpt' => $title . ' excerpt',
            'organization' => 'Test Org',
            'url' => 'https://example.org/test',
            'sort_order' => 1,
        ];
    }

    /** @return array<string, mixed> */
    private function visualization(string $key = 'key_indicators'): array
    {
        return [
            'key' => $key,
            'type' => VisualizationType::Kpi,
            'title' => ['en' => 'Indicators'],
            'description' => ['en' => 'Indicator list'],
            'payload' => ['items' => []],
            'schema_version' => 1,
            'sort_order' => 1,
        ];
    }
}
