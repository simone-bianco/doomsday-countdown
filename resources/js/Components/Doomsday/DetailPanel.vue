<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Button, Card, Image } from '@simone-bianco/vue-ui-components';
import { X } from 'lucide-vue-next';
import CountdownTimer from './CountdownTimer.vue';
import KeyIndicatorsCard from './KeyIndicatorsCard.vue';
import VisualizationChart from './VisualizationChart.vue';
import { t } from '@/i18n';
import type { CountdownDetailData, VisualizationData } from '@/types/generated';

const props = defineProps<{
    readonly countdown: CountdownDetailData;
    readonly currentLocale: string;
}>();

const activeTab = ref('overview');
const tabs = computed(() => [
    { value: 'overview', label: t('overview') },
    { value: 'predictions', label: t('predictions') },
    { value: 'statistics', label: t('statistics') },
    { value: 'news', label: t('news') },
]);
const indicators = computed((): VisualizationData | null => props.countdown.visualizations.find((item) => item.key === 'key_indicators') ?? null);
const projectionChart = computed((): VisualizationData | null => props.countdown.main_projection?.visualizations.find((item) => item.key === 'projection_curve') ?? null);
</script>

<template>
    <section class="doomsday-card rounded-2xl">
        <div class="relative grid gap-6 border-b border-white/10 p-5 sm:grid-cols-[1fr_auto] sm:p-7">
            <Image :src="countdown.image_url" :alt="countdown.title" aspect-ratio="56.25%" rounded="lg" :ui="{ root: 'sm:hidden' }" />
            <div>
                <p class="doomsday-display text-xs text-ui-muted-foreground">{{ t('selectedEvent') }}</p>
                <h2 class="doomsday-display mt-3 text-2xl text-white sm:text-3xl">{{ countdown.title }}</h2>
                <p class="mt-3 max-w-xl text-sm leading-relaxed text-ui-muted-foreground">{{ countdown.summary }}</p>
            </div>
            <div class="min-w-72">
                <CountdownTimer :target-date="countdown.timer.target_date" />
                <p class="mt-3 text-sm text-ui-muted-foreground">{{ countdown.timer.estimated_label }}</p>
            </div>
            <Link :href="`/?lang=${currentLocale}`" class="absolute right-5 top-5 text-white/60 hover:text-ui-primary" aria-label="Close detail"><X class="h-6 w-6" /></Link>
        </div>

        <div class="flex gap-4 overflow-x-auto border-b border-white/10 px-5 sm:px-7">
            <Button
                v-for="tab in tabs"
                :key="tab.value"
                variant="link"
                :ui="{ root: ['doomsday-display rounded-none border-b-2 px-0 py-4 text-xs no-underline', activeTab === tab.value ? 'border-ui-primary text-ui-primary' : 'border-transparent text-ui-muted-foreground'].join(' ') }"
                @click="activeTab = tab.value"
            >
                {{ tab.label }}
            </Button>
        </div>

        <div class="grid gap-5 p-5 sm:grid-cols-2 sm:p-7">
            <Card v-if="activeTab === 'overview'" :ui="{ root: 'doomsday-card rounded-xl', body: 'p-6' }">
                <h3 class="doomsday-display mb-4 text-white">{{ t('summary') }}</h3>
                <p class="leading-relaxed text-ui-muted-foreground">{{ countdown.description }}</p>
                <div class="mt-6 grid grid-cols-3 gap-3 text-sm">
                    <div class="rounded-lg border border-white/10 bg-white/5 p-3"><span class="text-ui-muted-foreground">Confidence</span><strong class="block text-xl text-white">{{ countdown.main_projection?.confidence_score ?? 0 }}%</strong></div>
                    <div class="rounded-lg border border-white/10 bg-white/5 p-3"><span class="text-ui-muted-foreground">Trend</span><strong class="block text-ui-primary">{{ countdown.main_projection?.trend }}</strong></div>
                    <div class="rounded-lg border border-white/10 bg-white/5 p-3"><span class="text-ui-muted-foreground">Risk</span><strong class="block text-ui-primary">{{ countdown.severity }}</strong></div>
                </div>
            </Card>

            <KeyIndicatorsCard v-if="activeTab === 'overview'" :visualization="indicators" />

            <Card v-if="activeTab === 'overview' || activeTab === 'predictions'" :ui="{ root: 'doomsday-card rounded-xl sm:col-span-1', body: 'p-6' }">
                <h3 class="doomsday-display mb-4 text-white">{{ t('projectionModel') }}</h3>
                <VisualizationChart v-if="projectionChart" :payload="projectionChart.payload" />
                <p class="mt-4 text-sm text-ui-muted-foreground">{{ projectionChart?.description }}</p>
            </Card>

            <Card v-if="activeTab === 'overview' || activeTab === 'news'" :ui="{ root: 'doomsday-card rounded-xl', body: 'p-6' }">
                <h3 class="doomsday-display mb-4 text-white">{{ t('news') }}</h3>
                <div class="grid gap-4">
                    <article v-for="item in countdown.news" :key="item.title" class="border-b border-white/5 pb-3 last:border-b-0 last:pb-0">
                        <h4 class="text-sm text-white">{{ item.title }}</h4>
                        <p class="mt-1 text-xs leading-relaxed text-ui-muted-foreground">{{ item.excerpt }}</p>
                    </article>
                </div>
            </Card>
        </div>
    </section>
</template>
