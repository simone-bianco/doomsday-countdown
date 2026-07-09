<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import BackofficeShell from '@/Components/Backoffice/Layout/BackofficeShell.vue';
import CountdownForm from '@/Components/Backoffice/Doomsday/CountdownForm.vue';
import { useBackofficePath } from '@/Components/Backoffice/Doomsday/useBackofficePath';
import type { BackofficeOptions } from '@/Components/Backoffice/Doomsday/types';
import type { BackofficeSection } from '@/Components/Backoffice/Layout/types';

defineProps<{
    readonly options: BackofficeOptions;
}>();

const { backofficePath, normalizedBackofficePath, counts } = useBackofficePath();
const activeSection = ref<BackofficeSection>('countdowns');
</script>

<template>
    <Head title="Create countdown" />
    <BackofficeShell v-model:active-section="activeSection" title="Create countdown" subtitle="Create a public Doomsday countdown draft." :backoffice-path="backofficePath" :counts="counts">
        <CountdownForm :options="options" :submit-url="`${normalizedBackofficePath}/countdowns`" method="post" submit-label="Create countdown" @saved="router.visit(`${normalizedBackofficePath}/countdowns`)" />
    </BackofficeShell>
</template>
