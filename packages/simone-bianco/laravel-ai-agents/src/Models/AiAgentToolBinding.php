<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SimoneBianco\LaravelAiAgents\Enums\AgentSubAgentHistoryMode;

class AiAgentToolBinding extends Model
{
    use HasUuids;

    protected $table = 'ai_agent_tool_bindings';

    protected $guarded = ['id'];

    protected $casts = [
        'sub_agent_history_mode' => AgentSubAgentHistoryMode::class,
        'position' => 'int',
    ];

    public function tool(): BelongsTo
    {
        return $this->belongsTo(AiAgentTool::class, 'tool_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(AiAgent::class, 'agent_id');
    }

    public function parameterOverrides(): HasMany
    {
        return $this->hasMany(AiAgentToolParameterOverride::class, 'tool_binding_id');
    }
}
