<?php

declare(strict_types=1);

namespace App\Console\Commands\Ai;

use Illuminate\Console\Command;
use LarAgent\Facades\Context;

final class AgentChatRemoveCommand extends Command
{
    use ResolvesConfiguredAgentClasses;

    protected $signature = 'agent:chat:remove {agent : The name of the agent to remove chat history for}';

    protected $description = 'Remove chat history for a specific agent using configured LarAgent namespaces';

    public function handle(): int
    {
        $agentName = (string) $this->argument('agent');

        if ($this->findConfiguredAgentClass($agentName) === null) {
            $this->error("Agent not found: {$agentName}");

            return self::FAILURE;
        }

        $removed = Context::named($agentName)->removeAllChats();

        if ($removed > 0) {
            $this->info("Successfully removed {$removed} chat histories for agent: {$agentName}");
        } else {
            $this->info("No chat histories found for agent: {$agentName}");
        }

        return self::SUCCESS;
    }
}
