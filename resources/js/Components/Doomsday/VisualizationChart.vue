<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    readonly payload: unknown;
    readonly compact?: boolean;
}>();

type RawSeries = { readonly name: string; readonly color: string; readonly values: readonly number[] };
type Point = { readonly x: number; readonly y: number; readonly label: string; readonly value: number };
type ChartSeries = RawSeries & { readonly points: readonly Point[]; readonly path: string };

const width = 680;
const height = 360;
const plot = { left: 72, top: 44, right: 636, bottom: 286 };
const fallbackColors = ['#ff2a23', '#22c55e', '#38bdf8', '#f59e0b'];

function isRecord(value: unknown): value is Record<string, unknown> {
    return typeof value === 'object' && value !== null;
}

function toNumber(value: unknown): number | null {
    const parsed = Number(value);
    return Number.isFinite(parsed) ? parsed : null;
}

function formatValue(value: number): string {
    return Math.abs(value) >= 1000 ? value.toLocaleString() : value.toString();
}

const source = computed((): Record<string, unknown> => isRecord(props.payload) ? props.payload : {});
const labels = computed((): string[] => Array.isArray(source.value.labels) ? source.value.labels.map(String) : []);
const unit = computed((): string => typeof source.value.unit === 'string' ? source.value.unit : '');

const rawSeries = computed((): RawSeries[] => {
    const raw = source.value.series;

    if (! Array.isArray(raw)) {
        return [];
    }

    if (raw.every(isRecord)) {
        return raw.map((entry, index): RawSeries => ({
            name: typeof entry.name === 'string' ? entry.name : `Series ${index + 1}`,
            color: typeof entry.color === 'string' ? entry.color : fallbackColors[index % fallbackColors.length],
            values: Array.isArray(entry.values) ? entry.values.map(toNumber).filter((value): value is number => value !== null) : [],
        })).filter((series) => series.values.length > 0);
    }

    return [{
        name: typeof source.value.name === 'string' ? source.value.name : 'Series',
        color: typeof source.value.color === 'string' ? source.value.color : fallbackColors[0],
        values: raw.map(toNumber).filter((value): value is number => value !== null),
    }];
});

const allValues = computed(() => rawSeries.value.flatMap((series) => [...series.values]));
const minValue = computed(() => Math.min(0, ...allValues.value));
const maxValue = computed(() => Math.max(...allValues.value, 1));
const range = computed(() => Math.max(maxValue.value - minValue.value, 1));
const paddedMax = computed(() => maxValue.value + range.value * 0.15);
const paddedMin = computed(() => Math.min(0, minValue.value - range.value * 0.08));
const paddedRange = computed(() => Math.max(paddedMax.value - paddedMin.value, 1));

function xAt(index: number, count: number): number {
    return plot.left + (index / Math.max(count - 1, 1)) * (plot.right - plot.left);
}

function yAt(value: number): number {
    return plot.bottom - ((value - paddedMin.value) / paddedRange.value) * (plot.bottom - plot.top);
}

const series = computed((): ChartSeries[] => rawSeries.value.map((item): ChartSeries => {
    const points = item.values.map((value, index): Point => ({
        x: xAt(index, item.values.length),
        y: yAt(value),
        label: labels.value[index] ?? `${index + 1}`,
        value,
    }));

    return {
        ...item,
        points,
        path: points.map((point, index) => `${index === 0 ? 'M' : 'L'} ${point.x} ${point.y}`).join(' '),
    };
}));

const yTicks = computed(() => [paddedMax.value, (paddedMax.value + paddedMin.value) / 2, paddedMin.value]);
</script>

<template>
    <div class="doomsday-scrollbar w-full overflow-x-auto rounded-lg border border-white/10 bg-black/35 px-3 py-4 pb-6 sm:px-5">
        <svg :viewBox="`0 0 ${width} ${height}`" class="h-[22rem] min-w-[600px] w-full" role="img" aria-label="Doomsday chart">
            <g class="text-white/10">
                <line v-for="tick in yTicks" :key="`y-${tick}`" :x1="plot.left" :y1="yAt(tick)" :x2="plot.right" :y2="yAt(tick)" stroke="currentColor" />
                <line v-for="(label, index) in labels" :key="`x-${label}`" :x1="xAt(index, labels.length)" :y1="plot.top" :x2="xAt(index, labels.length)" :y2="plot.bottom" stroke="currentColor" />
                <line :x1="plot.left" :y1="plot.top" :x2="plot.left" :y2="plot.bottom" stroke="currentColor" />
                <line :x1="plot.left" :y1="plot.bottom" :x2="plot.right" :y2="plot.bottom" stroke="currentColor" />
            </g>

            <g class="doomsday-display text-[11px] text-white/55">
                <text v-for="tick in yTicks" :key="`tick-${tick}`" :x="plot.left - 12" :y="yAt(tick) + 4" text-anchor="end" fill="currentColor">{{ formatValue(Math.round(tick * 10) / 10) }}</text>
                <text v-for="(label, index) in labels" :key="`label-${label}`" :x="xAt(index, labels.length)" :y="plot.bottom + 30" text-anchor="middle" fill="currentColor">{{ label }}</text>
                <text v-if="unit" :x="plot.left" :y="height - 10" fill="currentColor">{{ unit }}</text>
            </g>

            <g v-for="item in series" :key="item.name">
                <path v-if="item.points.length > 1" :d="item.path" fill="none" :stroke="item.color" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                <circle v-for="point in item.points" :key="`${item.name}-${point.label}`" :cx="point.x" :cy="point.y" r="4" :fill="item.color" />
            </g>

            <g class="doomsday-display text-[11px]">
                <g v-for="(item, index) in series" :key="`legend-${item.name}`" :transform="`translate(${plot.left + index * 150}, 16)`">
                    <line x1="0" y1="0" x2="22" y2="0" :stroke="item.color" stroke-width="3" stroke-linecap="round" />
                    <text x="30" y="4" fill="rgba(255,255,255,0.72)">{{ item.name }}</text>
                </g>
            </g>
        </svg>
    </div>
</template>
