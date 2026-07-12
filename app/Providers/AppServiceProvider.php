<?php

namespace App\Providers;

use App\Models\Countdown;
use App\Models\Initiative;
use App\Models\News;
use App\Models\Projection;
use App\Models\User;
use App\Models\Visualization;
use App\Observers\Doomsday\CountdownObserver;
use App\Observers\Doomsday\InitiativeObserver;
use App\Observers\Doomsday\NewsObserver;
use App\Observers\Doomsday\ProjectionObserver;
use App\Observers\Doomsday\VisualizationObserver;
use App\Policies\AdminPolicy;
use App\Policies\UserPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use SimoneBianco\LaravelKeyRotator\Models\RotableApiKey;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Countdown::observe(CountdownObserver::class);
        Projection::observe(ProjectionObserver::class);
        Visualization::observe(VisualizationObserver::class);
        News::observe(NewsObserver::class);
        Initiative::observe(InitiativeObserver::class);

        Gate::define('access-backoffice', fn (User $user): bool => $user->is_admin);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Countdown::class, AdminPolicy::class);
        Gate::policy(Projection::class, AdminPolicy::class);
        Gate::policy(Visualization::class, AdminPolicy::class);
        Gate::policy(News::class, AdminPolicy::class);
        Gate::policy(Initiative::class, AdminPolicy::class);
        Gate::policy(RotableApiKey::class, AdminPolicy::class);

        RateLimiter::for('login', function (Request $request): Limit {
            $email = Str::lower(trim((string) $request->input('email')));
            $key = hash('sha256', $email.'|'.(string) $request->ip());
            $attempts = max(1, (int) config('security.login.max_attempts_per_minute', 5));

            return Limit::perMinute($attempts)
                ->by($key)
                ->response(function (Request $request, array $headers) {
                    if ($request->expectsJson()) {
                        return response()->json(['message' => 'Too many login attempts.'], 429, $headers);
                    }

                    return response('Too many login attempts.', 429, $headers);
                });
        });
    }
}
