<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import CountdownList from '@/Components/Doomsday/CountdownList.vue';
import HeroSection from '@/Components/Doomsday/HeroSection.vue';
import MobileDetailSkeleton from '@/Components/Doomsday/MobileDetailSkeleton.vue';
import MobileDetailView from '@/Components/Doomsday/MobileDetailView.vue';
import SelectedMasterDetail from '@/Components/Doomsday/SelectedMasterDetail.vue';
import SidebarCards from '@/Components/Doomsday/SidebarCards.vue';
import { useDoomsdaySelection } from '@/Composables/useDoomsdaySelection';
import type { CountdownForecastsData, CountdownInitiativesSectionData, CountdownNewsSectionData, CountdownOverviewData, CountdownPageData, CountdownStatisticsData } from '@/types/generated';

const props = defineProps<{
    readonly page: CountdownPageData;
    readonly selected_countdown?: CountdownOverviewData | null;
    readonly forecast_section?: CountdownForecastsData | null;
    readonly statistics_section?: CountdownStatisticsData | null;
    readonly news_section?: CountdownNewsSectionData | null;
    readonly initiatives_section?: CountdownInitiativesSectionData | null;
}>();

const featured = computed(() => props.page.countdowns[0] ?? null);
const hero = computed(() => props.page.hero as Record<string, string>);
const initialSelectedCountdown = computed(() => props.selected_countdown ?? null);
const currentLocale = computed(() => props.page.current_locale);
const selection = useDoomsdaySelection(initialSelectedCountdown, currentLocale);
</script>

<template>
    <Head title="Home" />
    <PublicLayout :languages="page.languages" :current-locale="page.current_locale" :hide-mobile-header="selection.detailOpen.value" active-page="home">
        <template v-if="selection.detailOpen.value">
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
        </template>

        <template v-else>
            <HeroSection :hero="hero" />
            <div class="mx-auto grid max-w-[1760px] gap-5 px-4 py-7 sm:px-7 lg:grid-cols-[minmax(0,1fr)_560px] 2xl:grid-cols-[minmax(0,1fr)_600px]">
                <div class="grid gap-5">
                    <CountdownList
                        :countdowns="page.countdowns"
                        :selected-slug="selection.activeSelectedSlug.value"
                        :pending-slug="selection.pendingSelectedSlug.value"
                        @select="selection.selectCountdown"
                    />
                </div>
                <SidebarCards class="hidden lg:grid" :featured="featured" />
            </div>
            <footer class="mx-auto flex max-w-[1760px] items-center justify-between px-4 pb-8 text-xs text-ui-muted-foreground sm:px-7">
                <span>All countdowns are estimates generated from sample scenario data.</span>
                <a :href="`/about?lang=${page.current_locale}`" class="text-ui-primary">Learn more about our methodology</a>
            </footer>
        </template>
    </PublicLayout>
</template>
