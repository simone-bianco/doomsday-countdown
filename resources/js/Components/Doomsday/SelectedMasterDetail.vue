<script setup lang="ts">
import { computed, ref } from 'vue';
import { motion } from 'motion-v';
import CountdownList from './CountdownList.vue';
import DetailPanel from './DetailPanel.vue';
import DoomsdaySkeletonBlock from './DoomsdaySkeletonBlock.vue';
import { panelReveal, resolveMotionPreset, useDoomsdayReducedMotion } from '@/animations/doomsdayMotion';
import type { CountdownForecastsData, CountdownIndexData, CountdownInitiativesSectionData, CountdownNewsSectionData, CountdownOverviewData, CountdownStatisticsData } from '@/types/generated';

withDefaults(defineProps<{
    readonly countdowns: readonly CountdownIndexData[];
    readonly selectedCountdown: CountdownOverviewData | null;
    readonly hero: Record<string, string>;
    readonly currentLocale: string;
    readonly forecastSection: CountdownForecastsData | null;
    readonly statisticsSection: CountdownStatisticsData | null;
    readonly newsSection: CountdownNewsSectionData | null;
    readonly initiativesSection: CountdownInitiativesSectionData | null;
    readonly selectedSlug?: string | null;
    readonly pendingSlug?: string | null;
    readonly isLoadingSelection?: boolean;
}>(), {
    selectedSlug: null,
    pendingSlug: null,
    isLoadingSelection: false,
});

const emit = defineEmits<{
    select: [countdown: CountdownIndexData];
    close: [];
}>();

const isDetailExpanded = ref(false);
const reducedMotion = useDoomsdayReducedMotion();
const detailMotion = computed(() => resolveMotionPreset(panelReveal, reducedMotion.value));
</script>

<template>
    <motion.section :class="['mx-auto hidden max-w-[1760px] h-[calc(100vh-64px)] min-h-0 gap-5 overflow-hidden px-5 py-4 lg:grid xl:px-7', isDetailExpanded ? 'grid-cols-1' : 'grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]']" :initial="detailMotion.initial" :animate="detailMotion.animate" :transition="detailMotion.transition">
        <div v-if="!isDetailExpanded" class="doomsday-scrollbar grid min-h-0 min-w-0 content-start gap-5 overflow-y-auto pr-1">
            <div class="relative min-w-0 overflow-hidden rounded-2xl border border-white/10 bg-black p-6 xl:p-8">
                <img :src="hero.desktop_image" alt="Earth horizon with red monitoring arcs" class="absolute inset-0 h-full w-full object-cover object-center opacity-45" />
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/85 to-black/20" />
                <div class="relative max-w-2xl">
                    <div class="mb-6 h-px w-24 bg-ui-primary/70" />
                    <h1 class="doomsday-display text-3xl leading-tight text-white 2xl:text-5xl">
                        {{ hero.headline_prefix }}<br />
                        <span class="text-white/70">{{ hero.headline_middle }}</span>
                        <span class="doomsday-red-text"> {{ hero.headline_accent }}</span>
                    </h1>
                    <p class="mt-5 text-base text-ui-muted-foreground">{{ hero.subtitle }}</p>
                </div>
            </div>

            <CountdownList :countdowns="countdowns" :compact="true" :selected-slug="selectedSlug" :pending-slug="pendingSlug" @select="emit('select', $event)" />
        </div>

        <div class="min-h-0 min-w-0 self-stretch">
            <DetailPanel
                v-if="selectedCountdown && !isLoadingSelection"
                :countdown="selectedCountdown"
                :current-locale="currentLocale"
                :forecast-section="forecastSection"
                :statistics-section="statisticsSection"
                :news-section="newsSection"
                :initiatives-section="initiativesSection"
                :expanded="isDetailExpanded"
                @toggle-expanded="isDetailExpanded = !isDetailExpanded"
                @close="emit('close')"
            />
            <DoomsdaySkeletonBlock v-else />
        </div>
    </motion.section>
</template>
