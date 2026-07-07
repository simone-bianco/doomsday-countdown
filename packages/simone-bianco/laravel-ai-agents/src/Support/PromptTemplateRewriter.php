<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Support;

use SimoneBianco\LaravelAiAgents\Registries\ScopeResolverRegistry;

final class PromptTemplateRewriter
{
    /**
     * Rewrite {{ type.alias.field }} to {{ type.key:SCOPEKEY.field }} using scopeBindings context.
     * $scopeBindings: array of ['scope_type' => string, 'scope_key' => string, 'alias' => string]
     *
     * @param array<int, array{scope_type: string, scope_key: string, alias?: string}> $scopeBindings
     */
    public function rewriteForStorage(string $template, array $scopeBindings, ScopeResolverRegistry $registry): string
    {
        $aliasMap = [];
        foreach ($scopeBindings as $b) {
            if (! empty($b['alias'])) {
                $aliasMap[$b['scope_type']][$b['alias']] = $b['scope_key'];
            }
        }

        return preg_replace_callback(
            '/\{\{\s*([\w_]+)\.((?!key:)[^.{}\s]+)\.([\w_]+)\s*\}\}/',
            static function (array $m) use ($aliasMap): string {
                [$full, $type, $aliasOrPos, $field] = $m;
                if (ctype_digit($aliasOrPos)) {
                    return $full;
                }
                if (isset($aliasMap[$type][$aliasOrPos])) {
                    $scopeKey = $aliasMap[$type][$aliasOrPos];
                    return "{{ {$type}.key:{$scopeKey}.{$field} }}";
                }
                return $full;
            },
            $template
        ) ?? $template;
    }

    /**
     * Rewrite {{ type.key:SCOPEKEY.field }} to {{ type.ALIAS.field }} using resolver search.
     *
     * @param array<int, array{scope_type: string, scope_key: string, alias?: string}> $scopeBindings
     */
    public function rewriteForDisplay(string $template, array $scopeBindings, ScopeResolverRegistry $registry): string
    {
        $keyMap = [];
        foreach ($scopeBindings as $b) {
            if (! empty($b['alias'])) {
                $keyMap[$b['scope_type']][$b['scope_key']] = $b['alias'];
            }
        }

        return preg_replace_callback(
            '/\{\{\s*([\w_]+)\.key:([^.{}\s]+)\.([\w_]+)\s*\}\}/',
            static function (array $m) use ($keyMap): string {
                [$full, $type, $scopeKey, $field] = $m;
                if (isset($keyMap[$type][$scopeKey])) {
                    $alias = $keyMap[$type][$scopeKey];
                    return "{{ {$type}.{$alias}.{$field} }}";
                }
                return $full;
            },
            $template
        ) ?? $template;
    }
}
