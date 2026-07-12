<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Button, Dropdown } from '@simone-bianco/vue-ui-components';
import { Globe2 } from 'lucide-vue-next';
import { setLanguage } from '@/i18n';
import type { LanguageOptionData } from '@/types/generated';

const props = defineProps<{
    readonly languages: readonly LanguageOptionData[];
    readonly currentLocale: string;
}>();

const currentLanguage = computed(() => props.languages.find((language) => language.code === props.currentLocale) ?? props.languages[0]);

onMounted(() => {
    void setLanguage(props.currentLocale);
});
</script>

<template>
    <Dropdown align="right" width="56" content-classes="bg-black/95 p-1 text-sm text-ui-foreground ring-1 ring-ui-border">
        <template #trigger>
            <Button variant="secondary" size="sm" :icon="Globe2" :ui="{ root: 'border-ui-border bg-black/50 doomsday-display text-ui-primary' }">
                <span class="mr-2 inline-flex h-4 w-6 items-center justify-center overflow-hidden rounded-sm bg-white/10 text-sm leading-none">{{ currentLanguage?.flag }}</span>
                {{ currentLocale.toUpperCase() }}
            </Button>
        </template>
        <template #content>
            <Link
                v-for="language in languages"
                :key="language.code"
                :href="language.url"
                class="flex items-center justify-between rounded px-3 py-2 transition hover:bg-white/10"
                :class="language.is_current ? 'bg-white/10 text-white' : 'text-ui-muted-foreground'"
            >
                <span class="flex items-center gap-3">
                    <span class="inline-flex h-5 w-7 items-center justify-center overflow-hidden rounded-sm bg-white/10 text-base leading-none">{{ language.flag }}</span>
                    {{ language.native_label }}
                </span>
                <span v-if="language.is_current" class="text-ui-primary">✓</span>
            </Link>
        </template>
    </Dropdown>
</template>
