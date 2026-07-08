<?php

declare(strict_types=1);

namespace App\Observers\Doomsday;

use App\Models\Countdown;
use App\Models\Projection;
use App\Models\Visualization;
use App\Services\Doomsday\Cache\CountdownCache;

final class VisualizationObserver
{
    public function created(Visualization $visualization): void
    {
        $this->purge($visualization);
    }

    public function updated(Visualization $visualization): void
    {
        $this->purge($visualization);
    }

    public function deleted(Visualization $visualization): void
    {
        $this->purge($visualization);
    }

    public function restored(Visualization $visualization): void
    {
        $this->purge($visualization);
    }

    public function forceDeleted(Visualization $visualization): void
    {
        $this->purge($visualization);
    }

    private function purge(Visualization $visualization): void
    {
        $visualizable = $visualization->visualizable;
        $countdown = $visualizable instanceof Countdown
            ? $visualizable
            : ($visualizable instanceof Projection ? $visualizable->countdown : null);

        if (! $countdown instanceof Countdown) {
            return;
        }

        $cache = app(CountdownCache::class);
        $cache->forgetIndex();
        $cache->forgetCountdown($countdown);
    }
}
