<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Contracts;

use SimoneBianco\LaravelAiAgents\DTOs\ScopeBindingSnapshot;

interface ScopeBindingResolver
{
    public function resolve(string $scopeKey, array $metadata): ScopeBindingSnapshot;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function searchForUi(string $query, int $limit = 20): array;

    /**
     * @return array<int, string>
     */
    public function suggestedVariableFields(): array;
}
