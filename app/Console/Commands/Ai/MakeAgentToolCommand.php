<?php

declare(strict_types=1);

namespace App\Console\Commands\Ai;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class MakeAgentToolCommand extends Command
{
    protected $signature = 'make:agent:tool {name : The name of the agent tool}';

    protected $description = 'Create a new LarAgent tool class under app/Ai/Tools';

    public function handle(): int
    {
        $name = (string) $this->argument('name');

        if (! preg_match('/^[A-Z][a-zA-Z0-9]*$/', $name)) {
            $this->error('Invalid tool name. Tool name must be a valid PHP class name (PascalCase).');

            return self::FAILURE;
        }

        $toolsDir = app_path('Ai/Tools');
        $filePath = $toolsDir.'/'.$name.'.php';

        if (File::exists($filePath)) {
            $this->error('Agent tool already exists: '.$name);

            return self::FAILURE;
        }

        File::ensureDirectoryExists($toolsDir);
        File::put($filePath, $this->stub($name));

        $this->info('Agent tool created successfully: '.$name);
        $this->line('Location: '.$filePath);

        return self::SUCCESS;
    }

    private function stub(string $name): string
    {
        $toolName = Str::snake($name);

        return <<<PHP
<?php

declare(strict_types=1);

namespace App\\Ai\\Tools;

use LarAgent\\Core\\Contracts\\DataModel;
use LarAgent\\Tool;

final class {$name} extends Tool
{
    protected string \$name = '{$toolName}';

    protected string \$description = 'Tool description here';

    protected array \$properties = [
        'example_param' => [
            'type' => 'string',
            'description' => 'Parameter description',
        ],
    ];

    protected array \$required = ['example_param'];

    protected array \$metaData = [];

    protected function handle(array|DataModel \$input): mixed
    {
        return 'Result';
    }
}
PHP;
    }
}
