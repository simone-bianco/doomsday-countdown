<?php

declare(strict_types=1);

namespace SimoneBianco\LaravelAiAgents;

use SimoneBianco\LaravelAiAgents\Registries\AgentResponseHookRegistry;
use SimoneBianco\LaravelAiAgents\Registries\ScopeResolverRegistry;
use SimoneBianco\LaravelAiAgents\Registries\ToolRegistry;
use SimoneBianco\LaravelAiAgents\Registries\VariableProviderRegistry;
use SimoneBianco\LaravelAiAgents\Console\Commands\ClearStaleLocks;
use SimoneBianco\LaravelAiAgents\Services\AgentDefinitionService;
use SimoneBianco\LaravelAiAgents\Services\AgentInstantiationService;
use SimoneBianco\LaravelAiAgents\Services\AgentPlaygroundRunner;
use SimoneBianco\LaravelAiAgents\Services\AgentPromptRenderer;
use SimoneBianco\LaravelAiAgents\Services\AgentRevisionService;
use SimoneBianco\LaravelAiAgents\Services\AgentToolBuilder;
use SimoneBianco\LaravelAiAgents\Services\DynamicApiToolBuilder;
use SimoneBianco\LaravelAiAgents\Services\SubAgentToolBuilder;
use SimoneBianco\LaravelAiAgents\Support\AgentLogger;
use SimoneBianco\LaravelAiAgents\Support\AgentRunStack;
use SimoneBianco\LaravelAiAgents\Support\PromptTemplateRewriter;
use SimoneBianco\LaravelAiAgents\Support\VariableManifestBuilder;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAiAgentsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-ai-agents')
            ->hasConfigFile('ai-agents');
    }

    public function packageRegistered(): void
    {
        $this->app->scoped(AgentRunStack::class, fn () => new AgentRunStack());

        $this->app->singleton(ToolRegistry::class, fn () => new ToolRegistry());
        $this->app->singleton(ScopeResolverRegistry::class, fn () => new ScopeResolverRegistry());
        $this->app->singleton(VariableProviderRegistry::class, fn () => new VariableProviderRegistry());
        $this->app->singleton(AgentResponseHookRegistry::class, fn () => new AgentResponseHookRegistry());

        $this->app->singleton(AgentLogger::class);
        $this->app->singleton(PromptTemplateRewriter::class);
        $this->app->singleton(VariableManifestBuilder::class);
        $this->app->singleton(AgentPromptRenderer::class);

        $this->app->bind(DynamicApiToolBuilder::class);
        $this->app->bind(SubAgentToolBuilder::class);
        $this->app->bind(AgentToolBuilder::class);
        $this->app->bind(AgentInstantiationService::class);

        $this->app->singleton(AgentDefinitionService::class);
        $this->app->singleton(AgentRevisionService::class);
        $this->app->singleton(AgentPlaygroundRunner::class);
    }

    public function packageBooted(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearStaleLocks::class,
            ]);
        }
    }
}
