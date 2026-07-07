<?php

declare(strict_types=1);

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum ProjectionType: string
{
    case Optimistic = 'optimistic';
    case Neutral = 'neutral';
    case Pessimistic = 'pessimistic';
    case Other = 'other';
}
