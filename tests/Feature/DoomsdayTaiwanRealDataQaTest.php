<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\InitiativeType;
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
        $countdowns = Countdown::query()->with(['projections', 'visualizations', 'news', 'initiatives', 'contentSources'])->get();
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
        $this->assertGreaterThanOrEqual(10, $countdown->news->whereNotNull('source_url')->count());
        $this->assertGreaterThanOrEqual(8, $countdown->initiatives->whereNotNull('url')->count());

        $chinaUncensoredSource = $countdown->contentSources->firstWhere('external_id', 'UCgFP46yVT-GG4o1TgXn-04Q');
        $this->assertNotNull($chinaUncensoredSource);
        $this->assertSame(120, (int) $chinaUncensoredSource->pivot->weight);
        $this->assertTrue((bool) $chinaUncensoredSource->pivot->is_active);
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
        $this->assertGreaterThanOrEqual(9, count($news['news']));
        foreach ($news['news'] as $item) {
            $this->assertNotSame('', trim($item['title']));
            $this->assertNotSame('', trim($item['excerpt']));
            $this->assertStringStartsWith('https://', $item['source_url']);
            $this->assertStringStartsWith('https://', $item['image_url']);
            $this->assertStringNotContainsString('example.org', $item['source_url']);
            $this->assertDoesNotMatchRegularExpression('/turn\d+/i', $item['source_url']);
            if ($item['content_type'] === 'youtube_video') {
                $this->assertSame('youtube', $item['external_provider']);
                $this->assertMatchesRegularExpression('#^https://www\.youtube\.com/watch\?v=[A-Za-z0-9_-]{11}$#', $item['source_url']);
                $this->assertMatchesRegularExpression('#^https://www\.youtube\.com/embed/[A-Za-z0-9_-]{11}$#', $item['embed_url']);
                $this->assertMatchesRegularExpression('#^https://i\.ytimg\.com/vi/[A-Za-z0-9_-]{11}/hqdefault\.jpg$#', $item['image_url']);
            }
        }

        $this->assertCount(count($news['news']), array_unique(array_column($news['news'], 'image_url')));

        $chinaUncensoredVideos = array_values(array_filter(
            $news['news'],
            static fn (array $item): bool => $item['content_type'] === 'youtube_video' && $item['source_name'] === 'China Uncensored',
        ));
        $this->assertCount(2, $chinaUncensoredVideos);
        $this->assertContains(true, array_column($chinaUncensoredVideos, 'is_featured'));

        $this->assertSame('taiwan-invasion', $initiatives['countdown_slug']);
        $this->assertGreaterThanOrEqual(7, count($initiatives['initiatives']));
        foreach ($initiatives['initiatives'] as $initiative) {
            $this->assertNotSame('', trim($initiative['title']));
            $this->assertNotSame('', trim($initiative['excerpt']));
            $this->assertStringStartsWith('https://', $initiative['url']);
            $this->assertStringStartsWith('https://', $initiative['image_url']);
            $this->assertStringNotContainsString('example.org', $initiative['url']);
            if ($initiative['content_type'] === 'youtube_video') {
                $this->assertSame('youtube', $initiative['external_provider']);
                $this->assertMatchesRegularExpression('#^https://www\.youtube\.com/watch\?v=[A-Za-z0-9_-]{11}$#', $initiative['url']);
                $this->assertMatchesRegularExpression('#^https://www\.youtube\.com/embed/[A-Za-z0-9_-]{11}$#', $initiative['embed_url']);
                $this->assertMatchesRegularExpression('#^https://i\.ytimg\.com/vi/[A-Za-z0-9_-]{11}/hqdefault\.jpg$#', $initiative['image_url']);
            }
        }

        $this->assertCount(count($initiatives['initiatives']), array_unique(array_column($initiatives['initiatives'], 'image_url')));

        $initiativeVideos = array_values(array_filter(
            $initiatives['initiatives'],
            static fn (array $initiative): bool => $initiative['content_type'] === 'youtube_video',
        ));
        $this->assertCount(1, $initiativeVideos);
        $this->assertSame('resource', $initiativeVideos[0]['type']);
        $this->assertSame('Global Taiwan Institute', $initiativeVideos[0]['organization']);
    }

    public function test_taiwan_content_patches_are_idempotent_and_rollback_only_owned_records(): void
    {
        $this->seed(DoomsdaySeeder::class);
        $countdown = Countdown::query()->where('slug', 'taiwan-invasion')->firstOrFail();
        $newsCount = $countdown->news()->count();
        $initiativeCount = $countdown->initiatives()->count();

        $newsPatch = require base_path('database/patches/countdowns/taiwan_invasion/2026_07_09_010040_seed_taiwan_invasion_news/patch.php');
        $initiativePatch = require base_path('database/patches/countdowns/taiwan_invasion/2026_07_09_010060_seed_taiwan_invasion_initiatives/patch.php');

        $newsPatch->up();
        $initiativePatch->up();
        $newsPatch->up();
        $initiativePatch->up();

        $this->assertSame($newsCount, $countdown->news()->count());
        $this->assertSame($initiativeCount, $countdown->initiatives()->count());

        $unownedNews = $countdown->news()->create([
            'locale' => 'all',
            'title' => 'Unowned news rollback sentinel',
            'excerpt' => 'A record outside the patch-owned URL set.',
            'content_type' => 'article',
            'source_name' => 'Rollback QA',
            'source_url' => 'https://unowned.test/taiwan-news',
            'image_path' => 'images/doomsday/taiwan_invasion.png',
        ]);
        $unownedInitiative = $countdown->initiatives()->create([
            'locale' => 'all',
            'type' => InitiativeType::Resource,
            'title' => 'Unowned initiative rollback sentinel',
            'excerpt' => 'A record outside the patch-owned URL set.',
            'url' => 'https://unowned.test/taiwan-initiative',
            'image_path' => 'images/doomsday/taiwan_invasion.png',
        ]);

        $newsPatch->down();
        $initiativePatch->down();

        $this->assertTrue($countdown->news()->whereKey($unownedNews->getKey())->exists());
        $this->assertTrue($countdown->initiatives()->whereKey($unownedInitiative->getKey())->exists());
        $this->assertFalse($countdown->news()->where('source_url', 'https://www.youtube.com/watch?v=K_D41C19l-8')->exists());
        $this->assertFalse($countdown->initiatives()->where('url', 'https://www.youtube.com/watch?v=zV6YTjz0mbQ')->exists());
    }

    public function test_public_source_surfaces_have_no_old_sample_or_opaque_reference_terms_and_external_links_are_safe(): void
    {
        $sources = [
            base_path('database/seeders/DoomsdaySeeder.php'),
            base_path('database/patches/countdowns/taiwan_invasion/_shared.php'),
            ...glob(base_path('database/patches/countdowns/taiwan_invasion/*/patch.php')),
            ...glob(base_path('database/patches/countdowns/taiwan_invasion/*/data.php')),
            base_path('app/Services/Doomsday/CountdownPublicDataService.php'),
            base_path('resources/js/Pages/Doomsday/Home.vue'),
            base_path('resources/js/Pages/Doomsday/About.vue'),
            base_path('resources/js/Components/Doomsday/SidebarCards.vue'),
            base_path('resources/js/Components/Doomsday/ContentPreviewCard.vue'),
            base_path('resources/js/Components/Doomsday/NewsSection.vue'),
            base_path('resources/js/Components/Doomsday/InitiativesSection.vue'),
        ];
        $content = '';
        foreach ($sources as $source) {
            $content .= strtolower((string) file_get_contents($source));
        }
        foreach (['society-collapse', 'fall-of-europe', 'extreme-heat-breakpoint', 'uninhabitable-earth', 'example.org', 'daily monitor', 'global desk', 'sample data', 'sample scenario', 'dati campione'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $content);
        }
        $this->assertDoesNotMatchRegularExpression('/turn\d+/i', $content);

        $previewCard = (string) file_get_contents(base_path('resources/js/Components/Doomsday/ContentPreviewCard.vue'));
        $this->assertStringContainsString(':href="href || undefined"', $previewCard);
        $this->assertStringContainsString(":target=\"href ? '_blank' : undefined\"", $previewCard);
        $this->assertStringContainsString(":rel=\"href ? 'noopener noreferrer' : undefined\"", $previewCard);
    }

    public function test_taiwan_seed_keeps_no_url_selection_runtime_contract(): void
    {
        $selection = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdaySelection.ts'));
        $lazy = (string) file_get_contents(base_path('resources/js/Composables/useDoomsdayLazySections.ts'));
        $runtime = $selection.$lazy;
        $this->assertStringContainsString('axios.get<{ data: CountdownOverviewData }>', $selection);
        $this->assertStringContainsString("route('countdowns.data.overview'", $selection);
        $this->assertStringContainsString('axios.get<{ data: SectionDataByKey[K] }>', $lazy);
        $this->assertStringContainsString('route(sectionRouteByKey[key]', $lazy);
        foreach (['router.visit', 'router.reload', 'router.prefetch', 'history.pushState', 'window.location', 'window.fetch'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $runtime);
        }
    }
}
