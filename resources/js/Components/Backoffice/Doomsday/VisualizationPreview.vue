<script setup lang="ts">
import { computed } from 'vue';
import { AlertTriangle } from 'lucide-vue-next';
import { Badge, Card } from '@simone-bianco/vue-ui-components';
import KeyIndicatorsCard from '@/Components/Doomsday/KeyIndicatorsCard.vue';
import VisualizationChart from '@/Components/Doomsday/VisualizationChart.vue';
import type { VisualizationPayload, VisualizationRecord } from '@/Components/Backoffice/Doomsday/types';
import type { VisualizationData } from '@/types/generated';

const props = defineProps<{
    readonly visualization: VisualizationRecord | {
        readonly key: string;
        readonly type: string;
        readonly title: { readonly en?: string };
        readonly description?: { readonly en?: string } | null;
        readonly explanation?: { readonly en?: string } | null;
        readonly sources: readonly string[];
        readonly reasoning: { readonly en?: string };
        readonly payload: VisualizationPayload;
        readonly schema_version: number;
        readonly sort_order: number;
    };
}>();

const title = computed(() => props.visualization.title.en ?? props.visualization.key);
const isChart = computed(() => ['line', 'area', 'bar'].includes(props.visualization.type));
const isKpi = computed(() => props.visualization.type === 'kpi');
const publicVisualization = computed<VisualizationData>(() => ({
    key: props.visualization.key,
    type: props.visualization.type,
    title: title.value,
    description: props.visualization.description?.en ?? null,
    explanation: props.visualization.explanation?.en ?? null,
    sources: [...props.visualization.sources],
    reasoning: props.visualization.reasoning.en ?? '',
    payload: props.visualization.payload as Array<unknown>,
    schema_version: props.visualization.schema_version,
    sort_order: props.visualization.sort_order,
}));
</script>

<template>
    <Card :ui="{ body: 'space-y-4 p-4' }">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="font-semibold">{{ title }}</p>
                <p class="text-sm text-ui-muted-foreground">{{ visualization.key }} · {{ visualization.type }}</p>
            </div>
            <Badge :label="visualization.type" variant="soft" />
        </div>

        <VisualizationChart
            v-if="isChart"
            :payload="visualization.payload"
            :type="visualization.type"
            :sources="visualization.sources"
            :explanation="visualization.explanation?.en ?? ''"
            :reasoning="visualization.reasoning.en ?? ''"
            compact
        />
        <KeyIndicatorsCard v-else-if="isKpi" :visualization="publicVisualization" />
        <div v-else class="rounded-lg border border-dashed border-ui-border p-4 text-sm text-ui-muted-foreground">
            <Badge label="Preview unavailable" :icon="AlertTriangle" color="warning" variant="soft" />
            <p class="mt-3">Only line, area, bar and KPI payloads can be edited with live preview in this backoffice UI.</p>
        </div>
    </Card>
</template>

