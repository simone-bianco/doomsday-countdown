<?php

declare(strict_types=1);

namespace App\Console\Commands\Ai;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class MakeAgentCommand extends Command
{
    protected $signature = 'make:agent {name : The name of the agent}';

    protected $description = 'Create a new classic LarAgent agent class under app/Ai/Agents';

    public function handle(): int
    {
        $name = (string) $this->argument('name');

        if (! preg_match('/^[A-Z][a-zA-Z0-9]*$/', $name)) {
            $this->error('Invalid agent name. Agent name must be a valid PHP class name (PascalCase).');

            return self::FAILURE;
        }

        $agentsDir = app_path('Ai/Agents');
        $filePath = $agentsDir.'/'.$name.'.php';

        if (File::exists($filePath)) {
            $this->error('Agent already exists: '.$name);

            return self::FAILURE;
        }

        File::ensureDirectoryExists($agentsDir);
        File::put($filePath, $this->stub($name));

        $this->info('Agent created successfully: '.$name);
        $this->line('Location: '.$filePath);

        return self::SUCCESS;
    }

    private function stub(string $name): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\\Ai\\Agents;

final class {$name} extends RotableOpenAiAgent
{
    protected \$model = 'gpt-4.1-nano';

    protected \$history = 'in_memory';

    protected \$provider = 'default';

    protected \$tools = [];

    public function instructions()
    {
        return "Define your agent's instructions here.";
    }

    public function prompt(\$message)
    {
        return \$message;
    }
}
PHP;
    }
}
