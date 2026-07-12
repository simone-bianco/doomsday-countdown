import i18next from 'i18next';
import LanguageDetector from 'i18next-browser-languagedetector';
import { ref } from 'vue';

export const currentLanguage = ref('en');

export const i18n = i18next.createInstance();

void i18n.use(LanguageDetector).init({
    fallbackLng: 'en',
    supportedLngs: ['en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl'],
    resources: {
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
            },
        },
        fr: { translation: { home: 'Home', about: 'About' } },
        de: { translation: { home: 'Home', about: 'About' } },
        es: { translation: { home: 'Home', about: 'About' } },
        nl: { translation: { home: 'Home', about: 'About' } },
        sv: { translation: { home: 'Home', about: 'About' } },
        pl: { translation: { home: 'Home', about: 'About' } },
    },
});

currentLanguage.value = i18n.language || 'en';

export async function setLanguage(language: string): Promise<void> {
    await i18n.changeLanguage(language);
    currentLanguage.value = language;
    document.documentElement.lang = language;
}

export function t(key: string): string {
    return i18n.t(key);
}
