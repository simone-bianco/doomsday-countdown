<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Support;

final class AgentRunContext
{
    /** @var array<string, mixed> */
    private array $data;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function set(string $key, mixed $value): static
    {
        $new = clone $this;
        $new->data[$key] = $value;

        return $new;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function with(array $data): static
    {
        $new = clone $this;
        $new->data = array_merge($this->data, $data);

        return $new;
    }
}
