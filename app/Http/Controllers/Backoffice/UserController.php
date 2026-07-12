<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backoffice;

use App\Data\SaveUserData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Backoffice\Doomsday\BackofficeDashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

final class UserController extends Controller
{
    public function __construct(private readonly BackofficeDashboardService $dashboard) {}

    public function index(): Response
    {
        Gate::authorize('viewAny', User::class);

        return Inertia::render('Backoffice/Users/Index', $this->dashboard->usersIndex());
    }

    public function store(SaveUserData $data): RedirectResponse
    {
        Gate::authorize('create', User::class);

        if ($data->password === null || $data->password === '') {
            return back()->with('error', 'Password is required for new users.');
        }

        User::query()->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);

        return back()->with('success', 'User created.');
    }

    public function update(SaveUserData $data, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $attributes = [
            'name' => $data->name,
            'email' => $data->email,
        ];

        if ($data->password !== null && $data->password !== '') {
            $attributes['password'] = Hash::make($data->password);
        }

        $user->update($attributes);

        return back()->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        DB::transaction(function () use ($user): void {
            $lockedUser = User::query()->lockForUpdate()->findOrFail($user->getKey());
            $adminIds = User::query()->where('is_admin', true)->lockForUpdate()->pluck('id');
            Gate::authorize('delete', $lockedUser);
            abort_if($lockedUser->is_admin && $adminIds->count() <= 1, 403, 'The final administrator cannot be deleted.');
            $lockedUser->delete();
        });

        return back()->with('success', 'User deleted.');
    }
}
