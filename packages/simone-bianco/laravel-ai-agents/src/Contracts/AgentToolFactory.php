<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Contracts;

use LarAgent\Tool;
use SimoneBianco\LaravelAiAgents\Support\AgentRunContext;

interface AgentToolFactory
{
    public function make(array $config, AgentRunContext $context): Tool;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function editableParameters(): array;
}
