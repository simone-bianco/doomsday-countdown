<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\LoginData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class AuthSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Login', [
            'backofficePath' => '/' . config('ai-starter.backoffice_path'),
        ]);
    }

    public function store(LoginData $data, Request $request): RedirectResponse
    {
        if (! Auth::attempt(['email' => $data->email, 'password' => $data->password], true)) {
            return back()->with('error', 'Invalid credentials.');
        }

        $request->session()->regenerate();

        return redirect('/' . config('ai-starter.backoffice_path'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
