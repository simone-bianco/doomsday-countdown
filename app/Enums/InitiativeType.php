<?php

declare(strict_types=1);

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum InitiativeType: string
{
    case Petition = 'petition';
    case Protest = 'protest';
    case Fundraiser = 'fundraiser';
    case Campaign = 'campaign';
    case Resource = 'resource';
    case Other = 'other';
}
