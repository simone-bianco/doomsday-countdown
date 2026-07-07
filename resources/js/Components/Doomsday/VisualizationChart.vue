<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    readonly payload: unknown;
    readonly compact?: boolean;
}>();

type Point = { readonly x: number; readonly y: number; readonly label: string; readonly value: number };

function isRecord(value: unknown): value is Record<string, unknown> {
    return typeof value === 'object' && value !== null;
}

const source = computed((): Record<string, unknown> => isRecord(props.payload) ? props.payload : {});
const labels = computed((): string[] => Array.isArray(source.value.labels) ? source.value.labels.map(String) : []);
const values = computed((): number[] => Array.isArray(source.value.series) ? source.value.series.map(Number).filter(Number.isFinite) : []);
const max = computed(() => Math.max(100, ...values.value, 1));
const points = computed((): Point[] => values.value.map((value, index) => {
    const denominator = Math.max(values.value.length - 1, 1);
    return {
        x: 28 + (index / denominator) * 294,
        y: 130 - (value / max.value) * 108,
        label: labels.value[index] ?? `${index + 1}`,
        value,
    };
}));
const path = computed(() => points.value.map((point, index) => `${index === 0 ? 'M' : 'L'} ${point.x} ${point.y}`).join(' '));
const areaPath = computed(() => `${path.value} L 322 138 L 28 138 Z`);
</script>

<template>
    <div class="w-full overflow-hidden rounded-lg border border-white/10 bg-black/30 p-4">
        <svg viewBox="0 0 350 160" class="h-40 w-full" role="img" aria-label="Projection chart">
            <defs>
                <linearGradient id="doom-chart-fill" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0" stop-color="#ff2a23" stop-opacity="0.35" />
                    <stop offset="1" stop-color="#ff2a23" stop-opacity="0" />
                </linearGradient>
            </defs>
            <g class="text-white/10">
                <line v-for="x in [28, 87, 146, 205, 264, 322]" :key="`x-${x}`" :x1="x" y1="20" :x2="x" y2="138" stroke="currentColor" />
                <line v-for="y in [30, 65, 100, 138]" :key="`y-${y}`" x1="28" :y1="y" x2="322" :y2="y" stroke="currentColor" />
            </g>
            <path v-if="points.length > 1" :d="areaPath" fill="url(#doom-chart-fill)" />
            <path v-if="points.length > 1" :d="path" fill="none" stroke="#ff2a23" stroke-width="3" stroke-linecap="round" />
            <circle v-for="point in points" :key="point.label" :cx="point.x" :cy="point.y" r="4" fill="#ff2a23" />
            <text v-for="point in points" :key="`label-${point.label}`" :x="point.x" y="154" text-anchor="middle" fill="rgba(255,255,255,0.55)" font-size="11">{{ point.label }}</text>
        </svg>
    </div>
</template>
