<?php

declare(strict_types=1);

use App\Models\ContentSource;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return list<string> */
    public function pivotKeywords(): array
    {
        return ['europe', 'defence', 'defense', 'readiness', 'deterrence', 'nato', 'eu', 'military mobility', 'ammunition', 'air defence', 'joint procurement', 'industrial capacity'];
    }

    /** @return list<array<string, mixed>> */
    public function contentSources(): array
    {
        $topics = ['europe', 'defence', 'readiness', 'security', 'industry'];
        $keywords = $this->pivotKeywords();

        return [
            $this->website('nato-defence-expenditure', 'NATO Defence Expenditure', $this->shared->sources()['nato_expenditure_2025'], $topics, $keywords, 125, 'official alliance statistics'),
            $this->website('nato-hague-summit', 'NATO Hague Summit', $this->shared->sources()['nato_hague_declaration'], $topics, $keywords, 115, 'official alliance policy'),
            $this->website('eda-defence-data', 'EDA Defence Data', $this->shared->sources()['eda_defence_data_portal'], $topics, $keywords, 125, 'official EU defence statistics'),
            $this->website('eda-card', 'EDA Coordinated Annual Review on Defence', $this->shared->sources()['eda_card'], $topics, $keywords, 105, 'official capability review'),
            $this->website('ec-readiness-2030', 'European Defence Readiness 2030', $this->shared->sources()['white_paper'], $topics, $keywords, 120, 'official EU readiness policy'),
            $this->website('eu-safe', 'Security Action for Europe', $this->shared->sources()['safe_policy'], $topics, $keywords, 110, 'official joint procurement finance'),
            $this->website('eu-asap', 'Act in Support of Ammunition Production', $this->shared->sources()['asap'], $topics, $keywords, 105, 'official industrial capacity programme'),
            $this->website('eu-edf', 'European Defence Fund', $this->shared->sources()['edf'], $topics, $keywords, 100, 'official collaborative research programme'),
            $this->website('eu-pesco', 'Permanent Structured Cooperation', $this->shared->sources()['pesco'], $topics, $keywords, 100, 'official capability cooperation'),
            $this->website('eu-military-mobility', 'European Military Mobility', $this->shared->sources()['military_mobility'], $topics, $keywords, 105, 'official mobility policy'),
        ];
    }

    /** @param list<string> $topics @param list<string> $keywords @return array<string, mixed> */
    private function website(string $externalId, string $name, string $url, array $topics, array $keywords, int $weight, string $category): array
    {
        return [
            'type' => ContentSource::TYPE_WEBSITE,
            'provider' => 'official',
            'name' => $name,
            'external_id' => $externalId,
            'source_url' => $url,
            'feed_url' => null,
            'language' => 'en',
            'topics' => $topics,
            'keywords' => $keywords,
            'metadata' => ['authority' => 'official', 'category' => $category],
            'weight' => $weight,
            'is_global' => false,
            'is_active' => true,
        ];
    }
};
