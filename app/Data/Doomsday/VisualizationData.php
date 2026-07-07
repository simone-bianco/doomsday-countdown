<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class VisualizationData extends Data
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public readonly string $key,
        public readonly string $type,
        public readonly string $title,
        public readonly ?string $description,
        public readonly array $payload,
        public readonly int $schema_version,
        public readonly int $sort_order,
    ) {
    }
}
