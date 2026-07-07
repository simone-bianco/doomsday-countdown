# Laravel AI Agents

Database-driven AI agent definitions for Laravel, built on LarAgent.

This starter uses the package to keep reusable agent definitions in the `ai_agents` tables. The starter home demo at `POST /agent/demo` intentionally calls OpenAI through the local `App\Services\Starter\DemoAgentService` JSON endpoint so the quickstart does not require an optional chat-history package.

## What it provides

- `SimoneBianco\LaravelAiAgents\Models\AiAgent` and related models for agent definitions, revisions, tools, scope bindings, and variables.
- `AgentDefinitionService` for create, update, duplicate, delete, and listing workflows.
- `AgentInstantiationService` and `AgentPlaygroundRunner` for building and running agents by slug.
- Registries for tool factories, scope resolvers, variable providers, and response hooks.
- Optional key rotation integration through `simone-bianco/laravel-key-rotator`.

## Install in a host Laravel app

For a normal package install:

```bash
composer require simone-bianco/laravel-ai-agents
php artisan vendor:publish --tag=laravel-ai-agents-config
php artisan migrate
```

For this starter or another local path-repository app, use the local package path from the root `composer.json`:

```json
{
  "require": {
    "simone-bianco/laravel-ai-agents": "@dev"
  },
  "repositories": [
    {"type": "path", "url": "packages/simone-bianco/laravel-ai-agents", "options": {"symlink": true}}
  ]
}
```

The service provider auto-loads package migrations from `database/migrations` and registers `config/ai-agents.php`.

## Minimal agent definition

```php
use SimoneBianco\LaravelAiAgents\DTOs\AgentDefinitionData;
use SimoneBianco\LaravelAiAgents\Enums\AgentResponseFormat;
use SimoneBianco\LaravelAiAgents\Enums\AgentStreamingMode;
use SimoneBianco\LaravelAiAgents\Services\AgentDefinitionService;

$agent = app(AgentDefinitionService::class)->create(new AgentDefinitionData(
    id: null,
    name: 'Quickstart Assistant',
    slug: 'quickstart-assistant',
    description: 'Default starter assistant.',
    type: 'chat',
    scope: 'global',
    provider: 'openai',
    model: 'gpt-4o-mini',
    system_prompt: 'You are a concise starter assistant.',
    temperature: 0.2,
    top_p: null,
    max_completion_tokens: 800,
    parallel_tool_calls: false,
    developer_role_for_instructions: false,
    response_format: AgentResponseFormat::RawText,
    response_schema: null,
    streaming_mode: AgentStreamingMode::Sync,
    history_driver: null,
    metadata: ['starter' => true],
));
```

## Run an agent by slug

```php
use SimoneBianco\LaravelAiAgents\Services\AgentPlaygroundRunner;

$result = app(AgentPlaygroundRunner::class)->run(
    agentSlug: 'quickstart-assistant',
    chatKey: 'demo-session',
    message: 'Hello from the starter',
);
```

The runner returns an array containing `response`, `usage`, and `elapsed_ms` for synchronous runs. Streaming is available through `AgentPlaygroundRunner::stream(...)`.

## Key rotation

Use the local key-rotator package when provider calls need managed OpenAI keys. In this starter the concrete rotator is `App\KeyRotators\Openai\OpenAIKeyRotator`:

```php
$rotator = OpenAIKeyRotator::make()->pickKey()->injectKey();
// call the provider...
$rotator->registerUsage($totalTokens);
```

Keys are stored in `rotable_api_keys`. When `KEY_ROTATOR_ENCRYPT_KEYS=true`, plaintext keys must not be logged or stored in metadata.

## Configuration

`config/ai-agents.php` controls the log channel, recursion depth, stale lock age, dynamic tool timeout, and progress labels. Publish the config in a host app, or edit the local config while developing inside this starter.

## Optional history driver

The package suggests `simone-bianco/laravel-page-chat` only when you use `PageChatStorageDriver` for conversation history. The starter demo sets package-created agents with `history_driver: null` and uses a direct JSON debug call, so that optional package is not required for the quickstart page.

## Starter quickstart

```bash
composer install
npm install
php artisan migrate
php artisan initialize-ai-project
php artisan serve
npm run dev
```

Then open `/`, use the language switcher, log in with the initialized admin user, and use the Agent debug panel. Backoffice CRUD is under the configurable `AI_STARTER_BACKOFFICE_PATH` path.

## Troubleshooting

- Run `php artisan route:list` to confirm `agent.demo` and `backoffice.*` routes.
- Run `php artisan form-bridge:generate` after changing `app/Data/*` DTOs.
- Run `php artisan typescript:transform` after changing exported PHP types.
- Do not expose OpenAI plaintext keys in UI, logs, docs, or `extra_data`.
