export interface ConsentText {
    readonly compactTitle: string;
    readonly compactBody: string;
    readonly acceptAll: string;
    readonly rejectAll: string;
    readonly customize: string;
    readonly save: string;
    readonly settings: string;
    readonly close: string;
    readonly policyPrefix: string;
    readonly privacyPolicy: string;
    readonly cookiePolicy: string;
    readonly necessaryTitle: string;
    readonly necessaryBody: string;
    readonly analyticsTitle: string;
    readonly analyticsBody: string;
    readonly marketingTitle: string;
    readonly marketingBody: string;
    readonly functionalTitle: string;
    readonly functionalBody: string;
    readonly alwaysActive: string;
    readonly on: string;
    readonly off: string;
}

const en: ConsentText = {
    compactTitle: 'Privacy choices',
    compactBody: 'We use necessary cookies and, only with your consent, analytics and marketing tags to improve the site and measure growth.',
    acceptAll: 'Accept all',
    rejectAll: 'Reject',
    customize: 'Customize',
    save: 'Save choices',
    settings: 'Cookie settings',
    close: 'Close',
    policyPrefix: 'Details in our',
    privacyPolicy: 'Privacy Policy',
    cookiePolicy: 'Cookie Policy',
    necessaryTitle: 'Necessary',
    necessaryBody: 'Required for security, consent memory and core site behavior.',
    analyticsTitle: 'Analytics',
    analyticsBody: 'Helps us understand visits and improve content. Google Analytics can run only after this consent.',
    marketingTitle: 'Marketing',
    marketingBody: 'Allows advertising, remarketing and conversion tags such as Google Ads or social pixels.',
    functionalTitle: 'Functional',
    functionalBody: 'Allows optional embeds and third-party features when we add them.',
    alwaysActive: 'Always active',
    on: 'On',
    off: 'Off',
};

const it: ConsentText = {
    compactTitle: 'Scelte privacy',
    compactBody: 'Usiamo cookie necessari e, solo col tuo consenso, analytics e tag marketing per migliorare il sito e misurare la crescita.',
    acceptAll: 'Accetta tutto',
    rejectAll: 'Rifiuta',
    customize: 'Personalizza',
    save: 'Salva scelte',
    settings: 'Impostazioni cookie',
    close: 'Chiudi',
    policyPrefix: 'Dettagli nella',
    privacyPolicy: 'Privacy Policy',
    cookiePolicy: 'Cookie Policy',
    necessaryTitle: 'Necessari',
    necessaryBody: 'Richiesti per sicurezza, memoria del consenso e funzionamento base del sito.',
    analyticsTitle: 'Analytics',
    analyticsBody: 'Ci aiuta a capire le visite e migliorare i contenuti. Google Analytics parte solo dopo questo consenso.',
    marketingTitle: 'Marketing',
    marketingBody: 'Abilita advertising, remarketing e conversion tag come Google Ads o pixel social.',
    functionalTitle: 'Funzionali',
    functionalBody: 'Abilita embed opzionali e funzionalità di terze parti quando verranno aggiunti.',
    alwaysActive: 'Sempre attivi',
    on: 'Attivi',
    off: 'Disattivi',
};

export function consentText(locale: string): ConsentText {
    return locale === 'it' ? it : en;
}
