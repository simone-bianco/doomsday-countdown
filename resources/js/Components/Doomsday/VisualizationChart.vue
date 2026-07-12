<script setup lang="ts">
import { computed } from 'vue';
import { Maximize2, Minus, Plus, RotateCcw, X } from 'lucide-vue-next';
import VisualizationEvidence from './VisualizationEvidence.vue';
import { useMobileChartZoom } from '@/Composables/useMobileChartZoom';
import { localizeDoomsdayLabel, t } from '@/i18n';

const props = withDefaults(defineProps<{
    readonly payload: unknown;
    readonly type: string;
    readonly sources: readonly string[];
    readonly explanation: string;
    readonly reasoning: string;
    readonly compact?: boolean;
    readonly mobile?: boolean;
}>(), {
    compact: false,
    mobile: false,
});

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

    return { label: localizeDoomsdayLabel(value.label), type: value.type as XAxis['type'] };
}

function parseYAxis(value: unknown): YAxis | null {
    if (!isRecord(value) || typeof value.label !== 'string' || typeof value.unit !== 'string' || !['integer', 'decimal', 'percent', 'currency'].includes(String(value.format))) {
        return null;
    }

    return { label: localizeDoomsdayLabel(value.label), unit: localizeDoomsdayLabel(value.unit), format: value.format as YAxis['format'] };
}

const chartType = computed((): ChartType | null => isChartType(props.type) ? props.type : null);
const source = computed((): Record<string, unknown> => isRecord(props.payload) ? props.payload : {});
const labels = computed((): string[] => Array.isArray(source.value.labels)
    ? source.value.labels.filter((label): label is string => typeof label === 'string' && label.trim() !== '').map(localizeDoomsdayLabel)
    : []);
