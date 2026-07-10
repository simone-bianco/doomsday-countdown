<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Ai\Agents\ContentSourceSearchAgent;
use App\Services\Doomsday\NewsUpdater\ContentSourceAgentSearchService;
use App\Support\ContentSourceAgentRunContext;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use JsonException;
use LarAgent\Core\Contracts\DataModel;
use LarAgent\Core\Contracts\Message as MessageContract;
use Throwable;

final class AgentContentSourceSearchCommand extends Command
{
    protected $signature = 'countdowns:agent-content-source-search
        {prompt? : Natural-language request for the content source agent.}
        {--today= : Current date in YYYY-MM-DD; defaults to app timezone today.}
        {--limit=10 : Maximum items per tool call, from 1 to 20.}
        {--sources= : Comma-separated source keys/names to expose to the agent. Defaults to all active sources.}
        {--language= : Optional source language filter such as en or it.}
        {--raw : Print only the final JSON payload.}
        {--tool-only : Run the deterministic search tool directly, without an LLM call.}
        {--source= : Source key/name for --tool-only mode.}
        {--query= : Search query for --tool-only mode.}
        {--from= : Start date for --tool-only mode, YYYY-MM-DD.}
        {--to= : End date for --tool-only mode, YYYY-MM-DD.}';

    protected $description = 'Ask a rotable LarAgent to search predefined content sources and return DB-fillable news JSON.';

    public function handle(ContentSourceAgentSearchService $searchService): int
    {
        $runId = (string) Str::uuid();
        $limit = max(1, min((int) $this->option('limit'), 20));
        $raw = (bool) $this->option('raw');
        $toolOnly = (bool) $this->option('tool-only');
        $currentDate = $this->currentDate();
        $sourceSubset = $this->sourceSubset();
        $language = $this->languageFilter();

        ContentSourceAgentRunContext::start($runId, $this->getName() ?? self::class, $toolOnly, $currentDate, $limit);
        Log::channel('content_source_agent')->info('command.started', ContentSourceAgentRunContext::context([
            'raw' => $raw,
            'source_filter' => $sourceSubset,
            'language_filter' => $language,
        ]));

        if ($currentDate === null) {
            Log::channel('content_source_agent')->warning('command.invalid_today', ContentSourceAgentRunContext::context());

            return self::FAILURE;
        }

        if ($toolOnly) {
            return $this->handleToolOnly($searchService, $currentDate, $limit, $raw);
        }

        $prompt = trim((string) $this->argument('prompt'));
        if ($prompt === '') {
            Log::channel('content_source_agent')->warning('command.missing_prompt', ContentSourceAgentRunContext::context());
            $this->error('Prompt is required unless --tool-only is used.');
            $this->renderLogReference($raw);

            return self::FAILURE;
        }

        $availableSources = $searchService->availableSources($sourceSubset, $language);
        if ($availableSources === []) {
            Log::channel('content_source_agent')->warning('command.no_available_sources', ContentSourceAgentRunContext::context([
                'source_filter' => $sourceSubset,
                'language_filter' => $language,
            ]));
            $this->error('No active predefined content sources match the requested filters.');
            $this->renderLogReference($raw);

            return self::FAILURE;
        }

        $agentPrompt = ContentSourceSearchAgent::buildPrompt($prompt, $currentDate, $availableSources, $limit);
        ContentSourceAgentRunContext::setPrompt($prompt, $agentPrompt);
        Log::channel('content_source_agent')->info('agent.prompt_built', ContentSourceAgentRunContext::context([
            'sources_count' => count($availableSources),
            'source_filter' => $sourceSubset,
            'language_filter' => $language,
            'user_prompt_chars' => mb_strlen($prompt),
            'agent_prompt_chars' => mb_strlen($agentPrompt),
        ]));

        $startedAt = microtime(true);
        try {
            Log::channel('content_source_agent')->info('agent.call.started', ContentSourceAgentRunContext::context([
                'agent_slug' => ContentSourceSearchAgent::slug(),
                'model' => ContentSourceSearchAgent::modelName(),
                'tool_required' => true,
                'parallel_tool_calls' => true,
            ]));

            $agent = ContentSourceSearchAgent::make(ContentSourceSearchAgent::slug())
                ->withSourceCatalog($availableSources)
                ->toolRequired()
                ->parallelToolCalls(true);

            $payload = $this->normalizeAgentResponse($agent->respond($agentPrompt), $prompt, $currentDate);

            Log::channel('content_source_agent')->info('agent.call.completed', ContentSourceAgentRunContext::context([
                'elapsed_ms' => round((microtime(true) - $startedAt) * 1000, 2),
                'items_count' => count(Arr::wrap($payload['items'] ?? [])),
                'warnings_count' => count(Arr::wrap($payload['warnings'] ?? [])),
                'insert_ready_count' => (int) ($payload['insert_ready_count'] ?? 0),
            ]));
        } catch (Throwable $exception) {
            Log::channel('content_source_agent')->error('agent.call.failed', ContentSourceAgentRunContext::context([
                'elapsed_ms' => round((microtime(true) - $startedAt) * 1000, 2),
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
                'is_length_finish_reason' => str_contains(mb_strtolower($exception->getMessage()), 'finish reason: length'),
            ]));

            $this->error('Content source agent failed: '.$exception->getMessage());
            if (str_contains(mb_strtolower($exception->getMessage()), 'finish reason: length')) {
                $this->warn('The model hit its output token limit. Try a smaller --limit or a narrower prompt; the full trace is in the agent log.');
            }
            $this->renderLogReference($raw);

            return self::FAILURE;
        }

        $this->renderPayload($payload, $raw);
        Log::channel('content_source_agent')->info('command.completed', ContentSourceAgentRunContext::context(['exit_code' => self::SUCCESS]));
        $this->renderLogReference($raw);

        return self::SUCCESS;
    }

