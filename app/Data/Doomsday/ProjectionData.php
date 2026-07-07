<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class ProjectionData extends Data
{
    /**
     * @param array<int, VisualizationData> $visualizations
     */
    public function __construct(
        public readonly string $type,
        public readonly ?CarbonImmutable $target_date,
        public readonly string $title,
        public readonly string $summary,
        public readonly int $confidence_score,
        public readonly int $probability_score,
        public readonly string $trend,
        public readonly array $visualizations,
    ) {
    }
}
