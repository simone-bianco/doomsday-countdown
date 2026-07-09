<?php

declare(strict_types=1);
use App\Enums\ProjectionType;
use App\Models\Countdown;
use App\Models\Projection;
use SimoneBianco\Patches\Patch;

return new class extends Patch
{
    public bool $transactional = true;

    private const SLUG = 'taiwan-invasion';

    private const IMAGE_PATH = 'images/doomsday/taiwan_invasion.png';

    public function up(): void
    {
        $countdown = $this->countdown();
        $neutralProjection = $this->neutralProjection($countdown);
        $projectionCurve = $this->data()->projectionCurveVisualization();
        $neutralProjection->visualizations()->where('key', $projectionCurve['key'])->delete();
        $neutralProjection->visualizations()->create($projectionCurve);
        foreach ($this->data()->visualizations() as $visualization) {
            $countdown->visualizations()->where('key', $visualization['key'])->delete();
            $countdown->visualizations()->create($visualization);
        }
    }

    public function down(): void
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        if ($countdown === null) {
            return;
        }
        $countdown->visualizations()
            ->whereIn('key', array_map(
                static fn (array $visualization): string => (string) $visualization['key'],
                $this->data()->visualizations(),
            ))
            ->delete();
        $neutralProjection = $countdown->projections()->where('type', ProjectionType::Neutral->value)->first();
        if ($neutralProjection instanceof Projection) {
            $neutralProjection->visualizations()
                ->where('key', $this->data()->projectionCurveVisualization()['key'])
                ->delete();
        }
    }

    private function countdown(): Countdown
    {
        $countdown = Countdown::query()->where('slug', self::SLUG)->first();
        if (! $countdown instanceof Countdown) {
            throw new RuntimeException('Taiwan invasion countdown must exist before seeding visualizations.');
        }

        return $countdown;
    }

    private function neutralProjection(Countdown $countdown): Projection
    {
        $projection = $countdown->projections()->where('type', ProjectionType::Neutral->value)->first();
        if (! $projection instanceof Projection) {
            throw new RuntimeException('Neutral projection must exist before seeding projection visualizations.');
        }

        return $projection;
    }

    private function data(): object
    {
        return require __DIR__.'/data.php';
    }
};
