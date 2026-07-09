<script setup lang="ts">
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Edit3, Plus, Trash2 } from 'lucide-vue-next';
import { Badge, Button, EmptyState, SearchBox, ServerDataTable } from '@simone-bianco/vue-ui-components';
import type { DataTableSort } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import DeleteConfirmationModal from '@/Components/Backoffice/Shared/DeleteConfirmationModal.vue';
import VisualizationPreview from '@/Components/Backoffice/Doomsday/VisualizationPreview.vue';
import {
    flatRelationTableUi,
    relationLinks,
    relationMeta,
    relationRows,
    relationSearch,
    relationSort,
    updateRelationSearch,
    updateRelationSort,
    urlWithCurrentBackofficeQuery,
} from '@/Components/Backoffice/Doomsday/relationTable';
import type { BackofficeOptions, BackofficeRelationCollection, VisualizationRecord } from '@/Components/Backoffice/Doomsday/types';

const COUNTDOWN_TAB = 'visualizations';
const PROJECTION_TAB = 'projections';
const COUNTDOWN_SEARCH_PARAM = 'visualizations_search';
const COUNTDOWN_PAGE_PARAM = 'visualizations_page';
const COUNTDOWN_SORT_PARAM = 'visualizations_sort';
const COUNTDOWN_DIRECTION_PARAM = 'visualizations_direction';
const PROJECTION_SEARCH_PARAM = 'projection_visualizations_search';
const PROJECTION_PAGE_PARAM = 'projection_visualizations_page';

type EmptyForm = Record<string, never>;

const props = withDefaults(defineProps<{
    readonly basePath: string;
    readonly countdownId: number;
    readonly projectionId?: number;
    readonly visualizations: BackofficeRelationCollection<VisualizationRecord>;
    readonly options: BackofficeOptions;
    readonly title: string;
    readonly searchable?: boolean;
}>(), {
    searchable: true,
});

const pendingDelete = ref<VisualizationRecord | null>(null);
const deletingId = ref<number | null>(null);
const expandedVisualizationRows = ref<unknown[]>([]);
const deleteForm = useSmartForm<EmptyForm>({});
const isCountdownRelation = computed(() => props.projectionId === undefined);
const tabValue = computed(() => isCountdownRelation.value ? COUNTDOWN_TAB : PROJECTION_TAB);
const searchParam = computed(() => isCountdownRelation.value ? COUNTDOWN_SEARCH_PARAM : PROJECTION_SEARCH_PARAM);
const pageParam = computed(() => isCountdownRelation.value ? COUNTDOWN_PAGE_PARAM : PROJECTION_PAGE_PARAM);
const sortState = computed(() => relationSort(props.visualizations, COUNTDOWN_SORT_PARAM, COUNTDOWN_DIRECTION_PARAM));
const rows = computed<Record<string, unknown>[]>(() => relationRows(props.visualizations));
const meta = computed(() => relationMeta(props.visualizations));
const links = computed(() => relationLinks(props.visualizations));
const searchQuery = computed(() => props.searchable ? relationSearch(props.visualizations, searchParam.value) : '');
const columns = computed(() => [
    { key: 'id', label: 'ID', sortable: isCountdownRelation.value, class: 'w-20 flex-none', headerClass: 'w-20 flex-none' },
    { key: 'title', label: 'Visualization', sortable: isCountdownRelation.value, class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'key', label: 'Key / type', sortable: isCountdownRelation.value, class: 'flex-1', headerClass: 'flex-1' },
    { key: 'sort_order', label: 'Sort', sortable: isCountdownRelation.value, class: 'w-24 flex-none', headerClass: 'w-24 flex-none' },
    { key: 'actions', label: 'Actions', class: 'w-44 flex-none', headerClass: 'w-44 flex-none text-right' },
]);

function collectionUrl(): string {
    if (props.projectionId !== undefined) {
        return `${props.basePath}/countdowns/${props.countdownId}/projections/${props.projectionId}/visualizations`;
    }
    return `${props.basePath}/countdowns/${props.countdownId}/visualizations`;
}

function itemUrl(visualization: VisualizationRecord): string {
    return `${collectionUrl()}/${visualization.id}`;
}

