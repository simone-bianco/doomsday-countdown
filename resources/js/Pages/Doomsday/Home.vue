<script setup lang="ts">
import { computed } from 'vue';
import { AnimatePresence, motion } from 'motion-v';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import CountdownList from '@/Components/Doomsday/CountdownList.vue';
import HeroSection from '@/Components/Doomsday/HeroSection.vue';
import MobileDetailSkeleton from '@/Components/Doomsday/MobileDetailSkeleton.vue';
import MobileDetailView from '@/Components/Doomsday/MobileDetailView.vue';
import SelectedMasterDetail from '@/Components/Doomsday/SelectedMasterDetail.vue';
import SidebarCards from '@/Components/Doomsday/SidebarCards.vue';
import { useDoomsdaySelection } from '@/Composables/useDoomsdaySelection';
import { panelReveal, resolveMotionPreset, useDoomsdayReducedMotion } from '@/animations/doomsdayMotion';
import type { CountdownForecastsData, CountdownInitiativesSectionData, CountdownNewsSectionData, CountdownOverviewData, CountdownPageData, CountdownStatisticsData } from '@/types/generated';

const props = defineProps<{
    readonly page: CountdownPageData;
    readonly selected_countdown?: CountdownOverviewData | null;
    readonly forecast_section?: CountdownForecastsData | null;
    readonly statistics_section?: CountdownStatisticsData | null;
    readonly news_section?: CountdownNewsSectionData | null;
    readonly initiatives_section?: CountdownInitiativesSectionData | null;
}>();

const hero = computed(() => props.page.hero as unknown as Record<string, string>);
const initialSelectedCountdown = computed(() => props.selected_countdown ?? null);
const currentLocale = computed(() => props.page.current_locale);
const selection = useDoomsdaySelection(initialSelectedCountdown, currentLocale);
const reducedMotion = useDoomsdayReducedMotion();
const pageStateMotion = computed(() => resolveMotionPreset(panelReveal, reducedMotion.value));
</script>

<template>
    <Head title="Home" />
    <PublicLayout :languages="page.languages" :current-locale="page.current_locale" :hide-mobile-header="selection.detailOpen.value" active-page="home">
        <AnimatePresence mode="wait" :initial="false">
            <template v-if="selection.detailOpen.value">
                <motion.div key="detail" class="min-h-0" :initial="pageStateMotion.initial" :animate="pageStateMotion.animate" :exit="pageStateMotion.exit" :transition="pageStateMotion.transition">
                    <MobileDetailView
                        v-if="selection.selectedCountdown.value && !selection.isReplacingSelection.value"
                        :countdown="selection.selectedCountdown.value"
                        :current-locale="page.current_locale"
                        :forecast-section="forecast_section ?? null"
                        :statistics-section="statistics_section ?? null"
                        :news-section="news_section ?? null"
                        :initiatives-section="initiatives_section ?? null"
                        @close="selection.closeSelectedCountdown"
                    />
                    <MobileDetailSkeleton v-else @close="selection.closeSelectedCountdown" />
                    <SelectedMasterDetail
                        :countdowns="page.countdowns"
                        :selected-countdown="selection.selectedCountdown.value"
                        :hero="hero"
                        :current-locale="page.current_locale"
                        :forecast-section="forecast_section ?? null"
                        :statistics-section="statistics_section ?? null"
                        :news-section="news_section ?? null"
                        :initiatives-section="initiatives_section ?? null"
                        :selected-slug="selection.activeSelectedSlug.value"
                        :pending-slug="selection.pendingSelectedSlug.value"
                        :is-loading-selection="selection.isReplacingSelection.value"
                        @select="selection.selectCountdown"
                        @close="selection.closeSelectedCountdown"
                    />
                </motion.div>
            </template>

            <template v-else>
                <motion.div key="overview" class="min-h-0" :initial="pageStateMotion.initial" :animate="pageStateMotion.animate" :exit="pageStateMotion.exit" :transition="pageStateMotion.transition">
                    <HeroSection :hero="hero" />
                    <div class="mx-auto grid items-start max-w-[1760px] gap-5 px-4 py-7 sm:px-7 lg:grid-cols-[minmax(0,1fr)_560px] 2xl:grid-cols-[minmax(0,1fr)_600px]">
                        <div class="grid content-start items-start gap-5">
                            <CountdownList
                                :countdowns="page.countdowns"
                                :selected-slug="selection.activeSelectedSlug.value"
                                :pending-slug="selection.pendingSelectedSlug.value"
                                @select="selection.selectCountdown"
                            />
                        </div>
                        <SidebarCards class="hidden lg:grid" :sidebar="page.sidebar" />
                    </div>
                    <footer class="mx-auto flex max-w-[1760px] items-center justify-between px-4 pb-8 text-xs text-ui-muted-foreground sm:px-7">
                        <span>All countdowns are editorial estimates based on public-source scenario data.</span>
                        <a :href="`/about?lang=${page.current_locale}`" class="text-ui-primary">Learn more about our methodology</a>
                    </footer>
                </motion.div>
            </template>
        </AnimatePresence>
    </PublicLayout>
</template>
