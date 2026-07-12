import i18next from 'i18next';
import { computed, ref } from 'vue';

export const supportedLocales = ['en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl'] as const;
export type SupportedLocale = typeof supportedLocales[number];

type I18nInstance = ReturnType<typeof i18next.createInstance>;
type ServerI18nResolver = () => I18nInstance | undefined;

const resources = {
    en: {
        translation: {
            home: 'Home',
            about: 'About',
            overview: 'Overview',
            predictions: 'Forecasts',
            statistics: 'Statistics',
            news: 'News',
            initiatives: 'Initiatives',
            resources: 'Resources',
            analysis: 'Analysis',
            latestNews: 'Latest news',
            latestNewsEmpty: 'No published news is available for the monitored countdowns.',
            newsCarouselLabel: 'Latest public-source news',
            newsSlide: 'News item',
            previousNews: 'Previous news item',
            nextNews: 'Next news item',
            openSource: 'Open source',
            viewCountdown: 'View countdown',
            publicSignalActivity: 'Public Signal Activity',
            publicSignalActivitySummary: 'Publication volume across the latest 12 UTC weeks.',
            publicSignalActivityChart: 'Weekly publication activity from monitored public sources',
            publishedItems: 'published items',
            uniqueSources: 'Unique sources',
            latestPublication: 'Latest publication',
            topMonitoredCountdown: 'Top monitored countdown',
            noSignalActivity: 'No publications were recorded in this window.',
            publicSignalActivityDisclaimer: 'Published items from monitored public sources. Volume measures source activity, not event probability.',
            projectionModel: 'Projection model',
            estimatedTarget: 'Estimated target',
            readMore: 'Read more',
            viewDetails: 'View details',
            viewAllNews: 'View all news',
            keyIndicators: 'Key indicators',
            summary: 'Summary',
            methodology: 'Learn more about our methodology',
            loadingSection: 'Loading section',
            supportUs: 'Support us',
            supportOnPatreon: 'Support on Patreon',
            supportProjectDescription: 'Help keep the project independent, maintained, and open to everyone.',
            opensInNewTab: 'opens in a new tab',
        },
    },
    it: {
        translation: {
            home: 'Home',
            about: 'About',
            overview: 'Sintesi',
            predictions: 'Previsioni',
            statistics: 'Statistiche',
            news: 'Notizie',
            initiatives: 'Iniziative',
            resources: 'Risorse',
            analysis: 'Analisi',
            latestNews: 'Ultime notizie',
            latestNewsEmpty: 'Non sono disponibili notizie pubblicate per i countdown monitorati.',
            newsCarouselLabel: 'Ultime notizie da fonti pubbliche',
            newsSlide: 'Notizia',
            previousNews: 'Notizia precedente',
            nextNews: 'Notizia successiva',
            openSource: 'Apri la fonte',
            viewCountdown: 'Vedi countdown',
            publicSignalActivity: 'Attività dei segnali pubblici',
            publicSignalActivitySummary: 'Volume delle pubblicazioni nelle ultime 12 settimane UTC.',
            publicSignalActivityChart: 'Attività settimanale delle pubblicazioni da fonti pubbliche monitorate',
            publishedItems: 'elementi pubblicati',
            uniqueSources: 'Fonti uniche',
            latestPublication: 'Ultima pubblicazione',
            topMonitoredCountdown: 'Countdown più monitorato',
            noSignalActivity: 'Nessuna pubblicazione registrata in questa finestra.',
            publicSignalActivityDisclaimer: 'Elementi pubblicati da fonti pubbliche monitorate. Il volume misura l’attività delle fonti, non la probabilità di un evento.',
            projectionModel: 'Modello di proiezione',
            estimatedTarget: 'Obiettivo stimato',
            readMore: 'Leggi di più',
            viewDetails: 'Vedi dettagli',
            viewAllNews: 'Vedi tutte le notizie',
            keyIndicators: 'Indicatori chiave',
            summary: 'Sintesi',
            methodology: 'Scopri la metodologia',
            loadingSection: 'Caricamento sezione',
            supportUs: 'Sostienici',
            supportOnPatreon: 'Sostieni su Patreon',
            supportProjectDescription: 'Aiutaci a mantenere il progetto indipendente, aggiornato e aperto a tutti.',
            opensInNewTab: 'si apre in una nuova scheda',
        },
    },
    fr: { translation: { home: 'Home', about: 'About', supportUs: 'Nous soutenir', supportOnPatreon: 'Soutenir sur Patreon', supportProjectDescription: 'Aidez-nous à garder le projet indépendant, maintenu et accessible à tous.', opensInNewTab: 's’ouvre dans un nouvel onglet' } },
    de: { translation: { home: 'Home', about: 'About', supportUs: 'Unterstützen', supportOnPatreon: 'Auf Patreon unterstützen', supportProjectDescription: 'Hilf uns, das Projekt unabhängig, gepflegt und für alle offen zu halten.', opensInNewTab: 'öffnet in einem neuen Tab' } },
    es: { translation: { home: 'Home', about: 'About', supportUs: 'Apóyanos', supportOnPatreon: 'Apoyar en Patreon', supportProjectDescription: 'Ayúdanos a mantener el proyecto independiente, actualizado y abierto para todos.', opensInNewTab: 'se abre en una pestaña nueva' } },
    nl: { translation: { home: 'Home', about: 'About', supportUs: 'Steun ons', supportOnPatreon: 'Steun via Patreon', supportProjectDescription: 'Help ons het project onafhankelijk, onderhouden en voor iedereen toegankelijk te houden.', opensInNewTab: 'opent in een nieuw tabblad' } },
    sv: { translation: { home: 'Home', about: 'About', supportUs: 'Stöd oss', supportOnPatreon: 'Stöd på Patreon', supportProjectDescription: 'Hjälp oss att hålla projektet oberoende, uppdaterat och öppet för alla.', opensInNewTab: 'öppnas i en ny flik' } },
    pl: { translation: { home: 'Home', about: 'About', supportUs: 'Wesprzyj nas', supportOnPatreon: 'Wesprzyj na Patreon', supportProjectDescription: 'Pomóż nam utrzymać projekt niezależny, aktualny i dostępny dla wszystkich.', opensInNewTab: 'otwiera się w nowej karcie' } },
} as const;

