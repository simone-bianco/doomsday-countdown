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
    private const LEGACY_URLS = [
        'https://eda.europa.eu/publications-and-data/defence-data',
    ];

    public function up(): void
    {
        $countdown = $this->countdown();
        $seedInitiatives = $this->data()->initiatives();

        $countdown->initiatives()->whereIn('url', $this->ownedUrls($seedInitiatives))->delete();

        foreach ($seedInitiatives as $index => $initiative) {
            $countdown->initiatives()->create(array_merge([
                'image_path' => self::IMAGE_PATH,
                'sort_order' => $index + 1,
                'is_featured' => $index === 0,
            ], $initiative));
        }
    }

    public function down(): void
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        if ($countdown !== null) {
            $countdown->initiatives()->whereIn('url', $this->ownedUrls($this->data()->initiatives()))->delete();
        }
    }

    private function countdown(): Countdown
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        if (! $countdown instanceof Countdown) {
            throw new RuntimeException('Europe war countdown must exist before seeding initiatives.');
        }

        return $countdown;
    }

    /** @param list<array<string, mixed>> $initiatives @return list<string> */
    private function ownedUrls(array $initiatives): array
    {
        return array_values(array_unique(array_merge(
            self::LEGACY_URLS,
            array_map(static fn (array $item): string => (string) $item['url'], $initiatives),
        )));
    }

    private function data(): object
    {
        return require __DIR__.'/data.php';
    }
};
