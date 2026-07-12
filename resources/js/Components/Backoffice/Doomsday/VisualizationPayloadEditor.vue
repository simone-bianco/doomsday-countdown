<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { AlertTriangle } from 'lucide-vue-next';
import { Badge, Card, Textarea, TextInput } from '@simone-bianco/vue-ui-components';
import BackofficeSelectField from '@/Components/Backoffice/Shared/BackofficeSelectField.vue';
import VisualizationPreview from '@/Components/Backoffice/Doomsday/VisualizationPreview.vue';
import { chartText, chartXAxisTypes, chartYAxisFormats, defaultPayload, isRecord, kpiText, optionItems, parseChartPayload, parseKpiPayload } from '@/Components/Backoffice/Doomsday/formHelpers';
import type { ChartXAxisType, ChartYAxisFormat, VisualizationPayload } from '@/Components/Backoffice/Doomsday/types';

const props = defineProps<{
    readonly sources: readonly string[];
    readonly reasoning: string;
}>();
const typeModel = defineModel<string>('type', { required: true });
const payloadModel = defineModel<VisualizationPayload>('payload', { required: true });
const validModel = defineModel<boolean>('valid', { default: true });

const labelsText = ref('');
const xLabelText = ref('');
const xTypeValue = ref<ChartXAxisType>('ordinal');
const yLabelText = ref('');
const yUnitText = ref('');
const yFormatValue = ref<ChartYAxisFormat>('percent');
const seriesText = ref('');
const itemsText = ref('');
const isHydrating = ref(false);

const xTypeOptions = optionItems(chartXAxisTypes);
const yFormatOptions = optionItems(chartYAxisFormats);
const isChart = computed(() => ['line', 'area', 'bar'].includes(typeModel.value));
const isKpi = computed(() => typeModel.value === 'kpi');
const isSupported = computed(() => isChart.value || isKpi.value);

function chartErrors(): string[] {
    const payload = payloadModel.value as unknown;
    if (!isRecord(payload)) {
        return ['Chart payload must be an object.'];
    }

    const errors: string[] = [];
    const labels = Array.isArray(payload.labels) ? payload.labels : [];
    if (labels.length === 0 || labels.some((label) => typeof label !== 'string' || label.trim() === '')) {
        errors.push('Add at least one non-empty chart label.');
    }

    const series = Array.isArray(payload.series) ? payload.series : [];
    if (series.length === 0) {
        errors.push('Add at least one numeric series.');
    } else if (series.every((value) => typeof value === 'number' && Number.isFinite(value))) {
        if (series.length !== labels.length) {
            errors.push('Series values must have the same length as labels.');
        }
    } else {
        for (const item of series) {
            if (!isRecord(item) || typeof item.name !== 'string' || item.name.trim() === '' || !Array.isArray(item.values)) {
                errors.push('Each series requires a name and numeric values.');
                break;
            }
            if ('unit' in item || 'format' in item) {
                errors.push('Series must share the y-axis unit and format.');
                break;
            }
            if (item.values.length !== labels.length || item.values.some((value) => typeof value !== 'number' || !Number.isFinite(value))) {
                errors.push('Every series must contain one numeric value per label.');
                break;
            }
        }
    }

    const axes = isRecord(payload.axes) ? payload.axes : null;
    const xAxis = axes && isRecord(axes.x) ? axes.x : null;
    const yAxis = axes && isRecord(axes.y) ? axes.y : null;
    if (!xAxis || typeof xAxis.label !== 'string' || xAxis.label.trim() === '') {
        errors.push('Add an x-axis label.');
    }
    if (!xAxis || typeof xAxis.type !== 'string' || !chartXAxisTypes.includes(xAxis.type as ChartXAxisType)) {
        errors.push('Choose a valid x-axis type.');
    } else if (typeModel.value === 'bar' && xAxis.type !== 'category') {
        errors.push('Bar charts require a category x-axis.');
    } else if (['line', 'area'].includes(typeModel.value) && xAxis.type === 'category') {
        errors.push('Line and area charts require a temporal or ordinal x-axis.');
    }
    if (!yAxis || typeof yAxis.label !== 'string' || yAxis.label.trim() === '') {
        errors.push('Add a y-axis label.');
    }
    if (!yAxis || typeof yAxis.unit !== 'string' || yAxis.unit.trim() === '') {
        errors.push('Add the shared y-axis unit.');
    }
    if (!yAxis || typeof yAxis.format !== 'string' || !chartYAxisFormats.includes(yAxis.format as ChartYAxisFormat)) {
        errors.push('Choose a valid y-axis format.');
    }

    return errors;
}

