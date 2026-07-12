<?php

declare(strict_types=1);

use App\Models\ContentSource;

return new class
{
    /** @return array<int, string> */
    public function contentSourcePivotKeywords(): array
    {
        return [
            'biodiversity', 'species', 'extinction', 'habitat', 'ecosystem', 'conservation',
            'restoration', 'protected area', 'wetland', 'forest', 'red list', 'wildlife',
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function contentSources(): array
    {
        $topics = ['biodiversity', 'conservation', 'ecosystems', 'restoration', 'wildlife'];
        $keywords = $this->contentSourcePivotKeywords();
        $englishFeed = static fn (string $query): string => 'https://news.google.com/rss/search?q='.rawurlencode($query).'&hl=en-US&gl=US&ceid=US:en';
        $italianFeed = static fn (string $query): string => 'https://news.google.com/rss/search?q='.rawurlencode($query).'&hl=it&gl=IT&ceid=IT:it';

        return [
            $this->googleNewsSource('biodiversity-google-news-en', 'Google News Biodiversity EN', 'https://news.google.com', $englishFeed('biodiversity'), 'en', $topics, $keywords, 118, ['locale' => 'en-US', 'country' => 'US', 'ceid' => 'US:en']),
            $this->googleNewsSource('biodiversity-google-news-it', 'Google News Biodiversità IT', 'https://news.google.com', $italianFeed('biodiversità'), 'it', $topics, $keywords, 110, ['locale' => 'it', 'country' => 'IT', 'ceid' => 'IT:it']),
            $this->googleNewsSource('biodiversity-iucn-en', 'IUCN via Google News', 'https://iucn.org', $englishFeed('biodiversity site:iucn.org'), 'en', $topics, $keywords, 116, ['site' => 'iucn.org']),
            $this->googleNewsSource('biodiversity-cbd-en', 'CBD via Google News', 'https://www.cbd.int', $englishFeed('biodiversity site:cbd.int'), 'en', $topics, $keywords, 115, ['site' => 'cbd.int']),
            $this->googleNewsSource('biodiversity-ipbes-en', 'IPBES via Google News', 'https://www.ipbes.net', $englishFeed('biodiversity site:ipbes.net'), 'en', $topics, $keywords, 112, ['site' => 'ipbes.net']),
            $this->googleNewsSource('biodiversity-protected-planet-en', 'Protected Planet via Google News', 'https://www.protectedplanet.net', $englishFeed('biodiversity site:protectedplanet.net'), 'en', $topics, $keywords, 108, ['site' => 'protectedplanet.net']),
            $this->googleNewsSource('biodiversity-fao-en', 'FAO Biodiversity via Google News', 'https://www.fao.org', $englishFeed('biodiversity site:fao.org'), 'en', $topics, $keywords, 106, ['site' => 'fao.org']),
            $this->googleNewsSource('biodiversity-ramsar-en', 'Convention on Wetlands via Google News', 'https://www.ramsar.org', $englishFeed('biodiversity site:ramsar.org'), 'en', $topics, $keywords, 104, ['site' => 'ramsar.org']),
            $this->googleNewsSource('biodiversity-zsl-en', 'ZSL via Google News', 'https://www.zsl.org', $englishFeed('biodiversity site:zsl.org'), 'en', $topics, $keywords, 102, ['site' => 'zsl.org']),
            $this->googleNewsSource('biodiversity-unep-restoration-en', 'UN Ecosystem Restoration via Google News', 'https://www.decadeonrestoration.org', $englishFeed('ecosystem restoration site:decadeonrestoration.org'), 'en', $topics, $keywords, 100, ['site' => 'decadeonrestoration.org']),
        ];
    }

    /**
     * @param  array<int, string>  $topics
     * @param  array<int, string>  $keywords
     * @param  array<string, string>  $metadata
     * @return array<string, mixed>
     */
    private function googleNewsSource(string $externalId, string $name, string $sourceUrl, string $feedUrl, string $language, array $topics, array $keywords, int $weight, array $metadata): array
    {
        return [
            'type' => ContentSource::TYPE_RSS_FEED,
            'provider' => ContentSource::PROVIDER_GOOGLE_NEWS,
            'name' => $name,
            'external_id' => $externalId,
            'source_url' => $sourceUrl,
            'feed_url' => $feedUrl,
            'language' => $language,
            'topics' => $topics,
            'keywords' => $keywords,
            'metadata' => $metadata,
            'weight' => $weight,
            'is_global' => false,
            'is_active' => true,
        ];
    }
};
