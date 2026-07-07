<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Support;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Throwable;

final class AgentLogger
{
    private function channel(): LoggerInterface
    {
        $channel = (string) config('ai-agents.log_channel', 'agents');

        return Log::channel($channel);
    }

    public function agentLoad(string $slug, string $id, array $extra = []): void
    {
        $this->channel()->info('agent.load', array_merge([
            'slug' => $slug,
            'id' => $id,
        ], $extra));
    }

    public function promptRender(string $slug, int $variablesResolved, int $variablesMissing, int $chars, float $ms): void
    {
        $this->channel()->info('agent.prompt_render', [
            'slug' => $slug,
            'variables_resolved' => $variablesResolved,
            'variables_missing' => $variablesMissing,
            'chars' => $chars,
            'ms' => round($ms, 2),
        ]);
    }

    public function variableMissing(string $slug, string $varKey, string $scopeType, string $scopeKey): void
    {
        $this->channel()->warning('agent.variable_missing', [
            'slug' => $slug,
            'var_key' => $varKey,
            'scope_type' => $scopeType,
            'scope_key' => $scopeKey,
        ]);
    }

    public function toolBuild(string $slug, string $toolKey, string $kind, float $ms): void
    {
        $this->channel()->info('agent.tool_build', [
            'slug' => $slug,
            'tool_key' => $toolKey,
            'kind' => $kind,
            'ms' => round($ms, 2),
        ]);
    }

    public function toolExecuteStart(string $toolKey, array $params = []): void
    {
        $this->channel()->info('agent.tool_execute_start', [
            'tool_key' => $toolKey,
            'params' => $params,
        ]);
    }

    public function toolExecuteEnd(string $toolKey, float $ms, bool $success = true): void
    {
        $this->channel()->info('agent.tool_execute_end', [
            'tool_key' => $toolKey,
            'ms' => round($ms, 2),
            'success' => $success,
        ]);
    }

    public function subAgentDispatch(string $parentSlug, string $childSlug, int $depth): void
    {
        $this->channel()->info('agent.sub_agent_dispatch', [
            'parent_slug' => $parentSlug,
            'child_slug' => $childSlug,
            'depth' => $depth,
        ]);
    }

    public function recursionViolation(string $slug, array $stack): void
    {
        $this->channel()->error('agent.recursion_violation', [
            'slug' => $slug,
            'stack' => $stack,
        ]);
    }

    public function agentRespond(string $slug, string $chatKey, string $event, array $extra = []): void
    {
        $this->channel()->info('agent.respond', array_merge([
            'slug' => $slug,
            'chat_key' => $chatKey,
            'event' => $event,
        ], $extra));
    }

    public function revisionPersist(string $agentId, int $revisionNumber, ?int $oldestPruned): void
    {
        $this->channel()->info('agent.revision_persist', [
            'agent_id' => $agentId,
            'revision_number' => $revisionNumber,
            'oldest_pruned' => $oldestPruned,
        ]);
    }

    /**
     * Log the result of a respond() call (type, length, preview) for debugging.
     */
    public function agentRespondResult(string $slug, string $chatKey, string $stage, mixed $result): void
    {
        $type = get_debug_type($result);
        $preview = '';
        $length = null;
        $isEmpty = null;

        if (is_string($result)) {
            $length = strlen($result);
            $isEmpty = $result === '';
            $preview = mb_substr($result, 0, 300);
        } elseif (is_array($result)) {
            $length = count($result);
            $preview = json_encode(array_slice($result, 0, 5, true), JSON_PARTIAL_OUTPUT_ON_ERROR);
        } elseif (is_object($result)) {
            $preview = method_exists($result, '__toString') ? mb_substr((string) $result, 0, 300) : '(object)';
        }

        $this->channel()->debug('agent.respond_result', [
            'slug'    => $slug,
            'chat_key' => $chatKey,
            'stage'   => $stage,
            'type'    => $type,
            'length'  => $length,
            'is_empty' => $isEmpty,
            'preview' => $preview,
        ]);
    }

    /**
     * Log a playground run or stream event (start / end / chunk).
     *
     * @param array<string, mixed> $extra
     */
    public function playgroundEvent(string $slug, string $chatKey, string $event, array $extra = []): void
    {
        $this->channel()->debug('playground.' . $event, array_merge([
            'slug'     => $slug,
            'chat_key' => $chatKey,
        ], $extra));
    }

    public function error(string $event, string $slug, Throwable $e): void
    {
        $this->channel()->error('agent.' . $event, [
            'slug' => $slug,
            'exception' => $e::class,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
