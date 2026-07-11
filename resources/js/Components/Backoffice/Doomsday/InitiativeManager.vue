<script setup lang="ts">
import { computed, ref } from 'vue';
import { Edit3, Plus, Trash2 } from 'lucide-vue-next';
import { Badge, Button, EmptyState, Modal, SearchBox, ServerDataTable } from '@simone-bianco/vue-ui-components';
import type { DataTableSort } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import DeleteConfirmationModal from '@/Components/Backoffice/Shared/DeleteConfirmationModal.vue';
import InitiativeForm from '@/Components/Backoffice/Doomsday/InitiativeForm.vue';
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
import type { BackofficeOptions, BackofficeRelationCollection, InitiativeRecord } from '@/Components/Backoffice/Doomsday/types';

const TAB_VALUE = 'initiatives';
const SEARCH_PARAM = 'initiatives_search';
const PAGE_PARAM = 'initiatives_page';
const SORT_PARAM = 'initiatives_sort';
const DIRECTION_PARAM = 'initiatives_direction';

type EmptyForm = Record<string, never>;
const props = defineProps<{ readonly basePath: string; readonly countdownId: number; readonly initiatives: BackofficeRelationCollection<InitiativeRecord>; readonly options: BackofficeOptions }>();
const showForm = ref(false);
const editing = ref<InitiativeRecord | null>(null);
const pendingDelete = ref<InitiativeRecord | null>(null);
const deletingId = ref<number | null>(null);
const formRevision = ref(0);
const deleteForm = useSmartForm<EmptyForm>({});
const rows = computed<Record<string, unknown>[]>(() => relationRows(props.initiatives));
const meta = computed(() => relationMeta(props.initiatives));
const links = computed(() => relationLinks(props.initiatives));
const searchQuery = computed(() => relationSearch(props.initiatives, SEARCH_PARAM));
const sortState = computed(() => relationSort(props.initiatives, SORT_PARAM, DIRECTION_PARAM));
const formTitle = computed(() => editing.value === null ? 'Create initiative' : 'Edit initiative');
const columns = [
    { key: 'id', label: 'ID', sortable: true, class: 'w-20 flex-none', headerClass: 'w-20 flex-none' },
    { key: 'title', label: 'Initiative', sortable: true, class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'meta', label: 'Meta', class: 'flex-1', headerClass: 'flex-1' },
    { key: 'sort_order', label: 'Sort', sortable: true, class: 'w-24 flex-none', headerClass: 'w-24 flex-none' },
    { key: 'actions', label: 'Actions', class: 'w-44 flex-none', headerClass: 'w-44 flex-none text-right' },
];
function collectionUrl(): string { return `${props.basePath}/countdowns/${props.countdownId}/initiatives`; }
function itemUrl(initiative: InitiativeRecord): string { return `${collectionUrl()}/${initiative.id}`; }
function contextualUrl(url: string): string { return urlWithCurrentBackofficeQuery(url, TAB_VALUE); }
function asInitiative(item: Record<string, unknown>): InitiativeRecord { return item as unknown as InitiativeRecord; }
function startCreate(): void { editing.value = null; formRevision.value += 1; showForm.value = true; }
function startEdit(initiative: InitiativeRecord): void { editing.value = initiative; formRevision.value += 1; showForm.value = true; }
function closeForm(): void { showForm.value = false; editing.value = null; formRevision.value += 1; }
function handleSearch(query: string): void { updateRelationSearch(SEARCH_PARAM, PAGE_PARAM, TAB_VALUE, query); }
function handleSort(sort: DataTableSort): void { updateRelationSort(SORT_PARAM, DIRECTION_PARAM, PAGE_PARAM, TAB_VALUE, sort); }
function displayPeriod(initiative: InitiativeRecord): string {
    const startsAt = formatBackofficeDateTime(initiative.starts_at);
    const endsAt = formatBackofficeDateTime(initiative.ends_at);

    if (startsAt && endsAt) {
        return `${startsAt} → ${endsAt}`;
    }

    return startsAt || endsAt || 'no dates';
}
function confirmDestroy(): void {
    const initiative = pendingDelete.value;
    if (initiative === null) return;
    deletingId.value = initiative.id;
    deleteForm.delete(contextualUrl(itemUrl(initiative)), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            pendingDelete.value = null;
            if (editing.value?.id === initiative.id) {
                closeForm();
            }
        },
        onFinish: () => { deletingId.value = null; },
    });
}
</script>

