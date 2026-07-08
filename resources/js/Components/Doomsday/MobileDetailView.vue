<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Button, Card, Image } from '@simone-bianco/vue-ui-components';
import { ChevronDown, ChevronLeft, FileText, Folder, Newspaper, Share2, Sparkles, X } from 'lucide-vue-next';
import CountdownTimer from './CountdownTimer.vue';
import DoomsdaySectionError from './DoomsdaySectionError.vue';
import DoomsdaySkeletonBlock from './DoomsdaySkeletonBlock.vue';
import ForecastsSection from './ForecastsSection.vue';
import InitiativesSection from './InitiativesSection.vue';
import NewsSection from './NewsSection.vue';
import StatisticsSection from './StatisticsSection.vue';
import { isLazySectionKey, useDoomsdayLazySections, type LazySectionKey } from '@/Composables/useDoomsdayLazySections';
import { t } from '@/i18n';
import type { CountdownForecastsData, CountdownInitiativesSectionData, CountdownNewsSectionData, CountdownOverviewData, CountdownStatisticsData } from '@/types/generated';

const props = defineProps<{
    readonly countdown: CountdownOverviewData;
    readonly currentLocale: string;
    readonly forecastSection: CountdownForecastsData | null;
    readonly statisticsSection: CountdownStatisticsData | null;
    readonly newsSection: CountdownNewsSectionData | null;
    readonly initiativesSection: CountdownInitiativesSectionData | null;
}>();

const emit = defineEmits<{
    close: [];
}>();

const activeTab = ref('overview');
const tabs = computed(() => [
    { value: 'overview', label: t('overview') },
    { value: 'forecasts', label: t('predictions') },
    { value: 'statistics', label: t('statistics') },
    { value: 'news', label: t('news') },
    { value: 'initiatives', label: t('initiatives') },
]);
const countdownSlug = computed(() => props.countdown.slug);
const currentLocale = computed(() => props.currentLocale);
const lazy = useDoomsdayLazySections(countdownSlug, currentLocale, {
    forecasts: computed(() => props.forecastSection),
    statistics: computed(() => props.statisticsSection),
    news: computed(() => props.newsSection),
    initiatives: computed(() => props.initiativesSection),
});
const forecastSection = lazy.forecastSection;
const statisticsSection = lazy.statisticsSection;
const newsSection = lazy.newsSection;
const initiativesSection = lazy.initiativesSection;

function activateTab(value: string): void {
    activeTab.value = value;
    if (isLazySectionKey(value)) {
        void lazy.loadSection(value);
    }
}

watch(() => `${props.countdown.slug}:${props.currentLocale}`, () => {
    activeTab.value = 'overview';
    lazy.reset();
});
</script>

