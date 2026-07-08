<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InitiativeLocale;
use App\Enums\InitiativeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Initiative extends Model
{
    use HasFactory;

    protected $fillable = [
        'countdown_id',
        'locale',
        'type',
        'title',
        'excerpt',
        'body',
        'organization',
        'url',
        'image_path',
        'cta_label',
        'starts_at',
        'ends_at',
        'sort_order',
        'is_featured',
    ];

    protected $casts = [
        'locale' => InitiativeLocale::class,
        'type' => InitiativeType::class,
        'starts_at' => 'immutable_datetime',
        'ends_at' => 'immutable_datetime',
        'sort_order' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function countdown(): BelongsTo
    {
        return $this->belongsTo(Countdown::class);
    }
}
