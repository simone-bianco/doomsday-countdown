<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Button, Toggle } from '@simone-bianco/vue-ui-components';
import { Cookie, Settings2, ShieldCheck } from 'lucide-vue-next';
import { consentText } from '@/Consent/consentText';
import { acceptAllConsent, initializeConsentRuntime, rejectOptionalConsent, saveConsentDraft, trackVirtualPageView } from '@/Consent/tracking';
import type { ConsentDraft, ConsentPreferences } from '@/Consent/consentTypes';

const props = defineProps<{
    readonly currentLocale: string;
}>();

const visible = ref(false);
const preferencesOpen = ref(false);
const savedConsent = ref<ConsentPreferences | null>(null);
const draft = reactive<ConsentDraft>({
    analytics: false,
    marketing: false,
    functional: false,
});

const text = computed(() => consentText(props.currentLocale));
const privacyUrl = computed(() => `/privacy?lang=${props.currentLocale}`);
const cookieUrl = computed(() => `/cookie-policy?lang=${props.currentLocale}`);

let removeRouterListener: (() => void) | null = null;

function syncDraft(consent: ConsentPreferences | null): void {
    draft.analytics = consent?.analytics ?? false;
    draft.marketing = consent?.marketing ?? false;
    draft.functional = consent?.functional ?? false;
}

function commit(consent: ConsentPreferences): void {
    savedConsent.value = consent;
    syncDraft(consent);
    visible.value = false;
    preferencesOpen.value = false;
}

function acceptAll(): void {
    commit(acceptAllConsent());
}

function rejectAll(): void {
    commit(rejectOptionalConsent());
}

function saveCustom(): void {
    commit(saveConsentDraft({ ...draft }, 'custom'));
}

function openSettings(): void {
    syncDraft(savedConsent.value);
    preferencesOpen.value = true;
    visible.value = true;
}

onMounted(() => {
    const consent = initializeConsentRuntime();
    savedConsent.value = consent;
    syncDraft(consent);
    visible.value = consent === null;

    removeRouterListener = router.on('finish', () => {
        trackVirtualPageView();
    });
});

onBeforeUnmount(() => {
    removeRouterListener?.();
});
</script>

<template>
    <section
        v-if="visible"
        class="fixed inset-x-3 bottom-3 z-[70] mx-auto max-w-2xl rounded-2xl border border-white/12 bg-black/90 p-4 text-white shadow-[0_22px_80px_rgba(0,0,0,0.62)] backdrop-blur-xl sm:bottom-5 sm:right-5 sm:left-auto sm:mx-0 sm:p-5"
        role="dialog"
        aria-live="polite"
        aria-label="Cookie consent"
    >
        <div class="flex items-start gap-3">
            <div class="hidden rounded-xl border border-ui-primary/30 bg-ui-primary/10 p-2 text-ui-primary sm:block">
                <Cookie class="h-5 w-5" aria-hidden="true" />
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-[0.18em] text-ui-primary">{{ text.compactTitle }}</h2>
                        <p class="mt-2 text-sm leading-6 text-white/78">{{ text.compactBody }}</p>
                    </div>
                    <Button v-if="savedConsent" variant="ghost" size="sm" :label="text.close" @click="visible = false" />
                </div>

                <div v-if="preferencesOpen" class="mt-4 grid gap-3 rounded-xl border border-white/10 bg-white/[0.035] p-3">
                    <div class="flex items-start justify-between gap-4 rounded-lg border border-white/8 bg-black/35 p-3">
                        <div>
                            <p class="text-sm font-semibold text-white">{{ text.necessaryTitle }}</p>
                            <p class="mt-1 text-xs leading-5 text-white/60">{{ text.necessaryBody }}</p>
                        </div>
                        <span class="shrink-0 rounded-full border border-white/10 px-2.5 py-1 text-[11px] uppercase tracking-[0.14em] text-white/60">{{ text.alwaysActive }}</span>
                    </div>

                    <Toggle v-model="draft.analytics" :label="text.analyticsTitle" :description="text.analyticsBody" :on-label="text.on" :off-label="text.off" />
                    <Toggle v-model="draft.marketing" :label="text.marketingTitle" :description="text.marketingBody" :on-label="text.on" :off-label="text.off" />
                    <Toggle v-model="draft.functional" :label="text.functionalTitle" :description="text.functionalBody" :on-label="text.on" :off-label="text.off" />
                </div>

                <p class="mt-3 text-xs leading-5 text-white/50">
                    {{ text.policyPrefix }}
                    <a :href="privacyUrl" class="text-ui-primary hover:underline">{{ text.privacyPolicy }}</a>
                    ·
                    <a :href="cookieUrl" class="text-ui-primary hover:underline">{{ text.cookiePolicy }}</a>
                </p>

                <div class="mt-4 flex flex-col-reverse gap-2 sm:flex-row sm:items-center sm:justify-end">
                    <Button variant="ghost" size="sm" :label="text.rejectAll" @click="rejectAll" />
                    <Button v-if="!preferencesOpen" variant="secondary" size="sm" :icon="Settings2" :label="text.customize" @click="preferencesOpen = true" />
                    <Button v-else variant="secondary" size="sm" :label="text.save" @click="saveCustom" />
                    <Button size="sm" :icon="ShieldCheck" :label="text.acceptAll" @click="acceptAll" />
                </div>
            </div>
        </div>
    </section>

    <Button
        v-else-if="savedConsent"
        class="fixed bottom-3 left-3 z-[60]"
        variant="secondary"
        size="sm"
        :icon="ShieldCheck"
        :label="text.settings"
        :ui="{ root: 'rounded-full border-white/10 bg-black/70 text-xs text-white/78 shadow-lg backdrop-blur hover:text-white sm:bottom-4 sm:left-4' }"
        @click="openSettings"
    />
</template>
