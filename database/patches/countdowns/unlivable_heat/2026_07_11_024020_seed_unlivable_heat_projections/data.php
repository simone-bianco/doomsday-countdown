<?php

declare(strict_types=1);

use App\Enums\ProjectionType;
use Carbon\CarbonImmutable;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<int, array<string, mixed>> */
    public function projections(): array
    {
        $sources = $this->shared->sources();
        $commonSources = [
            $sources['wmo_2025'],
            $sources['wmo_decadal_2026'],
            $sources['ipcc_wg1_ch4'],
            $sources['ipcc_wg1_ch12'],
            $sources['ipcc_wg2_spm'],
            $sources['ipcc_wg2_cities'],
            $sources['nws_heat_index'],
            $sources['raymond_wet_bulb'],
            $sources['vecellio_wet_bulb'],
            $sources['un_urbanization_2025'],
            $sources['who_hhap_guidance'],
            $sources['wmo_early_warnings'],
        ];
        $common = [
            'metric' => 'editorial heat-risk planning horizon',
            'observed_year' => 2025,
            'observed_value_celsius' => 1.44,
            'reference_period' => '1850-1900',
            'classification' => 'editorial target date anchored to assessed climate windows; not a threshold-crossing forecast',
            'assessed_at' => '2026-07-11',
            'nature' => 'editorial scenario anchor; not an official event forecast',
            'probability_role' => 'legacy display field only; scenario dates are not probabilistic event forecasts',
            'physiology_references' => [
                'heat_index' => [
                    'nws_danger_starts_fahrenheit' => 103,
                    'nws_danger_starts_celsius' => 39.4,
                    'ipcc_assessed_threshold_celsius' => 41.0,
                ],
                'wet_bulb' => [
                    'theoretical_upper_limit_celsius' => 35.0,
                    'experimental_humid_mean_celsius' => 30.55,
                    'experimental_population' => 'young healthy adults at modest metabolic rate',
                ],
                'limit' => 'Heat index, wet-bulb temperature, UTCI and air temperature are not interchangeable. No single value supplies a global unlivable date; risk varies with exposure duration, sun, wind, health, acclimatization, work, housing and access to cooling.',
            ],
            'sources' => $commonSources,
        ];

        return [
            [
                'type' => ProjectionType::Optimistic,
                'target_date' => CarbonImmutable::parse('2100-12-31 23:59:59', 'UTC'),
                'title' => $this->shared->text('projection.optimistic.title'),
                'summary' => $this->shared->text('projection.optimistic.summary'),
                'confidence_score' => 50,
                'probability_score' => 33,
                'trend' => 'peaking-then-easing',
                'methodology' => array_merge($common, [
                    'scenario' => 'SSP1-1.9',
                    'scenario_period' => '2081-2100',
                    'endpoint_celsius' => 1.4,
                    'target_year' => 2100,
                    'target_basis' => 'end of the IPCC long-term assessment window after a low-emissions mid-century overshoot',
                    'drivers' => ['strong mitigation', 'expanded heat-health adaptation', 'early-warning and equitable urban cooling capacity'],
                    'milestones' => [
                        'IPCC central warming estimate: 1.5°C in 2021-2040, 1.6°C in 2041-2060 and 1.4°C in 2081-2100 under SSP1-1.9.',
                        'IPCC finds mitigation strongly limits the late-century frequency of days above dangerous Heat Index thresholds compared with SSP5-8.5.',
                        'WHO heat-health action plans and WMO early warnings are effective adaptation measures but do not eliminate residual risk.',
                        'The date is a late-century resilience review boundary, not a claim that heat becomes safe or unlivable in 2100.',
                    ],
                    'reasoning' => '2100 is selected as the optimistic boundary because SSP1-1.9 peaks around mid-century and declines to a 1.4°C late-century central estimate. Strong mitigation plus sustained adaptation can defer widespread intolerable conditions, while local lethal heat remains possible before and after this date.',
                    'limits' => [
                        'SSP1-1.9 is a scenario, not a forecast of policy delivery.',
                        'The 1.4°C value is a 2081-2100 mean, not a value for 31 December 2100.',
                        'Adaptation effectiveness depends on governance, energy, housing, health systems and equitable cooling access.',
                    ],
                    'stop_conditions' => [
                        'Do not describe 2100 as a physiological threshold crossing or guarantee of safety.',
                        'Reassess if a newer IPCC assessment changes the long-term window or SSP1-1.9 central estimate materially.',
                        'Do not localize this date to a city without local humid-heat, demographic and adaptation evidence.',
                    ],
                    'score_method' => 'editorial equal-weight prior; not an IPCC probability',
                ]),
                'sort_order' => 1,
            ],
            [
                'type' => ProjectionType::Neutral,
                'target_date' => CarbonImmutable::parse('2050-12-31 23:59:59', 'UTC'),
                'title' => $this->shared->text('projection.neutral.title'),
                'summary' => $this->shared->text('projection.neutral.summary'),
                'confidence_score' => 50,
                'probability_score' => 34,
                'trend' => 'rising',
                'methodology' => array_merge($common, [
                    'scenario' => 'SSP2-4.5',
                    'scenario_period' => '2041-2060',
                    'endpoint_celsius' => 2.0,
                    'target_year' => 2050,
                    'target_basis' => 'midpoint of the IPCC mid-term assessment window and the UN urbanization projection endpoint',
                    'drivers' => ['SSP2-4.5 mid-term warming', 'continued urban population growth', 'uneven adaptation and cooling access'],
                    'milestones' => [
                        'IPCC central warming estimate: 2.0°C for 2041-2060 under SSP2-4.5; crossing 2°C in this window is more likely than not.',
                        'UN urbanization projections extend to 2050, when around two-thirds of the global population is expected to live in urban areas.',
                        'IPCC assesses that heat plus urban-growth warming could affect half of the future urban population.',
                        'WHO heat-health plans, warning systems, health-system resilience and exposure reduction can moderate but not remove risk.',
                    ],
                    'reasoning' => '2050 remains the main timer because it is the representative midpoint of the IPCC 2041-2060 window and a major urban planning horizon. It captures the interaction of approximately 2°C central warming under SSP2-4.5, expanding urban exposure and uneven adaptation.',
                    'limits' => [
                        'The 2.0°C value is a 20-year global mean and does not describe any city or individual heatwave.',
                        'Urban growth can amplify or reduce exposure depending on land cover, design, housing and services.',
                        'Heat mortality and physiological limits vary by population, duration and adaptation.',
                    ],
                    'stop_conditions' => [
                        'Do not present 2050 as the day Earth becomes uninhabitable.',
                        'Reassess if newer official urbanization projections or IPCC mid-term estimates materially change the milestone.',
                        'Do not infer a wet-bulb or Heat Index exceedance date from global mean warming alone.',
                    ],
                    'score_method' => 'editorial equal-weight prior; not an IPCC probability',
                ]),
                'sort_order' => 2,
            ],
            [
                'type' => ProjectionType::Pessimistic,
                'target_date' => CarbonImmutable::parse('2040-12-31 23:59:59', 'UTC'),
                'title' => $this->shared->text('projection.pessimistic.title'),
                'summary' => $this->shared->text('projection.pessimistic.summary'),
                'confidence_score' => 50,
                'probability_score' => 33,
                'trend' => 'rising-faster',
                'methodology' => array_merge($common, [
                    'scenario' => 'SSP5-8.5',
                    'scenario_period' => '2021-2040',
                    'endpoint_celsius' => 1.6,
                    'target_year' => 2040,
                    'target_basis' => 'end of the IPCC near-term assessment window under very high emissions and insufficient adaptation',
                    'drivers' => ['persistent near-record warmth', 'very high emissions', 'unresolved exposure and protection gaps'],
                    'milestones' => [
                        'WMO gives a 75% chance that the 2026-2030 five-year mean exceeds 1.5°C, while distinguishing temporary exceedance from long-term warming.',
                        'IPCC central warming estimate: 1.6°C in 2021-2040 under SSP5-8.5, with 1.5°C very likely to be exceeded in the near term.',
                        'Observed humid-heat extremes have already briefly reached the theoretical 35°C wet-bulb reference in a few locations; experimental uncompensable limits can be lower.',
                        'IPCC finds near-term outcomes depend strongly on vulnerability, exposure and adaptation, including urbanization in exposed areas.',
                    ],
                    'reasoning' => '2040 is selected as an early stress horizon because it closes the IPCC near-term window after WMO projects persistent near-record warmth through 2030. Under high emissions and unresolved protection gaps, dangerous local heat can become seasonally disruptive well before mid-century.',
                    'limits' => [
                        'Brief observed wet-bulb exceedances are localized and do not establish a global onset year.',
                        'The 1.6°C value is a 2021-2040 global mean, not an annual 2040 forecast.',
                        'Near-term risk differences are driven heavily by exposure and adaptation, not emissions scenario alone.',
                    ],
                    'stop_conditions' => [
                        'Do not describe 2040 as a universal survivability deadline.',
                        'Reassess if WMO decadal predictions or newer IPCC near-term estimates materially change the assessed window.',
                        'Do not use the 35°C theoretical wet-bulb reference as a universal human threshold; empirical limits are lower and environment-dependent.',
                    ],
                    'score_method' => 'editorial equal-weight prior; not an IPCC probability',
                ]),
                'sort_order' => 3,
            ],
        ];
    }
};
