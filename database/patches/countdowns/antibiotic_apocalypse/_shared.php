<?php

declare(strict_types=1);

use App\Enums\VisualizationType;

return new class
{
    /** @return array<string, string> */
    public function sources(): array
    {
        return [
            'who_report_2025' => 'https://www.who.int/publications/i/item/9789240116337',
            'who_resistance_news_2025' => 'https://www.who.int/news/item/13-10-2025-who-warns-of-widespread-resistance-to-common-antibiotics-worldwide',
            'who_amr_fact_sheet' => 'https://www.who.int/news-room/fact-sheets/detail/antimicrobial-resistance',
            'gram_burden_2021' => 'https://doi.org/10.1016/S0140-6736(24)01867-1',
            'who_un_declaration_2024' => 'https://www.who.int/news/item/26-09-2024-world-leaders-commit-to-decisive-action-on-antimicrobial-resistance',
            'un_amr_declaration_2024' => 'https://www.un.org/pga/wp-content/uploads/sites/108/2024/09/FINAL-Text-AMR-to-PGA.pdf',
            'who_gap_2026' => 'https://www.who.int/news/item/25-05-2026-the-world-health-assembly-adopts-updated-global-action-plan-on-antimicrobial-resistance-%282026-2036%29',
            'who_pipeline_2025' => 'https://www.who.int/news/item/02-10-2025-who-releases-new-reports-on-new-tests-and-treatments-in-development-for-bacterial-infections',
            'who_pipeline_report_2025' => 'https://www.who.int/publications/i/item/9789240113091',
            'who_diagnostics_2025' => 'https://www.who.int/publications/i/item/9789240109827',
            'who_pipeline_2024' => 'https://www.who.int/news/item/14-06-2024-who-releases-report-on-state-of-development-of-antibacterials',
            'who_glass' => 'https://www.who.int/initiatives/glass',
            'who_aware' => 'https://www.who.int/publications/i/item/WHO-MHP-HPS-EML-2023.04',
            'who_secure' => 'https://www.who.int/initiatives/secure-expanding-sustainable-access-to-antibiotics',
            'ecdc_ears_2024' => 'https://www.ecdc.europa.eu/en/publications-data/antimicrobial-resistance-eueea-ears-net-annual-epidemiological-report-2024',
            'ecdc_ears_2023' => 'https://www.ecdc.europa.eu/en/publications-data/antimicrobial-resistance-eueea-ears-net-annual-epidemiological-report-2023',
            'ecdc_esac_2024' => 'https://www.ecdc.europa.eu/en/publications-data/antimicrobial-consumption-eueea-esac-net-annual-epidemiological-report-2024',
            'ecdc_esac_2023' => 'https://www.ecdc.europa.eu/en/publications-data/antimicrobial-consumption-eueea-esac-net-annual-epidemiological-report-2023',
            'ecdc_burden' => 'https://www.ecdc.europa.eu/en/publications-data/health-burden-infections-antibiotic-resistant-bacteria-2016-2020',
            'ecdc_amr' => 'https://www.ecdc.europa.eu/en/antimicrobial-resistance',
            'ecdc_consumption' => 'https://www.ecdc.europa.eu/en/antimicrobial-consumption',
            'ecdc_targets' => 'https://www.ecdc.europa.eu/en/news-events/reducing-antimicrobial-resistance-accelerated-efforts-are-needed-meet-eu-targets',
            'ecdc_action_2025' => 'https://www.ecdc.europa.eu/en/news-events/time-act-and-not-react-how-can-european-union-turn-tide-antimicrobial-resistance',
            'ema_amr' => 'https://www.ema.europa.eu/en/human-regulatory-overview/public-health-threats/antimicrobial-resistance',
            'ema_jiacra' => 'https://www.ema.europa.eu/en/news/multi-agency-report-highlights-importance-reducing-antibiotic-use',
            'oecd_one_health' => 'https://www.oecd.org/en/publications/embracing-a-one-health-framework-to-fight-antimicrobial-resistance_ce44c755-en.html',
            'global_leaders_group' => 'https://www.amrleaders.org/',
            'one_health_plan' => 'https://www.fao.org/one-health/resources/publications/joint-plan-of-action/en',
            'carb_x' => 'https://carb-x.org/',
            'gardp' => 'https://gardp.org/',
            'fleming_fund' => 'https://extranet.who.int/sph/fleming-fund',
            'amr_mptf' => 'https://mptf.undp.org/fund/amr00',
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
