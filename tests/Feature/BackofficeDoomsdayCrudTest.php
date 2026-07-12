<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Countdown;
use App\Models\Initiative;
use App\Models\News;
use App\Models\Projection;
use App\Models\User;
use App\Models\Visualization;
use App\Services\Doomsday\Cache\DoomsdayCacheKeys;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

final class BackofficeDoomsdayCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_doomsday_backoffice_routes_require_authentication(): void
    {
        $this->get(route('backoffice.countdowns.index'))
            ->assertRedirect(route('login'));
    }

    public function test_countdown_index_summary_exposes_image_path_for_backoffice_table(): void
    {
        $admin = User::factory()->create();
        Countdown::query()->create($this->countdownModelAttributes());

        $this->actingAs($admin)
            ->get(route('backoffice.countdowns.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Backoffice/Countdowns/Index')
                ->where('countdowns.data.0.image_path', 'images/doomsday/test.png'));
    }

    public function test_countdown_index_filters_and_paginates_with_server_shape(): void
    {
        $admin = User::factory()->create();
        Countdown::query()->create($this->countdownModelAttributes([
            'slug' => 'alpha-risk',
            'title' => ['en' => 'Alpha risk'],
            'summary' => ['en' => 'Alpha summary.'],
            'sort_order' => 1,
        ]));
        Countdown::query()->create($this->countdownModelAttributes([
            'slug' => 'beta-risk',
            'title' => ['en' => 'Beta risk'],
            'summary' => ['en' => 'Beta summary.'],
            'sort_order' => 2,
        ]));

        $this->actingAs($admin)
            ->get(route('backoffice.countdowns.index', ['search' => 'beta']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Backoffice/Countdowns/Index')
                ->where('countdowns.filters.search', 'beta')
                ->where('countdowns.filters.sort', 'sort_order')
                ->where('countdowns.filters.direction', 'asc')
                ->where('countdowns.meta.total', 1)
                ->where('countdowns.data.0.slug', 'beta-risk'));
    }

    public function test_countdown_index_sorts_by_id_title_and_sort_order(): void
    {
        $admin = User::factory()->create();
        $alpha = Countdown::query()->create($this->countdownModelAttributes([
            'slug' => 'alpha-risk',
            'title' => ['en' => 'Alpha risk'],
            'summary' => ['en' => 'Alpha summary.'],
            'sort_order' => 30,
        ]));
        $beta = Countdown::query()->create($this->countdownModelAttributes([
            'slug' => 'beta-risk',
            'title' => ['en' => 'Beta risk'],
            'summary' => ['en' => 'Beta summary.'],
            'sort_order' => 10,
        ]));

        $this->actingAs($admin)
            ->get(route('backoffice.countdowns.index', ['sort' => 'id', 'direction' => 'desc']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('countdowns.filters.sort', 'id')
                ->where('countdowns.filters.direction', 'desc')
                ->where('countdowns.data.0.id', $beta->id));

        $this->actingAs($admin)
            ->get(route('backoffice.countdowns.index', ['sort' => 'title', 'direction' => 'desc']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('countdowns.filters.sort', 'title')
                ->where('countdowns.filters.direction', 'desc')
                ->where('countdowns.data.0.slug', 'beta-risk'));

        $this->actingAs($admin)
            ->get(route('backoffice.countdowns.index', ['sort' => 'sort_order', 'direction' => 'asc']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('countdowns.filters.sort', 'sort_order')
                ->where('countdowns.filters.direction', 'asc')
                ->where('countdowns.data.0.id', $beta->id)
                ->where('countdowns.data.1.id', $alpha->id));
    }

    public function test_countdown_edit_exposes_namespaced_relation_search_paginators(): void
    {
        $admin = User::factory()->create();
        $countdown = Countdown::query()->create($this->countdownModelAttributes());
        $projection = $countdown->projections()->create($this->projectionPayload([
            'title' => ['en' => 'Alpha scenario'],
            'summary' => ['en' => 'Projection alpha summary'],
        ]));
        $countdown->projections()->create($this->projectionPayload([
            'title' => ['en' => 'Beta scenario'],
            'summary' => ['en' => 'Projection beta summary'],
            'sort_order' => 2,
        ]));
        $visualization = $countdown->visualizations()->create($this->visualizationPayload([
            'key' => 'alpha_chart',
            'title' => ['en' => 'Alpha chart'],
        ]));
        $countdown->visualizations()->create($this->visualizationPayload([
            'key' => 'beta_chart',
            'title' => ['en' => 'Beta chart'],
            'sort_order' => 2,
        ]));
        $news = $countdown->news()->create($this->newsPayload(['title' => 'Alpha bulletin', 'source_url' => 'https://example.com/news-alpha']));
        $countdown->news()->create($this->newsPayload(['title' => 'Beta bulletin', 'source_url' => 'https://example.com/news-beta', 'sort_order' => 2]));
        $initiative = $countdown->initiatives()->create($this->initiativePayload(['title' => 'Alpha initiative']));
        $countdown->initiatives()->create($this->initiativePayload(['title' => 'Beta initiative', 'sort_order' => 2]));

        $this->actingAs($admin)
            ->get(route('backoffice.countdowns.edit', [
                'countdown' => $countdown,
                'projections_search' => 'alpha',
                'visualizations_search' => 'alpha',
                'news_search' => 'alpha',
                'initiatives_search' => 'alpha',
            ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Backoffice/Countdowns/Edit')
                ->where('countdown.projections.filters.search', 'alpha')
                ->where('countdown.projections.meta.total', 1)
                ->where('countdown.projections.data.0.id', $projection->id)
                ->where('countdown.visualizations.filters.search', 'alpha')
                ->where('countdown.visualizations.meta.total', 1)
                ->where('countdown.visualizations.data.0.id', $visualization->id)
                ->where('countdown.news.filters.search', 'alpha')
                ->where('countdown.news.meta.total', 1)
                ->where('countdown.news.data.0.id', $news->id)
                ->where('countdown.initiatives.filters.search', 'alpha')
                ->where('countdown.initiatives.meta.total', 1)
                ->where('countdown.initiatives.data.0.id', $initiative->id));
    }

    public function test_authenticated_user_can_manage_countdowns_projections_and_visualizations_with_cleanup(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('backoffice.countdowns.store'), $this->countdownPayload())
            ->assertRedirect()
            ->assertSessionHas('success', 'Countdown created.');

        $countdown = Countdown::query()->where('slug', 'test-countdown')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('backoffice.countdowns.update', $countdown), $this->countdownPayload(['slug' => 'test-countdown-updated']))
            ->assertRedirect()
            ->assertSessionHas('success', 'Countdown updated.');

        $countdown->refresh();
        $this->assertSame('test-countdown-updated', $countdown->slug);

        $this->actingAs($admin)
            ->post(route('backoffice.countdowns.projections.store', $countdown), $this->projectionPayload())
            ->assertRedirect()
            ->assertSessionHas('success', 'Projection created.');

        $projection = Projection::query()->where('countdown_id', $countdown->id)->firstOrFail();

        $this->actingAs($admin)
            ->post(route('backoffice.countdowns.visualizations.store', $countdown), $this->visualizationPayload(['key' => 'countdown_curve']))
            ->assertRedirect()
            ->assertSessionHas('success', 'Visualization created.');

        $this->actingAs($admin)
            ->post(route('backoffice.countdowns.projections.visualizations.store', [$countdown, $projection]), $this->visualizationPayload([
                'key' => 'projection_kpis',
                'type' => 'kpi',
                'payload' => ['items' => [['label' => 'Confidence', 'value' => '70%']]],
                'schema_version' => 1,
            ]))
            ->assertRedirect()
            ->assertSessionHas('success', 'Visualization created.');

        $this->assertSame(2, Visualization::query()->count());
        $projectionVisualization = Visualization::query()->where('key', 'projection_kpis')->firstOrFail();

        $this->actingAs($admin)
            ->delete(route('backoffice.countdowns.projections.destroy', [$countdown, $projection]))
            ->assertRedirect()
            ->assertSessionHas('success', 'Projection deleted.');

        $this->assertDatabaseMissing('projections', ['id' => $projection->id]);
        $this->assertDatabaseMissing('visualizations', ['id' => $projectionVisualization->id]);
        $this->assertDatabaseHas('visualizations', ['key' => 'countdown_curve']);

        $this->actingAs($admin)
            ->delete(route('backoffice.countdowns.destroy', $countdown))
            ->assertRedirect(route('backoffice.countdowns.index'))
            ->assertSessionHas('success', 'Countdown deleted.');

        $this->assertDatabaseMissing('countdowns', ['id' => $countdown->id]);
        $this->assertSame(0, Visualization::query()->count());
    }

    public function test_authenticated_user_can_manage_countdown_news_and_initiatives(): void
    {
        $admin = User::factory()->create();
        $countdown = Countdown::query()->create($this->countdownModelAttributes());

        $this->actingAs($admin)
            ->post(route('backoffice.countdowns.news.store', $countdown), $this->newsPayload())
            ->assertRedirect()
            ->assertSessionHas('success', 'News item created.');

        $news = News::query()->where('countdown_id', $countdown->id)->firstOrFail();

        $this->actingAs($admin)
            ->put(route('backoffice.countdowns.news.update', [$countdown, $news]), $this->newsPayload(['title' => 'Updated news']))
            ->assertRedirect()
            ->assertSessionHas('success', 'News item updated.');

        $this->assertDatabaseHas('news', ['id' => $news->id, 'title' => 'Updated news']);

        $this->actingAs($admin)
            ->post(route('backoffice.countdowns.initiatives.store', $countdown), $this->initiativePayload())
            ->assertRedirect()
            ->assertSessionHas('success', 'Initiative created.');

        $initiative = Initiative::query()->where('countdown_id', $countdown->id)->firstOrFail();

        $this->actingAs($admin)
            ->put(route('backoffice.countdowns.initiatives.update', [$countdown, $initiative]), $this->initiativePayload(['title' => 'Updated initiative']))
            ->assertRedirect()
            ->assertSessionHas('success', 'Initiative updated.');

        $this->assertDatabaseHas('initiatives', ['id' => $initiative->id, 'title' => 'Updated initiative']);

        $this->actingAs($admin)
            ->delete(route('backoffice.countdowns.news.destroy', [$countdown, $news]))
            ->assertRedirect()
            ->assertSessionHas('success', 'News item deleted.');

        $this->actingAs($admin)
            ->delete(route('backoffice.countdowns.initiatives.destroy', [$countdown, $initiative]))
            ->assertRedirect()
            ->assertSessionHas('success', 'Initiative deleted.');

        $this->assertDatabaseMissing('news', ['id' => $news->id]);
        $this->assertDatabaseMissing('initiatives', ['id' => $initiative->id]);
    }

    public function test_countdown_slug_and_visualization_payload_are_validated(): void
    {
        $admin = User::factory()->create();
        $countdown = Countdown::query()->create($this->countdownModelAttributes());

        $this->actingAs($admin)
            ->post(route('backoffice.countdowns.store'), $this->countdownPayload(['slug' => $countdown->slug]))
            ->assertSessionHasErrors('slug');

        $this->actingAs($admin)
            ->post(route('backoffice.countdowns.visualizations.store', $countdown), $this->visualizationPayload([
                'payload' => ['labels' => ['A', 'B'], 'series' => [1, 2]],
                'schema_version' => 1,
            ]))
            ->assertSessionHasErrors('schema_version');

        $this->actingAs($admin)
            ->post(route('backoffice.countdowns.visualizations.store', $countdown), $this->visualizationPayload([
                'payload' => array_replace_recursive($this->visualizationPayload()['payload'], ['series' => [1]]),
            ]))
            ->assertSessionHasErrors('payload.series');

        $this->actingAs($admin)
            ->post(route('backoffice.countdowns.visualizations.store', $countdown), $this->visualizationPayload([
                'type' => 'bar',
                'payload' => array_replace_recursive($this->visualizationPayload()['payload'], ['axes' => ['x' => ['type' => 'ordinal']]]),
            ]))
            ->assertSessionHasErrors('payload.axes.x.type');

        $this->actingAs($admin)
            ->post(route('backoffice.countdowns.visualizations.store', $countdown), $this->visualizationPayload([
                'payload' => array_replace_recursive($this->visualizationPayload()['payload'], [
                    'series' => [['name' => 'Mixed', 'values' => [1, 2], 'unit' => 'days']],
                ]),
            ]))
            ->assertSessionHasErrors('payload.series');

        $this->actingAs($admin)
            ->post(route('backoffice.countdowns.visualizations.store', $countdown), $this->visualizationPayload([
                'type' => 'bar',
                'key' => 'categorical_bar',
                'reasoning' => ['en' => 'Direct categorical values from the cited source.'],
                'payload' => [
                    'labels' => ['A', 'B'],
                    'series' => [['name' => 'Exposure', 'values' => [1.2, 2.4]]],
                    'axes' => [
                        'x' => ['label' => 'Category', 'type' => 'category'],
                        'y' => ['label' => 'Exposure', 'unit' => 'US$ trillion', 'format' => 'currency'],
                    ],
                ],
            ]))
            ->assertRedirect()
            ->assertSessionHas('success', 'Visualization created.');

        $this->assertDatabaseHas('visualizations', ['key' => 'categorical_bar', 'type' => 'bar', 'schema_version' => 2]);
        $this->assertSame(1, Visualization::query()->count());
    }

    public function test_nested_backoffice_mutations_reject_records_from_another_countdown(): void
    {
        $admin = User::factory()->create();
        $countdown = Countdown::query()->create($this->countdownModelAttributes());
        $otherCountdown = Countdown::query()->create($this->countdownModelAttributes([
            'slug' => 'other-countdown',
            'title' => ['en' => 'Other countdown'],
            'summary' => ['en' => 'Other countdown summary.'],
        ]));

        $projection = $countdown->projections()->create($this->projectionPayload());
        $countdownVisualization = $countdown->visualizations()->create($this->visualizationPayload(['key' => 'countdown_scope']));
        $projectionVisualization = $projection->visualizations()->create($this->visualizationPayload(['key' => 'projection_scope']));
        $news = $countdown->news()->create($this->newsPayload());
        $initiative = $countdown->initiatives()->create($this->initiativePayload());

        $this->actingAs($admin)
            ->put(route('backoffice.countdowns.projections.update', [$otherCountdown, $projection]), $this->projectionPayload([
                'title' => ['en' => 'Cross-countdown projection update'],
            ]))
            ->assertNotFound();

        $this->actingAs($admin)
            ->delete(route('backoffice.countdowns.visualizations.destroy', [$otherCountdown, $countdownVisualization]))
            ->assertNotFound();

        $this->actingAs($admin)
            ->delete(route('backoffice.countdowns.projections.visualizations.destroy', [$otherCountdown, $projection, $projectionVisualization]))
            ->assertNotFound();

        $this->actingAs($admin)
            ->put(route('backoffice.countdowns.news.update', [$otherCountdown, $news]), $this->newsPayload(['title' => 'Cross-countdown news update']))
            ->assertNotFound();

        $this->actingAs($admin)
            ->put(route('backoffice.countdowns.initiatives.update', [$otherCountdown, $initiative]), $this->initiativePayload(['title' => 'Cross-countdown initiative update']))
            ->assertNotFound();

        $this->assertSame(['en' => 'Projection'], $projection->refresh()->title);
        $this->assertDatabaseHas('visualizations', ['id' => $countdownVisualization->id]);
        $this->assertDatabaseHas('visualizations', ['id' => $projectionVisualization->id]);
        $this->assertSame('Test news', $news->refresh()->title);
        $this->assertSame('Test initiative', $initiative->refresh()->title);
    }

    public function test_backoffice_countdown_update_purges_public_cache_for_old_and_new_slug(): void
    {
        config(['doomsday.cache.enabled' => true]);
        Cache::flush();

        $admin = User::factory()->create();
        $countdown = Countdown::query()->create($this->countdownModelAttributes(['slug' => 'cache-old-countdown']));
        $oldSlug = $countdown->slug;
        $newSlug = 'cache-new-countdown';

        Cache::put(DoomsdayCacheKeys::index('en'), ['stale' => true], 3600);
        Cache::put(DoomsdayCacheKeys::overview($oldSlug, 'en'), ['stale' => true], 3600);
        Cache::put(DoomsdayCacheKeys::news($oldSlug, 'it'), ['stale' => true], 3600);
        Cache::put(DoomsdayCacheKeys::statistics($newSlug, 'en'), ['stale' => true], 3600);

        $this->assertTrue(Cache::has(DoomsdayCacheKeys::index('en')));
        $this->assertTrue(Cache::has(DoomsdayCacheKeys::overview($oldSlug, 'en')));
        $this->assertTrue(Cache::has(DoomsdayCacheKeys::news($oldSlug, 'it')));
        $this->assertTrue(Cache::has(DoomsdayCacheKeys::statistics($newSlug, 'en')));

        $this->actingAs($admin)
            ->put(route('backoffice.countdowns.update', $countdown), $this->countdownPayload(['slug' => $newSlug]))
            ->assertRedirect()
            ->assertSessionHas('success', 'Countdown updated.');

        $this->assertFalse(Cache::has(DoomsdayCacheKeys::index('en')));
        $this->assertFalse(Cache::has(DoomsdayCacheKeys::overview($oldSlug, 'en')));
        $this->assertFalse(Cache::has(DoomsdayCacheKeys::news($oldSlug, 'it')));
        $this->assertFalse(Cache::has(DoomsdayCacheKeys::statistics($newSlug, 'en')));
    }

    /** @param array<string, mixed> $overrides @return array<string, mixed> */
    private function countdownPayload(array $overrides = []): array
    {
        return array_merge($this->countdownModelAttributes(), $overrides);
    }

    /** @param array<string, mixed> $overrides @return array<string, mixed> */
    private function countdownModelAttributes(array $overrides = []): array
    {
        return array_merge([
            'slug' => 'test-countdown',
            'title' => ['en' => 'Test countdown'],
            'summary' => ['en' => 'A test countdown summary.'],
            'description' => ['en' => 'Longer description.'],
            'causes' => ['en' => ['Cause one']],
            'consequences' => ['en' => ['Consequence one']],
            'recommended_actions' => ['en' => ['Action one']],
            'severity' => 'high',
            'status' => 'active',
            'target_date' => '2030-01-01 00:00:00',
            'image_path' => 'images/doomsday/test.png',
            'accent_color' => '#ff2a23',
            'sort_order' => 10,
            'is_published' => true,
        ], $overrides);
    }

    /** @param array<string, mixed> $overrides @return array<string, mixed> */
    private function projectionPayload(array $overrides = []): array
    {
        return array_merge([
            'type' => 'neutral',
            'target_date' => '2031-01-01 00:00:00',
            'title' => ['en' => 'Projection'],
            'summary' => ['en' => 'Projection summary'],
            'confidence_score' => 70,
            'probability_score' => 55,
            'trend' => 'stable',
            'methodology' => ['en' => 'Modelled from test data.'],
            'sort_order' => 1,
        ], $overrides);
    }

    /** @param array<string, mixed> $overrides @return array<string, mixed> */
    private function visualizationPayload(array $overrides = []): array
    {
        return array_merge([
            'key' => 'projection_curve',
            'type' => 'line',
            'title' => ['en' => 'Projection curve'],
            'description' => ['en' => 'Projection chart.'],
            'sources' => ['https://example.com/source'],
            'reasoning' => ['en' => 'Values are copied directly from the cited test source.'],
            'payload' => [
                'labels' => ['2026', '2027'],
                'series' => [['name' => 'Risk', 'values' => [10, 20]]],
                'axes' => [
                    'x' => ['label' => 'Year', 'type' => 'temporal'],
                    'y' => ['label' => 'Risk', 'unit' => '%', 'format' => 'percent'],
                ],
            ],
            'schema_version' => 2,
            'sort_order' => 1,
        ], $overrides);
    }

    /** @param array<string, mixed> $overrides @return array<string, mixed> */
    private function newsPayload(array $overrides = []): array
    {
        return array_merge([
            'locale' => 'en',
            'title' => 'Test news',
            'excerpt' => 'News excerpt.',
            'source_name' => 'Source',
            'source_url' => 'https://example.com/news',
            'image_path' => 'images/doomsday/news.png',
            'published_at' => '2029-01-01 00:00:00',
            'sort_order' => 1,
            'is_featured' => true,
        ], $overrides);
    }

    /** @param array<string, mixed> $overrides @return array<string, mixed> */
    private function initiativePayload(array $overrides = []): array
    {
        return array_merge([
            'locale' => 'en',
            'type' => 'campaign',
            'title' => 'Test initiative',
            'excerpt' => 'Initiative excerpt.',
            'body' => 'Initiative body.',
            'organization' => 'Test org',
            'url' => 'https://example.com/initiative',
            'image_path' => 'images/doomsday/initiative.png',
            'cta_label' => 'Act now',
            'starts_at' => '2029-01-01 00:00:00',
            'ends_at' => '2029-02-01 00:00:00',
            'sort_order' => 1,
            'is_featured' => true,
        ], $overrides);
    }
}
