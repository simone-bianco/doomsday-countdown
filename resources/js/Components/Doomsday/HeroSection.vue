<script setup lang="ts">
defineProps<{
    readonly hero: Record<string, string>;
}>();
</script>

<template>
    <section class="relative min-h-[430px] overflow-hidden bg-transparent lg:min-h-[460px]">
        <div class="absolute inset-0 bg-gradient-to-r to-transparent" />
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent" />
        <div class="relative mx-auto flex min-h-[430px] max-w-[1760px] items-center px-4 py-12 sm:px-7 lg:min-h-[460px] lg:py-16">
            <div class="max-w-3xl">
                <div class="mb-7 h-px w-24 bg-ui-primary/80" />
                <h1 class="doomsday-display text-3xl leading-tight text-white sm:text-5xl lg:text-6xl">
                    {{ hero.headline_prefix }}<br />
                    <span class="text-white/80">{{ hero.headline_middle }}</span>
                    <span class="doomsday-red-text"> {{ hero.headline_accent }}</span>
                </h1>
                <p class="mt-6 max-w-2xl text-base text-ui-muted-foreground sm:text-xl">{{ hero.subtitle }}</p>
            </div>
        </div>
    </section>
</template>
