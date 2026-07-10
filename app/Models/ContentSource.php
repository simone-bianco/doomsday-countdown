<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class ContentSource extends Model
{
    use HasFactory;

    public const TYPE_YOUTUBE_CHANNEL = 'youtube_channel';

    public const TYPE_RSS_FEED = 'rss_feed';

    public const TYPE_WEBSITE = 'website';

    public const PROVIDER_YOUTUBE = 'youtube';

    public const PROVIDER_GOOGLE_NEWS = 'google_news';

    protected $fillable = [
        'type',
        'provider',
        'name',
        'external_id',
        'source_url',
        'feed_url',
        'language',
        'topics',
        'keywords',
        'metadata',
        'weight',
        'is_global',
        'is_active',
    ];

    protected $casts = [
        'topics' => 'array',
        'keywords' => 'array',
        'metadata' => 'array',
        'weight' => 'integer',
        'is_global' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function countdowns(): BelongsToMany
    {
        return $this->belongsToMany(Countdown::class)
            ->withPivot(['keywords', 'excluded_keywords', 'weight', 'is_active'])
            ->withTimestamps();
    }
}
