import { computed, reactive, shallowRef, type ComputedRef, type Ref } from 'vue';
import axios from 'axios';
import type { CountdownForecastsData, CountdownInitiativesSectionData, CountdownNewsSectionData, CountdownStatisticsData } from '@/types/generated';

export type LazySectionKey = 'forecasts' | 'statistics' | 'news' | 'initiatives';

type SectionDataByKey = {
    forecasts: CountdownForecastsData;
    statistics: CountdownStatisticsData;
    news: CountdownNewsSectionData;
    initiatives: CountdownInitiativesSectionData;
};

type InitialSections = {
    [K in LazySectionKey]: ComputedRef<SectionDataByKey[K] | null>;
};

const sectionRouteByKey: Record<LazySectionKey, string> = {
    forecasts: 'countdowns.data.forecasts',
    statistics: 'countdowns.data.statistics',
    news: 'countdowns.data.news',
    initiatives: 'countdowns.data.initiatives',
};

const lazyKeys: readonly LazySectionKey[] = ['forecasts', 'statistics', 'news', 'initiatives'];

declare const route: (name: string, params?: Record<string, string>) => string;

export function isLazySectionKey(value: string): value is LazySectionKey {
    return lazyKeys.includes(value as LazySectionKey);
}

export function useDoomsdayLazySections(
    countdownSlug: ComputedRef<string>,
    currentLocale: ComputedRef<string>,
    initialSections: InitialSections,
) {
    const loaded = {
        forecasts: shallowRef<CountdownForecastsData | null>(null),
        statistics: shallowRef<CountdownStatisticsData | null>(null),
        news: shallowRef<CountdownNewsSectionData | null>(null),
        initiatives: shallowRef<CountdownInitiativesSectionData | null>(null),
    } satisfies { [K in LazySectionKey]: Ref<SectionDataByKey[K] | null> };
    const loading = reactive<Record<LazySectionKey, boolean>>({
        forecasts: false,
        statistics: false,
        news: false,
        initiatives: false,
    });
    const errors = reactive<Record<LazySectionKey, boolean>>({
        forecasts: false,
        statistics: false,
        news: false,
        initiatives: false,
    });
    const cache = new Map<string, SectionDataByKey[LazySectionKey]>();

    const forecastSection = computed(() => currentSection('forecasts'));
    const statisticsSection = computed(() => currentSection('statistics'));
    const newsSection = computed(() => currentSection('news'));
    const initiativesSection = computed(() => currentSection('initiatives'));

    async function loadSection<K extends LazySectionKey>(key: K): Promise<void> {
        if (currentSection(key) !== null || loading[key]) {
            return;
        }

        const requestedSlug = countdownSlug.value;
        const requestedLocale = currentLocale.value;
        const keyName = cacheKey(requestedSlug, requestedLocale, key);
        const cached = cache.get(keyName) as SectionDataByKey[K] | undefined;

        errors[key] = false;

        if (cached !== undefined) {
            loaded[key].value = cached;
            return;
        }

        loading[key] = true;

        try {
            const response = await axios.get<{ data: SectionDataByKey[K] }>(route(sectionRouteByKey[key], {
                slug: requestedSlug,
                lang: requestedLocale,
            }));

            if (countdownSlug.value === requestedSlug && currentLocale.value === requestedLocale) {
                cache.set(keyName, response.data.data);
                loaded[key].value = response.data.data;
            }
        } catch {
            if (countdownSlug.value === requestedSlug) {
                errors[key] = true;
            }
        } finally {
            if (countdownSlug.value === requestedSlug) {
                loading[key] = false;
            }
        }
    }

    function reset(): void {
        lazyKeys.forEach((key) => {
            loading[key] = false;
            errors[key] = false;
        });
    }

    function currentSection<K extends LazySectionKey>(key: K): SectionDataByKey[K] | null {
        const localSection = loaded[key].value;
        if (localSection?.countdown_slug === countdownSlug.value) {
            return localSection;
        }

        const initialSection = initialSections[key].value;
        return initialSection?.countdown_slug === countdownSlug.value ? initialSection : null;
    }

    function cacheKey(slug: string, locale: string, key: LazySectionKey): string {
        return `${slug}:${locale}:${key}`;
    }

    return {
        forecastSection,
        statisticsSection,
        newsSection,
        initiativesSection,
        loadSection,
        reset,
        isLoading: (key: LazySectionKey): boolean => loading[key],
        hasError: (key: LazySectionKey): boolean => errors[key],
    };
}
