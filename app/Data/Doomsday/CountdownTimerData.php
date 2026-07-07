<?php

declare(strict_types=1);

namespace App\Data\Doomsday;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class CountdownTimerData extends Data
{
    public function __construct(
        public readonly ?CarbonImmutable $target_date,
        public readonly string $estimated_label,
        public readonly bool $is_elapsed,
    ) {
    }
}
