<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAgentScopeBinding extends Model
{
    use HasUuids;

    protected $table = 'ai_agent_scope_bindings';

    protected $guarded = ['id'];

    protected $casts = [
        'metadata' => 'array',
        'position' => 'int',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(AiAgent::class, 'agent_id');
    }
}
