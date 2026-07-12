<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class SeoOpenGraphData extends Data
{
    /** @param array<int, string> $alternate_locales */
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $url,
        public readonly string $type,
        public readonly string $site_name,
        public readonly string $locale,
        public readonly array $alternate_locales,
        public readonly SeoImageData $image,
    ) {}
}