    private function handleToolOnly(ContentSourceAgentSearchService $searchService, string $currentDate, int $limit, bool $raw): int
    {
        $source = trim((string) $this->option('source'));
        $query = trim((string) $this->option('query'));
        $fromDate = trim((string) $this->option('from'));
        $toDate = trim((string) ($this->option('to') ?: $currentDate));

        Log::channel('content_source_agent')->info('tool_only.started', ContentSourceAgentRunContext::context([
            'source' => $source,
            'query' => $query,
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ]));

        if ($source === '' || $query === '' || $fromDate === '') {
            Log::channel('content_source_agent')->warning('tool_only.missing_required_options', ContentSourceAgentRunContext::context([
                'has_source' => $source !== '',
                'has_query' => $query !== '',
                'has_from' => $fromDate !== '',
            ]));
            $this->error('--tool-only requires --source, --query, and --from.');
            $this->renderLogReference($raw);

            return self::FAILURE;
        }

        $result = $searchService->search($source, $query, $fromDate, $toDate, $limit);
        $payload = $this->payloadFromToolResult($result, $query, $currentDate);
        $this->renderPayload($payload, $raw);

        $exitCode = (bool) ($result['ok'] ?? false) ? self::SUCCESS : self::FAILURE;
        Log::channel('content_source_agent')->info('tool_only.completed', ContentSourceAgentRunContext::context([
            'exit_code' => $exitCode,
            'ok' => (bool) ($result['ok'] ?? false),
            'items_count' => (int) ($result['items_count'] ?? 0),
            'errors' => Arr::wrap($result['errors'] ?? []),
        ]));
        $this->renderLogReference($raw);

        return $exitCode;
    }

    /** @return array<int, string> */
    private function sourceSubset(): array
    {
        $value = $this->option('sources');
        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        return collect(preg_split('/[,|]/', $value) ?: [])
            ->map(fn (mixed $source): string => trim((string) $source))
            ->filter()
            ->values()
            ->all();
    }

    private function languageFilter(): ?string
    {
        $value = $this->option('language');
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        return mb_strtolower(trim($value));
    }

    private function currentDate(): ?string
    {
        $today = $this->option('today');
        $timezone = (string) config('app.timezone', 'Europe/Rome');

        try {
            if (is_string($today) && trim($today) !== '') {
                return CarbonImmutable::parse(trim($today), $timezone)->toDateString();
            }

            return CarbonImmutable::now($timezone)->toDateString();
        } catch (Throwable) {
            $this->error('--today must be a valid date, preferably YYYY-MM-DD.');

            return null;
        }
    }

