<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveCountdownData;
use App\Http\Controllers\Controller;
use App\Models\Countdown;
use App\Services\Backoffice\Doomsday\BackofficeCountdownService;
use App\Services\Backoffice\Doomsday\BackofficeDoomsdayOptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class CountdownController extends Controller
{
    public function __construct(private readonly BackofficeCountdownService $countdowns) {}

    public function index(Request $request): Response
    {
        return Inertia::render('Backoffice/Countdowns/Index', $this->countdowns->index(
            (string) $request->query('search', ''),
            (string) $request->query('sort', 'sort_order'),
            (string) $request->query('direction', 'asc'),
        ));
    }

    public function create(BackofficeDoomsdayOptionService $options): Response
    {
        return Inertia::render('Backoffice/Countdowns/Create', ['options' => $options->toArray()]);
    }

    public function store(SaveCountdownData $data): RedirectResponse
    {
        $countdown = $this->countdowns->create($data);

        return to_route('backoffice.countdowns.edit', $countdown)->with('success', 'Countdown created.');
    }

    public function edit(Request $request, Countdown $countdown): Response
    {
        return Inertia::render('Backoffice/Countdowns/Edit', $this->countdowns->detail($countdown, $request->query()));
    }

    public function update(SaveCountdownData $data, Countdown $countdown): RedirectResponse
    {
        $this->countdowns->update($countdown, $data);

        return back()->with('success', 'Countdown updated.');
    }

    public function destroy(Countdown $countdown): RedirectResponse
    {
        $this->countdowns->delete($countdown);

        return to_route('backoffice.countdowns.index')->with('success', 'Countdown deleted.');
    }
}
