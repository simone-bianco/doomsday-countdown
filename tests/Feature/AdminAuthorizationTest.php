<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

final class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_are_non_admin_by_default_and_cast_is_boolean(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->is_admin);
        $this->assertIsBool($user->is_admin);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_admin' => false]);
    }

    public function test_authenticated_non_admin_receives_forbidden_for_backoffice(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/'.config('ai-starter.backoffice_path'));

        $response->assertForbidden();
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_admin_can_access_backoffice(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/'.config('ai-starter.backoffice_path'))
            ->assertOk();
    }

    public function test_final_administrator_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->delete(route('backoffice.users.destroy', $admin))
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'is_admin' => true]);
    }

    public function test_admin_can_delete_non_admin_and_another_admin_when_one_remains(): void
    {
        $admin = User::factory()->admin()->create();
        $otherAdmin = User::factory()->admin()->create();
        $operator = User::factory()->create();

        $this->actingAs($admin)->delete(route('backoffice.users.destroy', $operator))->assertRedirect();
        $this->actingAs($admin)->delete(route('backoffice.users.destroy', $otherAdmin))->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $operator->id]);
        $this->assertDatabaseMissing('users', ['id' => $otherAdmin->id]);
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'is_admin' => true]);
    }

    public function test_admin_promotion_command_requires_force_and_audits_success(): void
    {
        $user = User::factory()->create(['email' => 'operator@example.test']);

        $this->artisan('user:promote-admin', ['email' => 'operator@example.test'])
            ->assertFailed();
        $this->assertFalse($user->refresh()->is_admin);

        Log::spy();
        $this->artisan('user:promote-admin', [
            'email' => ' OPERATOR@EXAMPLE.TEST ',
            '--force' => true,
        ])->assertSuccessful();

        $this->assertTrue($user->refresh()->is_admin);
        Log::shouldHaveReceived('notice')
            ->once()
            ->withArgs(static fn (string $message, array $context): bool => $message === 'security.admin.promoted'
                && $context['user_id'] === $user->id
                && $context['command'] === 'user:promote-admin');
    }
}
