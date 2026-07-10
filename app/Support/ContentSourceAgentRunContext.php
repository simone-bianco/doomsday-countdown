<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class ContentSourceAgentRunContext
{
    private const PREFIX = 'content_source_agent.';

    public static function start(string $runId, string $command, bool $toolOnly, ?string $currentDate, int $limit): void
    {
        Context::add([
            self::PREFIX.'run_id' => $runId,
            self::PREFIX.'command' => $command,
            self::PREFIX.'agent' => 'content-source-search-agent',
            self::PREFIX.'tool_only' => $toolOnly,
            self::PREFIX.'current_date' => $currentDate,
            self::PREFIX.'limit' => $limit,
        ]);

        Log::withContext(self::context());
    }

    public static function setPrompt(string $userPrompt, string $agentPrompt): void
    {
        Context::add(self::PREFIX.'user_prompt', Str::limit($userPrompt, 500, ''));
        Context::addHidden(self::PREFIX.'agent_prompt', $agentPrompt);

        Log::withContext(self::context([
            'user_prompt' => Str::limit($userPrompt, 500, ''),
            'agent_prompt_chars' => mb_strlen($agentPrompt),
        ]));
    }

    /** @param array<string, mixed> $extra @return array<string, mixed> */
    public static function context(array $extra = []): array
    {
        $base = [
            'run_id' => Context::get(self::PREFIX.'run_id'),
            'command' => Context::get(self::PREFIX.'command'),
            'agent' => Context::get(self::PREFIX.'agent'),
            'tool_only' => Context::get(self::PREFIX.'tool_only'),
            'current_date' => Context::get(self::PREFIX.'current_date'),
            'limit' => Context::get(self::PREFIX.'limit'),
        ];

        return array_filter(
            array_merge($base, $extra),
            static fn (mixed $value): bool => $value !== null && $value !== '' && $value !== []
        );
    }

    public static function runId(): ?string
    {
        $runId = Context::get(self::PREFIX.'run_id');

        return is_string($runId) && $runId !== '' ? $runId : null;
    }
}
