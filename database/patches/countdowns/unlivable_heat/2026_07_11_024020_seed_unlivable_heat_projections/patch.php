<?php

declare(strict_types=1);

use App\Models\Countdown;
use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    private const SLUG = 'unlivable-heat';

    public function up(): void
    {
        $countdown = $this->countdown();

        foreach ($this->data()->projections() as $projection) {
            $countdown->projections()->updateOrCreate(
                ['type' => $this->scalarEnum($projection['type'])],
                $projection,
            );
        }
    }

    public function down(): void
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        if ($countdown === null) {
            return;
        }

        $countdown->projections()
            ->whereIn('type', array_map(
                fn (array $projection): string => $this->scalarEnum($projection['type']),
                $this->data()->projections(),
            ))
            ->delete();
    }

    private function countdown(): Countdown
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        if (! $countdown instanceof Countdown) {
            throw new RuntimeException('Unlivable Heat countdown must exist before seeding projections.');
        }

        return $countdown;
    }

    private function scalarEnum(mixed $value): string
    {
        return $value instanceof BackedEnum ? (string) $value->value : (string) $value;
    }

    private function data(): object
    {
        return require __DIR__.'/data.php';
    }
};
