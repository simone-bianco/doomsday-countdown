<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAgentRevision extends Model
{
    use HasUuids;

    protected $table = 'ai_agent_revisions';

    public $timestamps = false;

    const CREATED_AT = 'created_at';

    protected $guarded = ['id'];

    protected $casts = [
        'snapshot' => 'array',
        'revision_number' => 'int',
        'created_at' => 'datetime',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(AiAgent::class, 'agent_id');
    }
}
