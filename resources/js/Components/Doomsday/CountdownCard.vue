<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';
import { Card, Image } from '@simone-bianco/vue-ui-components';
import CountdownTimer from './CountdownTimer.vue';
import type { CountdownIndexData } from '@/types/generated';

const props = withDefaults(defineProps<{
    readonly countdown: CountdownIndexData;
    readonly compact?: boolean;
}>(), {
    compact: false,
});

const gridClass = computed(() => props.compact
    ? 'grid grid-cols-[42%_1fr] gap-0 xl:grid-cols-[220px_1fr_255px_34px]'
    : 'grid grid-cols-[42%_1fr] gap-0 sm:grid-cols-[300px_1fr_minmax(300px,1.2fr)_48px]');
const imageClass = computed(() => props.compact ? 'relative min-h-32 overflow-hidden sm:min-h-36' : 'relative min-h-36 overflow-hidden sm:min-h-40');
const titleClass = computed(() => props.compact ? 'doomsday-display text-base leading-tight text-white sm:text-lg' : 'doomsday-display text-lg leading-tight text-white sm:text-2xl');
const bodyClass = computed(() => props.compact ? 'flex flex-col justify-center gap-2 p-4' : 'flex flex-col justify-center gap-3 p-5');
</script>

<template>
    <Link :href="countdown.url" class="block">
        <Card
            :ui="{
                root: ['doomsday-card rounded-xl transition duration-200 hover:border-ui-primary/80', countdown.is_selected ? 'doomsday-glow border-ui-primary' : ''].join(' '),
                body: 'p-0',
            }"
        >
            <div :class="gridClass">
                <div :class="imageClass">
                    <Image :src="countdown.image_url" :alt="countdown.title" rounded="none" aspect-ratio="100%" :ui="{ root: 'h-full', image: 'h-full w-full object-cover' }" />
                    <div class="absolute inset-0 bg-gradient-to-r from-black/5 to-black/55" />
                </div>
                <div :class="bodyClass">
                    <h2 :class="titleClass">{{ countdown.title }}</h2>
                    <p class="text-sm leading-relaxed text-ui-muted-foreground" :class="compact ? 'line-clamp-2' : 'sm:text-base'">{{ countdown.summary }}</p>
                </div>
                <div class="col-span-2 flex flex-col justify-center border-t border-white/10 p-4 xl:col-span-1 xl:border-l xl:border-t-0" :class="compact ? '' : 'sm:col-span-1 sm:p-5 sm:border-l sm:border-t-0'">
                    <CountdownTimer :target-date="countdown.timer.target_date" :compact="true" :dense="compact" />
                    <p class="mt-3 text-xs text-ui-muted-foreground">{{ countdown.timer.estimated_label }}</p>
                </div>
                <div class="hidden items-center justify-center text-ui-primary sm:flex"><ChevronRight class="h-7 w-7" /></div>
            </div>
        </Card>
    </Link>
</template>
