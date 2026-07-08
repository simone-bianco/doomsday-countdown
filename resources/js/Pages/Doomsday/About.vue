<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AboutClosingBand from '@/Components/Doomsday/AboutClosingBand.vue';
import AboutFAQSection from '@/Components/Doomsday/AboutFAQSection.vue';
import AboutGreatFilterSection from '@/Components/Doomsday/AboutGreatFilterSection.vue';
import AboutHero from '@/Components/Doomsday/AboutHero.vue';
import AboutSignalGrid from '@/Components/Doomsday/AboutSignalGrid.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import type { AboutPageData } from '@/types/generated';

defineProps<{
    readonly page: AboutPageData;
}>();
</script>

<template>
    <Head :title="page.title" />
    <PublicLayout :languages="page.languages" :current-locale="page.current_locale" active-page="about">
        <div class="mx-auto grid max-w-[1760px] gap-7 px-4 py-7 sm:px-7 lg:gap-10 lg:py-12">
            <AboutHero :page="page" />
            <AboutSignalGrid :stats="page.stats" :sections="page.sections" />
            <AboutGreatFilterSection :eyebrow="page.eyebrow" :pipeline-label="page.pipeline_label" :timeline="page.timeline" />
            <AboutFAQSection :title="page.faq_title" :subtitle="page.faq_subtitle" :items="page.faq" />
            <AboutClosingBand :label="page.closing_label" :title="page.closing_title" :body="page.closing_body" />
        </div>
    </PublicLayout>
</template>
