<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Support;

class AgentRunStack
{
    /** @var array<int, array{slug: string, started_at: float}> */
    private array $stack = [];

    public function push(string $slug): void
    {
        $this->stack[] = ['slug' => $slug, 'started_at' => microtime(true)];
    }

    public function pop(string $slug): void
    {
        for ($i = count($this->stack) - 1; $i >= 0; $i--) {
            if ($this->stack[$i]['slug'] === $slug) {
                array_splice($this->stack, $i, 1);
                return;
            }
        }
    }

    public function has(string $slug): bool
    {
        foreach ($this->stack as $frame) {
            if ($frame['slug'] === $slug) {
                return true;
            }
        }
        return false;
    }

    public function depth(): int
    {
        return count($this->stack);
    }

    /**
     * @return array<int, array{slug: string, started_at: float}>
     */
    public function current(): array
    {
        return $this->stack;
    }
}
