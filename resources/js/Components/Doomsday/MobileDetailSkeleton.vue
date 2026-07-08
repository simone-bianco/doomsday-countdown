<script setup lang="ts">
import { ChevronLeft } from 'lucide-vue-next';
import { Button, Card } from '@simone-bianco/vue-ui-components';
import DoomsdaySkeletonBlock from './DoomsdaySkeletonBlock.vue';

const emit = defineEmits<{
    close: [];
}>();
</script>

<template>
    <section class="min-h-screen bg-black pb-24 lg:hidden">
        <header class="sticky top-0 z-40 flex h-16 items-center justify-between border-b border-white/10 bg-black/95 px-4 backdrop-blur-xl">
            <Button variant="link" size="sm" :icon="ChevronLeft" aria-label="Back to countdown list" :ui="{ root: 'p-0 text-ui-primary no-underline' }" @click="emit('close')" />
            <div class="doomsday-skeleton h-4 w-44 rounded" />
            <span class="h-7 w-7" />
        </header>
        <div class="relative h-[220px] overflow-hidden border-b border-white/10 bg-gradient-to-b from-ui-primary/20 to-black sm:h-[260px]">
            <div class="absolute inset-0 doomsday-skeleton opacity-40" />
        </div>
        <div class="mt-4 px-4">
            <Card :ui="{ root: 'doomsday-card doomsday-glow rounded-xl', body: 'p-5' }">
                <DoomsdaySkeletonBlock />
            </Card>
        </div>
    </section>
</template>