    /** @return array<string, mixed> */
    private function normalizeAgentResponse(mixed $response, string $request, string $currentDate): array
    {
        if ($response instanceof MessageContract && method_exists($response, 'getContent')) {
            $response = $response->getContent();
        }

        if ($response instanceof DataModel && method_exists($response, 'toArray')) {
            $response = $response->toArray();
        }

        if (is_array($response)) {
            return $response;
        }

        if (is_string($response)) {
            try {
                $decoded = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
                if (is_array($decoded)) {
                    return $decoded;
                }
            } catch (JsonException $exception) {
                Log::channel('content_source_agent')->warning('agent.response_invalid_json', ContentSourceAgentRunContext::context([
                    'exception' => $exception::class,
                    'message' => $exception->getMessage(),
                    'response_chars' => mb_strlen($response),
                ]));
            }
        }

        Log::channel('content_source_agent')->warning('agent.response_fallback_payload_used', ContentSourceAgentRunContext::context([
            'response_type' => get_debug_type($response),
        ]));

        return [
            'request' => $request,
            'current_date' => $currentDate,
            'date_range' => ['from_date' => $currentDate, 'to_date' => $currentDate],
            'sources_searched' => [],
            'insert_ready_count' => 0,
            'items' => [],
            'warnings' => ['agent_response_was_not_valid_schema_json'],
        ];
    }

    /** @param array<string, mixed> $result @return array<string, mixed> */
    private function payloadFromToolResult(array $result, string $request, string $currentDate): array
    {
        $source = is_array($result['source'] ?? null) ? $result['source'] : null;
        $items = is_array($result['items'] ?? null) ? $result['items'] : [];
        $errors = collect(Arr::wrap($result['errors'] ?? []))->map(fn (mixed $error): string => (string) $error)->filter()->values()->all();

        return [
            'request' => $request,
            'current_date' => $currentDate,
            'date_range' => [
                'from_date' => (string) ($result['from_date'] ?? $currentDate),
                'to_date' => (string) ($result['to_date'] ?? $currentDate),
            ],
            'sources_searched' => $source !== null ? [[
                'source_key' => (string) ($source['source_key'] ?? ''),
                'name' => (string) ($source['name'] ?? ''),
                'provider' => (string) ($source['provider'] ?? ''),
            ]] : [],
            'insert_ready_count' => count($items),
            'items' => $items,
            'warnings' => $errors,
        ];
    }

    /** @param array<string, mixed> $payload */
    private function renderPayload(array $payload, bool $raw): void
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($raw) {
            $this->line($json !== false ? $json : '{}');

            return;
        }

        $this->line('Request: '.(string) ($payload['request'] ?? '-'));
        $this->line('Current date passed to agent: '.(string) ($payload['current_date'] ?? '-'));
        $this->line('Date range: '.(string) data_get($payload, 'date_range.from_date', '-').' ? '.(string) data_get($payload, 'date_range.to_date', '-'));
        $this->line('Insert-ready items: '.(string) ($payload['insert_ready_count'] ?? count(Arr::wrap($payload['items'] ?? []))));

        $items = collect(Arr::wrap($payload['items'] ?? []))->map(fn (mixed $item): array => $this->tableRow(is_array($item) ? $item : []))->all();
        if ($items !== []) {
            $this->table(['Published', 'Source', 'Title', 'External ID', 'URL'], $items);
        } else {
            $this->warn('No insert-ready items returned.');
        }

        $warnings = collect(Arr::wrap($payload['warnings'] ?? []))->filter()->values()->all();
        if ($warnings !== []) {
            $this->warn('Warnings: '.implode(', ', $warnings));
        }

        $this->newLine();
        $this->line('JSON schema payload:');
        $this->line($json !== false ? $json : '{}');
    }

    private function renderLogReference(bool $raw): void
    {
        if ($raw) {
            return;
        }

        $this->newLine();
        $this->line('Log run id: '.(ContentSourceAgentRunContext::runId() ?? '-'));
        $this->line('Agent log: '.storage_path('logs/content-source-agent.log'));
        $this->line('News retrieval log: '.storage_path('logs/news-retrieval.log'));
    }

    /** @param array<string, mixed> $item @return array<int, string> */
    private function tableRow(array $item): array
    {
        return [
            (string) ($item['published_at'] ?? '-'),
            Str::limit((string) ($item['source_name'] ?? $item['source_key'] ?? '-'), 24),
            Str::limit((string) ($item['title'] ?? '-'), 72),
            (string) ($item['external_id'] ?? '-'),
            Str::limit((string) ($item['source_url'] ?? '-'), 80),
        ];
    }
}
