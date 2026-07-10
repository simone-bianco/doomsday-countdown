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
        $newsKeywords = ['taiwan', 'china', 'beijing', 'pla', 'blockade', 'invasion', 'strait'];

        return [
            [
                'type' => ContentSource::TYPE_YOUTUBE_CHANNEL,
                'provider' => ContentSource::PROVIDER_YOUTUBE,
                'name' => 'China Uncensored',
                'external_id' => 'UCgFP46yVT-GG4o1TgXn-04Q',
                'source_url' => 'https://www.youtube.com/channel/UCgFP46yVT-GG4o1TgXn-04Q',
                'feed_url' => 'https://www.youtube.com/feeds/videos.xml?channel_id=UCgFP46yVT-GG4o1TgXn-04Q',
                'language' => 'en',
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
                'language' => 'en',
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
                'language' => 'en',
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
                'name' => 'NTD',
                'external_id' => 'user:NTDTV',
                'source_url' => 'https://www.youtube.com/user/NTDTV',
                'feed_url' => 'https://www.youtube.com/feeds/videos.xml?user=NTDTV',
                'language' => 'en',
                'topics' => $topics,
                'keywords' => $keywords,
                'metadata' => ['orientation' => 'china-critical news'],
                'weight' => 90,
                'is_global' => false,
                'is_active' => true,
            ],
            [
                'type' => ContentSource::TYPE_RSS_FEED,
                'provider' => ContentSource::PROVIDER_GOOGLE_NEWS,
                'name' => 'Google News EN',
                'external_id' => 'google-news-en',
                'source_url' => 'https://news.google.com',
                'feed_url' => 'https://news.google.com/rss/search?hl=en-US&gl=US&ceid=US:en',
                'language' => 'en',
                'topics' => $topics,
                'keywords' => $newsKeywords,
                'metadata' => ['locale' => 'en-US', 'country' => 'US', 'ceid' => 'US:en'],
                'weight' => 118,
                'is_global' => false,
                'is_active' => true,
            ],
            [
                'type' => ContentSource::TYPE_RSS_FEED,
                'provider' => ContentSource::PROVIDER_GOOGLE_NEWS,
                'name' => 'Google News IT',
                'external_id' => 'google-news-it',
                'source_url' => 'https://news.google.com',
                'feed_url' => 'https://news.google.com/rss/search?hl=it&gl=IT&ceid=IT:it',
                'language' => 'it',
                'topics' => $topics,
                'keywords' => $newsKeywords,
                'metadata' => ['locale' => 'it', 'country' => 'IT', 'ceid' => 'IT:it'],
                'weight' => 112,
                'is_global' => false,
                'is_active' => true,
            ],
            [
                'type' => ContentSource::TYPE_RSS_FEED,
                'provider' => ContentSource::PROVIDER_GOOGLE_NEWS,
                'name' => 'Reuters via Google News',
                'external_id' => 'google-news-reuters-en',
                'source_url' => 'https://www.reuters.com',
                'feed_url' => 'https://news.google.com/rss/search?hl=en-US&gl=US&ceid=US:en',
                'language' => 'en',
                'topics' => $topics,
                'keywords' => $newsKeywords,
                'metadata' => ['site' => 'reuters.com', 'locale' => 'en-US', 'country' => 'US', 'ceid' => 'US:en'],
                'weight' => 116,
                'is_global' => false,
                'is_active' => true,
            ],
            [
                'type' => ContentSource::TYPE_RSS_FEED,
                'provider' => ContentSource::PROVIDER_GOOGLE_NEWS,
                'name' => 'CSIS via Google News',
                'external_id' => 'google-news-csis-en',
                'source_url' => 'https://www.csis.org',
                'feed_url' => 'https://news.google.com/rss/search?hl=en-US&gl=US&ceid=US:en',
                'language' => 'en',
                'topics' => $topics,
                'keywords' => $newsKeywords,
                'metadata' => ['site' => 'csis.org', 'locale' => 'en-US', 'country' => 'US', 'ceid' => 'US:en'],
                'weight' => 92,
                'is_global' => false,
                'is_active' => true,
            ],
            [
                'type' => ContentSource::TYPE_RSS_FEED,
                'provider' => ContentSource::PROVIDER_GOOGLE_NEWS,
                'name' => 'U.S. Defense via Google News',
                'external_id' => 'google-news-defense-en',
                'source_url' => 'https://www.defense.gov',
                'feed_url' => 'https://news.google.com/rss/search?hl=en-US&gl=US&ceid=US:en',
                'language' => 'en',
                'topics' => $topics,
                'keywords' => $newsKeywords,
                'metadata' => ['site' => 'defense.gov', 'locale' => 'en-US', 'country' => 'US', 'ceid' => 'US:en'],
                'weight' => 82,
                'is_global' => false,
                'is_active' => true,
            ],
            [
                'type' => ContentSource::TYPE_RSS_FEED,
                'provider' => ContentSource::PROVIDER_GOOGLE_NEWS,
                'name' => 'ANSA via Google News',
                'external_id' => 'google-news-ansa-it',
                'source_url' => 'https://www.ansa.it',
                'feed_url' => 'https://news.google.com/rss/search?hl=it&gl=IT&ceid=IT:it',
                'language' => 'it',
                'topics' => $topics,
                'keywords' => $newsKeywords,
                'metadata' => ['site' => 'ansa.it', 'locale' => 'it', 'country' => 'IT', 'ceid' => 'IT:it'],
                'weight' => 80,
                'is_global' => false,
                'is_active' => true,
            ],
        ];
    }
};