function createUrl(): string { return `${collectionUrl()}/create`; }
function editUrl(visualization: VisualizationRecord): string { return `${itemUrl(visualization)}/edit`; }
function contextualUrl(url: string): string { return urlWithCurrentBackofficeQuery(url, tabValue.value); }
function asVisualization(item: Record<string, unknown>): VisualizationRecord { return item as unknown as VisualizationRecord; }
function startCreate(): void { router.visit(contextualUrl(createUrl()), { preserveScroll: true, preserveState: true }); }
function startEdit(visualization: VisualizationRecord): void { router.visit(contextualUrl(editUrl(visualization)), { preserveScroll: true, preserveState: true }); }
function handleSearch(query: string): void { updateRelationSearch(searchParam.value, pageParam.value, tabValue.value, query); }
function handleSort(sort: DataTableSort): void {
    if (isCountdownRelation.value) {
        updateRelationSort(COUNTDOWN_SORT_PARAM, COUNTDOWN_DIRECTION_PARAM, COUNTDOWN_PAGE_PARAM, COUNTDOWN_TAB, sort);
    }
}
function toggleVisualizationRow(item: Record<string, unknown>): void {
    const visualization = asVisualization(item);
    expandedVisualizationRows.value = expandedVisualizationRows.value.includes(visualization.id)
        ? expandedVisualizationRows.value.filter((id) => id !== visualization.id)
        : [...expandedVisualizationRows.value, visualization.id];
}
function confirmDestroy(): void {
    const visualization = pendingDelete.value;
    if (visualization === null) return;
    deletingId.value = visualization.id;
    deleteForm.delete(contextualUrl(itemUrl(visualization)), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => { pendingDelete.value = null; },
        onFinish: () => { deletingId.value = null; },
    });
}
</script>

<template>
    <section class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="font-semibold">{{ title }}</p>
                <p class="text-sm text-ui-muted-foreground">Create, edit, delete and preview line/area/KPI payloads. Rows are collapsed until opened.</p>
            </div>
            <Button v-if="!searchable" :icon="Plus" size="sm" @click="startCreate">Add visualization</Button>
        </div>

        <ServerDataTable
            v-model:expanded-rows="expandedVisualizationRows"
            :data="rows"
            :columns="columns"
            :meta="meta"
            :links="links"
            item-key="id"
            :searchable="searchable"
            search-placeholder="Search visualizations..."
            :search-query="searchQuery"
            :sort="sortState"
            expandable
            enable-row-click
            density="comfortable"
            :ui="flatRelationTableUi"
            @search="handleSearch"
            @sort-change="handleSort"
            @row-click="(item) => toggleVisualizationRow(item)"
        >
            <template #toolbar="{ searchQuery: tableSearchQuery, updateSearch }">
                <div class="flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <SearchBox :model-value="String(tableSearchQuery)" placeholder="Search visualizations..." :ui="{ root: 'w-full md:w-96' }" @update:model-value="updateSearch" />
                    <Button :icon="Plus" size="sm" @click="startCreate">Add visualization</Button>
                </div>
            </template>
            <template #cell-id="{ item }"><span class="font-mono text-xs text-ui-muted-foreground">#{{ item.id }}</span></template>
            <template #cell-title="{ item }"><div class="font-medium">{{ item.title?.en ?? item.key }}</div><div class="text-sm text-ui-muted-foreground">schema {{ item.schema_version }}</div></template>
            <template #cell-key="{ item }"><div class="text-sm text-ui-muted-foreground">{{ item.key }}</div><Badge :label="String(item.type)" variant="soft" /></template>
            <template #cell-sort_order="{ item }"><span class="font-mono text-xs text-ui-muted-foreground">{{ item.sort_order }}</span></template>
            <template #cell-actions="{ item }">
                <div class="flex items-center justify-end gap-2" data-no-row-click>
                    <Button variant="secondary" size="sm" :icon="Edit3" @click="startEdit(asVisualization(item))">Edit</Button>
                    <Button variant="danger" size="sm" :icon="Trash2" :loading="deleteForm.processing && deletingId === item.id" :disabled="deleteForm.processing" @click="pendingDelete = asVisualization(item)">Delete</Button>
                </div>
            </template>
            <template #expansion="{ item }"><VisualizationPreview :visualization="asVisualization(item)" /></template>
            <template #empty><EmptyState title="No visualizations" description="No visualizations match this search." :icon="Plus" /></template>
        </ServerDataTable>

        <DeleteConfirmationModal :show="pendingDelete !== null" title="Delete visualization" :description="`Delete visualization ${pendingDelete?.key ?? ''}?`" :loading="deleteForm.processing" @close="pendingDelete = null" @confirm="confirmDestroy" />
    </section>
</template>
