<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Data\LoginData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

final class AuthSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Login', [
            'backofficePath' => '/'.config('ai-starter.backoffice_path'),
        ]);
    }

    public function store(LoginData $data, Request $request): RedirectResponse
    {
        $email = Str::lower(trim($data->email));
        $remember = $request->boolean('remember');
        $attemptKey = hash('sha256', $email.'|'.(string) $request->ip());

        if (! Auth::attempt(['email' => $email, 'password' => $data->password], $remember)) {
            Log::notice('security.login.failed', ['attempt_key' => $attemptKey]);

            return back()->with('error', 'Unable to sign in with the supplied credentials.');
        }

        $request->session()->regenerate();
        Log::notice('security.login.succeeded', [
            'user_id' => Auth::id(),
            'remember' => $remember,
        ]);

        return redirect('/'.config('ai-starter.backoffice_path'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
