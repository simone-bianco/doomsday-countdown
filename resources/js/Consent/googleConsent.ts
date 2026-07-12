import type { ConsentPreferences } from './consentTypes';

type ConsentSignal = 'granted' | 'denied';
type ConsentModePayload = Record<string, ConsentSignal | number | boolean>;
type DataLayerItem = Record<string, unknown> | readonly unknown[];

declare global {
    interface Window {
        dataLayer?: DataLayerItem[];
        gtag?: (...args: unknown[]) => void;
    }
}

let defaultsInitialized = false;

function ensureDataLayer(): DataLayerItem[] {
    window.dataLayer = window.dataLayer ?? [];
    window.gtag = window.gtag ?? function gtag(...args: unknown[]): void {
        window.dataLayer?.push(args);
    };

    return window.dataLayer;
}

function consentSignal(value: boolean): ConsentSignal {
    return value ? 'granted' : 'denied';
}

export function initializeGoogleConsentDefaults(): void {
    if (typeof window === 'undefined' || defaultsInitialized) {
        return;
    }

    ensureDataLayer();
    window.gtag?.('consent', 'default', {
        ad_storage: 'denied',
        ad_user_data: 'denied',
        ad_personalization: 'denied',
        analytics_storage: 'denied',
        functionality_storage: 'denied',
        personalization_storage: 'denied',
        security_storage: 'granted',
        wait_for_update: 500,
    } satisfies ConsentModePayload);
    window.gtag?.('set', 'ads_data_redaction', true);
    window.gtag?.('set', 'url_passthrough', false);
    defaultsInitialized = true;
}

export function updateGoogleConsent(consent: ConsentPreferences): void {
    if (typeof window === 'undefined') {
        return;
    }

    ensureDataLayer();
    window.gtag?.('consent', 'update', {
        ad_storage: consentSignal(consent.marketing),
        ad_user_data: consentSignal(consent.marketing),
        ad_personalization: consentSignal(consent.marketing),
        analytics_storage: consentSignal(consent.analytics),
        functionality_storage: consentSignal(consent.functional),
        personalization_storage: consentSignal(consent.functional),
        security_storage: 'granted',
    } satisfies ConsentModePayload);
}

function appendScript(id: string, source: string): void {
    if (document.getElementById(id)) {
        return;
    }

    const script = document.createElement('script');
    script.id = id;
    script.async = true;
    script.src = source;
    document.head.appendChild(script);
}

export function loadGoogleTagManager(containerId: string): void {
    if (typeof window === 'undefined' || containerId === '') {
        return;
    }

    const dataLayer = ensureDataLayer();
    dataLayer.push({
        'gtm.start': Date.now(),
        event: 'gtm.js',
    });
    appendScript('google-tag-manager', `https://www.googletagmanager.com/gtm.js?id=${encodeURIComponent(containerId)}`);
}

export function loadGoogleAnalytics(measurementId: string): void {
    if (typeof window === 'undefined' || measurementId === '') {
        return;
    }

    ensureDataLayer();
    appendScript('google-analytics-tag', `https://www.googletagmanager.com/gtag/js?id=${encodeURIComponent(measurementId)}`);
    window.gtag?.('js', new Date());
    window.gtag?.('config', measurementId, {
        send_page_view: false,
    });
}

export function pushDataLayerEvent(event: string, payload: Record<string, unknown> = {}): void {
    if (typeof window === 'undefined') {
        return;
    }

    ensureDataLayer().push({
        event,
        ...payload,
    });
}
