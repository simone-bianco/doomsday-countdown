<?php

declare(strict_types=1);

namespace App\Ai\Agents;

class DemoAgent extends RotableOpenAiAgent
{
    protected $temperature = 0.2;

    protected $maxCompletionTokens = 800;

    protected $parallelToolCalls = false;

    public function __construct($key, bool $usesUserId = false, ?string $group = null)
    {
        $this->model = (string) config('ai-starter.demo_agent.model', config('services.openai.model', 'gpt-4o-mini'));
        $this->apiUrl = rtrim((string) config('services.openai.base_url', 'https://api.openai.com/v1'), '/');

        parent::__construct($key, $usesUserId, $group);
    }

    public static function slug(): string
    {
        return (string) config('ai-starter.demo_agent.slug', 'quickstart-assistant');
    }

    public function instructions()
    {
        return 'You are the default AI assistant for this Laravel quickstart. Reply concisely and mention that the request passed through the starter demo agent.';
    }

    public function prompt($message)
    {
        return $message;
    }
}
