<script setup lang="ts">
import { computed } from 'vue';
import { ExternalLink } from 'lucide-vue-next';
import { t } from '@/i18n';

const props = withDefaults(defineProps<{
    readonly placement?: 'header' | 'about';
}>(), {
    placement: 'about',
});

const isHeader = computed(() => props.placement === 'header');

const communityLinks = [
    {
        label: 'Discord',
        href: 'https://discord.gg/NmKXDzwzK',
        iconSrc: '/images/community/discord.png',
    },
    {
        label: 'Telegram',
        href: 'https://t.me/doomsdayclockofficial',
        iconSrc: '/images/community/telegram.png',
    },
] as const;
</script>

<template>
    <div
        :class="isHeader ? 'flex items-center gap-1' : 'grid w-full max-w-xl grid-cols-2 gap-3'"
        aria-label="Discord and Telegram"
    >
        <a
            v-for="link in communityLinks"
            :key="link.label"
            :href="link.href"
            target="_blank"
            rel="noopener noreferrer"
            :aria-label="`${link.label} — ${t('opensInNewTab')}`"
            :title="link.label"
            :class="isHeader
                ? 'group inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/12 bg-white/[0.035] text-white/72 transition hover:border-ui-primary/60 hover:bg-ui-primary/[0.10] hover:text-ui-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black'
                : 'group inline-flex min-h-12 items-center gap-3 rounded-xl border border-white/12 bg-white/[0.035] px-3.5 py-3 text-sm font-semibold text-white/78 transition hover:-translate-y-0.5 hover:border-ui-primary/50 hover:bg-ui-primary/[0.08] hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black sm:px-4'"
        >
            <span :class="isHeader ? 'inline-flex items-center justify-center' : 'inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-white/10 bg-black/35 text-ui-primary transition group-hover:border-ui-primary/30'">
                <img
                    :src="link.iconSrc"
                    alt=""
                    :class="isHeader ? 'h-4 w-4 object-contain' : 'h-5 w-5 object-contain'"
                    width="20"
                    height="20"
                    loading="eager"
                    decoding="async"
                    aria-hidden="true"
                >
            </span>
            <span v-if="!isHeader" class="min-w-0 flex-1 truncate">{{ link.label }}</span>
            <ExternalLink v-if="!isHeader" class="h-3.5 w-3.5 shrink-0 text-white/35 transition group-hover:text-ui-primary" aria-hidden="true" />
        </a>
    </div>
</template>
