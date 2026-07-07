<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Registries;

use SimoneBianco\LaravelAiAgents\Contracts\AgentToolFactory;
use SimoneBianco\LaravelAiAgents\Exceptions\AgentToolResolutionException;

class ToolRegistry
{
    /** @var array<string, AgentToolFactory> */
    private array $factories = [];

    public function register(string $key, AgentToolFactory|string $factory): void
    {
        $this->factories[$key] = is_string($factory) ? app($factory) : $factory;
    }

    public function factory(string $key): AgentToolFactory
    {
        return $this->factories[$key] ?? throw new AgentToolResolutionException(
            "Tool '{$key}' not found in ToolRegistry"
        );
    }

    /**
     * @return array<string, AgentToolFactory>
     */
    public function all(): array
    {
        return $this->factories;
    }

    public function has(string $key): bool
    {
        return isset($this->factories[$key]);
    }

    /**
     * @return array<int, string>
     */
    public function registeredKeys(): array
    {
        return array_keys($this->factories);
    }
}
