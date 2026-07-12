<?php

declare(strict_types=1);

use App\Enums\VisualizationType;

return new class
{
    public const SLUG = 'europe-war-countdown';

    public const IMAGE_PATH = 'images/doomsday/europe_war_countdown.png';

    /** @return list<string> */
    public function locales(): array
    {
        return ['en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl'];
    }

    /** @return array<string, string> */
    public function sources(): array
    {
        return [
            'nato_expenditure_2025_pdf' => 'https://www.nato.int/content/dam/nato/webready/documents/finance/def-exp-2025-en.pdf',
            'nato_expenditure_2025' => 'https://www.nato.int/en/news-and-events/articles/news/2025/08/28/defence-expenditure-of-nato-countries-2014-2025',
            'nato_hague_declaration' => 'https://www.nato.int/en/about-us/official-texts-and-resources/official-texts/2025/06/25/the-hague-summit-declaration',
            'nato_hague_news' => 'https://www.nato.int/en/news-and-events/articles/news/2025/06/27/nato-concludes-historic-summit-in-the-hague',
            'nato_annual_report_2025' => 'https://www.nato.int/en/news-and-events/articles/news/2026/03/26/nato-secretary-generals-annual-report-shows-significant-increase-in-defence-investment-from-europe-and-canada',
            'nato_five_year_assessment_2025' => 'https://www.nato.int/en/news-and-events/events/transcripts/2025/06/10/building-a-better-nato',
            'nato_2029_assessment_2026' => 'https://www.nato.int/en/news-and-events/events/transcripts/2026/06/18/press-conference-following-the-meetings-of-nato-ministers-of-defence',
            'nato_ankara_declaration_2026' => 'https://www.nato.int/en/about-us/official-texts-and-resources/official-texts/2026/07/08/the-ankara-summit-declaration',
            'ddis_russia_threat_2025' => 'https://www.fe-ddis.dk/globalassets/fe/dokumenter/2025/trusselsvurderinger/-20250209_opdateret_vurdering_af_truslen_fra_rusland_mod--.pdf',
            'eu_readiness_roadmap_2030' => 'https://commission.europa.eu/document/download/56143695-ed00-4a70-a1de-c97e0b05ff07_en',
            'eda_defence_data_pdf' => 'https://eda.europa.eu/docs/default-source/brochures/2025-eda_defencedata_web.pdf',
            'eda_defence_data_portal' => 'https://eda.europa.eu/publications-and-data/defence-data',
            'eda_defence_data_report' => 'https://eda.europa.eu/publications-and-data/thematic-policy-reports/eda-defence-data-2024-2025',
            'eda_card' => 'https://eda.europa.eu/news-and-events/news/2024/11/19/2024-defence-review-paves-way-for-joint-military-projects',
            'white_paper_pdf' => 'https://commission.europa.eu/document/download/e6d5db69-e0ab-4bec-9dc0-3867b4373019_en',
            'white_paper' => 'https://defence-industry-space.ec.europa.eu/eu-defence-industry/white-paper-european-defence-readiness-2030_en',
            'future_defence' => 'https://commission.europa.eu/topics/defence/future-european-defence_en',
            'safe_council' => 'https://www.consilium.europa.eu/en/press/press-releases/2025/05/27/safe-council-adopts-150-billion-boost-for-joint-procurement-on-european-security-and-defence/',
            'safe_policy' => 'https://www.consilium.europa.eu/en/policies/safe/',
            'asap' => 'https://defence-industry-space.ec.europa.eu/eu-defence-industry/asap-boosting-defence-production_en',
            'edf' => 'https://defence-industry-space.ec.europa.eu/eu-defence-industry/european-defence-fund-edf-official-webpage-european-commission_en',
            'pesco' => 'https://www.consilium.europa.eu/en/policies/pesco/',
            'pesco_projects' => 'https://www.consilium.europa.eu/en/press/press-releases/2025/05/27/eu-defence-readiness-council-launches-6th-wave-of-new-pesco-projects/',
            'edip' => 'https://defence-industry-space.ec.europa.eu/eu-defence-industry/edip-forging-europes-defence_en',
            'defence_simplification' => 'https://commission.europa.eu/news-and-media/news/new-simplification-proposal-will-speed-defence-investments-eu-2025-06-17_en',
            'military_mobility' => 'https://commission.europa.eu/news-and-media/news/commission-takes-steps-modernise-european-defence-and-improve-military-mobility-2025-11-19_en',
            'edip_projects_2026' => 'https://defence-industry-space.ec.europa.eu/commission-proposes-five-joint-defence-projects-strengthen-europes-industrial-capabilities-2026-07-03_en',
        ];
    }

    /** @return array{en: string, it: string, fr: string, de: string, es: string, nl: string, sv: string, pl: string} */
    public function t(string $en, string $it, string $fr, string $de, string $es, string $nl, string $sv, string $pl): array
    {
        return compact('en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl');
    }

    /**
     * @param  list<string>  $en
     * @param  list<string>  $it
     * @param  list<string>  $fr
     * @param  list<string>  $de
     * @param  list<string>  $es
     * @param  list<string>  $nl
     * @param  list<string>  $sv
     * @param  list<string>  $pl
     * @return array{en: list<string>, it: list<string>, fr: list<string>, de: list<string>, es: list<string>, nl: list<string>, sv: list<string>, pl: list<string>}
     */
    public function tl(array $en, array $it, array $fr, array $de, array $es, array $nl, array $sv, array $pl): array
    {
        return compact('en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl');
    }

    /**
     * @param  array<string, string>  $title
     * @param  array<string, string>  $description
     * @param  array<string, string>  $reasoning
     * @param  list<string>  $labels
     * @param  list<int|float|array<string, mixed>>  $series
     * @param  list<string>  $sources
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
};
