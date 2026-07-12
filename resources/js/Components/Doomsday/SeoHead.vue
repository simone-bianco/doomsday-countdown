<script setup lang="ts">
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import type { DoomsdayPageProps } from '@/types/page-props';

const page = usePage<DoomsdayPageProps>();
const seo = computed(() => page.props.seo);
const structuredData = computed(() => seo.value.structured_data.map((block) => JSON.stringify(block).replace(/</g, '\\u003C')));
</script>

<template>
    <Head :title="seo.title">
        <meta head-key="description" name="description" :content="seo.description" />
        <meta head-key="robots" name="robots" :content="seo.robots" />
        <link head-key="canonical" rel="canonical" :href="seo.canonical_url" />
        <link v-for="alternate in seo.alternates" :key="alternate.hreflang" :head-key="`alternate-${alternate.hreflang}`" rel="alternate" :hreflang="alternate.hreflang" :href="alternate.url" />
        <link head-key="alternate-x-default" rel="alternate" hreflang="x-default" :href="seo.x_default_url" />

        <meta head-key="og-site-name" property="og:site_name" :content="seo.open_graph.site_name" />
        <meta head-key="og-type" property="og:type" :content="seo.open_graph.type" />
        <meta head-key="og-title" property="og:title" :content="seo.open_graph.title" />
        <meta head-key="og-description" property="og:description" :content="seo.open_graph.description" />
        <meta head-key="og-url" property="og:url" :content="seo.open_graph.url" />
        <meta head-key="og-locale" property="og:locale" :content="seo.open_graph.locale" />
        <meta v-for="locale in seo.open_graph.alternate_locales" :key="locale" :head-key="`og-locale-${locale}`" property="og:locale:alternate" :content="locale" />
        <meta head-key="og-image" property="og:image" :content="seo.open_graph.image.url" />
        <meta head-key="og-image-alt" property="og:image:alt" :content="seo.open_graph.image.alt" />
        <meta v-if="seo.open_graph.image.width" head-key="og-image-width" property="og:image:width" :content="String(seo.open_graph.image.width)" />
        <meta v-if="seo.open_graph.image.height" head-key="og-image-height" property="og:image:height" :content="String(seo.open_graph.image.height)" />

        <meta head-key="twitter-card" name="twitter:card" :content="seo.twitter.card" />
        <meta head-key="twitter-title" name="twitter:title" :content="seo.twitter.title" />
        <meta head-key="twitter-description" name="twitter:description" :content="seo.twitter.description" />
        <meta head-key="twitter-image" name="twitter:image" :content="seo.twitter.image_url" />
        <meta head-key="twitter-image-alt" name="twitter:image:alt" :content="seo.twitter.image_alt" />
        <meta v-if="seo.date_modified" head-key="date-modified" property="article:modified_time" :content="seo.date_modified" />

        <component :is="'script'" v-for="(block, index) in structuredData" :key="index" :head-key="`json-ld-${index}`" type="application/ld+json" v-html="block" />
    </Head>
</template>
