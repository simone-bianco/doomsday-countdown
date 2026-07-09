<script setup lang="ts">
import { computed, ref } from 'vue';
import { Edit3, Plus, Trash2 } from 'lucide-vue-next';
import { Badge, Button, Card, DataTable, EmptyState } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import DeleteConfirmationModal from '@/Components/Backoffice/Shared/DeleteConfirmationModal.vue';
import VisualizationForm from '@/Components/Backoffice/Doomsday/VisualizationForm.vue';
import VisualizationPreview from '@/Components/Backoffice/Doomsday/VisualizationPreview.vue';
import type { BackofficeOptions, VisualizationRecord } from '@/Components/Backoffice/Doomsday/types';

type EmptyForm = Record<string, never>;

const props = defineProps<{
    readonly basePath: string;
    readonly countdownId: number;
    readonly projectionId?: number;
    readonly visualizations: readonly VisualizationRecord[];
    readonly options: BackofficeOptions;
    readonly title: string;
}>();

const showForm = ref(false);
const editing = ref<VisualizationRecord | null>(null);
const pendingDelete = ref<VisualizationRecord | null>(null);
const deletingId = ref<number | null>(null);
const deleteForm = useSmartForm<EmptyForm>({});
const rows = computed<Record<string, unknown>[]>(() => props.visualizations.map((visualization) => ({ ...visualization })));
const expandedRows = computed(() => props.visualizations.map((visualization) => visualization.id));
const columns = [
    { key: 'title', label: 'Visualization', class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'type', label: 'Type', class: 'flex-1', headerClass: 'flex-1' },
    { key: 'actions', label: 'Actions', class: 'w-44 flex-none', headerClass: 'w-44 flex-none text-right' },
];

function collectionUrl(): string {
    if (props.projectionId !== undefined) {
        return `${props.basePath}/countdowns/${props.countdownId}/projections/${props.projectionId}/visualizations`;
    }
    return `${props.basePath}/countdowns/${props.countdownId}/visualizations`;
}

function itemUrl(visualization: VisualizationRecord): string {
    return `${collectionUrl()}/${visualization.id}`;
}

function asVisualization(item: Record<string, unknown>): VisualizationRecord {
    return item as unknown as VisualizationRecord;
}

function startCreate(): void {
    editing.value = null;
    showForm.value = true;
}

function startEdit(visualization: VisualizationRecord): void {
    editing.value = visualization;
    showForm.value = true;
}

function closeForm(): void {
    showForm.value = false;
    editing.value = null;
}

function confirmDestroy(): void {
    const visualization = pendingDelete.value;
    if (visualization === null) return;
    deletingId.value = visualization.id;
    deleteForm.delete(itemUrl(visualization), {
        preserveScroll: true,
        onSuccess: () => { pendingDelete.value = null; },
        onFinish: () => { deletingId.value = null; },
    });
}
</script>

<template>
    <Card :ui="{ body: 'space-y-4 p-4' }">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div><p class="font-semibold">{{ title }}</p><p class="text-sm text-ui-muted-foreground">Create, edit, delete and preview line/area/KPI payloads before saving.</p></div>
            <Button :icon="Plus" size="sm" @click="startCreate">Add visualization</Button>
        </div>

        <VisualizationForm
            v-if="showForm"
            :key="editing?.id ?? 'new'"
            :options="options"
            :visualization="editing ?? undefined"
            :submit-url="editing ? itemUrl(editing) : collectionUrl()"
            :method="editing ? 'put' : 'post'"
            :submit-label="editing ? 'Save visualization' : 'Create visualization'"
            @saved="closeForm"
            @cancel="closeForm"
        />

        <DataTable v-if="rows.length" :items="rows" :columns="columns" item-key="id" expandable :expanded-rows="expandedRows">
            <template #cell-title="{ item }">
                <div class="font-medium">{{ item.title?.en ?? item.key }}</div>
                <div class="text-sm text-ui-muted-foreground">{{ item.key }} · schema {{ item.schema_version }}</div>
            </template>
            <template #cell-type="{ item }"><Badge :label="String(item.type)" variant="soft" /></template>
            <template #cell-actions="{ item }">
                <div class="flex items-center justify-end gap-2">
                    <Button variant="secondary" size="sm" :icon="Edit3" @click="startEdit(asVisualization(item))">Edit</Button>
                    <Button variant="danger" size="sm" :icon="Trash2" :loading="deleteForm.processing && deletingId === item.id" :disabled="deleteForm.processing" @click="pendingDelete = asVisualization(item)">Delete</Button>
                </div>
            </template>
            <template #expansion="{ item }"><VisualizationPreview :visualization="asVisualization(item)" /></template>
        </DataTable>
        <EmptyState v-else title="No visualizations" description="Add a line, area or KPI visualization with live preview." :icon="Plus" />

        <DeleteConfirmationModal :show="pendingDelete !== null" title="Delete visualization" :description="`Delete visualization ${pendingDelete?.key ?? ''}?`" :loading="deleteForm.processing" @close="pendingDelete = null" @confirm="confirmDestroy" />
    </Card>
</template>
