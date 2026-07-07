<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Services;

use SimoneBianco\LaravelAiAgents\Models\AiAgent;
use SimoneBianco\LaravelAiAgents\Registries\ScopeResolverRegistry;
use SimoneBianco\LaravelAiAgents\Registries\VariableProviderRegistry;
use SimoneBianco\LaravelAiAgents\Support\AgentLogger;
use SimoneBianco\LaravelAiAgents\Support\AgentRunContext;

class AgentPromptRenderer
{
    public function __construct(
        private readonly ScopeResolverRegistry $scopeRegistry,
        private readonly VariableProviderRegistry $variableRegistry,
        private readonly AgentLogger $logger,
    ) {
    }

    public function render(AiAgent $agent, AgentRunContext $context): string
    {
        $start = microtime(true);
        $template = (string) ($agent->system_prompt ?? '');
        $agentSlug = (string) ($agent['slug'] ?? '');

        if ($template === '') {
            return '';
        }

        $replacements = [];
        $resolved = 0;
        $missing = 0;

        // Scope bindings
        $scopeBindings = $agent->scopeBindings()->orderBy('position')->get();
        $positionIndex = 0;
        foreach ($scopeBindings as $binding) {
            $positionIndex++;
            $scopeType = (string) $binding->scope_type;
            $scopeKey = (string) $binding->scope_key;
            $metadata = is_array($binding->metadata) ? $binding->metadata : [];

            if (! $this->scopeRegistry->has($scopeType)) {
                continue;
            }

            $resolver = $this->scopeRegistry->get($scopeType);
            $snapshot = $resolver->resolve($scopeKey, $metadata);

            foreach ($snapshot->fields as $fieldName => $fieldValue) {
                $valueString = is_scalar($fieldValue) ? (string) $fieldValue : json_encode($fieldValue);
                $positionalKey = "{{ {$scopeType}.{$positionIndex}.{$fieldName} }}";
                $keyedKey = "{{ {$scopeType}.key:{$scopeKey}.{$fieldName} }}";
                $directKey = "{{ {$scopeType}.{$scopeKey}.{$fieldName} }}";

                if ($snapshot->exists) {
                    $replacements[$positionalKey] = (string) $valueString;
                    $replacements[$keyedKey] = (string) $valueString;
                    $replacements[$directKey] = (string) $valueString;
                    $resolved++;
                } else {
                    $placeholder = "[missing:{$scopeType}.{$positionIndex}.{$fieldName}]";
                    $replacements[$positionalKey] = $placeholder;
                    $replacements[$keyedKey] = $placeholder;
                    $replacements[$directKey] = $placeholder;
                    $missing++;
                    $this->logger->variableMissing($agentSlug, $fieldName, $scopeType, $scopeKey);
                }
            }
        }

        // Variable bindings
        foreach ($agent->variableBindings()->get() as $varBinding) {
            $key = (string) $varBinding->key;
            $provider = (string) $varBinding->provider;
            $payload = is_array($varBinding->payload) ? $varBinding->payload : [];

            if ($provider === 'static') {
                foreach ($payload as $k => $v) {
                    $valueString = is_scalar($v) ? (string) $v : json_encode($v);
                    $replacements["{{ {$key}.{$k} }}"] = (string) $valueString;
                    $resolved++;
                }
                if (array_key_exists('value', $payload)) {
                    $v = $payload['value'];
                    $replacements["{{ {$key} }}"] = is_scalar($v) ? (string) $v : (string) json_encode($v);
                }
                continue;
            }

            $providerInstance = $this->variableRegistry->get($provider);
            if ($providerInstance === null) {
                $missing++;
                continue;
            }

            $resolvedData = $providerInstance->resolve($context->all());
            foreach ($resolvedData as $k => $v) {
                $valueString = is_scalar($v) ? (string) $v : json_encode($v);
                $replacements["{{ {$key}.{$k} }}"] = (string) $valueString;
                $resolved++;
            }
        }

        $rendered = str_replace(array_keys($replacements), array_values($replacements), $template);

        $this->logger->promptRender(
            $agentSlug,
            $resolved,
            $missing,
            strlen($rendered),
            (microtime(true) - $start) * 1000,
        );

        return $rendered;
    }
}
