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
            readLess: 'Read less',
            expandChart: 'Open chart full screen',
            closeChart: 'Close full-screen chart',
            chartZoomHint: 'Tap to enlarge',
            chartZoomGestureHint: 'Pinch or double-tap to zoom',
            zoomInChart: 'Zoom in',
            zoomOutChart: 'Zoom out',
            resetChartZoom: 'Reset chart zoom',
            viewDetails: 'View details',
            viewAllNews: 'View all news',
            keyIndicators: 'Key indicators',
            summary: 'Summary',
            confidence: 'Confidence',
            trend: 'Trend',
            risk: 'Risk',
            chartExplanation: 'What this chart shows:',
            reasoningLabel: 'Method and limits:',
            sourcesLabel: 'Sources:',
            sourceLabel: 'Source',
            seriesLabel: 'Series',
            visualizationUnavailable: 'Visualization unavailable: the chart data is incomplete or invalid.',
            forecastsUnavailable: 'Forecasts unavailable',
            statisticsUnavailable: 'Statistics unavailable',
            newsUnavailable: 'News unavailable',
            initiativesUnavailable: 'Initiatives unavailable',
            sectionLoadFailed: 'This section could not be loaded. Please try again.',
            retry: 'Retry',
            trendValues: { improving: 'improving', stable: 'stable', deteriorating: 'deteriorating', worsening: 'worsening', unknown: 'unknown' },
            severityValues: { low: 'low', moderate: 'moderate', elevated: 'elevated', high: 'high', severe: 'severe', critical: 'critical', existential: 'existential' },
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
            readLess: 'Mostra meno',
            expandChart: 'Apri il grafico a schermo intero',
            closeChart: 'Chiudi il grafico a schermo intero',
            chartZoomHint: 'Tocca per ingrandire',
            chartZoomGestureHint: 'Pizzica o fai doppio tap per zoomare',
            zoomInChart: 'Aumenta zoom',
            zoomOutChart: 'Riduci zoom',
            resetChartZoom: 'Reimposta zoom grafico',
            viewDetails: 'Vedi dettagli',
            viewAllNews: 'Vedi tutte le notizie',
            keyIndicators: 'Indicatori chiave',
            summary: 'Sintesi',
            confidence: 'Affidabilità',
            trend: 'Tendenza',
            risk: 'Rischio',
            chartExplanation: 'Come leggere il grafico:',
            reasoningLabel: 'Metodo e limiti:',
            sourcesLabel: 'Fonti:',
            sourceLabel: 'Fonte',
            seriesLabel: 'Serie',
            visualizationUnavailable: 'Grafico non disponibile: i dati sono incompleti o non validi.',
            forecastsUnavailable: 'Previsioni non disponibili',
            statisticsUnavailable: 'Statistiche non disponibili',
            newsUnavailable: 'Notizie non disponibili',
            initiativesUnavailable: 'Iniziative non disponibili',
            sectionLoadFailed: 'Non è stato possibile caricare questa sezione. Riprova.',
            retry: 'Riprova',
            trendValues: { improving: 'in miglioramento', stable: 'stabile', deteriorating: 'in peggioramento', worsening: 'in peggioramento', unknown: 'non disponibile' },
            severityValues: { low: 'basso', moderate: 'moderato', elevated: 'elevato', high: 'alto', severe: 'grave', critical: 'critico', existential: 'esistenziale' },
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

