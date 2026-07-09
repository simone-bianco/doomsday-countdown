<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { AlertTriangle } from 'lucide-vue-next';
import { Badge, Card, Textarea, TextInput } from '@simone-bianco/vue-ui-components';
import VisualizationPreview from '@/Components/Backoffice/Doomsday/VisualizationPreview.vue';
import { chartText, defaultPayload, isRecord, kpiText, parseChartPayload, parseKpiPayload } from '@/Components/Backoffice/Doomsday/formHelpers';
import type { VisualizationPayload } from '@/Components/Backoffice/Doomsday/types';

const typeModel = defineModel<string>('type', { required: true });
const payloadModel = defineModel<VisualizationPayload>('payload', { required: true });
const validModel = defineModel<boolean>('valid', { default: true });

const labelsText = ref('');
const unitText = ref('');
const seriesText = ref('');
const itemsText = ref('');
const isHydrating = ref(false);

const isChart = computed(() => ['line', 'area'].includes(typeModel.value));
const isKpi = computed(() => typeModel.value === 'kpi');
const isSupported = computed(() => isChart.value || isKpi.value);
const errors = computed(() => {
    if (!isSupported.value) {
        return ['Live editing is available only for line, area and KPI visualizations.'];
    }

    if (isChart.value) {
        const payload = payloadModel.value;
        if (!isRecord(payload) || !Array.isArray(payload.labels) || payload.labels.length === 0) {
            return ['Add at least one chart label.'];
        }
        const series = Array.isArray(payload.series) ? payload.series : [];
        if (series.length === 0) {
            return ['Add at least one numeric series.'];
        }
    }

    if (isKpi.value) {
        const payload = payloadModel.value;
        if (!isRecord(payload) || !Array.isArray(payload.items) || payload.items.length === 0) {
            return ['Add at least one KPI item.'];
        }
    }

    return [];
});
const hasErrors = computed(() => errors.value.length > 0);
const previewVisualization = computed(() => ({
    key: 'draft-preview',
    type: typeModel.value,
    title: { en: 'Draft preview' },
    description: { en: 'Live preview before saving' },
    payload: payloadModel.value,
    schema_version: 1,
    sort_order: 0,
}));

function hydrateText(payload: unknown): void {
    isHydrating.value = true;
    const chart = chartText(payload);
    labelsText.value = chart.labels;
    unitText.value = chart.unit;
    seriesText.value = chart.series;
    itemsText.value = kpiText(payload);
    isHydrating.value = false;
}

function syncPayload(): void {
    if (isHydrating.value) {
        return;
    }

    if (isChart.value) {
        payloadModel.value = parseChartPayload(labelsText.value, unitText.value, seriesText.value);
    } else if (isKpi.value) {
        payloadModel.value = parseKpiPayload(itemsText.value);
    }
}

hydrateText(payloadModel.value);

watch(() => typeModel.value, (type, previousType) => {
    if (previousType === undefined || type === previousType) {
        return;
    }

    payloadModel.value = defaultPayload(type);
    hydrateText(payloadModel.value);
});

watch(errors, () => {
    validModel.value = !hasErrors.value;
}, { immediate: true });

watch([labelsText, unitText, seriesText, itemsText], syncPayload, { flush: 'sync' });
</script>

<template>
    <Card :ui="{ body: 'space-y-4 p-4' }">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="font-semibold">Payload editor</p>
                <p class="text-sm text-ui-muted-foreground">Structured payload with live pre-save preview.</p>
            </div>
            <Badge :label="typeModel" :color="isSupported ? 'success' : 'warning'" variant="soft" />
        </div>

        <div v-if="isChart" class="space-y-4">
            <TextInput v-model="labelsText" label="Labels" helper-text="Comma-separated labels, for example: Now, 2030, 2050" />
            <TextInput v-model="unitText" label="Unit" helper-text="Optional chart unit, for example % or ppm." />
            <Textarea v-model="seriesText" label="Series" :rows="4" helper-text="One series per line: Scenario: 20, 42, 64" />
        </div>

        <div v-else-if="isKpi" class="space-y-4">
            <Textarea v-model="itemsText" label="KPI items" :rows="5" helper-text="One KPI per line: Label|Value|direction|sparkline comma values" />
        </div>

        <div v-else class="rounded-lg border border-dashed border-ui-border p-4 text-sm text-ui-muted-foreground">
            <Badge label="Unsupported editor" :icon="AlertTriangle" color="warning" variant="soft" />
            <p class="mt-3">Select line, area or KPI to save with a structured live preview.</p>
        </div>

        <div v-if="hasErrors" class="rounded-lg border border-ui-warning/40 bg-ui-warning/10 p-3 text-sm text-ui-muted-foreground">
            <p v-for="error in errors" :key="error">{{ error }}</p>
        </div>

        <VisualizationPreview :visualization="previewVisualization" />
    </Card>
</template>
