<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class SeoAlternateData extends Data
{
    public function __construct(
        public readonly string $hreflang,
        public readonly string $url,
    ) {}
}
