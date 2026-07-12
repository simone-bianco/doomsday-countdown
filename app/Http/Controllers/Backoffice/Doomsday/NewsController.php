<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveNewsData;
use App\Http\Controllers\Controller;
use App\Models\Countdown;
use App\Models\News;
use App\Services\Backoffice\Doomsday\BackofficeNewsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final class NewsController extends Controller
{
    public function __construct(private readonly BackofficeNewsService $newsService) {}

    public function store(SaveNewsData $data, Countdown $countdown): RedirectResponse
    {
        Gate::authorize('create', News::class);
        $this->newsService->create($countdown, $data);

        return back()->with('success', 'News item created.');
    }

    public function update(SaveNewsData $data, Countdown $countdown, News $news): RedirectResponse
    {
        Gate::authorize('update', $news);
        $this->newsService->update($countdown, $news, $data);

        return back()->with('success', 'News item updated.');
    }

    public function destroy(Countdown $countdown, News $news): RedirectResponse
    {
        Gate::authorize('delete', $news);
        $this->newsService->delete($countdown, $news);

        return back()->with('success', 'News item deleted.');
    }
}