<template>
    <section class="space-y-4">
        <div>
            <p class="font-semibold">Initiatives</p>
            <p class="text-sm text-ui-muted-foreground">Create, edit and delete action initiatives.</p>
        </div>
        <ServerDataTable
            :data="rows"
            :columns="columns"
            :meta="meta"
            :links="links"
            item-key="id"
            searchable
            search-placeholder="Search initiatives..."
            :search-query="searchQuery"
            :sort="sortState"
            enable-row-click
            density="comfortable"
            :ui="flatRelationTableUi"
            @search="handleSearch"
            @sort-change="handleSort"
            @row-click="(item) => startEdit(asInitiative(item))"
        >
            <template #toolbar="{ searchQuery: tableSearchQuery, updateSearch }">
                <div class="flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <SearchBox :model-value="String(tableSearchQuery)" placeholder="Search initiatives..." :ui="{ root: 'w-full md:w-96' }" @update:model-value="updateSearch" />
                    <Button :icon="Plus" size="sm" @click="startCreate">Add initiative</Button>
                </div>
            </template>
            <template #cell-id="{ item }"><span class="font-mono text-xs text-ui-muted-foreground">#{{ item.id }}</span></template>
            <template #cell-title="{ item }"><div class="font-medium">{{ item.title }}</div><div class="text-sm text-ui-muted-foreground">{{ item.excerpt }}</div></template>
            <template #cell-meta="{ item }"><div class="flex flex-wrap gap-2"><Badge :label="String(item.locale)" variant="soft" /><Badge :label="String(item.type)" variant="soft" /><Badge :label="String(item.content_type ?? 'article')" variant="soft" /><Badge v-if="item.external_provider" :label="String(item.external_provider)" variant="soft" /><Badge :label="item.is_featured ? 'featured' : 'standard'" :color="item.is_featured ? 'success' : 'secondary'" variant="soft" /></div><div class="mt-1 text-sm text-ui-muted-foreground">{{ displayPeriod(asInitiative(item)) }}</div></template>
            <template #cell-sort_order="{ item }"><span class="font-mono text-xs text-ui-muted-foreground">{{ item.sort_order }}</span></template>
            <template #cell-actions="{ item }"><div class="flex items-center justify-end gap-2" data-no-row-click><Button variant="secondary" size="sm" :icon="Edit3" @click="startEdit(asInitiative(item))">Edit</Button><Button variant="danger" size="sm" :icon="Trash2" :loading="deleteForm.processing && deletingId === item.id" :disabled="deleteForm.processing" @click="pendingDelete = asInitiative(item)">Delete</Button></div></template>
            <template #empty><EmptyState title="No initiatives" description="No initiatives match this search." :icon="Plus" /></template>
        </ServerDataTable>

        <Modal :show="showForm" :title="formTitle" max-width="3xl" @close="closeForm">
            <InitiativeForm :key="`${editing?.id ?? 'new'}-${formRevision}`" :options="options" :initiative="editing ?? undefined" :submit-url="contextualUrl(editing ? itemUrl(editing) : collectionUrl())" :method="editing ? 'put' : 'post'" :submit-label="editing ? 'Save initiative' : 'Create initiative'" @saved="closeForm" @cancel="closeForm" />
        </Modal>

        <DeleteConfirmationModal :show="pendingDelete !== null" title="Delete initiative" :description="`Delete initiative ${pendingDelete?.title ?? ''}?`" :loading="deleteForm.processing" @close="pendingDelete = null" @confirm="confirmDestroy" />
    </section>
</template>
