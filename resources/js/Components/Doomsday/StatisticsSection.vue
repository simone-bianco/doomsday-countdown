<script setup lang="ts">
import { computed } from 'vue';
import { Card } from '@simone-bianco/vue-ui-components';
import KeyIndicatorsCard from './KeyIndicatorsCard.vue';
import VisualizationChart from './VisualizationChart.vue';
import { t } from '@/i18n';
import type { CountdownStatisticsData, VisualizationData } from '@/types/generated';

const props = defineProps<{
    readonly section: CountdownStatisticsData;
}>();

const keyIndicators = computed((): VisualizationData | null => props.section.visualizations.find((item) => item.key === 'key_indicators') ?? null);
const otherVisualizations = computed(() => props.section.visualizations.filter((item) => item.key !== 'key_indicators'));
</script>

<template>
    <KeyIndicatorsCard :visualization="keyIndicators" />
    <Card v-for="visualization in otherVisualizations" :key="visualization.key" :ui="{ root: 'doomsday-card rounded-xl', body: 'p-5 sm:p-6' }">
        <h3 class="doomsday-display mb-4 text-white">{{ visualization.title || t('statistics') }}</h3>
        <VisualizationChart v-if="visualization.type === 'line' || visualization.type === 'area'" :payload="visualization.payload" />
        <p class="mt-4 text-sm text-ui-muted-foreground">{{ visualization.description }}</p>
    </Card>
</template>
