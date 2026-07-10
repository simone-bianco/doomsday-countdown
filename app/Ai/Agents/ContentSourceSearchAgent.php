<?php

declare(strict_types=1);

namespace App\Ai\Agents;

use App\Ai\Tools\SearchContentSourceTool;
use App\Services\Doomsday\NewsUpdater\ContentSourceAgentSearchService;

final class ContentSourceSearchAgent extends RotableOpenAiAgent
{
    /** @var array<int, array<string, mixed>> */
    private array $sourceCatalog = [];

    /** @var array<int, string> */
    private array $allowedSourceKeys = [];

    protected $temperature = 0.1;

    protected $maxCompletionTokens = 8000;

    protected $parallelToolCalls = true;

    public function __construct($key, bool $usesUserId = false, ?string $group = null)
    {
        $this->model = self::modelName();
        $this->apiUrl = rtrim((string) config('services.openai.base_url', 'https://api.openai.com/v1'), '/');
        $this->responseSchema = self::outputSchema();

        parent::__construct($key, $usesUserId, $group);
    }

    public static function slug(): string
    {
        return 'content-source-search-agent';
    }

    public static function modelName(): string
    {
        return 'gpt-5.4-mini';
    }

    /** @param array<int, array<string, mixed>> $sourceCatalog */
    public function withSourceCatalog(array $sourceCatalog): self
    {
        $this->sourceCatalog = $sourceCatalog;
        $this->allowedSourceKeys = collect($sourceCatalog)
            ->pluck('source_key')
            ->map(fn (mixed $key): string => mb_strtolower(trim((string) $key)))
            ->filter()
            ->values()
            ->all();

        return $this;
    }

    /** @return array<int, string> */
    public function allowedSourceKeys(): array
    {
        return $this->allowedSourceKeys;
    }

    /** @param array<int, array<string, mixed>> $availableSources */
    public static function buildPrompt(string $userRequest, string $currentDate, array $availableSources, int $defaultLimit): string
    {
        $sourcesJson = json_encode($availableSources, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
CURRENT_DATE: {$currentDate}
DEFAULT_LIMIT_PER_TOOL_CALL: {$defaultLimit}
AVAILABLE_SOURCES_JSON:
{$sourcesJson}

USER_REQUEST:
{$userRequest}

Resolve any relative date expression from CURRENT_DATE. For example, "ultimo mese" means from one calendar month before CURRENT_DATE through CURRENT_DATE.
Use only source_key values from AVAILABLE_SOURCES_JSON when calling search_content_source.
AVAILABLE_SOURCES_JSON is supplied by the command from outside the agent; do not assume or invent sources outside that list.
Choose freely from all supplied sources unless USER_REQUEST names a specific source, source type, or language. For localized requests, prefer matching source language.
Call search_content_source for every source/query/date-range combination needed. You may call it multiple times and in parallel.
For broad requests, prefer one compact query per explicitly requested source. Do not expand into many synonym queries unless the first tool result is empty.
Keep the final JSON compact: include at most DEFAULT_LIMIT_PER_TOOL_CALL items total unless the user explicitly asks for fewer.
Return only data supported by tool results. Do not invent titles, URLs, IDs, hashes, dates, counts, or DB fields.
PROMPT;
    }

    /** @return array<string, mixed> */
    public static function outputSchema(): array
    {
        $nullableString = ['type' => ['string', 'null']];

        return [
            'title' => 'ContentSourceSearchResponse',
            'type' => 'object',
            'properties' => [
                'request' => ['type' => 'string'],
                'current_date' => ['type' => 'string'],
                'date_range' => [
                    'type' => 'object',
                    'properties' => [
                        'from_date' => ['type' => 'string'],
                        'to_date' => ['type' => 'string'],
                    ],
                    'required' => ['from_date', 'to_date'],
                ],
                'sources_searched' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'source_key' => ['type' => 'string'],
                            'name' => ['type' => 'string'],
                            'provider' => ['type' => 'string'],
                            'type' => ['type' => 'string'],
                            'language' => $nullableString,
                        ],
                        'required' => ['source_key', 'name', 'provider'],
                    ],
                ],
                'insert_ready_count' => ['type' => 'integer'],
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'source_key' => ['type' => 'string'],
                            'locale' => ['type' => 'string'],
                            'content_type' => ['type' => 'string'],
                            'title' => ['type' => 'string'],
                            'excerpt' => ['type' => 'string'],
                            'source_name' => ['type' => 'string'],
                            'source_url' => ['type' => 'string'],
                            'canonical_source_url' => ['type' => 'string'],
                            'canonical_source_hash' => ['type' => 'string'],
                            'external_provider' => ['type' => 'string'],
                            'external_id' => ['type' => 'string'],
                            'embed_url' => ['type' => 'string'],
                            'preview_image_url' => ['type' => 'string'],
                            'published_at' => $nullableString,
                        ],
                        'required' => [
                            'source_key',
                            'locale',
                            'content_type',
                            'title',
                            'excerpt',
                            'source_name',
                            'source_url',
                            'canonical_source_url',
                            'canonical_source_hash',
                            'external_provider',
                            'external_id',
                            'embed_url',
                            'preview_image_url',
                            'published_at',
                        ],
                    ],
                ],
                'warnings' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
            ],
            'required' => ['request', 'current_date', 'date_range', 'sources_searched', 'insert_ready_count', 'items', 'warnings'],
        ];
    }

    public function instructions()
    {
        return 'You are a read-only content source search agent. You use the search_content_source tool to retrieve real source candidates. You never fabricate DB-fill fields. You return the final response as the required JSON schema.';
    }

    public function prompt($message)
    {
        return $message;
    }

    public function registerTools()
    {
        return [new SearchContentSourceTool(
            searchService: app(ContentSourceAgentSearchService::class),
            sourceCatalog: $this->sourceCatalog,
        )];
    }
}
