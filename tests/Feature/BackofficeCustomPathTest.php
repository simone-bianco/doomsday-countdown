<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Symfony\Component\Process\Process;
use Tests\TestCase;

final class BackofficeCustomPathTest extends TestCase
{
    use RefreshDatabase;

    public function test_backoffice_page_exposes_backoffice_path_prop(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->get('/backoffice')
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('Backoffice/Index')
                ->where('backofficePath', '/backoffice')
                ->has('users')
                ->has('apiKeys'));
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

        $output = $process->getOutput() . $process->getErrorOutput();

        $this->assertTrue($process->isSuccessful(), $output);
        $this->assertStringContainsString('control-room', $output);
        $this->assertStringContainsString('control-room/users', $output);
        $this->assertStringContainsString('control-room/openai-keys', $output);
        $this->assertStringNotContainsString('backoffice/users', $output);
        $this->assertStringNotContainsString('backoffice/openai-keys', $output);
    }
}
