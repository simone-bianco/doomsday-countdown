<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Console\Commands\InitializeAiProjectCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use SimoneBianco\LaravelKeyRotator\Models\RotableApiKey;
use Tests\TestCase;

final class InitializeAiProjectCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_initializer_openai_key_registration_is_idempotent_with_encrypted_keys(): void
    {
        config(['laravel-key-rotator.encrypt_keys' => true]);

        $command = app(InitializeAiProjectCommand::class);
        $method = new ReflectionMethod($command, 'registerInitializerOpenAiKey');
        $method->setAccessible(true);

        $method->invoke($command, 'sk-test-initializer');
        $method->invoke($command, 'sk-test-initializer');

        $this->assertSame(1, RotableApiKey::query()->where('service', 'openai')->count());

        $apiKey = RotableApiKey::query()->firstOrFail();
        $this->assertSame('sk-test-initializer', $apiKey->key);
        $this->assertNotSame('sk-test-initializer', $apiKey->getRawOriginal('key'));
        $this->assertSame('initialize-ai-project', data_get($apiKey->extra_data, 'source'));
    }

    public function test_initializer_reuses_legacy_initializer_label_without_resetting_usage_or_status(): void
    {
        config(['laravel-key-rotator.encrypt_keys' => true]);

        $legacy = RotableApiKey::query()->create([
            'service' => 'openai',
            'key' => 'sk-test-old',
            'base_limit_type' => 'fixed',
            'max_base_usage' => 2000,
            'current_base_usage' => 42,
            'free_limit_type' => 'daily',
            'max_free_usage' => 100,
            'current_free_usage' => 7,
            'extra_data' => ['label' => 'Initializer OpenAI key'],
            'is_active' => false,
            'is_depleted' => true,
        ]);

        $command = app(InitializeAiProjectCommand::class);
        $method = new ReflectionMethod($command, 'registerInitializerOpenAiKey');
        $method->setAccessible(true);

        $method->invoke($command, 'sk-test-new');

        $this->assertSame(1, RotableApiKey::query()->where('service', 'openai')->count());

        $legacy->refresh();
        $this->assertSame('sk-test-new', $legacy->key);
        $this->assertSame(42.0, $legacy->current_base_usage);
        $this->assertSame(7.0, $legacy->current_free_usage);
        $this->assertFalse($legacy->is_active);
        $this->assertTrue($legacy->is_depleted);
        $this->assertSame('initialize-ai-project', data_get($legacy->extra_data, 'source'));
    }
}
