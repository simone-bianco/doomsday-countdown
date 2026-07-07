<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Services;

use LarAgent\Tool;
use SimoneBianco\LaravelAiAgents\Enums\AgentSubAgentHistoryMode;
use SimoneBianco\LaravelAiAgents\Exceptions\AgentToolResolutionException;
use SimoneBianco\LaravelAiAgents\Models\AiAgentToolBinding;
use SimoneBianco\LaravelAiAgents\Support\AgentLogger;
use SimoneBianco\LaravelAiAgents\Support\AgentRunContext;
use SimoneBianco\LaravelAiAgents\Support\AgentRunStack;

class SubAgentToolBuilder
{
    public function __construct(
        private readonly AgentLogger $logger,
        private readonly AgentRunStack $runStack,
    ) {
    }

    public function build(AiAgentToolBinding $binding, AgentRunContext $context): Tool
    {
        $subAgent = $binding->tool?->subAgent;
        if ($subAgent === null) {
            throw new AgentToolResolutionException(
                "SubAgent tool binding '{$binding->id}' has no associated sub agent"
            );
        }

        $subSlug = (string) $subAgent->slug;
        $toolName = (string) ($binding->alias ?? ('call_' . str_replace('-', '_', $subSlug)));
        $description = (string) ($subAgent->description ?? "Delegate to sub agent {$subSlug}");
        $historyMode = $binding->sub_agent_history_mode ?? AgentSubAgentHistoryMode::Stateless;
        $parentSlug = (string) ($binding->agent?->slug ?? '');
        $parentChatKey = (string) ($context->get('chat_key', '')); // may be empty

        $logger = $this->logger;
        $stack = $this->runStack;

        $instance = Tool::create($toolName, $description);
        $instance->addProperty('message', 'string', 'Message to send to the sub agent');
        $instance->setRequired('message');

        $instance->setCallback(function (string $message) use ($subSlug, $historyMode, $parentChatKey, $parentSlug, $logger, $stack, $context): mixed {
            // Lazy import avoids circular boot issues.
            $service = app(AgentInstantiationService::class);

            $chatKey = $historyMode === AgentSubAgentHistoryMode::Stateless
                ? $parentChatKey . ':sub:' . $subSlug . ':' . uniqid('', true)
                : $parentChatKey . ':sub:' . $subSlug;

            $logger->subAgentDispatch($parentSlug, $subSlug, $stack->depth());

            $subAgentInstance = $service
                ->for($subSlug)
                ->withChatKey($chatKey)
                ->withContext($context->all())
                ->build();

            return $subAgentInstance->respond($message);
        });

        return $instance;
    }
}