const errors = computed(() => {
    if (!isSupported.value) {
        return ['Live editing is available only for line, area, bar and KPI visualizations.'];
    }

    if (isChart.value) {
        return chartErrors();
    }

    if (isKpi.value) {
        const payload = payloadModel.value as unknown;
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
    sources: [...props.sources],
    reasoning: { en: props.reasoning },
    payload: payloadModel.value,
    schema_version: isChart.value ? 2 : 1,
    sort_order: 0,
}));

function hydrateText(payload: unknown): void {
    isHydrating.value = true;
    const chart = chartText(payload);
    labelsText.value = chart.labels;
    xLabelText.value = chart.xLabel;
    xTypeValue.value = chart.xType;
    yLabelText.value = chart.yLabel;
    yUnitText.value = chart.yUnit;
    yFormatValue.value = chart.yFormat;
    seriesText.value = chart.series;
    itemsText.value = kpiText(payload);
    isHydrating.value = false;
}

function syncPayload(): void {
    if (isHydrating.value) {
        return;
    }

    if (isChart.value) {
        payloadModel.value = parseChartPayload({
            labels: labelsText.value,
            xLabel: xLabelText.value,
            xType: xTypeValue.value,
            yLabel: yLabelText.value,
            yUnit: yUnitText.value,
            yFormat: yFormatValue.value,
            series: seriesText.value,
        });
    } else if (isKpi.value) {
        payloadModel.value = parseKpiPayload(itemsText.value);
    }
}

function chooseXType(value: string | number | null): void {
    if (typeof value === 'string' && chartXAxisTypes.includes(value as ChartXAxisType)) {
        xTypeValue.value = value as ChartXAxisType;
    }
}

function chooseYFormat(value: string | number | null): void {
    if (typeof value === 'string' && chartYAxisFormats.includes(value as ChartYAxisFormat)) {
        yFormatValue.value = value as ChartYAxisFormat;
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

watch([labelsText, xLabelText, xTypeValue, yLabelText, yUnitText, yFormatValue, seriesText, itemsText], syncPayload, { flush: 'sync' });
</script>

<template>
    <Card :ui="{ body: 'space-y-4 p-4' }">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="font-semibold">Payload editor</p>
                <p class="text-sm text-ui-muted-foreground">Structured payload editor; sources and calculation reasoning are managed on the visualization.</p>
            </div>
            <Badge :label="typeModel" :color="isSupported ? 'success' : 'warning'" variant="soft" />
        </div>

        <div v-if="isChart" class="space-y-4">
            <TextInput v-model="labelsText" label="Labels" helper-text="Comma-separated ordered values or categories." />
            <div class="grid gap-4 md:grid-cols-2">
                <TextInput v-model="xLabelText" label="X-axis label" helper-text="Describe the time, sequence or category dimension." />
                <BackofficeSelectField label="X-axis type" :model-value="xTypeValue" :options="xTypeOptions" :clearable="false" @update:model-value="chooseXType" />
                <TextInput v-model="yLabelText" label="Y-axis label" helper-text="Describe the measured quantity." />
                <TextInput v-model="yUnitText" label="Y-axis unit" helper-text="One shared unit for every series." />
                <BackofficeSelectField label="Y-axis format" :model-value="yFormatValue" :options="yFormatOptions" :clearable="false" @update:model-value="chooseYFormat" />
            </div>
            <Textarea v-model="seriesText" label="Series" :rows="4" helper-text="One series per line: Scenario: 20, 42, 64" />
        </div>

        <div v-else-if="isKpi" class="space-y-4">
            <Textarea v-model="itemsText" label="KPI items" :rows="5" helper-text="One KPI per line: Label|Value|direction|sparkline comma values" />
        </div>

        <div v-else class="rounded-lg border border-dashed border-ui-border p-4 text-sm text-ui-muted-foreground">
            <Badge label="Unsupported editor" :icon="AlertTriangle" color="warning" variant="soft" />
            <p class="mt-3">Select line, area, bar or KPI to save with a structured live preview.</p>
        </div>

        <div v-if="hasErrors" class="rounded-lg border border-ui-warning/40 bg-ui-warning/10 p-3 text-sm text-ui-muted-foreground">
            <p v-for="error in errors" :key="error">{{ error }}</p>
        </div>

        <VisualizationPreview :visualization="previewVisualization" />
    </Card>
</template>
