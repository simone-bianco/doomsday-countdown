<script setup lang="ts">
import { computed } from 'vue';
import { Card } from '@simone-bianco/vue-ui-components';
import { localizeDoomsdayLabel, t } from '@/i18n';
import VisualizationEvidence from './VisualizationEvidence.vue';
import type { VisualizationData } from '@/types/generated';

const props = defineProps<{
    readonly visualization: VisualizationData | null;
}>();

type Indicator = { readonly label: string; readonly value: string; readonly direction: string; readonly sparkline: readonly number[] };

function isRecord(value: unknown): value is Record<string, unknown> {
    return typeof value === 'object' && value !== null;
}

const indicators = computed((): Indicator[] => {
    const payload = props.visualization?.payload as unknown;
    const items = isRecord(payload) ? payload.items : null;
    if (!Array.isArray(items)) {
        return [];
    }

    return items.filter(isRecord).map((item): Indicator => ({
        label: localizeDoomsdayLabel(String(item.label ?? '')),
        value: String(item.value ?? ''),
        direction: String(item.direction ?? 'up'),
        sparkline: Array.isArray(item.sparkline) ? item.sparkline.map(Number).filter(Number.isFinite) : [],
    }));
});
</script>

<template>
    <Card :ui="{ root: 'doomsday-card min-w-0 rounded-xl', body: 'min-w-0 p-5 pb-6 sm:p-6 sm:pb-8' }">
        <h3 class="doomsday-display mb-5 text-lg text-white">{{ t('keyIndicators') }}</h3>
        <div class="grid min-w-0 gap-4">
            <div
                v-for="indicator in indicators"
                :key="indicator.label"
                class="grid min-w-0 grid-cols-[minmax(0,1fr)_auto] items-center gap-x-3 gap-y-2 border-b border-white/5 pb-3 last:border-b-0 last:pb-0 sm:grid-cols-[minmax(0,1fr)_auto_minmax(56px,90px)]"
            >
                <span class="min-w-0 break-words text-sm text-ui-muted-foreground">{{ indicator.label }}</span>
                <span class="whitespace-nowrap text-sm text-white">{{ indicator.value }} <span class="text-ui-primary">{{ indicator.direction === 'up' ? '↑' : '↓' }}</span></span>
                <div class="col-span-2 flex h-5 min-w-0 max-w-full items-end gap-1 overflow-hidden sm:col-span-1">
                    <span v-for="(value, index) in indicator.sparkline" :key="index" class="min-w-0 flex-1 bg-ui-primary" :style="{ height: `${Math.max(15, value)}%` }" />
                </div>
            </div>
        </div>
        <div class="mt-5 min-w-0 pb-1">
            <VisualizationEvidence :sources="visualization?.sources ?? []" :explanation="visualization?.explanation ?? null" :reasoning="visualization?.reasoning ?? null" />
        </div>
    </Card>
</template>
