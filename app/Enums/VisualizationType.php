<?php

declare(strict_types=1);

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum VisualizationType: string
{
    case Line = 'line';
    case Area = 'area';
    case Bar = 'bar';
    case Sparkline = 'sparkline';
    case Kpi = 'kpi';
    case Timeline = 'timeline';
    case RiskMatrix = 'risk_matrix';
    case Custom = 'custom';
}
