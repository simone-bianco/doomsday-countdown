<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Enums;

enum AgentSubAgentHistoryMode: string
{
    case Stateless = 'stateless';
    case Persistent = 'persistent';
}
