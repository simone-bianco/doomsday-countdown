<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class BackofficeCountdownRefactorContractTest extends TestCase
{
    public function test_countdown_index_uses_server_table_search_pagination_and_row_click_contract(): void
    {
        $index = $this->readProjectFile('resources/js/Pages/Backoffice/Countdowns/Index.vue');

        $this->assertStringContainsString(
            'ServerDataTable',
            $index,
            'Countdown index must render ServerDataTable so the backend paginator/search contract is visible in the UI.'
        );
        $this->assertStringContainsString(':data="rows"', $index);
        $this->assertStringContainsString(':meta="countdowns.meta"', $index);
        $this->assertStringContainsString(':links="countdowns.links"', $index);
        $this->assertStringContainsString('searchable', $index);
        $this->assertStringContainsString('@search', $index);
        $this->assertStringContainsString('enable-row-click', $index);
        $this->assertStringContainsString('@row-click', $index);
        $this->assertStringContainsString("key: 'id'", $index);
        $this->assertStringContainsString("key: 'sort_order'", $index);
        $this->assertStringContainsString('sortable: true', $index);
        $this->assertStringContainsString(':sort="currentSort"', $index);
        $this->assertStringContainsString('@sort-change="handleSortChange"', $index);
        $this->assertStringContainsString("params.set('sort'", $index);
        $this->assertStringContainsString("params.set('direction'", $index);
        $this->assertStringContainsString('item.sort_order', $index);
        $this->assertStringContainsString('imageSource(item)', $index);
    }

    public function test_relation_managers_preserve_query_contract_and_protect_row_actions(): void
    {
        $relationTable = $this->readProjectFile('resources/js/Components/Backoffice/Doomsday/relationTable.ts');
        $this->assertStringContainsString('params.set(\'tab\', tab)', $relationTable);
        $this->assertStringContainsString('params.delete(pageParam)', $relationTable);
        $this->assertStringContainsString('router.get(window.location.pathname', $relationTable);
        $this->assertStringContainsString('urlWithCurrentBackofficeQuery', $relationTable);
        $this->assertStringContainsString('formatBackofficeDateTime', $relationTable);

        foreach ([
            'resources/js/Components/Backoffice/Doomsday/ProjectionManager.vue',
            'resources/js/Components/Backoffice/Doomsday/VisualizationManager.vue',
            'resources/js/Components/Backoffice/Doomsday/NewsManager.vue',
            'resources/js/Components/Backoffice/Doomsday/InitiativeManager.vue',
        ] as $managerPath) {
            $manager = $this->readProjectFile($managerPath);
            $this->assertStringContainsString('ServerDataTable', $manager, $managerPath);
            $this->assertStringContainsString('enable-row-click', $manager, $managerPath);
            $this->assertStringContainsString('data-no-row-click', $manager, $managerPath);
            $this->assertStringContainsString('contextualUrl', $manager, $managerPath);
            $this->assertStringContainsString('deleteForm.delete(contextualUrl', $manager, $managerPath);
            $this->assertStringNotContainsString('axios.', $manager, $managerPath);
            $this->assertStringNotContainsString('fetch(', $manager, $managerPath);
            $this->assertStringNotContainsString('/backoffice', $manager, $managerPath);
        }
    }

    public function test_doomsday_tables_follow_reopened_flat_toolbar_and_strong_hover_standard(): void
    {
        $relationTable = $this->readProjectFile('resources/js/Components/Backoffice/Doomsday/relationTable.ts');
        $this->assertStringContainsString('hover:bg-ui-primary/20', $relationTable);
        $this->assertStringContainsString('focus-within:bg-ui-primary/20', $relationTable);
        $this->assertStringNotContainsString('hover:bg-ui-muted/40', $relationTable);

        $index = $this->readProjectFile('resources/js/Pages/Backoffice/Countdowns/Index.vue');
        $this->assertStringContainsString('SearchBox', $index);
        $this->assertStringContainsString('#toolbar', $index);
        $this->assertStringContainsString(':ui="flatCountdownTableUi"', $index);
        $this->assertStringContainsString('hover:bg-ui-primary/20', $index);
        $this->assertStringNotContainsString('Card,', $index);
        $this->assertStringNotContainsString('<Card', $index);

        foreach ([
            'resources/js/Components/Backoffice/Doomsday/ProjectionManager.vue',
            'resources/js/Components/Backoffice/Doomsday/VisualizationManager.vue',
            'resources/js/Components/Backoffice/Doomsday/NewsManager.vue',
            'resources/js/Components/Backoffice/Doomsday/InitiativeManager.vue',
        ] as $managerPath) {
            $manager = $this->readProjectFile($managerPath);
            $this->assertStringContainsString('SearchBox', $manager, $managerPath);
            $this->assertStringContainsString('#toolbar', $manager, $managerPath);
            $this->assertStringContainsString('@update:model-value="updateSearch"', $manager, $managerPath);
            $this->assertStringContainsString('flatRelationTableUi', $manager, $managerPath);
        }
    }

    public function test_relation_managers_expose_id_sort_order_and_wired_server_sorting(): void
    {
        $relationTable = $this->readProjectFile('resources/js/Components/Backoffice/Doomsday/relationTable.ts');
        $this->assertStringContainsString('relationSort', $relationTable);
        $this->assertStringContainsString('updateRelationSort', $relationTable);
        $this->assertStringContainsString('params.set(sortParam, sort.key)', $relationTable);
        $this->assertStringContainsString('params.set(directionParam, sort.direction)', $relationTable);

        foreach ([
            'resources/js/Components/Backoffice/Doomsday/ProjectionManager.vue' => 'projections_sort',
            'resources/js/Components/Backoffice/Doomsday/VisualizationManager.vue' => 'visualizations_sort',
            'resources/js/Components/Backoffice/Doomsday/NewsManager.vue' => 'news_sort',
            'resources/js/Components/Backoffice/Doomsday/InitiativeManager.vue' => 'initiatives_sort',
        ] as $managerPath => $sortParam) {
            $manager = $this->readProjectFile($managerPath);
            $this->assertStringContainsString("key: 'id'", $manager, $managerPath);
            $this->assertStringContainsString("key: 'sort_order'", $manager, $managerPath);
            $this->assertStringContainsString(':sort="sortState"', $manager, $managerPath);
            $this->assertStringContainsString('@sort-change="handleSort"', $manager, $managerPath);
            $this->assertStringContainsString($sortParam, $manager, $managerPath);
            $this->assertStringContainsString('updateRelationSort', $manager, $managerPath);
            $this->assertStringContainsString('#{{ item.id }}', $manager, $managerPath);
            $this->assertStringContainsString('{{ item.sort_order }}', $manager, $managerPath);
        }

        $service = $this->readProjectFile('app/Services/Backoffice/Doomsday/BackofficeCountdownService.php');
        foreach (['projections_sort', 'visualizations_sort', 'news_sort', 'initiatives_sort'] as $sortParam) {
            $this->assertStringContainsString($sortParam, $service);
        }
        $this->assertStringContainsString('applyRelationOrder', $service);
        $this->assertStringContainsString("'sort' => \$sort", $service);
        $this->assertStringContainsString("'direction' => \$direction", $service);
    }

    public function test_complex_relation_managers_do_not_render_inline_edit_forms(): void
    {
        $projectionManager = $this->readProjectFile('resources/js/Components/Backoffice/Doomsday/ProjectionManager.vue');
        $visualizationManager = $this->readProjectFile('resources/js/Components/Backoffice/Doomsday/VisualizationManager.vue');

        $this->assertStringNotContainsString('ProjectionForm', $projectionManager);
        $this->assertStringNotContainsString('VisualizationForm', $visualizationManager);
        $this->assertStringContainsString('/create', $projectionManager);
        $this->assertStringContainsString('/edit', $projectionManager);
        $this->assertStringContainsString('/create', $visualizationManager);
        $this->assertStringContainsString('/edit', $visualizationManager);
    }

    public function test_countdown_icon_contract_is_purged_from_schema_dtos_generated_and_forms(): void
    {
        $criticalFiles = [
            'database/migrations/2026_07_07_010000_create_countdowns_table.php',
            'app/Models/Countdown.php',
            'app/Data/Backoffice/Doomsday/SaveCountdownData.php',
            'app/Data/Doomsday/CountdownIndexData.php',
            'app/Data/Doomsday/CountdownDetailData.php',
            'app/Data/Doomsday/CountdownOverviewData.php',
            'resources/js/generated/form-rules.ts',
            'resources/js/types/generated.d.ts',
            'resources/js/Components/Backoffice/Doomsday/CountdownForm.vue',
            'resources/js/Components/Backoffice/Doomsday/types.ts',
        ];

        foreach ($criticalFiles as $path) {
            $contents = $this->readProjectFile($path);
            $this->assertStringNotContainsString('Icon key', $contents, $path);
            $this->assertStringNotContainsString('countdown.icon', $contents, $path);
            $this->assertStringNotContainsString('form.icon', $contents, $path);
            $this->assertStringNotContainsString('readonly icon', $contents, $path);
            $this->assertStringNotContainsString('public string $icon', $contents, $path);
            $this->assertStringNotContainsString("'icon'", $contents, $path);
            $this->assertStringNotContainsString('"icon"', $contents, $path);
        }
    }

    public function test_doomsday_backoffice_components_have_no_raw_mutation_or_default_path_smells(): void
    {
        foreach (glob($this->projectPath('resources/js/Components/Backoffice/Doomsday/*.{vue,ts}'), GLOB_BRACE) ?: [] as $file) {
            $contents = file_get_contents($file);
            $this->assertIsString($contents, $file);
            $this->assertStringNotContainsString('/backoffice', $contents, $file);
            $this->assertStringNotContainsString('window.confirm', $contents, $file);
            $this->assertStringNotContainsString('axios.', $contents, $file);
            $this->assertStringNotContainsString('fetch(', $contents, $file);
        }
    }

    public function test_countdown_save_action_stays_inside_main_panel_contract(): void
    {
        $form = $this->readProjectFile('resources/js/Components/Backoffice/Doomsday/CountdownForm.vue');
        $relationsManager = $this->readProjectFile('resources/js/Components/Backoffice/Doomsday/CountdownRelationsManager.vue');

        $this->assertStringContainsString('<form class="space-y-6" @submit.prevent="submit">', $form);
        $this->assertStringContainsString('Main countdown', $form);
        $this->assertStringContainsString('<Toggle v-model="form.is_published"', $form);
        $this->assertStringContainsString('<Button type="submit" :loading="form.processing">{{ submitLabel }}</Button>', $form);

        $this->assertStringNotContainsString('formId', $relationsManager);
        $this->assertStringNotContainsString('submit()', $relationsManager);
        $this->assertStringNotContainsString('Save countdown', $relationsManager);
        $this->assertStringNotContainsString('absolute', $relationsManager);
    }

    private function readProjectFile(string $path): string
    {
        $contents = file_get_contents($this->projectPath($path));

        $this->assertIsString($contents, $path);

        return $contents;
    }

    private function projectPath(string $path): string
    {
        return dirname(__DIR__, 2).DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $path);
    }
}
