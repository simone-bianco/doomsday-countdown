<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use App\Enums\InitiativeLocale;
use App\Enums\InitiativeType;
use App\Enums\NewsLocale;
use App\Enums\ProjectionType;
use App\Models\Countdown;
use App\Services\Doomsday\CountdownPublicDataService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DoomsdayContractsTest extends TestCase
{
    use RefreshDatabase;

    public function test_projection_fallback_prefers_neutral_then_pessimistic_then_optimistic_then_other(): void
    {
        $countdown = $this->countdown('fallback-check');
        $countdown->projections()->create($this->projection(ProjectionType::Optimistic, 'Optimistic'));
        $countdown->projections()->create($this->projection(ProjectionType::Other, 'Other'));
        $countdown->projections()->create($this->projection(ProjectionType::Pessimistic, 'Pessimistic'));

        $service = app(CountdownPublicDataService::class);
        $overview = $service->overview('fallback-check', 'en');
        $this->assertSame('pessimistic', $overview['main_projection']['type'] ?? null);

        $countdown->projections()->create($this->projection(ProjectionType::Neutral, 'Neutral'));
        $overview = $service->overview('fallback-check', 'en');
        $this->assertSame('neutral', $overview['main_projection']['type'] ?? null);
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

    /** @return array<string, mixed> */
    private function projection(ProjectionType $type, string $title): array
    {
        return [
            'type' => $type,
            'target_date' => now()->addYear(),
            'title' => ['en' => $title],
            'summary' => ['en' => $title . ' summary'],
            'confidence_score' => 60,
            'probability_score' => 70,
            'trend' => 'stable',
            'sort_order' => 1,
        ];
    }

    /** @return array<string, mixed> */
    private function initiative(InitiativeLocale $locale, string $title): array
    {
        return [
            'locale' => $locale,
            'type' => InitiativeType::Campaign,
            'title' => $title,
            'excerpt' => $title . ' excerpt',
            'organization' => 'Test Org',
            'url' => 'https://example.org/test',
            'sort_order' => 1,
        ];
    }

    private function countdown(string $slug, bool $published = true): Countdown
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
            'is_published' => $published,
            'sort_order' => 1,
        ]);
    }
}
