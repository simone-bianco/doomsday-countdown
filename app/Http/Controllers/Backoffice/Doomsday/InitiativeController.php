<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveInitiativeData;
use App\Http\Controllers\Controller;
use App\Models\Countdown;
use App\Models\Initiative;
use App\Services\Backoffice\Doomsday\BackofficeInitiativeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final class InitiativeController extends Controller
{
    public function __construct(private readonly BackofficeInitiativeService $initiatives) {}

    public function store(SaveInitiativeData $data, Countdown $countdown): RedirectResponse
    {
        Gate::authorize('create', Initiative::class);
        $this->initiatives->create($countdown, $data);

        return back()->with('success', 'Initiative created.');
    }

    public function update(SaveInitiativeData $data, Countdown $countdown, Initiative $initiative): RedirectResponse
    {
        Gate::authorize('update', $initiative);
        $this->initiatives->update($countdown, $initiative, $data);

        return back()->with('success', 'Initiative updated.');
    }

    public function destroy(Countdown $countdown, Initiative $initiative): RedirectResponse
    {
        Gate::authorize('delete', $initiative);
        $this->initiatives->delete($countdown, $initiative);

        return back()->with('success', 'Initiative deleted.');
    }
}
