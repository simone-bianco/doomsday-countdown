import { computed, ref, watch, type ComputedRef, type Ref } from 'vue';
import axios from 'axios';
import type { CountdownIndexData, CountdownOverviewData } from '@/types/generated';

declare const route: (name: string, params?: Record<string, string>) => string;

export function useDoomsdaySelection(
    initialSelectedCountdown: ComputedRef<CountdownOverviewData | null>,
    currentLocale: ComputedRef<string>,
) {
    const selectedCountdown = ref<CountdownOverviewData | null>(initialSelectedCountdown.value) as Ref<CountdownOverviewData | null>;
    const pendingSelectedSlug = ref<string | null>(null);
    const selectionLoading = ref(false);
    const selectionError = ref(false);
    const overviewCache = new Map<string, CountdownOverviewData>();

    if (initialSelectedCountdown.value !== null) {
        overviewCache.set(cacheKey(initialSelectedCountdown.value.slug, currentLocale.value), initialSelectedCountdown.value);
    }

    const activeSelectedSlug = computed(() => pendingSelectedSlug.value ?? selectedCountdown.value?.slug ?? null);
    const isReplacingSelection = computed(() => pendingSelectedSlug.value !== null && selectionLoading.value);
    const detailOpen = computed(() => selectedCountdown.value !== null || pendingSelectedSlug.value !== null || selectionLoading.value);

    watch(initialSelectedCountdown, (countdown) => {
        selectedCountdown.value = countdown;
        if (countdown !== null) {
            overviewCache.set(cacheKey(countdown.slug, currentLocale.value), countdown);
        }
    });

    async function selectCountdown(countdown: CountdownIndexData): Promise<void> {
        if (activeSelectedSlug.value === countdown.slug && !selectionLoading.value) {
            closeSelectedCountdown();
            return;
        }

        const requestedSlug = countdown.slug;
        const requestedLocale = currentLocale.value;
        const key = cacheKey(requestedSlug, requestedLocale);
        const cached = overviewCache.get(key);

        selectionError.value = false;
        pendingSelectedSlug.value = requestedSlug;

        if (cached !== undefined) {
            selectedCountdown.value = cached;
            pendingSelectedSlug.value = null;
            selectionLoading.value = false;
            return;
        }

        selectionLoading.value = true;

        try {
            const response = await axios.get<{ data: CountdownOverviewData }>(route('countdowns.data.overview', {
                slug: requestedSlug,
                lang: requestedLocale,
            }));

            if (pendingSelectedSlug.value === requestedSlug && currentLocale.value === requestedLocale) {
                overviewCache.set(key, response.data.data);
                selectedCountdown.value = response.data.data;
                pendingSelectedSlug.value = null;
            }
        } catch {
            if (pendingSelectedSlug.value === requestedSlug) {
                selectionError.value = true;
                pendingSelectedSlug.value = null;
            }
        } finally {
            if (pendingSelectedSlug.value === requestedSlug || pendingSelectedSlug.value === null) {
                selectionLoading.value = false;
            }
        }
    }

    function closeSelectedCountdown(): void {
        selectedCountdown.value = null;
        pendingSelectedSlug.value = null;
        selectionLoading.value = false;
        selectionError.value = false;
    }

    function cacheKey(slug: string, locale: string): string {
        return `${slug}:${locale}`;
    }

    return {
        selectedCountdown,
        activeSelectedSlug,
        pendingSelectedSlug,
        detailOpen,
        isReplacingSelection,
        selectionLoading,
        selectionError,
        selectCountdown,
        closeSelectedCountdown,
    };
}
