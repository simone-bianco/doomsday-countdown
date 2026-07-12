<?php

declare(strict_types=1);

use App\Models\Countdown;
use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    private const SLUG = 'ai-job-apocalypse';

    private const IMAGE_PATH = 'images/doomsday/ai_job_apocalypse.png';

    public function up(): void
    {
        $countdown = $this->countdown();
        $seedInitiatives = $this->data()->initiatives();

        $countdown->initiatives()
            ->whereIn('url', $this->ownedUrls())
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
                ->whereIn('url', $this->ownedUrls())
                ->delete();
        }
    }

    private function countdown(): Countdown
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        if (! $countdown instanceof Countdown) {
            throw new RuntimeException('AI Job Apocalypse countdown must exist before seeding initiatives.');
        }

        return $countdown;
    }

    /** @return array<int, string> */
    private function ownedUrls(): array
    {
        $data = $this->data();

        return array_values(array_unique(array_merge(
            array_map(
                static fn (array $initiative): string => (string) $initiative['url'],
                $data->initiatives(),
            ),
            $data->legacyUrls(),
        )));
    }

    private function data(): object
    {
        return require __DIR__.'/data.php';
    }
};
