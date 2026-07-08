<?php

declare(strict_types=1);

namespace App\Observers\Doomsday;

use App\Models\Projection;
use App\Services\Doomsday\Cache\CountdownCache;

final class ProjectionObserver
{
    public function created(Projection $projection): void
    {
        $this->purge($projection);
    }

    public function updated(Projection $projection): void
    {
        $this->purge($projection);
    }

    public function deleted(Projection $projection): void
    {
        $this->purge($projection);
    }

    public function restored(Projection $projection): void
    {
        $this->purge($projection);
    }

    public function forceDeleted(Projection $projection): void
    {
        $this->purge($projection);
    }

    private function purge(Projection $projection): void
    {
        $cache = app(CountdownCache::class);
        $cache->forgetIndex();
        $cache->forgetCountdown($projection->countdown);
    }
}
