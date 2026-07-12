<?php

declare(strict_types=1);

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use Carbon\CarbonImmutable;

$shared = require __DIR__.'/../_shared.php';

return new class($shared)
{
    public function __construct(private object $shared) {}

    /** @return array<string, mixed> */
    public function countdown(): array
    {
        $neutralPlanningHorizon = CarbonImmutable::parse('2050-12-31 23:59:59', 'UTC');

        return [
            'slug' => 'unlivable-heat',
            'title' => $this->shared->text('countdown.title'),
            'summary' => $this->shared->text('countdown.summary'),
            'description' => $this->shared->text('countdown.description'),
            'causes' => $this->shared->list('countdown.causes'),
            'consequences' => $this->shared->list('countdown.consequences'),
            'recommended_actions' => $this->shared->list('countdown.actions'),
            'severity' => CountdownSeverity::Severe,
            'status' => CountdownStatus::Active,
            'target_date' => $neutralPlanningHorizon,
            'image_path' => 'images/doomsday/extreme_heat_breakpoint_separate.png',
            'sort_order' => 6,
            'is_published' => true,
        ];
    }
};
