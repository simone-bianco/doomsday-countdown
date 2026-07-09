<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveVisualizationData;
use App\Http\Controllers\Controller;
use App\Models\Countdown;
use App\Models\Projection;
use App\Models\Visualization;
use App\Services\Backoffice\Doomsday\BackofficeCountdownService;
use App\Services\Backoffice\Doomsday\BackofficeProjectionService;
use App\Services\Backoffice\Doomsday\BackofficeVisualizationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class VisualizationController extends Controller
{
    public function __construct(
        private readonly BackofficeVisualizationService $visualizations,
        private readonly BackofficeProjectionService $projections,
        private readonly BackofficeCountdownService $countdowns,
    ) {}

    public function createForCountdown(Countdown $countdown): Response
    {
        return Inertia::render('Backoffice/Countdowns/Visualizations/Create', $this->countdowns->visualizationForm($countdown));
    }

    public function editForCountdown(Countdown $countdown, Visualization $visualization): Response
    {
        $this->visualizations->assertBelongsToVisualizable($countdown, $visualization);

        return Inertia::render('Backoffice/Countdowns/Visualizations/Edit', $this->countdowns->visualizationForm($countdown, $visualization));
    }

    public function createForProjection(Countdown $countdown, Projection $projection): Response
    {
        $this->projections->assertBelongsToCountdown($countdown, $projection);

        return Inertia::render('Backoffice/Countdowns/Visualizations/Create', $this->countdowns->visualizationForm($countdown, null, $projection));
    }

    public function editForProjection(Countdown $countdown, Projection $projection, Visualization $visualization): Response
    {
        $this->projections->assertBelongsToCountdown($countdown, $projection);
        $this->visualizations->assertBelongsToVisualizable($projection, $visualization);

        return Inertia::render('Backoffice/Countdowns/Visualizations/Edit', $this->countdowns->visualizationForm($countdown, $visualization, $projection));
    }

    public function storeForCountdown(SaveVisualizationData $data, Countdown $countdown): RedirectResponse
    {
        $this->visualizations->create($countdown, $data);

        return back()->with('success', 'Visualization created.');
    }

    public function updateForCountdown(SaveVisualizationData $data, Countdown $countdown, Visualization $visualization): RedirectResponse
    {
        $this->visualizations->update($countdown, $visualization, $data);

        return back()->with('success', 'Visualization updated.');
    }

    public function destroyForCountdown(Countdown $countdown, Visualization $visualization): RedirectResponse
    {
        $this->visualizations->delete($countdown, $visualization);

        return back()->with('success', 'Visualization deleted.');
    }

    public function storeForProjection(SaveVisualizationData $data, Countdown $countdown, Projection $projection): RedirectResponse
    {
        $this->projections->assertBelongsToCountdown($countdown, $projection);
        $this->visualizations->create($projection, $data);

        return back()->with('success', 'Visualization created.');
    }

    public function updateForProjection(SaveVisualizationData $data, Countdown $countdown, Projection $projection, Visualization $visualization): RedirectResponse
    {
        $this->projections->assertBelongsToCountdown($countdown, $projection);
        $this->visualizations->update($projection, $visualization, $data);

        return back()->with('success', 'Visualization updated.');
    }

    public function destroyForProjection(Countdown $countdown, Projection $projection, Visualization $visualization): RedirectResponse
    {
        $this->projections->assertBelongsToCountdown($countdown, $projection);
        $this->visualizations->delete($projection, $visualization);

        return back()->with('success', 'Visualization deleted.');
    }
}
