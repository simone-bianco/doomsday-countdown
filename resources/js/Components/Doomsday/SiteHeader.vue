<script setup lang="ts">
import { computed } from 'vue';
import LanguageSelector from './LanguageSelector.vue';
import { t } from '@/i18n';
import type { LanguageOptionData } from '@/types/generated';

const props = withDefaults(defineProps<{
    readonly languages: readonly LanguageOptionData[];
    readonly currentLocale: string;
    readonly activePage?: 'home' | 'about';
}>(), {
    activePage: 'home',
});

const homeUrl = computed(() => `/?lang=${props.currentLocale}`);
const aboutUrl = computed(() => `/about?lang=${props.currentLocale}`);

function navClass(page: 'home' | 'about'): string {
    return props.activePage === page
        ? 'border-ui-primary text-ui-primary'
        : 'border-transparent text-white/80 hover:text-white';
}
</script>

<template>
    <header class="sticky top-0 z-40 border-b border-white/10 bg-black/75 backdrop-blur-xl">
        <div class="mx-auto flex max-w-[1760px] items-center justify-between px-4 py-3 sm:px-7">
            <a :href="homeUrl" class="flex items-center gap-3" aria-label="Doomsday Countdown home">
                <img src="/images/doomsday/doomsday_logo_transparent.png" alt="Doomsday Countdown" class="h-9 w-auto sm:h-10" />
            </a>

            <nav class="hidden items-center gap-10 text-sm uppercase tracking-[0.18em] lg:flex">
                <a :href="homeUrl" :class="['border-b-2 px-2 py-5', navClass('home')]">{{ t('home') }}</a>
                <a :href="aboutUrl" :class="['border-b-2 px-2 py-5', navClass('about')]">{{ t('about') }}</a>
            </nav>

            <LanguageSelector :languages="languages" :current-locale="currentLocale" />
        </div>
    </header>
</template>
