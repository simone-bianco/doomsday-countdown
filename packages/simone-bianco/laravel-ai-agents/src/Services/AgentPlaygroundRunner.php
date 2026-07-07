<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Services;

use App\Agent\Progress\AgentProgressReporterInterface;
use App\Agent\Progress\NullProgressReporter;
use App\Agent\Progress\ProgressEvent;
use App\Agent\Progress\ProgressEventType;
use Generator;
use LarAgent\Messages\StreamedAssistantMessage;
use LarAgent\Messages\ToolCallMessage;
use Illuminate\Support\Str;
use SimoneBianco\LaravelAiAgents\Agents\DatabaseAgent;
use SimoneBianco\LaravelAiAgents\Models\AiAgent;
use SimoneBianco\LaravelAiAgents\Models\AiAgentScopeBinding;
use SimoneBianco\LaravelAiAgents\Models\AiAgentToolBinding;
use SimoneBianco\LaravelAiAgents\Models\AiAgentToolParameterOverride;
use SimoneBianco\LaravelAiAgents\Models\AiAgentVariableBinding;
use SimoneBianco\LaravelAiAgents\Registries\AgentResponseHookRegistry;
use SimoneBianco\LaravelAiAgents\Support\AgentLogger;
use SimoneBianco\LaravelAiAgents\Support\AgentRunContext;

class AgentPlaygroundRunner
{
    public function __construct(
        private readonly AgentInstantiationService $instantiation,
        private readonly AgentLogger $logger,
        private readonly AgentPromptRenderer $renderer,
        private readonly AgentToolBuilder $toolBuilder,
        private readonly AgentResponseHookRegistry $responseHookRegistry,
    ) {
    }

    /**
     * Run the agent synchronously, emitting progress events via the reporter.
     *
     * @param array<string, mixed> $context
     * @param array<string, mixed>|null $agentDraft
     * @return array<string, mixed>
     */
    public function run(
        string $agentSlug,
        string $chatKey,
        string $message,
        array $context = [],
        ?array $agentDraft = null,
        ?AgentProgressReporterInterface $reporter = null,
    ): array {
        $reporter ??= new NullProgressReporter();
        $start = microtime(true);

        $this->logger->playgroundEvent($agentSlug, $chatKey, 'run_start', [
            'message_preview' => mb_substr($message, 0, 100),
            'has_draft'       => $agentDraft !== null,
        ]);

        $context['progress_reporter'] = $reporter;

        $reporter->report(new ProgressEvent(ProgressEventType::Thinking));

        $agent = $agentDraft !== null
            ? $this->buildFromDraft($agentDraft, $chatKey, $context)
            : $this->instantiation->for($agentSlug)->withChatKey($chatKey)->withContext($context)->build();

        $response = $agent->respond($message);
        $elapsed = (microtime(true) - $start) * 1000;

        $responseType = get_debug_type($response);
        $responsePreview = is_string($response)
            ? mb_substr($response, 0, 200)
            : (is_array($response) ? json_encode(array_slice($response, 0, 3, true)) : '(object:' . get_class($response) . ')');

        $this->logger->playgroundEvent($agentSlug, $chatKey, 'run_end', [
            'elapsed_ms'       => round($elapsed, 2),
            'response_type'    => $responseType,
            'response_empty'   => is_string($response) ? $response === '' : null,
            'response_length'  => is_string($response) ? strlen($response) : null,
            'response_preview' => $responsePreview,
        ]);

        return [
            'response'   => $response,
            'usage'      => [],
            'elapsed_ms' => round($elapsed, 2),
        ];
    }

