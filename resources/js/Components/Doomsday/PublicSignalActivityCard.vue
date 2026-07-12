<script setup lang="ts">
import { computed } from 'vue';
import { Card } from '@simone-bianco/vue-ui-components';
import { Activity, CalendarDays, Newspaper, RadioTower } from 'lucide-vue-next';
import { currentLanguage, t } from '@/i18n';
import type { NewsActivityData } from '@/types/generated';

const props = defineProps<{
    readonly activity: NewsActivityData;
}>();

const chartWidth = 240;
const chartHeight = 76;
const chartTop = 8;
const chartBaseline = 62;

const counts = computed(() => props.activity.bucket_counts.map((value) => {
    const count = Number(value);
    return Number.isFinite(count) && count > 0 ? count : 0;
}));
const labels = computed(() => props.activity.bucket_labels.map((value) => String(value)));
const hasActivity = computed(() => counts.value.some((count) => count > 0));
const maxCount = computed(() => Math.max(1, ...counts.value));
const points = computed(() => counts.value.map((count, index, values) => {
    const x = values.length <= 1 ? chartWidth / 2 : (index / (values.length - 1)) * chartWidth;
    const y = chartBaseline - ((count / maxCount.value) * (chartBaseline - chartTop));
    return { x: Number(x.toFixed(2)), y: Number(y.toFixed(2)), count };
}));
const linePoints = computed(() => points.value.map((point) => `${point.x},${point.y}`).join(' '));
const areaPath = computed(() => {
    const chartPoints = points.value;
    if (!chartPoints.length) {
        return '';
    }

    const first = chartPoints[0];
    const last = chartPoints[chartPoints.length - 1];
    const line = chartPoints.map((point) => `L ${point.x} ${point.y}`).join(' ');
    return `M ${first.x} ${chartBaseline} ${line} L ${last.x} ${chartBaseline} Z`;
});
const firstBucketLabel = computed(() => formatDate(labels.value[0] ?? null, { month: 'short', day: 'numeric' }));
const lastBucketLabel = computed(() => formatDate(labels.value[labels.value.length - 1] ?? null, { month: 'short', day: 'numeric' }));
const latestPublishedLabel = computed(() => formatDate(props.activity.latest_published_at, { dateStyle: 'medium' }));

function formatDate(value: string | null, options: Intl.DateTimeFormatOptions): string {
    if (!value) {
        return '—';
    }

    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return value;
    }

    return new Intl.DateTimeFormat(currentLanguage.value || 'en', options).format(date);
}
</script>

<template>
    <Card :ui="{ root: 'doomsday-card min-w-0 rounded-xl', body: 'min-w-0 p-5 sm:p-6' }">
        <div class="flex min-w-0 items-start justify-between gap-4">
            <div class="min-w-0">
                <p class="doomsday-display text-ui-primary">{{ t('publicSignalActivity') }}</p>
                <p class="mt-2 text-sm leading-relaxed text-ui-muted-foreground">{{ t('publicSignalActivitySummary') }}</p>
            </div>
            <Activity class="h-5 w-5 shrink-0 text-ui-primary" aria-hidden="true" />
        </div>

        <div class="mt-5 flex items-end gap-3">
            <strong class="doomsday-display text-5xl leading-none text-white">{{ activity.total_items }}</strong>
            <span class="pb-1 text-xs uppercase tracking-[0.08em] text-ui-muted-foreground">{{ t('publishedItems') }}</span>
        </div>

        <div class="mt-5 min-w-0 rounded-lg border border-white/10 bg-black/35 p-3">
            <svg class="h-24 w-full overflow-visible" :viewBox="`0 0 ${chartWidth} ${chartHeight}`" role="img" :aria-label="t('publicSignalActivityChart')">
                <title>{{ t('publicSignalActivityChart') }}</title>
                <line x1="0" :y1="chartBaseline" :x2="chartWidth" :y2="chartBaseline" stroke="currentColor" class="text-white/15" />
                <path v-if="areaPath" :d="areaPath" fill="currentColor" class="text-ui-primary/10" />
                <polyline v-if="linePoints" :points="linePoints" fill="none" stroke="currentColor" stroke-width="2" vector-effect="non-scaling-stroke" class="text-ui-primary" />
                <circle v-for="(point, index) in points" :key="index" :cx="point.x" :cy="point.y" r="2.4" fill="currentColor" class="text-ui-primary" />
            </svg>
            <div class="flex items-center justify-between text-[0.65rem] text-ui-muted-foreground">
                <span>{{ firstBucketLabel }}</span>
                <span>{{ lastBucketLabel }}</span>
            </div>
            <p v-if="!hasActivity" class="mt-3 text-xs text-ui-muted-foreground">{{ t('noSignalActivity') }}</p>
        </div>

        <dl class="mt-5 grid min-w-0 gap-3 text-sm sm:grid-cols-2">
            <div class="min-w-0 rounded-lg border border-white/10 bg-white/[0.03] p-3">
                <dt class="flex items-center gap-2 text-xs text-ui-muted-foreground"><RadioTower class="h-3.5 w-3.5 text-ui-primary" aria-hidden="true" />{{ t('uniqueSources') }}</dt>
                <dd class="mt-1 text-lg font-semibold text-white">{{ activity.unique_sources }}</dd>
            </div>
            <div class="min-w-0 rounded-lg border border-white/10 bg-white/[0.03] p-3">
                <dt class="flex items-center gap-2 text-xs text-ui-muted-foreground"><CalendarDays class="h-3.5 w-3.5 text-ui-primary" aria-hidden="true" />{{ t('latestPublication') }}</dt>
                <dd class="mt-1 break-words text-sm font-semibold text-white">{{ latestPublishedLabel }}</dd>
            </div>
            <div v-if="activity.top_countdown_title" class="min-w-0 rounded-lg border border-white/10 bg-white/[0.03] p-3 sm:col-span-2">
                <dt class="flex items-center gap-2 text-xs text-ui-muted-foreground"><Newspaper class="h-3.5 w-3.5 text-ui-primary" aria-hidden="true" />{{ t('topMonitoredCountdown') }}</dt>
                <dd class="mt-1 flex min-w-0 items-center justify-between gap-3 text-sm font-semibold text-white">
                    <span class="min-w-0 break-words">{{ activity.top_countdown_title }}</span>
                    <span class="shrink-0 tabular-nums text-ui-primary">{{ activity.top_countdown_count }}</span>
                </dd>
            </div>
        </dl>

        <p class="mt-5 border-t border-white/10 pt-4 text-xs leading-relaxed text-ui-muted-foreground">{{ t('publicSignalActivityDisclaimer') }}</p>
    </Card>
</template>
