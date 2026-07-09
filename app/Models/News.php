<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NewsLocale;
use App\Services\Doomsday\NewsUpdater\NewsUrlNormalizer;
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
        'content_type',
        'source_name',
        'source_url',
        'canonical_source_url',
        'canonical_source_hash',
        'external_provider',
        'external_id',
        'embed_url',
        'preview_image_url',
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

    protected static function booted(): void
    {
        self::saving(function (News $news): void {
            $canonicalUrl = app(NewsUrlNormalizer::class)->canonicalize($news->source_url);

            $news->canonical_source_url = $canonicalUrl;
            $news->canonical_source_hash = $canonicalUrl !== null ? hash('sha256', $canonicalUrl) : null;
        });
    }

    public function countdown(): BelongsTo
    {
        return $this->belongsTo(Countdown::class);
    }
}
