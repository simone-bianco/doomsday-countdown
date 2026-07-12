import assert from 'node:assert/strict';
import render from '../../bootstrap/ssr/ssr.js';

const locales = ['en', 'it', 'fr', 'de', 'es', 'nl', 'sv', 'pl'];
const expectedCopy = {
    en: { support: 'Support on Patreon', external: 'opens in a new tab' },
    it: { support: 'Sostieni su Patreon', external: 'si apre in una nuova scheda' },
    fr: { support: 'Soutenir sur Patreon', external: 's’ouvre dans un nouvel onglet' },
    de: { support: 'Auf Patreon unterstützen', external: 'öffnet in einem neuen Tab' },
    es: { support: 'Apoyar en Patreon', external: 'se abre en una pestaña nueva' },
    nl: { support: 'Steun via Patreon', external: 'opent in een nieuw tabblad' },
    sv: { support: 'Stöd på Patreon', external: 'öppnas i en ny flik' },
    pl: { support: 'Wesprzyj na Patreon', external: 'otwiera się w nowej karcie' },
};
const image = { url: 'https://doomsday-clock.com/favicon-512x512.png', width: 512, height: 512, alt: 'Doomsday Clock' };

function languagesFor(locale) {
    return locales.map((code) => ({
        code,
        label: code.toUpperCase(),
        native_label: code.toUpperCase(),
        flag: '🌐',
        url: `/about?lang=${code}`,
        is_current: code === locale,
    }));
}

function seoFor(locale) {
    const title = `SSR ${locale}`;
    const canonical = `https://doomsday-clock.com/about?lang=${locale}`;

    return {
        title,
        description: `SSR description ${locale}`,
        canonical_url: canonical,
        robots: 'index,follow',
        locale,
        alternates: locales.map((hreflang) => ({ hreflang, url: `https://doomsday-clock.com/about?lang=${hreflang}` })),
        x_default_url: 'https://doomsday-clock.com/about?lang=en',
        open_graph: {
            title,
            description: `SSR description ${locale}`,
            url: canonical,
            type: 'website',
            site_name: 'Doomsday Clock',
            locale,
            alternate_locales: [],
            image,
        },
        twitter: {
            card: 'summary_large_image',
            title,
            description: `SSR description ${locale}`,
            image_url: image.url,
            image_alt: image.alt,
        },
        date_modified: null,
        structured_data: [],
    };
}

function pageDataFor(locale) {
    return {
        app_name: 'Doomsday Clock',
        current_locale: locale,
        languages: languagesFor(locale),
        title: `About ${locale}`,
        subtitle: `Server-rendered content ${locale}`,
        eyebrow: 'Global signals',
        hero_badge: 'Observatory',
        filter_watch_label: 'Monitoring',
        visual_label: 'Scenario',
        pipeline_label: 'Pipeline',
        faq_title: 'FAQ',
        faq_subtitle: 'Answers',
        closing_label: 'Support the project',
        intro: ['Introduction'],
        stats: [],
        sections: [],
        timeline: [{ label: '01', title: 'Checkpoint', body: 'Evidence' }],
        faq: [],
        closing_title: 'Keep the project independent',
        closing_body: 'Contribute to maintenance.',
    };
}

async function renderLocale(locale) {
    return render({
        component: 'Doomsday/About',
        url: `/about?lang=${locale}`,
        version: null,
        clearHistory: false,
        encryptHistory: false,
        props: {
            locale,
            rendered_at: '2026-07-12T12:00:00+00:00',
            seo: seoFor(locale),
            page: pageDataFor(locale),
            errors: {},
        },
    });
}

const results = await Promise.all(locales.map(async (locale) => [locale, await renderLocale(locale)]));

for (const [locale, result] of results) {
    const head = result.head.join('');
    const copy = expectedCopy[locale];

    assert.match(result.body, new RegExp(copy.support.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')));
    assert.match(result.body, new RegExp(copy.external.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')));
    assert.match(head, new RegExp(`SSR ${locale}`));

    for (const [otherLocale, otherCopy] of Object.entries(expectedCopy)) {
        if (otherLocale !== locale && otherCopy.support !== copy.support) {
            assert.doesNotMatch(result.body, new RegExp(otherCopy.support.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')));
        }
    }
}

console.log('SSR 8-locale concurrent render smoke passed');
