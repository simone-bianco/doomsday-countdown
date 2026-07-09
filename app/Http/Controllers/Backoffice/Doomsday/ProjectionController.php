<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveProjectionData;
use App\Http\Controllers\Controller;
use App\Models\Countdown;
use App\Models\Projection;
use App\Services\Backoffice\Doomsday\BackofficeCountdownService;
use App\Services\Backoffice\Doomsday\BackofficeProjectionService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class ProjectionController extends Controller
{
    public function __construct(
        private readonly BackofficeProjectionService $projections,
        private readonly BackofficeCountdownService $countdowns,
    ) {}

    public function create(Countdown $countdown): Response
    {
        return Inertia::render('Backoffice/Countdowns/Projections/Create', $this->countdowns->projectionForm($countdown));
    }

    public function edit(Countdown $countdown, Projection $projection): Response
    {
        $this->projections->assertBelongsToCountdown($countdown, $projection);

        return Inertia::render('Backoffice/Countdowns/Projections/Edit', $this->countdowns->projectionForm($countdown, $projection));
    }

    public function store(SaveProjectionData $data, Countdown $countdown): RedirectResponse
    {
        $this->projections->create($countdown, $data);

        return back()->with('success', 'Projection created.');
    }

    public function update(SaveProjectionData $data, Countdown $countdown, Projection $projection): RedirectResponse
    {
        $this->projections->update($countdown, $projection, $data);

        return back()->with('success', 'Projection updated.');
    }

    public function destroy(Countdown $countdown, Projection $projection): RedirectResponse
    {
        $this->projections->delete($countdown, $projection);

        return back()->with('success', 'Projection deleted.');
    }
}
