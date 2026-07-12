<?php

declare(strict_types=1);

use App\Enums\VisualizationType;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<string, mixed> */
    public function projectionCurveVisualization(): array
    {
        return $this->shared->chartVisualization(
            key: 'projection_curve',
            type: VisualizationType::Line,
            title: $this->shared->text('chart.projection.title'),
            description: $this->shared->text('chart.projection.description'),
            reasoning: $this->shared->text('chart.projection.reasoning'),
            labels: ['2025', '2030', '2050', '2090'],
            series: [
                [
                    'name' => 'SSP1-1.9',
                    'values' => [1.44, 1.5, 1.6, 1.4],
                    'assessment_windows' => ['observed', '2021-2040', '2041-2060', '2081-2100'],
                    'editorial_target_year' => 2100,
                ],
                [
                    'name' => 'SSP2-4.5',
                    'values' => [1.44, 1.5, 2.0, 2.7],
                    'assessment_windows' => ['observed', '2021-2040', '2041-2060', '2081-2100'],
                    'editorial_target_year' => 2050,
                ],
                [
                    'name' => 'SSP5-8.5',
                    'values' => [1.44, 1.6, 2.4, 4.4],
                    'assessment_windows' => ['observed', '2021-2040', '2041-2060', '2081-2100'],
                    'editorial_target_year' => 2040,
                ],
            ],
            xLabel: 'Representative midpoint year of assessed window',
            xType: 'temporal',
            yLabel: 'Global mean air-temperature anomaly vs 1850-1900',
            yUnit: '°C',
            yFormat: 'decimal',
            sources: [
                $this->shared->sources()['wmo_2025'],
                $this->shared->sources()['wmo_decadal_2026'],
                $this->shared->sources()['ipcc_wg1_ch4'],
            ],
            sortOrder: 1,
        );
    }

    /** @return array<int, array<string, mixed>> */
    public function visualizations(): array
    {
        return [
            [
                'key' => 'key_indicators',
                'type' => VisualizationType::Kpi,
                'title' => $this->shared->text('chart.kpi.title'),
                'description' => $this->shared->text('chart.kpi.description'),
                'sources' => [
                    $this->shared->sources()['wmo_2025'],
                    $this->shared->sources()['copernicus_southeast'],
                    $this->shared->sources()['who_heat_deaths'],
                ],
                'reasoning' => $this->shared->text('chart.kpi.reasoning'),
                'payload' => [
                    'items' => [
                        ['label' => '2025 global air-temperature anomaly', 'value' => '+1.44°C'],
                        ['label' => 'Southeastern Europe strong-heat-stress days, summer 2024', 'value' => '66 days'],
                        ['label' => 'Southeastern Europe tropical nights, summer 2024', 'value' => '23 nights'],
                        ['label' => 'WHO European Region heat-related deaths, annual 2000-2019 average', 'value' => '>175,000'],
                    ],
                ],
                'schema_version' => 1,
                'sort_order' => 1,
            ],
            $this->shared->chartVisualization(
                key: 'global_temperature_anomaly',
                type: VisualizationType::Line,
                title: $this->shared->text('chart.anomaly.title'),
                description: $this->shared->text('chart.anomaly.description'),
                reasoning: $this->shared->text('chart.anomaly.reasoning'),
                labels: ['2023', '2024', '2025'],
                series: [1.45, 1.55, 1.44],
                xLabel: 'Year',
                xType: 'temporal',
                yLabel: 'Global mean air-temperature anomaly vs 1850-1900',
                yUnit: '°C',
                yFormat: 'decimal',
                sources: [$this->shared->sources()['wmo_2023'], $this->shared->sources()['wmo_2024'], $this->shared->sources()['wmo_2025']],
                sortOrder: 2,
            ),
            $this->shared->chartVisualization(
                key: 'tropical_night_area_share',
                type: VisualizationType::Area,
                title: $this->shared->text('chart.tropical_share.title'),
                description: $this->shared->text('chart.tropical_share.description'),
                reasoning: $this->shared->text('chart.tropical_share.reasoning'),
                labels: ['1970s', '2010s', '2020s'],
                series: [20, 34, 35],
                xLabel: 'Decade',
                xType: 'ordinal',
                yLabel: 'European area with at least one tropical night',
                yUnit: '%',
                yFormat: 'percent',
                sources: [$this->shared->sources()['copernicus_thermal']],
                sortOrder: 3,
            ),
            $this->shared->chartVisualization(
                key: 'southeast_heat_stress_days',
                type: VisualizationType::Bar,
                title: $this->shared->text('chart.stress_days.title'),
                description: $this->shared->text('chart.stress_days.description'),
                reasoning: $this->shared->text('chart.stress_days.reasoning'),
                labels: ['1991-2020 average', 'Summer 2024'],
                series: [29, 66],
                xLabel: 'Period',
                xType: 'category',
                yLabel: 'Days with UTCI at or above 32°C',
                yUnit: 'days',
                yFormat: 'integer',
                sources: [$this->shared->sources()['copernicus_southeast'], $this->shared->sources()['copernicus_utci']],
                sortOrder: 4,
            ),
            $this->shared->chartVisualization(
                key: 'southeast_tropical_nights',
                type: VisualizationType::Bar,
                title: $this->shared->text('chart.nights.title'),
                description: $this->shared->text('chart.nights.description'),
                reasoning: $this->shared->text('chart.nights.reasoning'),
                labels: ['Long-term average', 'Previous record 2012', 'Summer 2024'],
                series: [8, 16, 23],
                xLabel: 'Reference',
                xType: 'category',
                yLabel: 'Nights with minimum air temperature at or above 20°C',
                yUnit: 'nights',
                yFormat: 'integer',
                sources: [$this->shared->sources()['copernicus_southeast']],
                sortOrder: 5,
            ),
            $this->shared->chartVisualization(
                key: 'eu_cooling_degree_days_2022',
                type: VisualizationType::Bar,
                title: $this->shared->text('chart.cdd.title'),
                description: $this->shared->text('chart.cdd.description'),
                reasoning: $this->shared->text('chart.cdd.reasoning'),
                labels: ['Malta', 'Cyprus', 'Spain', 'Italy', 'Greece'],
                series: [842, 698, 384, 375, 372],
                xLabel: 'Country',
                xType: 'category',
                yLabel: 'Cooling degree days',
                yUnit: 'CDD',
                yFormat: 'integer',
                sources: [$this->shared->sources()['eurostat_cdd_news'], $this->shared->sources()['eurostat_cdd_data']],
                sortOrder: 6,
            ),
        ];
    }
};
