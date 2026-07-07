<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents\Services;

use Illuminate\Support\Facades\Http;
use LarAgent\Tool;
use SimoneBianco\LaravelAiAgents\Exceptions\AgentToolResolutionException;
use SimoneBianco\LaravelAiAgents\Models\AiAgentToolBinding;
use SimoneBianco\LaravelAiAgents\Support\AgentLogger;
use SimoneBianco\LaravelAiAgents\Support\AgentRunContext;

class DynamicApiToolBuilder
{
    public function __construct(
        private readonly AgentLogger $logger,
    ) {
    }

    public function build(AiAgentToolBinding $binding, AgentRunContext $context): Tool
    {
        $tool = $binding->tool;
        $definition = is_array($tool->dynamic_definition) ? $tool->dynamic_definition : [];

        if ($definition === []) {
            throw new AgentToolResolutionException(
                "Dynamic tool '{$tool->key}' has empty dynamic_definition"
            );
        }

        $name = (string) ($definition['name'] ?? $tool->key);
        $description = (string) ($definition['description'] ?? ($tool->description ?? ''));
        $method = strtolower((string) ($definition['method'] ?? 'get'));
        $url = (string) ($definition['url'] ?? '');
        $headers = (array) ($definition['headers'] ?? []);
        $bodyTemplate = $definition['body'] ?? null;
        $timeout = (int) config('ai-agents.dynamic_tools.http_timeout_seconds', 30);
        $allowedHosts = is_array($tool->allowed_hosts) ? $tool->allowed_hosts : [];
        $manifest = is_array($tool->parameter_manifest) ? $tool->parameter_manifest : [];
        $logger = $this->logger;
        $toolKey = (string) $tool->key;

        $instance = Tool::create($name, $description);

        // Build properties from manifest entries [{name,type,description,required,default}].
        foreach ($manifest as $entry) {
            $paramName = (string) ($entry['name'] ?? '');
            if ($paramName === '') {
                continue;
            }
            $type = (string) ($entry['type'] ?? 'string');
            $paramDesc = (string) ($entry['description'] ?? '');
            $instance->addProperty($paramName, $type, $paramDesc);
            if (! empty($entry['required'])) {
                $instance->setRequired($paramName);
            }
        }

        $instance->setCallback(function (...$args) use (
            $method, $url, $headers, $bodyTemplate, $timeout, $allowedHosts, $logger, $toolKey, $manifest
        ): mixed {
            $start = microtime(true);
            // Map positional args back via manifest order.
            $input = [];
            foreach (array_values($manifest) as $idx => $entry) {
                $name = (string) ($entry['name'] ?? '');
                if ($name === '') {
                    continue;
                }
                if (array_key_exists($idx, $args)) {
                    $input[$name] = $args[$idx];
                }
            }

            $resolvedUrl = self::interpolate($url, $input);
            if ($allowedHosts !== []) {
                $host = parse_url($resolvedUrl, PHP_URL_HOST) ?: '';
                if (! in_array($host, $allowedHosts, true)) {
                    throw new AgentToolResolutionException(
                        "Host '{$host}' not in allowed_hosts for dynamic tool '{$toolKey}'"
                    );
                }
            }

            $resolvedHeaders = [];
            foreach ($headers as $k => $v) {
                $resolvedHeaders[(string) $k] = self::interpolate((string) $v, $input);
            }

            $body = null;
            if (is_array($bodyTemplate)) {
                $body = json_decode(self::interpolate(json_encode($bodyTemplate) ?: '{}', $input), true);
            } elseif (is_string($bodyTemplate)) {
                $body = self::interpolate($bodyTemplate, $input);
            }

            $logger->toolExecuteStart($toolKey, $input);

            try {
                $req = Http::timeout($timeout)->withHeaders($resolvedHeaders);
                $response = match ($method) {
                    'post' => $req->post($resolvedUrl, is_array($body) ? $body : []),
                    'put' => $req->put($resolvedUrl, is_array($body) ? $body : []),
                    'patch' => $req->patch($resolvedUrl, is_array($body) ? $body : []),
                    'delete' => $req->delete($resolvedUrl, is_array($body) ? $body : []),
                    default => $req->get($resolvedUrl, is_array($body) ? $body : []),
                };

                $result = $response->json() ?? $response->body();
                $logger->toolExecuteEnd($toolKey, (microtime(true) - $start) * 1000, $response->successful());
                return $result;
            } catch (\Throwable $e) {
                $logger->toolExecuteEnd($toolKey, (microtime(true) - $start) * 1000, false);
                $logger->error('tool_execute_error', $toolKey, $e);
                throw $e;
            }
        });

        return $instance;
    }

    /**
     * @param array<string, mixed> $vars
     */
    private static function interpolate(string $template, array $vars): string
    {
        $search = [];
        $replace = [];
        foreach ($vars as $k => $v) {
            $search[] = '{' . $k . '}';
            $replace[] = is_scalar($v) ? (string) $v : (string) json_encode($v);
        }
        return str_replace($search, $replace, $template);
    }
}
