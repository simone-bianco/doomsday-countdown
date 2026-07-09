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
        $seedNews = $this->data()->news();

        $countdown->news()
            ->whereIn('source_url', array_map(
                static fn (array $news): string => (string) $news['source_url'],
                $seedNews,
            ))
            ->delete();

        foreach ($seedNews as $index => $news) {
            $countdown->news()->create(array_merge($news, [
                'content_type' => 'article',
                'image_path' => self::IMAGE_PATH,
                'sort_order' => $index + 1,
                'is_featured' => $index === 0,
            ]));
        }
    }

    public function down(): void
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        if ($countdown !== null) {
            $countdown->news()
                ->whereIn('source_url', array_map(
                    static fn (array $news): string => (string) $news['source_url'],
                    $this->data()->news(),
                ))
                ->delete();
        }
    }

    private function countdown(): Countdown
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        if (! $countdown instanceof Countdown) {
            throw new RuntimeException('Taiwan invasion countdown must exist before seeding news.');
        }

        return $countdown;
    }

    private function data(): object
    {
        return require __DIR__.'/data.php';
    }
};
