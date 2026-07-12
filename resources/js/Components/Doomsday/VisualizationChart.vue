<script setup lang="ts">
import { computed } from 'vue';
import VisualizationEvidence from './VisualizationEvidence.vue';

const props = defineProps<{
    readonly payload: unknown;
    readonly type: string;
    readonly sources: readonly string[];
    readonly reasoning: string;
    readonly compact?: boolean;
}>();

type ChartType = 'line' | 'area' | 'bar';
type XAxis = { readonly label: string; readonly type: 'temporal' | 'ordinal' | 'category' };
type YAxis = { readonly label: string; readonly unit: string; readonly format: 'integer' | 'decimal' | 'percent' | 'currency' };
type RawSeries = { readonly name: string; readonly color: string; readonly values: readonly number[] };
type Point = { readonly x: number; readonly y: number; readonly label: string; readonly value: number };
type ChartSeries = RawSeries & { readonly points: readonly Point[]; readonly path: string; readonly areaPath: string };
type BarRect = { readonly x: number; readonly y: number; readonly width: number; readonly height: number; readonly label: string; readonly value: number; readonly name: string; readonly color: string };

const width = 680;
const height = 360;
const plot = { left: 72, top: 44, right: 636, bottom: 286 };
const fallbackColors = ['#ff2a23', '#22c55e', '#38bdf8', '#f59e0b'];

function isRecord(value: unknown): value is Record<string, unknown> {
    return typeof value === 'object' && value !== null && !Array.isArray(value);
}

function toNumber(value: unknown): number | null {
    const parsed = Number(value);
    return Number.isFinite(parsed) ? parsed : null;
}

function isChartType(value: string): value is ChartType {
    return ['line', 'area', 'bar'].includes(value);
}

function parseXAxis(value: unknown): XAxis | null {
    if (!isRecord(value) || typeof value.label !== 'string' || !['temporal', 'ordinal', 'category'].includes(String(value.type))) {
        return null;
    }

    return { label: value.label, type: value.type as XAxis['type'] };
}

function parseYAxis(value: unknown): YAxis | null {
    if (!isRecord(value) || typeof value.label !== 'string' || typeof value.unit !== 'string' || !['integer', 'decimal', 'percent', 'currency'].includes(String(value.format))) {
        return null;
    }

    return { label: value.label, unit: value.unit, format: value.format as YAxis['format'] };
}

const chartType = computed((): ChartType | null => isChartType(props.type) ? props.type : null);
const source = computed((): Record<string, unknown> => isRecord(props.payload) ? props.payload : {});
const labels = computed((): string[] => Array.isArray(source.value.labels)
    ? source.value.labels.filter((label): label is string => typeof label === 'string' && label.trim() !== '')
    : []);
const axes = computed(() => isRecord(source.value.axes) ? source.value.axes : {});
const xAxis = computed(() => parseXAxis(axes.value.x));
const yAxis = computed(() => parseYAxis(axes.value.y));
const validSources = computed(() => props.sources.filter((item) => item.startsWith('https://')));

const rawSeries = computed((): RawSeries[] => {
    const raw = source.value.series;
    if (!Array.isArray(raw) || raw.length === 0) {
        return [];
    }

    if (raw.every(isRecord)) {
        return raw.map((entry, index): RawSeries | null => {
            if (typeof entry.name !== 'string' || !Array.isArray(entry.values)) {
                return null;
            }
            const values = entry.values.map(toNumber);
            if (values.some((value) => value === null)) {
                return null;
            }

            return {
                name: entry.name,
                color: typeof entry.color === 'string' ? entry.color : fallbackColors[index % fallbackColors.length],
                values: values as number[],
            };
        }).filter((series): series is RawSeries => series !== null);
    }

    const values = raw.map(toNumber);
    if (values.some((value) => value === null)) {
        return [];
    }

    return [{
        name: yAxis.value?.label || 'Series',
        color: fallbackColors[0],
        values: values as number[],
    }];
});

