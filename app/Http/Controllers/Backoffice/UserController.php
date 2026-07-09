<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backoffice;

use App\Data\SaveUserData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Backoffice\Doomsday\BackofficeDashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

final class UserController extends Controller
{
    public function __construct(private readonly BackofficeDashboardService $dashboard) {}

    public function index(): Response
    {
        return Inertia::render('Backoffice/Users/Index', $this->dashboard->usersIndex());
    }

    public function store(SaveUserData $data): RedirectResponse
    {
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
        $user->delete();

        return back()->with('success', 'User deleted.');
    }
}
