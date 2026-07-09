<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import BackofficeShell from '@/Components/Backoffice/Layout/BackofficeShell.vue';
import UserManager from '@/Components/Backoffice/UserManager.vue';
import type { BackofficeCounts, BackofficeSection } from '@/Components/Backoffice/Layout/types';

type User = {
    readonly id: number;
    readonly name: string;
    readonly email: string;
};

const props = defineProps<{
    readonly backofficePath: string;
    readonly counts: BackofficeCounts;
    readonly users: readonly User[];
}>();

const activeSection = ref<BackofficeSection>('users');
</script>

<template>
    <Head title="Backoffice users" />
    <BackofficeShell
        v-model:active-section="activeSection"
        title="Users"
        subtitle="Manage authenticated backoffice users."
        :backoffice-path="props.backofficePath"
        :counts="props.counts"
    >
        <UserManager :users="props.users" :backoffice-path="props.backofficePath" />
    </BackofficeShell>
</template>
