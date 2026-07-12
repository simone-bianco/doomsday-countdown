<script setup lang="ts">
import { computed } from 'vue';
import { ExternalLink, HeartHandshake } from 'lucide-vue-next';
import { t } from '@/i18n';

const PATREON_URL = 'https://www.patreon.com/cw/doomsdayclock';

const props = withDefaults(defineProps<{
    readonly placement?: 'header' | 'about';
}>(), {
    placement: 'header',
});

const isHeader = computed(() => props.placement === 'header');

const rootClass = computed(() => isHeader.value
    ? 'group inline-flex h-9 min-w-9 items-center justify-center gap-2 rounded-full border border-ui-primary/35 bg-ui-primary/[0.08] px-2.5 text-xs font-semibold text-white/88 shadow-[0_0_18px_rgba(255,42,35,0.08)] transition hover:border-ui-primary/70 hover:bg-ui-primary/[0.14] hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black sm:px-3'
    : 'group inline-flex w-full max-w-xl items-center gap-4 rounded-2xl border border-ui-primary/35 bg-black/45 px-4 py-3 text-left shadow-[0_0_28px_rgba(255,42,35,0.10)] transition hover:-translate-y-0.5 hover:border-ui-primary/70 hover:bg-ui-primary/[0.10] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black sm:px-5 sm:py-4');
</script>

<template>
    <a
        :href="PATREON_URL"
        target="_blank"
        rel="noopener noreferrer"
        :aria-label="`${t('supportOnPatreon')} — ${t('opensInNewTab')}`"
        :class="rootClass"
    >
        <span :class="isHeader ? 'inline-flex h-5 w-5 items-center justify-center' : 'inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-ui-primary/30 bg-ui-primary/[0.12] text-ui-primary'">
            <HeartHandshake :class="isHeader ? 'h-4 w-4 text-ui-primary' : 'h-5 w-5'" aria-hidden="true" />
        </span>

        <span v-if="isHeader" class="hidden sm:inline">{{ t('supportUs') }}</span>
        <span v-else class="min-w-0 flex-1">
            <span class="doomsday-display block text-xs text-ui-primary">{{ t('supportOnPatreon') }}</span>
            <span class="mt-1 block text-sm leading-5 text-white/70">{{ t('supportProjectDescription') }}</span>
        </span>

        <span v-if="isHeader" class="hidden border-l border-white/15 pl-2 text-[10px] uppercase tracking-[0.16em] text-white/55 xl:inline">Patreon</span>
        <ExternalLink :class="isHeader ? 'hidden h-3.5 w-3.5 text-white/45 transition group-hover:text-ui-primary sm:block' : 'h-4 w-4 shrink-0 text-white/45 transition group-hover:text-ui-primary'" aria-hidden="true" />
    </a>
</template>
