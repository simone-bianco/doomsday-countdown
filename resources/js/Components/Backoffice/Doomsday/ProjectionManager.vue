<script setup lang="ts">
import { computed, ref } from 'vue';
import { Edit3, Plus, Trash2 } from 'lucide-vue-next';
import { Button, Card, DataTable, EmptyState } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import DeleteConfirmationModal from '@/Components/Backoffice/Shared/DeleteConfirmationModal.vue';
import ProjectionForm from '@/Components/Backoffice/Doomsday/ProjectionForm.vue';
import VisualizationManager from '@/Components/Backoffice/Doomsday/VisualizationManager.vue';
import type { BackofficeOptions, ProjectionRecord } from '@/Components/Backoffice/Doomsday/types';

type EmptyForm = Record<string, never>;

const props = defineProps<{
    readonly basePath: string;
    readonly countdownId: number;
    readonly projections: readonly ProjectionRecord[];
    readonly options: BackofficeOptions;
}>();

const showForm = ref(false);
const editing = ref<ProjectionRecord | null>(null);
const pendingDelete = ref<ProjectionRecord | null>(null);
const deletingId = ref<number | null>(null);
const deleteForm = useSmartForm<EmptyForm>({});
const rows = computed<Record<string, unknown>[]>(() => props.projections.map((projection) => ({ ...projection })));
const columns = [
    { key: 'title', label: 'Projection', class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'scores', label: 'Scores', class: 'flex-1', headerClass: 'flex-1' },
    { key: 'actions', label: 'Actions', class: 'w-44 flex-none', headerClass: 'w-44 flex-none text-right' },
];

function collectionUrl(): string { return `${props.basePath}/countdowns/${props.countdownId}/projections`; }
function itemUrl(projection: ProjectionRecord): string { return `${collectionUrl()}/${projection.id}`; }
function asProjection(item: Record<string, unknown>): ProjectionRecord { return item as unknown as ProjectionRecord; }
function startCreate(): void { editing.value = null; showForm.value = true; }
function startEdit(projection: ProjectionRecord): void { editing.value = projection; showForm.value = true; }
function closeForm(): void { showForm.value = false; editing.value = null; }
function confirmDestroy(): void {
    const projection = pendingDelete.value;
    if (projection === null) return;
    deletingId.value = projection.id;
    deleteForm.delete(itemUrl(projection), { preserveScroll: true, onSuccess: () => { pendingDelete.value = null; }, onFinish: () => { deletingId.value = null; } });
}
</script>

<template>
    <Card :ui="{ body: 'space-y-4 p-4' }">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div><p class="font-semibold">Projections</p><p class="text-sm text-ui-muted-foreground">Manage scenario projections and nested projection visualizations.</p></div>
            <Button :icon="Plus" size="sm" @click="startCreate">Add projection</Button>
        </div>

        <ProjectionForm v-if="showForm" :key="editing?.id ?? 'new'" :options="options" :projection="editing ?? undefined" :submit-url="editing ? itemUrl(editing) : collectionUrl()" :method="editing ? 'put' : 'post'" :submit-label="editing ? 'Save projection' : 'Create projection'" @saved="closeForm" @cancel="closeForm" />

        <DataTable v-if="rows.length" :items="rows" :columns="columns" item-key="id" expandable>
            <template #cell-title="{ item }"><div class="font-medium">{{ item.title?.en ?? 'Untitled projection' }}</div><div class="text-sm text-ui-muted-foreground">{{ item.type }} · {{ item.target_date ?? 'no target date' }}</div></template>
            <template #cell-scores="{ item }"><div class="text-sm text-ui-muted-foreground">confidence {{ item.confidence_score }} · probability {{ item.probability_score }}</div></template>
            <template #cell-actions="{ item }"><div class="flex items-center justify-end gap-2"><Button variant="secondary" size="sm" :icon="Edit3" @click="startEdit(asProjection(item))">Edit</Button><Button variant="danger" size="sm" :icon="Trash2" :loading="deleteForm.processing && deletingId === item.id" :disabled="deleteForm.processing" @click="pendingDelete = asProjection(item)">Delete</Button></div></template>
            <template #expansion="{ item }"><VisualizationManager :base-path="basePath" :countdown-id="countdownId" :projection-id="Number(item.id)" :visualizations="asProjection(item).visualizations" :options="options" title="Projection visualizations" /></template>
        </DataTable>
        <EmptyState v-else title="No projections" description="Create the first projection for this countdown." :icon="Plus" />

        <DeleteConfirmationModal :show="pendingDelete !== null" title="Delete projection" :description="`Delete projection ${pendingDelete?.title.en ?? ''}? Nested visualizations will be removed by backend services.`" :loading="deleteForm.processing" @close="pendingDelete = null" @confirm="confirmDestroy" />
    </Card>
</template>
