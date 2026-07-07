<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimoneBianco\LaravelAiAgents\DTOs\AgentDefinitionData;
use SimoneBianco\LaravelAiAgents\Exceptions\AgentIsSystemException;
use SimoneBianco\LaravelAiAgents\Exceptions\AgentLockedException;
use SimoneBianco\LaravelAiAgents\Exceptions\AgentNotFoundException;
use SimoneBianco\LaravelAiAgents\Models\AiAgent;
use SimoneBianco\LaravelAiAgents\Models\AiAgentScopeBinding;
use SimoneBianco\LaravelAiAgents\Models\AiAgentToolBinding;
use SimoneBianco\LaravelAiAgents\Models\AiAgentToolParameterOverride;
use SimoneBianco\LaravelAiAgents\Models\AiAgentVariableBinding;
use SimoneBianco\LaravelAiAgents\Registries\ScopeResolverRegistry;
use SimoneBianco\LaravelAiAgents\Support\AgentLogger;
use SimoneBianco\LaravelAiAgents\Support\PromptTemplateRewriter;

class AgentDefinitionService
{
    public function __construct(
        private readonly AgentLogger $logger,
        private readonly PromptTemplateRewriter $rewriter,
        private readonly ScopeResolverRegistry $scopeResolvers,
    ) {
    }

    /**
     * @param array<int, array<string, mixed>> $toolBindings
     * @param array<int, array<string, mixed>> $scopeBindings
     * @param array<int, array<string, mixed>> $variableBindings
     */
    public function create(
        AgentDefinitionData $data,
        array $toolBindings = [],
        array $scopeBindings = [],
        array $variableBindings = [],
    ): AiAgent {
        return DB::transaction(function () use ($data, $toolBindings, $scopeBindings, $variableBindings): AiAgent {
            $attributes = $this->dataToAttributes($data);
            $attributes['slug'] = $this->ensureUniqueSlug($data->slug !== '' ? $data->slug : Str::slug($data->name));

            if (! empty($attributes['system_prompt'])) {
                $attributes['system_prompt'] = $this->rewriter->rewriteForStorage(
                    (string) $attributes['system_prompt'],
                    $scopeBindings,
                    $this->scopeResolvers,
                );
            }

            $attributes['is_system'] = $attributes['is_system'] ?? false;
            $attributes['is_locked'] = $attributes['is_locked'] ?? false;
            $attributes['active_runs_count'] = 0;

            $agent = AiAgent::create($attributes);

            $this->syncBindings($agent, $toolBindings, $scopeBindings, $variableBindings);

            return $agent->fresh(['toolBindings', 'scopeBindings', 'variableBindings']) ?? $agent;
        });
    }

    /**
     * @param array<int, array<string, mixed>> $toolBindings
     * @param array<int, array<string, mixed>> $scopeBindings
     * @param array<int, array<string, mixed>> $variableBindings
     */
    public function update(
        string $agentId,
        AgentDefinitionData $data,
        array $toolBindings = [],
        array $scopeBindings = [],
        array $variableBindings = [],
    ): AiAgent {
        return DB::transaction(function () use ($agentId, $data, $toolBindings, $scopeBindings, $variableBindings): AiAgent {
            $agent = $this->find($agentId);
            $this->guardMutable($agent);

            $attributes = $this->dataToAttributes($data);
            unset($attributes['slug'], $attributes['is_system'], $attributes['is_locked']);

            if (! empty($attributes['system_prompt'])) {
                $attributes['system_prompt'] = $this->rewriter->rewriteForStorage(
                    (string) $attributes['system_prompt'],
                    $scopeBindings,
                    $this->scopeResolvers,
                );
            }

            $agent->update($attributes);

            // Wipe and re-create bindings
            $agent->toolBindings()->each(function (AiAgentToolBinding $b): void {
                $b->parameterOverrides()->delete();
                $b->delete();
            });
            $agent->scopeBindings()->delete();
            $agent->variableBindings()->delete();

            $this->syncBindings($agent, $toolBindings, $scopeBindings, $variableBindings);

            return $agent->fresh(['toolBindings', 'scopeBindings', 'variableBindings']) ?? $agent;
        });
    }