const isPayloadValid = computed(() => {
    if (!chartType.value || !xAxis.value || !yAxis.value || labels.value.length === 0 || validSources.value.length === 0 || rawSeries.value.length === 0) {
        return false;
    }
    if (chartType.value === 'bar' && xAxis.value.type !== 'category') {
        return false;
    }
    if (chartType.value !== 'bar' && xAxis.value.type === 'category') {
        return false;
    }

    return rawSeries.value.every((series) => series.values.length === labels.value.length);
});

const allValues = computed(() => rawSeries.value.flatMap((series) => [...series.values]));
const minValue = computed(() => Math.min(0, ...allValues.value));
const maxValue = computed(() => Math.max(...allValues.value, 1));
const range = computed(() => Math.max(maxValue.value - minValue.value, 1));
const paddedMax = computed(() => maxValue.value + range.value * 0.15);
const paddedMin = computed(() => Math.min(0, minValue.value - range.value * 0.08));
const paddedRange = computed(() => Math.max(paddedMax.value - paddedMin.value, 1));

function xAt(index: number, count: number): number {
    if (chartType.value === 'bar') {
        return plot.left + ((index + 0.5) / Math.max(count, 1)) * (plot.right - plot.left);
    }

    return plot.left + (index / Math.max(count - 1, 1)) * (plot.right - plot.left);
}

function yAt(value: number): number {
    return plot.bottom - ((value - paddedMin.value) / paddedRange.value) * (plot.bottom - plot.top);
}

function formatValue(value: number): string {
    const format = yAxis.value?.format;
    if (format === 'integer') {
        return Math.round(value).toLocaleString();
    }
    if (format === 'percent') {
        return `${Math.round(value * 10) / 10}%`;
    }

    return value.toLocaleString(undefined, { maximumFractionDigits: 2 });
}

const series = computed((): ChartSeries[] => rawSeries.value.map((item): ChartSeries => {
    const points = item.values.map((value, index): Point => ({
        x: xAt(index, item.values.length),
        y: yAt(value),
        label: labels.value[index] ?? `${index + 1}`,
        value,
    }));
    const path = points.map((point, index) => `${index === 0 ? 'M' : 'L'} ${point.x} ${point.y}`).join(' ');
    const baseline = yAt(0);
    const areaPath = points.length === 0 ? '' : `M ${points[0].x} ${baseline} L ${points.map((point) => `${point.x} ${point.y}`).join(' L ')} L ${points[points.length - 1].x} ${baseline} Z`;

    return { ...item, points, path, areaPath };
}));

const bars = computed((): BarRect[] => {
    if (chartType.value !== 'bar' || labels.value.length === 0 || rawSeries.value.length === 0) {
        return [];
    }

    const categoryWidth = (plot.right - plot.left) / labels.value.length;
    const groupWidth = categoryWidth * 0.72;
    const slotWidth = groupWidth / rawSeries.value.length;
    const barWidth = Math.max(slotWidth - 4, 2);
    const zeroY = yAt(0);

    return rawSeries.value.flatMap((item, seriesIndex) => item.values.map((value, labelIndex): BarRect => {
        const valueY = yAt(value);
        return {
            x: plot.left + categoryWidth * labelIndex + (categoryWidth - groupWidth) / 2 + slotWidth * seriesIndex + 2,
            y: Math.min(zeroY, valueY),
            width: barWidth,
            height: Math.max(Math.abs(zeroY - valueY), 1),
            label: labels.value[labelIndex] ?? `${labelIndex + 1}`,
            value,
            name: item.name,
            color: item.color,
        };
    }));
});

const yTicks = computed(() => [paddedMax.value, (paddedMax.value + paddedMin.value) / 2, paddedMin.value]);
const rotateLabels = computed(() => labels.value.length > 5 || labels.value.some((label) => label.length > 14));
const ariaLabel = computed(() => isPayloadValid.value
    ? `${chartType.value} chart. X axis: ${xAxis.value?.label}. Y axis: ${yAxis.value?.label}, unit ${yAxis.value?.unit}.`
    : 'Visualization unavailable because the schema v2 payload is invalid.');
</script>

