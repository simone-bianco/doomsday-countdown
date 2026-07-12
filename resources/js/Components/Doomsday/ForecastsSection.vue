<script setup lang="ts">
import { Card } from '@simone-bianco/vue-ui-components';
import VisualizationChart from './VisualizationChart.vue';
import { t } from '@/i18n';
import type { CountdownForecastsData } from '@/types/generated';

withDefaults(defineProps<{
    readonly section: CountdownForecastsData;
    readonly mobile?: boolean;
}>(), {
    mobile: false,
});
</script>

<template>
    <Card :ui="{ root: 'doomsday-card rounded-xl sm:col-span-2', body: 'p-5 sm:p-6' }">
        <h3 class="doomsday-display mb-4 text-white">{{ t('projectionModel') }}</h3>
        <VisualizationChart
            v-if="section.projection_chart"
            :payload="section.projection_chart.payload"
            :type="section.projection_chart.type"
            :sources="section.projection_chart.sources"
            :explanation="section.projection_chart.explanation"
            :reasoning="section.projection_chart.reasoning"
            :mobile="mobile"
        />
        <p class="mt-4 text-sm text-ui-muted-foreground">{{ section.projection_chart?.description }}</p>
    </Card>

    <Card v-for="projection in section.projections" :key="projection.type" :ui="{ root: 'doomsday-card rounded-xl', body: 'p-5' }">
        <p class="doomsday-display text-xs text-ui-primary">{{ projection.type }}</p>
        <h4 class="mt-3 text-lg text-white">{{ projection.title }}</h4>
        <p class="mt-2 text-sm leading-relaxed text-ui-muted-foreground">{{ projection.summary }}</p>
        <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
            <span class="rounded border border-white/10 bg-white/5 p-3">Confidence <strong class="block text-white">{{ projection.confidence_score }}%</strong></span>
            <span class="rounded border border-white/10 bg-white/5 p-3">Probability <strong class="block text-ui-primary">{{ projection.probability_score }}%</strong></span>
        </div>
    </Card>
</template>
