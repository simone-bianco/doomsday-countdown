<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\DTOs;

use Spatie\LaravelData\Data;

class AgentRunRequestData extends Data
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        public readonly string $agent_slug,
        public readonly string $chat_key,
        public readonly string $message,
        public readonly array $context = [],
        public readonly ?AgentDefinitionData $agent_draft = null,
    ) {
    }
}