    public function delete(string $agentId): void
    {
        DB::transaction(function () use ($agentId): void {
            $agent = $this->find($agentId);
            $this->guardMutable($agent);

            $agent->toolBindings()->each(function (AiAgentToolBinding $b): void {
                $b->parameterOverrides()->delete();
                $b->delete();
            });
            $agent->scopeBindings()->delete();
            $agent->variableBindings()->delete();
            $agent->delete();
        });
    }

    public function duplicate(string $agentId, ?string $newName = null): AiAgent
    {
        return DB::transaction(function () use ($agentId, $newName): AiAgent {
            $original = AiAgent::with([
                'toolBindings.parameterOverrides',
                'scopeBindings',
                'variableBindings',
            ])->findOrFail($agentId);

            $attributes = $original->getAttributes();
            unset($attributes['id'], $attributes['created_at'], $attributes['updated_at'], $attributes['deleted_at']);

            $attributes['name'] = $newName ?? ($original->name . ' (copy)');
            $attributes['slug'] = $this->ensureUniqueSlug(Str::slug($attributes['name']));
            $attributes['is_system'] = false;
            $attributes['is_locked'] = false;
            $attributes['active_runs_count'] = 0;
            $attributes['current_revision_id'] = null;

            $clone = AiAgent::create($attributes);

            foreach ($original->toolBindings as $tb) {
                $tbAttrs = $tb->getAttributes();
                unset($tbAttrs['id'], $tbAttrs['created_at'], $tbAttrs['updated_at']);
                $tbAttrs['agent_id'] = $clone->id;
                $newBinding = AiAgentToolBinding::create($tbAttrs);

                foreach ($tb->parameterOverrides as $po) {
                    $poAttrs = $po->getAttributes();
                    unset($poAttrs['id'], $poAttrs['created_at'], $poAttrs['updated_at']);
                    $poAttrs['tool_binding_id'] = $newBinding->id;
                    AiAgentToolParameterOverride::create($poAttrs);
                }
            }

            foreach ($original->scopeBindings as $sb) {
                $sbAttrs = $sb->getAttributes();
                unset($sbAttrs['id'], $sbAttrs['created_at'], $sbAttrs['updated_at']);
                $sbAttrs['agent_id'] = $clone->id;
                AiAgentScopeBinding::create($sbAttrs);
            }

            foreach ($original->variableBindings as $vb) {
                $vbAttrs = $vb->getAttributes();
                unset($vbAttrs['id'], $vbAttrs['created_at'], $vbAttrs['updated_at']);
                $vbAttrs['agent_id'] = $clone->id;
                AiAgentVariableBinding::create($vbAttrs);
            }

            return $clone->fresh(['toolBindings', 'scopeBindings', 'variableBindings']) ?? $clone;
        });
    }

