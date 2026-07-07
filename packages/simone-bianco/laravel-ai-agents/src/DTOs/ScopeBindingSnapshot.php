<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\DTOs;

final class ScopeBindingSnapshot
{
    /**
     * @param array<string, mixed> $fields
     */
    public function __construct(
        public readonly bool $exists,
        public readonly array $fields,
        public readonly string $label,
    ) {
    }
}