const doomsdayLabelTranslations: Partial<Record<SupportedLocale, Record<string, string>>> = {
    it: {
        'Pessimistic': 'Pessimistico',
        'Neutral': 'Intermedio',
        'Optimistic': 'Ottimistico',
        'Optimistic — 2036': 'Ottimistico — 2036',
        'Neutral — 2032': 'Intermedio — 2032',
        'Pessimistic — 2029': 'Pessimistico — 2029',
        'Year': 'Anno',
        'Ally': 'Alleato',
        'Series': 'Serie',
        'Male': 'Uomini',
        'Female': 'Donne',
        'Denmark': 'Danimarca',
        'Finland': 'Finlandia',
        'Poland': 'Polonia',
        'Lithuania': 'Lituania',
        'Latvia': 'Lettonia',
        'Estonia': 'Estonia',
        'Norway': 'Norvegia',
        'Sweden': 'Svezia',
        'Netherlands': 'Paesi Bassi',
        'United Kingdom': 'Regno Unito',
        'France': 'Francia',
        'Verified checkpoint date': 'Data del checkpoint verificato',
        'Elapsed share of scenario horizon': 'Quota trascorsa dell’orizzonte di scenario',
        'Jobs with some GenAI exposure': 'Lavori con una qualche esposizione all’IA generativa',
        'Global employment in highest exposure gradient': 'Occupazione globale nel livello di esposizione più alto',
        'EU enterprises using AI, 2025': 'Imprese UE che usano l’IA, 2025',
        'OECD employment at highest automation risk, all technologies': 'Occupazione OCSE al rischio di automazione più alto, tutte le tecnologie',
        'Enterprises using AI': 'Imprese che usano l’IA',
        'Income group': 'Gruppo di reddito',
        'Employment with some GenAI exposure': 'Occupazione con una qualche esposizione all’IA generativa',
        'ILO exposure category': 'Categoria di esposizione ILO',
        'Employment in exposure category': 'Occupazione nella categoria di esposizione',
        'Sex': 'Genere',
        'Weighted high-exposure index': 'Indice ponderato di alta esposizione',
        'Employees reporting need for AI skills': 'Lavoratori che dichiarano di aver bisogno di competenze IA',
        'Employees trained in previous year': 'Lavoratori formati nell’anno precedente',
        'Derived training gap': 'Divario di formazione derivato',
        'Composite AMR operational-risk index': 'Indice composito di rischio operativo AMR',
        'Resistant bacterial infections, 2023': 'Infezioni batteriche resistenti, 2023',
        'AMR-attributable deaths, 2021': 'Morti attribuibili all’AMR, 2021',
        'AMR-associated deaths, 2021': 'Morti associate all’AMR, 2021',
        'EU/EEA deaths annually': 'Morti annue nell’UE/SEE',
        'Clinical antibacterial candidates, 2025': 'Candidati antibatterici clinici, 2025',
        'Counterfactual definition': 'Definizione controfattuale',
        'Estimated deaths in 2021': 'Morti stimate nel 2021',
        'Pathogen–drug surveillance indicator': 'Indicatore di sorveglianza patogeno–farmaco',
        'Resistant bloodstream infections': 'Infezioni del sangue resistenti',
        'Reporting year': 'Anno di rilevazione',
        'Carbapenem-resistant K. pneumoniae bloodstream-infection incidence': 'Incidenza di infezioni del sangue da K. pneumoniae resistente ai carbapenemi',
        'Community systemic antibacterial consumption': 'Consumo comunitario di antibatterici sistemici',
        'Observed and target share': 'Quota osservata e obiettivo',
        'Access antibiotics as share of consumption': 'Quota di antibiotici Access sul consumo',
        'Candidate category': 'Categoria di candidato',
        'Clinical antibacterial candidates': 'Candidati antibatterici clinici',
        'Editorial deadline pressure': 'Pressione della scadenza editoriale',
        'Total defence expenditure': 'Spesa totale per la difesa',
        'Share of EU GDP': 'Quota del PIL UE',
        'Defence investment': 'Investimenti nella difesa',
        'Equipment procurement': 'Acquisto di equipaggiamenti',
        'Defence R&D': 'Ricerca e sviluppo per la difesa',
        'Member states at or above 2%': 'Stati membri al 2% o oltre',
        'Defence expenditure share': 'Quota di spesa per la difesa',
        'Real defence expenditure': 'Spesa reale per la difesa',
        'Estimated defence expenditure share': 'Quota stimata di spesa per la difesa',
        'Spending category': 'Categoria di spesa',
        'Expenditure': 'Spesa',
        'Large-calibre rounds objective': 'Obiettivo munizioni di grosso calibro',
        'SAFE loan capacity': 'Capacità di prestito SAFE',
        'Readiness 2030 mobilisation': 'Mobilitazione Readiness 2030',
        'Scenario evidence checkpoint': 'Checkpoint delle evidenze di scenario',
        'Protected and conserved terrestrial and inland-water area': 'Area terrestre e delle acque interne protetta e conservata',
        'Species threatened (global estimate)': 'Specie minacciate (stima globale)',
        'Land significantly altered': 'Territorio significativamente alterato',
        'Ocean with increasing cumulative impacts': 'Oceano con impatti cumulativi in aumento',
        'Wetland area lost from historical baseline': 'Zone umide perse rispetto alla base storica',
        'Living Planet Index': 'Living Planet Index',
        'Assessment year': 'Anno di valutazione',
        'Estimated wetland extent': 'Estensione stimata delle zone umide',
        'Geographic domain': 'Ambito geografico',
        'Area covered': 'Area coperta',
        '2024 observed': 'Valore osservato 2024',
        '2030 policy target': 'Obiettivo politico 2030',
        'Comprehensively assessed taxonomic group': 'Gruppo tassonomico valutato integralmente',
        'Assessed species threatened': 'Specie valutate minacciate',
        'Assessment period': 'Periodo di valutazione',
        'Average annual net forest-area loss': 'Perdita netta media annua di superficie forestale',
        'Scenario checkpoint': 'Checkpoint di scenario',
        'Editorial scenario risk index': 'Indice editoriale di rischio di scenario',
        'PLA ADIZ activity 2024': 'Attività PLA nella ADIZ, 2024',
        'Taiwan Strait trade at risk': 'Commercio a rischio nello Stretto di Taiwan',
        'Leading-edge chips in Taiwan': 'Chip avanzati prodotti a Taiwan',
        'Taiwan energy import dependence': 'Dipendenza energetica di Taiwan dalle importazioni',
        'Drone special budget': 'Bilancio speciale per i droni',
        'PLA ADIZ activity': 'Attività PLA nella ADIZ',
        'Observation': 'Osservazione',
        'Military and coast guard ships': 'Navi militari e della guardia costiera',
        'Exposure category': 'Categoria di esposizione',
        'Economic value exposed': 'Valore economico esposto',
        'Scenario': 'Scenario',
        'First-year Taiwan GDP shock': 'Shock sul PIL di Taiwan nel primo anno',
        'Energy import dependence': 'Dipendenza dalle importazioni energetiche',
        'Oil and gas import dependence': 'Dipendenza dalle importazioni di petrolio e gas',
        'Displayed LNG reserve': 'Riserva di GNL indicata',
        'Representative midpoint year of assessed window': 'Anno centrale rappresentativo della finestra valutata',
        'Global mean air-temperature anomaly vs 1850-1900': 'Anomalia della temperatura media globale dell’aria rispetto al 1850–1900',
        '2025 global air-temperature anomaly': 'Anomalia globale della temperatura dell’aria nel 2025',
        'Southeastern Europe strong-heat-stress days, summer 2024': 'Giorni di forte stress termico nell’Europa sudorientale, estate 2024',
        'Southeastern Europe tropical nights, summer 2024': 'Notti tropicali nell’Europa sudorientale, estate 2024',
        'WHO European Region heat-related deaths, annual 2000-2019 average': 'Morti legate al caldo nella Regione europea WHO, media annua 2000–2019',
        'Decade': 'Decennio',
        'European area with at least one tropical night': 'Area europea con almeno una notte tropicale',
        'Period': 'Periodo',
        'Days with UTCI at or above 32°C': 'Giorni con UTCI pari o superiore a 32°C',
        'Reference': 'Riferimento',
        'Nights with minimum air temperature at or above 20°C': 'Notti con temperatura minima pari o superiore a 20°C',
        'Country': 'Paese',
        'Cooling degree days': 'Gradi giorno di raffrescamento',
        'Total expenditure': 'Spesa totale',
        'R&D': 'R&S',
        'R&T': 'R&T',
    },
};

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

export function localizeDoomsdayLabel(value: string): string {
    return doomsdayLabelTranslations[currentLanguage.value]?.[value] ?? value;
}

export function localizeDoomsdayEnum(group: 'trendValues' | 'severityValues', value: string | null | undefined): string {
    const normalized = value?.trim() || 'unknown';
    const key = `${group}.${normalized}`;
    const translated = t(key);

    return translated === key ? normalized : translated;
}

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
