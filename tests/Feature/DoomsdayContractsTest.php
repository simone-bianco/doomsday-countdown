<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use App\Enums\InitiativeLocale;
use App\Enums\InitiativeType;
use App\Enums\NewsLocale;
use App\Enums\ProjectionType;
use App\Enums\VisualizationType;
use App\Models\Countdown;
use App\Services\Doomsday\CountdownPublicDataService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdayContractsTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        $this->travelBack();

        parent::tearDown();
    }

    public function test_active_projection_keeps_pessimistic_before_and_at_target_then_rolls_to_neutral(): void
    {
        $countdown = $this->countdown('boundary-check');
        $countdown->projections()->create($this->projection(ProjectionType::Pessimistic, 'Pessimistic', '2026-07-12 01:00:00'));
        $countdown->projections()->create($this->projection(ProjectionType::Neutral, 'Neutral', '2027-01-01 00:00:00'));
        $countdown->projections()->create($this->projection(ProjectionType::Optimistic, 'Optimistic', '2028-01-01 00:00:00'));

        $this->freezeAt('2026-07-12 00:00:00');
        $this->assertActiveProjection($countdown, ProjectionType::Pessimistic, '2026-07-12 01:00:00');

        $this->freezeAt('2026-07-12 01:00:00');
        $this->assertActiveProjection($countdown, ProjectionType::Pessimistic, '2026-07-12 01:00:00');

        $this->freezeAt('2026-07-12 01:00:01');
        $this->assertActiveProjection($countdown, ProjectionType::Neutral, '2027-01-01 00:00:00');
    }

    public function test_active_projection_rolls_from_neutral_to_optimistic_and_uses_latest_expired_fallback(): void
    {
        $countdown = $this->countdown('rollover-check');
        $countdown->projections()->create($this->projection(ProjectionType::Pessimistic, 'Pessimistic', '2026-01-01 00:00:00'));
        $countdown->projections()->create($this->projection(ProjectionType::Neutral, 'Neutral', '2026-07-12 01:00:00'));
        $countdown->projections()->create($this->projection(ProjectionType::Optimistic, 'Optimistic', '2026-07-13 00:00:00'));

        $this->freezeAt('2026-07-12 01:00:01');
        $this->assertActiveProjection($countdown, ProjectionType::Optimistic, '2026-07-13 00:00:00');

        $this->freezeAt('2026-07-13 00:00:01');
        $this->assertActiveProjection($countdown, ProjectionType::Optimistic, '2026-07-13 00:00:00');
    }

    public function test_active_projection_skips_missing_pessimistic_type(): void
    {
        $countdown = $this->countdown('missing-type-check');
        $countdown->projections()->create($this->projection(ProjectionType::Neutral, 'Neutral', '2027-01-01 00:00:00'));
        $countdown->projections()->create($this->projection(ProjectionType::Optimistic, 'Optimistic', '2028-01-01 00:00:00'));

        $this->freezeAt('2026-07-12 00:00:00');
        $this->assertActiveProjection($countdown, ProjectionType::Neutral, '2027-01-01 00:00:00');
    }

    public function test_active_projection_ignores_null_pessimistic_date_for_future_selection(): void
    {
        $countdown = $this->countdown('null-date-check');
        $countdown->projections()->create($this->projection(ProjectionType::Pessimistic, 'Pessimistic without date', null));
        $countdown->projections()->create($this->projection(ProjectionType::Neutral, 'Neutral', '2027-01-01 00:00:00'));

        $this->freezeAt('2026-07-12 00:00:00');
        $this->assertActiveProjection($countdown, ProjectionType::Neutral, '2027-01-01 00:00:00');
    }

    public function test_active_projection_uses_chain_priority_when_all_dates_are_null(): void
    {
        $countdown = $this->countdown('all-null-check');
        $countdown->projections()->create($this->projection(ProjectionType::Other, 'Other', null, 1));
        $countdown->projections()->create($this->projection(ProjectionType::Optimistic, 'Optimistic', null, 1));
        $countdown->projections()->create($this->projection(ProjectionType::Neutral, 'Neutral', null, 1));
        $countdown->projections()->create($this->projection(ProjectionType::Pessimistic, 'Pessimistic', null, 2));

        $this->freezeAt('2026-07-12 00:00:00');
        $this->assertActiveProjection($countdown, ProjectionType::Pessimistic, null);
    }

    public function test_active_projection_supports_only_other(): void
    {
        $countdown = $this->countdown('only-other-check');
        $countdown->projections()->create($this->projection(ProjectionType::Other, 'Other', '2027-01-01 00:00:00'));

        $this->freezeAt('2026-07-12 00:00:00');
        $this->assertActiveProjection($countdown, ProjectionType::Other, '2027-01-01 00:00:00');
    }

    public function test_no_projections_uses_countdown_target_and_null_main_projection(): void
    {
        $countdown = $this->countdown('no-projections-check', true, '2042-05-06 07:08:09');

        $this->freezeAt('2026-07-12 00:00:00');
        $this->assertActiveProjection($countdown, null, '2042-05-06 07:08:09');
    }

    public function test_all_expired_fallback_uses_latest_date_then_chain_priority(): void
    {
        $countdown = $this->countdown('expired-fallback-check');
        $countdown->projections()->create($this->projection(ProjectionType::Pessimistic, 'Older pessimistic', '2026-07-10 00:00:00'));
        $countdown->projections()->create($this->projection(ProjectionType::Optimistic, 'Latest optimistic', '2026-07-11 00:00:00'));
        $countdown->projections()->create($this->projection(ProjectionType::Neutral, 'Latest neutral', '2026-07-11 00:00:00'));

        $this->freezeAt('2026-07-12 00:00:00');
        $this->assertActiveProjection($countdown, ProjectionType::Neutral, '2026-07-11 00:00:00', 'Latest neutral');
    }

    public function test_equal_future_targets_use_sort_order_then_id(): void
    {
        $countdown = $this->countdown('tie-break-check');
        $countdown->projections()->create($this->projection(ProjectionType::Pessimistic, 'Higher sort', '2027-01-01 00:00:00', 2));
        $expected = $countdown->projections()->create($this->projection(ProjectionType::Pessimistic, 'Lower sort and id', '2027-01-01 00:00:00', 1));
        $countdown->projections()->create($this->projection(ProjectionType::Pessimistic, 'Lower sort higher id', '2027-01-01 00:00:00', 1));

        $this->freezeAt('2026-07-12 00:00:00');
        $this->assertActiveProjection($countdown, ProjectionType::Pessimistic, '2027-01-01 00:00:00', $expected->title['en']);
    }

    public function test_index_and_overview_return_the_same_active_projection(): void
    {
        $countdown = $this->countdown('index-overview-check');
        $countdown->projections()->create($this->projection(ProjectionType::Pessimistic, 'Pessimistic', '2027-01-01 00:00:00'));
        $countdown->projections()->create($this->projection(ProjectionType::Neutral, 'Neutral', '2030-01-01 00:00:00'));

        $this->freezeAt('2026-07-12 00:00:00');
        $this->assertActiveProjection($countdown, ProjectionType::Pessimistic, '2027-01-01 00:00:00');
    }

    public function test_forecasts_find_canonical_chart_on_neutral_while_pessimistic_is_active(): void
    {
        $countdown = $this->countdown('forecast-chart-check');
        $countdown->projections()->create($this->projection(ProjectionType::Pessimistic, 'Pessimistic', '2027-01-01 00:00:00', 3));
        $neutral = $countdown->projections()->create($this->projection(ProjectionType::Neutral, 'Neutral', '2030-01-01 00:00:00', 2));
        $neutral->visualizations()->create([
            'key' => 'projection_curve',
            'type' => VisualizationType::Line,
            'title' => ['en' => 'Projection curve'],
            'description' => ['en' => 'Canonical chart on Neutral.'],
            'sources' => ['https://example.org/source'],
            'reasoning' => ['en' => 'Deterministic chart fixture.'],
            'payload' => [
                'labels' => ['2026', '2030'],
                'series' => [10, 20],
                'axes' => [
                    'x' => ['label' => 'Year', 'type' => 'temporal'],
                    'y' => ['label' => 'Index', 'unit' => 'index points', 'format' => 'integer'],
                ],
            ],
            'schema_version' => 2,
            'sort_order' => 1,
        ]);

        $this->freezeAt('2026-07-12 00:00:00');
        $this->assertActiveProjection($countdown, ProjectionType::Pessimistic, '2027-01-01 00:00:00');

        $forecasts = app(CountdownPublicDataService::class)->forecasts($countdown->slug, 'en');
        $this->assertSame('projection_curve', $forecasts['projection_chart']['key'] ?? null);
        $this->assertCount(2, $forecasts['projections'] ?? []);
    }

    public function test_news_locale_filter_includes_all_and_current_locale_only(): void
    {
        $countdown = $this->countdown('locale-check');
        $countdown->projections()->create($this->projection(ProjectionType::Neutral, 'Neutral'));
        $countdown->news()->create(['locale' => NewsLocale::All, 'title' => 'Shared', 'excerpt' => 'Shared note']);
        $countdown->news()->create(['locale' => NewsLocale::It, 'title' => 'Italiano', 'excerpt' => 'Nota italiana']);
        $countdown->news()->create(['locale' => NewsLocale::En, 'title' => 'English', 'excerpt' => 'English note']);

        $section = app(CountdownPublicDataService::class)->newsSection('locale-check', 'it');
        $titles = collect($section['news'] ?? [])->pluck('title')->all();

        $this->assertContains('Shared', $titles);
        $this->assertContains('Italiano', $titles);
        $this->assertNotContains('English', $titles);
    }

    public function test_initiatives_locale_filter_includes_all_and_current_locale_only(): void
    {
        $countdown = $this->countdown('initiative-locale-check');
        $countdown->initiatives()->create($this->initiative(InitiativeLocale::All, 'Shared initiative'));
        $countdown->initiatives()->create($this->initiative(InitiativeLocale::It, 'Iniziativa italiana'));
        $countdown->initiatives()->create($this->initiative(InitiativeLocale::En, 'English initiative'));

        $section = app(CountdownPublicDataService::class)->initiativesSection('initiative-locale-check', 'it');
        $titles = collect($section['initiatives'] ?? [])->pluck('title')->all();

        $this->assertContains('Shared initiative', $titles);
        $this->assertContains('Iniziativa italiana', $titles);
        $this->assertNotContains('English initiative', $titles);
    }

    public function test_unpublished_countdowns_are_excluded_and_not_routable(): void
    {
        $this->countdown('hidden', false);

        $this->get('/countdowns/hidden')->assertNotFound();
    }

    public function test_seeded_public_asset_paths_do_not_point_to_z_docs(): void
    {
        $countdown = $this->countdown('asset-check');

        $this->assertStringStartsWith('images/doomsday/', $countdown->image_path);
        $this->assertStringNotContainsString('z-docs', $countdown->image_path);
    }

    private function assertActiveProjection(Countdown $countdown, ?ProjectionType $expectedType, ?string $expectedTarget, ?string $expectedTitle = null): void
    {
        $service = app(CountdownPublicDataService::class);
        $indexItem = collect($service->indexPayload('en')['countdowns'])->firstWhere('slug', $countdown->slug);
        $overview = $service->overview($countdown->slug, 'en');

        $this->assertNotNull($indexItem);
        $this->assertNotNull($overview);
        $this->assertSame($expectedType?->value, $indexItem['main_projection']['type'] ?? null);
        $this->assertSame($expectedType?->value, $overview['main_projection']['type'] ?? null);
        $this->assertSame($indexItem['main_projection']['type'] ?? null, $overview['main_projection']['type'] ?? null);
        $this->assertUtcTimestamp($expectedTarget, $indexItem['timer']['target_date'] ?? null);
        $this->assertUtcTimestamp($expectedTarget, $overview['timer']['target_date'] ?? null);
        $this->assertUtcTimestamp($indexItem['timer']['target_date'] ?? null, $overview['timer']['target_date'] ?? null);

        if ($expectedType !== null) {
            $this->assertUtcTimestamp($expectedTarget, $indexItem['main_projection']['target_date'] ?? null);
            $this->assertUtcTimestamp($expectedTarget, $overview['main_projection']['target_date'] ?? null);
        }

        if ($expectedTitle !== null) {
            $this->assertSame($expectedTitle, $indexItem['main_projection']['title'] ?? null);
            $this->assertSame($expectedTitle, $overview['main_projection']['title'] ?? null);
        }
    }

    private function assertUtcTimestamp(?string $expected, ?string $actual): void
    {
        if ($expected === null) {
            $this->assertNull($actual);

            return;
        }

        $this->assertNotNull($actual);
        $this->assertSame(
            CarbonImmutable::parse($expected, 'UTC')->format('Y-m-d H:i:s'),
            CarbonImmutable::parse($actual)->utc()->format('Y-m-d H:i:s'),
        );
    }

    private function freezeAt(string $date): void
    {
        $this->travelTo(CarbonImmutable::parse($date, 'UTC'));
    }

    /** @return array<string, mixed> */
    private function projection(ProjectionType $type, string $title, ?string $targetDate = '2027-01-01 00:00:00', int $sortOrder = 1): array
    {
        return [
            'type' => $type,
            'target_date' => $targetDate !== null ? CarbonImmutable::parse($targetDate, 'UTC') : null,
            'title' => ['en' => $title],
            'summary' => ['en' => $title.' summary'],
            'confidence_score' => 60,
            'probability_score' => 70,
            'trend' => 'stable',
            'sort_order' => $sortOrder,
        ];
    }

    /** @return array<string, mixed> */
    private function initiative(InitiativeLocale $locale, string $title): array
    {
        return [
            'locale' => $locale,
            'type' => InitiativeType::Campaign,
            'title' => $title,
            'excerpt' => $title.' excerpt',
            'organization' => 'Test Org',
            'url' => 'https://example.org/test',
            'sort_order' => 1,
        ];
    }

    private function countdown(string $slug, bool $published = true, string $targetDate = '2040-01-01 00:00:00'): Countdown
    {
        return Countdown::query()->create([
            'slug' => $slug,
            'title' => ['en' => 'Sample'],
            'summary' => ['en' => 'Sample summary'],
            'description' => ['en' => 'Sample description'],
            'severity' => CountdownSeverity::High,
            'status' => CountdownStatus::Active,
            'target_date' => CarbonImmutable::parse($targetDate, 'UTC'),
            'image_path' => 'images/doomsday/society_collapse_separate.png',
            'is_published' => $published,
            'sort_order' => 1,
        ]);
    }
}
