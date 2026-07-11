<?php

declare(strict_types=1);

use App\Models\Countdown;
use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    private const SLUG = 'taiwan-invasion';

    private const IMAGE_PATH = 'images/doomsday/taiwan_invasion.png';

    public function up(): void
    {
        $countdown = $this->countdown();
        $seedInitiatives = $this->data()->initiatives();

        $countdown->initiatives()
            ->whereIn('url', array_map(
                static fn (array $initiative): string => (string) $initiative['url'],
                $seedInitiatives,
            ))
            ->delete();

        foreach ($seedInitiatives as $index => $initiative) {
            $countdown->initiatives()->create(array_merge([
                'content_type' => 'article',
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
            $countdown->initiatives()
                ->whereIn('url', array_map(
                    static fn (array $initiative): string => (string) $initiative['url'],
                    $this->data()->initiatives(),
                ))
                ->delete();
        }
    }

    private function countdown(): Countdown
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        if (! $countdown instanceof Countdown) {
            throw new RuntimeException('Taiwan invasion countdown must exist before seeding initiatives.');
        }

        return $countdown;
    }

    private function data(): object
    {
        return require __DIR__.'/data.php';
    }
};
