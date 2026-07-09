<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Countdown;
use App\Services\Doomsday\NewsUpdater\ContentSourceNewsRefreshService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final class RefreshContentSourcesCommand extends Command
{
    protected $signature = 'countdowns:refresh-content-sources
        {slug : Countdown slug.}
        {--query= : Optional keyword/query override for filtering source items.}
        {--days=30 : Search window in days, from 1 to 120.}
        {--limit=5 : Maximum new content items accepted, from 1 to 20.}
        {--insert : Persist accepted content items. Default is dry-run.}';

    protected $description = 'Refresh generic content sources such as YouTube channels for a countdown and optionally insert matching items as news.';

    public function handle(ContentSourceNewsRefreshService $service): int
    {
        $slug = trim((string) $this->argument('slug'));
        $countdown = Countdown::query()->where('slug', $slug)->first();

        if (! $countdown instanceof Countdown) {
            $this->error('Countdown not found: ' . $slug);

            return self::FAILURE;
        }

        $query = $this->option('query');
        $query = is_string($query) && trim($query) !== '' ? trim($query) : null;
        $insert = (bool) $this->option('insert');

        $result = $service->run(
            countdown: $countdown,
            query: $query,
            daysBack: (int) $this->option('days'),
            limit: (int) $this->option('limit'),
            insert: $insert,
        );

        $this->line('Mode: ' . strtoupper((string) $result['mode']));
        $this->line('Countdown: ' . $result['countdown_slug']);
        $this->line('Query: ' . ($result['query'] ?: 'source/countdown keywords'));
        $this->line(sprintf(
            'Sources: %d | Fetched: %d | New: %d | Duplicates: %d | Skipped: %d | Inserted: %d',
            (int) $result['sources_count'],
            (int) $result['fetched_count'],
            (int) $result['new_count'],
            (int) $result['duplicate_count'],
            (int) $result['skipped_count'],
            (int) $result['inserted_count'],
        ));

        $rows = collect($result['rows'])
            ->map(function (array $row): array {
                $candidate = (array) $row['candidate'];

                return [
                    'status' => (string) $row['status'],
                    'published' => Str::before((string) ($candidate['published_at'] ?? ''), ' ') ?: '-',
                    'source' => Str::limit((string) ($row['source_name'] ?? $candidate['source_name'] ?? '-'), 22),
                    'type' => (string) ($candidate['content_type'] ?? '-'),
                    'title' => Str::limit((string) ($candidate['title'] ?? '-'), 64),
                    'reason/url' => Str::limit((string) $row['reason'] . ' | ' . (string) ($candidate['source_url'] ?? ''), 92),
                ];
            })
            ->all();

        if ($rows !== []) {
            $this->table(['Status', 'Published', 'Source', 'Type', 'Title', 'Reason / URL'], $rows);
        }

        if (! $insert) {
            $this->warn('Dry-run only: no database rows were created. Pass --insert to persist accepted content.');
        }

        if ((int) Arr::get($result, 'sources_count', 0) === 0) {
            $this->warn('No active content sources are linked/global for this countdown.');
        }

        return self::SUCCESS;
    }
}
