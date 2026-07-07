<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Services;

use App\Agent\Progress\AgentProgressReporterInterface;
use App\Agent\Progress\ProgressEvent;
use App\Agent\Progress\ProgressEventType;
use Illuminate\Support\Facades\Log;
use LarAgent\Tool;
use SimoneBianco\LaravelAiAgents\Enums\AgentParameterSource;
use SimoneBianco\LaravelAiAgents\Enums\AgentToolKind;
use SimoneBianco\LaravelAiAgents\Models\AiAgentToolBinding;
use SimoneBianco\LaravelAiAgents\Registries\ScopeResolverRegistry;
use SimoneBianco\LaravelAiAgents\Registries\ToolRegistry;
use SimoneBianco\LaravelAiAgents\Support\AgentLogger;
use SimoneBianco\LaravelAiAgents\Support\AgentRunContext;
use SimoneBianco\LaravelAiAgents\Support\ProgressAwareTool;
use SimoneBianco\LaravelAiAgents\Support\WrappedTool;

class AgentToolBuilder
{
    public function __construct(
        private readonly ToolRegistry $toolRegistry,
        private readonly AgentLogger $logger,
        private readonly DynamicApiToolBuilder $dynamicApiBuilder,
        private readonly SubAgentToolBuilder $subAgentBuilder,
        private readonly ScopeResolverRegistry $scopeRegistry,
    ) {
    }

    public function build(AiAgentToolBinding $binding, AgentRunContext $context): Tool
    {
        $start = microtime(true);
        $tool = $binding->tool;
        $kind = $tool->kind;

        $result = match ($kind) {
            AgentToolKind::RegisteredClass => $this->buildRegisteredClass($binding, $context),
            AgentToolKind::DynamicApi => $this->dynamicApiBuilder->build($binding, $context),
            AgentToolKind::SubAgent => $this->subAgentBuilder->build($binding, $context),
        };

        $agentSlug = $binding->agent?->slug ?? '';
        $this->logger->toolBuild(
            $agentSlug,
            (string) $tool->key,
            $kind->value,
            (microtime(true) - $start) * 1000,
        );

        $result = $this->wrapWithProgress($result, $context);

        return $result;
    }

    private function buildRegisteredClass(AiAgentToolBinding $binding, AgentRunContext $context): Tool
    {
        $tool = $binding->tool;
        $factory = $this->toolRegistry->factory((string) $tool->key);

        $config = is_array($tool->default_parameters) ? $tool->default_parameters : [];

        // Enrich context with resolved scope aliases so factories like
        // SearchInAllowedProjectsFactory can read 'allowed_project_aliases' automatically.
        $enrichedContext = $this->enrichContextWithScopeAliases($binding, $context);
        $enrichedContext = $this->enrichContextWithCallingAgent($binding, $enrichedContext);

        $overrides = $binding->parameterOverrides()->get();

        // Extract factory:: prefixed overrides and merge into config before make().
        // These are factory-level settings (e.g. factory::include_images) that the
        // factory constructor reads from $config, not the tool's input schema.
        foreach ($overrides as $override) {
            $path = (string) $override->parameter_path;
            if (! str_starts_with($path, 'factory::')) {
                continue;
            }

            $configKey = substr($path, strlen('factory::'));
            $value     = match ($override->source) {
                AgentParameterSource::Literal => is_array($override->literal_value) && array_key_exists('value', $override->literal_value)
                    ? $override->literal_value['value']
                    : $override->literal_value,
                AgentParameterSource::Variable => $this->resolveVariableExpression(
                    (string) ($override->variable_expression ?? ''),
                    $context,
                ),
                default => null,
            };

            if ($value !== null) {
                $config[$configKey] = $value;
            }
        }

        $toolKey = (string) $tool->key;
        if (in_array($toolKey, ['search_in_project', 'search_in_allowed_projects'], true)) {
            Log::channel('messages')->info('agent.tool_runtime_config', [
                'slug' => (string) ($binding->agent?->slug ?? ''),
                'tool_key' => $toolKey,
                'allowed_project_aliases_count' => is_array($enrichedContext->get('allowed_project_aliases'))
                    ? count($enrichedContext->get('allowed_project_aliases'))
                    : 0,
                'allowed_project_aliases' => is_array($enrichedContext->get('allowed_project_aliases'))
                    ? array_values($enrichedContext->get('allowed_project_aliases'))
                    : [],
                'factory_config_keys' => array_values(array_unique(array_keys($config))),
                'factory_config' => $config,
            ]);
        }

        $base = $factory->make($config, $enrichedContext);

        if ($overrides->isEmpty()) {
            return $base;
        }

        $disabled = [];
        $pinned   = [];
        foreach ($overrides as $override) {
            $path = (string) $override->parameter_path;
            if (str_starts_with($path, 'factory::')) {
                continue;
            }

            switch ($override->source) {
                case AgentParameterSource::Disabled:
                    $disabled[] = $path;
                    break;
                case AgentParameterSource::Literal:
                    $literal      = $override->literal_value;
                    $pinned[$path] = is_array($literal) && array_key_exists('value', $literal)
                        ? $literal['value']
                        : $literal;
                    break;
                case AgentParameterSource::Variable:
                    $expr          = (string) ($override->variable_expression ?? '');
                    $pinned[$path] = $this->resolveVariableExpression($expr, $context);
                    break;
            }
        }

        if (empty($disabled) && empty($pinned)) {
            return $base;
        }

        return new WrappedTool($base, $disabled, $pinned);
    }

