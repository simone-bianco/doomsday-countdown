<?php

declare(strict_types=1);

namespace App\Observers\Doomsday;

use App\Models\News;
use App\Services\Doomsday\Cache\CountdownCache;

final class NewsObserver
{
    public function created(News $news): void
    {
        $this->purge($news);
    }

    public function updated(News $news): void
    {
        $this->purge($news);
    }

    public function deleted(News $news): void
    {
        $this->purge($news);
    }

    public function restored(News $news): void
    {
        $this->purge($news);
    }

    public function forceDeleted(News $news): void
    {
        $this->purge($news);
    }

    private function purge(News $news): void
    {
        $cache = app(CountdownCache::class);
        $cache->forgetIndex();
        $cache->forgetCountdown($news->countdown);
    }
}
