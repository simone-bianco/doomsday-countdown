<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimoneBianco\LaravelAiAgents\Exceptions\AgentLockedException;
use SimoneBianco\LaravelAiAgents\Models\AiAgent;
use SimoneBianco\LaravelAiAgents\Models\AiAgentRevision;
use SimoneBianco\LaravelAiAgents\Support\AgentLogger;

class AgentRevisionService
{
    public const MAX_REVISIONS = 10;

    public function __construct(private readonly AgentLogger $logger)
    {
    }

    public function saveRevision(AiAgent $agent, ?string $note = null, ?string $authorId = null): AiAgentRevision
    {
        if ($agent->is_locked) {
            throw new AgentLockedException("Agent '{$agent->slug}' is locked");
        }

        $this->clearStaleActiveRunLock($agent);

        if ((int) $agent->active_runs_count > 0) {
            Log::channel((string) config('ai-agents.log_channel', 'agents'))->warning('agents.revision_saved_while_running', [
                'agent_id' => (string) $agent->id,
                'agent_slug' => (string) $agent->slug,
                'active_runs_count' => (int) $agent->active_runs_count,
            ]);
        }

        return DB::transaction(function () use ($agent, $note, $authorId): AiAgentRevision {
            $agent->loadMissing(['toolBindings.parameterOverrides', 'scopeBindings', 'variableBindings']);

            $snapshot = [
                'agent' => $agent->toArray(),
                'tool_bindings' => $agent->toolBindings->toArray(),
                'scope_bindings' => $agent->scopeBindings->toArray(),
                'variable_bindings' => $agent->variableBindings->toArray(),
            ];

            $nextNumber = ((int) AiAgentRevision::where('agent_id', $agent->id)->max('revision_number')) + 1;

            $revision = AiAgentRevision::create([
                'agent_id' => $agent->id,
                'revision_number' => $nextNumber,
                'snapshot' => $snapshot,
                'note' => $note,
                'author_id' => $authorId,
                'created_at' => now(),
            ]);

            $oldestPruned = null;
            $count = AiAgentRevision::where('agent_id', $agent->id)->count();
            if ($count > self::MAX_REVISIONS) {
                $toPrune = AiAgentRevision::where('agent_id', $agent->id)
                    ->orderBy('revision_number', 'asc')
                    ->limit($count - self::MAX_REVISIONS)
                    ->get();
                foreach ($toPrune as $old) {
                    $oldestPruned = (int) $old->revision_number;
                    $old->delete();
                }
            }

            $agent->forceFill(['current_revision_id' => $revision->id])->save();

            $this->logger->revisionPersist((string) $agent->id, $nextNumber, $oldestPruned);

            return $revision;
        });
    }

    public function restore(AiAgent $agent, string $revisionId): AiAgent
    {
        if ($agent->is_locked) {
            throw new AgentLockedException("Agent '{$agent->slug}' is locked");
        }

        return DB::transaction(function () use ($agent, $revisionId): AiAgent {
            $revision = AiAgentRevision::where('agent_id', $agent->id)
                ->where('id', $revisionId)
                ->firstOrFail();

            // Save a restore-point revision before applying.
            $this->saveRevision($agent, "Auto-snapshot before restore of revision {$revision->revision_number}");

            $snapshot = (array) $revision->snapshot;
            $agentSnap = (array) ($snapshot['agent'] ?? []);

            $restorable = [
                'name', 'description', 'type', 'scope', 'provider', 'model',
                'system_prompt', 'temperature', 'top_p', 'max_completion_tokens',
                'parallel_tool_calls', 'developer_role_for_instructions',
                'response_format', 'response_schema', 'streaming_mode',
                'history_driver', 'metadata',
            ];

            $update = [];
            foreach ($restorable as $key) {
                if (array_key_exists($key, $agentSnap)) {
                    $update[$key] = $agentSnap[$key];
                }
            }

            $agent->update($update);

            return $agent->fresh() ?? $agent;
        });
    }

    public function listRevisions(AiAgent $agent): Collection
    {
        return AiAgentRevision::where('agent_id', $agent->id)
            ->orderBy('revision_number', 'desc')
            ->get();
    }

    private function clearStaleActiveRunLock(AiAgent $agent): void
    {
        if ((int) $agent->active_runs_count <= 0) {
            return;
        }

        $maxAgeMinutes = max(1, (int) config('ai-agents.stale_lock_max_age_minutes', 15));
        $updatedAt = $agent->updated_at;

        if ($updatedAt === null || $updatedAt->greaterThan(now()->subMinutes($maxAgeMinutes))) {
            return;
        }

        AiAgent::where('id', $agent->id)->update(['active_runs_count' => 0]);
        $agent->active_runs_count = 0;

        Log::channel((string) config('ai-agents.log_channel', 'agents'))->warning('agents.stale_lock_auto_cleared_for_revision', [
            'agent_id' => (string) $agent->id,
            'agent_slug' => (string) $agent->slug,
            'max_age_minutes' => $maxAgeMinutes,
        ]);
    }

    /**
     * @return array<string, array{0: mixed, 1: mixed}>
     */
    public function diff(string $revisionIdA, string $revisionIdB): array
    {
        $a = AiAgentRevision::findOrFail($revisionIdA);
        $b = AiAgentRevision::findOrFail($revisionIdB);

        $aAgent = (array) (((array) $a->snapshot)['agent'] ?? []);
        $bAgent = (array) (((array) $b->snapshot)['agent'] ?? []);

        $diff = [];
        $keys = array_unique(array_merge(array_keys($aAgent), array_keys($bAgent)));
        foreach ($keys as $key) {
            $av = $aAgent[$key] ?? null;
            $bv = $bAgent[$key] ?? null;
            if ($av !== $bv) {
                $diff[$key] = [$av, $bv];
            }
        }

        return $diff;
    }
}
