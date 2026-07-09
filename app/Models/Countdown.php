<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property string $slug
 */
final class Countdown extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'summary',
        'description',
        'causes',
        'consequences',
        'recommended_actions',
        'severity',
        'status',
        'target_date',
        'image_path',
        'accent_color',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'title' => 'array',
        'summary' => 'array',
        'description' => 'array',
        'causes' => 'array',
        'consequences' => 'array',
        'recommended_actions' => 'array',
        'severity' => CountdownSeverity::class,
        'status' => CountdownStatus::class,
        'target_date' => 'immutable_datetime',
        'sort_order' => 'integer',
        'is_published' => 'boolean',
    ];

    /**
     * @param  Builder<Countdown>  $query
     * @return Builder<Countdown>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function projections(): HasMany
    {
        return $this->hasMany(Projection::class);
    }

    public function visualizations(): MorphMany
    {
        return $this->morphMany(Visualization::class, 'visualizable');
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    public function contentSources(): BelongsToMany
    {
        return $this->belongsToMany(ContentSource::class)
            ->withPivot(['keywords', 'excluded_keywords', 'weight', 'is_active'])
            ->withTimestamps();
    }

    public function initiatives(): HasMany
    {
        return $this->hasMany(Initiative::class);
    }
}
