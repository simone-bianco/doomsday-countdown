<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { Edit3, Plus, Target, Trash2 } from 'lucide-vue-next';
import { Badge, Button, Image as UiImage, SearchBox, ServerDataTable } from '@simone-bianco/vue-ui-components';
import type { ServerDataTableUI } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import BackofficeShell from '@/Components/Backoffice/Layout/BackofficeShell.vue';
import DeleteConfirmationModal from '@/Components/Backoffice/Shared/DeleteConfirmationModal.vue';
import { useBackofficePath } from '@/Components/Backoffice/Doomsday/useBackofficePath';
import type { BackofficeOptions, BackofficePaginatedCollection, CountdownSummary } from '@/Components/Backoffice/Doomsday/types';
import type { BackofficeSection } from '@/Components/Backoffice/Layout/types';

type EmptyForm = Record<string, never>;
type SortDirection = 'asc' | 'desc';
type CountdownIndexSortKey = 'id' | 'title' | 'sort_order';
type CountdownIndexSort = { key: CountdownIndexSortKey; direction: SortDirection };

const SORT_KEYS = ['id', 'title', 'sort_order'] as const;

const props = defineProps<{
    readonly countdowns: BackofficePaginatedCollection<CountdownSummary>;
    readonly options: BackofficeOptions;
}>();

const { backofficePath, normalizedBackofficePath, counts } = useBackofficePath();
const activeSection = ref<BackofficeSection>('countdowns');
const pendingDelete = ref<CountdownSummary | null>(null);
const deletingId = ref<number | null>(null);
const deleteForm = useSmartForm<EmptyForm>({});
const rows = computed<Record<string, unknown>[]>(() => props.countdowns.data.map((countdown) => ({ ...countdown })));
const searchQuery = computed(() => props.countdowns.filters?.search ?? currentSearchQuery());
const currentSort = computed<CountdownIndexSort>(() => ({
    key: currentSortKey(),
    direction: currentSortDirection(),
}));
const columns = [
    { key: 'id', label: 'ID', class: 'w-20 flex-none', headerClass: 'w-20 flex-none', sortable: true },
    { key: 'image', label: '', class: 'w-20 flex-none', headerClass: 'w-20 flex-none' },
    { key: 'title', label: 'Countdown', class: 'flex-[2]', headerClass: 'flex-[2]', sortable: true },
    { key: 'sort_order', label: 'Sort', class: 'w-24 flex-none', headerClass: 'w-24 flex-none', sortable: true },
    { key: 'status', label: 'Status', class: 'w-40 flex-none', headerClass: 'w-40 flex-none' },
    { key: 'relations', label: 'Relations', class: 'w-48 flex-none', headerClass: 'w-48 flex-none' },
    { key: 'actions', label: 'Actions', class: 'w-40 flex-none', headerClass: 'w-40 flex-none text-right' },
];
const flatCountdownTableUi = {
    toolbar: 'rounded-none border-0 bg-transparent px-0 pt-0 pb-4',
    root: 'space-y-0 rounded-none border-0 bg-transparent',
    header: 'border-b border-ui-border bg-ui-muted/30',
    row: 'hover:bg-ui-primary/20 focus-within:bg-ui-primary/20',
    footer: 'border-t border-ui-border bg-transparent px-0 py-4',
} satisfies ServerDataTableUI;

function countdownUrl(countdown?: CountdownSummary, suffix = ''): string {
    return countdown === undefined
        ? `${normalizedBackofficePath.value}/countdowns${suffix}`
        : `${normalizedBackofficePath.value}/countdowns/${countdown.id}${suffix}`;
}

function asCountdown(item: Record<string, unknown>): CountdownSummary {
    return item as unknown as CountdownSummary;
}

function visit(url: string): void {
    router.visit(url);
}

function currentSearchQuery(): string {
    if (typeof window === 'undefined') {
        return '';
    }

    return new URL(window.location.href).searchParams.get('search') ?? '';
}

function queryParam(key: string): string | null {
    if (typeof window === 'undefined') {
        return null;
    }

    return new URL(window.location.href).searchParams.get(key);
}

function normalizeSortKey(value: string | null | undefined): CountdownIndexSortKey {
    return SORT_KEYS.includes(value as CountdownIndexSortKey) ? value as CountdownIndexSortKey : 'sort_order';
}

function normalizeSortDirection(value: string | null | undefined): SortDirection {
    return value === 'desc' ? 'desc' : 'asc';
}

function currentSortKey(): CountdownIndexSortKey {
    return normalizeSortKey(props.countdowns.filters?.sort ?? queryParam('sort'));
}

function currentSortDirection(): SortDirection {
    return normalizeSortDirection(props.countdowns.filters?.direction ?? queryParam('direction'));
}

function queryObject(params: URLSearchParams): Record<string, string> {
    const query: Record<string, string> = {};

    for (const [key, value] of params.entries()) {
        query[key] = value;
    }

    return query;
}

