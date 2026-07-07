<?php

declare(strict_types=1);

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum CountdownSeverity: string
{
    case Low = 'low';
    case Moderate = 'moderate';
    case Elevated = 'elevated';
    case High = 'high';
    case Severe = 'severe';
    case Critical = 'critical';
    case Existential = 'existential';
}
