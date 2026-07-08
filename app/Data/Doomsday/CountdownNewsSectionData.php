<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class CountdownNewsSectionData extends Data
{
    /** @param array<int, NewsData> $news */
    public function __construct(
        public readonly string $countdown_slug,
        public readonly array $news,
    ) {
    }
}
