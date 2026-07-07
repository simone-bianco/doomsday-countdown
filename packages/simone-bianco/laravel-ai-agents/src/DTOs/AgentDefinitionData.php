<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\DTOs;

use SimoneBianco\LaravelAiAgents\Enums\AgentResponseFormat;
use SimoneBianco\LaravelAiAgents\Enums\AgentStreamingMode;
use Spatie\LaravelData\Data;

class AgentDefinitionData extends Data
{
    /**
     * @param array<string, mixed>|null $response_schema
     * @param array<string, mixed>|null $metadata
     */
    public function __construct(
        public readonly ?string $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $description,
        public readonly ?string $type,
        public readonly ?string $scope,
        public readonly string $provider,
        public readonly string $model,
        public readonly ?string $system_prompt,
        public readonly ?float $temperature,
        public readonly ?float $top_p,
        public readonly ?int $max_completion_tokens,
        public readonly bool $parallel_tool_calls,
        public readonly bool $developer_role_for_instructions,
        public readonly AgentResponseFormat $response_format,
        public readonly ?array $response_schema,
        public readonly AgentStreamingMode $streaming_mode,
        public readonly ?string $history_driver,
        public readonly ?array $metadata,
    ) {
    }
}
