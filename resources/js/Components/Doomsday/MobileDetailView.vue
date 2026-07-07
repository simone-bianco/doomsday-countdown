<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Button, Card, Image } from '@simone-bianco/vue-ui-components';
import { ChevronDown, ChevronLeft, FileText, Folder, Newspaper, Share2, Sparkles, X } from 'lucide-vue-next';
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
const homeUrl = computed(() => `/?lang=${props.currentLocale}`);
</script>

<template>
    <section class="min-h-screen bg-black pb-24 lg:hidden">
        <header class="sticky top-0 z-40 flex h-16 items-center justify-between border-b border-white/10 bg-black/95 px-4 backdrop-blur-xl">
            <Link :href="homeUrl" class="text-ui-primary" aria-label="Back to countdown list"><ChevronLeft class="h-7 w-7" /></Link>
            <h1 class="doomsday-display max-w-[58vw] truncate text-center text-lg text-white">{{ countdown.title }}</h1>
            <div class="flex items-center gap-3">
                <Button variant="link" size="sm" :icon="Share2" aria-label="Share selected countdown" :ui="{ root: 'p-0 text-ui-primary no-underline' }" />
                <Link :href="homeUrl" class="text-ui-primary" aria-label="Close detail"><X class="h-7 w-7" /></Link>
            </div>
        </header>

        <div class="relative h-[260px] overflow-hidden border-b border-white/10">
            <Image :src="countdown.image_url" :alt="countdown.title" rounded="none" :ui="{ root: 'h-full', image: 'h-full w-full object-cover' }" />
            <div class="absolute inset-0 bg-gradient-to-b from-black/15 via-transparent to-black" />
        </div>

        <div class="-mt-8 px-4">
            <Card :ui="{ root: 'doomsday-card doomsday-glow rounded-xl', body: 'p-5 text-center' }">
                <p class="doomsday-display text-xs text-ui-muted-foreground">{{ countdown.timer.estimated_label }}</p>
                <div class="mt-4 flex justify-center"><CountdownTimer :target-date="countdown.timer.target_date" /></div>
                <p class="mt-4 text-xs text-ui-muted-foreground">{{ countdown.summary }}</p>
            </Card>
        </div>

        <div class="mt-5 flex gap-6 overflow-x-auto border-b border-white/10 px-4">
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

        <div class="grid gap-4 px-4 py-5">
            <Card v-if="activeTab === 'overview'" :ui="{ root: 'doomsday-card rounded-xl', body: 'p-5' }">
                <h2 class="doomsday-display mb-4 text-lg text-white">{{ t('summary') }}</h2>
                <p class="leading-relaxed text-ui-muted-foreground">{{ countdown.description }}</p>
                <div class="mt-5 grid grid-cols-3 gap-2 text-xs">
                    <div class="rounded-lg border border-white/10 bg-white/5 p-3"><span class="text-ui-muted-foreground">Confidence</span><strong class="block text-lg text-white">{{ countdown.main_projection?.confidence_score ?? 0 }}%</strong></div>
                    <div class="rounded-lg border border-white/10 bg-white/5 p-3"><span class="text-ui-muted-foreground">Trend</span><strong class="block text-ui-primary">{{ countdown.main_projection?.trend }}</strong></div>
                    <div class="rounded-lg border border-white/10 bg-white/5 p-3"><span class="text-ui-muted-foreground">Risk</span><strong class="block text-ui-primary">{{ countdown.severity }}</strong></div>
                </div>
                <Button variant="link" size="sm" :icon="ChevronDown" icon-position="right" :ui="{ root: 'mt-5 p-0 text-ui-primary no-underline' }">{{ t('readMore') }}</Button>
            </Card>

            <KeyIndicatorsCard v-if="activeTab === 'overview' || activeTab === 'statistics'" :visualization="indicators" />

            <Card v-if="activeTab === 'overview' || activeTab === 'predictions'" :ui="{ root: 'doomsday-card rounded-xl', body: 'p-5' }">
                <h2 class="doomsday-display mb-4 text-lg text-white">{{ t('projectionModel') }}</h2>
                <VisualizationChart v-if="projectionChart" :payload="projectionChart.payload" />
                <p class="mt-4 text-sm text-ui-muted-foreground">{{ projectionChart?.description }}</p>
            </Card>

            <Card v-if="activeTab === 'overview' || activeTab === 'news'" :ui="{ root: 'doomsday-card rounded-xl', body: 'p-5' }">
                <h2 class="doomsday-display mb-4 text-lg text-white">{{ t('news') }}</h2>
                <div class="grid gap-4">
                    <article v-for="item in countdown.news" :key="item.title" class="border-b border-white/5 pb-3 last:border-b-0 last:pb-0">
                        <h3 class="text-sm text-white">{{ item.title }}</h3>
                        <p class="mt-1 text-xs leading-relaxed text-ui-muted-foreground">{{ item.excerpt }}</p>
                    </article>
                </div>
            </Card>
        </div>

        <nav class="fixed inset-x-0 bottom-0 z-40 grid grid-cols-4 border-t border-white/10 bg-black/95 px-3 pb-5 pt-3 text-center text-[11px] text-ui-muted-foreground backdrop-blur-xl">
            <Button variant="link" size="sm" :icon="Sparkles" :ui="{ root: 'grid justify-items-center gap-1 p-0 text-ui-primary no-underline' }" @click="activeTab = 'overview'">{{ t('overview') }}</Button>
            <Button variant="link" size="sm" :icon="Newspaper" :ui="{ root: 'grid justify-items-center gap-1 p-0 text-ui-muted-foreground no-underline' }" @click="activeTab = 'news'">{{ t('news') }}</Button>
            <a :href="`/about?lang=${currentLocale}`" class="grid justify-items-center gap-1"><Folder class="h-5 w-5" />{{ t('resources') }}</a>
            <Button variant="link" size="sm" :icon="FileText" :ui="{ root: 'grid justify-items-center gap-1 p-0 text-ui-muted-foreground no-underline' }" @click="activeTab = 'predictions'">{{ t('analysis') }}</Button>
        </nav>
    </section>
</template>
