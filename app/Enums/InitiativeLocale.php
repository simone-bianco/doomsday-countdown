<?php

declare(strict_types=1);

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum InitiativeLocale: string
{
    case All = 'all';
    case En = 'en';
    case It = 'it';
    case Fr = 'fr';
    case De = 'de';
    case Es = 'es';
    case Nl = 'nl';
    case Sv = 'sv';
    case Pl = 'pl';
}
