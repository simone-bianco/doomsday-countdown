<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class CountdownForecastsData extends Data
{
    /** @param array<int, ProjectionData> $projections */
    public function __construct(
        public readonly string $countdown_slug,
        public readonly array $projections,
        public readonly ?VisualizationData $projection_chart,
    ) {
    }
}
