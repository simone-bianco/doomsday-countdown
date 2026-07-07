<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Support;

use LarAgent\Tool;
use SimoneBianco\LaravelAiAgents\Concerns\ExposesEditableParameters;

class WrappedTool extends Tool
{
    /**
     * @param array<int, string>   $disabledParams
     * @param array<string, mixed> $pinnedValues
     */
    public function __construct(
        private readonly Tool $inner,
        private readonly array $disabledParams,
        private readonly array $pinnedValues,
    ) {
        parent::__construct($inner->getName(), $inner->getDescription());

        // Rebuild properties on this wrapper based on inner filtered.
        $innerProps = $this->extractInnerProperties();
        $filteredProps = [];
        foreach ($innerProps as $name => $schema) {
            if (in_array($name, $this->disabledParams, true)) {
                continue;
            }
            if (array_key_exists($name, $this->pinnedValues)) {
                $schema['default'] = $this->pinnedValues[$name];
            }
            $filteredProps[$name] = $schema;
        }

        $innerRequired = $this->extractInnerRequired();
        $filteredRequired = array_values(array_filter(
            $innerRequired,
            fn (string $r) => ! in_array($r, $this->disabledParams, true)
        ));

        $this->properties = $filteredProps;
        $this->required = $filteredRequired;
    }

    public function execute(array $input): mixed
    {
        // Merge pinned values (they take precedence over LLM input).
        $merged = array_merge($input, $this->pinnedValues);

        return $this->inner->execute($merged);
    }

    /**
     * @return array<string, mixed>
     */
    private function extractInnerProperties(): array
    {
        if (in_array(ExposesEditableParameters::class, class_uses_recursive($this->inner), true)) {
            $schema = $this->inner->buildPropertiesWithOverrides($this->disabledParams, $this->pinnedValues);
            return $schema['properties'] ?? [];
        }

        $ref = new \ReflectionClass($this->inner);
        if ($ref->hasProperty('properties')) {
            $prop = $ref->getProperty('properties');
            $prop->setAccessible(true);
            $value = $prop->getValue($this->inner);
            return is_array($value) ? $value : [];
        }

        return [];
    }

    /**
     * @return array<int, string>
     */
    private function extractInnerRequired(): array
    {
        $ref = new \ReflectionClass($this->inner);
        if ($ref->hasProperty('required')) {
            $prop = $ref->getProperty('required');
            $prop->setAccessible(true);
            $value = $prop->getValue($this->inner);
            return is_array($value) ? array_values($value) : [];
        }

        return [];
    }
}
