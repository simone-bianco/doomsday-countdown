<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SimoneBianco\LaravelAiAgents\Enums\AgentResponseFormat;
use SimoneBianco\LaravelAiAgents\Enums\AgentStreamingMode;

class AiAgent extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'ai_agents';

    protected $guarded = ['id'];

    protected $casts = [
        'response_format' => AgentResponseFormat::class,
        'streaming_mode' => AgentStreamingMode::class,
        'response_schema' => 'array',
        'metadata' => 'array',
        'is_system' => 'bool',
        'is_locked' => 'bool',
        'is_generating_prompt' => 'bool',
        'parallel_tool_calls' => 'bool',
        'developer_role_for_instructions' => 'bool',
        'active_runs_count' => 'int',
        'temperature' => 'float',
        'top_p' => 'float',
        'max_completion_tokens' => 'int',
    ];

    public function toolBindings(): HasMany
    {
        return $this->hasMany(AiAgentToolBinding::class, 'agent_id')->orderBy('position');
    }

    public function scopeBindings(): HasMany
    {
        return $this->hasMany(AiAgentScopeBinding::class, 'agent_id')->orderBy('position');
    }

    public function variableBindings(): HasMany
    {
        return $this->hasMany(AiAgentVariableBinding::class, 'agent_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(AiAgentRevision::class, 'agent_id');
    }

    public function currentRevision(): BelongsTo
    {
        return $this->belongsTo(AiAgentRevision::class, 'current_revision_id');
    }
}
