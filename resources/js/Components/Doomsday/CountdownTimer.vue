<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import type { DoomsdayPageProps } from '@/types/page-props';
import { countdownParts, parseRenderedAt } from './countdownClock';

const props = withDefaults(defineProps<{
    readonly targetDate: string | null;
    readonly compact?: boolean;
    readonly dense?: boolean;
}>(), {
    compact: false,
    dense: false,
});

const page = usePage<DoomsdayPageProps>();
const now = ref(parseRenderedAt(page.props.rendered_at));
let timer: number | undefined;

onMounted(() => {
    now.value = Date.now();
    timer = window.setInterval(() => {
        now.value = Date.now();
    }, 1000);
});

onUnmounted(() => {
    if (timer !== undefined) {
        window.clearInterval(timer);
    }
});

const parts = computed(() => countdownParts(props.targetDate, now.value, props.compact));

const valueClass = computed(() => props.dense
    ? 'text-[clamp(0.72rem,2.15vw,1.08rem)] leading-none tabular-nums sm:text-[clamp(0.88rem,1.45vw,1.15rem)] 2xl:text-xl'
    : 'text-[clamp(0.95rem,3.8vw,1.9rem)] leading-none tabular-nums sm:text-3xl 2xl:text-4xl');
const labelGapClass = computed(() => props.dense ? 'gap-x-1 sm:gap-x-1.5' : 'gap-x-2 sm:gap-x-4');

function pad(value: number): string {
    return value.toString().padStart(2, '0');
}
</script>

<template>
    <div class="doomsday-display max-w-full min-w-0">
        <div class="flex min-w-0 flex-nowrap items-end justify-center gap-x-0.5 whitespace-nowrap text-ui-primary sm:gap-x-1">
            <template v-for="(part, index) in parts" :key="part.label">
                <span :class="valueClass">{{ pad(part.value) }}</span>
                <span v-if="index < parts.length - 1" :class="valueClass">:</span>
            </template>
        </div>
        <div :class="['mt-1 flex min-w-0 flex-nowrap justify-center whitespace-nowrap text-[8px] text-ui-primary sm:text-[10px]', labelGapClass]">
            <span v-for="part in parts" :key="part.label">{{ part.label }}</span>
        </div>
    </div>
</template>
