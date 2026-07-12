<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    readonly sources: readonly string[];
    readonly reasoning: string | null;
}>();

function sourceLabel(source: string, index: number): string {
    try {
        return new URL(source).hostname.replace(/^www\./, '');
    } catch {
        return `Source ${index + 1}`;
    }
}

const validSources = computed(() => props.sources.filter((source) => {
    try {
        return new URL(source).protocol === 'https:';
    } catch {
        return false;
    }
}));
const reasoningText = computed(() => props.reasoning?.trim() ?? '');
</script>

<template>
    <div v-if="reasoningText || validSources.length" class="min-w-0 space-y-3 border-t border-white/10 pt-3 text-xs leading-relaxed text-white/55">
        <p v-if="reasoningText" class="min-w-0 break-words">
            <span class="font-semibold text-white/75">Reasoning:</span>
            {{ reasoningText }}
        </p>
        <div v-if="validSources.length" class="min-w-0">
            <span class="font-semibold text-white/75">Sources:</span>
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