    public function find(string $idOrSlug): AiAgent
    {
        try {
            $query = AiAgent::with(['toolBindings.parameterOverrides', 'scopeBindings', 'variableBindings']);

            if (Str::isUuid($idOrSlug)) {
                return $query->where('id', $idOrSlug)->firstOrFail();
            }

            return $query->where('slug', $idOrSlug)->firstOrFail();
        } catch (ModelNotFoundException) {
            throw new AgentNotFoundException("Agent '{$idOrSlug}' not found");
        }
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function paginate(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        $query = AiAgent::query()->with(['toolBindings', 'scopeBindings']);

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (! empty($filters['scope'])) {
            $query->where('scope', $filters['scope']);
        }
        if (! empty($filters['search'])) {
            $term = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($term): void {
                $q->where('name', 'like', $term)->orWhere('description', 'like', $term);
            });
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    private function guardMutable(AiAgent $agent): void
    {
        if ($agent->is_locked) {
            throw new AgentLockedException("Agent '{$agent->slug}' is locked");
        }

        $this->clearStaleActiveRunLock($agent);

        if ((int) $agent->active_runs_count > 0) {
            Log::channel((string) config('ai-agents.log_channel', 'agents'))->warning('agents.mutable_while_running', [
                'agent_id' => (string) $agent->id,
                'agent_slug' => (string) $agent->slug,
                'active_runs_count' => (int) $agent->active_runs_count,
            ]);
        }
        if ($agent->is_system) {
            throw new AgentIsSystemException("Agent '{$agent->slug}' is a system agent");
        }
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

        Log::channel((string) config('ai-agents.log_channel', 'agents'))->warning('agents.stale_lock_auto_cleared', [
            'agent_id' => (string) $agent->id,
            'agent_slug' => (string) $agent->slug,
            'max_age_minutes' => $maxAgeMinutes,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function dataToAttributes(AgentDefinitionData $data): array
    {
        return [
            'name' => $data->name,
            'slug' => $data->slug,
            'description' => $data->description,
            'type' => $data->type,
            'scope' => $data->scope,
            'provider' => $data->provider,
            'model' => $data->model,
            'system_prompt' => $data->system_prompt,
            'temperature' => $data->temperature,
            'top_p' => $data->top_p,
            'max_completion_tokens' => $data->max_completion_tokens,
            'parallel_tool_calls' => $data->parallel_tool_calls,
            'developer_role_for_instructions' => $data->developer_role_for_instructions,
            'response_format' => $data->response_format,
            'response_schema' => $data->response_schema,
            'streaming_mode' => $data->streaming_mode,
            'history_driver' => $data->history_driver,
            'metadata' => $data->metadata,
        ];
    }

    private function ensureUniqueSlug(string $base): string
    {
        $base = $base !== '' ? $base : 'agent';
        $slug = $base;
        $i = 1;
        while (AiAgent::where('slug', $slug)->exists()) {
            $i++;
            $slug = $base . '-' . $i;
        }
        return $slug;
    }

    /**
     * @param array<int, array<string, mixed>> $toolBindings
     * @param array<int, array<string, mixed>> $scopeBindings
     * @param array<int, array<string, mixed>> $variableBindings
     */
    private function syncBindings(
        AiAgent $agent,
        array $toolBindings,
        array $scopeBindings,
        array $variableBindings,
    ): void {
        foreach ($toolBindings as $i => $tb) {
            $binding = AiAgentToolBinding::create([
                'agent_id' => $agent->id,
                'tool_id' => $tb['tool_id'],
                'position' => $tb['position'] ?? $i,
                'alias' => $tb['alias'] ?? null,
                'sub_agent_history_mode' => $tb['sub_agent_history_mode'] ?? 'stateless',
                'notes' => $tb['notes'] ?? null,
            ]);

            foreach (($tb['parameter_overrides'] ?? []) as $po) {
                AiAgentToolParameterOverride::create([
                    'tool_binding_id' => $binding->id,
                    'parameter_path' => $po['parameter_path'],
                    'source' => $po['source'],
                    'literal_value' => $po['literal_value'] ?? null,
                    'variable_expression' => $po['variable_expression'] ?? null,
                ]);
            }
        }

        foreach ($scopeBindings as $i => $sb) {
            $metadata = is_array($sb['metadata'] ?? null) ? $sb['metadata'] : [];
            if (isset($sb['scope_label'])) {
                $metadata['scope_label'] = (string) $sb['scope_label'];
            }
            AiAgentScopeBinding::create([
                'agent_id' => $agent->id,
                'scope_type' => $sb['scope_type'],
                'scope_key' => $sb['scope_key'],
                'position' => $sb['position'] ?? $i,
                'alias' => $sb['alias'] ?? null,
                'metadata' => $metadata ?: null,
            ]);
        }

        foreach ($variableBindings as $vb) {
            AiAgentVariableBinding::create([
                'agent_id' => $agent->id,
                'key' => $vb['key'],
                'provider' => $vb['provider'],
                'payload' => $vb['payload'] ?? null,
            ]);
        }
    }
}
