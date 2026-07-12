<script setup lang="ts">
import { computed } from 'vue';
import { Card } from '@simone-bianco/vue-ui-components';
import KeyIndicatorsCard from './KeyIndicatorsCard.vue';
import VisualizationChart from './VisualizationChart.vue';
import VisualizationEvidence from './VisualizationEvidence.vue';
import { t } from '@/i18n';
import type { CountdownStatisticsData, VisualizationData } from '@/types/generated';

type KpiItem = { readonly label: string; readonly value: string };

const props = withDefaults(defineProps<{
    readonly section: CountdownStatisticsData;
    readonly mobile?: boolean;
}>(), {
    mobile: false,
});

function isRecord(value: unknown): value is Record<string, unknown> {
    return typeof value === 'object' && value !== null && !Array.isArray(value);
}

function kpiItems(visualization: VisualizationData): KpiItem[] {
    const payload = visualization.payload as unknown;
    const items = isRecord(payload) && Array.isArray(payload.items) ? payload.items : [];

    return items.filter(isRecord).map((item) => ({
        label: String(item.label ?? ''),
        value: String(item.value ?? ''),
    })).filter((item) => item.label !== '' && item.value !== '');
}

const keyIndicators = computed((): VisualizationData | null => props.section.visualizations.find((item) => item.key === 'key_indicators') ?? null);
const otherVisualizations = computed(() => props.section.visualizations.filter((item) => item.key !== 'key_indicators'));
</script>

<template>
    <KeyIndicatorsCard :visualization="keyIndicators" />
    <Card v-for="visualization in otherVisualizations" :key="visualization.key" :ui="{ root: 'doomsday-card min-w-0 rounded-xl', body: 'overflow-visible p-5 sm:p-6' }">
        <h3 class="doomsday-display mb-4 text-white">{{ visualization.title || t('statistics') }}</h3>
        <VisualizationChart
            v-if="visualization.type === 'line' || visualization.type === 'area' || visualization.type === 'bar'"
            :payload="visualization.payload"
            :type="visualization.type"
            :sources="visualization.sources"
            :explanation="visualization.explanation"
            :reasoning="visualization.reasoning"
            :mobile="mobile"
        />
        <div v-else-if="visualization.type === 'kpi'" class="space-y-4">
            <div class="grid gap-3 sm:grid-cols-2">
                <div v-for="item in kpiItems(visualization)" :key="item.label" class="rounded-lg border border-white/10 bg-black/25 p-4">
                    <p class="text-sm text-ui-muted-foreground">{{ item.label }}</p>
                    <p class="mt-2 text-xl text-white">{{ item.value }}</p>
                </div>
            </div>
            <VisualizationEvidence :sources="visualization.sources" :explanation="visualization.explanation"
            :reasoning="visualization.reasoning" />
        </div>
        <p class="mt-4 text-sm text-ui-muted-foreground">{{ visualization.description }}</p>
    </Card>
</template>
