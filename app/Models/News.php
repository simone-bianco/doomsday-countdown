<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NewsLocale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'countdown_id',
        'locale',
        'title',
        'excerpt',
        'source_name',
        'source_url',
        'image_path',
        'published_at',
        'sort_order',
        'is_featured',
    ];

    protected $casts = [
        'locale' => NewsLocale::class,
        'published_at' => 'immutable_datetime',
        'sort_order' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function countdown(): BelongsTo
    {
        return $this->belongsTo(Countdown::class);
    }
}
