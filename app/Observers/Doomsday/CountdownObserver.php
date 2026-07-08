<?php

declare(strict_types=1);

namespace App\Observers\Doomsday;

use App\Models\Countdown;
use App\Services\Doomsday\Cache\CountdownCache;

final class CountdownObserver
{
    public function created(Countdown $countdown): void
    {
        $this->purge($countdown);
    }

    public function updated(Countdown $countdown): void
    {
        $this->purge($countdown);
        $originalSlug = (string) $countdown->getOriginal('slug');
        if ($originalSlug !== '' && $originalSlug !== $countdown->slug) {
            $this->cache()->forgetCountdownSlug($originalSlug);
        }
    }

    public function deleted(Countdown $countdown): void
    {
        $this->purge($countdown);
    }

    public function restored(Countdown $countdown): void
    {
        $this->purge($countdown);
    }

    public function forceDeleted(Countdown $countdown): void
    {
        $this->purge($countdown);
    }

    private function purge(Countdown $countdown): void
    {
        $this->cache()->forgetIndex();
        $this->cache()->forgetCountdown($countdown);
    }

    private function cache(): CountdownCache
    {
        return app(CountdownCache::class);
    }
}
