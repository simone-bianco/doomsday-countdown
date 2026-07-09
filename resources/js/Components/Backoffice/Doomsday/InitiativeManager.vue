<script setup lang="ts">
import { computed, ref } from 'vue';
import { Edit3, Plus, Trash2 } from 'lucide-vue-next';
import { Badge, Button, Card, DataTable, EmptyState } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import DeleteConfirmationModal from '@/Components/Backoffice/Shared/DeleteConfirmationModal.vue';
import InitiativeForm from '@/Components/Backoffice/Doomsday/InitiativeForm.vue';
import type { BackofficeOptions, InitiativeRecord } from '@/Components/Backoffice/Doomsday/types';

type EmptyForm = Record<string, never>;
const props = defineProps<{ readonly basePath: string; readonly countdownId: number; readonly initiatives: readonly InitiativeRecord[]; readonly options: BackofficeOptions }>();
const showForm = ref(false);
const editing = ref<InitiativeRecord | null>(null);
const pendingDelete = ref<InitiativeRecord | null>(null);
const deletingId = ref<number | null>(null);
const deleteForm = useSmartForm<EmptyForm>({});
const rows = computed<Record<string, unknown>[]>(() => props.initiatives.map((item) => ({ ...item })));
const columns = [
    { key: 'title', label: 'Initiative', class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'meta', label: 'Meta', class: 'flex-1', headerClass: 'flex-1' },
    { key: 'actions', label: 'Actions', class: 'w-44 flex-none', headerClass: 'w-44 flex-none text-right' },
];
function collectionUrl(): string { return `${props.basePath}/countdowns/${props.countdownId}/initiatives`; }
function itemUrl(initiative: InitiativeRecord): string { return `${collectionUrl()}/${initiative.id}`; }
function asInitiative(item: Record<string, unknown>): InitiativeRecord { return item as unknown as InitiativeRecord; }
function startCreate(): void { editing.value = null; showForm.value = true; }
function startEdit(initiative: InitiativeRecord): void { editing.value = initiative; showForm.value = true; }
function closeForm(): void { showForm.value = false; editing.value = null; }
function confirmDestroy(): void {
    const initiative = pendingDelete.value;
    if (initiative === null) return;
    deletingId.value = initiative.id;
    deleteForm.delete(itemUrl(initiative), { preserveScroll: true, onSuccess: () => { pendingDelete.value = null; }, onFinish: () => { deletingId.value = null; } });
}
</script>

<template>
    <Card :ui="{ body: 'space-y-4 p-4' }">
        <div class="flex flex-wrap items-center justify-between gap-3"><div><p class="font-semibold">Initiatives</p><p class="text-sm text-ui-muted-foreground">Create, edit and delete action initiatives.</p></div><Button :icon="Plus" size="sm" @click="startCreate">Add initiative</Button></div>
        <InitiativeForm v-if="showForm" :key="editing?.id ?? 'new'" :options="options" :initiative="editing ?? undefined" :submit-url="editing ? itemUrl(editing) : collectionUrl()" :method="editing ? 'put' : 'post'" :submit-label="editing ? 'Save initiative' : 'Create initiative'" @saved="closeForm" @cancel="closeForm" />
        <DataTable v-if="rows.length" :items="rows" :columns="columns" item-key="id">
            <template #cell-title="{ item }"><div class="font-medium">{{ item.title }}</div><div class="text-sm text-ui-muted-foreground">{{ item.excerpt }}</div></template>
            <template #cell-meta="{ item }"><div class="flex flex-wrap gap-2"><Badge :label="String(item.locale)" variant="soft" /><Badge :label="String(item.type)" variant="soft" /><Badge :label="item.is_featured ? 'featured' : 'standard'" :color="item.is_featured ? 'success' : 'secondary'" variant="soft" /></div></template>
            <template #cell-actions="{ item }"><div class="flex items-center justify-end gap-2"><Button variant="secondary" size="sm" :icon="Edit3" @click="startEdit(asInitiative(item))">Edit</Button><Button variant="danger" size="sm" :icon="Trash2" :loading="deleteForm.processing && deletingId === item.id" :disabled="deleteForm.processing" @click="pendingDelete = asInitiative(item)">Delete</Button></div></template>
        </DataTable>
        <EmptyState v-else title="No initiatives" description="Create the first initiative for this countdown." :icon="Plus" />
        <DeleteConfirmationModal :show="pendingDelete !== null" title="Delete initiative" :description="`Delete initiative ${pendingDelete?.title ?? ''}?`" :loading="deleteForm.processing" @close="pendingDelete = null" @confirm="confirmDestroy" />
    </Card>
</template>
