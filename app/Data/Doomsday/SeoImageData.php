<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class SeoImageData extends Data
{
    public function __construct(
        public readonly string $url,
        public readonly ?int $width,
        public readonly ?int $height,
        public readonly string $alt,
    ) {}
}