<template>
    <section class="min-h-screen bg-black pb-24 lg:hidden">
        <header class="sticky top-0 z-40 flex h-16 items-center justify-between border-b border-white/10 bg-black/95 px-4 backdrop-blur-xl">
            <Button variant="link" size="sm" :icon="ChevronLeft" aria-label="Back to countdown list" :ui="{ root: 'p-0 text-ui-primary no-underline' }" @click="emit('close')" />
            <h1 class="doomsday-display max-w-[58vw] truncate text-center text-lg text-white">{{ countdown.title }}</h1>
            <div class="flex items-center gap-3">
                <Button variant="link" size="sm" :icon="Share2" aria-label="Share selected countdown" :ui="{ root: 'p-0 text-ui-primary no-underline' }" />
                <Button variant="link" size="sm" :icon="X" aria-label="Close detail" :ui="{ root: 'p-0 text-ui-primary no-underline' }" @click="emit('close')" />
            </div>
        </header>

        <div class="relative h-[220px] overflow-hidden border-b border-white/10 sm:h-[260px]">
            <Image :src="countdown.image_url" :alt="countdown.title" rounded="none" :ui="{ root: 'h-full', image: 'h-full w-full object-cover object-center sm:object-[center_35%]' }" />
            <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-transparent to-black/70" />
        </div>

        <div class="mt-4 px-4">
            <Card :ui="{ root: 'doomsday-card doomsday-glow rounded-xl', body: 'p-5 text-center' }">
                <div class="flex justify-center"><CountdownTimer :target-date="countdown.timer.target_date" /></div>
                <p class="mt-4 text-xs text-ui-muted-foreground">{{ countdown.summary }}</p>
            </Card>
        </div>

        <div class="mt-5 flex gap-6 overflow-x-auto border-b border-white/10 px-4">
            <Button
                v-for="tab in tabs"
                :key="tab.value"
                variant="link"
                :ui="{ root: ['doomsday-display rounded-none border-b-2 px-0 py-4 text-xs no-underline outline-none ring-0 shadow-none focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0 active:ring-0 active:outline-none', activeTab === tab.value ? 'border-ui-primary text-ui-primary' : 'border-transparent text-ui-muted-foreground'].join(' ') }"
                @click="activateTab(tab.value)"
            >
                {{ tab.label }}
            </Button>
        </div>

        <div class="grid gap-4 px-4 py-5">
            <template v-if="activeTab === 'overview'">
                <Card :ui="{ root: 'doomsday-card rounded-xl', body: 'p-5' }">
                    <h2 class="doomsday-display mb-4 text-lg text-white">{{ t('summary') }}</h2>
                    <p class="leading-relaxed text-ui-muted-foreground">{{ countdown.description }}</p>
                    <div class="mt-5 grid grid-cols-[repeat(auto-fit,minmax(96px,1fr))] gap-2 text-xs">
                        <div class="rounded-lg border border-white/10 bg-white/5 p-3"><span class="text-ui-muted-foreground">Confidence</span><strong class="block text-lg text-white">{{ countdown.main_projection?.confidence_score ?? 0 }}%</strong></div>
                        <div class="rounded-lg border border-white/10 bg-white/5 p-3"><span class="text-ui-muted-foreground">Trend</span><strong class="block text-ui-primary">{{ countdown.main_projection?.trend }}</strong></div>
                        <div class="rounded-lg border border-white/10 bg-white/5 p-3"><span class="text-ui-muted-foreground">Risk</span><strong class="block text-ui-primary">{{ countdown.severity }}</strong></div>
                    </div>
                    <Button variant="link" size="sm" :icon="ChevronDown" icon-position="right" :ui="{ root: 'mt-5 p-0 text-ui-primary no-underline' }">{{ t('readMore') }}</Button>
                </Card>
            </template>

            <template v-else-if="activeTab === 'forecasts'">
                <ForecastsSection v-if="forecastSection" :section="forecastSection" />
                <DoomsdaySectionError v-else-if="lazy.hasError('forecasts')" title="Forecasts unavailable" @retry="lazy.loadSection('forecasts')" />
                <DoomsdaySkeletonBlock v-else variant="chart" />
            </template>

            <template v-else-if="activeTab === 'statistics'">
                <StatisticsSection v-if="statisticsSection" :section="statisticsSection" />
                <DoomsdaySectionError v-else-if="lazy.hasError('statistics')" title="Statistics unavailable" @retry="lazy.loadSection('statistics')" />
                <DoomsdaySkeletonBlock v-else variant="summary" />
            </template>

            <template v-else-if="activeTab === 'news'">
                <NewsSection v-if="newsSection" :section="newsSection" />
                <DoomsdaySectionError v-else-if="lazy.hasError('news')" title="News unavailable" @retry="lazy.loadSection('news')" />
                <DoomsdaySkeletonBlock v-else variant="list" />
            </template>

            <template v-else-if="activeTab === 'initiatives'">
                <InitiativesSection v-if="initiativesSection" :section="initiativesSection" />
                <DoomsdaySectionError v-else-if="lazy.hasError('initiatives')" title="Initiatives unavailable" @retry="lazy.loadSection('initiatives')" />
                <DoomsdaySkeletonBlock v-else variant="initiatives" />
            </template>
        </div>

        <nav class="fixed inset-x-0 bottom-0 z-40 grid grid-cols-4 border-t border-white/10 bg-black/95 px-3 pb-5 pt-3 text-center text-[11px] text-ui-muted-foreground backdrop-blur-xl">
            <Button variant="link" size="sm" :icon="Sparkles" :ui="{ root: 'grid justify-items-center gap-1 p-0 text-ui-primary no-underline' }" @click="activateTab('overview')">{{ t('overview') }}</Button>
            <Button variant="link" size="sm" :icon="Newspaper" :ui="{ root: 'grid justify-items-center gap-1 p-0 text-ui-muted-foreground no-underline' }" @click="activateTab('news')">{{ t('news') }}</Button>
            <a :href="`/about?lang=${currentLocale}`" class="grid justify-items-center gap-1"><Folder class="h-5 w-5" />{{ t('resources') }}</a>
            <Button variant="link" size="sm" :icon="FileText" :ui="{ root: 'grid justify-items-center gap-1 p-0 text-ui-muted-foreground no-underline' }" @click="activateTab('forecasts')">{{ t('analysis') }}</Button>
        </nav>
    </section>
</template>
