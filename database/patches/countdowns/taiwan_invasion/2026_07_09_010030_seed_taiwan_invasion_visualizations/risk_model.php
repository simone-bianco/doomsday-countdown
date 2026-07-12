<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;

return new class
{
    /** @return array<string, string> */
    public function checkpoints(): array
    {
        return [
            '2026-Q3' => '2026-09-30 23:59:59',
            '2027-Q4' => '2027-12-31 23:59:59',
            '2028-Q2' => '2028-05-20 00:00:00',
            '2030-Q4' => '2030-12-31 23:59:59',
            '2032-Q2' => '2032-05-20 00:00:00',
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function inputs(): array
    {
        return array_map(function (array $definition): array {
            $checkpoints = [];

            foreach ($this->checkpoints() as $label => $date) {
                $checkpoint = CarbonImmutable::parse($date, 'UTC');
                $normalized = $this->normalizedValue($definition, $checkpoint);
                $directionalRisk = $definition['direction'] === 'risk_down'
                    ? 100 - $normalized
                    : $normalized;
                $adjustments = [
                    'Pessimistic' => $definition['scenario_spread'],
                    'Neutral' => 0.0,
                    'Optimistic' => -$definition['scenario_spread'],
                ];
                $calculated = [];

                foreach ($adjustments as $scenario => $adjustment) {
                    $calculated[$scenario] = round(
                        $definition['weight'] * $this->clamp($directionalRisk + $adjustment),
                        4,
                    );
                }

                $checkpoints[$label] = [
                    'normalized_value' => round($normalized, 4),
                    'directional_risk_value' => round($directionalRisk, 4),
                    'scenario_adjustments' => $adjustments,
                    'calculated_values' => $calculated,
                ];
            }

            return array_merge($definition, ['checkpoints' => $checkpoints]);
        }, $this->definitions());
    }

    /** @return array{Pessimistic: array<int, float>, Neutral: array<int, float>, Optimistic: array<int, float>} */
    public function scores(): array
    {
        $scores = [
            'Pessimistic' => [],
            'Neutral' => [],
            'Optimistic' => [],
        ];

        foreach (array_keys($this->checkpoints()) as $label) {
            foreach (array_keys($scores) as $scenario) {
                $sum = array_sum(array_map(
                    static fn (array $input): float => (float) $input['checkpoints'][$label]['calculated_values'][$scenario],
                    $this->inputs(),
                ));
                $scores[$scenario][] = round($this->clamp($sum), 1);
            }
        }

        return $scores;
    }

    /** @return array<int, string> */
    public function sources(): array
    {
        return array_values(array_unique(array_column($this->definitions(), 'source_url')));
    }

    public function formula(): string
    {
        return 'score(scenario, checkpoint) = round(clamp(sum(weight_i * clamp(directional_normalized_i(checkpoint) + scenario_adjustment_i, 0, 100)), 0, 100), 1)';
    }

    /** @return array<int, array<string, mixed>> */
    private function definitions(): array
    {
        return [
            [
                'signal_key' => 'pla_2027_capability_milestone',
                'date' => '2027-12-31',
                'date_type' => 'effective milestone',
                'source_url' => 'https://media.defense.gov/2025/Dec/23/2003849070/-1/-1/1/ANNUAL-REPORT-TO-CONGRESS-MILITARY-AND-SECURITY-DEVELOPMENTS-INVOLVING-THE-PEOPLES-REPUBLIC-OF-CHINA-2025.PDF',
                'source_fact' => 'The PLA continues progress toward end-2027 goals for a strategic decisive victory over Taiwan; this is a capability/readiness milestone, not an order date.',
                'source_value' => '2027-12-31',
                'normalization' => 'Temporal proximity: max(20, 100 - 18 * absolute years from 2027-12-31).',
                'direction' => 'risk_up',
                'weight' => 0.18,
                'weight_rationale' => 'Capability readiness is material but cannot dominate political intent, operational friction or deterrence.',
                'scenario_spread' => 9.0,
                'scenario_adjustment_rule' => 'Pessimistic +9, Neutral +0, Optimistic -9 index points before weighting.',
            ],
            [
                'signal_key' => 'pla_adiz_activity_2024',
                'date' => '2024-11 (late November)',
                'date_type' => 'observed annual activity',
                'source_url' => 'https://media.defense.gov/2025/Dec/23/2003849070/-1/-1/1/ANNUAL-REPORT-TO-CONGRESS-MILITARY-AND-SECURITY-DEVELOPMENTS-INVOLVING-THE-PEOPLES-REPUBLIC-OF-CHINA-2025.PDF',
                'source_fact' => 'Approximately 2,771 PLA aircraft were detected in Taiwan’s ADIZ in 2024, up from 1,703 in 2023.',
                'source_value' => 2771,
                'normalization' => 'Base min(100, 2771 / 3000 * 100), then subtract 4 points per elapsed year after 2026-07-01 with a floor of 55.',
                'direction' => 'risk_up',
                'weight' => 0.18,
                'weight_rationale' => 'Persistent air pressure is a repeated observed coercion signal, tempered for source age.',
                'scenario_spread' => 7.0,
                'scenario_adjustment_rule' => 'Pessimistic +7, Neutral +0, Optimistic -7 index points before weighting.',
            ],
            [
                'signal_key' => 'naval_presence_2026',
                'date' => '2026-07-03',
                'date_type' => 'observed presence',
                'source_url' => 'https://www.reuters.com/world/china/taiwan-says-it-is-tracking-upward-trend-chinese-naval-movements-2026-07-06/',
                'source_fact' => 'Taiwan tracked more than 110 Chinese military and coast guard ships along the First Island Chain.',
                'source_value' => 110,
                'normalization' => 'Base min(100, 110 / 120 * 100), then subtract 6 points per elapsed year after 2026-07-03 with a floor of 50.',
                'direction' => 'risk_up',
                'weight' => 0.14,
                'weight_rationale' => 'Maritime presence is directly relevant to coercion and blockade capacity but is a snapshot rather than a continuous series.',
                'scenario_spread' => 7.0,
                'scenario_adjustment_rule' => 'Pessimistic +7, Neutral +0, Optimistic -7 index points before weighting.',
            ],
            [
                'signal_key' => 'blockade_quarantine_capability',
                'date' => '2026-02-24',
                'date_type' => 'expert assessment',
                'source_url' => 'https://www.csis.org/analysis/assessing-xis-unprecedented-purges-chinas-military-key-developments-and-potential',
                'source_fact' => 'PLA purges complicate a large campaign, while blockade or quarantine options remain easier to execute than amphibious invasion.',
                'source_value' => 'high coercive-path capability',
                'normalization' => 'Categorical rubric: high=80, medium=50, low=20; held constant because no comparable time series is cited.',
                'direction' => 'risk_up',
                'weight' => 0.14,
                'weight_rationale' => 'The index covers coercion and crisis pressure, so non-amphibious pathways require separate weight.',
                'scenario_spread' => 8.0,
                'scenario_adjustment_rule' => 'Pessimistic +8, Neutral +0, Optimistic -8 index points before weighting.',
            ],
            [
                'signal_key' => 'taiwan_2028_political_inflection',
                'date' => '2028-05-20',
                'date_type' => 'effective political checkpoint',
                'source_url' => 'https://www.brookings.edu/articles/adapting-us-taiwan-policy-for-a-new-strategic-reality/',
                'source_fact' => 'The 2028 presidential election is a consequential near-term political inflection point, not a deterministic military date.',
                'source_value' => '2028 presidential transition',
                'normalization' => 'Temporal proximity: max(15, 100 - 20 * absolute years from 2028-05-20).',
                'direction' => 'risk_up',
                'weight' => 0.10,
                'weight_rationale' => 'Political transition can affect coercive incentives but receives less weight than observed military activity.',
                'scenario_spread' => 5.0,
                'scenario_adjustment_rule' => 'Pessimistic +5, Neutral +0, Optimistic -5 index points before weighting.',
            ],
            [
                'signal_key' => 'pla_command_disruption',
                'date' => '2026-02-24',
                'date_type' => 'observed leadership disruption',
                'source_url' => 'https://www.csis.org/analysis/assessing-xis-unprecedented-purges-chinas-military-key-developments-and-potential',
                'source_fact' => 'Sixty-eight percent of purged deputy-theater-command officers came from the command track, complicating large-scale joint operations.',
                'source_value' => 68,
                'normalization' => 'Protective disruption strength starts at 68 and falls 6 points per elapsed year after 2026-02-24, with a floor of 30.',
                'direction' => 'risk_down',
                'weight' => 0.12,
                'weight_rationale' => 'Command disruption moderates near-term large-operation readiness but may diminish as positions are refilled.',
                'scenario_spread' => 6.0,
                'scenario_adjustment_rule' => 'Pessimistic +6, Neutral +0, Optimistic -6 risk points after converting protective strength.',
            ],
            [
                'signal_key' => 'taiwan_asymmetric_resilience',
                'date' => '2026-07-02',
                'date_type' => 'announced defense investment',
                'source_url' => 'https://www.reuters.com/world/china/taiwan-needs-hornets-nest-drones-deter-conflict-us-diplomat-says-2026-07-02/',
                'source_fact' => 'Taiwan’s proposed drone special budget is NT$210 billion and supports distributed asymmetric deterrence.',
                'source_value' => 210,
                'normalization' => 'Base min(100, 210 / 250 * 100), then add 3 points per elapsed year after 2026-07-02, capped at 100.',
                'direction' => 'risk_down',
                'weight' => 0.14,
                'weight_rationale' => 'Distributed resilience can raise the cost of coercion, but budget authorization is not immediate deployed capability.',
                'scenario_spread' => 8.0,
                'scenario_adjustment_rule' => 'Pessimistic +8, Neutral +0, Optimistic -8 risk points after converting protective strength.',
            ],
        ];
    }

    /** @param array<string, mixed> $signal */
    private function normalizedValue(array $signal, CarbonImmutable $checkpoint): float
    {
        return match ($signal['signal_key']) {
            'pla_2027_capability_milestone' => max(20, 100 - (18 * $this->absoluteYears($checkpoint, '2027-12-31'))),
            'pla_adiz_activity_2024' => max(55, min(100, (2771 / 3000) * 100) - (4 * $this->elapsedYears($checkpoint, '2026-07-01'))),
            'naval_presence_2026' => max(50, min(100, (110 / 120) * 100) - (6 * $this->elapsedYears($checkpoint, '2026-07-03'))),
            'blockade_quarantine_capability' => 80.0,
            'taiwan_2028_political_inflection' => max(15, 100 - (20 * $this->absoluteYears($checkpoint, '2028-05-20'))),
            'pla_command_disruption' => max(30, 68 - (6 * $this->elapsedYears($checkpoint, '2026-02-24'))),
            'taiwan_asymmetric_resilience' => min(100, 84 + (3 * $this->elapsedYears($checkpoint, '2026-07-02'))),
            default => throw new LogicException('Unknown Taiwan risk signal: '.$signal['signal_key']),
        };
    }

    private function elapsedYears(CarbonImmutable $checkpoint, string $from): float
    {
        $origin = CarbonImmutable::parse($from, 'UTC');

        return max(0, ($checkpoint->getTimestamp() - $origin->getTimestamp()) / 31556952);
    }

    private function absoluteYears(CarbonImmutable $checkpoint, string $anchor): float
    {
        $target = CarbonImmutable::parse($anchor, 'UTC');

        return abs($checkpoint->getTimestamp() - $target->getTimestamp()) / 31556952;
    }

    private function clamp(float $value): float
    {
        return min(100, max(0, $value));
    }
};
