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
    <header class="fixed inset-x-0 top-0 z-50 border-b border-white/10 bg-black/82 backdrop-blur-xl">
        <div class="mx-auto flex max-w-[1760px] items-center justify-between px-4 py-2 sm:px-7">
            <a :href="homeUrl" class="flex items-center gap-3" aria-label="Doomsday Countdown home">
                <img src="/images/doomsday/doomsday_logo_transparent.png" alt="Doomsday Countdown" class="h-9 w-auto sm:h-10" />
            </a>

            <nav class="hidden items-center gap-8 text-sm uppercase tracking-[0.18em] lg:flex">
                <a :href="homeUrl" :class="['border-b-2 px-2 pb-2 pt-1', navClass('home')]">{{ t('home') }}</a>
                <a :href="aboutUrl" :class="['border-b-2 px-2 pb-2 pt-1', navClass('about')]">{{ t('about') }}</a>
            </nav>

            <LanguageSelector :languages="languages" :current-locale="currentLocale" />
        </div>
    </header>
</template>
