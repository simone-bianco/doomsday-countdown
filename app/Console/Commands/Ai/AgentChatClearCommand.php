<?php

declare(strict_types=1);

namespace App\Console\Commands\Ai;

use Illuminate\Console\Command;
use LarAgent\Facades\Context;

final class AgentChatClearCommand extends Command
{
    use ResolvesConfiguredAgentClasses;

    protected $signature = 'agent:chat:clear {agent : The name of the agent to clear chat history for}';

    protected $description = 'Clear chat history for a specific agent using configured LarAgent namespaces';

    public function handle(): int
    {
        $agentName = (string) $this->argument('agent');

        if ($this->findConfiguredAgentClass($agentName) === null) {
            $this->error("Agent not found: {$agentName}");

            return self::FAILURE;
        }

        Context::named($agentName)->clearAllChats();

        $this->info("Successfully cleared chat history for agent: {$agentName}");

        return self::SUCCESS;
    }
}
