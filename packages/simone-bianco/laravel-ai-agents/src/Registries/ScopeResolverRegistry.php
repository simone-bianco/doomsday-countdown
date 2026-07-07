<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Registries;

use SimoneBianco\LaravelAiAgents\Contracts\ScopeBindingResolver;
use SimoneBianco\LaravelAiAgents\Exceptions\AgentScopeResolverMissingException;

class ScopeResolverRegistry
{
    /** @var array<string, ScopeBindingResolver> */
    private array $resolvers = [];

    public function register(string $scopeType, ScopeBindingResolver|string $resolver): void
    {
        $this->resolvers[$scopeType] = is_string($resolver) ? app($resolver) : $resolver;
    }

    public function get(string $scopeType): ScopeBindingResolver
    {
        return $this->resolvers[$scopeType] ?? throw new AgentScopeResolverMissingException(
            "No resolver for scope_type '{$scopeType}'"
        );
    }

    public function has(string $scopeType): bool
    {
        return isset($this->resolvers[$scopeType]);
    }

    /**
     * @return array<string, ScopeBindingResolver>
     */
    public function all(): array
    {
        return $this->resolvers;
    }

    /**
     * @return array<int, string>
     */
    public function types(): array
    {
        return array_keys($this->resolvers);
    }
}