<template>
    <div :class="['doomsday-scrollbar w-full overflow-x-auto rounded-lg border border-white/10 bg-black/35 py-4 pb-6', compact ? 'px-2 sm:px-3' : 'px-3 sm:px-5']">
        <template v-if="isPayloadValid">
            <svg :viewBox="`0 0 ${width} ${height}`" class="h-[22rem] min-w-[600px] w-full" role="img" :aria-label="ariaLabel">
                <g class="text-white/10">
                    <line v-for="tick in yTicks" :key="`y-${tick}`" :x1="plot.left" :y1="yAt(tick)" :x2="plot.right" :y2="yAt(tick)" stroke="currentColor" />
                    <line v-for="(label, index) in labels" :key="`x-${label}`" :x1="xAt(index, labels.length)" :y1="plot.top" :x2="xAt(index, labels.length)" :y2="plot.bottom" stroke="currentColor" />
                    <line :x1="plot.left" :y1="plot.top" :x2="plot.left" :y2="plot.bottom" stroke="currentColor" />
                    <line :x1="plot.left" :y1="plot.bottom" :x2="plot.right" :y2="plot.bottom" stroke="currentColor" />
                </g>

                <g class="doomsday-display text-[11px] text-white/55">
                    <text v-for="tick in yTicks" :key="`tick-${tick}`" :x="plot.left - 12" :y="yAt(tick) + 4" text-anchor="end" fill="currentColor">{{ formatValue(tick) }}</text>
                    <text
                        v-for="(label, index) in labels"
                        :key="`label-${label}`"
                        :x="xAt(index, labels.length)"
                        :y="plot.bottom + 28"
                        :text-anchor="rotateLabels ? 'end' : 'middle'"
                        :transform="rotateLabels ? `rotate(-22 ${xAt(index, labels.length)} ${plot.bottom + 28})` : undefined"
                        fill="currentColor"
                    ><title>{{ label }}</title>{{ label }}</text>
                    <text :x="(plot.left + plot.right) / 2" :y="height - 6" text-anchor="middle" fill="currentColor">{{ xAxis?.label }}</text>
                    <text :transform="`rotate(-90 16 ${(plot.top + plot.bottom) / 2})`" x="16" :y="(plot.top + plot.bottom) / 2" text-anchor="middle" fill="currentColor">{{ yAxis?.label }} ({{ yAxis?.unit }})</text>
                </g>

                <g v-if="chartType === 'bar'">
                    <rect v-for="bar in bars" :key="`${bar.name}-${bar.label}`" :x="bar.x" :y="bar.y" :width="bar.width" :height="bar.height" :fill="bar.color" rx="2">
                        <title>{{ bar.name }} · {{ bar.label }}: {{ formatValue(bar.value) }} {{ yAxis?.unit }}</title>
                    </rect>
                </g>

                <g v-else v-for="item in series" :key="item.name">
                    <path v-if="chartType === 'area' && item.points.length > 1" :d="item.areaPath" :fill="item.color" fill-opacity="0.18" />
                    <path v-if="item.points.length > 1" :d="item.path" fill="none" :stroke="item.color" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    <circle v-for="point in item.points" :key="`${item.name}-${point.label}`" :cx="point.x" :cy="point.y" r="4" :fill="item.color">
                        <title>{{ item.name }} · {{ point.label }}: {{ formatValue(point.value) }} {{ yAxis?.unit }}</title>
                    </circle>
                </g>

                <g class="doomsday-display text-[11px]">
                    <g v-for="(item, index) in series" :key="`legend-${item.name}`" :transform="`translate(${plot.left + index * 150}, 16)`">
                        <line x1="0" y1="0" x2="22" y2="0" :stroke="item.color" stroke-width="3" stroke-linecap="round" />
                        <text x="30" y="4" fill="rgba(255,255,255,0.72)">{{ item.name }}</text>
                    </g>
                </g>
            </svg>

            <VisualizationEvidence class="min-w-[600px] px-2" :sources="validSources" :reasoning="reasoning" />
        </template>
        <p v-else class="min-w-[600px] px-4 py-16 text-center text-sm text-white/55">Visualization unavailable: schema v2 axes, series and entity-level HTTPS sources are required.</p>
    </div>
</template>
