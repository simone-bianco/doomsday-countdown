<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import BackofficeShell from '@/Components/Backoffice/Layout/BackofficeShell.vue';
import OpenAiKeyManager from '@/Components/Backoffice/OpenAiKeyManager.vue';
import type { BackofficeCounts, BackofficeSection } from '@/Components/Backoffice/Layout/types';

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

const props = defineProps<{
    readonly backofficePath: string;
    readonly counts: BackofficeCounts;
    readonly apiKeys: readonly ApiKey[];
}>();

const activeSection = ref<BackofficeSection>('openai-keys');
</script>

<template>
    <Head title="OpenAI keys" />
    <BackofficeShell
        v-model:active-section="activeSection"
        title="OpenAI keys"
        subtitle="Manage OpenAI key rotation and usage limits."
        :backoffice-path="props.backofficePath"
        :counts="props.counts"
    >
        <OpenAiKeyManager :api-keys="props.apiKeys" :backoffice-path="props.backofficePath" />
    </BackofficeShell>
</template>
