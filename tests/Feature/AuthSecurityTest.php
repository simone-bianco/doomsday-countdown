<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

final class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_normalizes_email_and_remember_is_false_by_default(): void
    {
        $user = User::factory()->create(['email' => 'admin@example.test']);

        $response = $this->post('/login', [
            'email' => '  ADMIN@EXAMPLE.TEST  ',
            'password' => 'password',
        ]);

        $response->assertRedirect('/'.config('ai-starter.backoffice_path'));
        $this->assertAuthenticatedAs($user);
        $response->assertCookieMissing(Auth::guard('web')->getRecallerName());
    }

    public function test_login_sets_remember_cookie_only_when_explicitly_requested(): void
    {
        User::factory()->create(['email' => 'admin@example.test']);

        $response = $this->post('/login', [
            'email' => 'admin@example.test',
            'password' => 'password',
            'remember' => true,
        ]);

        $response->assertRedirect('/'.config('ai-starter.backoffice_path'));
        $response->assertCookie(Auth::guard('web')->getRecallerName());
    }

    public function test_login_failure_is_generic_for_existing_and_unknown_accounts(): void
    {
        User::factory()->create(['email' => 'admin@example.test']);
        $message = 'Unable to sign in with the supplied credentials.';

        $this->post('/login', ['email' => 'admin@example.test', 'password' => 'wrong-password'])
            ->assertRedirect()
            ->assertSessionHas('error', $message);
        $this->post('/login', ['email' => 'unknown@example.test', 'password' => 'wrong-password'])
            ->assertRedirect()
            ->assertSessionHas('error', $message);

        $this->assertGuest();
    }

    public function test_named_login_limiter_uses_normalized_email_and_ip(): void
    {
        config(['security.login.max_attempts_per_minute' => 2]);
        $payload = ['email' => 'Rate.Limit@Example.Test', 'password' => 'wrong-password'];

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])->post('/login', $payload)->assertRedirect();
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])->post('/login', [
            'email' => ' rate.limit@example.test ',
            'password' => 'wrong-password',
        ])->assertRedirect();
        $limited = $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])->post('/login', $payload);

        $limited->assertTooManyRequests();
        $this->assertSame('Too many login attempts.', $limited->getContent());
        $limited->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_logout_invalidates_authenticated_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }
}
