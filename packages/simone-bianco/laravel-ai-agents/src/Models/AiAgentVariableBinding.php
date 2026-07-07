<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAgentVariableBinding extends Model
{
    use HasUuids;

    protected $table = 'ai_agent_variable_bindings';

    protected $guarded = ['id'];

    protected $casts = [
        'payload' => 'array',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(AiAgent::class, 'agent_id');
    }
}
