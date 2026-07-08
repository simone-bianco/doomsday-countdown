<?php

declare(strict_types=1);

namespace App\Observers\Doomsday;

use App\Models\Initiative;
use App\Services\Doomsday\Cache\CountdownCache;

final class InitiativeObserver
{
    public function created(Initiative $initiative): void
    {
        $this->purge($initiative);
    }

    public function updated(Initiative $initiative): void
    {
        $this->purge($initiative);
    }

    public function deleted(Initiative $initiative): void
    {
        $this->purge($initiative);
    }

    public function restored(Initiative $initiative): void
    {
        $this->purge($initiative);
    }

    public function forceDeleted(Initiative $initiative): void
    {
        $this->purge($initiative);
    }

    private function purge(Initiative $initiative): void
    {
        app(CountdownCache::class)->forgetCountdown($initiative->countdown);
    }
}
