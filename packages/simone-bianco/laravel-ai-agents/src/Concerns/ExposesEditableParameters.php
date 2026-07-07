<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Concerns;

trait ExposesEditableParameters
{
    /**
     * Manifest format: [{name, type, description, default, overridable, variable_bindable, toggleable, default_enabled}]
     *
     * @return array<int, array<string, mixed>>
     */
    abstract public function editableParameters(): array;

    /**
     * @param array<int, string>        $disabledParams
     * @param array<string, mixed>      $pinnedDefaults
     * @return array<string, mixed>
     */
    public function buildPropertiesWithOverrides(array $disabledParams, array $pinnedDefaults): array
    {
        /** @var array<string, mixed> $base */
        $base = method_exists($this, 'properties') ? $this->properties() : ['properties' => [], 'required' => []];

        $result = [];
        foreach (($base['properties'] ?? []) as $paramName => $schema) {
            if (in_array($paramName, $disabledParams, true)) {
                continue;
            }
            if (array_key_exists($paramName, $pinnedDefaults)) {
                $schema['default'] = $pinnedDefaults[$paramName];
            }
            $result[$paramName] = $schema;
        }

        $required = array_filter(
            $base['required'] ?? [],
            static fn ($r) => ! in_array($r, $disabledParams, true)
        );

        return [
            'type' => 'object',
            'properties' => $result,
            'required' => array_values($required),
        ];
    }
}
