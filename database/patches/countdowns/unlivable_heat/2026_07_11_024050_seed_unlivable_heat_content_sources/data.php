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
        return ['extreme heat', 'heatwave', 'heat stress', 'utci', 'tropical night', 'temperature anomaly', 'heat mortality', 'cooling degree days', 'heat-health action plan', 'climate adaptation'];
    }

    /** @return array<int, array<string, mixed>> */
    public function contentSources(): array
    {
        $sources = $this->shared->sources();
        $topics = ['climate', 'extreme-heat', 'public-health', 'adaptation'];
        $keywords = $this->contentSourcePivotKeywords();

        return [
            $this->website('World Meteorological Organization — global temperature 2025', 'wmo', 'unlivable-heat:wmo-2025', $sources['wmo_2025'], $topics, $keywords, 125),
            $this->website('Copernicus — southeastern Europe heat and drought', 'copernicus', 'unlivable-heat:copernicus-southeast-2024', $sources['copernicus_southeast'], $topics, $keywords, 125),
            $this->website('Copernicus — European thermal stress', 'copernicus', 'unlivable-heat:copernicus-thermal-2024', $sources['copernicus_thermal'], $topics, $keywords, 120),
            $this->website('World Health Organization — climate change, heat and health', 'who', 'unlivable-heat:who-heat-health', $sources['who_heat_facts'], $topics, $keywords, 125),
            $this->website('IPCC AR6 Working Group I Summary for Policymakers', 'ipcc', 'unlivable-heat:ipcc-ar6-wgi-spm', $sources['ipcc_spm'], $topics, $keywords, 125),
            $this->website('Eurostat — cooling degree days dataset', 'eurostat', 'unlivable-heat:eurostat-cdd', $sources['eurostat_cdd_data'], $topics, $keywords, 115),
            $this->website('United Nations — Call to Action on Extreme Heat', 'un', 'unlivable-heat:un-extreme-heat', $sources['un_extreme_heat'], $topics, $keywords, 105),
            $this->website('Climate-ADAPT — heat-health action plans', 'eea', 'unlivable-heat:climate-adapt-hhap', $sources['climate_adapt_hhap'], $topics, $keywords, 110),
            $this->website('C40 Cool Cities Network', 'c40', 'unlivable-heat:c40-cool-cities', $sources['c40_cool_cities'], $topics, $keywords, 100),
            $this->website('Global Heat Health Information Network', 'ghhin', 'unlivable-heat:ghhin', $sources['ghhin'], $topics, $keywords, 110),
        ];
    }

    /**
     * @param  array<int, string>  $topics
     * @param  array<int, string>  $keywords
     * @return array<string, mixed>
     */
    private function website(string $name, string $provider, string $externalId, string $url, array $topics, array $keywords, int $weight): array
    {
        return [
            'type' => ContentSource::TYPE_WEBSITE,
            'provider' => $provider,
            'name' => $name,
            'external_id' => $externalId,
            'source_url' => $url,
            'feed_url' => null,
            'language' => 'en',
            'topics' => $topics,
            'keywords' => $keywords,
            'metadata' => ['scope' => 'official or institutional heat evidence'],
            'weight' => $weight,
            'is_global' => false,
            'is_active' => true,
        ];
    }
};