function handleSearch(query: string): void {
    const params = typeof window === 'undefined'
        ? new URLSearchParams()
        : new URLSearchParams(new URL(window.location.href).search);
    const normalizedQuery = query.trim();

    if (normalizedQuery === '') {
        params.delete('search');
    } else {
        params.set('search', normalizedQuery);
    }

    params.set('sort', currentSort.value.key);
    params.set('direction', currentSort.value.direction);
    params.delete('page');

    router.get(countdownUrl(undefined), queryObject(params), {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
}

function handleSortChange(sort: { key: string; direction: string }): void {
    const params = typeof window === 'undefined'
        ? new URLSearchParams()
        : new URLSearchParams(new URL(window.location.href).search);

    params.set('sort', normalizeSortKey(sort.key));
    params.set('direction', normalizeSortDirection(sort.direction));
    params.delete('page');

    router.get(countdownUrl(undefined), queryObject(params), {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
}

function visitEdit(item: Record<string, unknown>): void {
    visit(countdownUrl(asCountdown(item), '/edit'));
}

function imageSource(item: Record<string, unknown>): string {
    const value = item.image_path;
    if (typeof value !== 'string') {
        return '';
    }

    const trimmed = value.trim();
    if (trimmed === '') {
        return '';
    }

    if (/^(?:[a-z][a-z0-9+.-]*:|\/\/|\/)/i.test(trimmed)) {
        return trimmed;
    }

    return `/${trimmed.replace(/^\.?\//, '')}`;
}

function confirmDestroy(): void {
    const countdown = pendingDelete.value;
    if (countdown === null) {
        return;
    }

    deletingId.value = countdown.id;
    deleteForm.delete(countdownUrl(countdown), {
        onSuccess: () => {
            pendingDelete.value = null;
        },
        onFinish: () => {
            deletingId.value = null;
        },
    });
}
</script>

<template>
    <Head title="Countdowns" />
    <BackofficeShell v-model:active-section="activeSection" title="Countdowns" subtitle="Manage public Doomsday Clock records." :backoffice-path="backofficePath" :counts="counts">
        <ServerDataTable
                :data="rows"
                :columns="columns"
                :meta="countdowns.meta"
                :links="countdowns.links"
                item-key="id"
                searchable
                search-placeholder="Search countdowns..."
                :search-query="searchQuery"
                :sort="currentSort"
                enable-row-click
                density="comfortable"
                :ui="flatCountdownTableUi"
                @search="handleSearch"
                @sort-change="handleSortChange"
                @row-click="(item) => visitEdit(item)"
            >
                <template #toolbar="{ searchQuery: tableSearchQuery, updateSearch }">
                    <div class="flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <SearchBox :model-value="String(tableSearchQuery)" placeholder="Search countdowns..." :ui="{ root: 'w-full md:w-96' }" @update:model-value="updateSearch" />
                        <Button :icon="Plus" @click="visit(countdownUrl(undefined, '/create'))">Create countdown</Button>
                    </div>
                </template>
                <template #cell-id="{ item }">
                    <span class="font-mono text-xs text-ui-muted-foreground">#{{ item.id }}</span>
                </template>
                <template #cell-image="{ item }">
                    <UiImage v-if="imageSource(item)" :src="imageSource(item)" :alt="String(item.slug)" aspect-ratio="1/1" rounded="lg" :ui="{ root: 'h-14 w-14 overflow-hidden' }" />
                    <div v-else class="flex h-14 w-14 items-center justify-center rounded-lg bg-ui-muted text-ui-muted-foreground">
                        <Target class="h-5 w-5" />
                    </div>
                </template>
                <template #cell-title="{ item }">
                    <div class="font-medium">{{ item.title?.en ?? item.slug }}</div>
                    <div class="text-sm text-ui-muted-foreground">{{ item.slug }}</div>
                </template>
                <template #cell-sort_order="{ item }">
                    <span class="font-mono text-sm text-ui-muted-foreground">{{ item.sort_order }}</span>
                </template>
                <template #cell-status="{ item }">
                    <div class="space-y-1">
                        <Badge :label="String(item.status)" variant="soft" />
                        <Badge :label="String(item.severity)" color="warning" variant="soft" />
                        <Badge :label="item.is_published ? 'published' : 'draft'" :color="item.is_published ? 'success' : 'secondary'" variant="soft" />
                    </div>
                </template>
                <template #cell-relations="{ item }">
                    <div class="space-y-1 text-sm text-ui-muted-foreground">
                        <p>{{ item.projections_count }} projections</p>
                        <p>{{ item.visualizations_count }} visualizations</p>
                        <p>{{ item.news_count }} news</p>
                        <p>{{ item.initiatives_count }} initiatives</p>
                    </div>
                </template>
                <template #cell-actions="{ item }">
                    <div data-no-row-click class="flex items-center justify-end gap-2">
                        <Button variant="secondary" size="sm" :icon="Edit3" @click="visitEdit(item)">Edit</Button>
                        <Button variant="danger" size="sm" :icon="Trash2" :loading="deleteForm.processing && deletingId === item.id" :disabled="deleteForm.processing" @click="pendingDelete = asCountdown(item)">Delete</Button>
                    </div>
                </template>
        </ServerDataTable>

        <DeleteConfirmationModal
            :show="pendingDelete !== null"
            title="Delete countdown"
            :description="`Delete countdown ${pendingDelete?.slug ?? ''}? Related public data will be removed by backend services.`"
            :loading="deleteForm.processing"
            @close="pendingDelete = null"
            @confirm="confirmDestroy"
        />
    </BackofficeShell>
</template>
