<script setup lang="ts">
import { ToastNotification } from '@simone-bianco/vue-ui-components';
import CookieConsentBanner from '@/Components/Consent/CookieConsentBanner.vue';
import ResponsiveImage from '@/Components/Doomsday/ResponsiveImage.vue';
import SeoHead from '@/Components/Doomsday/SeoHead.vue';
import SiteHeader from '@/Components/Doomsday/SiteHeader.vue';
import type { LanguageOptionData } from '@/types/generated';

withDefaults(defineProps<{
    readonly languages: readonly LanguageOptionData[];
    readonly currentLocale: string;
    readonly hideMobileHeader?: boolean;
    readonly hideBackground?: boolean;
    readonly activePage?: 'home' | 'about';
}>(), {
    hideMobileHeader: false,
    hideBackground: false,
    activePage: 'home',
});
</script>

<template>
    <SeoHead />
    <div class="doomsday-scrollbar relative min-h-screen overflow-x-hidden bg-black text-ui-foreground">
        <div v-if="!hideBackground" class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
            <ResponsiveImage
                v-if="!hideMobileHeader"
                src="/images/doomsday/doomsday_hero_background_desktop.png"
                mobile-src="/images/doomsday/doomsday_hero_background_mobile.png"
                alt=""
                sizes="100vw"
                mobile-sizes="100vw"
                :breakpoint="768"
                loading="eager"
                :fetch-priority="activePage === 'home' ? 'high' : 'auto'"
                :aria-hidden="true"
                picture-class="block h-full w-full"
                img-class="h-full w-full object-cover object-center opacity-95"
            />
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-black/15" />
            <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-black/35 to-black/90" />
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_68%_28%,rgba(255,42,35,0.18),transparent_34%)]" />
        </div>

        <div class="relative z-10 min-h-screen">
            <ToastNotification />
            <SiteHeader :class="hideMobileHeader ? 'hidden lg:block' : ''" :languages="languages" :current-locale="currentLocale" :active-page="activePage" />
            <main :class="hideMobileHeader ? 'lg:pt-[64px]' : 'pt-[64px]'">
                <slot />
            </main>
            <CookieConsentBanner :current-locale="currentLocale" />
        </div>
    </div>
</template>
