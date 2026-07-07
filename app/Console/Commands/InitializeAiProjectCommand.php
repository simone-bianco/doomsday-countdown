<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SimoneBianco\LaravelKeyRotator\Models\RotableApiKey;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

final class InitializeAiProjectCommand extends Command
{
    protected $signature = 'initialize-ai-project';

    protected $description = 'Configure the AI starter .env files, admin user, and optional OpenAI key.';

    public function handle(): int
    {
        $appName = text('Application name', default: (string) config('app.name'), required: true);
        $appUrl = text('Application URL', default: (string) config('app.url'), required: true);
        $backofficePath = trim(text('Backoffice path', default: (string) config('ai-starter.backoffice_path'), required: true), '/');
        $openAiKey = password('OpenAI API key (optional)');
        $openAiModel = text('OpenAI model', default: (string) config('services.openai.model'), required: true);
        $adminEmail = text('Admin email', default: 'admin@example.com', required: true);
        $adminName = text('Admin name', default: 'Admin', required: true);
        $adminPassword = password('Admin password (optional; generated if empty)');

        $this->writeEnvFile(base_path('.env'), [
            'APP_NAME' => $appName,
            'APP_URL' => $appUrl,
            'AI_STARTER_BACKOFFICE_PATH' => $backofficePath,
            'OPENAI_API_KEY' => $openAiKey,
            'OPENAI_MODEL' => $openAiModel,
        ]);

        $this->writeEnvFile(base_path('.env.example'), [
            'APP_NAME' => $appName,
            'APP_URL' => $appUrl,
            'AI_STARTER_BACKOFFICE_PATH' => $backofficePath,
            'OPENAI_API_KEY' => '',
            'OPENAI_MODEL' => $openAiModel,
        ]);

        Artisan::call('config:clear');

        if (confirm('Create or update the admin user?', default: true)) {
            $plainPassword = $adminPassword !== '' ? $adminPassword : Str::password(18);

            User::query()->updateOrCreate(
                ['email' => $adminEmail],
                ['name' => $adminName, 'password' => Hash::make($plainPassword)],
            );

            $this->info("Admin user ready: {$adminEmail}");
            if ($adminPassword === '') {
                $this->warn("Generated admin password: {$plainPassword}");
            }
        }

        if ($openAiKey !== '') {
            $this->registerInitializerOpenAiKey($openAiKey);

            $this->info('OpenAI key registered in key rotator.');
        }

        $this->info('AI project initialized.');

        return self::SUCCESS;
    }

    private function registerInitializerOpenAiKey(string $openAiKey): void
    {
        $existing = RotableApiKey::query()
            ->where('service', 'openai')
            ->get()
            ->first(fn (RotableApiKey $key): bool => $this->isInitializerOpenAiKey($key));

        $extraData = array_merge(
            is_array($existing?->extra_data) ? $existing->extra_data : [],
            ['label' => 'Initializer OpenAI key', 'source' => 'initialize-ai-project'],
        );

        if ($existing instanceof RotableApiKey) {
            $existing->fill([
                'key' => $openAiKey,
                'extra_data' => $extraData,
            ]);
            $existing->save();

            return;
        }

        RotableApiKey::query()->create([
            'service' => 'openai',
            'key' => $openAiKey,
            'base_limit_type' => 'unlimited',
            'free_limit_type' => 'none',
            'extra_data' => $extraData,
            'is_active' => true,
            'is_depleted' => false,
        ]);
    }

    private function isInitializerOpenAiKey(RotableApiKey $key): bool
    {
        $extraData = is_array($key->extra_data) ? $key->extra_data : [];

        return data_get($extraData, 'source') === 'initialize-ai-project'
            || data_get($extraData, 'label') === 'Initializer OpenAI key';
    }

    /**
     * @param array<string, string> $values
     */
    private function writeEnvFile(string $path, array $values): void
    {
        $content = file_exists($path) ? (string) file_get_contents($path) : '';

        foreach ($values as $key => $value) {
            $line = $key . '=' . $this->formatEnvValue($value);
            $pattern = '/^' . preg_quote($key, '/') . '=.*$/m';
            $content = preg_match($pattern, $content) === 1
                ? preg_replace($pattern, $line, $content) ?? $content
                : rtrim($content) . PHP_EOL . $line . PHP_EOL;
        }

        file_put_contents($path, $content);
    }

    private function formatEnvValue(string $value): string
    {
        if ($value === '') {
            return '';
        }

        $escaped = str_replace('"', '\\"', $value);

        return '"' . $escaped . '"';
    }
}
