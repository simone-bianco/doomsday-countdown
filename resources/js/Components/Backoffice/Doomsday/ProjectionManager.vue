<script setup lang="ts">
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Edit3, Plus, Trash2 } from 'lucide-vue-next';
import { Button, EmptyState, SearchBox, ServerDataTable } from '@simone-bianco/vue-ui-components';
import type { DataTableSort } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import DeleteConfirmationModal from '@/Components/Backoffice/Shared/DeleteConfirmationModal.vue';
import VisualizationManager from '@/Components/Backoffice/Doomsday/VisualizationManager.vue';
import {
    flatRelationTableUi,
    formatBackofficeDateTime,
    relationLinks,
    relationMeta,
    relationRows,
    relationSearch,
    relationSort,
    updateRelationSearch,
    updateRelationSort,
    urlWithCurrentBackofficeQuery,
} from '@/Components/Backoffice/Doomsday/relationTable';
import type { BackofficeOptions, BackofficeRelationCollection, ProjectionRecord } from '@/Components/Backoffice/Doomsday/types';

const TAB_VALUE = 'projections';
const SEARCH_PARAM = 'projections_search';
const PAGE_PARAM = 'projections_page';
const SORT_PARAM = 'projections_sort';
const DIRECTION_PARAM = 'projections_direction';

type EmptyForm = Record<string, never>;

const props = defineProps<{
    readonly basePath: string;
    readonly countdownId: number;
    readonly projections: BackofficeRelationCollection<ProjectionRecord>;
    readonly options: BackofficeOptions;
}>();

const pendingDelete = ref<ProjectionRecord | null>(null);
const deletingId = ref<number | null>(null);
const expandedProjectionRows = ref<unknown[]>([]);
const deleteForm = useSmartForm<EmptyForm>({});
const rows = computed<Record<string, unknown>[]>(() => relationRows(props.projections));
const meta = computed(() => relationMeta(props.projections));
const links = computed(() => relationLinks(props.projections));
const searchQuery = computed(() => relationSearch(props.projections, SEARCH_PARAM));
const sortState = computed(() => relationSort(props.projections, SORT_PARAM, DIRECTION_PARAM));
const columns = [
    { key: 'id', label: 'ID', sortable: true, class: 'w-20 flex-none', headerClass: 'w-20 flex-none' },
    { key: 'title', label: 'Projection', sortable: true, class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'scores', label: 'Scores', class: 'flex-1', headerClass: 'flex-1' },
    { key: 'sort_order', label: 'Sort', sortable: true, class: 'w-24 flex-none', headerClass: 'w-24 flex-none' },
    { key: 'actions', label: 'Actions', class: 'w-44 flex-none', headerClass: 'w-44 flex-none text-right' },
];

function collectionUrl(): string { return `${props.basePath}/countdowns/${props.countdownId}/projections`; }
function itemUrl(projection: ProjectionRecord): string { return `${collectionUrl()}/${projection.id}`; }
function createUrl(): string { return `${collectionUrl()}/create`; }
function editUrl(projection: ProjectionRecord): string { return `${itemUrl(projection)}/edit`; }
function contextualUrl(url: string): string { return urlWithCurrentBackofficeQuery(url, TAB_VALUE); }
function asProjection(item: Record<string, unknown>): ProjectionRecord { return item as unknown as ProjectionRecord; }
function startCreate(): void { router.visit(contextualUrl(createUrl()), { preserveScroll: true, preserveState: true }); }
function startEdit(projection: ProjectionRecord): void { router.visit(contextualUrl(editUrl(projection)), { preserveScroll: true, preserveState: true }); }
function handleSearch(query: string): void { updateRelationSearch(SEARCH_PARAM, PAGE_PARAM, TAB_VALUE, query); }
function handleSort(sort: DataTableSort): void { updateRelationSort(SORT_PARAM, DIRECTION_PARAM, PAGE_PARAM, TAB_VALUE, sort); }
function displayTargetDate(value: unknown): string { return formatBackofficeDateTime(typeof value === 'string' ? value : null) || 'no target date'; }
function toggleProjectionRow(item: Record<string, unknown>): void {
    const projection = asProjection(item);
    expandedProjectionRows.value = expandedProjectionRows.value.includes(projection.id)
        ? expandedProjectionRows.value.filter((id) => id !== projection.id)
        : [...expandedProjectionRows.value, projection.id];
}
function confirmDestroy(): void {
    const projection = pendingDelete.value;
    if (projection === null) return;
    deletingId.value = projection.id;
    deleteForm.delete(contextualUrl(itemUrl(projection)), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => { pendingDelete.value = null; },
        onFinish: () => { deletingId.value = null; },
    });
}
</script>

<template>
    <section class="space-y-4">
        <div>
            <p class="font-semibold">Projections</p>
            <p class="text-sm text-ui-muted-foreground">Manage scenario projections and expand rows for nested projection visualizations.</p>
        </div>

        <ServerDataTable
            v-model:expanded-rows="expandedProjectionRows"
            :data="rows"
            :columns="columns"
            :meta="meta"
            :links="links"
            item-key="id"
            searchable
            search-placeholder="Search projections..."
            :search-query="searchQuery"
            :sort="sortState"
            expandable
            enable-row-click
            density="comfortable"
            :ui="flatRelationTableUi"
            @search="handleSearch"
            @sort-change="handleSort"
            @row-click="(item) => toggleProjectionRow(item)"
        >
            <template #toolbar="{ searchQuery: tableSearchQuery, updateSearch }">
                <div class="flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <SearchBox :model-value="String(tableSearchQuery)" placeholder="Search projections..." :ui="{ root: 'w-full md:w-96' }" @update:model-value="updateSearch" />
                    <Button :icon="Plus" size="sm" @click="startCreate">Add projection</Button>
                </div>
            </template>
            <template #cell-id="{ item }"><span class="font-mono text-xs text-ui-muted-foreground">#{{ item.id }}</span></template>
            <template #cell-title="{ item }"><div class="font-medium">{{ item.title?.en ?? 'Untitled projection' }}</div><div class="text-sm text-ui-muted-foreground">{{ item.type }} · {{ displayTargetDate(item.target_date) }}</div></template>
            <template #cell-scores="{ item }"><div class="text-sm text-ui-muted-foreground">confidence {{ item.confidence_score }} · probability {{ item.probability_score }}</div></template>
            <template #cell-sort_order="{ item }"><span class="font-mono text-xs text-ui-muted-foreground">{{ item.sort_order }}</span></template>
            <template #cell-actions="{ item }"><div class="flex items-center justify-end gap-2" data-no-row-click><Button variant="secondary" size="sm" :icon="Edit3" @click="startEdit(asProjection(item))">Edit</Button><Button variant="danger" size="sm" :icon="Trash2" :loading="deleteForm.processing && deletingId === item.id" :disabled="deleteForm.processing" @click="pendingDelete = asProjection(item)">Delete</Button></div></template>
            <template #expansion="{ item }"><VisualizationManager :base-path="basePath" :countdown-id="countdownId" :projection-id="Number(item.id)" :visualizations="asProjection(item).visualizations" :options="options" title="Projection visualizations" :searchable="false" /></template>
            <template #empty><EmptyState title="No projections" description="No projections match this search." :icon="Plus" /></template>
        </ServerDataTable>

        <DeleteConfirmationModal :show="pendingDelete !== null" title="Delete projection" :description="`Delete projection ${pendingDelete?.title.en ?? ''}? Nested visualizations will be removed by backend services.`" :loading="deleteForm.processing" @close="pendingDelete = null" @confirm="confirmDestroy" />
    </section>
</template>
