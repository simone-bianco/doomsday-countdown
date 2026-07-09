<script setup lang="ts">
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { Activity, BarChart3, Clock3, KeyRound, Newspaper, Target, Users } from 'lucide-vue-next';
import { Badge, Button, Card, DataTable, Image as UiImage } from '@simone-bianco/vue-ui-components';
import type { CountdownSummary } from '@/Components/Backoffice/Doomsday/types';

export type DashboardMetrics = {
    readonly countdowns: number;
    readonly published: number;
    readonly drafts: number;
    readonly projections: number;
    readonly visualizations: number;
    readonly news: number;
    readonly initiatives: number;
    readonly users: number;
    readonly apiKeys: number;
    readonly activeApiKeys: number;
};

const props = defineProps<{
    readonly backofficePath: string;
    readonly metrics: DashboardMetrics;
    readonly recentCountdowns: readonly CountdownSummary[];
}>();

const normalizedBackofficePath = computed(() => props.backofficePath.replace(/\/+$/g, ''));
const metricCards = computed(() => [
    { label: 'Countdowns', value: props.metrics.countdowns, helper: `${props.metrics.published} published · ${props.metrics.drafts} drafts`, icon: Target },
    { label: 'Relations', value: props.metrics.projections + props.metrics.visualizations, helper: `${props.metrics.projections} projections · ${props.metrics.visualizations} visualizations`, icon: BarChart3 },
    { label: 'Content', value: props.metrics.news + props.metrics.initiatives, helper: `${props.metrics.news} news · ${props.metrics.initiatives} initiatives`, icon: Newspaper },
    { label: 'Administration', value: props.metrics.users, helper: `${props.metrics.activeApiKeys} active API keys`, icon: Users },
]);
const rows = computed<Record<string, unknown>[]>(() => props.recentCountdowns.map((countdown) => ({ ...countdown })));
const columns = [
    { key: 'countdown', label: 'Recent countdowns', class: 'flex-[2]', headerClass: 'flex-[2]' },
    { key: 'status', label: 'Status', class: 'flex-1', headerClass: 'flex-1' },
    { key: 'relations', label: 'Relations', class: 'flex-[2]', headerClass: 'flex-[2]' },
];

function editUrl(countdown: CountdownSummary): string {
    return `${normalizedBackofficePath.value}/countdowns/${countdown.id}/edit`;
}

function visit(url: string): void {
    router.visit(url);
}
</script>

<template>
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <Card v-for="metric in metricCards" :key="metric.label" :ui="{ body: 'flex items-start gap-4 p-5' }">
                <div class="rounded-2xl bg-ui-primary/10 p-3 text-ui-primary">
                    <component :is="metric.icon" class="h-6 w-6" />
                </div>
                <div class="min-w-0">
                    <p class="text-sm text-ui-muted-foreground">{{ metric.label }}</p>
                    <p class="text-3xl font-bold tracking-tight">{{ metric.value }}</p>
                    <p class="mt-1 text-sm text-ui-muted-foreground">{{ metric.helper }}</p>
                </div>
            </Card>
        </div>

        <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_22rem]">
            <DataTable v-if="rows.length > 0" :items="rows" :columns="columns" item-key="id" density="comfortable">
                <template #cell-countdown="{ item }">
                    <div class="flex items-center gap-3">
                        <UiImage v-if="item.image_path" :src="String(item.image_path)" :alt="String(item.slug)" aspect-ratio="1/1" rounded="lg" :ui="{ root: 'h-12 w-12 overflow-hidden' }" />
                        <div v-else class="flex h-12 w-12 items-center justify-center rounded-lg bg-ui-muted text-ui-muted-foreground">
                            <Target class="h-5 w-5" />
                        </div>
                        <div class="min-w-0">
                            <p class="truncate font-medium">{{ item.title?.en ?? item.slug }}</p>
                            <p class="truncate text-sm text-ui-muted-foreground">{{ item.slug }}</p>
                        </div>
                    </div>
                </template>
                <template #cell-status="{ item }">
                    <div class="space-y-1">
                        <Badge :label="String(item.status)" variant="soft" />
                        <Badge :label="item.is_published ? 'published' : 'draft'" :color="item.is_published ? 'success' : 'secondary'" variant="soft" />
                    </div>
                </template>
                <template #cell-relations="{ item }">
                    <div class="space-y-1 text-sm text-ui-muted-foreground">
                        <p>{{ item.projections_count }} projections</p>
                        <p>{{ item.visualizations_count }} visualizations</p>
                        <p>{{ item.news_count }} news · {{ item.initiatives_count }} initiatives</p>
                    </div>
                </template>
            </DataTable>
            <div v-else class="p-6 text-sm text-ui-muted-foreground">No recent countdowns are available yet.</div>

            <div class="space-y-4">
                <Card :ui="{ body: 'space-y-3 p-5' }">
                    <Badge label="CRUD ready" :icon="Activity" color="success" variant="soft" />
                    <p class="text-sm text-ui-muted-foreground">Manage countdown records and all relation entities from the Countdowns edit page.</p>
                    <Button variant="secondary" :icon="Target" @click="visit(`${normalizedBackofficePath}/countdowns`)">Open countdowns</Button>
                </Card>
                <Card :ui="{ body: 'space-y-3 p-5' }">
                    <Badge label="API keys" :icon="KeyRound" :color="metrics.activeApiKeys > 0 ? 'success' : 'warning'" variant="soft" />
                    <p class="text-sm text-ui-muted-foreground">{{ metrics.activeApiKeys }} of {{ metrics.apiKeys }} OpenAI keys are active.</p>
                    <Button variant="secondary" :icon="Clock3" @click="visit(`${normalizedBackofficePath}/openai-keys`)">Manage keys</Button>
                </Card>
            </div>
        </div>
    </div>
</template>
