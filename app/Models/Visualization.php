<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VisualizationType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class Visualization extends Model
{
    use HasFactory;

    protected $fillable = [
        'visualizable_id',
        'visualizable_type',
        'key',
        'type',
        'title',
        'description',
        'sources',
        'reasoning',
        'payload',
        'schema_version',
        'sort_order',
    ];

    protected $casts = [
        'type' => VisualizationType::class,
        'title' => 'array',
        'description' => 'array',
        'sources' => 'array',
        'reasoning' => 'array',
        'payload' => 'array',
        'schema_version' => 'integer',
        'sort_order' => 'integer',
    ];

    public function visualizable(): MorphTo
    {
        return $this->morphTo();
    }
}
