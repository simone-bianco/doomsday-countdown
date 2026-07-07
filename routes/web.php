<?php

use App\Http\Controllers\Agent\DemoAgentController;
use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\Backoffice\DashboardController as BackofficeDashboardController;
use App\Http\Controllers\Backoffice\OpenAiKeyController;
use App\Http\Controllers\Backoffice\UserController;
use App\Http\Controllers\Web\AboutPageController;
use App\Http\Controllers\Web\CountdownShowPageController;
use App\Http\Controllers\Web\HomePageController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePageController::class)->name('home');
Route::get('/about', AboutPageController::class)->name('about');
Route::get('/countdowns/{slug}', CountdownShowPageController::class)->name('countdowns.show');

Route::post('/agent/demo', DemoAgentController::class)->name('agent.demo');

Route::get('/login', [AuthSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthSessionController::class, 'destroy'])->name('logout');

Route::middleware('auth')
    ->prefix(config('ai-starter.backoffice_path'))
    ->name('backoffice.')
    ->group(function (): void {
        Route::get('/', BackofficeDashboardController::class)->name('index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/openai-keys', [OpenAiKeyController::class, 'store'])->name('openai-keys.store');
        Route::put('/openai-keys/{openAiKey}', [OpenAiKeyController::class, 'update'])->name('openai-keys.update');
        Route::delete('/openai-keys/{openAiKey}', [OpenAiKeyController::class, 'destroy'])->name('openai-keys.destroy');
    });
