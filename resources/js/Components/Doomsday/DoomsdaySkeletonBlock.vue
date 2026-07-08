<script setup lang="ts">
withDefaults(defineProps<{
    readonly variant?: 'summary' | 'chart' | 'list' | 'initiatives';
}>(), {
    variant: 'summary',
});
</script>

<template>
    <div class="doomsday-card rounded-xl p-5 sm:p-6" role="status" aria-label="Loading section">
        <div class="mb-5 flex items-center gap-3">
            <span class="h-3 w-3 rounded-full bg-ui-primary shadow-[0_0_18px_rgba(255,42,35,0.8)]" />
            <span class="doomsday-display text-xs text-ui-primary">Loading</span>
        </div>

        <div v-if="variant === 'chart'" class="space-y-4">
            <div class="doomsday-skeleton h-5 w-44 rounded" />
            <div class="grid h-44 grid-cols-6 items-end gap-3 rounded-lg border border-white/10 bg-black/30 p-4">
                <div v-for="index in 6" :key="index" class="doomsday-skeleton rounded-t" :style="{ height: `${24 + index * 10}%` }" />
            </div>
            <div class="doomsday-skeleton h-4 w-56 rounded" />
        </div>

        <div v-else-if="variant === 'list'" class="space-y-4">
            <div v-for="index in 3" :key="index" class="rounded-lg border border-white/10 bg-white/[0.03] p-4">
                <div class="doomsday-skeleton h-4 w-3/4 rounded" />
                <div class="doomsday-skeleton mt-3 h-3 w-full rounded" />
                <div class="doomsday-skeleton mt-2 h-3 w-2/3 rounded" />
            </div>
        </div>

        <div v-else-if="variant === 'initiatives'" class="grid grid-cols-1 gap-4">
            <div v-for="index in 2" :key="index" class="rounded-lg border border-ui-primary/20 bg-ui-primary/5 p-4">
                <div class="doomsday-skeleton h-4 w-28 rounded" />
                <div class="doomsday-skeleton mt-4 h-5 w-3/4 rounded" />
                <div class="doomsday-skeleton mt-3 h-3 w-full rounded" />
                <div class="doomsday-skeleton mt-2 h-3 w-4/5 rounded" />
            </div>
        </div>

        <div v-else class="space-y-4">
            <div class="doomsday-skeleton h-5 w-40 rounded" />
            <div class="doomsday-skeleton h-3 w-full rounded" />
            <div class="doomsday-skeleton h-3 w-11/12 rounded" />
            <div class="mt-5 grid grid-cols-3 gap-3">
                <div v-for="index in 3" :key="index" class="doomsday-skeleton h-20 rounded-lg" />
            </div>
        </div>
    </div>
</template>
