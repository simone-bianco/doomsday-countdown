<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import CountdownList from '@/Components/Doomsday/CountdownList.vue';
import HeroSection from '@/Components/Doomsday/HeroSection.vue';
import MobileDetailView from '@/Components/Doomsday/MobileDetailView.vue';
import SelectedMasterDetail from '@/Components/Doomsday/SelectedMasterDetail.vue';
import SidebarCards from '@/Components/Doomsday/SidebarCards.vue';
import type { CountdownPageData } from '@/types/generated';

const props = defineProps<{
    readonly page: CountdownPageData;
}>();

const featured = computed(() => props.page.countdowns[0] ?? null);
const hero = computed(() => props.page.hero as Record<string, string>);
const selectedCountdown = computed(() => props.page.selected_countdown);
</script>

<template>
    <Head title="Home" />
    <PublicLayout :languages="page.languages" :current-locale="page.current_locale" :hide-mobile-header="selectedCountdown !== null" active-page="home">
        <template v-if="selectedCountdown">
            <MobileDetailView :countdown="selectedCountdown" :current-locale="page.current_locale" />
            <SelectedMasterDetail :countdowns="page.countdowns" :selected-countdown="selectedCountdown" :hero="hero" :current-locale="page.current_locale" />
        </template>

        <template v-else>
            <HeroSection :hero="hero" />
            <div class="mx-auto grid max-w-[1760px] gap-5 px-4 py-7 sm:px-7 lg:grid-cols-[1fr_520px]">
                <div class="grid gap-5">
                    <CountdownList :countdowns="page.countdowns" />
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
