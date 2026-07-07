<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use SimoneBianco\LaravelAiAgents\Agents\DatabaseAgent;
use SimoneBianco\LaravelAiAgents\Exceptions\AgentNotFoundException;
use SimoneBianco\LaravelAiAgents\Exceptions\AgentRecursionException;
use SimoneBianco\LaravelAiAgents\Models\AiAgent;
use SimoneBianco\LaravelAiAgents\Registries\AgentResponseHookRegistry;
use SimoneBianco\LaravelAiAgents\Support\AgentLogger;
use SimoneBianco\LaravelAiAgents\Support\AgentRunContext;
use SimoneBianco\LaravelAiAgents\Support\AgentRunStack;

class AgentInstantiationService
{
    private string $slug = '';

    private mixed $chatKey = null;

    /** @var array<string, mixed> */
    private array $contextData = [];

    public function __construct(
        private readonly AgentRunStack $runStack,
        private readonly AgentLogger $logger,
        private readonly AgentPromptRenderer $renderer,
        private readonly AgentToolBuilder $toolBuilder,
        private readonly AgentResponseHookRegistry $responseHookRegistry,
    ) {
    }

    public function for(string $slug): static
    {
        $new = clone $this;
        $new->slug = $slug;

        return $new;
    }

    public function withChatKey(mixed $key): static
    {
        $new = clone $this;
        $new->chatKey = $key;

        return $new;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function withContext(array $data): static
    {
        $new = clone $this;
        $new->contextData = $data;

        return $new;
    }

    public function build(): DatabaseAgent
    {
        try {
            $agent = AiAgent::with([
                'toolBindings.tool.subAgent',
                'toolBindings.parameterOverrides',
                'scopeBindings',
                'variableBindings',
            ])
                ->where('slug', $this->slug)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new AgentNotFoundException("Agent with slug '{$this->slug}' not found");
        }

        $maxDepth = (int) config('ai-agents.max_recursion_depth', 4);

        if ($this->runStack->has($this->slug)) {
            $this->logger->recursionViolation($this->slug, $this->runStack->current());
            throw new AgentRecursionException(
                "Recursive agent call detected: '{$this->slug}' is already in the call stack"
            );
        }

        if ($this->runStack->depth() >= $maxDepth) {
            throw new AgentRecursionException("Max recursion depth {$maxDepth} reached");
        }

        $this->runStack->push($this->slug);

        AiAgent::where('id', $agent->id)->increment('active_runs_count');

        $context = new AgentRunContext(array_merge([
            'calling_agent_id' => (string) $agent->id,
            'history_enabled'  => true,
        ], $this->contextData, [
            'chat_key' => $this->chatKey,
        ]));

        $dbAgent = new DatabaseAgent(
            $this->chatKey,
            $agent,
            $context,
            $this->renderer,
            $this->toolBuilder,
            $this->logger,
            $this->responseHookRegistry,
        );

        $slug = $this->slug;
        $agentId = (string) $agent->id;
        $stack = $this->runStack;

        $dbAgent->setOnComplete(function () use ($stack, $slug, $agentId): void {
            $stack->pop($slug);
            AiAgent::where('id', $agentId)->decrement('active_runs_count');
        });

        $this->logger->agentLoad($slug, $agentId, [
            'type' => $agent->type,
            'scope' => $agent->scope,
            'tools' => $agent->toolBindings->count(),
            'scope_bindings' => $agent->scopeBindings->count(),
        ]);

        return $dbAgent;
    }
}
