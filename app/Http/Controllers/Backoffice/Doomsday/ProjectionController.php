<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveProjectionData;
use App\Http\Controllers\Controller;
use App\Models\Countdown;
use App\Models\Projection;
use App\Services\Backoffice\Doomsday\BackofficeProjectionService;
use Illuminate\Http\RedirectResponse;

final class ProjectionController extends Controller
{
    public function __construct(private readonly BackofficeProjectionService $projections)
    {
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
