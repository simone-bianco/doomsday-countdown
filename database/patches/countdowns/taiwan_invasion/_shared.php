<?php

declare(strict_types=1);

use App\Enums\VisualizationType;

return new class
{
    /** @return array<string, string> */
    public function sources(): array
    {
        return [
            'dod' => 'https://media.defense.gov/2025/Dec/23/2003849070/-1/-1/1/ANNUAL-REPORT-TO-CONGRESS-MILITARY-AND-SECURITY-DEVELOPMENTS-INVOLVING-THE-PEOPLES-REPUBLIC-OF-CHINA-2025.PDF',
            'reuters_status_quo' => 'https://www.reuters.com/world/china/chinas-actions-risk-creation-new-status-quo-taiwan-official-says-2026-07-08/',
            'reuters_preparedness' => 'https://www.reuters.com/business/aerospace-defense/taiwans-preparations-face-chinese-attack-are-not-provocation-senior-official-2026-07-07/',
            'reuters_naval' => 'https://www.reuters.com/world/china/taiwan-says-it-is-tracking-upward-trend-chinese-naval-movements-2026-07-06/',
            'reuters_coast_guard' => 'https://www.reuters.com/world/china/china-launches-coast-guard-patrol-east-taiwan-despite-international-pushback-2026-07-04/',
            'reuters_drill' => 'https://www.reuters.com/world/china/inside-taiwans-nightmare-scenario-chinese-blockade-earthquake-sabotage-invasion-2026-07-03/',
            'reuters_drone' => 'https://www.reuters.com/world/china/taiwan-needs-hornets-nest-drones-deter-conflict-us-diplomat-says-2026-07-02/',
            'csis_trade' => 'https://www.csis.org/analysis/disruptions-trade-taiwan-strait-would-severely-impact-chinas-economy',
            'rhodium_disruptions' => 'https://rhg.com/research/taiwan-economic-disruptions/',
            'trade_semiconductors' => 'https://www.trade.gov/country-commercial-guides/taiwan-semiconductors-including-chip-design-ai',
            'energy_resilience' => 'https://www.atlanticcouncil.org/blogs/energysource/the-iran-war-tests-taiwans-energy-resilience/',
            'whole_society' => 'https://english.president.gov.tw/Page/669',
            'public_safety_guide' => 'https://adma.mnd.gov.tw/files/web/191/file_up/100004/66/InCaseofCrisisTaiwanPublicSafetyGuide.pdf',
            'urban_resilience' => 'https://adma.mnd.gov.tw/uniten/100005/8074',
            'kuma' => 'https://kuma-academy.org/about?lang=en',
            'g7_statement' => 'https://www.elysee.fr/en/G7evian/2026/06/17/g7-leaders-statement-on-geopolitical-issues',
            'quad_statement' => 'https://www.state.gov/releases/office-of-the-spokesperson/2026/05/joint-statement-from-the-quad-foreign-ministers-meeting-in-new-delhi',
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

    /** @param array<string, string> $title @param array<string, string> $description @param array<int, string> $labels @param array<int, int|float> $series @param array<int, string> $sources @return array<string, mixed> */
    public function lineVisualization(string $key, array $title, array $description, array $labels, array $series, string $unit, array $sources, int $sortOrder): array
    {
        return [
            'key' => $key,
            'type' => VisualizationType::Line,
            'title' => $title,
            'description' => $description,
            'payload' => [
                'labels' => $labels,
                'series' => $series,
                'unit' => $unit,
                'sources' => $sources,
            ],
            'schema_version' => 1,
            'sort_order' => $sortOrder,
        ];
    }
};
