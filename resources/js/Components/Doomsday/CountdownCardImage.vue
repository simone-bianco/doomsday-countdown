<script setup lang="ts">
import { computed } from 'vue';
import { Image } from '@simone-bianco/vue-ui-components';

const props = withDefaults(defineProps<{
    readonly imageUrl: string;
    readonly title: string;
    readonly subtitle: string;
    readonly compact?: boolean;
}>(), {
    compact: false,
});

const wrapperClass = computed(() => props.compact
    ? 'relative h-[150px] min-w-0 overflow-hidden sm:h-[160px] xl:h-[150px]'
    : 'relative h-[220px] min-w-0 overflow-hidden sm:h-[240px] xl:h-[260px]');
const overlayClass = computed(() => props.compact ? 'p-3 sm:p-4' : 'p-4 sm:p-5');
const titleClass = computed(() => props.compact
    ? 'doomsday-display line-clamp-2 text-[clamp(1rem,2vw,1.25rem)] font-bold leading-[1.08] tracking-[0.06em] text-white drop-shadow-[0_2px_12px_rgba(0,0,0,0.9)]'
    : 'doomsday-display line-clamp-2 text-[clamp(1.25rem,3vw,1.95rem)] font-bold leading-[1.05] tracking-[0.06em] text-white drop-shadow-[0_2px_16px_rgba(0,0,0,0.9)]');
const subtitleClass = computed(() => props.compact
    ? 'mt-2 line-clamp-2 text-[0.72rem] leading-relaxed text-white/75 sm:text-xs'
    : 'mt-3 line-clamp-2 text-xs leading-relaxed text-white/75 sm:text-sm');
</script>

<template>
    <div :class="wrapperClass">
        <Image :src="imageUrl" :alt="title" rounded="none" :ui="{ root: 'h-full', image: 'h-full w-full object-cover object-center' }" />
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/35 to-black/95" />
        <div class="absolute inset-x-0 bottom-0" :class="overlayClass">
            <h2 :class="titleClass">{{ title }}</h2>
            <p :class="subtitleClass">{{ subtitle }}</p>
        </div>
    </div>
</template>
