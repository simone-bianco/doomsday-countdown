<?php

declare(strict_types=1);

namespace App\Services\Starter;

use App\Ai\Agents\DemoAgent;
use LarAgent\Core\Contracts\DataModel;
use LarAgent\Core\Contracts\Message as MessageContract;

final class DemoAgentService
{
    /** @return array<string, mixed> */
    public function run(string $message): array
    {
        $startedAt = microtime(true);
        $agent = DemoAgent::make(DemoAgent::slug());
        $response = $agent->respond($message);
        $payload = $this->payload($message, $agent->toDTO()->toArray());

        return [
            'agent' => [
                'id' => DemoAgent::slug(),
                'slug' => DemoAgent::slug(),
                'model' => (string) $agent->model(),
                'provider' => $payload['provider'],
            ],
            'payload' => $payload,
            'status' => 200,
            'ok' => true,
            'response' => $this->normalizeResponse($response),
            'elapsed_ms' => round((microtime(true) - $startedAt) * 1000, 2),
        ];
    }

    /**
     * @param  array<string, mixed>  $agent
     * @return array<string, mixed>
     */
    private function payload(string $message, array $agent): array
    {
        $configuration = is_array($agent['configuration'] ?? null) ? $agent['configuration'] : [];

        return [
            'message' => $message,
            'provider' => $agent['provider'] ?? null,
            'provider_name' => $agent['providerName'] ?? null,
            'tools' => is_array($agent['tools'] ?? null) ? $agent['tools'] : [],
            'configuration' => [
                'history' => $configuration['history'] ?? null,
                'model' => $configuration['model'] ?? null,
                'driver' => $configuration['driver'] ?? null,
            ],
            'session_id' => $agent['sessionId'] ?? null,
        ];
    }

    private function normalizeResponse(string|array|DataModel|MessageContract $response): mixed
    {
        if ($response instanceof MessageContract) {
            if (method_exists($response, 'toArrayWithMeta')) {
                return $response->toArrayWithMeta();
            }

            return (string) $response;
        }

        if ($response instanceof DataModel && method_exists($response, 'toArray')) {
            return $response->toArray();
        }

        return $response;
    }
}
