<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import BackofficeShell from '@/Components/Backoffice/Layout/BackofficeShell.vue';
import DashboardOverview from '@/Components/Backoffice/Dashboard/DashboardOverview.vue';
import OpenAiKeyManager from '@/Components/Backoffice/OpenAiKeyManager.vue';
import UserManager from '@/Components/Backoffice/UserManager.vue';
import type { DashboardMetrics } from '@/Components/Backoffice/Dashboard/DashboardOverview.vue';
import type { CountdownSummary } from '@/Components/Backoffice/Doomsday/types';
import type { BackofficeCounts, BackofficeSection } from '@/Components/Backoffice/Layout/types';

type User = {
    readonly id: number;
    readonly name: string;
    readonly email: string;
};

type ApiKey = {
    readonly id: number;
    readonly label: string;
    readonly masked_key: string;
    readonly base_limit_type: string;
    readonly max_base_usage: number | null;
    readonly current_base_usage: number;
    readonly free_limit_type: string;
    readonly max_free_usage: number | null;
    readonly current_free_usage: number;
    readonly is_active: boolean;
    readonly is_depleted: boolean;
    readonly last_used_at: string | null;
};

const props = withDefaults(defineProps<{
    readonly backofficePath: string;
    readonly users: readonly User[];
    readonly apiKeys: readonly ApiKey[];
    readonly metrics?: Partial<DashboardMetrics>;
    readonly recentCountdowns?: readonly CountdownSummary[];
}>(), {
    metrics: () => ({}),
    recentCountdowns: () => [],
});

function initialSection(): BackofficeSection {
    const section = new URLSearchParams(window.location.search).get('section');
    return section === 'users' || section === 'openai-keys' ? section : 'dashboard';
}

const activeSection = ref<BackofficeSection>(initialSection());
const activeKeys = computed(() => props.apiKeys.filter((apiKey) => apiKey.is_active).length);
const dashboardMetrics = computed<DashboardMetrics>(() => ({
    countdowns: props.metrics.countdowns ?? props.recentCountdowns.length,
    published: props.metrics.published ?? props.recentCountdowns.filter((countdown) => countdown.is_published).length,
    drafts: props.metrics.drafts ?? props.recentCountdowns.filter((countdown) => !countdown.is_published).length,
    projections: props.metrics.projections ?? props.recentCountdowns.reduce((total, countdown) => total + countdown.projections_count, 0),
    visualizations: props.metrics.visualizations ?? props.recentCountdowns.reduce((total, countdown) => total + countdown.visualizations_count, 0),
    news: props.metrics.news ?? props.recentCountdowns.reduce((total, countdown) => total + countdown.news_count, 0),
    initiatives: props.metrics.initiatives ?? props.recentCountdowns.reduce((total, countdown) => total + countdown.initiatives_count, 0),
    users: props.metrics.users ?? props.users.length,
    apiKeys: props.metrics.apiKeys ?? props.apiKeys.length,
    activeApiKeys: props.metrics.activeApiKeys ?? activeKeys.value,
}));
const counts = computed<BackofficeCounts>(() => ({
    users: dashboardMetrics.value.users,
    apiKeys: dashboardMetrics.value.apiKeys,
    countdowns: dashboardMetrics.value.countdowns,
}));
const subtitle = computed(() => activeSection.value === 'dashboard'
    ? 'Operational cockpit for Doomsday Countdown administration.'
    : activeSection.value === 'users'
        ? 'Manage authenticated backoffice users.'
        : 'Manage OpenAI key rotation and usage limits.');
</script>

<template>
    <Head title="Backoffice" />
    <BackofficeShell
        v-model:active-section="activeSection"
        title="Backoffice"
        :subtitle="subtitle"
        :backoffice-path="backofficePath"
        :counts="counts"
    >
        <DashboardOverview v-if="activeSection === 'dashboard'" :backoffice-path="backofficePath" :metrics="dashboardMetrics" :recent-countdowns="recentCountdowns" />
        <UserManager v-else-if="activeSection === 'users'" :users="users" :backoffice-path="backofficePath" />
        <OpenAiKeyManager v-else :api-keys="apiKeys" :backoffice-path="backofficePath" />
    </BackofficeShell>
</template>
