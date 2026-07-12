import { createConsentPreferences, EMPTY_CONSENT_DRAFT, type ConsentDraft, type ConsentDecision, type ConsentPreferences } from './consentTypes';
import { deleteKnownTrackerCookies, persistConsent, readStoredConsent } from './consentStorage';
import { initializeGoogleConsentDefaults, loadGoogleAnalytics, loadGoogleTagManager, pushDataLayerEvent, updateGoogleConsent } from './googleConsent';

interface TrackingConfig {
    readonly googleTagManagerId: string;
    readonly googleAnalyticsId: string;
    readonly loadGoogleTagManagerBeforeConsent: boolean;
}

let activeConsent: ConsentPreferences | null = null;
let googleTagManagerLoaded = false;
let googleAnalyticsLoaded = false;
let lastTrackedPageLocation: string | null = null;

function trackingConfig(): TrackingConfig {
    return {
        googleTagManagerId: String(import.meta.env.VITE_GOOGLE_TAG_MANAGER_ID ?? '').trim(),
        googleAnalyticsId: String(import.meta.env.VITE_GOOGLE_ANALYTICS_ID ?? '').trim(),
        loadGoogleTagManagerBeforeConsent: String(import.meta.env.VITE_GOOGLE_TAG_MANAGER_PRELOAD ?? '').trim().toLowerCase() === 'true',
    };
}

function loadAllowedGoogleTags(consent: ConsentPreferences): void {
    const config = trackingConfig();

    const canLoadGoogleTagManager = config.loadGoogleTagManagerBeforeConsent || consent.analytics || consent.marketing;

    if (config.googleTagManagerId !== '' && canLoadGoogleTagManager && !googleTagManagerLoaded) {
        loadGoogleTagManager(config.googleTagManagerId);
        googleTagManagerLoaded = true;
        return;
    }

    if (config.googleTagManagerId === '' && config.googleAnalyticsId !== '' && consent.analytics && !googleAnalyticsLoaded) {
        loadGoogleAnalytics(config.googleAnalyticsId);
        googleAnalyticsLoaded = true;
    }
}

export function applyTrackingConsent(consent: ConsentPreferences): void {
    initializeGoogleConsentDefaults();
    updateGoogleConsent(consent);
    activeConsent = consent;

    if (!consent.analytics) {
        lastTrackedPageLocation = null;
    }

    if (!consent.analytics && !consent.marketing) {
        deleteKnownTrackerCookies();
    }

    loadAllowedGoogleTags(consent);

    if (consent.analytics) {
        trackVirtualPageView();
    }
}

export function initializeConsentRuntime(): ConsentPreferences | null {
    initializeGoogleConsentDefaults();
    loadAllowedGoogleTags(createConsentPreferences(EMPTY_CONSENT_DRAFT, 'rejected_all', null));
    const stored = readStoredConsent();

    if (stored !== null) {
        applyTrackingConsent(stored);
    }

    return stored;
}

export function saveConsentDraft(draft: ConsentDraft, decision: ConsentDecision): ConsentPreferences {
    const consent = createConsentPreferences(draft, decision, activeConsent ?? readStoredConsent());
    persistConsent(consent);
    applyTrackingConsent(consent);

    return consent;
}

export function acceptAllConsent(): ConsentPreferences {
    return saveConsentDraft({ analytics: true, marketing: true, functional: true }, 'accepted_all');
}

export function rejectOptionalConsent(): ConsentPreferences {
    return saveConsentDraft(EMPTY_CONSENT_DRAFT, 'rejected_all');
}

export function currentConsent(): ConsentPreferences | null {
    return activeConsent ?? readStoredConsent();
}

export function canTrackAnalytics(): boolean {
    return currentConsent()?.analytics === true;
}

export function trackEvent(eventName: string, payload: Record<string, unknown> = {}): void {
    if (!canTrackAnalytics()) {
        return;
    }

    pushDataLayerEvent(eventName, payload);

    if (typeof window !== 'undefined' && window.gtag !== undefined && trackingConfig().googleTagManagerId === '') {
        window.gtag('event', eventName, payload);
    }
}

export function trackVirtualPageView(): void {
    if (typeof window === 'undefined' || !canTrackAnalytics()) {
        return;
    }

    const pageLocation = window.location.href;

    if (lastTrackedPageLocation === pageLocation) {
        return;
    }

    trackEvent('page_view', {
        page_location: pageLocation,
        page_path: `${window.location.pathname}${window.location.search}`,
        page_title: document.title,
    });
    lastTrackedPageLocation = pageLocation;
}
