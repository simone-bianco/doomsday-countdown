<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Countdown;
use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdayTaiwanRealDataQaTest extends TestCase
{
    use RefreshDatabase;

    public function test_fresh_seed_public_state_contains_only_taiwan_countdown_with_readable_asset(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $countdowns = Countdown::query()->with(['projections', 'visualizations', 'news', 'initiatives'])->get();
        $this->assertCount(1, $countdowns);

        $countdown = $countdowns->first();
        $this->assertNotNull($countdown);
        $this->assertSame('taiwan-invasion', $countdown->slug);
        $this->assertTrue($countdown->is_published);
        $this->assertSame('Taiwan Invasion', $countdown->title['en']);
        $this->assertSame('Invasione di Taiwan', $countdown->title['it']);
        $this->assertSame('images/doomsday/taiwan_invasion.png', $countdown->image_path);
        $this->assertFileExists(public_path('images/doomsday/taiwan_invasion.png'));
        $this->assertIsArray(getimagesize(public_path('images/doomsday/taiwan_invasion.png')) ?: null);
        $this->assertCount(3, $countdown->projections);
        $this->assertGreaterThanOrEqual(6, $countdown->visualizations->count());
        $this->assertGreaterThanOrEqual(6, $countdown->news->whereNotNull('source_url')->count());
        $this->assertGreaterThanOrEqual(5, $countdown->initiatives->whereNotNull('url')->count());
    }

    public function test_public_routes_and_json_endpoints_expose_taiwan_payload_integrity(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $this->get('/?lang=en')
            ->assertOk()
            ->assertSee('Doomsday Countdown')
            ->assertSee('taiwan-invasion');

        $this->get('/countdowns/taiwan-invasion?lang=it')
            ->assertOk()
            ->assertSee('Invasione di Taiwan');

        $overview = $this->getJson(route('countdowns.data.overview', ['slug' => 'taiwan-invasion', 'lang' => 'en']));
        $overview->assertOk()
            ->assertJsonPath('data.slug', 'taiwan-invasion')
            ->assertJsonPath('data.image_url', asset('images/doomsday/taiwan_invasion.png'));

        $forecasts = $this->getJson(route('countdowns.data.forecasts', ['slug' => 'taiwan-invasion', 'lang' => 'en']))->assertOk()->json('data');
        $statistics = $this->getJson(route('countdowns.data.statistics', ['slug' => 'taiwan-invasion', 'lang' => 'en']))->assertOk()->json('data');
        $news = $this->getJson(route('countdowns.data.news', ['slug' => 'taiwan-invasion', 'lang' => 'en']))->assertOk()->json('data');
        $initiatives = $this->getJson(route('countdowns.data.initiatives', ['slug' => 'taiwan-invasion', 'lang' => 'en']))->assertOk()->json('data');

        $this->assertSame('taiwan-invasion', $forecasts['countdown_slug']);
        $this->assertCount(3, $forecasts['projections']);
        $this->assertSame(['optimistic', 'neutral', 'pessimistic'], array_column($forecasts['projections'], 'type'));

        $this->assertSame('taiwan-invasion', $statistics['countdown_slug']);
        $visualizationKeys = array_column($statistics['visualizations'], 'key');
        foreach (['key_indicators', 'pla_pressure_trend', 'economic_exposure', 'scenario_gdp_shock', 'energy_resilience'] as $requiredKey) {
            $this->assertContains($requiredKey, $visualizationKeys);
        }

        $this->assertSame('taiwan-invasion', $news['countdown_slug']);
        $this->assertGreaterThanOrEqual(6, count($news['news']));
        foreach ($news['news'] as $item) {
            $this->assertArrayHasKey('source_url', $item);
            $this->assertIsString($item['source_url']);
            $this->assertStringStartsWith('https://', $item['source_url']);
            $this->assertStringNotContainsString('example.org', $item['source_url']);
            $this->assertDoesNotMatchRegularExpression('/turn\d+/i', $item['source_url']);
        }

        $this->assertSame('taiwan-invasion', $initiatives['countdown_slug']);
        $this->assertGreaterThanOrEqual(5, count($initiatives['initiatives']));
        foreach ($initiatives['initiatives'] as $initiative) {
            $this->assertIsString($initiative['url']);
            $this->assertStringStartsWith('https://', $initiative['url']);
            $this->assertStringNotContainsString('example.org', $initiative['url']);
        }
    }

    public function test_public_source_surfaces_have_no_old_sample_or_opaque_reference_terms_and_news_links_are_external_safe(): void
    {
        $sources = [
            base_path('database/seeders/DoomsdaySeeder.php'),
            base_path('app/Services/Doomsday/CountdownPublicDataService.php'),
            base_path('resources/js/Pages/Doomsday/Home.vue'),
            base_path('resources/js/Pages/Doomsday/About.vue'),
            base_path('resources/js/Components/Doomsday/SidebarCards.vue'),
            base_path('resources/js/Components/Doomsday/NewsSection.vue'),
        ];

        $content = '';
        foreach ($sources as $source) {
            $content .= strtolower((string) file_get_contents($source));
        }

        foreach (['society-collapse', 'fall-of-europe', 'extreme-heat-breakpoint', 'uninhabitable-earth', 'example.org', 'daily monitor', 'global desk', 'sample data', 'sample scenario', 'dati campione'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $content);
        }
        $this->assertDoesNotMatchRegularExpression('/turn\d+/i', $content);

        $newsSection = (string) file_get_contents(base_path('resources/js/Components/Doomsday/NewsSection.vue'));
        $this->assertStringContainsString(':href="item.source_url ?? \'#\'"', $newsSection);
        $this->assertStringContainsString('target="_blank"', $newsSection);
        $this->assertStringContainsString('rel="noopener noreferrer"', $newsSection);
    }

    public function test_taiwan_seed_keeps_no_url_selection_runtime_contract(): void
    {
        $selection = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdaySelection.ts'));
        $lazy = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdayLazySections.ts'));
        $runtime = $selection . $lazy;

        $this->assertStringContainsString('axios.get<{ data: CountdownOverviewData }>', $selection);
        $this->assertStringContainsString("route('countdowns.data.overview'", $selection);
        $this->assertStringContainsString('axios.get<{ data: SectionDataByKey[K] }>', $lazy);
        $this->assertStringContainsString('route(sectionRouteByKey[key]', $lazy);

        foreach (['router.visit', 'router.reload', 'router.prefetch', 'history.pushState', 'window.location', 'window.fetch'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $runtime);
        }
    }
}
