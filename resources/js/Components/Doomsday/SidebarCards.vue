<script setup lang="ts">
import LatestNewsCarousel from './LatestNewsCarousel.vue';
import PublicSignalActivityCard from './PublicSignalActivityCard.vue';
import type { HomeSidebarData } from '@/types/generated';

defineProps<{
    readonly sidebar: HomeSidebarData;
}>();
</script>

<template>
    <aside class="grid min-w-0 content-start gap-5">
        <LatestNewsCarousel :items="sidebar.latest_news" />
        <PublicSignalActivityCard :activity="sidebar.signal_activity" />
        <p class="px-5 text-sm leading-relaxed text-ui-muted-foreground"><span class="text-3xl text-ui-primary">“</span> The end is not sudden. It is a series of events no one wants to believe in—until it is too late.</p>
    </aside>
</template>
