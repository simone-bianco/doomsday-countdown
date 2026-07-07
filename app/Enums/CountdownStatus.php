<?php

declare(strict_types=1);

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum CountdownStatus: string
{
    case Draft = 'draft';
    case Monitoring = 'monitoring';
    case Active = 'active';
    case Stabilized = 'stabilized';
    case Averted = 'averted';
    case Occurred = 'occurred';
    case Archived = 'archived';
}
