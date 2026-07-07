<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Support;

use LarAgent\Tool;

/**
 * Wraps a Tool with optional before/after callbacks so progress events
 * can be fired without modifying individual tool implementations.
 */
class ProgressAwareTool extends Tool
{
    /**
     * @param \Closure(string $toolName, array $input, string $executionId): void|null $beforeExecute
     * @param \Closure(string $toolName, mixed $result, float $ms, bool $success, string $executionId): void|null $afterExecute
     */
    public function __construct(
        private readonly Tool $inner,
        private readonly ?\Closure $beforeExecute = null,
        private readonly ?\Closure $afterExecute = null,
    ) {
        parent::__construct($inner->getName(), $inner->getDescription(), $inner->getMetaData());
    }

    public function getProperties(): array
    {
        return $this->inner->getProperties();
    }

    public function getRequired(): array
    {
        return $this->inner->getRequired();
    }

    public function execute(array $input): mixed
    {
        $name = $this->inner->getName();
        $start = microtime(true);
        $executionId = bin2hex(random_bytes(6));

        if ($this->beforeExecute !== null) {
            ($this->beforeExecute)($name, $input, $executionId);
        }

        try {
            $result = $this->inner->execute($input);

            if ($this->afterExecute !== null) {
                ($this->afterExecute)($name, $result, (microtime(true) - $start) * 1000, true, $executionId);
            }

            return $result;
        } catch (\Throwable $e) {
            if ($this->afterExecute !== null) {
                ($this->afterExecute)($name, null, (microtime(true) - $start) * 1000, false, $executionId);
            }

            throw $e;
        }
    }
}
