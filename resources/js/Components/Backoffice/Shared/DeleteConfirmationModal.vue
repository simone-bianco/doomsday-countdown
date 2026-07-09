<script setup lang="ts">
import { DangerModal } from '@simone-bianco/vue-ui-components';

withDefaults(defineProps<{
    readonly show: boolean;
    readonly title: string;
    readonly description: string;
    readonly loading?: boolean;
}>(), {
    loading: false,
});

const emit = defineEmits<{
    (event: 'close'): void;
    (event: 'confirm'): void;
}>();
</script>

<template>
    <DangerModal
        :show="show"
        :title="title"
        :description="description"
        confirm-label="Delete"
        cancel-label="Keep"
        :loading="loading"
        @close="emit('close')"
        @confirm="emit('confirm')"
    />
</template>
