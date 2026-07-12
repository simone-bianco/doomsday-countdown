<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Countdown;
use App\Services\Doomsday\CountdownPublicDataService;
use Carbon\CarbonImmutable;
use Database\Seeders\DoomsdaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdayCountdownCatalogTest extends TestCase
{
    use RefreshDatabase;

    /** @var array<string, string> */
    private const CATALOG = [
        'taiwan-invasion' => 'images/doomsday/taiwan_invasion.png',
        'europe-war-countdown' => 'images/doomsday/europe_war_countdown.png',
        'ai-job-apocalypse' => 'images/doomsday/ai_job_apocalypse.png',
        'sixth-mass-extinction' => 'images/doomsday/uninhabitable_earth_separate.png',
        'antibiotic-apocalypse' => 'images/doomsday/antibiotic_apocalypse.png',
        'unlivable-heat' => 'images/doomsday/extreme_heat_breakpoint_separate.png',
    ];

    /** @var array<int, string> */
    private const DISPLAY_ORDER = [
        'europe-war-countdown',
        'ai-job-apocalypse',
        'taiwan-invasion',
        'sixth-mass-extinction',
        'antibiotic-apocalypse',
        'unlivable-heat',
    ];

    /** @var array<int, string> */
    private const ROLLOVER_ORDER = [
        'ai-job-apocalypse',
        'taiwan-invasion',
        'sixth-mass-extinction',
        'antibiotic-apocalypse',
        'europe-war-countdown',
        'unlivable-heat',
    ];

    /** @var array<string, string> */
    private const ACTIVE_TARGETS = [
        'taiwan-invasion' => '2027-12-31 23:59:59',
        'europe-war-countdown' => '2027-03-31 23:59:59',
        'ai-job-apocalypse' => '2027-12-02 23:59:59',
        'sixth-mass-extinction' => '2029-06-30 23:59:59',
        'antibiotic-apocalypse' => '2029-12-31 23:59:59',
        'unlivable-heat' => '2040-12-31 23:59:59',
    ];

    protected function tearDown(): void
    {
        $this->travelBack();

        parent::tearDown();
    }

    public function test_seed_creates_complete_ordered_published_catalog(): void
    {
        $this->seed(DoomsdaySeeder::class);

        $countdowns = Countdown::query()->published()->orderBy('sort_order')->get();

        $this->assertSame(array_keys(self::CATALOG), $countdowns->pluck('slug')->all());
        $this->assertSame([1, 2, 3, 4, 5, 6], $countdowns->pluck('sort_order')->all());
        $this->assertCount(6, $countdowns);

        foreach ($countdowns as $countdown) {
            $this->assertSame(self::CATALOG[$countdown->slug], $countdown->image_path);
            $this->assertFileExists(public_path($countdown->image_path));
            $this->assertIsArray(getimagesize(public_path($countdown->image_path)) ?: null);
            $this->assertCount(8, $countdown->title);
            $this->assertCount(8, $countdown->summary);
            $this->assertCount(8, $countdown->description);
        }
    }

    public function test_public_catalog_uses_same_active_pessimistic_projection_for_index_and_overview(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-07-12 00:00:00', 'UTC'));
        $this->seed(DoomsdaySeeder::class);

        $service = app(CountdownPublicDataService::class);
        $payload = $service->indexPayload('en');
        $items = collect($payload['countdowns'])->keyBy('slug');

        $this->assertSame(self::DISPLAY_ORDER, array_column($payload['countdowns'], 'slug'));

        foreach (self::ACTIVE_TARGETS as $slug => $target) {
            $item = $items->get($slug);
            $this->assertNotNull($item, $slug);
            $this->assertSame('pessimistic', $item['main_projection']['type'] ?? null, $slug.':index type');
            $this->assertUtcTimestamp($target, $item['main_projection']['target_date'] ?? null, $slug.':index projection');
            $this->assertUtcTimestamp($target, $item['timer']['target_date'] ?? null, $slug.':index timer');

            $overview = $service->overview($slug, 'en');
            $this->assertNotNull($overview, $slug);
            $this->assertSame('pessimistic', $overview['main_projection']['type'] ?? null, $slug.':overview type');
            $this->assertUtcTimestamp($target, $overview['main_projection']['target_date'] ?? null, $slug.':overview projection');
            $this->assertUtcTimestamp($target, $overview['timer']['target_date'] ?? null, $slug.':overview timer');

            $forecasts = $service->forecasts($slug, 'en');
            $this->assertNotNull($forecasts, $slug);
            $this->assertCount(3, $forecasts['projections'] ?? [], $slug);
            $this->assertSame('projection_curve', $forecasts['projection_chart']['key'] ?? null, $slug);
            $this->assertNotEmpty($forecasts['projection_chart']['payload'] ?? [], $slug);

            $countdown = Countdown::query()
                ->where('slug', $slug)
                ->with('projections.visualizations')
                ->firstOrFail();
            $canonicalCharts = $countdown->projections
                ->flatMap(fn ($projection) => $projection->visualizations)
                ->where('key', 'projection_curve');
            $this->assertCount(1, $canonicalCharts, $slug.':canonical projection curve');
        }

        $this->travelTo(CarbonImmutable::parse('2027-04-01 00:00:00', 'UTC'));
        $rolloverPayload = $service->indexPayload('en');
        $this->assertSame(self::ROLLOVER_ORDER, array_column($rolloverPayload['countdowns'], 'slug'));
    }

    private function assertUtcTimestamp(string $expected, ?string $actual, string $message): void
    {
        $this->assertNotNull($actual, $message);
        $this->assertSame($expected, CarbonImmutable::parse($actual)->utc()->format('Y-m-d H:i:s'), $message);
    }
}
