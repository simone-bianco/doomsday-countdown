<?php

use App\Http\Controllers\Agent\DemoAgentController;
use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\Backoffice\DashboardController as BackofficeDashboardController;
use App\Http\Controllers\Backoffice\Doomsday\CountdownController;
use App\Http\Controllers\Backoffice\Doomsday\InitiativeController;
use App\Http\Controllers\Backoffice\Doomsday\NewsController;
use App\Http\Controllers\Backoffice\Doomsday\ProjectionController;
use App\Http\Controllers\Backoffice\Doomsday\VisualizationController;
use App\Http\Controllers\Backoffice\OpenAiKeyController;
use App\Http\Controllers\Backoffice\UserController;
use App\Http\Controllers\Web\AboutPageController;
use App\Http\Controllers\Web\CountdownDataController;
use App\Http\Controllers\Web\CountdownShowPageController;
use App\Http\Controllers\Web\HomePageController;
use App\Http\Controllers\Web\LegalPolicyPageController;
use App\Http\Controllers\Web\RobotsController;
use App\Http\Controllers\Web\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');

Route::get('/', HomePageController::class)->name('home');
Route::get('/about', AboutPageController::class)->name('about');
Route::get('/privacy', [LegalPolicyPageController::class, 'privacy'])->middleware('noindex:follow')->name('privacy');
Route::get('/cookie-policy', [LegalPolicyPageController::class, 'cookies'])->middleware('noindex:follow')->name('cookie-policy');
Route::get('/countdowns/{slug}', CountdownShowPageController::class)->name('countdowns.show');
Route::get('/countdowns/{slug}/overview-data', [CountdownDataController::class, 'overview'])->middleware('noindex')->name('countdowns.data.overview');
Route::get('/countdowns/{slug}/forecasts-data', [CountdownDataController::class, 'forecasts'])->middleware('noindex')->name('countdowns.data.forecasts');
Route::get('/countdowns/{slug}/statistics-data', [CountdownDataController::class, 'statistics'])->middleware('noindex')->name('countdowns.data.statistics');
Route::get('/countdowns/{slug}/news-data', [CountdownDataController::class, 'news'])->middleware('noindex')->name('countdowns.data.news');
Route::get('/countdowns/{slug}/initiatives-data', [CountdownDataController::class, 'initiatives'])->middleware('noindex')->name('countdowns.data.initiatives');

$demoEnvironments = config('security.demo_agent.environments', ['local', 'testing']);
if (config('security.demo_agent.enabled', true) && in_array(app()->environment(), $demoEnvironments, true)) {
    Route::post('/agent/demo', DemoAgentController::class)->middleware('noindex')->name('agent.demo');
}

Route::middleware('noindex')->group(function (): void {
    Route::get('/login', [AuthSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthSessionController::class, 'store'])->middleware('throttle:login')->name('login.store');
});
Route::post('/logout', [AuthSessionController::class, 'destroy'])->middleware(['auth', 'noindex'])->name('logout');

Route::middleware(['auth', 'admin', 'noindex'])
    ->prefix(config('ai-starter.backoffice_path'))
    ->name('backoffice.')
    ->group(function (): void {
        Route::get('/', BackofficeDashboardController::class)->name('index');
        Route::resource('countdowns', CountdownController::class)->except(['show']);
        Route::get('/countdowns/{countdown}/projections/create', [ProjectionController::class, 'create'])->name('countdowns.projections.create');
        Route::get('/countdowns/{countdown}/projections/{projection}/edit', [ProjectionController::class, 'edit'])->name('countdowns.projections.edit');
        Route::post('/countdowns/{countdown}/projections', [ProjectionController::class, 'store'])->name('countdowns.projections.store');
        Route::put('/countdowns/{countdown}/projections/{projection}', [ProjectionController::class, 'update'])->name('countdowns.projections.update');
        Route::delete('/countdowns/{countdown}/projections/{projection}', [ProjectionController::class, 'destroy'])->name('countdowns.projections.destroy');
        Route::get('/countdowns/{countdown}/visualizations/create', [VisualizationController::class, 'createForCountdown'])->name('countdowns.visualizations.create');
        Route::get('/countdowns/{countdown}/visualizations/{visualization}/edit', [VisualizationController::class, 'editForCountdown'])->name('countdowns.visualizations.edit');
        Route::post('/countdowns/{countdown}/visualizations', [VisualizationController::class, 'storeForCountdown'])->name('countdowns.visualizations.store');
        Route::put('/countdowns/{countdown}/visualizations/{visualization}', [VisualizationController::class, 'updateForCountdown'])->name('countdowns.visualizations.update');
        Route::delete('/countdowns/{countdown}/visualizations/{visualization}', [VisualizationController::class, 'destroyForCountdown'])->name('countdowns.visualizations.destroy');
        Route::get('/countdowns/{countdown}/projections/{projection}/visualizations/create', [VisualizationController::class, 'createForProjection'])->name('countdowns.projections.visualizations.create');
        Route::get('/countdowns/{countdown}/projections/{projection}/visualizations/{visualization}/edit', [VisualizationController::class, 'editForProjection'])->name('countdowns.projections.visualizations.edit');
        Route::post('/countdowns/{countdown}/projections/{projection}/visualizations', [VisualizationController::class, 'storeForProjection'])->name('countdowns.projections.visualizations.store');
        Route::put('/countdowns/{countdown}/projections/{projection}/visualizations/{visualization}', [VisualizationController::class, 'updateForProjection'])->name('countdowns.projections.visualizations.update');
        Route::delete('/countdowns/{countdown}/projections/{projection}/visualizations/{visualization}', [VisualizationController::class, 'destroyForProjection'])->name('countdowns.projections.visualizations.destroy');
        Route::post('/countdowns/{countdown}/news', [NewsController::class, 'store'])->name('countdowns.news.store');
        Route::put('/countdowns/{countdown}/news/{news}', [NewsController::class, 'update'])->name('countdowns.news.update');
        Route::delete('/countdowns/{countdown}/news/{news}', [NewsController::class, 'destroy'])->name('countdowns.news.destroy');
        Route::post('/countdowns/{countdown}/initiatives', [InitiativeController::class, 'store'])->name('countdowns.initiatives.store');
        Route::put('/countdowns/{countdown}/initiatives/{initiative}', [InitiativeController::class, 'update'])->name('countdowns.initiatives.update');
        Route::delete('/countdowns/{countdown}/initiatives/{initiative}', [InitiativeController::class, 'destroy'])->name('countdowns.initiatives.destroy');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/openai-keys', [OpenAiKeyController::class, 'index'])->name('openai-keys.index');
        Route::post('/openai-keys', [OpenAiKeyController::class, 'store'])->name('openai-keys.store');
        Route::put('/openai-keys/{openAiKey}', [OpenAiKeyController::class, 'update'])->name('openai-keys.update');
        Route::delete('/openai-keys/{openAiKey}', [OpenAiKeyController::class, 'destroy'])->name('openai-keys.destroy');
    });
