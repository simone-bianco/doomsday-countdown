<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ExternalLink, Gauge, KeyRound, Target, Users } from 'lucide-vue-next';
import { SidebarNavigation } from '@simone-bianco/vue-ui-components';
import type { BackofficeCounts, BackofficeSection } from '@/Components/Backoffice/Layout/types';

const props = defineProps<{
    readonly activeSection: BackofficeSection;
    readonly backofficePath: string;
    readonly counts: BackofficeCounts;
}>();

const normalizedBackofficePath = computed(() => props.backofficePath.replace(/\/+$/g, ''));
const dashboardPath = computed(() => normalizedBackofficePath.value);
const usersPath = computed(() => `${normalizedBackofficePath.value}?section=users`);
const openAiKeysPath = computed(() => `${normalizedBackofficePath.value}?section=openai-keys`);
const countdownsPath = computed(() => `${normalizedBackofficePath.value}/countdowns`);

const groups = computed(() => [
    {
        label: 'Operations',
        items: [
            {
                label: 'Dashboard',
                href: dashboardPath.value,
                icon: Gauge,
                active: props.activeSection === 'dashboard',
            },
        ],
    },
    {
        label: 'Doomsday CRUD',
        items: [
            {
                label: 'Countdowns',
                href: countdownsPath.value,
                icon: Target,
                count: props.counts.countdowns,
                active: props.activeSection === 'countdowns',
            },
        ],
    },
    {
        label: 'Administration',
        items: [
            {
                label: 'Users',
                href: usersPath.value,
                icon: Users,
                count: props.counts.users,
                active: props.activeSection === 'users',
            },
            {
                label: 'OpenAI keys',
                href: openAiKeysPath.value,
                icon: KeyRound,
                count: props.counts.apiKeys,
                active: props.activeSection === 'openai-keys',
            },
        ],
    },
]);
</script>

<template>
    <SidebarNavigation
        :groups="groups"
        site-name="Doomsday Ops"
        position="relative"
        :ui="{
            root: 'h-[calc(100vh-73px)] min-h-0 w-[17rem] shrink-0 overflow-hidden rounded-none border-y-0 border-l-0 border-r border-ui-border/70 bg-ui-card/95 shadow-none',
            header: 'h-16 px-4',
            nav: 'min-h-0 flex-none overflow-y-hidden overflow-x-hidden px-3 py-4 space-y-4',
            itemIcon: 'mr-2 h-4 w-4',
        }"
    >
        <template #header>
            <span class="hidden" aria-hidden="true" />
        </template>
        <template #top>
            <Link href="/" class="group flex items-center justify-between rounded-xl border border-ui-primary/25 bg-ui-primary/10 px-3 py-2 text-sm font-semibold text-ui-primary transition hover:border-ui-primary/50 hover:bg-ui-primary/15 hover:text-ui-primary-hover">
                <span>Visit site</span>
                <ExternalLink class="h-4 w-4 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
            </Link>
        </template>
    </SidebarNavigation>
</template>
