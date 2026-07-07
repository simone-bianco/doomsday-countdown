<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import OpenAiKeyManager from '@/Components/Backoffice/OpenAiKeyManager.vue';
import UserManager from '@/Components/Backoffice/UserManager.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

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

defineProps<{
    readonly backofficePath: string;
    readonly users: readonly User[];
    readonly apiKeys: readonly ApiKey[];
}>();
</script>

<template>
    <Head title="Backoffice" />
    <AppLayout title="Backoffice">
        <div class="grid gap-6">
            <UserManager :users="users" :backoffice-path="backofficePath" />
            <OpenAiKeyManager :api-keys="apiKeys" :backoffice-path="backofficePath" />
        </div>
    </AppLayout>
</template>
