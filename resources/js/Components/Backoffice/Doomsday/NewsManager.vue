<script setup lang="ts">
import { computed, ref } from 'vue';
import { Edit3, Plus, Trash2 } from 'lucide-vue-next';
import { Badge, Button, EmptyState, Modal, SearchBox, ServerDataTable } from '@simone-bianco/vue-ui-components';
import type { DataTableSort } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import DeleteConfirmationModal from '@/Components/Backoffice/Shared/DeleteConfirmationModal.vue';
import NewsForm from '@/Components/Backoffice/Doomsday/NewsForm.vue';
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
import type { BackofficeOptions, BackofficeRelationCollection, NewsRecord } from '@/Components/Backoffice/Doomsday/types';

const TAB_VALUE = 'news';
const SEARCH_PARAM = 'news_search';
const PAGE_PARAM = 'news_page';
const SORT_PARAM = 'news_sort';
const DIRECTION_PARAM = 'news_direction';

type EmptyForm = Record<string, never>;
const props = defineProps<{ readonly basePath: string; readonly countdownId: number; readonly news: BackofficeRelationCollection<NewsRecord>; readonly options: BackofficeOptions }>();
const showForm = ref(false);
const editing = ref<NewsRecord | null>(null);
const pendingDelete = ref<NewsRecord | null>(null);
const deletingId = ref<number | null>(null);
const formRevision = ref(0);
const deleteForm = useSmartForm<EmptyForm>({});
const rows = computed<Record<string, unknown>[]>(() => relationRows(props.news));
const meta = computed(() => relationMeta(props.news));
const links = computed(() => relationLinks(props.news));
const searchQuery = computed(() => relationSearch(props.news, SEARCH_PARAM));
const sortState = computed(() => relationSort(props.news, SORT_PARAM, DIRECTION_PARAM));
const formTitle = computed(() => editing.value === null ? 'Create news' : 'Edit news');
const columns = [
    { key: 'id', label: 'ID', sortable: true, class: 'w-20 flex-none', headerClass: 'w-20 flex-none' },
    { key: 'title', label: 'News', sortable: true, class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'meta', label: 'Meta', class: 'flex-1', headerClass: 'flex-1' },
    { key: 'sort_order', label: 'Sort', sortable: true, class: 'w-24 flex-none', headerClass: 'w-24 flex-none' },
    { key: 'actions', label: 'Actions', class: 'w-44 flex-none', headerClass: 'w-44 flex-none text-right' },
];
function collectionUrl(): string { return `${props.basePath}/countdowns/${props.countdownId}/news`; }
function itemUrl(news: NewsRecord): string { return `${collectionUrl()}/${news.id}`; }
function contextualUrl(url: string): string { return urlWithCurrentBackofficeQuery(url, TAB_VALUE); }
function asNews(item: Record<string, unknown>): NewsRecord { return item as unknown as NewsRecord; }
function startCreate(): void { editing.value = null; formRevision.value += 1; showForm.value = true; }
function startEdit(news: NewsRecord): void { editing.value = news; formRevision.value += 1; showForm.value = true; }
function closeForm(): void { showForm.value = false; editing.value = null; formRevision.value += 1; }
function handleSearch(query: string): void { updateRelationSearch(SEARCH_PARAM, PAGE_PARAM, TAB_VALUE, query); }
function handleSort(sort: DataTableSort): void { updateRelationSort(SORT_PARAM, DIRECTION_PARAM, PAGE_PARAM, TAB_VALUE, sort); }
function displayPublishedAt(value: string | null): string { return formatBackofficeDateTime(value) || 'not published'; }
function confirmDestroy(): void {
    const news = pendingDelete.value;
    if (news === null) return;
    deletingId.value = news.id;
    deleteForm.delete(contextualUrl(itemUrl(news)), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            pendingDelete.value = null;
            if (editing.value?.id === news.id) {
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
            <p class="font-semibold">News</p>
            <p class="text-sm text-ui-muted-foreground">Create, edit and delete localized countdown news.</p>
        </div>
        <ServerDataTable
            :data="rows"
            :columns="columns"
            :meta="meta"
            :links="links"
            item-key="id"
            searchable
            search-placeholder="Search news..."
            :search-query="searchQuery"
            :sort="sortState"
            enable-row-click
            density="comfortable"
            :ui="flatRelationTableUi"
            @search="handleSearch"
            @sort-change="handleSort"
            @row-click="(item) => startEdit(asNews(item))"
        >
            <template #toolbar="{ searchQuery: tableSearchQuery, updateSearch }">
                <div class="flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <SearchBox :model-value="String(tableSearchQuery)" placeholder="Search news..." :ui="{ root: 'w-full md:w-96' }" @update:model-value="updateSearch" />
                    <Button :icon="Plus" size="sm" @click="startCreate">Add news</Button>
                </div>
            </template>
            <template #cell-id="{ item }"><span class="font-mono text-xs text-ui-muted-foreground">#{{ item.id }}</span></template>
            <template #cell-title="{ item }"><div class="font-medium">{{ item.title }}</div><div class="text-sm text-ui-muted-foreground">{{ item.excerpt }}</div></template>
            <template #cell-meta="{ item }"><div class="flex flex-wrap gap-2"><Badge :label="String(item.locale)" variant="soft" /><Badge :label="item.is_featured ? 'featured' : 'standard'" :color="item.is_featured ? 'success' : 'secondary'" variant="soft" /></div><div class="mt-1 text-sm text-ui-muted-foreground">{{ displayPublishedAt(String(item.published_at ?? '') || null) }}</div></template>
            <template #cell-sort_order="{ item }"><span class="font-mono text-xs text-ui-muted-foreground">{{ item.sort_order }}</span></template>
            <template #cell-actions="{ item }"><div class="flex items-center justify-end gap-2" data-no-row-click><Button variant="secondary" size="sm" :icon="Edit3" @click="startEdit(asNews(item))">Edit</Button><Button variant="danger" size="sm" :icon="Trash2" :loading="deleteForm.processing && deletingId === item.id" :disabled="deleteForm.processing" @click="pendingDelete = asNews(item)">Delete</Button></div></template>
            <template #empty><EmptyState title="No news" description="No news items match this search." :icon="Plus" /></template>
        </ServerDataTable>

        <Modal :show="showForm" :title="formTitle" max-width="3xl" @close="closeForm">
            <NewsForm :key="`${editing?.id ?? 'new'}-${formRevision}`" :options="options" :news="editing ?? undefined" :submit-url="contextualUrl(editing ? itemUrl(editing) : collectionUrl())" :method="editing ? 'put' : 'post'" :submit-label="editing ? 'Save news' : 'Create news'" @saved="closeForm" @cancel="closeForm" />
        </Modal>

        <DeleteConfirmationModal :show="pendingDelete !== null" title="Delete news" :description="`Delete news ${pendingDelete?.title ?? ''}?`" :loading="deleteForm.processing" @close="pendingDelete = null" @confirm="confirmDestroy" />
    </section>
</template>
