<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { Edit3, Plus, Target, Trash2 } from 'lucide-vue-next';
import { Badge, Button, Card, DataTable, Image as UiImage } from '@simone-bianco/vue-ui-components';
import { useSmartForm } from '@simone-bianco/vue-form-core';
import BackofficeShell from '@/Components/Backoffice/Layout/BackofficeShell.vue';
import DeleteConfirmationModal from '@/Components/Backoffice/Shared/DeleteConfirmationModal.vue';
import { useBackofficePath } from '@/Components/Backoffice/Doomsday/useBackofficePath';
import type { BackofficeOptions, CountdownSummary } from '@/Components/Backoffice/Doomsday/types';
import type { BackofficeSection } from '@/Components/Backoffice/Layout/types';

type EmptyForm = Record<string, never>;

const props = defineProps<{
    readonly countdowns: readonly CountdownSummary[];
    readonly options: BackofficeOptions;
}>();

const { backofficePath, normalizedBackofficePath, counts } = useBackofficePath();
const activeSection = ref<BackofficeSection>('countdowns');
const pendingDelete = ref<CountdownSummary | null>(null);
const deletingId = ref<number | null>(null);
const deleteForm = useSmartForm<EmptyForm>({});
const rows = computed<Record<string, unknown>[]>(() => props.countdowns.map((countdown) => ({ ...countdown })));
const columns = [
    { key: 'image', label: '', class: 'w-20 flex-none', headerClass: 'w-20 flex-none' },
    { key: 'title', label: 'Countdown', class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'status', label: 'Status', class: 'w-40 flex-none', headerClass: 'w-40 flex-none' },
    { key: 'relations', label: 'Relations', class: 'w-48 flex-none', headerClass: 'w-48 flex-none' },
    { key: 'actions', label: 'Actions', class: 'w-40 flex-none', headerClass: 'w-40 flex-none text-right' },
];

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
    <BackofficeShell v-model:active-section="activeSection" title="Countdowns" subtitle="Manage public Doomsday countdown records." :backoffice-path="backofficePath" :counts="counts">
        <template #actions>
            <Button :icon="Plus" @click="visit(countdownUrl(undefined, '/create'))">Create countdown</Button>
        </template>

        <Card :ui="{ body: 'p-0' }">
            <DataTable :items="rows" :columns="columns" item-key="id" density="comfortable">
                <template #cell-image="{ item }">
                    <UiImage v-if="item.image_path" :src="String(item.image_path)" :alt="String(item.slug)" aspect-ratio="1/1" rounded="lg" :ui="{ root: 'h-14 w-14 overflow-hidden' }" />
                    <div v-else class="flex h-14 w-14 items-center justify-center rounded-lg bg-ui-muted text-ui-muted-foreground">
                        <Target class="h-5 w-5" />
                    </div>
                </template>
                <template #cell-title="{ item }">
                    <div class="font-medium">{{ item.title?.en ?? item.slug }}</div>
                    <div class="text-sm text-ui-muted-foreground">{{ item.slug }} · sort {{ item.sort_order }}</div>
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
                    <div class="flex items-center justify-end gap-2">
                        <Button variant="secondary" size="sm" :icon="Edit3" @click="visit(countdownUrl(asCountdown(item), '/edit'))">Edit</Button>
                        <Button variant="danger" size="sm" :icon="Trash2" :loading="deleteForm.processing && deletingId === item.id" :disabled="deleteForm.processing" @click="pendingDelete = asCountdown(item)">Delete</Button>
                    </div>
                </template>
            </DataTable>
        </Card>

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
