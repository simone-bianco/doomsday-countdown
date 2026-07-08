<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { ArrowLeft, Maximize2, Minimize2 } from 'lucide-vue-next';
import { Button, Image } from '@simone-bianco/vue-ui-components';
import CountdownTimer from './CountdownTimer.vue';
import DoomsdaySectionError from './DoomsdaySectionError.vue';
import DoomsdaySkeletonBlock from './DoomsdaySkeletonBlock.vue';
import ForecastsSection from './ForecastsSection.vue';
import InitiativesSection from './InitiativesSection.vue';
import NewsSection from './NewsSection.vue';
import OverviewSection from './OverviewSection.vue';
import StatisticsSection from './StatisticsSection.vue';
import { isLazySectionKey, useDoomsdayLazySections, type LazySectionKey } from '@/Composables/useDoomsdayLazySections';
import { t } from '@/i18n';
import type { CountdownForecastsData, CountdownInitiativesSectionData, CountdownNewsSectionData, CountdownOverviewData, CountdownStatisticsData } from '@/types/generated';

const props = withDefaults(defineProps<{
    readonly countdown: CountdownOverviewData;
    readonly currentLocale: string;
    readonly forecastSection: CountdownForecastsData | null;
    readonly statisticsSection: CountdownStatisticsData | null;
    readonly newsSection: CountdownNewsSectionData | null;
    readonly initiativesSection: CountdownInitiativesSectionData | null;
    readonly expanded?: boolean;
}>(), {
    expanded: false,
});

const emit = defineEmits<{
    close: [];
    toggleExpanded: [];
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
    <section class="doomsday-card flex max-h-[calc(100vh-5.25rem)] min-h-0 flex-col rounded-2xl">
        <div class="grid min-w-0 shrink-0 gap-4 border-b border-white/10 p-4 sm:p-5 xl:grid-cols-[minmax(0,1fr)_auto]">
            <Image :src="countdown.image_url" :alt="countdown.title" aspect-ratio="56.25%" rounded="lg" :ui="{ root: 'sm:hidden' }" />
            <div class="min-w-0">
                <div class="mb-2 flex flex-wrap items-center gap-3">
                    <Button variant="link" size="sm" :icon="ArrowLeft" :ui="{ root: 'p-0 text-ui-primary no-underline' }" @click="emit('close')">All countdowns</Button>
                    <Button variant="link" size="sm" :icon="expanded ? Minimize2 : Maximize2" :ui="{ root: 'p-0 text-ui-muted-foreground no-underline hover:text-ui-primary' }" @click="emit('toggleExpanded')">
                        {{ expanded ? 'Collapse' : 'Expand' }}
                    </Button>
                </div>
                <h2 class="doomsday-display mt-4 text-xl leading-tight text-white sm:text-2xl 2xl:text-3xl">{{ countdown.title }}</h2>
                <p class="mt-2 max-w-3xl text-sm leading-relaxed text-ui-muted-foreground">{{ countdown.summary }}</p>
            </div>
            <div class="min-w-0 max-w-full justify-self-start text-center xl:justify-self-end">
                <CountdownTimer :target-date="countdown.timer.target_date" />
            </div>
        </div>

        <div class="doomsday-scrollbar flex shrink-0 gap-3 overflow-x-auto border-b border-white/10 px-4 sm:px-5">
            <Button
                v-for="tab in tabs"
                :key="tab.value"
                variant="link"
                :ui="{ root: ['doomsday-display rounded-none border-b-2 px-0 py-3 text-xs no-underline outline-none ring-0 shadow-none focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0 active:ring-0 active:outline-none', activeTab === tab.value ? 'border-ui-primary text-ui-primary' : 'border-transparent text-ui-muted-foreground'].join(' ') }"
                @click="activateTab(tab.value)"
            >
                {{ tab.label }}
            </Button>
        </div>

        <div :class="['doomsday-scrollbar grid min-h-0 min-w-0 flex-1 gap-5 overflow-y-auto p-4 sm:p-5', expanded ? 'xl:grid-cols-2' : '']">
            <template v-if="activeTab === 'overview'">
                <OverviewSection :countdown="countdown" />
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
    </section>
</template>
