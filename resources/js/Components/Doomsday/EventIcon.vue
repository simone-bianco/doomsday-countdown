<script setup lang="ts">
import { Globe2, Thermometer, Users, Waves } from 'lucide-vue-next';
import type { Component } from 'vue';

const props = defineProps<{
    readonly name: string;
}>();

const icons: Record<string, Component> = {
    users: Users,
    europe: Globe2,
    thermometer: Thermometer,
    waves: Waves,
};
</script>

<template>
    <div class="flex h-16 w-16 items-center justify-center rounded-full border border-ui-primary bg-black/80 text-ui-primary shadow-[0_0_24px_rgba(255,42,35,0.25)]">
        <component :is="icons[props.name] ?? Globe2" class="h-8 w-8" />
    </div>
</template>
