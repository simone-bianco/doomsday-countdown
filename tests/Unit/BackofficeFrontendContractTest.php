<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class BackofficeFrontendContractTest extends TestCase
{
    public function test_backoffice_managers_do_not_hardcode_default_mutation_urls(): void
    {
        $userManager = file_get_contents(__DIR__.'/../../resources/js/Components/Backoffice/UserManager.vue');
        $keyManager = file_get_contents(__DIR__.'/../../resources/js/Components/Backoffice/OpenAiKeyManager.vue');

        $this->assertIsString($userManager);
        $this->assertIsString($keyManager);
        $this->assertStringNotContainsString('/backoffice/users', $userManager);
        $this->assertStringNotContainsString('/backoffice/openai-keys', $keyManager);
        $this->assertStringContainsString('backofficePath', $userManager);
        $this->assertStringContainsString('backofficePath', $keyManager);
    }

    public function test_backoffice_sections_do_not_use_query_parameter_switching(): void
    {
        $backofficeIndex = file_get_contents(__DIR__.'/../../resources/js/Pages/Backoffice/Index.vue');
        $sidebar = file_get_contents(__DIR__.'/../../resources/js/Components/Backoffice/Navigation/BackofficeSidebar.vue');
        $dashboard = file_get_contents(__DIR__.'/../../resources/js/Components/Backoffice/Dashboard/DashboardOverview.vue');

        $this->assertIsString($backofficeIndex);
        $this->assertIsString($sidebar);
        $this->assertIsString($dashboard);

        foreach ([$backofficeIndex, $sidebar, $dashboard] as $source) {
            $this->assertStringNotContainsString('?section=', $source);
            $this->assertStringNotContainsString("get('section')", $source);
        }

        $this->assertStringNotContainsString('window.location.search', $backofficeIndex);
        $this->assertStringContainsString("ref<BackofficeSection>('dashboard')", $backofficeIndex);
        $this->assertStringContainsString('`${normalizedBackofficePath.value}/users`', $sidebar);
        $this->assertStringContainsString('`${normalizedBackofficePath.value}/openai-keys`', $sidebar);
    }

    public function test_backoffice_local_managers_follow_table_standard(): void
    {
        $userManager = file_get_contents(__DIR__.'/../../resources/js/Components/Backoffice/UserManager.vue');
        $keyManager = file_get_contents(__DIR__.'/../../resources/js/Components/Backoffice/OpenAiKeyManager.vue');
        $tableUi = file_get_contents(__DIR__.'/../../resources/js/Components/Backoffice/Shared/backofficeTableUi.ts');

        $this->assertIsString($userManager);
        $this->assertIsString($keyManager);
        $this->assertIsString($tableUi);

        foreach ([$userManager, $keyManager] as $source) {
            $this->assertStringContainsString('SearchBox', $source);
            $this->assertStringContainsString('searchQuery', $source);
            $this->assertStringContainsString('matchesSearch', $source);
            $this->assertStringContainsString('enable-row-click', $source);
            $this->assertStringContainsString('@row-click', $source);
            $this->assertStringContainsString('data-no-row-click', $source);
            $this->assertStringContainsString('backofficeInteractiveTableUi', $source);
            $this->assertStringContainsString('backofficeTableSearchUi', $source);
            $this->assertStringNotContainsString('window.confirm', $source);
            $this->assertStringNotContainsString('axios.', $source);
            $this->assertStringNotContainsString('fetch(', $source);
        }

        $this->assertStringNotContainsString('Card,', $keyManager);
        $this->assertStringNotContainsString('<Card', $keyManager);
        $this->assertStringContainsString('Create user</Button>', $userManager);
        $this->assertStringContainsString('Register key</Button>', $keyManager);
        $this->assertStringContainsString('hover:bg-ui-primary/20', $tableUi);
        $this->assertStringContainsString('focus-within:bg-ui-primary/20', $tableUi);
    }

    public function test_backoffice_managers_expose_delete_actions(): void
    {
        $userManager = file_get_contents(__DIR__.'/../../resources/js/Components/Backoffice/UserManager.vue');
        $keyManager = file_get_contents(__DIR__.'/../../resources/js/Components/Backoffice/OpenAiKeyManager.vue');

        $this->assertIsString($userManager);
        $this->assertIsString($keyManager);
        $this->assertStringContainsString('deleteForm.delete(userUrl(user.id)', $userManager);
        $this->assertStringContainsString('deleteForm.delete(openAiKeyUrl(apiKey.id)', $keyManager);
        $this->assertStringContainsString('Delete</Button>', $userManager);
        $this->assertStringContainsString('Delete</Button>', $keyManager);
    }

    public function test_agent_debug_panel_sends_json_csrf_headers_and_handles_non_json_errors(): void
    {
        $panel = file_get_contents(__DIR__.'/../../resources/js/Components/Agent/AgentDebugPanel.vue');

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
