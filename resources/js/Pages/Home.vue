<script setup lang="ts">
import DoomsdayHome from './Doomsday/Home.vue';
import type { CountdownPageData } from '@/types/generated';

defineProps<{
    readonly page: CountdownPageData;
}>();
</script>

<template>
    <DoomsdayHome :page="page" />
</template>
