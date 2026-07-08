<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class CountdownInitiativesSectionData extends Data
{
    /** @param array<int, InitiativeData> $initiatives */
    public function __construct(
        public readonly string $countdown_slug,
        public readonly array $initiatives,
    ) {
    }
}
