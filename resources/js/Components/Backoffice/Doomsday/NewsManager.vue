<script setup lang="ts">
import { computed, ref } from 'vue';
import { Edit3, Plus, Trash2 } from 'lucide-vue-next';
import { Badge, Button, Card, DataTable, EmptyState } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import DeleteConfirmationModal from '@/Components/Backoffice/Shared/DeleteConfirmationModal.vue';
import NewsForm from '@/Components/Backoffice/Doomsday/NewsForm.vue';
import type { BackofficeOptions, NewsRecord } from '@/Components/Backoffice/Doomsday/types';

type EmptyForm = Record<string, never>;
const props = defineProps<{ readonly basePath: string; readonly countdownId: number; readonly news: readonly NewsRecord[]; readonly options: BackofficeOptions }>();
const showForm = ref(false);
const editing = ref<NewsRecord | null>(null);
const pendingDelete = ref<NewsRecord | null>(null);
const deletingId = ref<number | null>(null);
const deleteForm = useSmartForm<EmptyForm>({});
const rows = computed<Record<string, unknown>[]>(() => props.news.map((item) => ({ ...item })));
const columns = [
    { key: 'title', label: 'News', class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'meta', label: 'Meta', class: 'flex-1', headerClass: 'flex-1' },
    { key: 'actions', label: 'Actions', class: 'w-44 flex-none', headerClass: 'w-44 flex-none text-right' },
];
function collectionUrl(): string { return `${props.basePath}/countdowns/${props.countdownId}/news`; }
function itemUrl(news: NewsRecord): string { return `${collectionUrl()}/${news.id}`; }
function asNews(item: Record<string, unknown>): NewsRecord { return item as unknown as NewsRecord; }
function startCreate(): void { editing.value = null; showForm.value = true; }
function startEdit(news: NewsRecord): void { editing.value = news; showForm.value = true; }
function closeForm(): void { showForm.value = false; editing.value = null; }
function confirmDestroy(): void {
    const news = pendingDelete.value;
    if (news === null) return;
    deletingId.value = news.id;
    deleteForm.delete(itemUrl(news), { preserveScroll: true, onSuccess: () => { pendingDelete.value = null; }, onFinish: () => { deletingId.value = null; } });
}
</script>

<template>
    <Card :ui="{ body: 'space-y-4 p-4' }">
        <div class="flex flex-wrap items-center justify-between gap-3"><div><p class="font-semibold">News</p><p class="text-sm text-ui-muted-foreground">Create, edit and delete localized countdown news.</p></div><Button :icon="Plus" size="sm" @click="startCreate">Add news</Button></div>
        <NewsForm v-if="showForm" :key="editing?.id ?? 'new'" :options="options" :news="editing ?? undefined" :submit-url="editing ? itemUrl(editing) : collectionUrl()" :method="editing ? 'put' : 'post'" :submit-label="editing ? 'Save news' : 'Create news'" @saved="closeForm" @cancel="closeForm" />
        <DataTable v-if="rows.length" :items="rows" :columns="columns" item-key="id">
            <template #cell-title="{ item }"><div class="font-medium">{{ item.title }}</div><div class="text-sm text-ui-muted-foreground">{{ item.excerpt }}</div></template>
            <template #cell-meta="{ item }"><div class="flex flex-wrap gap-2"><Badge :label="String(item.locale)" variant="soft" /><Badge :label="item.is_featured ? 'featured' : 'standard'" :color="item.is_featured ? 'success' : 'secondary'" variant="soft" /></div><div class="mt-1 text-sm text-ui-muted-foreground">{{ item.published_at ?? 'not published' }}</div></template>
            <template #cell-actions="{ item }"><div class="flex items-center justify-end gap-2"><Button variant="secondary" size="sm" :icon="Edit3" @click="startEdit(asNews(item))">Edit</Button><Button variant="danger" size="sm" :icon="Trash2" :loading="deleteForm.processing && deletingId === item.id" :disabled="deleteForm.processing" @click="pendingDelete = asNews(item)">Delete</Button></div></template>
        </DataTable>
        <EmptyState v-else title="No news" description="Create the first news item for this countdown." :icon="Plus" />
        <DeleteConfirmationModal :show="pendingDelete !== null" title="Delete news" :description="`Delete news ${pendingDelete?.title ?? ''}?`" :loading="deleteForm.processing" @close="pendingDelete = null" @confirm="confirmDestroy" />
    </Card>
</template>
