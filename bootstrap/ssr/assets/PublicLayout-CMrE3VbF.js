import { defineComponent, computed, mergeProps, unref, useSSRContext, createVNode, resolveDynamicComponent, ref, reactive, onMounted, onBeforeUnmount, withCtx, openBlock, createBlock, Fragment, renderList, createCommentVNode, toDisplayString, createTextVNode } from "vue";
import { ssrRenderAttrs, ssrRenderClass, ssrRenderComponent, ssrInterpolate, ssrRenderList, ssrRenderAttr, ssrRenderVNode, ssrRenderSlot } from "vue/server-renderer";
import { t, B as Button, _ as _sfc_main$8, v as setLanguage, w as _sfc_main$9, T as ToastNotification } from "../ssr.js";
import { router, usePage, Head } from "@inertiajs/vue3";
import { HeartHandshake, ExternalLink, MessagesSquare, Send, Cookie, Settings2, ShieldCheck, Globe2 } from "lucide-vue-next";
const PATREON_URL = "https://www.patreon.com/cw/doomsdayclock";
const _sfc_main$7 = /* @__PURE__ */ defineComponent({
  __name: "PatreonSupportLink",
  __ssrInlineRender: true,
  props: {
    placement: { default: "header" }
  },
  setup(__props) {
    const props = __props;
    const isHeader = computed(() => props.placement === "header");
    const rootClass = computed(() => isHeader.value ? "group inline-flex h-9 min-w-9 items-center justify-center gap-2 rounded-full border border-ui-primary/35 bg-ui-primary/[0.08] px-2.5 text-xs font-semibold text-white/88 shadow-[0_0_18px_rgba(255,42,35,0.08)] transition hover:border-ui-primary/70 hover:bg-ui-primary/[0.14] hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black sm:px-3" : "group inline-flex w-full max-w-xl items-center gap-4 rounded-2xl border border-ui-primary/35 bg-black/45 px-4 py-3 text-left shadow-[0_0_28px_rgba(255,42,35,0.10)] transition hover:-translate-y-0.5 hover:border-ui-primary/70 hover:bg-ui-primary/[0.10] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black sm:px-5 sm:py-4");
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<a${ssrRenderAttrs(mergeProps({
        href: PATREON_URL,
        target: "_blank",
        rel: "noopener noreferrer",
        "aria-label": `${unref(t)("supportOnPatreon")} — ${unref(t)("opensInNewTab")}`,
        class: rootClass.value
      }, _attrs))}><span class="${ssrRenderClass(isHeader.value ? "inline-flex h-5 w-5 items-center justify-center" : "inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-ui-primary/30 bg-ui-primary/[0.12] text-ui-primary")}">`);
      _push(ssrRenderComponent(unref(HeartHandshake), {
        class: isHeader.value ? "h-4 w-4 text-ui-primary" : "h-5 w-5",
        "aria-hidden": "true"
      }, null, _parent));
      _push(`</span>`);
      if (isHeader.value) {
        _push(`<span class="hidden sm:inline">${ssrInterpolate(unref(t)("supportUs"))}</span>`);
      } else {
        _push(`<span class="min-w-0 flex-1"><span class="doomsday-display block text-xs text-ui-primary">${ssrInterpolate(unref(t)("supportOnPatreon"))}</span><span class="mt-1 block text-sm leading-5 text-white/70">${ssrInterpolate(unref(t)("supportProjectDescription"))}</span></span>`);
      }
      if (isHeader.value) {
        _push(`<span class="hidden border-l border-white/15 pl-2 text-[10px] uppercase tracking-[0.16em] text-white/55 xl:inline">Patreon</span>`);
      } else {
        _push(`<!---->`);
      }
      _push(ssrRenderComponent(unref(ExternalLink), {
        class: isHeader.value ? "hidden h-3.5 w-3.5 text-white/45 transition group-hover:text-ui-primary sm:block" : "h-4 w-4 shrink-0 text-white/45 transition group-hover:text-ui-primary",
        "aria-hidden": "true"
      }, null, _parent));
      _push(`</a>`);
    };
  }
});
const _sfc_setup$7 = _sfc_main$7.setup;
_sfc_main$7.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/PatreonSupportLink.vue");
  return _sfc_setup$7 ? _sfc_setup$7(props, ctx) : void 0;
};
const _sfc_main$6 = /* @__PURE__ */ defineComponent({
  __name: "CommunityLinks",
  __ssrInlineRender: true,
  props: {
    placement: { default: "about" }
  },
  setup(__props) {
    const props = __props;
    const isHeader = computed(() => props.placement === "header");
    const communityLinks = [
      {
        label: "Discord",
        href: "https://discord.gg/NmKXDzwzK",
        icon: MessagesSquare
      },
      {
        label: "Telegram",
        href: "https://t.me/doomsdayclockofficial",
        icon: Send
      }
    ];
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: isHeader.value ? "flex items-center gap-1" : "grid w-full max-w-xl grid-cols-2 gap-3",
        "aria-label": "Discord and Telegram"
      }, _attrs))}><!--[-->`);
      ssrRenderList(communityLinks, (link) => {
        _push(`<a${ssrRenderAttr("href", link.href)} target="_blank" rel="noopener noreferrer"${ssrRenderAttr("aria-label", `${link.label} — ${unref(t)("opensInNewTab")}`)}${ssrRenderAttr("title", link.label)} class="${ssrRenderClass(isHeader.value ? "group inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/12 bg-white/[0.035] text-white/72 transition hover:border-ui-primary/60 hover:bg-ui-primary/[0.10] hover:text-ui-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black" : "group inline-flex min-h-12 items-center gap-3 rounded-xl border border-white/12 bg-white/[0.035] px-3.5 py-3 text-sm font-semibold text-white/78 transition hover:-translate-y-0.5 hover:border-ui-primary/50 hover:bg-ui-primary/[0.08] hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black sm:px-4")}"><span class="${ssrRenderClass(isHeader.value ? "inline-flex items-center justify-center" : "inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-white/10 bg-black/35 text-ui-primary transition group-hover:border-ui-primary/30")}">`);
        ssrRenderVNode(_push, createVNode(resolveDynamicComponent(link.icon), {
          class: "h-4 w-4",
          "aria-hidden": "true"
        }, null), _parent);
        _push(`</span>`);
        if (!isHeader.value) {
          _push(`<span class="min-w-0 flex-1 truncate">${ssrInterpolate(link.label)}</span>`);
        } else {
          _push(`<!---->`);
        }
        if (!isHeader.value) {
          _push(ssrRenderComponent(unref(ExternalLink), {
            class: "h-3.5 w-3.5 shrink-0 text-white/35 transition group-hover:text-ui-primary",
            "aria-hidden": "true"
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`</a>`);
      });
      _push(`<!--]--></div>`);
    };
  }
});
const _sfc_setup$6 = _sfc_main$6.setup;
_sfc_main$6.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/CommunityLinks.vue");
  return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
const doomsdayResponsiveImages = {
  doomsday_hero_background_desktop: { width: 1536, height: 1024, widths: [640, 960, 1280, 1536] },
  doomsday_hero_background_mobile: { width: 1440, height: 2560, widths: [480, 720, 1080, 1440] },
  ai_job_apocalypse: { width: 1672, height: 941, widths: [480, 768, 1200, 1672] },
  antibiotic_apocalypse: { width: 1535, height: 1024, widths: [480, 768, 1200, 1535] },
  europe_war_countdown: { width: 1672, height: 941, widths: [480, 768, 1200, 1672] },
  extreme_heat_breakpoint_separate: { width: 1671, height: 941, widths: [480, 768, 1200, 1671] },
  taiwan_invasion: { width: 1672, height: 941, widths: [480, 768, 1200, 1672] },
  uninhabitable_earth_separate: { width: 1536, height: 1024, widths: [480, 768, 1200, 1536] },
  society_collapse_separate: { width: 536, height: 473, widths: [320, 536] }
};
const doomsdayPngPattern = /^(.*\/images\/doomsday\/)([^/?#]+)\.png(?:[?#].*)?$/;
function srcset(base, name, widths, format) {
  return widths.map((width) => `${base}responsive/${name}-${width}.${format} ${width}w`).join(", ");
}
function resolveDoomsdayResponsiveImage(src) {
  const match = src.match(doomsdayPngPattern);
  if (!match) {
    return null;
  }
  const [, base, name] = match;
  const asset = doomsdayResponsiveImages[name];
  if (!asset) {
    return null;
  }
  return {
    ...asset,
    originalSrc: src,
    avifSrcset: srcset(base, name, asset.widths, "avif"),
    webpSrcset: srcset(base, name, asset.widths, "webp")
  };
}
const transparentPixel = "data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=";
const _sfc_main$5 = /* @__PURE__ */ defineComponent({
  __name: "ResponsiveImage",
  __ssrInlineRender: true,
  props: {
    src: {},
    mobileSrc: { default: null },
    alt: {},
    sizes: { default: "100vw" },
    mobileSizes: { default: null },
    breakpoint: { default: 768 },
    media: { default: null },
    inactiveMedia: { default: null },
    loading: { default: "lazy" },
    fetchPriority: { default: "auto" },
    decoding: { default: "async" },
    pictureClass: { default: "block" },
    imgClass: { default: "" },
    width: { default: null },
    height: { default: null },
    ariaHidden: { type: Boolean, default: false }
  },
  setup(__props) {
    const props = __props;
    const desktop = computed(() => resolveDoomsdayResponsiveImage(props.src));
    const mobile = computed(() => props.mobileSrc ? resolveDoomsdayResponsiveImage(props.mobileSrc) : null);
    const mobileMedia = computed(() => `(max-width: ${props.breakpoint}px)`);
    const desktopMedia = computed(() => props.mobileSrc ? `(min-width: ${props.breakpoint + 1}px)` : props.media ?? void 0);
    const resolvedWidth = computed(() => {
      var _a;
      return props.width ?? ((_a = desktop.value) == null ? void 0 : _a.width);
    });
    const resolvedHeight = computed(() => {
      var _a;
      return props.height ?? ((_a = desktop.value) == null ? void 0 : _a.height);
    });
    const resolvedMobileSizes = computed(() => props.mobileSizes ?? props.sizes);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<picture${ssrRenderAttrs(mergeProps({ class: __props.pictureClass }, _attrs))}>`);
      if (__props.inactiveMedia) {
        _push(`<source${ssrRenderAttr("media", __props.inactiveMedia)}${ssrRenderAttr("srcset", transparentPixel)}>`);
      } else {
        _push(`<!---->`);
      }
      if (mobile.value && __props.mobileSrc) {
        _push(`<!--[--><source${ssrRenderAttr("media", mobileMedia.value)} type="image/avif"${ssrRenderAttr("srcset", mobile.value.avifSrcset)}${ssrRenderAttr("sizes", resolvedMobileSizes.value)}><source${ssrRenderAttr("media", mobileMedia.value)} type="image/webp"${ssrRenderAttr("srcset", mobile.value.webpSrcset)}${ssrRenderAttr("sizes", resolvedMobileSizes.value)}><source${ssrRenderAttr("media", mobileMedia.value)}${ssrRenderAttr("srcset", __props.mobileSrc)}><!--]-->`);
      } else {
        _push(`<!---->`);
      }
      if (desktop.value) {
        _push(`<source${ssrRenderAttr("media", desktopMedia.value)} type="image/avif"${ssrRenderAttr("srcset", desktop.value.avifSrcset)}${ssrRenderAttr("sizes", __props.sizes)}>`);
      } else {
        _push(`<!---->`);
      }
      if (desktop.value) {
        _push(`<source${ssrRenderAttr("media", desktopMedia.value)} type="image/webp"${ssrRenderAttr("srcset", desktop.value.webpSrcset)}${ssrRenderAttr("sizes", __props.sizes)}>`);
      } else {
        _push(`<!---->`);
      }
      if (desktopMedia.value) {
        _push(`<source${ssrRenderAttr("media", desktopMedia.value)}${ssrRenderAttr("srcset", __props.src)}>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<img${ssrRenderAttr("src", __props.src)}${ssrRenderAttr("alt", __props.alt)}${ssrRenderAttr("width", resolvedWidth.value)}${ssrRenderAttr("height", resolvedHeight.value)}${ssrRenderAttr("loading", __props.loading)}${ssrRenderAttr("fetchpriority", __props.fetchPriority)}${ssrRenderAttr("decoding", __props.decoding)}${ssrRenderAttr("aria-hidden", __props.ariaHidden ? "true" : void 0)} class="${ssrRenderClass(__props.imgClass)}"></picture>`);
    };
  }
});
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/ResponsiveImage.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const en = {
  compactTitle: "Privacy choices",
  compactBody: "We use necessary cookies and, only with your consent, analytics and marketing tags to improve the site and measure growth.",
  acceptAll: "Accept all",
  rejectAll: "Reject",
  customize: "Customize",
  save: "Save choices",
  settings: "Cookie settings",
  close: "Close",
  policyPrefix: "Details in our",
  privacyPolicy: "Privacy Policy",
  cookiePolicy: "Cookie Policy",
  necessaryTitle: "Necessary",
  necessaryBody: "Required for security, consent memory and core site behavior.",
  analyticsTitle: "Analytics",
  analyticsBody: "Helps us understand visits and improve content. Google Analytics can run only after this consent.",
  marketingTitle: "Marketing",
  marketingBody: "Allows advertising, remarketing and conversion tags such as Google Ads or social pixels.",
  functionalTitle: "Functional",
  functionalBody: "Allows optional embeds and third-party features when we add them.",
  alwaysActive: "Always active",
  on: "On",
  off: "Off"
};
const it = {
  compactTitle: "Scelte privacy",
  compactBody: "Usiamo cookie necessari e, solo col tuo consenso, analytics e tag marketing per migliorare il sito e misurare la crescita.",
  acceptAll: "Accetta tutto",
  rejectAll: "Rifiuta",
  customize: "Personalizza",
  save: "Salva scelte",
  settings: "Impostazioni cookie",
  close: "Chiudi",
  policyPrefix: "Dettagli nella",
  privacyPolicy: "Privacy Policy",
  cookiePolicy: "Cookie Policy",
  necessaryTitle: "Necessari",
  necessaryBody: "Richiesti per sicurezza, memoria del consenso e funzionamento base del sito.",
  analyticsTitle: "Analytics",
  analyticsBody: "Ci aiuta a capire le visite e migliorare i contenuti. Google Analytics parte solo dopo questo consenso.",
  marketingTitle: "Marketing",
  marketingBody: "Abilita advertising, remarketing e conversion tag come Google Ads o pixel social.",
  functionalTitle: "Funzionali",
  functionalBody: "Abilita embed opzionali e funzionalità di terze parti quando verranno aggiunti.",
  alwaysActive: "Sempre attivi",
  on: "Attivi",
  off: "Disattivi"
};
function consentText(locale) {
  return locale === "it" ? it : en;
}
const CONSENT_VERSION = "2026-07-09-basic-consent-v1";
const CONSENT_STORAGE_KEY = "doomsday.cookieConsent";
const CONSENT_COOKIE_NAME = "dd_cookie_consent";
const CONSENT_MAX_AGE_DAYS = 180;
const EMPTY_CONSENT_DRAFT = {
  analytics: false,
  marketing: false,
  functional: false
};
function createConsentPreferences(draft, decision, previous) {
  const now = (/* @__PURE__ */ new Date()).toISOString();
  return {
    necessary: true,
    analytics: draft.analytics,
    marketing: draft.marketing,
    functional: draft.functional,
    version: CONSENT_VERSION,
    decision,
    created_at: (previous == null ? void 0 : previous.created_at) ?? now,
    updated_at: now
  };
}
const TRACKER_COOKIE_PREFIXES = ["_ga", "_gid", "_gat", "_gcl", "_fbp", "_fbc", "_hj", "_tt", "__utm"];
function isBrowser() {
  return typeof window !== "undefined" && typeof document !== "undefined";
}
function safeParse(raw) {
  try {
    return JSON.parse(raw);
  } catch {
    return null;
  }
}
function isConsentPreferences(value) {
  if (typeof value !== "object" || value === null) {
    return false;
  }
  const candidate = value;
  return candidate.necessary === true && typeof candidate.analytics === "boolean" && typeof candidate.marketing === "boolean" && typeof candidate.functional === "boolean" && candidate.version === CONSENT_VERSION && typeof candidate.created_at === "string" && typeof candidate.updated_at === "string";
}
function readCookie(name) {
  if (!isBrowser()) {
    return null;
  }
  const prefix = `${name}=`;
  const cookie = document.cookie.split("; ").find((entry) => entry.startsWith(prefix));
  return cookie ? decodeURIComponent(cookie.substring(prefix.length)) : null;
}
function writeCookie(name, value, maxAgeDays) {
  if (!isBrowser()) {
    return;
  }
  const secure = window.location.protocol === "https:" ? "; Secure" : "";
  const maxAge = maxAgeDays * 24 * 60 * 60;
  document.cookie = `${name}=${encodeURIComponent(value)}; Max-Age=${maxAge}; Path=/; SameSite=Lax${secure}`;
}
function readStoredConsent() {
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
function persistConsent(consent) {
  if (!isBrowser()) {
    return;
  }
  const serialized = JSON.stringify(consent);
  window.localStorage.setItem(CONSENT_STORAGE_KEY, serialized);
  writeCookie(CONSENT_COOKIE_NAME, serialized, CONSENT_MAX_AGE_DAYS);
}
function cookieDeletionDomains() {
  if (!isBrowser()) {
    return [null];
  }
  const hostname = window.location.hostname;
  const parts = hostname.split(".").filter(Boolean);
  const domains = /* @__PURE__ */ new Set([null, hostname]);
  if (parts.length > 2) {
    domains.add(`.${parts.slice(-2).join(".")}`);
  }
  return Array.from(domains);
}
function expireCookie(name, domain) {
  const domainPart = domain ? `; Domain=${domain}` : "";
  document.cookie = `${name}=; Max-Age=0; Path=/${domainPart}; SameSite=Lax`;
}
function deleteKnownTrackerCookies() {
  if (!isBrowser()) {
    return;
  }
  const cookieNames = document.cookie.split(";").map((entry) => entry.trim().split("=")[0]).filter((name) => TRACKER_COOKIE_PREFIXES.some((prefix) => name.startsWith(prefix)));
  for (const name of cookieNames) {
    for (const domain of cookieDeletionDomains()) {
      expireCookie(name, domain);
    }
  }
}
let defaultsInitialized = false;
function ensureDataLayer() {
  window.dataLayer = window.dataLayer ?? [];
  window.gtag = window.gtag ?? function gtag(...args) {
    var _a;
    (_a = window.dataLayer) == null ? void 0 : _a.push(args);
  };
  return window.dataLayer;
}
function consentSignal(value) {
  return value ? "granted" : "denied";
}
function initializeGoogleConsentDefaults() {
  var _a, _b, _c;
  if (typeof window === "undefined" || defaultsInitialized) {
    return;
  }
  ensureDataLayer();
  (_a = window.gtag) == null ? void 0 : _a.call(window, "consent", "default", {
    ad_storage: "denied",
    ad_user_data: "denied",
    ad_personalization: "denied",
    analytics_storage: "denied",
    functionality_storage: "denied",
    personalization_storage: "denied",
    security_storage: "granted",
    wait_for_update: 500
  });
  (_b = window.gtag) == null ? void 0 : _b.call(window, "set", "ads_data_redaction", true);
  (_c = window.gtag) == null ? void 0 : _c.call(window, "set", "url_passthrough", false);
  defaultsInitialized = true;
}
function updateGoogleConsent(consent) {
  var _a;
  if (typeof window === "undefined") {
    return;
  }
  ensureDataLayer();
  (_a = window.gtag) == null ? void 0 : _a.call(window, "consent", "update", {
    ad_storage: consentSignal(consent.marketing),
    ad_user_data: consentSignal(consent.marketing),
    ad_personalization: consentSignal(consent.marketing),
    analytics_storage: consentSignal(consent.analytics),
    functionality_storage: consentSignal(consent.functional),
    personalization_storage: consentSignal(consent.functional),
    security_storage: "granted"
  });
}
function appendScript(id, source) {
  if (document.getElementById(id)) {
    return;
  }
  const script = document.createElement("script");
  script.id = id;
  script.async = true;
  script.src = source;
  document.head.appendChild(script);
}
function loadGoogleTagManager(containerId) {
  if (typeof window === "undefined" || containerId === "") {
    return;
  }
  const dataLayer = ensureDataLayer();
  dataLayer.push({
    "gtm.start": Date.now(),
    event: "gtm.js"
  });
  appendScript("google-tag-manager", `https://www.googletagmanager.com/gtm.js?id=${encodeURIComponent(containerId)}`);
}
function loadGoogleAnalytics(measurementId) {
  var _a, _b;
  if (typeof window === "undefined" || measurementId === "") {
    return;
  }
  ensureDataLayer();
  appendScript("google-analytics-tag", `https://www.googletagmanager.com/gtag/js?id=${encodeURIComponent(measurementId)}`);
  (_a = window.gtag) == null ? void 0 : _a.call(window, "js", /* @__PURE__ */ new Date());
  (_b = window.gtag) == null ? void 0 : _b.call(window, "config", measurementId, {
    send_page_view: false
  });
}
function pushDataLayerEvent(event, payload = {}) {
  if (typeof window === "undefined") {
    return;
  }
  ensureDataLayer().push({
    event,
    ...payload
  });
}
let activeConsent = null;
let googleTagManagerLoaded = false;
let googleAnalyticsLoaded = false;
let lastTrackedPageLocation = null;
function trackingConfig() {
  return {
    googleTagManagerId: String("").trim(),
    googleAnalyticsId: String("").trim()
  };
}
function shouldLoadTracking(consent) {
  return consent.analytics || consent.marketing;
}
function loadAllowedGoogleTags(consent) {
  if (!shouldLoadTracking(consent)) {
    return;
  }
  const config = trackingConfig();
  if (config.googleTagManagerId !== "" && !googleTagManagerLoaded) {
    loadGoogleTagManager(config.googleTagManagerId);
    googleTagManagerLoaded = true;
    return;
  }
  if (config.googleTagManagerId === "" && config.googleAnalyticsId !== "" && consent.analytics && !googleAnalyticsLoaded) {
    loadGoogleAnalytics(config.googleAnalyticsId);
    googleAnalyticsLoaded = true;
  }
}
function applyTrackingConsent(consent) {
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
function initializeConsentRuntime() {
  initializeGoogleConsentDefaults();
  const stored = readStoredConsent();
  if (stored !== null) {
    applyTrackingConsent(stored);
  }
  return stored;
}
function saveConsentDraft(draft, decision) {
  const consent = createConsentPreferences(draft, decision, activeConsent ?? readStoredConsent());
  persistConsent(consent);
  applyTrackingConsent(consent);
  return consent;
}
function acceptAllConsent() {
  return saveConsentDraft({ analytics: true, marketing: true, functional: true }, "accepted_all");
}
function rejectOptionalConsent() {
  return saveConsentDraft(EMPTY_CONSENT_DRAFT, "rejected_all");
}
function currentConsent() {
  return activeConsent ?? readStoredConsent();
}
function canTrackAnalytics() {
  var _a;
  return ((_a = currentConsent()) == null ? void 0 : _a.analytics) === true;
}
function trackEvent(eventName, payload = {}) {
  if (!canTrackAnalytics()) {
    return;
  }
  pushDataLayerEvent(eventName, payload);
  if (typeof window !== "undefined" && window.gtag !== void 0 && trackingConfig().googleTagManagerId === "") {
    window.gtag("event", eventName, payload);
  }
}
function trackVirtualPageView() {
  if (typeof window === "undefined" || !canTrackAnalytics()) {
    return;
  }
  const pageLocation = window.location.href;
  if (lastTrackedPageLocation === pageLocation) {
    return;
  }
  trackEvent("page_view", {
    page_location: pageLocation,
    page_path: `${window.location.pathname}${window.location.search}`,
    page_title: document.title
  });
  lastTrackedPageLocation = pageLocation;
}
const _sfc_main$4 = /* @__PURE__ */ defineComponent({
  __name: "CookieConsentBanner",
  __ssrInlineRender: true,
  props: {
    currentLocale: {}
  },
  setup(__props) {
    const props = __props;
    const visible = ref(false);
    const preferencesOpen = ref(false);
    const savedConsent = ref(null);
    const draft = reactive({
      analytics: false,
      marketing: false,
      functional: false
    });
    const text = computed(() => consentText(props.currentLocale));
    const privacyUrl = computed(() => `/privacy?lang=${props.currentLocale}`);
    const cookieUrl = computed(() => `/cookie-policy?lang=${props.currentLocale}`);
    let removeRouterListener = null;
    function syncDraft(consent) {
      draft.analytics = (consent == null ? void 0 : consent.analytics) ?? false;
      draft.marketing = (consent == null ? void 0 : consent.marketing) ?? false;
      draft.functional = (consent == null ? void 0 : consent.functional) ?? false;
    }
    function commit(consent) {
      savedConsent.value = consent;
      syncDraft(consent);
      visible.value = false;
      preferencesOpen.value = false;
    }
    function acceptAll() {
      commit(acceptAllConsent());
    }
    function rejectAll() {
      commit(rejectOptionalConsent());
    }
    function saveCustom() {
      commit(saveConsentDraft({ ...draft }, "custom"));
    }
    function openSettings() {
      syncDraft(savedConsent.value);
      preferencesOpen.value = true;
      visible.value = true;
    }
    onMounted(() => {
      const consent = initializeConsentRuntime();
      savedConsent.value = consent;
      syncDraft(consent);
      visible.value = consent === null;
      removeRouterListener = router.on("finish", () => {
        trackVirtualPageView();
      });
    });
    onBeforeUnmount(() => {
      removeRouterListener == null ? void 0 : removeRouterListener();
    });
    return (_ctx, _push, _parent, _attrs) => {
      if (visible.value) {
        _push(`<section${ssrRenderAttrs(mergeProps({
          class: "fixed inset-x-3 bottom-3 z-[70] mx-auto max-w-2xl rounded-2xl border border-white/12 bg-black/90 p-4 text-white shadow-[0_22px_80px_rgba(0,0,0,0.62)] backdrop-blur-xl sm:bottom-5 sm:right-5 sm:left-auto sm:mx-0 sm:p-5",
          role: "dialog",
          "aria-live": "polite",
          "aria-label": "Cookie consent"
        }, _attrs))}><div class="flex items-start gap-3"><div class="hidden rounded-xl border border-ui-primary/30 bg-ui-primary/10 p-2 text-ui-primary sm:block">`);
        _push(ssrRenderComponent(unref(Cookie), {
          class: "h-5 w-5",
          "aria-hidden": "true"
        }, null, _parent));
        _push(`</div><div class="min-w-0 flex-1"><div class="flex items-start justify-between gap-3"><div><h2 class="text-sm font-semibold uppercase tracking-[0.18em] text-ui-primary">${ssrInterpolate(text.value.compactTitle)}</h2><p class="mt-2 text-sm leading-6 text-white/78">${ssrInterpolate(text.value.compactBody)}</p></div>`);
        if (savedConsent.value) {
          _push(ssrRenderComponent(unref(Button), {
            variant: "ghost",
            size: "sm",
            label: text.value.close,
            onClick: ($event) => visible.value = false
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
        if (preferencesOpen.value) {
          _push(`<div class="mt-4 grid gap-3 rounded-xl border border-white/10 bg-white/[0.035] p-3"><div class="flex items-start justify-between gap-4 rounded-lg border border-white/8 bg-black/35 p-3"><div><p class="text-sm font-semibold text-white">${ssrInterpolate(text.value.necessaryTitle)}</p><p class="mt-1 text-xs leading-5 text-white/60">${ssrInterpolate(text.value.necessaryBody)}</p></div><span class="shrink-0 rounded-full border border-white/10 px-2.5 py-1 text-[11px] uppercase tracking-[0.14em] text-white/60">${ssrInterpolate(text.value.alwaysActive)}</span></div>`);
          _push(ssrRenderComponent(unref(_sfc_main$8), {
            modelValue: draft.analytics,
            "onUpdate:modelValue": ($event) => draft.analytics = $event,
            label: text.value.analyticsTitle,
            description: text.value.analyticsBody,
            "on-label": text.value.on,
            "off-label": text.value.off
          }, null, _parent));
          _push(ssrRenderComponent(unref(_sfc_main$8), {
            modelValue: draft.marketing,
            "onUpdate:modelValue": ($event) => draft.marketing = $event,
            label: text.value.marketingTitle,
            description: text.value.marketingBody,
            "on-label": text.value.on,
            "off-label": text.value.off
          }, null, _parent));
          _push(ssrRenderComponent(unref(_sfc_main$8), {
            modelValue: draft.functional,
            "onUpdate:modelValue": ($event) => draft.functional = $event,
            label: text.value.functionalTitle,
            description: text.value.functionalBody,
            "on-label": text.value.on,
            "off-label": text.value.off
          }, null, _parent));
          _push(`</div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<p class="mt-3 text-xs leading-5 text-white/50">${ssrInterpolate(text.value.policyPrefix)} <a${ssrRenderAttr("href", privacyUrl.value)} class="text-ui-primary hover:underline">${ssrInterpolate(text.value.privacyPolicy)}</a> · <a${ssrRenderAttr("href", cookieUrl.value)} class="text-ui-primary hover:underline">${ssrInterpolate(text.value.cookiePolicy)}</a></p><div class="mt-4 flex flex-col-reverse gap-2 sm:flex-row sm:items-center sm:justify-end">`);
        _push(ssrRenderComponent(unref(Button), {
          variant: "ghost",
          size: "sm",
          label: text.value.rejectAll,
          onClick: rejectAll
        }, null, _parent));
        if (!preferencesOpen.value) {
          _push(ssrRenderComponent(unref(Button), {
            variant: "secondary",
            size: "sm",
            icon: unref(Settings2),
            label: text.value.customize,
            onClick: ($event) => preferencesOpen.value = true
          }, null, _parent));
        } else {
          _push(ssrRenderComponent(unref(Button), {
            variant: "secondary",
            size: "sm",
            label: text.value.save,
            onClick: saveCustom
          }, null, _parent));
        }
        _push(ssrRenderComponent(unref(Button), {
          size: "sm",
          icon: unref(ShieldCheck),
          label: text.value.acceptAll,
          onClick: acceptAll
        }, null, _parent));
        _push(`</div></div></div></section>`);
      } else if (savedConsent.value) {
        _push(ssrRenderComponent(unref(Button), mergeProps({
          class: "fixed bottom-3 left-3 z-[60]",
          variant: "secondary",
          size: "sm",
          icon: unref(ShieldCheck),
          label: text.value.settings,
          ui: { root: "rounded-full border-white/10 bg-black/70 text-xs text-white/78 shadow-lg backdrop-blur hover:text-white sm:bottom-4 sm:left-4" },
          onClick: openSettings
        }, _attrs), null, _parent));
      } else {
        _push(`<!---->`);
      }
    };
  }
});
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Consent/CookieConsentBanner.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const _sfc_main$3 = /* @__PURE__ */ defineComponent({
  __name: "SeoHead",
  __ssrInlineRender: true,
  setup(__props) {
    const page = usePage();
    const seo = computed(() => page.props.seo);
    const structuredData = computed(() => seo.value.structured_data.map((block) => JSON.stringify(block).replace(/</g, "\\u003C")));
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(Head), mergeProps({
        title: seo.value.title
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<meta head-key="description" name="description"${ssrRenderAttr("content", seo.value.description)}${_scopeId}><meta head-key="robots" name="robots"${ssrRenderAttr("content", seo.value.robots)}${_scopeId}><link head-key="canonical" rel="canonical"${ssrRenderAttr("href", seo.value.canonical_url)}${_scopeId}><!--[-->`);
            ssrRenderList(seo.value.alternates, (alternate) => {
              _push2(`<link${ssrRenderAttr("head-key", `alternate-${alternate.hreflang}`)} rel="alternate"${ssrRenderAttr("hreflang", alternate.hreflang)}${ssrRenderAttr("href", alternate.url)}${_scopeId}>`);
            });
            _push2(`<!--]--><link head-key="alternate-x-default" rel="alternate" hreflang="x-default"${ssrRenderAttr("href", seo.value.x_default_url)}${_scopeId}><meta head-key="og-site-name" property="og:site_name"${ssrRenderAttr("content", seo.value.open_graph.site_name)}${_scopeId}><meta head-key="og-type" property="og:type"${ssrRenderAttr("content", seo.value.open_graph.type)}${_scopeId}><meta head-key="og-title" property="og:title"${ssrRenderAttr("content", seo.value.open_graph.title)}${_scopeId}><meta head-key="og-description" property="og:description"${ssrRenderAttr("content", seo.value.open_graph.description)}${_scopeId}><meta head-key="og-url" property="og:url"${ssrRenderAttr("content", seo.value.open_graph.url)}${_scopeId}><meta head-key="og-locale" property="og:locale"${ssrRenderAttr("content", seo.value.open_graph.locale)}${_scopeId}><!--[-->`);
            ssrRenderList(seo.value.open_graph.alternate_locales, (locale) => {
              _push2(`<meta${ssrRenderAttr("head-key", `og-locale-${locale}`)} property="og:locale:alternate"${ssrRenderAttr("content", locale)}${_scopeId}>`);
            });
            _push2(`<!--]--><meta head-key="og-image" property="og:image"${ssrRenderAttr("content", seo.value.open_graph.image.url)}${_scopeId}><meta head-key="og-image-alt" property="og:image:alt"${ssrRenderAttr("content", seo.value.open_graph.image.alt)}${_scopeId}>`);
            if (seo.value.open_graph.image.width) {
              _push2(`<meta head-key="og-image-width" property="og:image:width"${ssrRenderAttr("content", String(seo.value.open_graph.image.width))}${_scopeId}>`);
            } else {
              _push2(`<!---->`);
            }
            if (seo.value.open_graph.image.height) {
              _push2(`<meta head-key="og-image-height" property="og:image:height"${ssrRenderAttr("content", String(seo.value.open_graph.image.height))}${_scopeId}>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<meta head-key="twitter-card" name="twitter:card"${ssrRenderAttr("content", seo.value.twitter.card)}${_scopeId}><meta head-key="twitter-title" name="twitter:title"${ssrRenderAttr("content", seo.value.twitter.title)}${_scopeId}><meta head-key="twitter-description" name="twitter:description"${ssrRenderAttr("content", seo.value.twitter.description)}${_scopeId}><meta head-key="twitter-image" name="twitter:image"${ssrRenderAttr("content", seo.value.twitter.image_url)}${_scopeId}><meta head-key="twitter-image-alt" name="twitter:image:alt"${ssrRenderAttr("content", seo.value.twitter.image_alt)}${_scopeId}>`);
            if (seo.value.date_modified) {
              _push2(`<meta head-key="date-modified" property="article:modified_time"${ssrRenderAttr("content", seo.value.date_modified)}${_scopeId}>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<!--[-->`);
            ssrRenderList(structuredData.value, (block, index) => {
              ssrRenderVNode(_push2, createVNode(resolveDynamicComponent("script"), {
                key: index,
                "head-key": `json-ld-${index}`,
                type: "application/ld+json"
              }, null), _parent2, _scopeId);
            });
            _push2(`<!--]-->`);
          } else {
            return [
              createVNode("meta", {
                "head-key": "description",
                name: "description",
                content: seo.value.description
              }, null, 8, ["content"]),
              createVNode("meta", {
                "head-key": "robots",
                name: "robots",
                content: seo.value.robots
              }, null, 8, ["content"]),
              createVNode("link", {
                "head-key": "canonical",
                rel: "canonical",
                href: seo.value.canonical_url
              }, null, 8, ["href"]),
              (openBlock(true), createBlock(Fragment, null, renderList(seo.value.alternates, (alternate) => {
                return openBlock(), createBlock("link", {
                  key: alternate.hreflang,
                  "head-key": `alternate-${alternate.hreflang}`,
                  rel: "alternate",
                  hreflang: alternate.hreflang,
                  href: alternate.url
                }, null, 8, ["head-key", "hreflang", "href"]);
              }), 128)),
              createVNode("link", {
                "head-key": "alternate-x-default",
                rel: "alternate",
                hreflang: "x-default",
                href: seo.value.x_default_url
              }, null, 8, ["href"]),
              createVNode("meta", {
                "head-key": "og-site-name",
                property: "og:site_name",
                content: seo.value.open_graph.site_name
              }, null, 8, ["content"]),
              createVNode("meta", {
                "head-key": "og-type",
                property: "og:type",
                content: seo.value.open_graph.type
              }, null, 8, ["content"]),
              createVNode("meta", {
                "head-key": "og-title",
                property: "og:title",
                content: seo.value.open_graph.title
              }, null, 8, ["content"]),
              createVNode("meta", {
                "head-key": "og-description",
                property: "og:description",
                content: seo.value.open_graph.description
              }, null, 8, ["content"]),
              createVNode("meta", {
                "head-key": "og-url",
                property: "og:url",
                content: seo.value.open_graph.url
              }, null, 8, ["content"]),
              createVNode("meta", {
                "head-key": "og-locale",
                property: "og:locale",
                content: seo.value.open_graph.locale
              }, null, 8, ["content"]),
              (openBlock(true), createBlock(Fragment, null, renderList(seo.value.open_graph.alternate_locales, (locale) => {
                return openBlock(), createBlock("meta", {
                  key: locale,
                  "head-key": `og-locale-${locale}`,
                  property: "og:locale:alternate",
                  content: locale
                }, null, 8, ["head-key", "content"]);
              }), 128)),
              createVNode("meta", {
                "head-key": "og-image",
                property: "og:image",
                content: seo.value.open_graph.image.url
              }, null, 8, ["content"]),
              createVNode("meta", {
                "head-key": "og-image-alt",
                property: "og:image:alt",
                content: seo.value.open_graph.image.alt
              }, null, 8, ["content"]),
              seo.value.open_graph.image.width ? (openBlock(), createBlock("meta", {
                key: 0,
                "head-key": "og-image-width",
                property: "og:image:width",
                content: String(seo.value.open_graph.image.width)
              }, null, 8, ["content"])) : createCommentVNode("", true),
              seo.value.open_graph.image.height ? (openBlock(), createBlock("meta", {
                key: 1,
                "head-key": "og-image-height",
                property: "og:image:height",
                content: String(seo.value.open_graph.image.height)
              }, null, 8, ["content"])) : createCommentVNode("", true),
              createVNode("meta", {
                "head-key": "twitter-card",
                name: "twitter:card",
                content: seo.value.twitter.card
              }, null, 8, ["content"]),
              createVNode("meta", {
                "head-key": "twitter-title",
                name: "twitter:title",
                content: seo.value.twitter.title
              }, null, 8, ["content"]),
              createVNode("meta", {
                "head-key": "twitter-description",
                name: "twitter:description",
                content: seo.value.twitter.description
              }, null, 8, ["content"]),
              createVNode("meta", {
                "head-key": "twitter-image",
                name: "twitter:image",
                content: seo.value.twitter.image_url
              }, null, 8, ["content"]),
              createVNode("meta", {
                "head-key": "twitter-image-alt",
                name: "twitter:image:alt",
                content: seo.value.twitter.image_alt
              }, null, 8, ["content"]),
              seo.value.date_modified ? (openBlock(), createBlock("meta", {
                key: 2,
                "head-key": "date-modified",
                property: "article:modified_time",
                content: seo.value.date_modified
              }, null, 8, ["content"])) : createCommentVNode("", true),
              (openBlock(true), createBlock(Fragment, null, renderList(structuredData.value, (block, index) => {
                return openBlock(), createBlock(resolveDynamicComponent("script"), {
                  key: index,
                  "head-key": `json-ld-${index}`,
                  type: "application/ld+json",
                  innerHTML: block
                }, null, 8, ["head-key", "innerHTML"]);
              }), 128))
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
});
const _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/SeoHead.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "LanguageSelector",
  __ssrInlineRender: true,
  props: {
    languages: {},
    currentLocale: {}
  },
  setup(__props) {
    const props = __props;
    const currentLanguage = computed(() => props.languages.find((language) => language.code === props.currentLocale) ?? props.languages[0]);
    onMounted(() => {
      void setLanguage(props.currentLocale);
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(_sfc_main$9), mergeProps({
        align: "right",
        width: "56",
        "content-classes": "bg-black/95 p-1 text-sm text-ui-foreground ring-1 ring-ui-border"
      }, _attrs), {
        trigger: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(Button), {
              variant: "secondary",
              size: "sm",
              icon: unref(Globe2),
              ui: { root: "border-ui-border bg-black/50 doomsday-display text-ui-primary" }
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                var _a, _b;
                if (_push3) {
                  _push3(`<span class="mr-2 inline-flex h-4 w-6 items-center justify-center overflow-hidden rounded-sm bg-white/10 text-sm leading-none"${_scopeId2}>${ssrInterpolate((_a = currentLanguage.value) == null ? void 0 : _a.flag)}</span> ${ssrInterpolate(__props.currentLocale.toUpperCase())}`);
                } else {
                  return [
                    createVNode("span", { class: "mr-2 inline-flex h-4 w-6 items-center justify-center overflow-hidden rounded-sm bg-white/10 text-sm leading-none" }, toDisplayString((_b = currentLanguage.value) == null ? void 0 : _b.flag), 1),
                    createTextVNode(" " + toDisplayString(__props.currentLocale.toUpperCase()), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(Button), {
                variant: "secondary",
                size: "sm",
                icon: unref(Globe2),
                ui: { root: "border-ui-border bg-black/50 doomsday-display text-ui-primary" }
              }, {
                default: withCtx(() => {
                  var _a;
                  return [
                    createVNode("span", { class: "mr-2 inline-flex h-4 w-6 items-center justify-center overflow-hidden rounded-sm bg-white/10 text-sm leading-none" }, toDisplayString((_a = currentLanguage.value) == null ? void 0 : _a.flag), 1),
                    createTextVNode(" " + toDisplayString(__props.currentLocale.toUpperCase()), 1)
                  ];
                }),
                _: 1
              }, 8, ["icon"])
            ];
          }
        }),
        content: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<!--[-->`);
            ssrRenderList(__props.languages, (language) => {
              _push2(`<a${ssrRenderAttr("href", language.url)} class="${ssrRenderClass([language.is_current ? "bg-white/10 text-white" : "text-ui-muted-foreground", "flex items-center justify-between rounded px-3 py-2 transition hover:bg-white/10"])}"${_scopeId}><span class="flex items-center gap-3"${_scopeId}><span class="inline-flex h-5 w-7 items-center justify-center overflow-hidden rounded-sm bg-white/10 text-base leading-none"${_scopeId}>${ssrInterpolate(language.flag)}</span> ${ssrInterpolate(language.native_label)}</span>`);
              if (language.is_current) {
                _push2(`<span class="text-ui-primary"${_scopeId}>✓</span>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</a>`);
            });
            _push2(`<!--]-->`);
          } else {
            return [
              (openBlock(true), createBlock(Fragment, null, renderList(__props.languages, (language) => {
                return openBlock(), createBlock("a", {
                  key: language.code,
                  href: language.url,
                  class: ["flex items-center justify-between rounded px-3 py-2 transition hover:bg-white/10", language.is_current ? "bg-white/10 text-white" : "text-ui-muted-foreground"]
                }, [
                  createVNode("span", { class: "flex items-center gap-3" }, [
                    createVNode("span", { class: "inline-flex h-5 w-7 items-center justify-center overflow-hidden rounded-sm bg-white/10 text-base leading-none" }, toDisplayString(language.flag), 1),
                    createTextVNode(" " + toDisplayString(language.native_label), 1)
                  ]),
                  language.is_current ? (openBlock(), createBlock("span", {
                    key: 0,
                    class: "text-ui-primary"
                  }, "✓")) : createCommentVNode("", true)
                ], 10, ["href"]);
              }), 128))
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
});
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/LanguageSelector.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "SiteHeader",
  __ssrInlineRender: true,
  props: {
    languages: {},
    currentLocale: {},
    activePage: { default: "home" }
  },
  setup(__props) {
    const props = __props;
    const homeUrl = computed(() => `/?lang=${props.currentLocale}`);
    const aboutUrl = computed(() => `/about?lang=${props.currentLocale}`);
    function navClass(page) {
      return props.activePage === page ? "border-ui-primary text-ui-primary" : "border-transparent text-white/80 hover:text-white";
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<header${ssrRenderAttrs(mergeProps({ class: "fixed inset-x-0 top-0 z-50 border-b border-white/10 bg-black/82 backdrop-blur-xl" }, _attrs))}><div class="mx-auto flex max-w-[1760px] items-center justify-between px-4 py-2 sm:px-7"><a${ssrRenderAttr("href", homeUrl.value)} class="flex items-center gap-3" aria-label="Doomsday Clock home"><img src="/images/doomsday/doomsday_logo_transparent.png" alt="Doomsday Clock" class="h-9 w-auto sm:h-10"></a><nav class="hidden items-center gap-8 text-sm uppercase tracking-[0.18em] lg:flex"><a${ssrRenderAttr("href", homeUrl.value)} class="${ssrRenderClass(["border-b-2 px-2 pb-2 pt-1", navClass("home")])}">${ssrInterpolate(unref(t)("home"))}</a><a${ssrRenderAttr("href", aboutUrl.value)} class="${ssrRenderClass(["border-b-2 px-2 pb-2 pt-1", navClass("about")])}">${ssrInterpolate(unref(t)("about"))}</a></nav><div class="flex items-center gap-1.5 sm:gap-2.5">`);
      _push(ssrRenderComponent(_sfc_main$6, { placement: "header" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$7, { placement: "header" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$2, {
        languages: __props.languages,
        "current-locale": __props.currentLocale
      }, null, _parent));
      _push(`</div></div></header>`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/SiteHeader.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "PublicLayout",
  __ssrInlineRender: true,
  props: {
    languages: {},
    currentLocale: {},
    hideMobileHeader: { type: Boolean, default: false },
    hideBackground: { type: Boolean, default: false },
    activePage: { default: "home" }
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(_sfc_main$3, null, null, _parent));
      _push(`<div class="doomsday-scrollbar relative min-h-screen overflow-x-hidden bg-black text-ui-foreground">`);
      if (!__props.hideBackground) {
        _push(`<div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">`);
        if (!__props.hideMobileHeader) {
          _push(ssrRenderComponent(_sfc_main$5, {
            src: "/images/doomsday/doomsday_hero_background_desktop.png",
            "mobile-src": "/images/doomsday/doomsday_hero_background_mobile.png",
            alt: "",
            sizes: "100vw",
            "mobile-sizes": "100vw",
            breakpoint: 768,
            loading: "eager",
            "fetch-priority": __props.activePage === "home" ? "high" : "auto",
            "aria-hidden": true,
            "picture-class": "block h-full w-full",
            "img-class": "h-full w-full object-cover object-center opacity-95"
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`<div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-black/15"></div><div class="absolute inset-0 bg-gradient-to-b from-black/20 via-black/35 to-black/90"></div><div class="absolute inset-0 bg-[radial-gradient(circle_at_68%_28%,rgba(255,42,35,0.18),transparent_34%)]"></div></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="relative z-10 min-h-screen">`);
      _push(ssrRenderComponent(unref(ToastNotification), null, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, {
        class: __props.hideMobileHeader ? "hidden lg:block" : "",
        languages: __props.languages,
        "current-locale": __props.currentLocale,
        "active-page": __props.activePage
      }, null, _parent));
      _push(`<main class="${ssrRenderClass(__props.hideMobileHeader ? "lg:pt-[64px]" : "pt-[64px]")}">`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</main>`);
      _push(ssrRenderComponent(_sfc_main$4, { "current-locale": __props.currentLocale }, null, _parent));
      _push(`</div></div><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/PublicLayout.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main$7 as _,
  _sfc_main$6 as a,
  _sfc_main$5 as b,
  _sfc_main as c
};
