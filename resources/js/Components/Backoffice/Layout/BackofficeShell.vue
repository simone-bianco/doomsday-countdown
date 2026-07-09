<script setup lang="ts">
import { PageHeader } from '@simone-bianco/vue-ui-components';
import AppLayout from '@/Layouts/AppLayout.vue';
import BackofficeSidebar from '@/Components/Backoffice/Navigation/BackofficeSidebar.vue';
import type { BackofficeCounts, BackofficeSection } from '@/Components/Backoffice/Layout/types';

defineProps<{
    readonly title: string;
    readonly subtitle: string;
    readonly backofficePath: string;
    readonly counts: BackofficeCounts;
}>();

const activeSection = defineModel<BackofficeSection>('activeSection', { required: true });
</script>

<template>
    <AppLayout
        :title="title"
        :show-title-card="false"
        :show-language-switcher="false"
        app-name-override="Doomsday Countdown"
        :logo-href="backofficePath"
        content-class="w-full px-0 py-0"
        header-inner-class="flex w-full items-center justify-between px-4 py-4 sm:px-6"
    >
        <div class="grid h-[calc(100vh-73px)] min-h-0 gap-0 overflow-hidden lg:grid-cols-[17rem_minmax(0,1fr)]">
            <BackofficeSidebar
                :active-section="activeSection"
                :backoffice-path="backofficePath"
                :counts="counts"
            />

            <section class="min-w-0 space-y-6 overflow-auto px-4 py-6 sm:px-6 lg:px-8 xl:px-10">
                <PageHeader :title="title" :subtitle="subtitle">
                    <template #actions>
                        <slot name="actions" />
                    </template>
                </PageHeader>

                <slot />
            </section>
        </div>
    </AppLayout>
</template>
