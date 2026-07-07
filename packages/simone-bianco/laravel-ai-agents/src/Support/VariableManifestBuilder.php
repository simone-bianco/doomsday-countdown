<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Support;

use SimoneBianco\LaravelAiAgents\Models\AiAgent;
use SimoneBianco\LaravelAiAgents\Registries\ScopeResolverRegistry;
use SimoneBianco\LaravelAiAgents\Registries\VariableProviderRegistry;

final class VariableManifestBuilder
{
    /**
     * @return array<int, array{key: string, label: string, description: string, category: string}>
     */
    public function build(AiAgent $agent, ScopeResolverRegistry $scopeReg, VariableProviderRegistry $varReg): array
    {
        $manifest = [];

        $scopeBindings = $agent->scopeBindings()->orderBy('position')->get();
        $position = 0;
        foreach ($scopeBindings as $binding) {
            $position++;
            $type = (string) $binding->scope_type;
            if (! $scopeReg->has($type)) {
                continue;
            }
            $scopeKey = (string) $binding->scope_key;
            $metadata = is_array($binding->metadata) ? $binding->metadata : [];
            $scopeName = $metadata['scope_label'] ?? $scopeKey;
            $resolver = $scopeReg->get($type);
            foreach ($resolver->suggestedVariableFields() as $field) {
                $manifest[] = [
                    'key' => "{{ {$type}.{$scopeKey}.{$field} }}",
                    'label' => ucfirst($type) . " {$scopeName} — {$field}",
                    'description' => "Field '{$field}' of scope binding '{$scopeName}'",
                    'category' => 'scope',
                ];
            }
        }

        foreach ($agent->variableBindings()->get() as $varBinding) {
            $key = (string) $varBinding->key;
            $provider = (string) $varBinding->provider;
            $payload = is_array($varBinding->payload) ? $varBinding->payload : [];

            if ($provider === 'static') {
                foreach ($payload as $k => $_v) {
                    $manifest[] = [
                        'key' => "{{ {$key}.{$k} }}",
                        'label' => "{$key}.{$k}",
                        'description' => 'Static variable binding',
                        'category' => 'variable_binding',
                    ];
                }
                continue;
            }

            $providerInstance = $varReg->get($provider);
            $label = $providerInstance?->label() ?? $provider;
            $manifest[] = [
                'key' => "{{ {$key} }}",
                'label' => "{$key} ({$label})",
                'description' => "Provider-resolved variable '{$provider}'",
                'category' => 'variable_binding',
            ];
        }

        return $manifest;
    }
}
