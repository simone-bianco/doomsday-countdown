<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProjectionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

final class Projection extends Model
{
    use HasFactory;

    protected $fillable = [
        'countdown_id',
        'type',
        'target_date',
        'title',
        'summary',
        'confidence_score',
        'probability_score',
        'trend',
        'methodology',
        'sort_order',
    ];

    protected $casts = [
        'type' => ProjectionType::class,
        'target_date' => 'immutable_datetime',
        'title' => 'array',
        'summary' => 'array',
        'confidence_score' => 'integer',
        'probability_score' => 'integer',
        'methodology' => 'array',
        'sort_order' => 'integer',
    ];

    public function countdown(): BelongsTo
    {
        return $this->belongsTo(Countdown::class);
    }

    public function visualizations(): MorphMany
    {
        return $this->morphMany(Visualization::class, 'visualizable');
    }
}
