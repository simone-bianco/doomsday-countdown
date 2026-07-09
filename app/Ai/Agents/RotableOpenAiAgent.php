<?php

declare(strict_types=1);

namespace App\Ai\Agents;

use App\KeyRotators\Openai\OpenAIKeyRotator;
use LarAgent\Agent;
use LarAgent\Core\Contracts\Message as MessageContract;
use LarAgent\Drivers\OpenAi\OpenAiCompatible;

abstract class RotableOpenAiAgent extends Agent
{
    protected $driver = OpenAiCompatible::class;

    protected $provider = 'default';

    protected $history = 'in_memory';

    protected $parallelToolCalls = false;

    protected OpenAIKeyRotator $keyRotator;

    public function __construct($key, bool $usesUserId = false, ?string $group = null)
    {
        $this->keyRotator = OpenAIKeyRotator::make()
            ->pickKey()
            ->injectKey();

        parent::__construct($key, $usesUserId, $group);
    }

    protected function afterResponse(MessageContract $message)
    {
        $tokens = $this->tokensFromMessage($message);

        if ($tokens !== null && $tokens > 0.0) {
            $this->keyRotator->registerUsage($tokens);
        }

        return parent::afterResponse($message);
    }

    private function tokensFromMessage(MessageContract $message): ?float
    {
        if (! method_exists($message, 'getUsage')) {
            return null;
        }

        return $this->tokensFromUsage($message->getUsage());
    }

    private function tokensFromUsage(mixed $usage): ?float
    {
        if ($usage === null) {
            return null;
        }

        if (is_array($usage)) {
            return $this->tokensFromUsageArray($usage);
        }

        if (is_object($usage)) {
            foreach (['totalTokens', 'total_tokens'] as $property) {
                if (isset($usage->{$property}) && is_numeric($usage->{$property})) {
                    return (float) $usage->{$property};
                }
            }

            if (method_exists($usage, 'toArray')) {
                $usageArray = $usage->toArray();

                if (is_array($usageArray)) {
                    return $this->tokensFromUsageArray($usageArray);
                }
            }
        }

        return null;
    }

    /** @param array<string, mixed> $usage */
    private function tokensFromUsageArray(array $usage): ?float
    {
        foreach (['totalTokens', 'total_tokens'] as $key) {
            if (isset($usage[$key]) && is_numeric($usage[$key])) {
                return (float) $usage[$key];
            }
        }

        $promptTokens = $usage['promptTokens'] ?? $usage['prompt_tokens'] ?? null;
        $completionTokens = $usage['completionTokens'] ?? $usage['completion_tokens'] ?? null;

        if (is_numeric($promptTokens) && is_numeric($completionTokens)) {
            return (float) $promptTokens + (float) $completionTokens;
        }

        return null;
    }
}
