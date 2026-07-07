<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SimoneBianco\LaravelAiAgents\Enums\AgentParameterSource;

class AiAgentToolParameterOverride extends Model
{
    use HasUuids;

    protected $table = 'ai_agent_tool_parameter_overrides';

    protected $guarded = ['id'];

    protected $casts = [
        'source' => AgentParameterSource::class,
        'literal_value' => 'array',
    ];

    public function toolBinding(): BelongsTo
    {
        return $this->belongsTo(AiAgentToolBinding::class, 'tool_binding_id');
    }
}
