import {
    CONSENT_COOKIE_NAME,
    CONSENT_MAX_AGE_DAYS,
    CONSENT_STORAGE_KEY,
    CONSENT_VERSION,
    type ConsentPreferences,
} from './consentTypes';

const TRACKER_COOKIE_PREFIXES = ['_ga', '_gid', '_gat', '_gcl', '_fbp', '_fbc', '_hj', '_tt', '__utm'];

function isBrowser(): boolean {
    return typeof window !== 'undefined' && typeof document !== 'undefined';
}

function safeParse(raw: string): unknown | null {
    try {
        return JSON.parse(raw);
    } catch {
        return null;
    }
}

function isConsentPreferences(value: unknown): value is ConsentPreferences {
    if (typeof value !== 'object' || value === null) {
        return false;
    }

    const candidate = value as Record<string, unknown>;

    return candidate.necessary === true
        && typeof candidate.analytics === 'boolean'
        && typeof candidate.marketing === 'boolean'
        && typeof candidate.functional === 'boolean'
        && candidate.version === CONSENT_VERSION
        && typeof candidate.created_at === 'string'
        && typeof candidate.updated_at === 'string';
}

function readCookie(name: string): string | null {
    if (!isBrowser()) {
        return null;
    }

    const prefix = `${name}=`;
    const cookie = document.cookie
        .split('; ')
        .find((entry) => entry.startsWith(prefix));

    return cookie ? decodeURIComponent(cookie.substring(prefix.length)) : null;
}

function writeCookie(name: string, value: string, maxAgeDays: number): void {
    if (!isBrowser()) {
        return;
    }

    const secure = window.location.protocol === 'https:' ? '; Secure' : '';
    const maxAge = maxAgeDays * 24 * 60 * 60;
    document.cookie = `${name}=${encodeURIComponent(value)}; Max-Age=${maxAge}; Path=/; SameSite=Lax${secure}`;
}

export function readStoredConsent(): ConsentPreferences | null {
    if (!isBrowser()) {
        return null;
    }

    const localValue = window.localStorage.getItem(CONSENT_STORAGE_KEY);
    const localConsent = localValue ? safeParse(localValue) : null;

    if (isConsentPreferences(localConsent)) {
        return localConsent;
    }

    const cookieValue = readCookie(CONSENT_COOKIE_NAME);
    const cookieConsent = cookieValue ? safeParse(cookieValue) : null;

    return isConsentPreferences(cookieConsent) ? cookieConsent : null;
}

export function persistConsent(consent: ConsentPreferences): void {
    if (!isBrowser()) {
        return;
    }

    const serialized = JSON.stringify(consent);
    window.localStorage.setItem(CONSENT_STORAGE_KEY, serialized);
    writeCookie(CONSENT_COOKIE_NAME, serialized, CONSENT_MAX_AGE_DAYS);
}

function cookieDeletionDomains(): readonly (string | null)[] {
    if (!isBrowser()) {
        return [null];
    }

    const hostname = window.location.hostname;
    const parts = hostname.split('.').filter(Boolean);
    const domains = new Set<string | null>([null, hostname]);

    if (parts.length > 2) {
        domains.add(`.${parts.slice(-2).join('.')}`);
    }

    return Array.from(domains);
}

function expireCookie(name: string, domain: string | null): void {
    const domainPart = domain ? `; Domain=${domain}` : '';
    document.cookie = `${name}=; Max-Age=0; Path=/${domainPart}; SameSite=Lax`;
}

export function deleteKnownTrackerCookies(): void {
    if (!isBrowser()) {
        return;
    }

    const cookieNames = document.cookie
        .split(';')
        .map((entry) => entry.trim().split('=')[0])
        .filter((name) => TRACKER_COOKIE_PREFIXES.some((prefix) => name.startsWith(prefix)));

    for (const name of cookieNames) {
        for (const domain of cookieDeletionDomains()) {
            expireCookie(name, domain);
        }
    }
}
