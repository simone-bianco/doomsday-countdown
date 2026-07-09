export const CONSENT_VERSION = '2026-07-09-basic-consent-v1';
export const CONSENT_STORAGE_KEY = 'doomsday.cookieConsent';
export const CONSENT_COOKIE_NAME = 'dd_cookie_consent';
export const CONSENT_MAX_AGE_DAYS = 180;

export type ConsentCategory = 'necessary' | 'analytics' | 'marketing' | 'functional';
export type ConsentDecision = 'accepted_all' | 'rejected_all' | 'custom';

export interface ConsentPreferences {
    readonly necessary: true;
    readonly analytics: boolean;
    readonly marketing: boolean;
    readonly functional: boolean;
    readonly version: string;
    readonly decision: ConsentDecision;
    readonly created_at: string;
    readonly updated_at: string;
}

export type MutableConsentPreferences = Omit<ConsentPreferences, 'necessary' | 'version' | 'created_at' | 'updated_at'> & {
    necessary: true;
    version: string;
    created_at: string;
    updated_at: string;
};

export interface ConsentDraft {
    readonly analytics: boolean;
    readonly marketing: boolean;
    readonly functional: boolean;
}

export const EMPTY_CONSENT_DRAFT: ConsentDraft = {
    analytics: false,
    marketing: false,
    functional: false,
};

export function createConsentPreferences(draft: ConsentDraft, decision: ConsentDecision, previous?: ConsentPreferences | null): ConsentPreferences {
    const now = new Date().toISOString();

    return {
        necessary: true,
        analytics: draft.analytics,
        marketing: draft.marketing,
        functional: draft.functional,
        version: CONSENT_VERSION,
        decision,
        created_at: previous?.created_at ?? now,
        updated_at: now,
    };
}
