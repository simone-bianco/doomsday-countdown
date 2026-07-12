<?php

declare(strict_types=1);

use App\Enums\VisualizationType;

return new class
{
    /** @return array<string, string> */
    public function sources(): array
    {
        return [
            'ilo_index' => 'https://www.ilo.org/publications/generative-ai-and-jobs-refined-global-index-occupational-exposure',
            'ilo_index_news' => 'https://www.ilo.org/resource/news/one-four-jobs-risk-being-transformed-genai-new-ilo%E2%80%93nask-global-index-shows',
            'ilo_occupations' => 'https://www.ilo.org/resource/article/how-might-generative-ai-impact-different-occupations',
            'ilo_gender' => 'https://www.ilo.org/resource/news/new-ilo-data-confirm-women-face-higher-workplace-risks-generative-ai-men',
            'ilo_observatory' => 'https://www.ilo.org/topics-and-sectors/observatory-ai-and-work-digital-economy',
            'ilo_skills' => 'https://www.ilo.org/topics-and-sectors/skills-and-lifelong-learning/skills-artificial-intelligence-and-digitalization',
            'ilo_governance' => 'https://www.ilo.org/resource/article/governing-ai-world-work-review-global-ethics-guidelines',
            'ilo_future_work' => 'https://www.ilo.org/resource/article/rethinking-ai%E2%80%99s-impact-future-work',
            'ilo_safety' => 'https://www.ilo.org/resource/news/ai-and-digitalization-are-transforming-safety-and-health-work',
            'eurostat_2025' => 'https://ec.europa.eu/eurostat/web/products-eurostat-news/w/ddn-20251211-2',
            'eurostat_2024' => 'https://ec.europa.eu/eurostat/web/products-eurostat-news/w/ddn-20250123-3',
            'eurostat_dataset' => 'https://ec.europa.eu/eurostat/databrowser/view/isoc_eb_ai/default/table?lang=en',
            'oecd_outlook' => 'https://www.oecd.org/en/publications/oecd-employment-outlook-2023_08785bba-en.html',
            'oecd_ai_jobs' => 'https://www.oecd.org/en/publications/oecd-employment-outlook-2023_08785bba-en/full-report/artificial-intelligence-and-jobs-no-signs-of-slowing-labour-demand-yet_5aebe670.html',
            'oecd_ai_wips' => 'https://www.oecd.org/en/about/programmes/ai-in-work-innovation-productivity-and-skills.html',
            'oecd_ai_work' => 'https://www.oecd.org/en/topics/ai-and-work.html',
            'oecd_conference' => 'https://www.oecd.org/en/events/2026/03/2026-international-conference-on-ai-in-work-innovation-productivity-and-skills.html',
            'eu_employment' => 'https://employment-social-affairs.ec.europa.eu/future-employment-impact-artificial-intelligence-and-emerging-digital-technologies-euro_en',
            'eu_ai_skills' => 'https://digital-strategy.ec.europa.eu/en/policies/ai-talent-skills-and-literacy',
            'pact_skills' => 'https://pact-for-skills.ec.europa.eu/index_en',
            'digital_skills_ai' => 'https://digital-skills-jobs.europa.eu/en/artificial-intelligence',
            'slovakia_skills' => 'https://digital-skills-jobs.europa.eu/en/latest/news/ai-changing-workplaces-slovakia',
            'arisa_academy' => 'https://digital-skills-jobs.europa.eu/en/latest/events/empowering-educators-using-arisa-academy-ai-skills-development',
            'ai_pioneers' => 'https://digital-skills-jobs.europa.eu/en/impact-shapers/good-practices/initiative-pioneers-artificial-intelligence',
            'microsoft_training' => 'https://digital-skills-jobs.europa.eu/en/latest/news/free-training-and-certification-artificial-intelligence-ai-skills-microsoft',
            'ai_literacy' => 'https://digital-skills-jobs.europa.eu/en/latest/opinions/ai-literacy-work-bridging-skills-policy-and-practice-europes-digital-transition',
            'automotive_skills' => 'https://pact-for-skills.ec.europa.eu/about/regional-skills-partnerships/regional-skills-partnership-automotive-regions-twin-transition_en',
            'eu_ai_act' => 'https://digital-strategy.ec.europa.eu/en/policies/regulatory-framework-ai',
            'eu_digital_decade' => 'https://digital-strategy.ec.europa.eu/en/library/digital-decade-policy-programme-europes-vision-connectivity-and-innovation',
            'eibis_2025' => 'https://www.eib.org/en/press/all/2025-383-european-firms-show-resilience-invest-in-green-transition-and-match-us-companies-in-adopting-ai-technologies-new-eib-survey-shows',
            'oecd_ai_trajectories_2030' => 'https://www.oecd.org/en/publications/exploring-possible-ai-trajectories-through-2030_cb41117a-en.html',
            'cedefop_ai_2035' => 'https://www.cedefop.europa.eu/files/9201_en.pdf',
            'bls_ai_2033' => 'https://www.bls.gov/opub/mlr/2025/article/incorporating-ai-impacts-in-bls-employment-projections.htm',
        ];
    }

    /** @return array{en: string, it: string, fr: string, de: string, es: string, nl: string, sv: string, pl: string} */
    public function t(string $en, string $it, string $fr, string $de, string $es, string $nl, string $sv, string $pl): array
    {
        return compact('en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl');
    }

    /**
     * @param  array<int, string>  $en
     * @param  array<int, string>  $it
     * @param  array<int, string>  $fr
     * @param  array<int, string>  $de
     * @param  array<int, string>  $es
     * @param  array<int, string>  $nl
     * @param  array<int, string>  $sv
     * @param  array<int, string>  $pl
     * @return array{en: array<int, string>, it: array<int, string>, fr: array<int, string>, de: array<int, string>, es: array<int, string>, nl: array<int, string>, sv: array<int, string>, pl: array<int, string>}
     */
    public function tl(array $en, array $it, array $fr, array $de, array $es, array $nl, array $sv, array $pl): array
    {
        return compact('en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl');
    }

    /**
     * @param  array<string, string>  $title
     * @param  array<string, string>  $description
     * @param  array<string, string>  $reasoning
     * @param  array<int, string>  $labels
     * @param  array<int, int|float|array<string, mixed>>  $series
     * @param  array<int, string>  $sources
     * @return array<string, mixed>
     */
    public function chartVisualization(
        string $key,
        VisualizationType $type,
        array $title,
        array $description,
        array $reasoning,
        array $labels,
        array $series,
        string $xLabel,
        string $xType,
        string $yLabel,
        string $yUnit,
        string $yFormat,
        array $sources,
        int $sortOrder,
    ): array {
        return [
            'key' => $key,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'sources' => array_values(array_unique($sources)),
            'reasoning' => $reasoning,
            'payload' => [
                'labels' => $labels,
                'series' => $series,
                'axes' => [
                    'x' => ['label' => $xLabel, 'type' => $xType],
                    'y' => ['label' => $yLabel, 'unit' => $yUnit, 'format' => $yFormat],
                ],
            ],
            'schema_version' => 2,
            'sort_order' => $sortOrder,
        ];
    }

    /**
     * @param  array<string, string>  $title
     * @param  array<string, string>  $description
     * @param  array<string, string>  $reasoning
     * @param  array<int, array<string, mixed>>  $items
     * @param  array<int, string>  $sources
     * @return array<string, mixed>
     */
    public function kpiVisualization(
        string $key,
        array $title,
        array $description,
        array $reasoning,
        array $items,
        array $sources,
        int $sortOrder,
    ): array {
        return [
            'key' => $key,
            'type' => VisualizationType::Kpi,
            'title' => $title,
            'description' => $description,
            'sources' => array_values(array_unique($sources)),
            'reasoning' => $reasoning,
            'payload' => ['items' => $items],
            'schema_version' => 1,
            'sort_order' => $sortOrder,
        ];
    }
};
