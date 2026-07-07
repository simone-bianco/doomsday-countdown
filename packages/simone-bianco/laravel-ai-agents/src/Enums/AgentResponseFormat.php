<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Enums;

enum AgentResponseFormat: string
{
    case JsonSchema = 'json_schema';
    case RawText = 'raw_text';
}
