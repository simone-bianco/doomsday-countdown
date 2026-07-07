<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Enums;

enum AgentStreamingMode: string
{
    case Sync = 'sync';
    case Stream = 'stream';
}
