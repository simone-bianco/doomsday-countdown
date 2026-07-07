<script setup lang="ts">
import { computed } from 'vue';
import { Card } from '@simone-bianco/vue-ui-components';
import { t } from '@/i18n';
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
        label: String(item.label ?? ''),
        value: String(item.value ?? ''),
        direction: String(item.direction ?? 'up'),
        sparkline: Array.isArray(item.sparkline) ? item.sparkline.map(Number).filter(Number.isFinite) : [],
    }));
});
</script>

<template>
    <Card :ui="{ root: 'doomsday-card rounded-xl', body: 'p-5 sm:p-6' }">
        <h3 class="doomsday-display mb-5 text-lg text-white">{{ t('keyIndicators') }}</h3>
        <div class="grid gap-4">
            <div v-for="indicator in indicators" :key="indicator.label" class="grid grid-cols-[1fr_auto_90px] items-center gap-3 border-b border-white/5 pb-3 last:border-b-0 last:pb-0">
                <span class="text-sm text-ui-muted-foreground">{{ indicator.label }}</span>
                <span class="text-sm text-white">{{ indicator.value }} <span class="text-ui-primary">{{ indicator.direction === 'up' ? '↑' : '↓' }}</span></span>
                <div class="flex h-5 items-end gap-1">
                    <span v-for="(value, index) in indicator.sparkline" :key="index" class="w-1 bg-ui-primary" :style="{ height: `${Math.max(15, value)}%` }" />
                </div>
            </div>
        </div>
    </Card>
</template>