const clientLanguage = ref<SupportedLocale>('en');
let clientInitialization: Promise<I18nInstance> | null = null;
let serverI18nResolver: ServerI18nResolver | undefined;

export const i18n = i18next.createInstance();

export function isSupportedLocale(locale: unknown): locale is SupportedLocale {
    return typeof locale === 'string' && supportedLocales.includes(locale as SupportedLocale);
}

export function assertSupportedLocale(locale: unknown): SupportedLocale {
    if (!isSupportedLocale(locale)) {
        throw new Error(`Unsupported locale received from the page contract: ${String(locale)}`);
    }

    return locale;
}

function initializationOptions(locale: SupportedLocale) {
    return {
        lng: locale,
        fallbackLng: 'en',
        showSupportNotice: false,
        supportedLngs: [...supportedLocales],
        resources,
        interpolation: {
            escapeValue: false,
        },
    };
}

function activeI18n(): I18nInstance {
    return serverI18nResolver?.() ?? i18n;
}

export const currentLanguage = computed({
    get(): SupportedLocale {
        const instance = activeI18n();
        const locale = instance.resolvedLanguage ?? instance.language;

        return isSupportedLocale(locale) ? locale : clientLanguage.value;
    },
    set(locale: SupportedLocale): void {
        clientLanguage.value = assertSupportedLocale(locale);
    },
});

export async function createI18nInstance(locale: unknown): Promise<I18nInstance> {
    const instance = i18next.createInstance();
    await instance.init(initializationOptions(assertSupportedLocale(locale)));

    return instance;
}

export function registerServerI18nResolver(resolver: ServerI18nResolver): void {
    serverI18nResolver = resolver;
}

export async function initializeClientI18n(locale: unknown): Promise<I18nInstance> {
    const resolvedLocale = assertSupportedLocale(locale);

    if (!i18n.isInitialized) {
        clientInitialization ??= i18n.init(initializationOptions(resolvedLocale)).then(() => i18n);
        await clientInitialization;
    } else if (i18n.resolvedLanguage !== resolvedLocale) {
        await i18n.changeLanguage(resolvedLocale);
    }

    clientLanguage.value = resolvedLocale;

    if (typeof document !== 'undefined') {
        document.documentElement.lang = resolvedLocale;
    }

    return i18n;
}

export async function setLanguage(language: string): Promise<void> {
    await initializeClientI18n(language);
}

export function t(key: string): string {
    const instance = activeI18n();

    return instance.isInitialized ? String(instance.t(key)) : key;
}
