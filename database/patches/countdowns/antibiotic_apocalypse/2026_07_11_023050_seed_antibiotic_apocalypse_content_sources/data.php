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
        return ['antimicrobial resistance', 'antibiotic resistance', 'AMR', 'drug-resistant infection', 'antibiotic consumption', 'stewardship', 'EARS-Net', 'ESAC-Net', 'GLASS', 'antibacterial pipeline'];
    }

    /** @return array<int, array<string, mixed>> */
    public function contentSources(): array
    {
        $topics = ['antimicrobial-resistance', 'antibiotics', 'public-health', 'one-health'];
        $keywords = ['antimicrobial resistance', 'antibiotic resistance', 'AMR', 'resistant infection', 'stewardship'];

        return [
            $this->website('who', 'WHO Global antibiotic resistance surveillance report 2025', 'who-amr-report-2025', $this->shared->sources()['who_report_2025'], 125, $topics, $keywords),
            $this->website('who', 'WHO Global Antimicrobial Resistance and Use Surveillance System', 'who-glass', $this->shared->sources()['who_glass'], 125, $topics, $keywords),
            $this->website('who', 'WHO antimicrobial resistance fact sheet', 'who-amr-fact-sheet', $this->shared->sources()['who_amr_fact_sheet'], 120, $topics, $keywords),
            $this->website('who', 'WHO antibacterial pipeline report 2025', 'who-antibacterial-pipeline-2025', $this->shared->sources()['who_pipeline_report_2025'], 115, $topics, $keywords),
            $this->website('ecdc', 'ECDC antimicrobial resistance', 'ecdc-amr-topic', $this->shared->sources()['ecdc_amr'], 120, $topics, $keywords),
            $this->website('ecdc', 'EARS-Net annual epidemiological report 2024', 'ecdc-ears-net-2024', $this->shared->sources()['ecdc_ears_2024'], 125, $topics, $keywords),
            $this->website('ecdc', 'ESAC-Net annual epidemiological report 2024', 'ecdc-esac-net-2024', $this->shared->sources()['ecdc_esac_2024'], 125, $topics, $keywords),
            $this->website('ema', 'EMA antimicrobial resistance overview', 'ema-amr-overview', $this->shared->sources()['ema_amr'], 105, $topics, $keywords),
            $this->website('oecd', 'OECD One Health framework to fight AMR', 'oecd-one-health-amr', $this->shared->sources()['oecd_one_health'], 110, $topics, $keywords),
            $this->website('glg-amr', 'Global Leaders Group on Antimicrobial Resistance', 'global-leaders-group-amr', $this->shared->sources()['global_leaders_group'], 95, $topics, $keywords),
            $this->website('carb-x', 'CARB-X antibacterial innovation portfolio', 'carb-x-home', $this->shared->sources()['carb_x'], 95, $topics, $keywords),
            $this->website('gardp', 'Global Antibiotic Research and Development Partnership', 'gardp-home', $this->shared->sources()['gardp'], 95, $topics, $keywords),
        ];
    }

    /**
     * @param  array<int, string>  $topics
     * @param  array<int, string>  $keywords
     * @return array<string, mixed>
     */
    private function website(string $provider, string $name, string $externalId, string $url, int $weight, array $topics, array $keywords): array
    {
        return [
            'type' => ContentSource::TYPE_WEBSITE,
            'provider' => $provider,
            'name' => $name,
            'external_id' => $externalId,
            'source_url' => $url,
            'language' => 'en',
            'topics' => $topics,
            'keywords' => $keywords,
            'metadata' => ['scope' => 'official or institutional AMR source'],
            'weight' => $weight,
            'is_global' => false,
            'is_active' => true,
        ];
    }
};
