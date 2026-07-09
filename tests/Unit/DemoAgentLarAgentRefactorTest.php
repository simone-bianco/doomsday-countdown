<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Ai\Agents\DemoAgent;
use App\Ai\Agents\RotableOpenAiAgent;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use LarAgent\Agent;
use LarAgent\Commands\AgentChatCommand;
use ReflectionClass;
use ReflectionMethod;
use Tests\TestCase;

final class DemoAgentLarAgentRefactorTest extends TestCase
{
    public function test_demo_agent_uses_classic_laragent_classes_not_database_agent_model(): void
    {
        $serviceSource = file_get_contents(app_path('Services/Starter/DemoAgentService.php'));

        $this->assertIsString($serviceSource);
        $this->assertStringNotContainsString('LaravelAiAgents', $serviceSource);
        $this->assertStringNotContainsString('AiAgent', $serviceSource);
        $this->assertStringNotContainsString('firstOrCreate', $serviceSource);
        $this->assertTrue(is_dir(app_path('Ai/Agents')));
        $this->assertTrue(is_dir(app_path('Ai/Tools')));
        $this->assertTrue(is_file(app_path('Ai/Agents/DemoAgent.php')));
    }

    public function test_demo_agent_extends_rotable_laragent_base(): void
    {
        $this->assertTrue(is_subclass_of(DemoAgent::class, RotableOpenAiAgent::class));
        $this->assertTrue(is_subclass_of(RotableOpenAiAgent::class, Agent::class));

        $defaults = (new ReflectionClass(DemoAgent::class))->getDefaultProperties();

        $this->assertSame(0.2, $defaults['temperature']);
        $this->assertSame(800, $defaults['maxCompletionTokens']);
        $this->assertFalse($defaults['parallelToolCalls']);
    }

    public function test_laragent_artisan_chat_command_can_discover_canonical_demo_agent(): void
    {
        $this->assertContains('App\\Ai\\Agents\\', config('laragent.namespaces'));
        $this->assertTrue(class_exists('App\\Agents\\DemoAgent'));
        $this->assertTrue(is_a('App\\Agents\\DemoAgent', DemoAgent::class, true));

        $command = app(AgentChatCommand::class);
        $method = new ReflectionMethod($command, 'findAgentClass');
        $method->setAccessible(true);

        $this->assertSame(DemoAgent::class, $method->invoke($command, 'DemoAgent'));
    }

    public function test_make_agent_artisan_command_uses_app_ai_agents_path(): void
    {
        $agentPath = app_path('Ai/Agents/GeneratedPathProbeAgent.php');
        $legacyAiAgentsPath = app_path('AiAgents/GeneratedPathProbeAgent.php');
        $legacyAgentsPath = app_path('Agents/GeneratedPathProbeAgent.php');

        File::delete([$agentPath, $legacyAiAgentsPath, $legacyAgentsPath]);

        try {
            $exitCode = Artisan::call('make:agent', ['name' => 'GeneratedPathProbeAgent']);

            $this->assertSame(0, $exitCode);
            $this->assertFileExists($agentPath);
            $this->assertFileDoesNotExist($legacyAiAgentsPath);
            $this->assertFileDoesNotExist($legacyAgentsPath);
            $this->assertStringContainsString('namespace App\\Ai\\Agents;', File::get($agentPath));
            $this->assertStringContainsString('extends RotableOpenAiAgent', File::get($agentPath));
        } finally {
            File::delete($agentPath);
        }
    }

    public function test_make_agent_tool_artisan_command_uses_app_ai_tools_path(): void
    {
        $toolPath = app_path('Ai/Tools/GeneratedPathProbeTool.php');
        $legacyToolPath = app_path('AgentTools/GeneratedPathProbeTool.php');

        File::delete([$toolPath, $legacyToolPath]);

        try {
            $exitCode = Artisan::call('make:agent:tool', ['name' => 'GeneratedPathProbeTool']);

            $this->assertSame(0, $exitCode);
            $this->assertFileExists($toolPath);
            $this->assertFileDoesNotExist($legacyToolPath);
            $this->assertStringContainsString('namespace App\\Ai\\Tools;', File::get($toolPath));
            $this->assertStringContainsString('extends Tool', File::get($toolPath));
        } finally {
            File::delete($toolPath);
        }
    }

    public function test_chat_clear_and_remove_commands_use_configured_agent_namespaces(): void
    {
        $exitCode = Artisan::call('agent:chat:clear', ['agent' => 'DemoAgent']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Successfully cleared chat history for agent: DemoAgent', Artisan::output());

        $exitCode = Artisan::call('agent:chat:remove', ['agent' => 'DemoAgent']);
        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('agent: DemoAgent', Artisan::output());
    }
}
