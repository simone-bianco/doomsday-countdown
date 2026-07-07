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
                resources: 'Resources',
                analysis: 'Analysis',
                latestUpdate: 'Latest update',
                dailyUpdate: 'Daily update',
                projectionModel: 'Projection model',
                riskIndex: 'Global risk index',
                estimatedTarget: 'Estimated target',
                readMore: 'Read more',
                viewDetails: 'View details',
                viewAllNews: 'View all news',
                keyIndicators: 'Key indicators',
                summary: 'Summary',
                selectedEvent: 'Selected event',
                methodology: 'Learn more about our methodology',
            },
        },
        it: {
            translation: {
                home: 'Home',
                about: 'Metodo',
                overview: 'Sintesi',
                predictions: 'Previsioni',
                statistics: 'Statistiche',
                news: 'Notizie',
                resources: 'Risorse',
                analysis: 'Analisi',
                latestUpdate: 'Ultimo aggiornamento',
                dailyUpdate: 'Aggiornamento giornaliero',
                projectionModel: 'Modello di proiezione',
                riskIndex: 'Indice di rischio globale',
                estimatedTarget: 'Obiettivo stimato',
                readMore: 'Leggi di più',
                viewDetails: 'Vedi dettagli',
                viewAllNews: 'Vedi tutte le notizie',
                keyIndicators: 'Indicatori chiave',
                summary: 'Sintesi',
                selectedEvent: 'Evento selezionato',
                methodology: 'Scopri la metodologia',
            },
        },
        fr: { translation: { home: 'Home', about: 'À propos' } },
        de: { translation: { home: 'Home', about: 'Über' } },
        es: { translation: { home: 'Home', about: 'Acerca de' } },
        nl: { translation: { home: 'Home', about: 'Over' } },
        sv: { translation: { home: 'Home', about: 'Om' } },
        pl: { translation: { home: 'Home', about: 'O nas' } },
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
