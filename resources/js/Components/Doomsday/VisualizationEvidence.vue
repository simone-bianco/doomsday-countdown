<script setup lang="ts">
import { computed } from 'vue';
import { t } from '@/i18n';

const props = defineProps<{
    readonly sources: readonly string[];
    readonly explanation: string | null;
    readonly reasoning: string | null;
}>();

function sourceLabel(source: string, index: number): string {
    try {
        return new URL(source).hostname.replace(/^www\./, '');
    } catch {
        return `${t('sourceLabel')} ${index + 1}`;
    }
}

const validSources = computed(() => props.sources.filter((source) => {
    try {
        return new URL(source).protocol === 'https:';
    } catch {
        return false;
    }
}));
const explanationText = computed(() => props.explanation?.trim() ?? '');
const reasoningText = computed(() => props.reasoning?.trim() ?? '');
</script>

<template>
    <div v-if="explanationText || reasoningText || validSources.length" class="min-w-0 space-y-3 border-t border-white/10 pt-3 text-xs leading-relaxed text-white/55">
        <p v-if="explanationText" class="min-w-0 break-words text-sm text-white/70">
            <span class="font-semibold text-white/85">{{ t('chartExplanation') }}</span>
            {{ explanationText }}
        </p>
        <p v-if="reasoningText" class="min-w-0 break-words">
            <span class="font-semibold text-white/75">{{ t('reasoningLabel') }}</span>
            {{ reasoningText }}
        </p>
        <div v-if="validSources.length" class="min-w-0">
            <span class="font-semibold text-white/75">{{ t('sourcesLabel') }}</span>
            <a
                v-for="(source, index) in validSources"
                :key="source"
                :href="source"
                target="_blank"
                rel="noopener noreferrer"
                class="ml-2 inline-block max-w-full break-words align-top text-ui-primary underline-offset-2 hover:underline"
                :title="source"
            >{{ sourceLabel(source, index) }}</a>
        </div>
    </div>
</template>
