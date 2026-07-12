<?php

declare(strict_types=1);

use App\Enums\VisualizationType;

return new class
{
    /** @return array<string, string> */
    public function sources(): array
    {
        return [
            'cbd_gbf' => 'https://www.cbd.int/gbf',
            'cbd_target_2' => 'https://www.cbd.int/gbf/targets/2',
            'cbd_target_3' => 'https://www.cbd.int/gbf/targets/3',
            'cbd_final_text' => 'https://www.cbd.int/article/cop15-final-text-kunming-montreal-gbf-221222',
            'cbd_national_reports' => 'https://www.cbd.int/reports',
            'cbd_global_review_2026' => 'https://www.cbd.int/article/2026-06-16-how-the-world-measures-progress-for-nature',
            'cbd_cop17_review' => 'https://www.cbd.int/article/COP17Logo-Unveiled',
            'cbd_nbsap_implementation_2025' => 'https://www.cbd.int/doc/c/4fc4/fb7a/aee60317b2bf743207635649/sbi-06-inf-05-en.pdf',
            'protected_planet_report' => 'https://digitalreport.protectedplanet.net/',
            'protected_planet' => 'https://www.protectedplanet.net/en',
            'living_planet_index' => 'https://www.livingplanetindex.org/lpi',
            'living_planet_technical' => 'https://livingplanetindex.org/documents/LPR2024_Technical_Supplement.pdf',
            'living_planet_video' => 'https://www.youtube.com/watch?v=jmvgQ5fBBLg',
            'gbf_review_video' => 'https://www.youtube.com/watch?v=U6oP6Q-hVQI',
            'ecosystem_restoration_video' => 'https://www.youtube.com/watch?v=XhjN8Xux2I4',
            'ramsar_gwo' => 'https://www.global-wetland-outlook.ramsar.org/',
            'ramsar_briefing' => 'https://www.global-wetland-outlook.ramsar.org/s/GWO2025-Briefing-Paper-ENG.pdf',
            'fao_fra_2020' => 'https://www.fao.org/interactive/forest-resources-assessment/2020/en/',
            'fao_fra_2025' => 'https://www.fao.org/newsroom/detail/global-deforestation-slows--but-forests-remain-under-pressure--fao-report-shows/en',
            'fao_aim4nature' => 'https://www.fao.org/newsroom/detail/fao-UK-aim4nature-to-ecosystem-restoration/en',
            'fao_restoration_flagships' => 'https://www.fao.org/newsroom/detail/healthy-nature-for-better-food-systems--un-awards-new-world-restoration-flagships/en',
            'iucn_summary' => 'https://www.iucnredlist.org/resources/summary-statistics',
            'iucn_amphibians' => 'https://nc.iucnredlist.org/redlist/resources/files/1696400756-SOTWA_GAA2_04Oct2023.pdf',
            'iucn_2025_update' => 'https://iucn.org/news/202504/iucn-red-list-update-global-impacts-regional-statuses-and-way-forward',
            'iucn_2025_birds' => 'https://iucn.org/press-release/202510/arctic-seals-threatened-climate-change-birds-decline-globally-iucn-red-list',
            'ipbes_spm' => 'https://files.ipbes.net/ipbes-web-prod-public-files/inline/files/ipbes_global_assessment_report_summary_for_policymakers.pdf',
            'restoration_decade' => 'https://www.decadeonrestoration.org/',
            'gbif' => 'https://www.gbif.org/',
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
};
