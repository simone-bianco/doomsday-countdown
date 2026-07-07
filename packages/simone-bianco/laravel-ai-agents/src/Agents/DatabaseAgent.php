<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Agents;

use App\Agent\Progress\AgentProgressReporterInterface;
use App\Agent\Progress\NullProgressReporter;
use Closure;
use Generator;
use Illuminate\Support\Collection;
use LarAgent\Core\Contracts\DataModel;
use LarAgent\Core\Contracts\Message as MessageInterface;
use LarAgent\Messages\StreamedAssistantMessage;
use SimoneBianco\LaravelAiAgents\Enums\AgentResponseFormat;
use SimoneBianco\LaravelAiAgents\History\PageChatStorageDriver;
use SimoneBianco\LaravelAiAgents\Models\AiAgent;
use SimoneBianco\LaravelAiAgents\Registries\AgentResponseHookRegistry;
use SimoneBianco\LaravelAiAgents\Services\AgentPromptRenderer;
use SimoneBianco\LaravelAiAgents\Services\AgentToolBuilder;
use SimoneBianco\LaravelAiAgents\Support\AgentLogger;
use SimoneBianco\LaravelAiAgents\Support\AgentRunContext;

class DatabaseAgent extends RotableAgent
{
    protected $history = PageChatStorageDriver::class;

    private ?Closure $onComplete = null;

    public function __construct(
        mixed $chatKey,
        private readonly AiAgent $definition,
        private readonly AgentRunContext $runContext,
        private readonly AgentPromptRenderer $renderer,
        private readonly AgentToolBuilder $toolBuilder,
        private readonly AgentLogger $logger,
        private readonly AgentResponseHookRegistry $responseHookRegistry,
    ) {
        $this->model = $definition->model;

        if ($definition->max_completion_tokens !== null) {
            $this->maxCompletionTokens = $definition->max_completion_tokens;
        }

        if ($definition->developer_role_for_instructions) {
            $this->developerRoleForInstructions = true;
        }

        if (
            $definition->response_format === AgentResponseFormat::JsonSchema
            && is_array($definition->response_schema)
        ) {
            $this->responseSchema = $definition->response_schema;
        }

        parent::__construct($chatKey);

        if ($definition->relationLoaded('toolBindings')) {
            /** @var Collection<int, mixed> $bindings */
            $bindings = Collection::make($definition->getRelation('toolBindings'))
                ->sortBy('position')
                ->values();
        } else {
            $bindings = $definition->toolBindings()
                ->with(['tool.subAgent', 'parameterOverrides'])
                ->orderBy('position')
                ->get();
        }

        foreach ($bindings as $binding) {
            if (! $binding->relationLoaded('tool')) {
                $binding->loadMissing('tool.subAgent');
            }

            if (! $binding->relationLoaded('parameterOverrides')) {
                $binding->loadMissing('parameterOverrides');
            }

            $binding->setRelation('agent', $definition);
            $this->withTool($this->toolBuilder->build($binding, $this->runContext));
        }

        // Only send parallel_tool_calls when tools are present AND the toggle is on.
        // OpenAI rejects the parameter when no tools are specified, and LarAgent
        // includes it in the payload whenever the property is !== null (even false).
        if ($bindings->isNotEmpty() && $definition->parallel_tool_calls) {
            $this->parallelToolCalls = true;
        }
    }

    public function setOnComplete(Closure $callback): void
    {
        $this->onComplete = $callback;
    }

    public function getProgressReporter(): AgentProgressReporterInterface
    {
        $reporter = $this->runContext->get('progress_reporter');

        return $reporter instanceof AgentProgressReporterInterface
            ? $reporter
            : new NullProgressReporter();
    }

    public function instructions(): string
    {
        return $this->renderer->render($this->definition, $this->runContext);
    }

    /**
     * @return Generator<int, StreamedAssistantMessage>
     */
    public function respondStreamed(?string $message = null, ?callable $callback = null): \Generator
    {
        $start = microtime(true);
        $chatKey = (string) ($this->chatHistory()?->getIdentifier() ?? '');
        $slug = (string) $this->definition->slug;

        $this->logger->agentRespond($slug, $chatKey, 'stream_start');

        try {
            yield from parent::respondStreamed($message, $callback);
        } finally {
            $ms = (microtime(true) - $start) * 1000;
            $this->logger->agentRespond($slug, $chatKey, 'stream_end', ['ms' => round($ms, 2)]);

            if ($this->onComplete !== null) {
                ($this->onComplete)();
            }
        }
    }

    public function respond(?string $message = null): string|array|DataModel|MessageInterface
    {
        $start = microtime(true);
        $chatKey = (string) ($this->chatHistory()?->getIdentifier() ?? '');
        $slug = (string) $this->definition->slug;

        $this->logger->agentRespond($slug, $chatKey, 'start');

        try {
            $result = parent::respond($message);
            $this->logger->agentRespondResult($slug, $chatKey, 'after_parent_respond', $result);

            $result = $this->responseHookRegistry->handle($this->definition, $result, $this->runContext);
            $this->logger->agentRespondResult($slug, $chatKey, 'after_hooks', $result);

            if ($this->definition->response_format === AgentResponseFormat::RawText) {
                if (is_array($result)) {
                    $result = $result['response'] ?? json_encode($result);
                }
                $result = (string) $result;
                $this->logger->agentRespondResult($slug, $chatKey, 'after_raw_text_cast', $result);
            }

            return $result;
        } finally {
            $ms = (microtime(true) - $start) * 1000;
            $this->logger->agentRespond($slug, $chatKey, 'end', ['ms' => round($ms, 2)]);

            if ($this->onComplete !== null) {
                ($this->onComplete)();
            }
        }
    }
}
