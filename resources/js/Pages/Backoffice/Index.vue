<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import BackofficeShell from '@/Components/Backoffice/Layout/BackofficeShell.vue';
import DashboardOverview from '@/Components/Backoffice/Dashboard/DashboardOverview.vue';
import type { DashboardMetrics } from '@/Components/Backoffice/Dashboard/DashboardOverview.vue';
import type { CountdownSummary } from '@/Components/Backoffice/Doomsday/types';
import type { BackofficeCounts, BackofficeSection } from '@/Components/Backoffice/Layout/types';

const props = defineProps<{
    readonly backofficePath: string;
    readonly counts: BackofficeCounts;
    readonly metrics: DashboardMetrics;
    readonly recentCountdowns: readonly CountdownSummary[];
}>();

const activeSection = ref<BackofficeSection>('dashboard');
</script>

<template>
    <Head title="Backoffice" />
    <BackofficeShell
        v-model:active-section="activeSection"
        title="Backoffice"
        subtitle="Operational cockpit for Doomsday Countdown administration."
        :backoffice-path="props.backofficePath"
        :counts="props.counts"
    >
        <DashboardOverview :backoffice-path="props.backofficePath" :metrics="props.metrics" :recent-countdowns="props.recentCountdowns" />
    </BackofficeShell>
</template>
