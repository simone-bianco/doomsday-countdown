<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Contracts;

interface AgentVariableProvider
{
    public function key(): string;

    public function label(): string;

    /**
     * @param array<string, mixed> $context
     * @return array<string, mixed>
     */
    public function resolve(array $context): array;
}
