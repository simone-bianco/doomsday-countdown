<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use SimoneBianco\LaravelKeyRotator\Models\RotableApiKey;
use Tests\TestCase;

final class BackofficeCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_update_and_delete_backoffice_users(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/backoffice/users', [
                'name' => 'Operator',
                'email' => 'operator@example.com',
                'password' => 'password-123',
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'User created.');

        $operator = User::query()->where('email', 'operator@example.com')->firstOrFail();

        $this->actingAs($admin)
            ->put("/backoffice/users/{$operator->id}", [
                'name' => 'Updated Operator',
                'email' => 'operator-updated@example.com',
                'password' => null,
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'User updated.');

        $this->assertDatabaseHas('users', [
            'id' => $operator->id,
            'name' => 'Updated Operator',
            'email' => 'operator-updated@example.com',
        ]);

        $this->actingAs($admin)
            ->delete("/backoffice/users/{$operator->id}")
            ->assertRedirect()
            ->assertSessionHas('success', 'User deleted.');

        $this->assertDatabaseMissing('users', ['id' => $operator->id]);
    }

    public function test_authenticated_user_can_create_update_and_delete_openai_keys(): void
    {
        config(['laravel-key-rotator.encrypt_keys' => true]);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/backoffice/openai-keys', [
                'label' => 'Primary key',
                'key' => 'sk-test-primary',
                'base_limit_type' => 'unlimited',
                'max_base_usage' => null,
                'free_limit_type' => 'none',
                'max_free_usage' => null,
                'is_active' => true,
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'OpenAI key registered.');

        $apiKey = RotableApiKey::query()->firstOrFail();
        $this->assertSame('sk-test-primary', $apiKey->key);
        $this->assertNotSame('sk-test-primary', $apiKey->getRawOriginal('key'));

        $this->actingAs($admin)
            ->put("/backoffice/openai-keys/{$apiKey->id}", [
                'label' => 'Updated key',
                'key' => '',
                'base_limit_type' => 'fixed',
                'max_base_usage' => 1000,
                'free_limit_type' => 'daily',
                'max_free_usage' => 100,
                'is_active' => false,
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'OpenAI key updated.');

        $apiKey->refresh();
        $this->assertSame('Updated key', data_get($apiKey->extra_data, 'label'));
        $this->assertSame('sk-test-primary', $apiKey->key);
        $this->assertFalse($apiKey->is_active);

        $this->actingAs($admin)
            ->delete("/backoffice/openai-keys/{$apiKey->id}")
            ->assertRedirect()
            ->assertSessionHas('success', 'OpenAI key deleted.');

        $this->assertDatabaseMissing('rotable_api_keys', ['id' => $apiKey->id]);
    }
}