    /**
     * Resolve the agent's scope bindings into project aliases and inject them
     * into the context so registered-class tool factories can consume them.
     *
     * Sets: allowed_project_aliases (merged from project + project_group scopes)
     */
    private function enrichContextWithScopeAliases(AiAgentToolBinding $binding, AgentRunContext $context): AgentRunContext
    {
        // Skip if context already has aliases (caller set them explicitly)
        if ($context->get('allowed_project_aliases') !== null) {
            return $context;
        }

        $agent = $binding->agent;
        if ($agent === null || ! $agent->relationLoaded('scopeBindings')) {
            return $context;
        }

        $projectAliases = [];

        foreach ($agent->scopeBindings as $sb) {
            $scopeType = (string) $sb->scope_type;
            $scopeKey  = (string) $sb->scope_key;
            $metadata  = is_array($sb->metadata) ? $sb->metadata : [];

            if (! $this->scopeRegistry->has($scopeType)) {
                continue;
            }

            $snapshot = $this->scopeRegistry->get($scopeType)->resolve($scopeKey, $metadata);

            if (! $snapshot->exists) {
                continue;
            }

            if ($scopeType === 'project' && isset($snapshot->fields['alias'])) {
                $projectAliases[] = (string) $snapshot->fields['alias'];
            } elseif ($scopeType === 'project_group' && is_array($snapshot->fields['allowed_project_aliases'] ?? null)) {
                foreach ($snapshot->fields['allowed_project_aliases'] as $alias) {
                    $projectAliases[] = (string) $alias;
                }
            }
        }

        if (empty($projectAliases)) {
            return $context;
        }

        return $context->with(['allowed_project_aliases' => array_values(array_unique($projectAliases))]);
    }

    /**
     * Inject the calling-agent identifier into the context so tool factories
     * (e.g. SearchInProjectFactory → SearchTool → SearchAgent) can wire
     * per-caller features like SearchResult history.
     */
    private function enrichContextWithCallingAgent(AiAgentToolBinding $binding, AgentRunContext $context): AgentRunContext
    {
        if ($context->get('calling_agent_id') !== null) {
            return $context;
        }

        $agentId = $binding->agent?->id;
        if ($agentId === null) {
            return $context;
        }

        return $context->with(['calling_agent_id' => (string) $agentId]);
    }

    private function resolveVariableExpression(string $expression, AgentRunContext $context): mixed
    {
        // Very light resolution: supports "context.key" or "context.key.sub".
        $trimmed = trim($expression);
        if ($trimmed === '') {
            return null;
        }

        if (str_starts_with($trimmed, 'context.')) {
            $path = substr($trimmed, strlen('context.'));
            $segments = explode('.', $path);
            $value = $context->all();
            foreach ($segments as $seg) {
                if (is_array($value) && array_key_exists($seg, $value)) {
                    $value = $value[$seg];
                } else {
                    return null;
                }
            }
            return $value;
        }

        return $expression;
    }

    private function wrapWithProgress(Tool $tool, AgentRunContext $context): Tool
    {
        $reporter = $context->get('progress_reporter');
        $logger = $this->logger;
        $chatKey = (string) ($context->get('chat_key') ?? '');
        $sanitizeInput = static function (array $input): array {
            $sensitivePattern = '/(token|secret|password|authorization|api[_-]?key|cookie|session)/i';
            $out = [];

            foreach ($input as $key => $value) {
                $keyString = is_string($key) ? $key : (string) $key;

                if (preg_match($sensitivePattern, $keyString) === 1) {
                    $out[$keyString] = '[redacted]';
                    continue;
                }

                if (is_string($value)) {
                    $out[$keyString] = mb_strlen($value) > 500
                        ? mb_substr($value, 0, 500) . '…'
                        : $value;
                    continue;
                }

                if (is_array($value)) {
                    $out[$keyString] = '[array:' . count($value) . ']';
                    continue;
                }

                $out[$keyString] = $value;
            }

            return $out;
        };

        $beforeExecute = static function (string $toolName, array $input, string $executionId) use ($reporter, $logger, $sanitizeInput, $chatKey): void {
            if ($reporter instanceof AgentProgressReporterInterface) {
                // Use Searching event when tool name hints at lookup, else ToolCall.
                $isSearch = str_contains(strtolower($toolName), 'search')
                    || str_contains(strtolower($toolName), 'find')
                    || str_contains(strtolower($toolName), 'lookup');

                $type = $isSearch ? ProgressEventType::Searching : ProgressEventType::ToolCall;

                $reporter->report(new ProgressEvent($type, metadata: ['name' => $toolName]));
            }

            $logger->toolExecuteStart($toolName, [
                'tool_call_id' => $executionId,
                'chat_key' => $chatKey,
                'input_keys' => array_values(array_unique(array_keys($input))),
                'input' => $sanitizeInput($input),
            ]);
        };

        $afterExecute = static function (string $toolName, mixed $result, float $ms, bool $success, string $executionId) use ($reporter, $logger, $chatKey): void {
            if ($reporter instanceof AgentProgressReporterInterface) {
                $reporter->report(new ProgressEvent(ProgressEventType::ToolResult, metadata: ['name' => $toolName]));
            }

            $logger->toolExecuteEnd($toolName, $ms, $success);
            Log::channel('messages')->info('agent.tool_execute_trace', [
                'tool_key' => $toolName,
                'tool_call_id' => $executionId,
                'chat_key' => $chatKey,
                'ms' => round($ms, 2),
                'success' => $success,
            ]);
        };

        return new ProgressAwareTool($tool, $beforeExecute, $afterExecute);
    }
}