const axes = computed(() => isRecord(source.value.axes) ? source.value.axes : {});
const xAxis = computed(() => parseXAxis(axes.value.x));
const yAxis = computed(() => parseYAxis(axes.value.y));
const validSources = computed(() => props.sources.filter((item) => item.startsWith('https://')));
const {
    chartSurface,
    isZoomed,
    chartScale,
    chartPanX,
    chartPanY,
    chartTransformStyle,
    minChartScale,
    maxChartScale,
    openZoom,
    closeZoom,
    zoomChartIn,
    zoomChartOut,
    resetChartMagnification,
    toggleChartMagnification,
    handleChartTouchStart,
    handleChartTouchMove,
    handleChartTouchEnd,
    resetChartGesture,
    stopZoomedTouchPropagation,
} = useMobileChartZoom(computed(() => props.mobile));

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
                name: localizeDoomsdayLabel(entry.name),
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
        name: yAxis.value?.label || t('seriesLabel'),
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
    <div
        ref="chartSurface"
        :class="[
            !mobile ? 'doomsday-scrollbar w-full overflow-x-auto rounded-lg border border-white/10 bg-black/35 py-4 pb-6' : '',
            !mobile ? (compact ? 'px-2 sm:px-3' : 'px-3 sm:px-5') : '',
            mobile && !isZoomed ? 'relative w-full overflow-hidden border-0 bg-transparent px-0 py-2' : '',
            mobile && isZoomed ? 'fixed inset-0 z-[100] flex h-[100dvh] w-[100dvw] select-none items-center justify-center overflow-hidden bg-black/95 p-2' : '',
        ]"
        @touchstart="stopZoomedTouchPropagation"
        @touchend="stopZoomedTouchPropagation"
    >
        <button
            v-if="mobile && isZoomed"
            type="button"
            class="absolute right-3 top-3 z-30 inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/15 bg-black/80 text-white shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary"
            :aria-label="t('closeChart')"
            @click.stop="closeZoom"
        >
            <X class="h-5 w-5" />
        </button>

        <template v-if="isPayloadValid">
            <div
                :class="isZoomed ? 'relative flex h-full w-full touch-none items-center justify-center overflow-hidden' : 'relative w-full'"
                @touchstart="handleChartTouchStart"
                @touchmove="handleChartTouchMove"
                @touchend="handleChartTouchEnd"
                @touchcancel="resetChartGesture"
                @dblclick.prevent="toggleChartMagnification"
            >
                <svg
                    :viewBox="`0 0 ${width} ${height}`"
                    :class="mobile ? (isZoomed ? 'block h-auto max-h-[calc(100dvh-3rem)] w-full max-w-[100dvw] will-change-transform' : 'block h-auto w-full max-w-full') : 'h-[22rem] min-w-[600px] w-full'"
                    :style="isZoomed ? chartTransformStyle : undefined"
                    role="img"
                    :aria-label="ariaLabel"
                >
                    <g class="text-white/10">
                        <line v-for="tick in yTicks" :key="`y-${tick}`" :x1="plot.left" :y1="yAt(tick)" :x2="plot.right" :y2="yAt(tick)" stroke="currentColor" />
                        <line v-for="(label, index) in labels" :key="`x-${label}`" :x1="xAt(index, labels.length)" :y1="plot.top" :x2="xAt(index, labels.length)" :y2="plot.bottom" stroke="currentColor" />
                        <line :x1="plot.left" :y1="plot.top" :x2="plot.left" :y2="plot.bottom" stroke="currentColor" />
                        <line :x1="plot.left" :y1="plot.bottom" :x2="plot.right" :y2="plot.bottom" stroke="currentColor" />
                    </g>

                    <g :class="['doomsday-display text-white/55', mobile ? 'text-[16px]' : 'text-[11px]']">
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
                        <path v-if="item.points.length > 1" :d="item.path" fill="none" :stroke="item.color" :stroke-width="mobile ? 4 : 3" stroke-linecap="round" stroke-linejoin="round" />
                        <circle v-for="point in item.points" :key="`${item.name}-${point.label}`" :cx="point.x" :cy="point.y" :r="mobile ? 5 : 4" :fill="item.color">
                            <title>{{ item.name }} · {{ point.label }}: {{ formatValue(point.value) }} {{ yAxis?.unit }}</title>
                        </circle>
                    </g>

                    <g :class="['doomsday-display', mobile ? 'text-[16px]' : 'text-[11px]']">
                        <g v-for="(item, index) in series" :key="`legend-${item.name}`" :transform="`translate(${plot.left + index * 150}, 16)`">
                            <line x1="0" y1="0" x2="22" y2="0" :stroke="item.color" stroke-width="3" stroke-linecap="round" />
                            <text x="30" y="4" fill="rgba(255,255,255,0.72)">{{ item.name }}</text>
                        </g>
                    </g>
                </svg>

                <button
                    v-if="mobile && !isZoomed"
                    type="button"
                    class="absolute inset-0 z-10 cursor-zoom-in rounded-lg bg-transparent focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-ui-primary"
                    :aria-label="t('expandChart')"
                    @click.stop="openZoom"
                >
                    <span class="pointer-events-none absolute bottom-2 right-2 inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-black/80 px-2.5 py-1.5 text-[10px] font-medium text-white shadow-lg">
                        <Maximize2 class="h-3.5 w-3.5" />
                        {{ t('chartZoomHint') }}
                    </span>
                </button>
            </div>

            <div v-if="mobile && isZoomed" class="absolute bottom-3 left-1/2 z-30 flex -translate-x-1/2 items-center gap-1 rounded-full border border-white/15 bg-black/85 p-1 shadow-xl backdrop-blur-xl">
                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full text-white disabled:cursor-not-allowed disabled:opacity-35 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary"
                    :aria-label="t('zoomOutChart')"
                    :disabled="chartScale <= minChartScale"
                    @click.stop="zoomChartOut"
                >
                    <Minus class="h-4 w-4" />
                </button>
                <span class="min-w-12 text-center text-xs tabular-nums text-white" aria-live="polite">{{ Math.round(chartScale * 100) }}%</span>
                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full text-white disabled:cursor-not-allowed disabled:opacity-35 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary"
                    :aria-label="t('zoomInChart')"
                    :disabled="chartScale >= maxChartScale"
                    @click.stop="zoomChartIn"
                >
                    <Plus class="h-4 w-4" />
                </button>
                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full text-white disabled:cursor-not-allowed disabled:opacity-35 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary"
                    :aria-label="t('resetChartZoom')"
                    :disabled="chartScale === minChartScale && chartPanX === 0 && chartPanY === 0"
                    @click.stop="resetChartMagnification"
                >
                    <RotateCcw class="h-4 w-4" />
                </button>
            </div>
            <p v-if="mobile && isZoomed" class="pointer-events-none absolute left-3 top-3 z-20 rounded-full bg-black/70 px-3 py-1.5 text-[10px] text-white/70">{{ t('chartZoomGestureHint') }}</p>

            <VisualizationEvidence v-if="!isZoomed" :class="mobile ? 'min-w-0 px-0' : 'min-w-[600px] px-2'" :sources="validSources" :explanation="explanation" :reasoning="reasoning" />
        </template>
        <p v-else :class="mobile ? 'min-w-0 px-2 py-10 text-center text-sm text-white/55' : 'min-w-[600px] px-4 py-16 text-center text-sm text-white/55'">{{ t('visualizationUnavailable') }}</p>
    </div>
</template>

