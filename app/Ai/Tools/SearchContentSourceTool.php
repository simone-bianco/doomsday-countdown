<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Services\Doomsday\NewsUpdater\ContentSourceAgentSearchService;
use App\Support\ContentSourceAgentRunContext;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use LarAgent\Core\Contracts\DataModel;
use LarAgent\Tool;
use Throwable;

final class SearchContentSourceTool extends Tool
{
    protected string $name = 'search_content_source';

    protected string $description = 'Search one externally provided active content source for recent items that can fill News database fields.';

    protected array $required = ['source', 'query', 'from_date', 'to_date', 'limit'];

    protected array $metaData = [];

    /** @var array<int, array<string, mixed>> */
    private array $sourceCatalog;

    /** @var array<int, string> */
    private array $allowedSourceKeys;

    /** @param array<int, array<string, mixed>> $sourceCatalog */
    public function __construct(private readonly ContentSourceAgentSearchService $searchService, array $sourceCatalog = [])
    {
        $this->sourceCatalog = array_values(array_filter($sourceCatalog, static fn (mixed $source): bool => is_array($source)));
        $this->allowedSourceKeys = collect($this->sourceCatalog)
            ->pluck('source_key')
            ->map(fn (mixed $key): string => mb_strtolower(trim((string) $key)))
            ->filter()
            ->values()
            ->all();

        $sourceProperty = [
            'type' => 'string',
            'description' => 'Source key from AVAILABLE_SOURCES_JSON. Do not invent arbitrary source names.',
        ];

        $sourceKeys = $this->allowedSourceKeys !== [] ? $this->allowedSourceKeys : $this->searchService->allowedSourceKeys();
        if ($sourceKeys !== []) {
            $sourceProperty['enum'] = $sourceKeys;
        }

        $this->properties = [
            'source' => $sourceProperty,
            'query' => [
                'type' => 'string',
                'description' => 'Compact search query. Use meaningful topic terms, e.g. taiwan, blockade, invasion.',
            ],
            'from_date' => [
                'type' => 'string',
                'description' => 'Inclusive start date in YYYY-MM-DD. Resolve relative dates from CURRENT_DATE.',
            ],
            'to_date' => [
                'type' => 'string',
                'description' => 'Inclusive end date in YYYY-MM-DD. Usually CURRENT_DATE for recent searches.',
            ],
            'limit' => [
                'type' => 'integer',
                'description' => 'Maximum candidate items to return, from 1 to 20.',
            ],
        ];

        parent::__construct();
    }

    /** @return array<string, mixed>|null */
    private function catalogSource(string $source): ?array
    {
        $needle = mb_strtolower(trim($source));
        if ($needle === '') {
            return null;
        }

        foreach ($this->sourceCatalog as $catalogSource) {
            $values = [
                $catalogSource['source_key'] ?? null,
                $catalogSource['name'] ?? null,
                $catalogSource['external_id'] ?? null,
            ];

            foreach ($values as $value) {
                if (mb_strtolower(trim((string) $value)) === $needle) {
                    return $catalogSource;
                }
            }
        }

        return null;
    }

    /** @return array<string, mixed> */
    protected function handle(array|DataModel $input): array
    {
        $payload = $input instanceof DataModel && method_exists($input, 'toArray')
            ? $input->toArray()
            : $input;

        $source = (string) ($payload['source'] ?? '');
        Log::channel('content_source_agent')->info('tool.search_content_source.started', ContentSourceAgentRunContext::context([
            'tool' => $this->name,
            'source' => $source,
            'query' => (string) ($payload['query'] ?? ''),
            'from_date' => (string) ($payload['from_date'] ?? ''),
            'to_date' => (string) ($payload['to_date'] ?? ''),
            'limit' => (int) ($payload['limit'] ?? 10),
        ]));

        $catalogSource = $this->catalogSource($source);
        if ($this->sourceCatalog !== [] && $catalogSource === null) {
            Log::channel('content_source_agent')->warning('tool.search_content_source.forbidden_source', ContentSourceAgentRunContext::context([
                'tool' => $this->name,
                'source' => $source,
                'allowed_source_keys' => $this->allowedSourceKeys,
            ]));

            return [
                'ok' => false,
                'source' => null,
                'query' => (string) ($payload['query'] ?? ''),
                'from_date' => (string) ($payload['from_date'] ?? ''),
                'to_date' => (string) ($payload['to_date'] ?? ''),
                'limit' => (int) ($payload['limit'] ?? 10),
                'items_count' => 0,
                'items' => [],
                'errors' => ['source_not_in_command_catalog'],
            ];
        }

        try {
            $result = $catalogSource !== null
                ? $this->searchService->searchCatalogSource(
                    source: $catalogSource,
                    query: (string) ($payload['query'] ?? ''),
                    fromDate: (string) ($payload['from_date'] ?? ''),
                    toDate: (string) ($payload['to_date'] ?? ''),
                    limit: (int) ($payload['limit'] ?? 10),
                )
                : $this->searchService->search(
                    source: (string) ($payload['source'] ?? ''),
                    query: (string) ($payload['query'] ?? ''),
                    fromDate: (string) ($payload['from_date'] ?? ''),
                    toDate: (string) ($payload['to_date'] ?? ''),
                    limit: (int) ($payload['limit'] ?? 10),
                );
        } catch (Throwable $exception) {
            Log::channel('content_source_agent')->error('tool.search_content_source.failed', ContentSourceAgentRunContext::context([
                'tool' => $this->name,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]));

            return [
                'ok' => false,
                'source' => null,
                'query' => (string) ($payload['query'] ?? ''),
                'from_date' => (string) ($payload['from_date'] ?? ''),
                'to_date' => (string) ($payload['to_date'] ?? ''),
                'limit' => (int) ($payload['limit'] ?? 10),
                'items_count' => 0,
                'items' => [],
                'errors' => ['tool_exception'],
            ];
        }

        Log::channel('content_source_agent')->info('tool.search_content_source.completed', ContentSourceAgentRunContext::context([
            'tool' => $this->name,
            'ok' => (bool) ($result['ok'] ?? false),
            'items_count' => (int) ($result['items_count'] ?? 0),
            'errors' => Arr::wrap($result['errors'] ?? []),
        ]));

        return $result;
    }
}
