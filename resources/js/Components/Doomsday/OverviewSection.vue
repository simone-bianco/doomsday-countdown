<script setup lang="ts">
import { Card } from '@simone-bianco/vue-ui-components';
import KeyIndicatorsCard from './KeyIndicatorsCard.vue';
import { localizeDoomsdayEnum, t } from '@/i18n';
import type { CountdownOverviewData } from '@/types/generated';

defineProps<{
    readonly countdown: CountdownOverviewData;
}>();
</script>

<template>
    <Card :ui="{ root: 'doomsday-card rounded-xl', body: 'p-5 sm:p-6' }">
        <h3 class="doomsday-display mb-4 text-white">{{ t('summary') }}</h3>
        <p class="leading-relaxed text-ui-muted-foreground">{{ countdown.description }}</p>
        <div class="mt-6 grid grid-cols-[repeat(auto-fit,minmax(120px,1fr))] gap-3 text-sm">
            <div class="min-w-0 rounded-lg border border-white/10 bg-white/5 p-3"><span class="text-ui-muted-foreground">{{ t('confidence') }}</span><strong class="block text-xl text-white">{{ countdown.main_projection?.confidence_score ?? 0 }}%</strong></div>
            <div class="min-w-0 rounded-lg border border-white/10 bg-white/5 p-3"><span class="text-ui-muted-foreground">{{ t('trend') }}</span><strong class="block text-ui-primary">{{ localizeDoomsdayEnum('trendValues', countdown.main_projection?.trend) }}</strong></div>
            <div class="min-w-0 rounded-lg border border-white/10 bg-white/5 p-3"><span class="text-ui-muted-foreground">{{ t('risk') }}</span><strong class="block text-ui-primary">{{ localizeDoomsdayEnum('severityValues', countdown.severity) }}</strong></div>
        </div>
    </Card>
    <KeyIndicatorsCard :visualization="countdown.key_indicators" />
</template>
