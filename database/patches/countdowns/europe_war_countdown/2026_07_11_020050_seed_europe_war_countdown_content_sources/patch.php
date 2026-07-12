<?php

declare(strict_types=1);

use App\Models\ContentSource;
use App\Models\Countdown;
use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    private const SLUG = 'europe-war-countdown';

    public function up(): void
    {
        $countdown = $this->countdown();
        $pivot = [
            'keywords' => json_encode($this->data()->pivotKeywords(), JSON_THROW_ON_ERROR),
            'excluded_keywords' => json_encode([], JSON_THROW_ON_ERROR),
            'is_active' => true,
        ];

        foreach ($this->data()->contentSources() as $sourceData) {
            $source = ContentSource::query()->updateOrCreate([
                'type' => $sourceData['type'],
                'provider' => $sourceData['provider'],
                'external_id' => $sourceData['external_id'],
            ], $sourceData);

            $pivotForSource = array_merge($pivot, ['weight' => $sourceData['weight'] ?? 100]);
            $countdown->contentSources()->syncWithoutDetaching([$source->getKey() => $pivotForSource]);
            $countdown->contentSources()->updateExistingPivot($source->getKey(), $pivotForSource);
        }
    }

    public function down(): void
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        $sources = ContentSource::query()->whereIn('external_id', $this->externalIds())->get();

        if ($countdown !== null) {
            $countdown->contentSources()->detach($sources->modelKeys());
        }

        $sources->each(function (ContentSource $source): void {
            if (! $source->countdowns()->exists()) {
                $source->delete();
            }
        });
    }

    private function countdown(): Countdown
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        if (! $countdown instanceof Countdown) {
            throw new RuntimeException('Europe war countdown must exist before seeding content sources.');
        }

        return $countdown;
    }

    /** @return list<string> */
    private function externalIds(): array
    {
        return array_values(array_filter(array_map(
            static fn (array $source): ?string => $source['external_id'] ?? null,
            $this->data()->contentSources(),
        )));
    }

    private function data(): object
    {
        return require __DIR__.'/data.php';
    }
};
