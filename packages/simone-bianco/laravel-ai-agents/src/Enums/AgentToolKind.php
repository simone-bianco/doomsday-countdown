<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Enums;

enum AgentToolKind: string
{
    case RegisteredClass = 'registered_class';
    case DynamicApi = 'dynamic_api';
    case SubAgent = 'sub_agent';
}
