<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Contracts;

use SimoneBianco\LaravelAiAgents\Models\AiAgent;
use SimoneBianco\LaravelAiAgents\Support\AgentRunContext;

interface AgentResponseHook
{
    public function handle(AiAgent $agent, mixed $rawResponse, AgentRunContext $context): mixed;
}
