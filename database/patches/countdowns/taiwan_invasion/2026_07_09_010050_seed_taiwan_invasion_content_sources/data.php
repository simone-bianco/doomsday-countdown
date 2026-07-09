<?php

declare(strict_types=1);

use App\Models\ContentSource;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<int, string> */
    public function contentSourcePivotKeywords(): array
    {
        return [
            'taiwan',
            'china',
            'ccp',
            'chinese communist party',
            'invasion',
            'blockade',
            'strait',
            'pla',
            'war',
            'military',
            'coast guard',
            'xi',
            'taipei',
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function contentSources(): array
    {
        $topics = ['china', 'taiwan', 'geopolitics', 'ccp', 'human-rights'];
        $keywords = ['china', 'ccp', 'taiwan', 'xi', 'communist party', 'beijing'];

        return [
            [
                'type' => ContentSource::TYPE_YOUTUBE_CHANNEL,
                'provider' => ContentSource::PROVIDER_YOUTUBE,
                'name' => 'China Uncensored',
                'external_id' => 'UCgFP46yVT-GG4o1TgXn-04Q',
                'source_url' => 'https://www.youtube.com/channel/UCgFP46yVT-GG4o1TgXn-04Q',
                'feed_url' => 'https://www.youtube.com/feeds/videos.xml?channel_id=UCgFP46yVT-GG4o1TgXn-04Q',
                'topics' => $topics,
                'keywords' => $keywords,
                'metadata' => ['orientation' => 'china-critical commentary'],
                'weight' => 120,
                'is_global' => false,
                'is_active' => true,
            ],
            [
                'type' => ContentSource::TYPE_YOUTUBE_CHANNEL,
                'provider' => ContentSource::PROVIDER_YOUTUBE,
                'name' => 'SerpentZA',
                'external_id' => 'user:serpentza',
                'source_url' => 'https://www.youtube.com/user/serpentza',
                'feed_url' => 'https://www.youtube.com/feeds/videos.xml?user=serpentza',
                'topics' => $topics,
                'keywords' => $keywords,
                'metadata' => ['orientation' => 'china-critical commentary'],
                'weight' => 105,
                'is_global' => false,
                'is_active' => true,
            ],
            [
                'type' => ContentSource::TYPE_YOUTUBE_CHANNEL,
                'provider' => ContentSource::PROVIDER_YOUTUBE,
                'name' => 'laowhy86',
                'external_id' => 'user:laowhy86',
                'source_url' => 'https://www.youtube.com/user/laowhy86',
                'feed_url' => 'https://www.youtube.com/feeds/videos.xml?user=laowhy86',
                'topics' => $topics,
                'keywords' => $keywords,
                'metadata' => ['orientation' => 'china-critical commentary'],
                'weight' => 100,
                'is_global' => false,
                'is_active' => true,
            ],
            [
                'type' => ContentSource::TYPE_YOUTUBE_CHANNEL,
                'provider' => ContentSource::PROVIDER_YOUTUBE,
                'name' => 'China Observer',
                'external_id' => 'user:ChinaObserver',
                'source_url' => 'https://www.youtube.com/user/ChinaObserver',
                'feed_url' => 'https://www.youtube.com/feeds/videos.xml?user=ChinaObserver',
                'topics' => $topics,
                'keywords' => $keywords,
                'metadata' => ['orientation' => 'china watch'],
                'weight' => 95,
                'is_global' => false,
                'is_active' => true,
            ],
            [
                'type' => ContentSource::TYPE_YOUTUBE_CHANNEL,
                'provider' => ContentSource::PROVIDER_YOUTUBE,
                'name' => 'NTD',
                'external_id' => 'user:NTDTV',
                'source_url' => 'https://www.youtube.com/user/NTDTV',
                'feed_url' => 'https://www.youtube.com/feeds/videos.xml?user=NTDTV',
                'topics' => $topics,
                'keywords' => $keywords,
                'metadata' => ['orientation' => 'china-critical news'],
                'weight' => 90,
                'is_global' => false,
                'is_active' => true,
            ],
        ];
    }
};
