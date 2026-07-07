<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class CountdownDetailData extends Data
{
    /**
     * @param array<int, string> $causes
     * @param array<int, string> $consequences
     * @param array<int, string> $recommended_actions
     * @param array<int, ProjectionData> $projections
     * @param array<int, VisualizationData> $visualizations
     * @param array<int, NewsData> $news
     */
    public function __construct(
        public readonly int $id,
        public readonly string $slug,
        public readonly string $title,
        public readonly string $summary,
        public readonly string $description,
        public readonly string $image_url,
        public readonly string $icon,
        public readonly string $severity,
        public readonly CountdownTimerData $timer,
        public readonly ?ProjectionData $main_projection,
        public readonly array $causes,
        public readonly array $consequences,
        public readonly array $recommended_actions,
        public readonly array $projections,
        public readonly array $visualizations,
        public readonly array $news,
    ) {
    }
}
