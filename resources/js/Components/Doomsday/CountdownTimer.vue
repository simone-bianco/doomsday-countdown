<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = withDefaults(defineProps<{
    readonly targetDate: string | null;
    readonly compact?: boolean;
    readonly dense?: boolean;
}>(), {
    compact: false,
    dense: false,
});

const now = ref(Date.now());
let timer: number | undefined;

onMounted(() => {
    timer = window.setInterval(() => {
        now.value = Date.now();
    }, 1000);
});

onUnmounted(() => {
    if (timer !== undefined) {
        window.clearInterval(timer);
    }
});

const parts = computed(() => {
    const target = props.targetDate === null ? now.value : new Date(props.targetDate).getTime();
    const totalSeconds = Math.max(0, Math.floor((target - now.value) / 1000));
    const years = Math.floor(totalSeconds / 31_536_000);
    const days = Math.floor((totalSeconds % 31_536_000) / 86_400);
    const hours = Math.floor((totalSeconds % 86_400) / 3_600);
    const minutes = Math.floor((totalSeconds % 3_600) / 60);
    const seconds = totalSeconds % 60;

    return props.compact
        ? [
            { label: 'YRS', value: years },
            { label: 'DAYS', value: days },
            { label: 'HRS', value: hours },
            { label: 'MIN', value: minutes },
            { label: 'SEC', value: seconds },
        ]
        : [
            { label: 'YEARS', value: years },
            { label: 'DAYS', value: days },
            { label: 'HOURS', value: hours },
            { label: 'MIN', value: minutes },
            { label: 'SEC', value: seconds },
        ];
});

const valueClass = computed(() => props.dense
    ? 'text-[1.05rem] leading-none sm:text-lg 2xl:text-xl'
    : 'text-xl leading-none sm:text-3xl 2xl:text-4xl');
const labelGapClass = computed(() => props.dense ? 'gap-x-2' : 'gap-x-4 sm:gap-x-5');

function pad(value: number): string {
    return value.toString().padStart(2, '0');
}
</script>

<template>
    <div class="doomsday-display max-w-full overflow-hidden">
        <div class="flex flex-nowrap items-end gap-x-1 whitespace-nowrap text-ui-primary sm:gap-x-2">
            <template v-for="(part, index) in parts" :key="part.label">
                <span :class="valueClass">{{ pad(part.value) }}</span>
                <span v-if="index < parts.length - 1" :class="valueClass">:</span>
            </template>
        </div>
        <div :class="['mt-1 flex flex-nowrap whitespace-nowrap text-[9px] text-ui-primary sm:text-[10px]', labelGapClass]">
            <span v-for="part in parts" :key="part.label">{{ part.label }}</span>
        </div>
    </div>
</template>
