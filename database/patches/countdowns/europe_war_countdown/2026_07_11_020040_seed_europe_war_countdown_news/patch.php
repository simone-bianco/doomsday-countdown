<?php

declare(strict_types=1);

use App\Models\Countdown;
use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    private const SLUG = 'europe-war-countdown';

    private const IMAGE_PATH = 'images/doomsday/europe_war_countdown.png';

    /** @var list<string> */
    private const LEGACY_SOURCE_URLS = [
        'https://eda.europa.eu/publications-and-data/thematic-policy-reports/eda-defence-data-2024-2025',
        'https://defence-industry-space.ec.europa.eu/eu-defence-industry/white-paper-european-defence-readiness-2030_en',
    ];

    public function up(): void
    {
        $countdown = $this->countdown();
        $seedNews = $this->data()->news();

        $countdown->news()->whereIn('source_url', $this->ownedSourceUrls($seedNews))->delete();

        foreach ($seedNews as $index => $news) {
            $countdown->news()->create(array_merge([
                'content_type' => 'article',
                'image_path' => self::IMAGE_PATH,
                'sort_order' => $index + 1,
                'is_featured' => $index === 0,
            ], $news));
        }
    }

    public function down(): void
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        if ($countdown !== null) {
            $countdown->news()->whereIn('source_url', $this->ownedSourceUrls($this->data()->news()))->delete();
        }
    }

    private function countdown(): Countdown
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        if (! $countdown instanceof Countdown) {
            throw new RuntimeException('Europe war countdown must exist before seeding news.');
        }

        return $countdown;
    }

    /** @param list<array<string, mixed>> $news @return list<string> */
    private function ownedSourceUrls(array $news): array
    {
        return array_values(array_unique(array_merge(
            self::LEGACY_SOURCE_URLS,
            array_map(static fn (array $item): string => (string) $item['source_url'], $news),
        )));
    }

    private function data(): object
    {
        return require __DIR__.'/data.php';
    }
};
