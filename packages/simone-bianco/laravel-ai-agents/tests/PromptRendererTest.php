<?php

declare(strict_types=1);

use SimoneBianco\LaravelAiAgents\Contracts\ScopeBindingResolver;
use SimoneBianco\LaravelAiAgents\DTOs\ScopeBindingSnapshot;
use SimoneBianco\LaravelAiAgents\Registries\ScopeResolverRegistry;
use SimoneBianco\LaravelAiAgents\Registries\VariableProviderRegistry;
use SimoneBianco\LaravelAiAgents\Services\AgentPromptRenderer;
use SimoneBianco\LaravelAiAgents\Support\AgentLogger;
use SimoneBianco\LaravelAiAgents\Support\AgentRunContext;

it('substitutes {{ project.1.name }} with resolved scope field', function () {
    $resolver = new class implements ScopeBindingResolver {
        public function resolve(string $scopeKey, array $metadata): ScopeBindingSnapshot
        {
            return new ScopeBindingSnapshot(true, ['name' => 'Alpha'], 'Alpha Project');
        }

        public function searchForUi(string $query, int $limit = 20): array { return []; }

        public function suggestedVariableFields(): array { return ['name']; }
    };

    $scopeReg = new ScopeResolverRegistry();
    $scopeReg->register('project', $resolver);
    $varReg = new VariableProviderRegistry();

    $logger = new AgentLogger();

    // Stub AiAgent-like object exposing the required methods/properties.
    $agent = new class {
        public string $slug = 'test-agent';
        public string $system_prompt = 'Hello {{ project.1.name }}';
        public function scopeBindings()
        {
            $bindingsQuery = new class {
                public function orderBy(string $col) { return $this; }
                public function get() {
                    return collect([
                        (object) [
                            'scope_type' => 'project',
                            'scope_key' => 'p1',
                            'metadata' => [],
                            'position' => 1,
                        ],
                    ]);
                }
            };
            return $bindingsQuery;
        }
        public function variableBindings()
        {
            return new class {
                public function get() { return collect([]); }
            };
        }
    };

    $renderer = new AgentPromptRenderer($scopeReg, $varReg, $logger);

    // @phpstan-ignore-next-line — duck typing for test
    $result = $renderer->render($agent, new AgentRunContext([]));

    expect($result)->toBe('Hello Alpha');
})->skip('Requires booted Laravel + DB for real AiAgent model — smoke placeholder.');
