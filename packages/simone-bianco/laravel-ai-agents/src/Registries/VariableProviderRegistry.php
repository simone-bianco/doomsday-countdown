<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Registries;

use SimoneBianco\LaravelAiAgents\Contracts\AgentVariableProvider;

class VariableProviderRegistry
{
    /** @var array<string, AgentVariableProvider> */
    private array $providers = [];

    public function register(string $key, AgentVariableProvider|string $provider): void
    {
        $this->providers[$key] = is_string($provider) ? app($provider) : $provider;
    }

    public function get(string $key): ?AgentVariableProvider
    {
        return $this->providers[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($this->providers[$key]);
    }

    /**
     * @return array<string, AgentVariableProvider>
     */
    public function all(): array
    {
        return $this->providers;
    }
}
