<?php

namespace App\Providers;

use App\Models\Countdown;
use App\Models\Initiative;
use App\Models\News;
use App\Models\Projection;
use App\Models\Visualization;
use App\Observers\Doomsday\CountdownObserver;
use App\Observers\Doomsday\InitiativeObserver;
use App\Observers\Doomsday\NewsObserver;
use App\Observers\Doomsday\ProjectionObserver;
use App\Observers\Doomsday\VisualizationObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Countdown::observe(CountdownObserver::class);
        Projection::observe(ProjectionObserver::class);
        Visualization::observe(VisualizationObserver::class);
        News::observe(NewsObserver::class);
        Initiative::observe(InitiativeObserver::class);
    }
}
