<?php

declare(strict_types=1);

namespace App\Services\Starter;

use App\KeyRotators\Openai\OpenAIKeyRotator;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use SimoneBianco\LaravelAiAgents\Enums\AgentResponseFormat;
use SimoneBianco\LaravelAiAgents\Enums\AgentStreamingMode;
use SimoneBianco\LaravelAiAgents\Models\AiAgent;
use SimoneBianco\LaravelKeyRotator\Exceptions\NoAvailableKeysException;

final class DemoAgentService
{
    /**
     * @return array<string, mixed>
     *
     * @throws ConnectionException
     */
    public function run(string $message): array
    {
        $agent = $this->ensureAgent();
        $rotator = OpenAIKeyRotator::make()->pickKey()->injectKey();
        $key = (string) config('services.openai.key');
        $model = (string) $agent->model;

        if ($key === '') {
            throw new NoAvailableKeysException("No OpenAI API key is configured for service 'openai'.");
        }

        $payload = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => (string) $agent->system_prompt],
                ['role' => 'user', 'content' => $message],
            ],
            'temperature' => (float) ($agent->temperature ?? 0.2),
        ];

        $startedAt = microtime(true);
        $response = Http::withToken($key)
            ->acceptJson()
            ->timeout(60)
            ->post(rtrim((string) config('services.openai.base_url'), '/') . '/chat/completions', $payload);

        $json = $response->json();
        $usage = is_array($json) && isset($json['usage']) && is_array($json['usage'])
            ? $json['usage']
            : [];
        $tokens = isset($usage['total_tokens']) && is_numeric($usage['total_tokens'])
            ? (float) $usage['total_tokens']
            : 1.0;

        $rotator->registerUsage($tokens);

        return [
            'agent' => [
                'id' => (string) $agent->id,
                'slug' => (string) $agent->slug,
                'model' => $model,
            ],
            'payload' => $payload,
            'status' => $response->status(),
            'ok' => $response->successful(),
            'response' => $json,
            'elapsed_ms' => round((microtime(true) - $startedAt) * 1000, 2),
        ];
    }

    private function ensureAgent(): AiAgent
    {
        $slug = (string) config('ai-starter.demo_agent.slug');

        return AiAgent::query()->firstOrCreate(
            ['slug' => $slug],
            [
                'name' => 'Quickstart Assistant',
                'description' => 'Default demo agent used by the starter home page.',
                'type' => 'chat',
                'scope' => 'global',
                'provider' => 'openai',
                'model' => (string) config('ai-starter.demo_agent.model'),
                'system_prompt' => 'You are the default AI assistant for this Laravel quickstart. Reply concisely and mention that the request passed through the starter demo agent.',
                'temperature' => 0.2,
                'top_p' => null,
                'max_completion_tokens' => 800,
                'parallel_tool_calls' => false,
                'developer_role_for_instructions' => false,
                'response_format' => AgentResponseFormat::RawText,
                'response_schema' => null,
                'streaming_mode' => AgentStreamingMode::Sync,
                'history_driver' => null,
                'metadata' => ['starter' => true],
                'is_system' => true,
                'is_locked' => false,
                'active_runs_count' => 0,
            ],
        );
    }
}
