<script setup lang="ts">
import { RefreshCw } from 'lucide-vue-next';
import { Button, Card } from '@simone-bianco/vue-ui-components';

defineProps<{
    readonly title: string;
}>();

const emit = defineEmits<{
    retry: [];
}>();
</script>

<template>
    <Card :ui="{ root: 'doomsday-card rounded-xl border-ui-primary/40', body: 'p-6' }">
        <p class="doomsday-display text-ui-primary">{{ title }}</p>
        <p class="mt-3 text-sm text-ui-muted-foreground">The section could not be loaded. Retry the Inertia partial reload.</p>
        <Button class="mt-5" variant="secondary" size="sm" :icon="RefreshCw" @click="emit('retry')">Retry</Button>
    </Card>
</template>
