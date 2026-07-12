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
            'artificial intelligence',
            'generative ai',
            'automation',
            'augmentation',
            'employment',
            'jobs',
            'labour market',
            'skills',
            'reskilling',
            'workplace',
            'occupational exposure',
            'worker rights',
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function contentSources(): array
    {
        $topics = ['artificial-intelligence', 'employment', 'skills', 'labour-market', 'future-of-work'];
        $keywords = $this->contentSourcePivotKeywords();

        return [
            $this->website('International Labour Organization — GenAI exposure index', 'ilo.org/genai-occupational-exposure-index', $this->shared->sources()['ilo_index'], 'ilo', $topics, $keywords, 130),
            $this->website('ILO Observatory on AI and Work', 'ilo.org/observatory-ai-work', $this->shared->sources()['ilo_observatory'], 'ilo', $topics, $keywords, 125),
            $this->website('ILO Skills for AI and Digitalization', 'ilo.org/skills-ai-digitalization', $this->shared->sources()['ilo_skills'], 'ilo', $topics, $keywords, 118),
            $this->website('OECD AI and Jobs analysis', 'oecd.org/employment-outlook-ai-jobs', $this->shared->sources()['oecd_ai_jobs'], 'oecd', $topics, $keywords, 125),
            $this->website('OECD AI-WIPS programme', 'oecd.org/ai-wips', $this->shared->sources()['oecd_ai_wips'], 'oecd', $topics, $keywords, 115),
            $this->website('OECD AI and Work topic', 'oecd.org/ai-and-work', $this->shared->sources()['oecd_ai_work'], 'oecd', $topics, $keywords, 112),
            $this->website('Eurostat enterprise AI adoption release', 'eurostat/enterprise-ai-adoption-2025', $this->shared->sources()['eurostat_2025'], 'eurostat', $topics, $keywords, 128),
            $this->website('Eurostat enterprise AI dataset', 'eurostat/isoc_eb_ai', $this->shared->sources()['eurostat_dataset'], 'eurostat', $topics, $keywords, 130),
            $this->website('European Commission AI talent and skills', 'ec.europa.eu/ai-talent-skills-literacy', $this->shared->sources()['eu_ai_skills'], 'european_commission', $topics, $keywords, 108),
            $this->website('EU Pact for Skills', 'ec.europa.eu/pact-for-skills', $this->shared->sources()['pact_skills'], 'european_commission', $topics, $keywords, 108),
            $this->website('Digital Skills and Jobs — Artificial Intelligence', 'digital-skills-jobs.europa.eu/artificial-intelligence', $this->shared->sources()['digital_skills_ai'], 'european_commission', $topics, $keywords, 105),
            $this->website('European employment impact analysis', 'ec.europa.eu/future-employment-impact-ai', $this->shared->sources()['eu_employment'], 'european_commission', $topics, $keywords, 104),
            $this->website('EU AI Act implementation timeline', 'ec.europa.eu/ai-act-implementation-timeline', $this->shared->sources()['eu_ai_act'], 'european_commission', $topics, $keywords, 126),
            $this->website('EU Digital Decade 2030 targets', 'ec.europa.eu/digital-decade-2030-targets', $this->shared->sources()['eu_digital_decade'], 'european_commission', $topics, $keywords, 124),
            $this->website('EIB Investment Survey 2025 — AI adoption', 'eib.org/eibis-2025-ai-adoption', $this->shared->sources()['eibis_2025'], 'eib', $topics, $keywords, 120),
            $this->website('OECD possible AI trajectories through 2030', 'oecd.org/ai-trajectories-2030', $this->shared->sources()['oecd_ai_trajectories_2030'], 'oecd', $topics, $keywords, 122),
            $this->website('Cedefop AI and skills forecast through 2035', 'cedefop.europa.eu/ai-skills-2035', $this->shared->sources()['cedefop_ai_2035'], 'cedefop', $topics, $keywords, 124),
            $this->website('BLS AI employment projections through 2033', 'bls.gov/ai-employment-projections-2033', $this->shared->sources()['bls_ai_2033'], 'bls', $topics, $keywords, 116),
        ];
    }

    /**
     * @param  array<int, string>  $topics
     * @param  array<int, string>  $keywords
     * @return array<string, mixed>
     */
    private function website(string $name, string $externalId, string $url, string $provider, array $topics, array $keywords, int $weight): array
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
            'metadata' => ['scope' => 'ai-work-evidence', 'official' => true],
            'weight' => $weight,
            'is_global' => false,
            'is_active' => true,
        ];
    }
};
