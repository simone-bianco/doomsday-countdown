<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class CountdownIndexData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $slug,
        public readonly string $title,
        public readonly string $summary,
        public readonly string $image_url,
        public readonly string $status,
        public readonly string $severity,
        public readonly int $sort_order,
        public readonly CountdownTimerData $timer,
        public readonly ?ProjectionData $main_projection,
        public readonly string $url,
        public readonly bool $is_selected,
    ) {}
}
