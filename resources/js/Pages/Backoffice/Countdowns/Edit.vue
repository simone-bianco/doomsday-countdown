<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import BackofficeShell from '@/Components/Backoffice/Layout/BackofficeShell.vue';
import CountdownRelationsManager from '@/Components/Backoffice/Doomsday/CountdownRelationsManager.vue';
import { useBackofficePath } from '@/Components/Backoffice/Doomsday/useBackofficePath';
import type { BackofficeOptions, CountdownDetail } from '@/Components/Backoffice/Doomsday/types';
import type { BackofficeSection } from '@/Components/Backoffice/Layout/types';

const props = defineProps<{
    readonly countdown: CountdownDetail;
    readonly options: BackofficeOptions;
}>();

const { backofficePath, normalizedBackofficePath, counts } = useBackofficePath();
const activeSection = ref<BackofficeSection>('countdowns');
</script>

<template>
    <Head :title="`Edit ${countdown.slug}`" />
    <BackofficeShell v-model:active-section="activeSection" :title="`Edit ${countdown.title.en ?? countdown.slug}`" subtitle="Manage the countdown record and all projections, visualizations, news and initiatives." :backoffice-path="backofficePath" :counts="counts">
        <CountdownRelationsManager
            :base-path="normalizedBackofficePath"
            :countdown="countdown"
            :options="options"
            :submit-url="`${normalizedBackofficePath}/countdowns/${props.countdown.id}`"
            method="put"
            submit-label="Save countdown"
        />
    </BackofficeShell>
</template>
