import { defineComponent, computed, unref, withCtx, createVNode, toDisplayString, openBlock, createBlock, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
import { Head } from "@inertiajs/vue3";
import { c as _sfc_main$1 } from "./PublicLayout-CMrE3VbF.js";
import "../ssr.js";
import "@vue/server-renderer";
import "clsx";
import "tailwind-merge";
import "lucide-vue-next";
import "vue-advanced-cropper";
import "node:async_hooks";
import "i18next";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "LegalPolicy",
  __ssrInlineRender: true,
  props: {
    page: {}
  },
  setup(__props) {
    const props = __props;
    const isItalian = computed(() => props.page.current_locale === "it");
    const updatedLabel = computed(() => isItalian.value ? "Aggiornato: 9 luglio 2026" : "Updated: July 9, 2026");
    const backLabel = computed(() => isItalian.value ? "Torna alla dashboard" : "Back to dashboard");
    const privacySections = computed(() => isItalian.value ? [
      { title: "Titolare e contatti", body: ["Doomsday Clock tratta i dati personali raccolti tramite il sito pubblico. I contatti privacy definitivi devono essere completati dal titolare prima della pubblicazione in produzione."] },
      { title: "Dati trattati", body: ["Possiamo trattare dati tecnici di navigazione, preferenze lingua, preferenze cookie, dati inseriti volontariamente nei form futuri e dati generati dagli strumenti analytics o marketing solo dopo consenso."] },
      { title: "Finalità e basi giuridiche", body: ["I dati necessari servono a sicurezza e funzionamento del sito. Analytics e marketing vengono usati solo previo consenso per misurare visite, migliorare contenuti, campagne e conversioni."] },
      { title: "Fornitori e trasferimenti", body: ["Hosting, strumenti tecnici e piattaforme come Google Tag Manager, Google Analytics o Google Ads possono trattare dati come responsabili o autonomi titolari secondo i rispettivi contratti. I dettagli vanno aggiornati quando vengono inseriti gli ID reali dei servizi."] },
      { title: "Diritti", body: ["Puoi chiedere accesso, rettifica, cancellazione, limitazione, portabilità, opposizione e revoca del consenso. Puoi modificare i cookie dal pulsante “Impostazioni cookie”."] }
    ] : [
      { title: "Controller and contacts", body: ["Doomsday Clock processes personal data collected through the public website. Final privacy contact details must be completed by the controller before production launch."] },
      { title: "Data we process", body: ["We may process technical browsing data, language preferences, cookie choices, data voluntarily submitted through future forms and analytics or marketing data only after consent."] },
      { title: "Purposes and legal bases", body: ["Necessary data supports security and core site operation. Analytics and marketing are used only with consent to measure visits, improve content, campaigns and conversions."] },
      { title: "Providers and transfers", body: ["Hosting, technical providers and platforms such as Google Tag Manager, Google Analytics or Google Ads may process data as processors or independent controllers under their own terms. Details must be updated when real service IDs are added."] },
      { title: "Your rights", body: ["You can request access, rectification, erasure, restriction, portability, objection and consent withdrawal. You can change cookie choices from “Cookie settings”."] }
    ]);
    const cookieSections = computed(() => isItalian.value ? [
      { title: "Cookie necessari", body: ["Sono sempre attivi e servono a sicurezza, funzionamento base e memorizzazione della scelta cookie. Non abilitano marketing o analytics."] },
      { title: "Cookie analytics", body: ["Google Analytics o strumenti equivalenti vengono caricati solo se accetti la categoria Analytics. In assenza di consenso, i tag non vengono caricati."] },
      { title: "Cookie marketing", body: ["Google Ads, remarketing e pixel pubblicitari futuri vengono caricati solo se accetti Marketing. Il consenso viene comunicato tramite Google Consent Mode v2."] },
      { title: "Cookie funzionali", body: ["Servono per contenuti opzionali di terze parti, come embed o widget futuri. Sono disattivati di default."] },
      { title: "Gestione consenso", body: ["Puoi accettare tutto, rifiutare o personalizzare. La scelta dura fino a 180 giorni e puoi modificarla in qualsiasi momento dal pulsante “Impostazioni cookie”."] }
    ] : [
      { title: "Necessary cookies", body: ["They are always active and support security, core behavior and consent memory. They do not enable marketing or analytics."] },
      { title: "Analytics cookies", body: ["Google Analytics or equivalent tools load only when you accept Analytics. Without consent, those tags are not loaded."] },
      { title: "Marketing cookies", body: ["Google Ads, remarketing and future advertising pixels load only when you accept Marketing. Consent is passed through Google Consent Mode v2."] },
      { title: "Functional cookies", body: ["These support optional third-party content such as future embeds or widgets. They are off by default."] },
      { title: "Consent management", body: ["You can accept all, reject or customize. Your choice lasts up to 180 days and can be changed at any time from “Cookie settings”."] }
    ]);
    const sections = computed(() => props.page.kind === "cookies" ? cookieSections.value : privacySections.value);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: __props.page.title
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, {
        languages: __props.page.languages,
        "current-locale": __props.page.current_locale,
        "active-page": "about"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<article class="mx-auto grid max-w-4xl gap-6 px-4 py-8 sm:px-7 lg:py-12"${_scopeId}><a${ssrRenderAttr("href", `/?lang=${__props.page.current_locale}`)} class="text-sm font-semibold uppercase tracking-[0.18em] text-ui-primary hover:underline"${_scopeId}>${ssrInterpolate(backLabel.value)}</a><header class="rounded-3xl border border-white/10 bg-black/72 p-6 shadow-2xl backdrop-blur-xl sm:p-8"${_scopeId}><p class="text-xs uppercase tracking-[0.24em] text-ui-primary"${_scopeId}>${ssrInterpolate(updatedLabel.value)}</p><h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-5xl"${_scopeId}>${ssrInterpolate(__props.page.title)}</h1><p class="mt-4 text-sm leading-7 text-white/64"${_scopeId}>${ssrInterpolate(__props.page.kind === "cookies" ? isItalian.value ? "Questa pagina spiega categorie cookie, consenso e strumenti di tracking predisposti." : "This page explains cookie categories, consent and prepared tracking tools." : isItalian.value ? "Questa pagina descrive in modo operativo i trattamenti privacy principali del sito." : "This page describes the main privacy processing activities of the site.")}</p></header><!--[-->`);
            ssrRenderList(sections.value, (section) => {
              _push2(`<section class="rounded-2xl border border-white/10 bg-black/62 p-5 backdrop-blur-xl"${_scopeId}><h2 class="text-lg font-bold text-white"${_scopeId}>${ssrInterpolate(section.title)}</h2><!--[-->`);
              ssrRenderList(section.body, (paragraph) => {
                _push2(`<p class="mt-3 text-sm leading-7 text-white/66"${_scopeId}>${ssrInterpolate(paragraph)}</p>`);
              });
              _push2(`<!--]--></section>`);
            });
            _push2(`<!--]--></article>`);
          } else {
            return [
              createVNode("article", { class: "mx-auto grid max-w-4xl gap-6 px-4 py-8 sm:px-7 lg:py-12" }, [
                createVNode("a", {
                  href: `/?lang=${__props.page.current_locale}`,
                  class: "text-sm font-semibold uppercase tracking-[0.18em] text-ui-primary hover:underline"
                }, toDisplayString(backLabel.value), 9, ["href"]),
                createVNode("header", { class: "rounded-3xl border border-white/10 bg-black/72 p-6 shadow-2xl backdrop-blur-xl sm:p-8" }, [
                  createVNode("p", { class: "text-xs uppercase tracking-[0.24em] text-ui-primary" }, toDisplayString(updatedLabel.value), 1),
                  createVNode("h1", { class: "mt-3 text-3xl font-black tracking-tight text-white sm:text-5xl" }, toDisplayString(__props.page.title), 1),
                  createVNode("p", { class: "mt-4 text-sm leading-7 text-white/64" }, toDisplayString(__props.page.kind === "cookies" ? isItalian.value ? "Questa pagina spiega categorie cookie, consenso e strumenti di tracking predisposti." : "This page explains cookie categories, consent and prepared tracking tools." : isItalian.value ? "Questa pagina descrive in modo operativo i trattamenti privacy principali del sito." : "This page describes the main privacy processing activities of the site."), 1)
                ]),
                (openBlock(true), createBlock(Fragment, null, renderList(sections.value, (section) => {
                  return openBlock(), createBlock("section", {
                    key: section.title,
                    class: "rounded-2xl border border-white/10 bg-black/62 p-5 backdrop-blur-xl"
                  }, [
                    createVNode("h2", { class: "text-lg font-bold text-white" }, toDisplayString(section.title), 1),
                    (openBlock(true), createBlock(Fragment, null, renderList(section.body, (paragraph) => {
                      return openBlock(), createBlock("p", {
                        key: paragraph,
                        class: "mt-3 text-sm leading-7 text-white/66"
                      }, toDisplayString(paragraph), 1);
                    }), 128))
                  ]);
                }), 128))
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Doomsday/LegalPolicy.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
