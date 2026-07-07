<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Enums;

enum AgentParameterSource: string
{
    case Literal = 'literal';
    case Variable = 'variable';
    case Disabled = 'disabled';
}
