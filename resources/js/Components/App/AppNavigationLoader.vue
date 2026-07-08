<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';

const visible = ref(false);
let removeStartListener: (() => void) | null = null;
let removeFinishListener: (() => void) | null = null;

onMounted(() => {
    removeStartListener = router.on('start', () => {
        visible.value = true;
    });
    removeFinishListener = router.on('finish', () => {
        visible.value = false;
    });
});

onUnmounted(() => {
    removeStartListener?.();
    removeFinishListener?.();
});
</script>

<template>
    <Transition name="doomsday-loader-fade">
        <div v-if="visible" class="pointer-events-none fixed inset-x-0 top-0 z-[9999]" role="status" aria-live="polite" aria-label="Loading page">
            <div class="h-[3px] overflow-hidden bg-black/70 shadow-[0_0_24px_rgba(255,42,35,0.35)]">
                <div class="doomsday-app-loader-bar h-full w-1/2 bg-ui-primary" />
            </div>
            <div class="fixed right-4 top-4 rounded-full border border-ui-primary/40 bg-black/80 px-4 py-2 text-[11px] text-ui-primary shadow-[0_0_28px_rgba(255,42,35,0.22)] backdrop-blur-md">
                <span class="doomsday-display">Loading</span>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.doomsday-app-loader-bar {
    animation: doomsday-loader-slide 1s ease-in-out infinite;
    box-shadow: 0 0 22px rgba(255, 42, 35, 0.9);
}

.doomsday-loader-fade-enter-active,
.doomsday-loader-fade-leave-active {
    transition: opacity 160ms ease;
}

.doomsday-loader-fade-enter-from,
.doomsday-loader-fade-leave-to {
    opacity: 0;
}

@keyframes doomsday-loader-slide {
    0% {
        transform: translateX(-100%);
    }

    100% {
        transform: translateX(220%);
    }
}

@media (prefers-reduced-motion: reduce) {
    .doomsday-app-loader-bar {
        animation: none;
        width: 100%;
    }
}
</style>
