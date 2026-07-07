<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Registries;

use SimoneBianco\LaravelAiAgents\Contracts\AgentResponseHook;
use SimoneBianco\LaravelAiAgents\Models\AiAgent;
use SimoneBianco\LaravelAiAgents\Support\AgentRunContext;

class AgentResponseHookRegistry
{
    /** @var array<string, class-string<AgentResponseHook>> */
    private array $hooks = [];

    /**
     * @param class-string<AgentResponseHook> $hookClass
     */
    public function register(string $agentType, string $hookClass): void
    {
        $this->hooks[$agentType] = $hookClass;
    }

    public function has(string $agentType): bool
    {
        return isset($this->hooks[$agentType]);
    }

    public function handle(AiAgent $agent, mixed $rawResponse, AgentRunContext $context): mixed
    {
        $type = $agent->type ?? '';
        if (! $this->has($type)) {
            return $rawResponse;
        }

        /** @var AgentResponseHook $hook */
        $hook = app($this->hooks[$type]);

        return $hook->handle($agent, $rawResponse, $context);
    }
}
