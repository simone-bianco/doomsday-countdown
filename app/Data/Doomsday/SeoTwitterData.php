<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class SeoTwitterData extends Data
{
    public function __construct(
        public readonly string $card,
        public readonly string $title,
        public readonly string $description,
        public readonly string $image_url,
        public readonly string $image_alt,
    ) {}
}
