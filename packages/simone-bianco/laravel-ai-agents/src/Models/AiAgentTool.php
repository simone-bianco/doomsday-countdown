<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SimoneBianco\LaravelAiAgents\Enums\AgentToolKind;

class AiAgentTool extends Model
{
    use HasUuids;

    protected $table = 'ai_agent_tools';

    protected $guarded = ['id'];

    protected $casts = [
        'kind' => AgentToolKind::class,
        'default_parameters' => 'array',
        'parameter_manifest' => 'array',
        'allowed_sub_agent_types' => 'array',
        'allowed_hosts' => 'array',
        'dynamic_definition' => 'array',
        'is_enabled' => 'bool',
    ];

    public function subAgent(): BelongsTo
    {
        return $this->belongsTo(AiAgent::class, 'sub_agent_id');
    }
}
