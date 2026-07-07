<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Concerns;

use App\KeyRotators\Openai\OpenAIKeyRotator;
use Illuminate\Support\Facades\Log;
use Throwable;

trait InjectsRotatedOpenAIKey
{
    protected function injectRotatedOpenAIKey(): void
    {
        if (!class_exists(OpenAIKeyRotator::class)) {
            return;
        }

        try {
            $rotator = OpenAIKeyRotator::make()->pickKey();
            $key = $rotator->getCurrentKey();

            if ($key !== null) {
                $tail = mb_substr((string) $key->key, -8);
                $note = is_array($key->extra_data)
                    ? ($key->extra_data['note'] ?? $key->extra_data['description'] ?? '')
                    : '';

                Log::channel(config('ai-agents.log_channel', 'agents'))->info(
                    '[RotableAgent] Key selected',
                    [
                        'key_id' => $key->id,
                        'key_tail' => $tail,
                        'note' => mb_substr($note, 0, 100),
                        'agent' => static::class,
                    ]
                );
            }

            $rotator->injectKey();
        } catch (Throwable $throwable) {
            Log::channel(config('ai-agents.log_channel', 'agents'))->warning(
                '[RotableAgent] OpenAI key rotation injection failed, using fallback config',
                [
                    'error' => $throwable->getMessage(),
                    'agent' => static::class,
                ]
            );
        }
    }
}
