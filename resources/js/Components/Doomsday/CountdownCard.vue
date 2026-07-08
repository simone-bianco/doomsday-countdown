<script setup lang="ts">
import { computed } from 'vue';
import { Card } from '@simone-bianco/vue-ui-components';
import CountdownCardImage from './CountdownCardImage.vue';
import CountdownTimer from './CountdownTimer.vue';
import type { CountdownIndexData } from '@/types/generated';

const props = withDefaults(defineProps<{
    readonly countdown: CountdownIndexData;
    readonly compact?: boolean;
    readonly selectedSlug?: string | null;
    readonly pendingSlug?: string | null;
}>(), {
    compact: false,
    selectedSlug: null,
    pendingSlug: null,
});

const emit = defineEmits<{
    select: [countdown: CountdownIndexData];
}>();

const isSelected = computed(() => props.countdown.slug === props.selectedSlug);
const isPending = computed(() => props.countdown.slug === props.pendingSlug);
const gridClass = computed(() => 'grid min-w-0 grid-cols-1 gap-0');
const timerClass = computed(() => props.compact
    ? 'flex min-w-0 flex-col items-center justify-center border-t border-white/10 px-4 py-2.5 text-center sm:px-5'
    : 'flex min-w-0 flex-col items-center justify-center border-t border-white/10 px-4 py-3 text-center sm:px-6');
</script>

<template>
    <Card
        role="button"
        tabindex="0"
        :aria-pressed="isSelected"
        :ui="{
            root: ['doomsday-card min-w-0 rounded-xl transition duration-200 hover:border-ui-primary/80 focus:outline-none focus:ring-2 focus:ring-ui-primary/50', isSelected ? 'doomsday-glow border-ui-primary' : '', isPending ? 'border-ui-primary/70 shadow-[0_0_28px_rgba(255,42,35,0.18)]' : ''].join(' '),
            body: 'p-0 min-w-0',
        }"
        @click="emit('select', countdown)"
        @keydown.enter="emit('select', countdown)"
        @keydown.space.prevent="emit('select', countdown)"
    >
        <div :class="gridClass">
            <CountdownCardImage
                :image-url="countdown.image_url"
                :title="countdown.title"
                :subtitle="countdown.summary"
                :compact="compact"
            />
            <div :class="timerClass">
                <CountdownTimer :target-date="countdown.timer.target_date" :compact="true" :dense="compact" />
            </div>
        </div>
    </Card>
</template>
