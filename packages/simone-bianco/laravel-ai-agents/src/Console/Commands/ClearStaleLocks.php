<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use SimoneBianco\LaravelAiAgents\Models\AiAgent;

class ClearStaleLocks extends Command
{
    protected $signature = 'agents:clear-stale-locks {--max-age=15 : Maximum age in minutes before a lock is considered stale}';

    protected $description = 'Reset active_runs_count for agents with stale locks';

    public function handle(): int
    {
        $maxAge = (int) $this->option('max-age');
        $cutoff = now()->subMinutes($maxAge);

        $count = AiAgent::where('active_runs_count', '>', 0)
            ->where('updated_at', '<', $cutoff)
            ->update(['active_runs_count' => 0]);

        $this->info("Cleared stale locks for {$count} agent(s).");

//        Log::channel((string) config('ai-agents.log_channel', 'agents'))
//            ->info('agents.stale_locks_cleared', [
//                'count' => $count,
//                'max_age_minutes' => $maxAge,
//            ]);

        return Command::SUCCESS;
    }
}
