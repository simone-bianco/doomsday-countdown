<?php

declare(strict_types=1);

namespace App\Console\Commands\Ai;

trait ResolvesConfiguredAgentClasses
{
    private function findConfiguredAgentClass(string $agentName): ?string
    {
        foreach ((array) config('laragent.namespaces', []) as $namespace) {
            $fqcn = rtrim((string) $namespace, '\\').'\\'.$agentName;

            if (class_exists($fqcn)) {
                return $fqcn;
            }
        }

        return null;
    }
}
