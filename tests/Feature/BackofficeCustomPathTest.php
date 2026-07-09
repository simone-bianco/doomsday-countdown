<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Countdown;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Inertia\Testing\AssertableInertia as Assert;
use SimoneBianco\LaravelKeyRotator\Models\RotableApiKey;
use Symfony\Component\Process\Process;
use Tests\TestCase;

final class BackofficeCustomPathTest extends TestCase
{
    use RefreshDatabase;

    public function test_backoffice_dashboard_exposes_dashboard_only_payload(): void
    {
        $admin = User::factory()->create(['name' => 'Admin User']);
        Countdown::query()->create($this->countdownAttributes());

        $this->actingAs($admin)
            ->get('/backoffice')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Backoffice/Index')
                ->where('backofficePath', '/backoffice')
                ->where('app.name', 'Doomsday Countdown')
                ->where('app.backoffice_counts.users', 1)
                ->where('app.backoffice_counts.apiKeys', 0)
                ->where('app.backoffice_counts.countdowns', 1)
                ->where('counts.users', 1)
                ->where('counts.apiKeys', 0)
                ->where('counts.countdowns', 1)
                ->where('metrics.countdowns', 1)
                ->where('metrics.published', 1)
                ->where('recentCountdowns.0.image_path', 'images/doomsday/test.png')
                ->has('health')
                ->missing('users')
                ->missing('apiKeys'));
    }

    public function test_backoffice_users_and_openai_keys_have_dedicated_pages(): void
    {
        $admin = User::factory()->create(['name' => 'Admin User']);
        User::factory()->create(['name' => 'Editor User', 'email' => 'editor@example.test']);
        $this->openAiKey();

        $this->actingAs($admin)
            ->get('/backoffice/users')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Backoffice/Users/Index')
                ->where('backofficePath', '/backoffice')
                ->where('counts.users', 2)
                ->where('counts.apiKeys', 1)
                ->has('users', 2)
                ->missing('apiKeys'));

        $this->actingAs($admin)
            ->get('/backoffice/openai-keys')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Backoffice/OpenAiKeys/Index')
                ->where('backofficePath', '/backoffice')
                ->where('counts.users', 2)
                ->where('counts.apiKeys', 1)
                ->has('apiKeys', 1)
                ->where('apiKeys.0.label', 'Primary key')
                ->missing('users'));
    }

    public function test_section_query_does_not_switch_dashboard_into_users_or_keys(): void
    {
        $admin = User::factory()->create();
        User::factory()->create();
        $this->openAiKey();

        $this->actingAs($admin)
            ->get('/backoffice?section=users')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Backoffice/Index')
                ->missing('users')
                ->missing('apiKeys'));
    }

    public function test_backoffice_routes_register_with_configured_path_before_boot(): void
    {
        $process = new Process(
            [PHP_BINARY, 'artisan', 'route:list', '--path=control-room'],
            base_path(),
            [
                'AI_STARTER_BACKOFFICE_PATH' => 'control-room',
                'APP_ENV' => 'testing',
                'DB_CONNECTION' => 'sqlite',
                'DB_DATABASE' => ':memory:',
            ],
        );
        $process->setTimeout(30);
        $process->run();

        $output = $process->getOutput().$process->getErrorOutput();

        $this->assertTrue($process->isSuccessful(), $output);
        $this->assertStringContainsString('control-room', $output);
        $this->assertStringContainsString('control-room/users', $output);
        $this->assertStringContainsString('control-room/openai-keys', $output);
        $this->assertStringContainsString('control-room/countdowns', $output);
        $this->assertStringNotContainsString('backoffice.countdowns.show', $output);
        $this->assertStringNotContainsString('backoffice/users', $output);
        $this->assertStringNotContainsString('backoffice/openai-keys', $output);
    }

    public function test_backoffice_countdown_show_route_is_removed_but_public_show_route_remains(): void
    {
        $this->assertFalse(Route::has('backoffice.countdowns.show'));
        $this->assertTrue(Route::has('countdowns.show'));
    }

    /** @return array<string, mixed> */
    private function countdownAttributes(): array
    {
        return [
            'slug' => 'test-countdown',
            'title' => ['en' => 'Test countdown'],
            'summary' => ['en' => 'A test countdown summary.'],
            'description' => ['en' => 'Longer description.'],
            'causes' => ['en' => ['Cause one']],
            'consequences' => ['en' => ['Consequence one']],
            'recommended_actions' => ['en' => ['Action one']],
            'severity' => 'high',
            'status' => 'active',
            'target_date' => '2030-01-01 00:00:00',
            'image_path' => 'images/doomsday/test.png',
            'accent_color' => '#ff2a23',
            'sort_order' => 10,
            'is_published' => true,
        ];
    }

    private function openAiKey(): RotableApiKey
    {
        return RotableApiKey::query()->create([
            'service' => 'openai',
            'key' => 'sk-test-primary',
            'base_limit_type' => 'unlimited',
            'free_limit_type' => 'none',
            'extra_data' => ['label' => 'Primary key'],
            'is_active' => true,
            'is_depleted' => false,
        ]);
    }
}