    /**
     * Stream the agent response token-by-token, emitting progress and chunk events.
     *
     * Yields items of the form:
     *   ['event' => 'progress', 'data' => [...]]
     *   ['event' => 'chunk',    'data' => ['delta' => string, 'done' => bool]]
     *   ['event' => 'result',   'data' => ['content' => string, 'elapsed_ms' => float]]
     *
     * @param array<string, mixed> $context
     * @param array<string, mixed>|null $agentDraft
     */
    public function stream(
        string $agentSlug,
        string $chatKey,
        string $message,
        array $context = [],
        ?array $agentDraft = null,
        ?AgentProgressReporterInterface $reporter = null,
    ): Generator {
        $reporter ??= new NullProgressReporter();
        $start = microtime(true);

        $this->logger->playgroundEvent($agentSlug, $chatKey, 'stream_start', [
            'message_preview' => mb_substr($message, 0, 100),
            'has_draft'       => $agentDraft !== null,
        ]);

        $context['progress_reporter'] = $reporter;

        // Emit initial "Thinking..." progress before agent build to avoid SSE idle gap.
        $thinkingEvent = new ProgressEvent(ProgressEventType::Thinking);
        $reporter->report($thinkingEvent);

        $buildStart = microtime(true);

        $agent = $agentDraft !== null
            ? $this->buildFromDraft($agentDraft, $chatKey, $context)
            : $this->instantiation->for($agentSlug)->withChatKey($chatKey)->withContext($context)->build();

        $this->logger->playgroundEvent($agentSlug, $chatKey, 'stream_agent_built', [
            'elapsed_ms' => round((microtime(true) - $buildStart) * 1000, 2),
        ]);

        $chunkCount = 0;
        $totalChars = 0;
        $accumulated = '';
        $lastChunk   = null;
        $firstChunkLogged = false;

        foreach ($agent->respondStreamed($message) as $chunk) {
            $chunkType = get_debug_type($chunk);
            $chunkCount++;
            $lastChunk = $chunk;

            if (! $firstChunkLogged) {
                $this->logger->playgroundEvent($agentSlug, $chatKey, 'stream_first_chunk', [
                    'elapsed_ms' => round((microtime(true) - $start) * 1000, 2),
                    'chunk_type' => $chunkType,
                ]);
                $firstChunkLogged = true;
            }

//            $this->logger->playgroundEvent($agentSlug, $chatKey, 'stream_chunk_received', [
//                'chunk_index' => $chunkCount,
//                'chunk_type' => $chunkType,
//            ]);

            if ($chunk instanceof StreamedAssistantMessage) {
                $delta = $chunk->getLastChunk() ?? '';

                if ($delta === '') {
                    continue;
                }

                $totalChars += strlen($delta);
                $accumulated .= $delta;

                yield ['event' => 'chunk', 'data' => ['delta' => $delta, 'done' => $chunk->isComplete()]];
                continue;
            }

            if ($chunk instanceof ToolCallMessage) {
                $this->logger->playgroundEvent($agentSlug, $chatKey, 'stream_tool_call_chunk', [
                    'chunk_index' => $chunkCount,
                ]);
                continue;
            }

            $this->logger->playgroundEvent($agentSlug, $chatKey, 'stream_non_delta_chunk_skipped', [
                'chunk_index' => $chunkCount,
                'chunk_type' => $chunkType,
            ]);
        }

        $elapsed = (microtime(true) - $start) * 1000;

        // Use getContent() as authoritative final text (clean content without tool-call JSON)
        $content = '';
        if ($lastChunk !== null && method_exists($lastChunk, 'getContent')) {
            $content = (string) $lastChunk->getContent();
        }
        if ($content === '') {
            $content = $accumulated;
        }

        yield ['event' => 'result', 'data' => ['content' => $content, 'elapsed_ms' => round($elapsed, 2)]];

        $this->logger->playgroundEvent($agentSlug, $chatKey, 'stream_end', [
            'chunk_count' => $chunkCount,
            'total_chars' => $totalChars,
            'elapsed_ms'  => round($elapsed, 2),
        ]);

        if ($chunkCount === 0) {
            $this->logger->playgroundEvent($agentSlug, $chatKey, 'stream_no_chunks_warning', [
                'elapsed_ms' => round($elapsed, 2),
            ]);
        }
    }

    /**
     * @param array<string, mixed> $draft
     * @param array<string, mixed> $context
     */
    private function buildFromDraft(array $draft, string $chatKey, array $context): DatabaseAgent
    {
        $agentAttrs = (array) ($draft['agent'] ?? []);
        $agent = AiAgent::make($agentAttrs);
        $agent->id = $agentAttrs['id'] ?? Str::uuid()->toString();
        $agent->exists = false;

        $toolBindings = collect();
        foreach ((array) ($draft['tool_bindings'] ?? []) as $tb) {
            $tbModel = AiAgentToolBinding::make($tb);
            $tbModel->id = $tb['id'] ?? Str::uuid()->toString();
            $tbModel->exists = false;

            $overrides = collect();
            foreach ((array) ($tb['parameter_overrides'] ?? []) as $po) {
                $poModel = AiAgentToolParameterOverride::make($po);
                $poModel->exists = false;
                $overrides->push($poModel);
            }
            $tbModel->setRelation('parameterOverrides', $overrides);
            $toolBindings->push($tbModel);
        }
        $agent->setRelation('toolBindings', $toolBindings);

        $scopeBindings = collect();
        foreach ((array) ($draft['scope_bindings'] ?? []) as $sb) {
            $sbModel = AiAgentScopeBinding::make($sb);
            $sbModel->exists = false;
            $scopeBindings->push($sbModel);
        }
        $agent->setRelation('scopeBindings', $scopeBindings);

        $variableBindings = collect();
        foreach ((array) ($draft['variable_bindings'] ?? []) as $vb) {
            $vbModel = AiAgentVariableBinding::make($vb);
            $vbModel->exists = false;
            $variableBindings->push($vbModel);
        }
        $agent->setRelation('variableBindings', $variableBindings);

        $runContext = new AgentRunContext(array_merge($context, ['chat_key' => $chatKey]));

        return new DatabaseAgent(
            $chatKey,
            $agent,
            $runContext,
            $this->renderer,
            $this->toolBuilder,
            $this->logger,
            $this->responseHookRegistry,
        );
    }

}
