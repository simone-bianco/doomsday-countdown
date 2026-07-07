<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class BackofficeFrontendContractTest extends TestCase
{
    public function test_backoffice_managers_do_not_hardcode_default_mutation_urls(): void
    {
        $userManager = file_get_contents(__DIR__ . '/../../resources/js/Components/Backoffice/UserManager.vue');
        $keyManager = file_get_contents(__DIR__ . '/../../resources/js/Components/Backoffice/OpenAiKeyManager.vue');

        $this->assertIsString($userManager);
        $this->assertIsString($keyManager);
        $this->assertStringNotContainsString('/backoffice/users', $userManager);
        $this->assertStringNotContainsString('/backoffice/openai-keys', $keyManager);
        $this->assertStringContainsString('backofficePath', $userManager);
        $this->assertStringContainsString('backofficePath', $keyManager);
    }

    public function test_backoffice_managers_expose_delete_actions(): void
    {
        $userManager = file_get_contents(__DIR__ . '/../../resources/js/Components/Backoffice/UserManager.vue');
        $keyManager = file_get_contents(__DIR__ . '/../../resources/js/Components/Backoffice/OpenAiKeyManager.vue');

        $this->assertIsString($userManager);
        $this->assertIsString($keyManager);
        $this->assertStringContainsString('deleteForm.delete(userUrl(user.id)', $userManager);
        $this->assertStringContainsString('deleteForm.delete(openAiKeyUrl(apiKey.id)', $keyManager);
        $this->assertStringContainsString('Delete</Button>', $userManager);
        $this->assertStringContainsString('Delete</Button>', $keyManager);
    }

    public function test_agent_debug_panel_sends_json_csrf_headers_and_handles_non_json_errors(): void
    {
        $panel = file_get_contents(__DIR__ . '/../../resources/js/Components/Agent/AgentDebugPanel.vue');

        $this->assertIsString($panel);
        $this->assertStringContainsString("cookieValue('XSRF-TOKEN')", $panel);
        $this->assertStringContainsString("'X-XSRF-TOKEN'", $panel);
        $this->assertStringContainsString("'X-CSRF-TOKEN'", $panel);
        $this->assertStringContainsString("Accept: 'application/json'", $panel);
        $this->assertStringContainsString("'X-Requested-With': 'XMLHttpRequest'", $panel);
        $this->assertStringContainsString('content-type', $panel);
        $this->assertStringContainsString('Response was not JSON', $panel);
        $this->assertStringContainsString('httpErrorResult(error, payload)', $panel);
        $this->assertStringNotContainsString('error.response.json<AgentResult>()', $panel);
    }
}
