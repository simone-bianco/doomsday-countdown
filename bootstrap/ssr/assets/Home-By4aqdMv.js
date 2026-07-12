import { defineComponent, computed, mergeProps, useSSRContext, ref, onMounted, onUnmounted, unref, withCtx, withDirectives, openBlock, createBlock, createCommentVNode, createVNode, withKeys, withModifiers, createTextVNode, toDisplayString, resolveDynamicComponent, Fragment, renderList, shallowRef, reactive, watch, onBeforeUnmount } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrRenderClass, ssrInterpolate, ssrRenderList, ssrGetDirectiveProps, ssrRenderStyle, ssrRenderVNode, ssrRenderAttr } from "vue/server-renderer";
import { motion, vMotion, AnimatePresence } from "motion-v";
import { usePage, Link, Head } from "@inertiajs/vue3";
import { h as _sfc_main$m, B as Button, t, j as _sfc_main$o, S as SkeletonLoader, u as currentLanguage, P } from "../ssr.js";
import { b as _sfc_main$l, c as _sfc_main$r } from "./PublicLayout-Cg7TjPHG.js";
import { u as useDoomsdayReducedMotion, r as resolveMotionPreset, s as selectedAccentPulse, d as selectedCardIdle, e as disabledMotionTarget, g as selectedCardActive, h as cardHover, i as cardPress, j as fastTransition, a as cardReveal, w as withMotionDelay, c as cardStaggerDelay, b as fadeIn, f as fadeUp, p as panelReveal, t as tabContent } from "./doomsdayMotion-D6KS_x2N.js";
import { ChevronLeft, RefreshCw, ExternalLink, Share2, X, ChevronUp, ChevronDown, Sparkles, Newspaper, Folder, FileText, ArrowLeft, Minimize2, Maximize2, ChevronRight, Activity, RadioTower, CalendarDays } from "lucide-vue-next";
import { _ as _sfc_main$n, a as _sfc_main$p, b as _sfc_main$q } from "./VisualizationChart-CkPtd6z_.js";
import axios from "axios";
import "@vue/server-renderer";
import "clsx";
import "tailwind-merge";
import "vue-advanced-cropper";
import "node:async_hooks";
import "i18next";
const _sfc_main$k = /* @__PURE__ */ defineComponent({
  __name: "CountdownCardImage",
  __ssrInlineRender: true,
  props: {
    imageUrl: {},
    title: {},
    subtitle: {},
    compact: { type: Boolean, default: false }
  },
  setup(__props) {
    const props = __props;
    const wrapperClass = computed(() => props.compact ? "relative h-[150px] min-w-0 overflow-hidden sm:h-[160px] xl:h-[150px]" : "relative h-[220px] min-w-0 overflow-hidden sm:h-[240px] xl:h-[260px]");
    const overlayClass = computed(() => props.compact ? "p-3 sm:p-4" : "p-4 sm:p-5");
    const titleClass = computed(() => props.compact ? "doomsday-display line-clamp-2 text-[clamp(1rem,2vw,1.25rem)] font-bold leading-[1.08] tracking-[0.06em] text-white drop-shadow-[0_2px_12px_rgba(0,0,0,0.9)]" : "doomsday-display line-clamp-2 text-[clamp(1.25rem,3vw,1.95rem)] font-bold leading-[1.05] tracking-[0.06em] text-white drop-shadow-[0_2px_16px_rgba(0,0,0,0.9)]");
    const subtitleClass = computed(() => props.compact ? "mt-2 line-clamp-2 text-[0.72rem] leading-relaxed text-white/75 sm:text-xs" : "line-clamp-2 text-xs leading-relaxed text-white/75 sm:text-sm");
    const imageSizes = computed(() => props.compact ? "(min-width: 1280px) 42vw, (min-width: 1024px) 48vw, 100vw" : "(min-width: 1536px) calc(100vw - 680px), (min-width: 1024px) calc(100vw - 620px), 100vw");
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: wrapperClass.value }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$l, {
        src: __props.imageUrl,
        alt: __props.title,
        sizes: imageSizes.value,
        loading: "lazy",
        "fetch-priority": "low",
        "picture-class": "block h-full w-full",
        "img-class": "h-full w-full object-cover object-center"
      }, null, _parent));
      _push(`<div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/35 to-black/95"></div><div class="${ssrRenderClass([overlayClass.value, "absolute inset-x-0 bottom-0"])}"><h2 class="${ssrRenderClass(titleClass.value)}">${ssrInterpolate(__props.title)}</h2><p class="${ssrRenderClass(subtitleClass.value)}">${ssrInterpolate(__props.subtitle)}</p></div></div>`);
    };
  }
});
const _sfc_setup$k = _sfc_main$k.setup;
_sfc_main$k.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/CountdownCardImage.vue");
  return _sfc_setup$k ? _sfc_setup$k(props, ctx) : void 0;
};
const SECONDS_PER_YEAR = 31536e3;
const SECONDS_PER_DAY = 86400;
const SECONDS_PER_HOUR = 3600;
const SECONDS_PER_MINUTE = 60;
function parseRenderedAt(renderedAt) {
  const timestamp = Date.parse(renderedAt);
  if (!Number.isFinite(timestamp)) {
    throw new Error(`Invalid rendered_at page prop: ${String(renderedAt)}`);
  }
  return timestamp;
}
function countdownParts(targetDate, now, compact = false) {
  const target = targetDate === null ? now : new Date(targetDate).getTime();
  const totalSeconds = Math.max(0, Math.floor((target - now) / 1e3));
  const years = Math.floor(totalSeconds / SECONDS_PER_YEAR);
  const days = Math.floor(totalSeconds % SECONDS_PER_YEAR / SECONDS_PER_DAY);
  const hours = Math.floor(totalSeconds % SECONDS_PER_DAY / SECONDS_PER_HOUR);
  const minutes = Math.floor(totalSeconds % SECONDS_PER_HOUR / SECONDS_PER_MINUTE);
  const seconds = totalSeconds % SECONDS_PER_MINUTE;
  return compact ? [
    { label: "YRS", value: years },
    { label: "DAYS", value: days },
    { label: "HRS", value: hours },
    { label: "MIN", value: minutes },
    { label: "SEC", value: seconds }
  ] : [
    { label: "YEARS", value: years },
    { label: "DAYS", value: days },
    { label: "HOURS", value: hours },
    { label: "MIN", value: minutes },
    { label: "SEC", value: seconds }
  ];
}
const _sfc_main$j = /* @__PURE__ */ defineComponent({
  __name: "CountdownTimer",
  __ssrInlineRender: true,
  props: {
    targetDate: {},
    compact: { type: Boolean, default: false },
    dense: { type: Boolean, default: false }
  },
  setup(__props) {
    const props = __props;
    const page = usePage();
    const now = ref(parseRenderedAt(page.props.rendered_at));
    let timer;
    onMounted(() => {
      now.value = Date.now();
      timer = window.setInterval(() => {
        now.value = Date.now();
      }, 1e3);
    });
    onUnmounted(() => {
      if (timer !== void 0) {
        window.clearInterval(timer);
      }
    });
    const parts = computed(() => countdownParts(props.targetDate, now.value, props.compact));
    const valueClass = computed(() => props.dense ? "text-[clamp(0.72rem,2.15vw,1.08rem)] leading-none tabular-nums sm:text-[clamp(0.88rem,1.45vw,1.15rem)] 2xl:text-xl" : "text-[clamp(0.95rem,3.8vw,1.9rem)] leading-none tabular-nums sm:text-3xl 2xl:text-4xl");
    const labelGapClass = computed(() => props.dense ? "gap-x-1 sm:gap-x-1.5" : "gap-x-2 sm:gap-x-4");
    function pad(value) {
      return value.toString().padStart(2, "0");
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "doomsday-display max-w-full min-w-0" }, _attrs))}><div class="flex min-w-0 flex-nowrap items-end justify-center gap-x-0.5 whitespace-nowrap text-ui-primary sm:gap-x-1"><!--[-->`);
      ssrRenderList(parts.value, (part, index) => {
        _push(`<!--[--><span class="${ssrRenderClass(valueClass.value)}">${ssrInterpolate(pad(part.value))}</span>`);
        if (index < parts.value.length - 1) {
          _push(`<span class="${ssrRenderClass(valueClass.value)}">:</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></div><div class="${ssrRenderClass(["mt-1 flex min-w-0 flex-nowrap justify-center whitespace-nowrap text-[8px] text-ui-primary sm:text-[10px]", labelGapClass.value])}"><!--[-->`);
      ssrRenderList(parts.value, (part) => {
        _push(`<span>${ssrInterpolate(part.label)}</span>`);
      });
      _push(`<!--]--></div></div>`);
    };
  }
});
const _sfc_setup$j = _sfc_main$j.setup;
_sfc_main$j.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/CountdownTimer.vue");
  return _sfc_setup$j ? _sfc_setup$j(props, ctx) : void 0;
};
const _sfc_main$i = /* @__PURE__ */ defineComponent({
  __name: "CountdownCard",
  __ssrInlineRender: true,
  props: {
    countdown: {},
    compact: { type: Boolean, default: false },
    selectedSlug: { default: null },
    pendingSlug: { default: null }
  },
  emits: ["select"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const reducedMotion = useDoomsdayReducedMotion();
    const isSelected = computed(() => props.countdown.slug === props.selectedSlug);
    const isPending = computed(() => props.countdown.slug === props.pendingSlug);
    const gridClass = computed(() => "grid min-w-0 grid-cols-1 gap-0");
    const timerClass = computed(() => props.compact ? "flex min-w-0 flex-col items-center justify-center border-t border-white/10 px-4 py-2.5 text-center sm:px-5" : "flex min-w-0 flex-col items-center justify-center border-t border-white/10 px-4 py-3 text-center sm:px-6");
    const accentMotion = computed(() => resolveMotionPreset(selectedAccentPulse, reducedMotion.value));
    const selectedCardMotion = computed(() => isSelected.value ? disabledMotionTarget(selectedCardActive, reducedMotion.value) : selectedCardIdle);
    const hoverMotion = computed(() => disabledMotionTarget(cardHover, reducedMotion.value));
    const pressMotion = computed(() => disabledMotionTarget(cardPress, reducedMotion.value));
    return (_ctx, _push, _parent, _attrs) => {
      let _temp0;
      _push(ssrRenderComponent(unref(motion).div, mergeProps({
        animate: selectedCardMotion.value,
        transition: unref(fastTransition),
        "while-hover": hoverMotion.value,
        "while-press": pressMotion.value
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$m), {
              role: "button",
              tabindex: "0",
              "aria-pressed": isSelected.value,
              ui: {
                root: ["doomsday-card relative h-fit self-start min-w-0 overflow-hidden rounded-xl transition duration-200 hover:border-ui-primary/80 focus:outline-none focus:ring-2 focus:ring-ui-primary/50", isSelected.value ? "doomsday-glow border-ui-primary" : "", isPending.value ? "border-ui-primary/70 shadow-[0_0_28px_rgba(255,42,35,0.18)]" : ""].join(" "),
                body: "p-0 min-w-0"
              },
              onClick: ($event) => emit("select", __props.countdown),
              onKeydown: [($event) => emit("select", __props.countdown), ($event) => emit("select", __props.countdown)]
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (isSelected.value) {
                    _push3(`<span${ssrRenderAttrs(_temp0 = mergeProps({
                      "aria-hidden": "true",
                      class: "pointer-events-none absolute inset-y-0 left-0 z-20 w-[2px] bg-ui-primary shadow-[0_0_16px_rgba(255,42,35,0.75)] origin-left",
                      initial: accentMotion.value.initial,
                      animate: accentMotion.value.animate,
                      transition: accentMotion.value.transition
                    }, ssrGetDirectiveProps(_ctx, unref(vMotion))))}${_scopeId2}>${"textContent" in _temp0 ? ssrInterpolate(_temp0.textContent) : _temp0.innerHTML ?? ""}</span>`);
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(`<div class="${ssrRenderClass(gridClass.value)}"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$k, {
                    "image-url": __props.countdown.image_url,
                    title: __props.countdown.title,
                    subtitle: __props.countdown.summary,
                    compact: __props.compact
                  }, null, _parent3, _scopeId2));
                  _push3(`<div class="${ssrRenderClass(timerClass.value)}"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$j, {
                    "target-date": __props.countdown.timer.target_date,
                    compact: true,
                    dense: __props.compact
                  }, null, _parent3, _scopeId2));
                  _push3(`</div></div>`);
                } else {
                  return [
                    isSelected.value ? withDirectives((openBlock(), createBlock("span", {
                      key: 0,
                      "aria-hidden": "true",
                      class: "pointer-events-none absolute inset-y-0 left-0 z-20 w-[2px] bg-ui-primary shadow-[0_0_16px_rgba(255,42,35,0.75)] origin-left",
                      initial: accentMotion.value.initial,
                      animate: accentMotion.value.animate,
                      transition: accentMotion.value.transition
                    }, null, 8, ["initial", "animate", "transition"])), [
                      [unref(vMotion)]
                    ]) : createCommentVNode("", true),
                    createVNode("div", { class: gridClass.value }, [
                      createVNode(_sfc_main$k, {
                        "image-url": __props.countdown.image_url,
                        title: __props.countdown.title,
                        subtitle: __props.countdown.summary,
                        compact: __props.compact
                      }, null, 8, ["image-url", "title", "subtitle", "compact"]),
                      createVNode("div", { class: timerClass.value }, [
                        createVNode(_sfc_main$j, {
                          "target-date": __props.countdown.timer.target_date,
                          compact: true,
                          dense: __props.compact
                        }, null, 8, ["target-date", "dense"])
                      ], 2)
                    ], 2)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$m), {
                role: "button",
                tabindex: "0",
                "aria-pressed": isSelected.value,
                ui: {
                  root: ["doomsday-card relative h-fit self-start min-w-0 overflow-hidden rounded-xl transition duration-200 hover:border-ui-primary/80 focus:outline-none focus:ring-2 focus:ring-ui-primary/50", isSelected.value ? "doomsday-glow border-ui-primary" : "", isPending.value ? "border-ui-primary/70 shadow-[0_0_28px_rgba(255,42,35,0.18)]" : ""].join(" "),
                  body: "p-0 min-w-0"
                },
                onClick: ($event) => emit("select", __props.countdown),
                onKeydown: [
                  withKeys(($event) => emit("select", __props.countdown), ["enter"]),
                  withKeys(withModifiers(($event) => emit("select", __props.countdown), ["prevent"]), ["space"])
                ]
              }, {
                default: withCtx(() => [
                  isSelected.value ? withDirectives((openBlock(), createBlock("span", {
                    key: 0,
                    "aria-hidden": "true",
                    class: "pointer-events-none absolute inset-y-0 left-0 z-20 w-[2px] bg-ui-primary shadow-[0_0_16px_rgba(255,42,35,0.75)] origin-left",
                    initial: accentMotion.value.initial,
                    animate: accentMotion.value.animate,
                    transition: accentMotion.value.transition
                  }, null, 8, ["initial", "animate", "transition"])), [
                    [unref(vMotion)]
                  ]) : createCommentVNode("", true),
                  createVNode("div", { class: gridClass.value }, [
                    createVNode(_sfc_main$k, {
                      "image-url": __props.countdown.image_url,
                      title: __props.countdown.title,
                      subtitle: __props.countdown.summary,
                      compact: __props.compact
                    }, null, 8, ["image-url", "title", "subtitle", "compact"]),
                    createVNode("div", { class: timerClass.value }, [
                      createVNode(_sfc_main$j, {
                        "target-date": __props.countdown.timer.target_date,
                        compact: true,
                        dense: __props.compact
                      }, null, 8, ["target-date", "dense"])
                    ], 2)
                  ], 2)
                ]),
                _: 1
              }, 8, ["aria-pressed", "ui", "onClick", "onKeydown"])
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
});
const _sfc_setup$i = _sfc_main$i.setup;
_sfc_main$i.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/CountdownCard.vue");
  return _sfc_setup$i ? _sfc_setup$i(props, ctx) : void 0;
};
const _sfc_main$h = /* @__PURE__ */ defineComponent({
  __name: "CountdownList",
  __ssrInlineRender: true,
  props: {
    countdowns: {},
    compact: { type: Boolean, default: false },
    selectedSlug: { default: null },
    pendingSlug: { default: null }
  },
  emits: ["select"],
  setup(__props, { emit: __emit }) {
    const emit = __emit;
    const reducedMotion = useDoomsdayReducedMotion();
    const cardMotion = computed(() => resolveMotionPreset(cardReveal, reducedMotion.value));
    function cardTransition(index) {
      return resolveMotionPreset(withMotionDelay(cardReveal, cardStaggerDelay(index)), reducedMotion.value).transition;
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "grid content-start items-start gap-3 sm:gap-4" }, _attrs))}><!--[-->`);
      ssrRenderList(__props.countdowns, (countdown, index) => {
        _push(ssrRenderComponent(unref(motion).div, {
          key: countdown.slug,
          class: "min-w-0",
          initial: cardMotion.value.initial,
          animate: cardMotion.value.animate,
          exit: cardMotion.value.exit,
          transition: cardTransition(index)
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(ssrRenderComponent(_sfc_main$i, {
                countdown,
                compact: __props.compact,
                "selected-slug": __props.selectedSlug,
                "pending-slug": __props.pendingSlug,
                onSelect: ($event) => emit("select", $event)
              }, null, _parent2, _scopeId));
            } else {
              return [
                createVNode(_sfc_main$i, {
                  countdown,
                  compact: __props.compact,
                  "selected-slug": __props.selectedSlug,
                  "pending-slug": __props.pendingSlug,
                  onSelect: ($event) => emit("select", $event)
                }, null, 8, ["countdown", "compact", "selected-slug", "pending-slug", "onSelect"])
              ];
            }
          }),
          _: 2
        }, _parent));
      });
      _push(`<!--]--></section>`);
    };
  }
});
const _sfc_setup$h = _sfc_main$h.setup;
_sfc_main$h.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/CountdownList.vue");
  return _sfc_setup$h ? _sfc_setup$h(props, ctx) : void 0;
};
const _sfc_main$g = /* @__PURE__ */ defineComponent({
  __name: "HeroSection",
  __ssrInlineRender: true,
  props: {
    hero: {}
  },
  setup(__props) {
    const reducedMotion = useDoomsdayReducedMotion();
    const lineMotion = computed(() => resolveMotionPreset(fadeIn, reducedMotion.value));
    const headlineMotion = computed(() => resolveMotionPreset(fadeUp, reducedMotion.value));
    const subtitleMotion = computed(() => resolveMotionPreset(withMotionDelay(fadeUp, 0.06), reducedMotion.value));
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "relative min-h-[430px] overflow-hidden bg-transparent lg:min-h-[460px]" }, _attrs))}><div class="absolute inset-0 bg-gradient-to-r to-transparent"></div><div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent"></div><div class="relative mx-auto flex min-h-[430px] max-w-[1760px] items-center px-4 py-12 sm:px-7 lg:min-h-[460px] lg:py-16"><div class="max-w-3xl">`);
      _push(ssrRenderComponent(unref(motion).div, {
        class: "mb-7 h-px w-24 bg-ui-primary/80",
        initial: lineMotion.value.initial,
        animate: lineMotion.value.animate,
        transition: lineMotion.value.transition
      }, null, _parent));
      _push(ssrRenderComponent(unref(motion).h1, {
        class: "doomsday-display text-3xl leading-tight text-white sm:text-5xl lg:text-6xl",
        initial: headlineMotion.value.initial,
        animate: headlineMotion.value.animate,
        transition: headlineMotion.value.transition
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`${ssrInterpolate(__props.hero.headline_prefix)}<br${_scopeId}><span class="text-white/80"${_scopeId}>${ssrInterpolate(__props.hero.headline_middle)}</span><span class="doomsday-red-text"${_scopeId}>${ssrInterpolate(__props.hero.headline_accent)}</span>`);
          } else {
            return [
              createTextVNode(toDisplayString(__props.hero.headline_prefix), 1),
              createVNode("br"),
              createVNode("span", { class: "text-white/80" }, toDisplayString(__props.hero.headline_middle), 1),
              createVNode("span", { class: "doomsday-red-text" }, toDisplayString(__props.hero.headline_accent), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(motion).p, {
        class: "mt-6 max-w-2xl text-base text-ui-muted-foreground sm:text-xl",
        initial: subtitleMotion.value.initial,
        animate: subtitleMotion.value.animate,
        transition: subtitleMotion.value.transition
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`${ssrInterpolate(__props.hero.subtitle)}`);
          } else {
            return [
              createTextVNode(toDisplayString(__props.hero.subtitle), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></div></section>`);
    };
  }
});
const _sfc_setup$g = _sfc_main$g.setup;
_sfc_main$g.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/HeroSection.vue");
  return _sfc_setup$g ? _sfc_setup$g(props, ctx) : void 0;
};
const _sfc_main$f = /* @__PURE__ */ defineComponent({
  __name: "DoomsdaySkeletonBlock",
  __ssrInlineRender: true,
  props: {
    variant: { default: "summary" }
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: "doomsday-card rounded-xl p-5 sm:p-6",
        role: "status",
        "aria-label": "Loading section"
      }, _attrs))}><div class="mb-5 flex items-center gap-3"><span class="h-3 w-3 rounded-full bg-ui-primary shadow-[0_0_18px_rgba(255,42,35,0.8)]"></span><span class="doomsday-display text-xs text-ui-primary">Loading</span></div>`);
      if (__props.variant === "chart") {
        _push(`<div class="space-y-4"><div class="doomsday-skeleton h-5 w-44 rounded"></div><div class="grid h-44 grid-cols-6 items-end gap-3 rounded-lg border border-white/10 bg-black/30 p-4"><!--[-->`);
        ssrRenderList(6, (index) => {
          _push(`<div class="doomsday-skeleton rounded-t" style="${ssrRenderStyle({ height: `${24 + index * 10}%` })}"></div>`);
        });
        _push(`<!--]--></div><div class="doomsday-skeleton h-4 w-56 rounded"></div></div>`);
      } else if (__props.variant === "list") {
        _push(`<div class="space-y-4"><!--[-->`);
        ssrRenderList(3, (index) => {
          _push(`<div class="rounded-lg border border-white/10 bg-white/[0.03] p-4"><div class="doomsday-skeleton h-4 w-3/4 rounded"></div><div class="doomsday-skeleton mt-3 h-3 w-full rounded"></div><div class="doomsday-skeleton mt-2 h-3 w-2/3 rounded"></div></div>`);
        });
        _push(`<!--]--></div>`);
      } else if (__props.variant === "initiatives") {
        _push(`<div class="grid grid-cols-1 gap-4"><!--[-->`);
        ssrRenderList(2, (index) => {
          _push(`<div class="rounded-lg border border-ui-primary/20 bg-ui-primary/5 p-4"><div class="doomsday-skeleton h-4 w-28 rounded"></div><div class="doomsday-skeleton mt-4 h-5 w-3/4 rounded"></div><div class="doomsday-skeleton mt-3 h-3 w-full rounded"></div><div class="doomsday-skeleton mt-2 h-3 w-4/5 rounded"></div></div>`);
        });
        _push(`<!--]--></div>`);
      } else {
        _push(`<div class="space-y-4"><div class="doomsday-skeleton h-5 w-40 rounded"></div><div class="doomsday-skeleton h-3 w-full rounded"></div><div class="doomsday-skeleton h-3 w-11/12 rounded"></div><div class="mt-5 grid grid-cols-3 gap-3"><!--[-->`);
        ssrRenderList(3, (index) => {
          _push(`<div class="doomsday-skeleton h-20 rounded-lg"></div>`);
        });
        _push(`<!--]--></div></div>`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$f = _sfc_main$f.setup;
_sfc_main$f.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/DoomsdaySkeletonBlock.vue");
  return _sfc_setup$f ? _sfc_setup$f(props, ctx) : void 0;
};
const _sfc_main$e = /* @__PURE__ */ defineComponent({
  __name: "MobileDetailSkeleton",
  __ssrInlineRender: true,
  emits: ["close"],
  setup(__props, { emit: __emit }) {
    const emit = __emit;
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "min-h-screen bg-black pb-24 lg:hidden" }, _attrs))}><header class="sticky top-0 z-40 flex h-16 items-center justify-between border-b border-white/10 bg-black/95 px-4 backdrop-blur-xl">`);
      _push(ssrRenderComponent(unref(Button), {
        variant: "link",
        size: "sm",
        icon: unref(ChevronLeft),
        "aria-label": "Back to countdown list",
        ui: { root: "p-0 text-ui-primary no-underline" },
        onClick: ($event) => emit("close")
      }, null, _parent));
      _push(`<div class="doomsday-skeleton h-4 w-44 rounded"></div><span class="h-7 w-7"></span></header><div class="relative h-[220px] overflow-hidden border-b border-white/10 bg-gradient-to-b from-ui-primary/20 to-black sm:h-[260px]"><div class="absolute inset-0 doomsday-skeleton opacity-40"></div></div><div class="mt-4 px-4">`);
      _push(ssrRenderComponent(unref(_sfc_main$m), { ui: { root: "doomsday-card doomsday-glow rounded-xl", body: "p-5" } }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$f, null, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$f)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></section>`);
    };
  }
});
const _sfc_setup$e = _sfc_main$e.setup;
_sfc_main$e.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/MobileDetailSkeleton.vue");
  return _sfc_setup$e ? _sfc_setup$e(props, ctx) : void 0;
};
const _sfc_main$d = /* @__PURE__ */ defineComponent({
  __name: "DoomsdaySectionError",
  __ssrInlineRender: true,
  props: {
    title: {}
  },
  emits: ["retry"],
  setup(__props, { emit: __emit }) {
    const emit = __emit;
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(_sfc_main$m), mergeProps({ ui: { root: "doomsday-card rounded-xl border-ui-primary/40", body: "p-6" } }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<p class="doomsday-display text-ui-primary"${_scopeId}>${ssrInterpolate(__props.title)}</p><p class="mt-3 text-sm text-ui-muted-foreground"${_scopeId}>The section could not be loaded. Retry the Inertia partial reload.</p>`);
            _push2(ssrRenderComponent(unref(Button), {
              class: "mt-5",
              variant: "secondary",
              size: "sm",
              icon: unref(RefreshCw),
              onClick: ($event) => emit("retry")
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Retry`);
                } else {
                  return [
                    createTextVNode("Retry")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode("p", { class: "doomsday-display text-ui-primary" }, toDisplayString(__props.title), 1),
              createVNode("p", { class: "mt-3 text-sm text-ui-muted-foreground" }, "The section could not be loaded. Retry the Inertia partial reload."),
              createVNode(unref(Button), {
                class: "mt-5",
                variant: "secondary",
                size: "sm",
                icon: unref(RefreshCw),
                onClick: ($event) => emit("retry")
              }, {
                default: withCtx(() => [
                  createTextVNode("Retry")
                ]),
                _: 1
              }, 8, ["icon", "onClick"])
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
});
const _sfc_setup$d = _sfc_main$d.setup;
_sfc_main$d.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/DoomsdaySectionError.vue");
  return _sfc_setup$d ? _sfc_setup$d(props, ctx) : void 0;
};
const _sfc_main$c = /* @__PURE__ */ defineComponent({
  __name: "ForecastsSection",
  __ssrInlineRender: true,
  props: {
    section: {},
    mobile: { type: Boolean, default: false }
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(_sfc_main$m), { ui: { root: "doomsday-card rounded-xl sm:col-span-2", body: "p-5 sm:p-6" } }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          var _a, _b;
          if (_push2) {
            _push2(`<h3 class="doomsday-display mb-4 text-white"${_scopeId}>${ssrInterpolate(unref(t)("projectionModel"))}</h3>`);
            if (__props.section.projection_chart) {
              _push2(ssrRenderComponent(_sfc_main$n, {
                payload: __props.section.projection_chart.payload,
                type: __props.section.projection_chart.type,
                sources: __props.section.projection_chart.sources,
                reasoning: __props.section.projection_chart.reasoning,
                mobile: __props.mobile
              }, null, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
            _push2(`<p class="mt-4 text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate((_a = __props.section.projection_chart) == null ? void 0 : _a.description)}</p>`);
          } else {
            return [
              createVNode("h3", { class: "doomsday-display mb-4 text-white" }, toDisplayString(unref(t)("projectionModel")), 1),
              __props.section.projection_chart ? (openBlock(), createBlock(_sfc_main$n, {
                key: 0,
                payload: __props.section.projection_chart.payload,
                type: __props.section.projection_chart.type,
                sources: __props.section.projection_chart.sources,
                reasoning: __props.section.projection_chart.reasoning,
                mobile: __props.mobile
              }, null, 8, ["payload", "type", "sources", "reasoning", "mobile"])) : createCommentVNode("", true),
              createVNode("p", { class: "mt-4 text-sm text-ui-muted-foreground" }, toDisplayString((_b = __props.section.projection_chart) == null ? void 0 : _b.description), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<!--[-->`);
      ssrRenderList(__props.section.projections, (projection) => {
        _push(ssrRenderComponent(unref(_sfc_main$m), {
          key: projection.type,
          ui: { root: "doomsday-card rounded-xl", body: "p-5" }
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<p class="doomsday-display text-xs text-ui-primary"${_scopeId}>${ssrInterpolate(projection.type)}</p><h4 class="mt-3 text-lg text-white"${_scopeId}>${ssrInterpolate(projection.title)}</h4><p class="mt-2 text-sm leading-relaxed text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(projection.summary)}</p><div class="mt-5 grid grid-cols-2 gap-3 text-sm"${_scopeId}><span class="rounded border border-white/10 bg-white/5 p-3"${_scopeId}>Confidence <strong class="block text-white"${_scopeId}>${ssrInterpolate(projection.confidence_score)}%</strong></span><span class="rounded border border-white/10 bg-white/5 p-3"${_scopeId}>Probability <strong class="block text-ui-primary"${_scopeId}>${ssrInterpolate(projection.probability_score)}%</strong></span></div>`);
            } else {
              return [
                createVNode("p", { class: "doomsday-display text-xs text-ui-primary" }, toDisplayString(projection.type), 1),
                createVNode("h4", { class: "mt-3 text-lg text-white" }, toDisplayString(projection.title), 1),
                createVNode("p", { class: "mt-2 text-sm leading-relaxed text-ui-muted-foreground" }, toDisplayString(projection.summary), 1),
                createVNode("div", { class: "mt-5 grid grid-cols-2 gap-3 text-sm" }, [
                  createVNode("span", { class: "rounded border border-white/10 bg-white/5 p-3" }, [
                    createTextVNode("Confidence "),
                    createVNode("strong", { class: "block text-white" }, toDisplayString(projection.confidence_score) + "%", 1)
                  ]),
                  createVNode("span", { class: "rounded border border-white/10 bg-white/5 p-3" }, [
                    createTextVNode("Probability "),
                    createVNode("strong", { class: "block text-ui-primary" }, toDisplayString(projection.probability_score) + "%", 1)
                  ])
                ])
              ];
            }
          }),
          _: 2
        }, _parent));
      });
      _push(`<!--]--><!--]-->`);
    };
  }
});
const _sfc_setup$c = _sfc_main$c.setup;
_sfc_main$c.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/ForecastsSection.vue");
  return _sfc_setup$c ? _sfc_setup$c(props, ctx) : void 0;
};
const _sfc_main$b = /* @__PURE__ */ defineComponent({
  __name: "ContentPreviewCard",
  __ssrInlineRender: true,
  props: {
    title: {},
    href: { default: null },
    excerpt: {},
    imageUrl: {},
    contentType: { default: "article" },
    externalProvider: { default: null },
    eyebrow: { default: null },
    metadata: { default: null },
    ctaLabel: {},
    variant: { default: "news" }
  },
  setup(__props) {
    const props = __props;
    const rootClass = computed(() => [
      "grid min-w-0 gap-4 rounded-lg border p-3 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary/70 sm:grid-cols-[132px_1fr_auto] sm:items-center",
      props.variant === "initiative" ? "border-ui-primary/20 bg-ui-primary/5 hover:border-ui-primary/60 hover:bg-ui-primary/[0.09]" : "border-white/10 bg-white/[0.03] hover:border-ui-primary/50 hover:bg-ui-primary/[0.07]"
    ]);
    const providerLabel = computed(() => {
      var _a;
      return ((_a = props.externalProvider) == null ? void 0 : _a.replaceAll("_", " ")) ?? null;
    });
    const isVideo = computed(() => props.contentType === "youtube_video");
    return (_ctx, _push, _parent, _attrs) => {
      ssrRenderVNode(_push, createVNode(resolveDynamicComponent(__props.href ? "a" : "article"), mergeProps({
        href: __props.href || void 0,
        target: __props.href ? "_blank" : void 0,
        rel: __props.href ? "noopener noreferrer" : void 0,
        class: rootClass.value
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$o), {
              src: __props.imageUrl,
              alt: __props.title,
              "aspect-ratio": "72%",
              rounded: "md",
              ui: { root: "min-w-0", image: "h-full w-full object-cover" }
            }, null, _parent2, _scopeId));
            _push2(`<div class="min-w-0"${_scopeId}><div class="flex flex-wrap items-center gap-2"${_scopeId}>`);
            if (__props.eyebrow) {
              _push2(`<span class="doomsday-display text-xs text-ui-primary"${_scopeId}>${ssrInterpolate(__props.eyebrow)}</span>`);
            } else {
              _push2(`<!---->`);
            }
            if (isVideo.value) {
              _push2(`<span class="rounded-full border border-red-400/40 bg-red-500/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-red-200"${_scopeId}>Video</span>`);
            } else {
              _push2(`<!---->`);
            }
            if (providerLabel.value) {
              _push2(`<span class="rounded-full border border-white/15 bg-white/5 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.14em] text-white/70"${_scopeId}>${ssrInterpolate(providerLabel.value)}</span>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><h4 class="${ssrRenderClass(["text-base font-semibold leading-snug text-white", __props.eyebrow || isVideo.value || providerLabel.value ? "mt-2" : ""])}"${_scopeId}>${ssrInterpolate(__props.title)}</h4><p class="mt-2 text-sm leading-relaxed text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(__props.excerpt)}</p>`);
            if (__props.metadata) {
              _push2(`<p class="mt-3 text-xs text-white/60"${_scopeId}>${ssrInterpolate(__props.metadata)}</p>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div>`);
            if (__props.href) {
              _push2(`<span class="inline-flex items-center gap-2 text-xs font-semibold text-ui-primary sm:justify-self-end"${_scopeId}>${ssrInterpolate(__props.ctaLabel)} `);
              _push2(ssrRenderComponent(unref(ExternalLink), {
                class: "h-4 w-4",
                "aria-hidden": "true"
              }, null, _parent2, _scopeId));
              _push2(`</span>`);
            } else {
              _push2(`<!---->`);
            }
          } else {
            return [
              createVNode(unref(_sfc_main$o), {
                src: __props.imageUrl,
                alt: __props.title,
                "aspect-ratio": "72%",
                rounded: "md",
                ui: { root: "min-w-0", image: "h-full w-full object-cover" }
              }, null, 8, ["src", "alt"]),
              createVNode("div", { class: "min-w-0" }, [
                createVNode("div", { class: "flex flex-wrap items-center gap-2" }, [
                  __props.eyebrow ? (openBlock(), createBlock("span", {
                    key: 0,
                    class: "doomsday-display text-xs text-ui-primary"
                  }, toDisplayString(__props.eyebrow), 1)) : createCommentVNode("", true),
                  isVideo.value ? (openBlock(), createBlock("span", {
                    key: 1,
                    class: "rounded-full border border-red-400/40 bg-red-500/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-red-200"
                  }, "Video")) : createCommentVNode("", true),
                  providerLabel.value ? (openBlock(), createBlock("span", {
                    key: 2,
                    class: "rounded-full border border-white/15 bg-white/5 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-[0.14em] text-white/70"
                  }, toDisplayString(providerLabel.value), 1)) : createCommentVNode("", true)
                ]),
                createVNode("h4", {
                  class: ["text-base font-semibold leading-snug text-white", __props.eyebrow || isVideo.value || providerLabel.value ? "mt-2" : ""]
                }, toDisplayString(__props.title), 3),
                createVNode("p", { class: "mt-2 text-sm leading-relaxed text-ui-muted-foreground" }, toDisplayString(__props.excerpt), 1),
                __props.metadata ? (openBlock(), createBlock("p", {
                  key: 0,
                  class: "mt-3 text-xs text-white/60"
                }, toDisplayString(__props.metadata), 1)) : createCommentVNode("", true)
              ]),
              __props.href ? (openBlock(), createBlock("span", {
                key: 0,
                class: "inline-flex items-center gap-2 text-xs font-semibold text-ui-primary sm:justify-self-end"
              }, [
                createTextVNode(toDisplayString(__props.ctaLabel) + " ", 1),
                createVNode(unref(ExternalLink), {
                  class: "h-4 w-4",
                  "aria-hidden": "true"
                })
              ])) : createCommentVNode("", true)
            ];
          }
        }),
        _: 1
      }), _parent);
    };
  }
});
const _sfc_setup$b = _sfc_main$b.setup;
_sfc_main$b.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/ContentPreviewCard.vue");
  return _sfc_setup$b ? _sfc_setup$b(props, ctx) : void 0;
};
const _sfc_main$a = /* @__PURE__ */ defineComponent({
  __name: "InitiativesSection",
  __ssrInlineRender: true,
  props: {
    section: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(_sfc_main$m), mergeProps({ ui: { root: "doomsday-card rounded-xl sm:col-span-2", body: "p-5 sm:p-6" } }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h3 class="doomsday-display mb-5 text-white"${_scopeId}>${ssrInterpolate(unref(t)("initiatives"))}</h3><div class="grid grid-cols-1 gap-4"${_scopeId}><!--[-->`);
            ssrRenderList(__props.section.initiatives, (item) => {
              _push2(ssrRenderComponent(_sfc_main$b, {
                key: `${item.title}-${item.url}`,
                title: item.title,
                href: item.url,
                excerpt: item.excerpt,
                "image-url": item.image_url,
                "content-type": item.content_type,
                "external-provider": item.external_provider,
                eyebrow: item.type,
                metadata: item.organization,
                "cta-label": item.cta_label || unref(t)("viewDetails"),
                variant: "initiative"
              }, null, _parent2, _scopeId));
            });
            _push2(`<!--]--></div>`);
          } else {
            return [
              createVNode("h3", { class: "doomsday-display mb-5 text-white" }, toDisplayString(unref(t)("initiatives")), 1),
              createVNode("div", { class: "grid grid-cols-1 gap-4" }, [
                (openBlock(true), createBlock(Fragment, null, renderList(__props.section.initiatives, (item) => {
                  return openBlock(), createBlock(_sfc_main$b, {
                    key: `${item.title}-${item.url}`,
                    title: item.title,
                    href: item.url,
                    excerpt: item.excerpt,
                    "image-url": item.image_url,
                    "content-type": item.content_type,
                    "external-provider": item.external_provider,
                    eyebrow: item.type,
                    metadata: item.organization,
                    "cta-label": item.cta_label || unref(t)("viewDetails"),
                    variant: "initiative"
                  }, null, 8, ["title", "href", "excerpt", "image-url", "content-type", "external-provider", "eyebrow", "metadata", "cta-label"]);
                }), 128))
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
});
const _sfc_setup$a = _sfc_main$a.setup;
_sfc_main$a.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/InitiativesSection.vue");
  return _sfc_setup$a ? _sfc_setup$a(props, ctx) : void 0;
};
const _sfc_main$9 = /* @__PURE__ */ defineComponent({
  __name: "NewsSection",
  __ssrInlineRender: true,
  props: {
    section: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(_sfc_main$m), mergeProps({ ui: { root: "doomsday-card rounded-xl sm:col-span-2", body: "p-5 sm:p-6" } }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h3 class="doomsday-display mb-5 text-white"${_scopeId}>${ssrInterpolate(unref(t)("news"))}</h3><div class="grid grid-cols-1 gap-4"${_scopeId}><!--[-->`);
            ssrRenderList(__props.section.news, (item) => {
              _push2(ssrRenderComponent(_sfc_main$b, {
                key: `${item.title}-${item.source_url ?? "no-link"}`,
                title: item.title,
                href: item.source_url,
                excerpt: item.excerpt,
                "image-url": item.image_url,
                "content-type": item.content_type,
                "external-provider": item.external_provider,
                metadata: item.source_name,
                "cta-label": unref(t)("viewDetails"),
                variant: "news"
              }, null, _parent2, _scopeId));
            });
            _push2(`<!--]--></div>`);
          } else {
            return [
              createVNode("h3", { class: "doomsday-display mb-5 text-white" }, toDisplayString(unref(t)("news")), 1),
              createVNode("div", { class: "grid grid-cols-1 gap-4" }, [
                (openBlock(true), createBlock(Fragment, null, renderList(__props.section.news, (item) => {
                  return openBlock(), createBlock(_sfc_main$b, {
                    key: `${item.title}-${item.source_url ?? "no-link"}`,
                    title: item.title,
                    href: item.source_url,
                    excerpt: item.excerpt,
                    "image-url": item.image_url,
                    "content-type": item.content_type,
                    "external-provider": item.external_provider,
                    metadata: item.source_name,
                    "cta-label": unref(t)("viewDetails"),
                    variant: "news"
                  }, null, 8, ["title", "href", "excerpt", "image-url", "content-type", "external-provider", "metadata", "cta-label"]);
                }), 128))
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
});
const _sfc_setup$9 = _sfc_main$9.setup;
_sfc_main$9.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/NewsSection.vue");
  return _sfc_setup$9 ? _sfc_setup$9(props, ctx) : void 0;
};
const _sfc_main$8 = /* @__PURE__ */ defineComponent({
  __name: "StatisticsSection",
  __ssrInlineRender: true,
  props: {
    section: {},
    mobile: { type: Boolean, default: false }
  },
  setup(__props) {
    const props = __props;
    function isRecord(value) {
      return typeof value === "object" && value !== null && !Array.isArray(value);
    }
    function kpiItems(visualization) {
      const payload = visualization.payload;
      const items = isRecord(payload) && Array.isArray(payload.items) ? payload.items : [];
      return items.filter(isRecord).map((item) => ({
        label: String(item.label ?? ""),
        value: String(item.value ?? "")
      })).filter((item) => item.label !== "" && item.value !== "");
    }
    const keyIndicators = computed(() => props.section.visualizations.find((item) => item.key === "key_indicators") ?? null);
    const otherVisualizations = computed(() => props.section.visualizations.filter((item) => item.key !== "key_indicators"));
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(_sfc_main$p, { visualization: keyIndicators.value }, null, _parent));
      _push(`<!--[-->`);
      ssrRenderList(otherVisualizations.value, (visualization) => {
        _push(ssrRenderComponent(unref(_sfc_main$m), {
          key: visualization.key,
          ui: { root: "doomsday-card min-w-0 rounded-xl", body: "overflow-visible p-5 sm:p-6" }
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<h3 class="doomsday-display mb-4 text-white"${_scopeId}>${ssrInterpolate(visualization.title || unref(t)("statistics"))}</h3>`);
              if (visualization.type === "line" || visualization.type === "area" || visualization.type === "bar") {
                _push2(ssrRenderComponent(_sfc_main$n, {
                  payload: visualization.payload,
                  type: visualization.type,
                  sources: visualization.sources,
                  reasoning: visualization.reasoning,
                  mobile: __props.mobile
                }, null, _parent2, _scopeId));
              } else if (visualization.type === "kpi") {
                _push2(`<div class="space-y-4"${_scopeId}><div class="grid gap-3 sm:grid-cols-2"${_scopeId}><!--[-->`);
                ssrRenderList(kpiItems(visualization), (item) => {
                  _push2(`<div class="rounded-lg border border-white/10 bg-black/25 p-4"${_scopeId}><p class="text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(item.label)}</p><p class="mt-2 text-xl text-white"${_scopeId}>${ssrInterpolate(item.value)}</p></div>`);
                });
                _push2(`<!--]--></div>`);
                _push2(ssrRenderComponent(_sfc_main$q, {
                  sources: visualization.sources,
                  reasoning: visualization.reasoning
                }, null, _parent2, _scopeId));
                _push2(`</div>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`<p class="mt-4 text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(visualization.description)}</p>`);
            } else {
              return [
                createVNode("h3", { class: "doomsday-display mb-4 text-white" }, toDisplayString(visualization.title || unref(t)("statistics")), 1),
                visualization.type === "line" || visualization.type === "area" || visualization.type === "bar" ? (openBlock(), createBlock(_sfc_main$n, {
                  key: 0,
                  payload: visualization.payload,
                  type: visualization.type,
                  sources: visualization.sources,
                  reasoning: visualization.reasoning,
                  mobile: __props.mobile
                }, null, 8, ["payload", "type", "sources", "reasoning", "mobile"])) : visualization.type === "kpi" ? (openBlock(), createBlock("div", {
                  key: 1,
                  class: "space-y-4"
                }, [
                  createVNode("div", { class: "grid gap-3 sm:grid-cols-2" }, [
                    (openBlock(true), createBlock(Fragment, null, renderList(kpiItems(visualization), (item) => {
                      return openBlock(), createBlock("div", {
                        key: item.label,
                        class: "rounded-lg border border-white/10 bg-black/25 p-4"
                      }, [
                        createVNode("p", { class: "text-sm text-ui-muted-foreground" }, toDisplayString(item.label), 1),
                        createVNode("p", { class: "mt-2 text-xl text-white" }, toDisplayString(item.value), 1)
                      ]);
                    }), 128))
                  ]),
                  createVNode(_sfc_main$q, {
                    sources: visualization.sources,
                    reasoning: visualization.reasoning
                  }, null, 8, ["sources", "reasoning"])
                ])) : createCommentVNode("", true),
                createVNode("p", { class: "mt-4 text-sm text-ui-muted-foreground" }, toDisplayString(visualization.description), 1)
              ];
            }
          }),
          _: 2
        }, _parent));
      });
      _push(`<!--]--><!--]-->`);
    };
  }
});
const _sfc_setup$8 = _sfc_main$8.setup;
_sfc_main$8.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/StatisticsSection.vue");
  return _sfc_setup$8 ? _sfc_setup$8(props, ctx) : void 0;
};
const sectionRouteByKey = {
  forecasts: "countdowns.data.forecasts",
  statistics: "countdowns.data.statistics",
  news: "countdowns.data.news",
  initiatives: "countdowns.data.initiatives"
};
const lazyKeys = ["forecasts", "statistics", "news", "initiatives"];
function isLazySectionKey(value) {
  return lazyKeys.includes(value);
}
function useDoomsdayLazySections(countdownSlug, currentLocale, initialSections) {
  const loaded = {
    forecasts: shallowRef(null),
    statistics: shallowRef(null),
    news: shallowRef(null),
    initiatives: shallowRef(null)
  };
  const loading = reactive({
    forecasts: false,
    statistics: false,
    news: false,
    initiatives: false
  });
  const errors = reactive({
    forecasts: false,
    statistics: false,
    news: false,
    initiatives: false
  });
  const cache = /* @__PURE__ */ new Map();
  const forecastSection = computed(() => currentSection("forecasts"));
  const statisticsSection = computed(() => currentSection("statistics"));
  const newsSection = computed(() => currentSection("news"));
  const initiativesSection = computed(() => currentSection("initiatives"));
  async function loadSection(key) {
    if (currentSection(key) !== null || loading[key]) {
      return;
    }
    const requestedSlug = countdownSlug.value;
    const requestedLocale = currentLocale.value;
    const keyName = cacheKey(requestedSlug, requestedLocale, key);
    const cached = cache.get(keyName);
    errors[key] = false;
    if (cached !== void 0) {
      loaded[key].value = cached;
      return;
    }
    loading[key] = true;
    try {
      const response = await axios.get(route(sectionRouteByKey[key], {
        slug: requestedSlug,
        lang: requestedLocale
      }));
      if (countdownSlug.value === requestedSlug && currentLocale.value === requestedLocale) {
        cache.set(keyName, response.data.data);
        loaded[key].value = response.data.data;
      }
    } catch {
      if (countdownSlug.value === requestedSlug) {
        errors[key] = true;
      }
    } finally {
      if (countdownSlug.value === requestedSlug) {
        loading[key] = false;
      }
    }
  }
  function reset() {
    lazyKeys.forEach((key) => {
      loading[key] = false;
      errors[key] = false;
    });
  }
  function currentSection(key) {
    const localSection = loaded[key].value;
    if ((localSection == null ? void 0 : localSection.countdown_slug) === countdownSlug.value) {
      return localSection;
    }
    const initialSection = initialSections[key].value;
    return (initialSection == null ? void 0 : initialSection.countdown_slug) === countdownSlug.value ? initialSection : null;
  }
  function cacheKey(slug, locale, key) {
    return `${slug}:${locale}:${key}`;
  }
  return {
    forecastSection,
    statisticsSection,
    newsSection,
    initiativesSection,
    loadSection,
    reset,
    isLoading: (key) => loading[key],
    hasError: (key) => errors[key]
  };
}
const _sfc_main$7 = /* @__PURE__ */ defineComponent({
  __name: "MobileDetailView",
  __ssrInlineRender: true,
  props: {
    countdown: {},
    currentLocale: {},
    forecastSection: {},
    statisticsSection: {},
    newsSection: {},
    initiativesSection: {}
  },
  emits: ["close"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const activeTab = ref("overview");
    const isOverviewExpanded = ref(false);
    const touchOrigin = ref(null);
    const tabs = computed(() => [
      { value: "overview", label: t("overview") },
      { value: "forecasts", label: t("predictions") },
      { value: "statistics", label: t("statistics") },
      { value: "news", label: t("news") },
      { value: "initiatives", label: t("initiatives") }
    ]);
    const activeTabIndex = computed(() => tabs.value.findIndex((tab) => tab.value === activeTab.value));
    const countdownSlug = computed(() => props.countdown.slug);
    const currentLocale = computed(() => props.currentLocale);
    const lazy = useDoomsdayLazySections(countdownSlug, currentLocale, {
      forecasts: computed(() => props.forecastSection),
      statistics: computed(() => props.statisticsSection),
      news: computed(() => props.newsSection),
      initiatives: computed(() => props.initiativesSection)
    });
    const forecastSection = lazy.forecastSection;
    const statisticsSection = lazy.statisticsSection;
    const newsSection = lazy.newsSection;
    const initiativesSection = lazy.initiativesSection;
    const reducedMotion = useDoomsdayReducedMotion();
    const panelMotion = computed(() => resolveMotionPreset(panelReveal, reducedMotion.value));
    function activateTab(value) {
      activeTab.value = value;
      if (isLazySectionKey(value)) {
        void lazy.loadSection(value);
      }
    }
    function handleTouchStart(event) {
      if (event.touches.length !== 1) {
        touchOrigin.value = null;
        return;
      }
      const touch = event.touches.item(0);
      touchOrigin.value = touch ? { x: touch.clientX, y: touch.clientY } : null;
    }
    function handleTouchEnd(event) {
      const origin = touchOrigin.value;
      const touch = event.changedTouches.item(0);
      touchOrigin.value = null;
      if (!origin || !touch) {
        return;
      }
      const horizontalDistance = origin.x - touch.clientX;
      const verticalDistance = origin.y - touch.clientY;
      const isHorizontalSwipe = Math.abs(horizontalDistance) >= 56 && Math.abs(horizontalDistance) > Math.abs(verticalDistance) * 1.25;
      if (!isHorizontalSwipe) {
        return;
      }
      const nextIndex = activeTabIndex.value + (horizontalDistance > 0 ? 1 : -1);
      const nextTab = tabs.value[nextIndex];
      if (nextTab) {
        activateTab(nextTab.value);
      }
    }
    function resetTouchGesture() {
      touchOrigin.value = null;
    }
    watch(() => `${props.countdown.slug}:${props.currentLocale}`, () => {
      activeTab.value = "overview";
      isOverviewExpanded.value = false;
      resetTouchGesture();
      lazy.reset();
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(motion).section, mergeProps({
        class: "min-h-screen overflow-x-hidden bg-black pb-24 lg:hidden",
        initial: panelMotion.value.initial,
        animate: panelMotion.value.animate,
        transition: panelMotion.value.transition
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<header class="sticky top-0 z-40 flex h-16 items-center justify-between border-b border-white/10 bg-black/95 px-4 backdrop-blur-xl"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Button), {
              variant: "link",
              size: "sm",
              icon: unref(ChevronLeft),
              "aria-label": "Back to countdown list",
              ui: { root: "p-0 text-ui-primary no-underline" },
              onClick: ($event) => emit("close")
            }, null, _parent2, _scopeId));
            _push2(`<h1 class="doomsday-display max-w-[58vw] truncate text-center text-lg text-white"${_scopeId}>${ssrInterpolate(__props.countdown.title)}</h1><div class="flex items-center gap-3"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Button), {
              variant: "link",
              size: "sm",
              icon: unref(Share2),
              "aria-label": "Share selected countdown",
              ui: { root: "p-0 text-ui-primary no-underline" }
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Button), {
              variant: "link",
              size: "sm",
              icon: unref(X),
              "aria-label": "Close detail",
              ui: { root: "p-0 text-ui-primary no-underline" },
              onClick: ($event) => emit("close")
            }, null, _parent2, _scopeId));
            _push2(`</div></header><div class="relative h-[220px] overflow-hidden border-b border-white/10 sm:h-[260px]"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$l, {
              src: __props.countdown.image_url,
              alt: __props.countdown.title,
              sizes: "100vw",
              media: "(max-width: 1023px)",
              "inactive-media": "(min-width: 1024px)",
              loading: "eager",
              "fetch-priority": "high",
              "picture-class": "block h-full w-full",
              "img-class": "h-full w-full object-cover object-center sm:object-[center_35%]"
            }, null, _parent2, _scopeId));
            _push2(`<div class="absolute inset-0 bg-gradient-to-b from-black/10 via-transparent to-black/70"${_scopeId}></div></div><div class="mt-4 px-4"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$m), { ui: { root: "doomsday-card doomsday-glow rounded-xl", body: "p-5 text-center" } }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="flex justify-center"${_scopeId2}>`);
                  _push3(ssrRenderComponent(_sfc_main$j, {
                    "target-date": __props.countdown.timer.target_date
                  }, null, _parent3, _scopeId2));
                  _push3(`</div><p class="mt-4 text-xs text-ui-muted-foreground"${_scopeId2}>${ssrInterpolate(__props.countdown.summary)}</p>`);
                } else {
                  return [
                    createVNode("div", { class: "flex justify-center" }, [
                      createVNode(_sfc_main$j, {
                        "target-date": __props.countdown.timer.target_date
                      }, null, 8, ["target-date"])
                    ]),
                    createVNode("p", { class: "mt-4 text-xs text-ui-muted-foreground" }, toDisplayString(__props.countdown.summary), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</div><div class="sticky top-16 z-30 mt-5 grid w-full grid-cols-5 border-b border-white/10 bg-black/95 px-1 backdrop-blur-xl" role="tablist"${ssrRenderAttr("aria-label", unref(t)("analysis"))}${_scopeId}><!--[-->`);
            ssrRenderList(tabs.value, (tab) => {
              _push2(ssrRenderComponent(unref(Button), {
                id: `mobile-tab-${tab.value}`,
                key: tab.value,
                variant: "link",
                role: "tab",
                "aria-selected": activeTab.value === tab.value,
                "aria-controls": `mobile-panel-${tab.value}`,
                ui: { root: ["doomsday-display min-w-0 w-full justify-center whitespace-normal rounded-none border-b-2 px-1 py-3 text-[10px] leading-tight no-underline outline-none ring-0 shadow-none focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0 active:ring-0 active:outline-none sm:text-xs", activeTab.value === tab.value ? "border-ui-primary text-ui-primary" : "border-transparent text-ui-muted-foreground"].join(" ") },
                onClick: ($event) => activateTab(tab.value)
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(tab.label)}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(tab.label), 1)
                    ];
                  }
                }),
                _: 2
              }, _parent2, _scopeId));
            });
            _push2(`<!--]--></div><div${ssrRenderAttr("id", `mobile-panel-${activeTab.value}`)} class="grid touch-pan-y gap-4 overflow-x-hidden px-4 py-5" role="tabpanel"${ssrRenderAttr("aria-labelledby", `mobile-tab-${activeTab.value}`)}${_scopeId}>`);
            if (activeTab.value === "overview") {
              _push2(ssrRenderComponent(unref(_sfc_main$m), { ui: { root: "doomsday-card rounded-xl", body: "p-5" } }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  var _a, _b, _c, _d;
                  if (_push3) {
                    _push3(`<h2 class="doomsday-display mb-4 text-lg text-white"${_scopeId2}>${ssrInterpolate(unref(t)("summary"))}</h2><p class="${ssrRenderClass(["leading-relaxed text-ui-muted-foreground", isOverviewExpanded.value ? "" : "line-clamp-5"])}"${_scopeId2}>${ssrInterpolate(__props.countdown.description)}</p><div class="mt-5 grid grid-cols-[repeat(auto-fit,minmax(96px,1fr))] gap-2 text-xs"${_scopeId2}><div class="rounded-lg border border-white/10 bg-white/5 p-3"${_scopeId2}><span class="text-ui-muted-foreground"${_scopeId2}>Confidence</span><strong class="block text-lg text-white"${_scopeId2}>${ssrInterpolate(((_a = __props.countdown.main_projection) == null ? void 0 : _a.confidence_score) ?? 0)}%</strong></div><div class="rounded-lg border border-white/10 bg-white/5 p-3"${_scopeId2}><span class="text-ui-muted-foreground"${_scopeId2}>Trend</span><strong class="block text-ui-primary"${_scopeId2}>${ssrInterpolate((_b = __props.countdown.main_projection) == null ? void 0 : _b.trend)}</strong></div><div class="rounded-lg border border-white/10 bg-white/5 p-3"${_scopeId2}><span class="text-ui-muted-foreground"${_scopeId2}>Risk</span><strong class="block text-ui-primary"${_scopeId2}>${ssrInterpolate(__props.countdown.severity)}</strong></div></div>`);
                    _push3(ssrRenderComponent(unref(Button), {
                      variant: "link",
                      size: "sm",
                      icon: isOverviewExpanded.value ? unref(ChevronUp) : unref(ChevronDown),
                      "icon-position": "right",
                      "aria-expanded": isOverviewExpanded.value,
                      ui: { root: "mt-5 p-0 text-ui-primary no-underline" },
                      onClick: ($event) => isOverviewExpanded.value = !isOverviewExpanded.value
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(unref(t)(isOverviewExpanded.value ? "readLess" : "readMore"))}`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(unref(t)(isOverviewExpanded.value ? "readLess" : "readMore")), 1)
                          ];
                        }
                      }),
                      _: 1
                    }, _parent3, _scopeId2));
                  } else {
                    return [
                      createVNode("h2", { class: "doomsday-display mb-4 text-lg text-white" }, toDisplayString(unref(t)("summary")), 1),
                      createVNode("p", {
                        class: ["leading-relaxed text-ui-muted-foreground", isOverviewExpanded.value ? "" : "line-clamp-5"]
                      }, toDisplayString(__props.countdown.description), 3),
                      createVNode("div", { class: "mt-5 grid grid-cols-[repeat(auto-fit,minmax(96px,1fr))] gap-2 text-xs" }, [
                        createVNode("div", { class: "rounded-lg border border-white/10 bg-white/5 p-3" }, [
                          createVNode("span", { class: "text-ui-muted-foreground" }, "Confidence"),
                          createVNode("strong", { class: "block text-lg text-white" }, toDisplayString(((_c = __props.countdown.main_projection) == null ? void 0 : _c.confidence_score) ?? 0) + "%", 1)
                        ]),
                        createVNode("div", { class: "rounded-lg border border-white/10 bg-white/5 p-3" }, [
                          createVNode("span", { class: "text-ui-muted-foreground" }, "Trend"),
                          createVNode("strong", { class: "block text-ui-primary" }, toDisplayString((_d = __props.countdown.main_projection) == null ? void 0 : _d.trend), 1)
                        ]),
                        createVNode("div", { class: "rounded-lg border border-white/10 bg-white/5 p-3" }, [
                          createVNode("span", { class: "text-ui-muted-foreground" }, "Risk"),
                          createVNode("strong", { class: "block text-ui-primary" }, toDisplayString(__props.countdown.severity), 1)
                        ])
                      ]),
                      createVNode(unref(Button), {
                        variant: "link",
                        size: "sm",
                        icon: isOverviewExpanded.value ? unref(ChevronUp) : unref(ChevronDown),
                        "icon-position": "right",
                        "aria-expanded": isOverviewExpanded.value,
                        ui: { root: "mt-5 p-0 text-ui-primary no-underline" },
                        onClick: ($event) => isOverviewExpanded.value = !isOverviewExpanded.value
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(unref(t)(isOverviewExpanded.value ? "readLess" : "readMore")), 1)
                        ]),
                        _: 1
                      }, 8, ["icon", "aria-expanded", "onClick"])
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
            } else if (activeTab.value === "forecasts") {
              _push2(`<!--[-->`);
              if (unref(forecastSection)) {
                _push2(ssrRenderComponent(_sfc_main$c, {
                  section: unref(forecastSection),
                  mobile: ""
                }, null, _parent2, _scopeId));
              } else if (unref(lazy).hasError("forecasts")) {
                _push2(ssrRenderComponent(_sfc_main$d, {
                  title: "Forecasts unavailable",
                  onRetry: ($event) => unref(lazy).loadSection("forecasts")
                }, null, _parent2, _scopeId));
              } else {
                _push2(ssrRenderComponent(_sfc_main$f, { variant: "chart" }, null, _parent2, _scopeId));
              }
              _push2(`<!--]-->`);
            } else if (activeTab.value === "statistics") {
              _push2(`<!--[-->`);
              if (unref(statisticsSection)) {
                _push2(ssrRenderComponent(_sfc_main$8, {
                  section: unref(statisticsSection),
                  mobile: ""
                }, null, _parent2, _scopeId));
              } else if (unref(lazy).hasError("statistics")) {
                _push2(ssrRenderComponent(_sfc_main$d, {
                  title: "Statistics unavailable",
                  onRetry: ($event) => unref(lazy).loadSection("statistics")
                }, null, _parent2, _scopeId));
              } else {
                _push2(ssrRenderComponent(_sfc_main$f, { variant: "summary" }, null, _parent2, _scopeId));
              }
              _push2(`<!--]-->`);
            } else if (activeTab.value === "news") {
              _push2(`<!--[-->`);
              if (unref(newsSection)) {
                _push2(ssrRenderComponent(_sfc_main$9, { section: unref(newsSection) }, null, _parent2, _scopeId));
              } else if (unref(lazy).hasError("news")) {
                _push2(ssrRenderComponent(_sfc_main$d, {
                  title: "News unavailable",
                  onRetry: ($event) => unref(lazy).loadSection("news")
                }, null, _parent2, _scopeId));
              } else {
                _push2(ssrRenderComponent(_sfc_main$f, { variant: "list" }, null, _parent2, _scopeId));
              }
              _push2(`<!--]-->`);
            } else if (activeTab.value === "initiatives") {
              _push2(`<!--[-->`);
              if (unref(initiativesSection)) {
                _push2(ssrRenderComponent(_sfc_main$a, { section: unref(initiativesSection) }, null, _parent2, _scopeId));
              } else if (unref(lazy).hasError("initiatives")) {
                _push2(ssrRenderComponent(_sfc_main$d, {
                  title: "Initiatives unavailable",
                  onRetry: ($event) => unref(lazy).loadSection("initiatives")
                }, null, _parent2, _scopeId));
              } else {
                _push2(ssrRenderComponent(_sfc_main$f, { variant: "initiatives" }, null, _parent2, _scopeId));
              }
              _push2(`<!--]-->`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><nav class="fixed inset-x-0 bottom-0 z-40 grid grid-cols-4 border-t border-white/10 bg-black/95 px-3 pb-5 pt-3 text-center text-[11px] text-ui-muted-foreground backdrop-blur-xl"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Button), {
              variant: "link",
              size: "sm",
              icon: unref(Sparkles),
              ui: { root: "grid justify-items-center gap-1 p-0 text-ui-primary no-underline" },
              onClick: ($event) => activateTab("overview")
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(unref(t)("overview"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(unref(t)("overview")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Button), {
              variant: "link",
              size: "sm",
              icon: unref(Newspaper),
              ui: { root: "grid justify-items-center gap-1 p-0 text-ui-muted-foreground no-underline" },
              onClick: ($event) => activateTab("news")
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(unref(t)("news"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(unref(t)("news")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`<a${ssrRenderAttr("href", `/about?lang=${currentLocale.value}`)} class="grid justify-items-center gap-1"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Folder), { class: "h-5 w-5" }, null, _parent2, _scopeId));
            _push2(`${ssrInterpolate(unref(t)("resources"))}</a>`);
            _push2(ssrRenderComponent(unref(Button), {
              variant: "link",
              size: "sm",
              icon: unref(FileText),
              ui: { root: "grid justify-items-center gap-1 p-0 text-ui-muted-foreground no-underline" },
              onClick: ($event) => activateTab("forecasts")
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(unref(t)("analysis"))}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(unref(t)("analysis")), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</nav>`);
          } else {
            return [
              createVNode("header", { class: "sticky top-0 z-40 flex h-16 items-center justify-between border-b border-white/10 bg-black/95 px-4 backdrop-blur-xl" }, [
                createVNode(unref(Button), {
                  variant: "link",
                  size: "sm",
                  icon: unref(ChevronLeft),
                  "aria-label": "Back to countdown list",
                  ui: { root: "p-0 text-ui-primary no-underline" },
                  onClick: ($event) => emit("close")
                }, null, 8, ["icon", "onClick"]),
                createVNode("h1", { class: "doomsday-display max-w-[58vw] truncate text-center text-lg text-white" }, toDisplayString(__props.countdown.title), 1),
                createVNode("div", { class: "flex items-center gap-3" }, [
                  createVNode(unref(Button), {
                    variant: "link",
                    size: "sm",
                    icon: unref(Share2),
                    "aria-label": "Share selected countdown",
                    ui: { root: "p-0 text-ui-primary no-underline" }
                  }, null, 8, ["icon"]),
                  createVNode(unref(Button), {
                    variant: "link",
                    size: "sm",
                    icon: unref(X),
                    "aria-label": "Close detail",
                    ui: { root: "p-0 text-ui-primary no-underline" },
                    onClick: ($event) => emit("close")
                  }, null, 8, ["icon", "onClick"])
                ])
              ]),
              createVNode("div", { class: "relative h-[220px] overflow-hidden border-b border-white/10 sm:h-[260px]" }, [
                createVNode(_sfc_main$l, {
                  src: __props.countdown.image_url,
                  alt: __props.countdown.title,
                  sizes: "100vw",
                  media: "(max-width: 1023px)",
                  "inactive-media": "(min-width: 1024px)",
                  loading: "eager",
                  "fetch-priority": "high",
                  "picture-class": "block h-full w-full",
                  "img-class": "h-full w-full object-cover object-center sm:object-[center_35%]"
                }, null, 8, ["src", "alt"]),
                createVNode("div", { class: "absolute inset-0 bg-gradient-to-b from-black/10 via-transparent to-black/70" })
              ]),
              createVNode("div", { class: "mt-4 px-4" }, [
                createVNode(unref(_sfc_main$m), { ui: { root: "doomsday-card doomsday-glow rounded-xl", body: "p-5 text-center" } }, {
                  default: withCtx(() => [
                    createVNode("div", { class: "flex justify-center" }, [
                      createVNode(_sfc_main$j, {
                        "target-date": __props.countdown.timer.target_date
                      }, null, 8, ["target-date"])
                    ]),
                    createVNode("p", { class: "mt-4 text-xs text-ui-muted-foreground" }, toDisplayString(__props.countdown.summary), 1)
                  ]),
                  _: 1
                })
              ]),
              createVNode("div", {
                class: "sticky top-16 z-30 mt-5 grid w-full grid-cols-5 border-b border-white/10 bg-black/95 px-1 backdrop-blur-xl",
                role: "tablist",
                "aria-label": unref(t)("analysis")
              }, [
                (openBlock(true), createBlock(Fragment, null, renderList(tabs.value, (tab) => {
                  return openBlock(), createBlock(unref(Button), {
                    id: `mobile-tab-${tab.value}`,
                    key: tab.value,
                    variant: "link",
                    role: "tab",
                    "aria-selected": activeTab.value === tab.value,
                    "aria-controls": `mobile-panel-${tab.value}`,
                    ui: { root: ["doomsday-display min-w-0 w-full justify-center whitespace-normal rounded-none border-b-2 px-1 py-3 text-[10px] leading-tight no-underline outline-none ring-0 shadow-none focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0 active:ring-0 active:outline-none sm:text-xs", activeTab.value === tab.value ? "border-ui-primary text-ui-primary" : "border-transparent text-ui-muted-foreground"].join(" ") },
                    onClick: ($event) => activateTab(tab.value)
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(tab.label), 1)
                    ]),
                    _: 2
                  }, 1032, ["id", "aria-selected", "aria-controls", "ui", "onClick"]);
                }), 128))
              ], 8, ["aria-label"]),
              createVNode("div", {
                id: `mobile-panel-${activeTab.value}`,
                class: "grid touch-pan-y gap-4 overflow-x-hidden px-4 py-5",
                role: "tabpanel",
                "aria-labelledby": `mobile-tab-${activeTab.value}`,
                onTouchstartPassive: handleTouchStart,
                onTouchendPassive: handleTouchEnd,
                onTouchcancel: resetTouchGesture
              }, [
                activeTab.value === "overview" ? (openBlock(), createBlock(unref(_sfc_main$m), {
                  key: 0,
                  ui: { root: "doomsday-card rounded-xl", body: "p-5" }
                }, {
                  default: withCtx(() => {
                    var _a, _b;
                    return [
                      createVNode("h2", { class: "doomsday-display mb-4 text-lg text-white" }, toDisplayString(unref(t)("summary")), 1),
                      createVNode("p", {
                        class: ["leading-relaxed text-ui-muted-foreground", isOverviewExpanded.value ? "" : "line-clamp-5"]
                      }, toDisplayString(__props.countdown.description), 3),
                      createVNode("div", { class: "mt-5 grid grid-cols-[repeat(auto-fit,minmax(96px,1fr))] gap-2 text-xs" }, [
                        createVNode("div", { class: "rounded-lg border border-white/10 bg-white/5 p-3" }, [
                          createVNode("span", { class: "text-ui-muted-foreground" }, "Confidence"),
                          createVNode("strong", { class: "block text-lg text-white" }, toDisplayString(((_a = __props.countdown.main_projection) == null ? void 0 : _a.confidence_score) ?? 0) + "%", 1)
                        ]),
                        createVNode("div", { class: "rounded-lg border border-white/10 bg-white/5 p-3" }, [
                          createVNode("span", { class: "text-ui-muted-foreground" }, "Trend"),
                          createVNode("strong", { class: "block text-ui-primary" }, toDisplayString((_b = __props.countdown.main_projection) == null ? void 0 : _b.trend), 1)
                        ]),
                        createVNode("div", { class: "rounded-lg border border-white/10 bg-white/5 p-3" }, [
                          createVNode("span", { class: "text-ui-muted-foreground" }, "Risk"),
                          createVNode("strong", { class: "block text-ui-primary" }, toDisplayString(__props.countdown.severity), 1)
                        ])
                      ]),
                      createVNode(unref(Button), {
                        variant: "link",
                        size: "sm",
                        icon: isOverviewExpanded.value ? unref(ChevronUp) : unref(ChevronDown),
                        "icon-position": "right",
                        "aria-expanded": isOverviewExpanded.value,
                        ui: { root: "mt-5 p-0 text-ui-primary no-underline" },
                        onClick: ($event) => isOverviewExpanded.value = !isOverviewExpanded.value
                      }, {
                        default: withCtx(() => [
                          createTextVNode(toDisplayString(unref(t)(isOverviewExpanded.value ? "readLess" : "readMore")), 1)
                        ]),
                        _: 1
                      }, 8, ["icon", "aria-expanded", "onClick"])
                    ];
                  }),
                  _: 1
                })) : activeTab.value === "forecasts" ? (openBlock(), createBlock(Fragment, { key: 1 }, [
                  unref(forecastSection) ? (openBlock(), createBlock(_sfc_main$c, {
                    key: 0,
                    section: unref(forecastSection),
                    mobile: ""
                  }, null, 8, ["section"])) : unref(lazy).hasError("forecasts") ? (openBlock(), createBlock(_sfc_main$d, {
                    key: 1,
                    title: "Forecasts unavailable",
                    onRetry: ($event) => unref(lazy).loadSection("forecasts")
                  }, null, 8, ["onRetry"])) : (openBlock(), createBlock(_sfc_main$f, {
                    key: 2,
                    variant: "chart"
                  }))
                ], 64)) : activeTab.value === "statistics" ? (openBlock(), createBlock(Fragment, { key: 2 }, [
                  unref(statisticsSection) ? (openBlock(), createBlock(_sfc_main$8, {
                    key: 0,
                    section: unref(statisticsSection),
                    mobile: ""
                  }, null, 8, ["section"])) : unref(lazy).hasError("statistics") ? (openBlock(), createBlock(_sfc_main$d, {
                    key: 1,
                    title: "Statistics unavailable",
                    onRetry: ($event) => unref(lazy).loadSection("statistics")
                  }, null, 8, ["onRetry"])) : (openBlock(), createBlock(_sfc_main$f, {
                    key: 2,
                    variant: "summary"
                  }))
                ], 64)) : activeTab.value === "news" ? (openBlock(), createBlock(Fragment, { key: 3 }, [
                  unref(newsSection) ? (openBlock(), createBlock(_sfc_main$9, {
                    key: 0,
                    section: unref(newsSection)
                  }, null, 8, ["section"])) : unref(lazy).hasError("news") ? (openBlock(), createBlock(_sfc_main$d, {
                    key: 1,
                    title: "News unavailable",
                    onRetry: ($event) => unref(lazy).loadSection("news")
                  }, null, 8, ["onRetry"])) : (openBlock(), createBlock(_sfc_main$f, {
                    key: 2,
                    variant: "list"
                  }))
                ], 64)) : activeTab.value === "initiatives" ? (openBlock(), createBlock(Fragment, { key: 4 }, [
                  unref(initiativesSection) ? (openBlock(), createBlock(_sfc_main$a, {
                    key: 0,
                    section: unref(initiativesSection)
                  }, null, 8, ["section"])) : unref(lazy).hasError("initiatives") ? (openBlock(), createBlock(_sfc_main$d, {
                    key: 1,
                    title: "Initiatives unavailable",
                    onRetry: ($event) => unref(lazy).loadSection("initiatives")
                  }, null, 8, ["onRetry"])) : (openBlock(), createBlock(_sfc_main$f, {
                    key: 2,
                    variant: "initiatives"
                  }))
                ], 64)) : createCommentVNode("", true)
              ], 40, ["id", "aria-labelledby"]),
              createVNode("nav", { class: "fixed inset-x-0 bottom-0 z-40 grid grid-cols-4 border-t border-white/10 bg-black/95 px-3 pb-5 pt-3 text-center text-[11px] text-ui-muted-foreground backdrop-blur-xl" }, [
                createVNode(unref(Button), {
                  variant: "link",
                  size: "sm",
                  icon: unref(Sparkles),
                  ui: { root: "grid justify-items-center gap-1 p-0 text-ui-primary no-underline" },
                  onClick: ($event) => activateTab("overview")
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(unref(t)("overview")), 1)
                  ]),
                  _: 1
                }, 8, ["icon", "onClick"]),
                createVNode(unref(Button), {
                  variant: "link",
                  size: "sm",
                  icon: unref(Newspaper),
                  ui: { root: "grid justify-items-center gap-1 p-0 text-ui-muted-foreground no-underline" },
                  onClick: ($event) => activateTab("news")
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(unref(t)("news")), 1)
                  ]),
                  _: 1
                }, 8, ["icon", "onClick"]),
                createVNode("a", {
                  href: `/about?lang=${currentLocale.value}`,
                  class: "grid justify-items-center gap-1"
                }, [
                  createVNode(unref(Folder), { class: "h-5 w-5" }),
                  createTextVNode(toDisplayString(unref(t)("resources")), 1)
                ], 8, ["href"]),
                createVNode(unref(Button), {
                  variant: "link",
                  size: "sm",
                  icon: unref(FileText),
                  ui: { root: "grid justify-items-center gap-1 p-0 text-ui-muted-foreground no-underline" },
                  onClick: ($event) => activateTab("forecasts")
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(unref(t)("analysis")), 1)
                  ]),
                  _: 1
                }, 8, ["icon", "onClick"])
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
});
const _sfc_setup$7 = _sfc_main$7.setup;
_sfc_main$7.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/MobileDetailView.vue");
  return _sfc_setup$7 ? _sfc_setup$7(props, ctx) : void 0;
};
const _sfc_main$6 = /* @__PURE__ */ defineComponent({
  __name: "OverviewSection",
  __ssrInlineRender: true,
  props: {
    countdown: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(_sfc_main$m), { ui: { root: "doomsday-card rounded-xl", body: "p-5 sm:p-6" } }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          var _a, _b, _c, _d;
          if (_push2) {
            _push2(`<h3 class="doomsday-display mb-4 text-white"${_scopeId}>${ssrInterpolate(unref(t)("summary"))}</h3><p class="leading-relaxed text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(__props.countdown.description)}</p><div class="mt-6 grid grid-cols-[repeat(auto-fit,minmax(120px,1fr))] gap-3 text-sm"${_scopeId}><div class="min-w-0 rounded-lg border border-white/10 bg-white/5 p-3"${_scopeId}><span class="text-ui-muted-foreground"${_scopeId}>Confidence</span><strong class="block text-xl text-white"${_scopeId}>${ssrInterpolate(((_a = __props.countdown.main_projection) == null ? void 0 : _a.confidence_score) ?? 0)}%</strong></div><div class="min-w-0 rounded-lg border border-white/10 bg-white/5 p-3"${_scopeId}><span class="text-ui-muted-foreground"${_scopeId}>Trend</span><strong class="block text-ui-primary"${_scopeId}>${ssrInterpolate((_b = __props.countdown.main_projection) == null ? void 0 : _b.trend)}</strong></div><div class="min-w-0 rounded-lg border border-white/10 bg-white/5 p-3"${_scopeId}><span class="text-ui-muted-foreground"${_scopeId}>Risk</span><strong class="block text-ui-primary"${_scopeId}>${ssrInterpolate(__props.countdown.severity)}</strong></div></div>`);
          } else {
            return [
              createVNode("h3", { class: "doomsday-display mb-4 text-white" }, toDisplayString(unref(t)("summary")), 1),
              createVNode("p", { class: "leading-relaxed text-ui-muted-foreground" }, toDisplayString(__props.countdown.description), 1),
              createVNode("div", { class: "mt-6 grid grid-cols-[repeat(auto-fit,minmax(120px,1fr))] gap-3 text-sm" }, [
                createVNode("div", { class: "min-w-0 rounded-lg border border-white/10 bg-white/5 p-3" }, [
                  createVNode("span", { class: "text-ui-muted-foreground" }, "Confidence"),
                  createVNode("strong", { class: "block text-xl text-white" }, toDisplayString(((_c = __props.countdown.main_projection) == null ? void 0 : _c.confidence_score) ?? 0) + "%", 1)
                ]),
                createVNode("div", { class: "min-w-0 rounded-lg border border-white/10 bg-white/5 p-3" }, [
                  createVNode("span", { class: "text-ui-muted-foreground" }, "Trend"),
                  createVNode("strong", { class: "block text-ui-primary" }, toDisplayString((_d = __props.countdown.main_projection) == null ? void 0 : _d.trend), 1)
                ]),
                createVNode("div", { class: "min-w-0 rounded-lg border border-white/10 bg-white/5 p-3" }, [
                  createVNode("span", { class: "text-ui-muted-foreground" }, "Risk"),
                  createVNode("strong", { class: "block text-ui-primary" }, toDisplayString(__props.countdown.severity), 1)
                ])
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$p, {
        visualization: __props.countdown.key_indicators
      }, null, _parent));
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup$6 = _sfc_main$6.setup;
_sfc_main$6.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/OverviewSection.vue");
  return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
const _sfc_main$5 = /* @__PURE__ */ defineComponent({
  __name: "DetailPanel",
  __ssrInlineRender: true,
  props: {
    countdown: {},
    currentLocale: {},
    forecastSection: {},
    statisticsSection: {},
    newsSection: {},
    initiativesSection: {},
    expanded: { type: Boolean, default: false }
  },
  emits: ["close", "toggleExpanded"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const activeTab = ref("overview");
    const tabs = computed(() => [
      { value: "overview", label: t("overview") },
      { value: "forecasts", label: t("predictions") },
      { value: "statistics", label: t("statistics") },
      { value: "news", label: t("news") },
      { value: "initiatives", label: t("initiatives") }
    ]);
    const countdownSlug = computed(() => props.countdown.slug);
    const currentLocale = computed(() => props.currentLocale);
    const lazy = useDoomsdayLazySections(countdownSlug, currentLocale, {
      forecasts: computed(() => props.forecastSection),
      statistics: computed(() => props.statisticsSection),
      news: computed(() => props.newsSection),
      initiatives: computed(() => props.initiativesSection)
    });
    const forecastSection = lazy.forecastSection;
    const statisticsSection = lazy.statisticsSection;
    const newsSection = lazy.newsSection;
    const initiativesSection = lazy.initiativesSection;
    const reducedMotion = useDoomsdayReducedMotion();
    const tabMotion = computed(() => resolveMotionPreset(tabContent, reducedMotion.value));
    const tabContentKey = computed(() => `${props.countdown.slug}:${activeTab.value}:${sectionState(activeTab.value)}`);
    function activateTab(value) {
      activeTab.value = value;
      if (isLazySectionKey(value)) {
        void lazy.loadSection(value);
      }
    }
    function sectionState(value) {
      if (!isLazySectionKey(value)) {
        return "ready";
      }
      if (lazy.hasError(value)) {
        return "error";
      }
      return sectionByKey(value) === null ? "loading" : "ready";
    }
    function sectionByKey(key) {
      if (key === "forecasts") {
        return forecastSection.value;
      }
      if (key === "statistics") {
        return statisticsSection.value;
      }
      if (key === "news") {
        return newsSection.value;
      }
      return initiativesSection.value;
    }
    watch(() => `${props.countdown.slug}:${props.currentLocale}`, () => {
      activeTab.value = "overview";
      lazy.reset();
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "doomsday-card flex h-full min-h-0 flex-col overflow-hidden rounded-2xl" }, _attrs))}><div class="grid min-w-0 shrink-0 gap-4 border-b border-white/10 p-4 sm:p-5 xl:grid-cols-[minmax(0,1fr)_auto]">`);
      _push(ssrRenderComponent(_sfc_main$l, {
        src: __props.countdown.image_url,
        alt: __props.countdown.title,
        sizes: "(max-width: 639px) calc(100vw - 2rem), 1px",
        loading: "lazy",
        "fetch-priority": "low",
        "picture-class": "block aspect-video w-full overflow-hidden rounded-lg sm:hidden",
        "img-class": "h-full w-full object-cover object-center"
      }, null, _parent));
      _push(`<div class="min-w-0"><div class="mb-2 flex flex-wrap items-center gap-3">`);
      _push(ssrRenderComponent(unref(Button), {
        variant: "link",
        size: "sm",
        icon: unref(ArrowLeft),
        ui: { root: "p-0 text-ui-primary no-underline" },
        onClick: ($event) => emit("close")
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`All countdowns`);
          } else {
            return [
              createTextVNode("All countdowns")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(Button), {
        variant: "link",
        size: "sm",
        icon: __props.expanded ? unref(Minimize2) : unref(Maximize2),
        ui: { root: "p-0 text-ui-muted-foreground no-underline hover:text-ui-primary" },
        onClick: ($event) => emit("toggleExpanded")
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`${ssrInterpolate(__props.expanded ? "Collapse" : "Expand")}`);
          } else {
            return [
              createTextVNode(toDisplayString(__props.expanded ? "Collapse" : "Expand"), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div><h2 class="doomsday-display mt-4 text-xl leading-tight text-white sm:text-2xl 2xl:text-3xl">${ssrInterpolate(__props.countdown.title)}</h2><p class="mt-2 max-w-3xl text-sm leading-relaxed text-ui-muted-foreground">${ssrInterpolate(__props.countdown.summary)}</p></div><div class="min-w-0 max-w-full justify-self-start text-center xl:justify-self-end">`);
      _push(ssrRenderComponent(_sfc_main$j, {
        "target-date": __props.countdown.timer.target_date
      }, null, _parent));
      _push(`</div></div><div class="doomsday-scrollbar flex shrink-0 gap-3 overflow-x-auto border-b border-white/10 px-4 sm:px-5"><!--[-->`);
      ssrRenderList(tabs.value, (tab) => {
        _push(ssrRenderComponent(unref(Button), {
          key: tab.value,
          variant: "link",
          ui: { root: ["doomsday-display rounded-none border-b-2 px-0 py-3 text-xs no-underline outline-none ring-0 shadow-none focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0 active:ring-0 active:outline-none", activeTab.value === tab.value ? "border-ui-primary text-ui-primary" : "border-transparent text-ui-muted-foreground"].join(" ") },
          onClick: ($event) => activateTab(tab.value)
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`${ssrInterpolate(tab.label)}`);
            } else {
              return [
                createTextVNode(toDisplayString(tab.label), 1)
              ];
            }
          }),
          _: 2
        }, _parent));
      });
      _push(`<!--]--></div><div class="doomsday-scrollbar grid min-h-0 min-w-0 flex-1 auto-rows-max gap-5 overflow-y-auto overscroll-contain scroll-pb-8 p-4 pb-8 pr-2 sm:scroll-pb-10 sm:p-5 sm:pb-10 sm:pr-3">`);
      _push(ssrRenderComponent(unref(AnimatePresence), {
        mode: "wait",
        initial: false
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(motion).div, {
              key: tabContentKey.value,
              class: ["grid w-full min-w-0 gap-5", __props.expanded ? "xl:grid-cols-2" : ""],
              initial: tabMotion.value.initial,
              animate: tabMotion.value.animate,
              exit: tabMotion.value.exit,
              transition: tabMotion.value.transition
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (activeTab.value === "overview") {
                    _push3(ssrRenderComponent(_sfc_main$6, { countdown: __props.countdown }, null, _parent3, _scopeId2));
                  } else if (activeTab.value === "forecasts") {
                    _push3(`<!--[-->`);
                    if (unref(forecastSection)) {
                      _push3(ssrRenderComponent(_sfc_main$c, { section: unref(forecastSection) }, null, _parent3, _scopeId2));
                    } else if (unref(lazy).hasError("forecasts")) {
                      _push3(ssrRenderComponent(_sfc_main$d, {
                        title: "Forecasts unavailable",
                        onRetry: ($event) => unref(lazy).loadSection("forecasts")
                      }, null, _parent3, _scopeId2));
                    } else {
                      _push3(ssrRenderComponent(_sfc_main$f, { variant: "chart" }, null, _parent3, _scopeId2));
                    }
                    _push3(`<!--]-->`);
                  } else if (activeTab.value === "statistics") {
                    _push3(`<!--[-->`);
                    if (unref(statisticsSection)) {
                      _push3(ssrRenderComponent(_sfc_main$8, { section: unref(statisticsSection) }, null, _parent3, _scopeId2));
                    } else if (unref(lazy).hasError("statistics")) {
                      _push3(ssrRenderComponent(_sfc_main$d, {
                        title: "Statistics unavailable",
                        onRetry: ($event) => unref(lazy).loadSection("statistics")
                      }, null, _parent3, _scopeId2));
                    } else {
                      _push3(ssrRenderComponent(_sfc_main$f, { variant: "summary" }, null, _parent3, _scopeId2));
                    }
                    _push3(`<!--]-->`);
                  } else if (activeTab.value === "news") {
                    _push3(`<!--[-->`);
                    if (unref(newsSection)) {
                      _push3(ssrRenderComponent(_sfc_main$9, { section: unref(newsSection) }, null, _parent3, _scopeId2));
                    } else if (unref(lazy).hasError("news")) {
                      _push3(ssrRenderComponent(_sfc_main$d, {
                        title: "News unavailable",
                        onRetry: ($event) => unref(lazy).loadSection("news")
                      }, null, _parent3, _scopeId2));
                    } else {
                      _push3(ssrRenderComponent(_sfc_main$f, { variant: "list" }, null, _parent3, _scopeId2));
                    }
                    _push3(`<!--]-->`);
                  } else if (activeTab.value === "initiatives") {
                    _push3(`<!--[-->`);
                    if (unref(initiativesSection)) {
                      _push3(ssrRenderComponent(_sfc_main$a, { section: unref(initiativesSection) }, null, _parent3, _scopeId2));
                    } else if (unref(lazy).hasError("initiatives")) {
                      _push3(ssrRenderComponent(_sfc_main$d, {
                        title: "Initiatives unavailable",
                        onRetry: ($event) => unref(lazy).loadSection("initiatives")
                      }, null, _parent3, _scopeId2));
                    } else {
                      _push3(ssrRenderComponent(_sfc_main$f, { variant: "initiatives" }, null, _parent3, _scopeId2));
                    }
                    _push3(`<!--]-->`);
                  } else {
                    _push3(`<!---->`);
                  }
                } else {
                  return [
                    activeTab.value === "overview" ? (openBlock(), createBlock(_sfc_main$6, {
                      key: 0,
                      countdown: __props.countdown
                    }, null, 8, ["countdown"])) : activeTab.value === "forecasts" ? (openBlock(), createBlock(Fragment, { key: 1 }, [
                      unref(forecastSection) ? (openBlock(), createBlock(_sfc_main$c, {
                        key: 0,
                        section: unref(forecastSection)
                      }, null, 8, ["section"])) : unref(lazy).hasError("forecasts") ? (openBlock(), createBlock(_sfc_main$d, {
                        key: 1,
                        title: "Forecasts unavailable",
                        onRetry: ($event) => unref(lazy).loadSection("forecasts")
                      }, null, 8, ["onRetry"])) : (openBlock(), createBlock(_sfc_main$f, {
                        key: 2,
                        variant: "chart"
                      }))
                    ], 64)) : activeTab.value === "statistics" ? (openBlock(), createBlock(Fragment, { key: 2 }, [
                      unref(statisticsSection) ? (openBlock(), createBlock(_sfc_main$8, {
                        key: 0,
                        section: unref(statisticsSection)
                      }, null, 8, ["section"])) : unref(lazy).hasError("statistics") ? (openBlock(), createBlock(_sfc_main$d, {
                        key: 1,
                        title: "Statistics unavailable",
                        onRetry: ($event) => unref(lazy).loadSection("statistics")
                      }, null, 8, ["onRetry"])) : (openBlock(), createBlock(_sfc_main$f, {
                        key: 2,
                        variant: "summary"
                      }))
                    ], 64)) : activeTab.value === "news" ? (openBlock(), createBlock(Fragment, { key: 3 }, [
                      unref(newsSection) ? (openBlock(), createBlock(_sfc_main$9, {
                        key: 0,
                        section: unref(newsSection)
                      }, null, 8, ["section"])) : unref(lazy).hasError("news") ? (openBlock(), createBlock(_sfc_main$d, {
                        key: 1,
                        title: "News unavailable",
                        onRetry: ($event) => unref(lazy).loadSection("news")
                      }, null, 8, ["onRetry"])) : (openBlock(), createBlock(_sfc_main$f, {
                        key: 2,
                        variant: "list"
                      }))
                    ], 64)) : activeTab.value === "initiatives" ? (openBlock(), createBlock(Fragment, { key: 4 }, [
                      unref(initiativesSection) ? (openBlock(), createBlock(_sfc_main$a, {
                        key: 0,
                        section: unref(initiativesSection)
                      }, null, 8, ["section"])) : unref(lazy).hasError("initiatives") ? (openBlock(), createBlock(_sfc_main$d, {
                        key: 1,
                        title: "Initiatives unavailable",
                        onRetry: ($event) => unref(lazy).loadSection("initiatives")
                      }, null, 8, ["onRetry"])) : (openBlock(), createBlock(_sfc_main$f, {
                        key: 2,
                        variant: "initiatives"
                      }))
                    ], 64)) : createCommentVNode("", true)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              (openBlock(), createBlock(unref(motion).div, {
                key: tabContentKey.value,
                class: ["grid w-full min-w-0 gap-5", __props.expanded ? "xl:grid-cols-2" : ""],
                initial: tabMotion.value.initial,
                animate: tabMotion.value.animate,
                exit: tabMotion.value.exit,
                transition: tabMotion.value.transition
              }, {
                default: withCtx(() => [
                  activeTab.value === "overview" ? (openBlock(), createBlock(_sfc_main$6, {
                    key: 0,
                    countdown: __props.countdown
                  }, null, 8, ["countdown"])) : activeTab.value === "forecasts" ? (openBlock(), createBlock(Fragment, { key: 1 }, [
                    unref(forecastSection) ? (openBlock(), createBlock(_sfc_main$c, {
                      key: 0,
                      section: unref(forecastSection)
                    }, null, 8, ["section"])) : unref(lazy).hasError("forecasts") ? (openBlock(), createBlock(_sfc_main$d, {
                      key: 1,
                      title: "Forecasts unavailable",
                      onRetry: ($event) => unref(lazy).loadSection("forecasts")
                    }, null, 8, ["onRetry"])) : (openBlock(), createBlock(_sfc_main$f, {
                      key: 2,
                      variant: "chart"
                    }))
                  ], 64)) : activeTab.value === "statistics" ? (openBlock(), createBlock(Fragment, { key: 2 }, [
                    unref(statisticsSection) ? (openBlock(), createBlock(_sfc_main$8, {
                      key: 0,
                      section: unref(statisticsSection)
                    }, null, 8, ["section"])) : unref(lazy).hasError("statistics") ? (openBlock(), createBlock(_sfc_main$d, {
                      key: 1,
                      title: "Statistics unavailable",
                      onRetry: ($event) => unref(lazy).loadSection("statistics")
                    }, null, 8, ["onRetry"])) : (openBlock(), createBlock(_sfc_main$f, {
                      key: 2,
                      variant: "summary"
                    }))
                  ], 64)) : activeTab.value === "news" ? (openBlock(), createBlock(Fragment, { key: 3 }, [
                    unref(newsSection) ? (openBlock(), createBlock(_sfc_main$9, {
                      key: 0,
                      section: unref(newsSection)
                    }, null, 8, ["section"])) : unref(lazy).hasError("news") ? (openBlock(), createBlock(_sfc_main$d, {
                      key: 1,
                      title: "News unavailable",
                      onRetry: ($event) => unref(lazy).loadSection("news")
                    }, null, 8, ["onRetry"])) : (openBlock(), createBlock(_sfc_main$f, {
                      key: 2,
                      variant: "list"
                    }))
                  ], 64)) : activeTab.value === "initiatives" ? (openBlock(), createBlock(Fragment, { key: 4 }, [
                    unref(initiativesSection) ? (openBlock(), createBlock(_sfc_main$a, {
                      key: 0,
                      section: unref(initiativesSection)
                    }, null, 8, ["section"])) : unref(lazy).hasError("initiatives") ? (openBlock(), createBlock(_sfc_main$d, {
                      key: 1,
                      title: "Initiatives unavailable",
                      onRetry: ($event) => unref(lazy).loadSection("initiatives")
                    }, null, 8, ["onRetry"])) : (openBlock(), createBlock(_sfc_main$f, {
                      key: 2,
                      variant: "initiatives"
                    }))
                  ], 64)) : createCommentVNode("", true)
                ]),
                _: 1
              }, 8, ["class", "initial", "animate", "exit", "transition"]))
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></section>`);
    };
  }
});
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/DetailPanel.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const _sfc_main$4 = /* @__PURE__ */ defineComponent({
  __name: "SelectedMasterDetail",
  __ssrInlineRender: true,
  props: {
    countdowns: {},
    selectedCountdown: {},
    hero: {},
    currentLocale: {},
    forecastSection: {},
    statisticsSection: {},
    newsSection: {},
    initiativesSection: {},
    selectedSlug: { default: null },
    pendingSlug: { default: null },
    isLoadingSelection: { type: Boolean, default: false }
  },
  emits: ["select", "close"],
  setup(__props, { emit: __emit }) {
    const emit = __emit;
    const isDetailExpanded = ref(false);
    const reducedMotion = useDoomsdayReducedMotion();
    const detailMotion = computed(() => resolveMotionPreset(panelReveal, reducedMotion.value));
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(motion).section, mergeProps({
        class: ["mx-auto hidden h-[calc(100dvh-64px)] min-h-0 max-w-[1760px] gap-5 overflow-hidden px-5 py-4 lg:grid xl:px-7", isDetailExpanded.value ? "grid-cols-1" : "grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]"],
        initial: detailMotion.value.initial,
        animate: detailMotion.value.animate,
        transition: detailMotion.value.transition
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (!isDetailExpanded.value) {
              _push2(`<div class="doomsday-scrollbar grid min-h-0 min-w-0 content-start gap-5 overflow-y-auto pr-1"${_scopeId}><div class="relative min-w-0 overflow-hidden rounded-2xl border border-white/10 bg-black p-6 xl:p-8"${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$l, {
                src: __props.hero.desktop_image,
                alt: "Earth horizon with red monitoring arcs",
                sizes: "(min-width: 1280px) 42vw, 50vw",
                media: "(min-width: 1024px)",
                "inactive-media": "(max-width: 1023px)",
                loading: "eager",
                "fetch-priority": "high",
                "picture-class": "absolute inset-0 block h-full w-full",
                "img-class": "h-full w-full object-cover object-center opacity-45"
              }, null, _parent2, _scopeId));
              _push2(`<div class="absolute inset-0 bg-gradient-to-r from-black via-black/85 to-black/20"${_scopeId}></div><div class="relative max-w-2xl"${_scopeId}><div class="mb-6 h-px w-24 bg-ui-primary/70"${_scopeId}></div><h1 class="doomsday-display text-3xl leading-tight text-white 2xl:text-5xl"${_scopeId}>${ssrInterpolate(__props.hero.headline_prefix)}<br${_scopeId}><span class="text-white/70"${_scopeId}>${ssrInterpolate(__props.hero.headline_middle)}</span><span class="doomsday-red-text"${_scopeId}>${ssrInterpolate(__props.hero.headline_accent)}</span></h1><p class="mt-5 text-base text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(__props.hero.subtitle)}</p></div></div>`);
              _push2(ssrRenderComponent(_sfc_main$h, {
                countdowns: __props.countdowns,
                compact: true,
                "selected-slug": __props.selectedSlug,
                "pending-slug": __props.pendingSlug,
                onSelect: ($event) => emit("select", $event)
              }, null, _parent2, _scopeId));
              _push2(`</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<div class="min-h-0 min-w-0 self-stretch"${_scopeId}>`);
            if (__props.selectedCountdown && !__props.isLoadingSelection) {
              _push2(ssrRenderComponent(_sfc_main$5, {
                countdown: __props.selectedCountdown,
                "current-locale": __props.currentLocale,
                "forecast-section": __props.forecastSection,
                "statistics-section": __props.statisticsSection,
                "news-section": __props.newsSection,
                "initiatives-section": __props.initiativesSection,
                expanded: isDetailExpanded.value,
                onToggleExpanded: ($event) => isDetailExpanded.value = !isDetailExpanded.value,
                onClose: ($event) => emit("close")
              }, null, _parent2, _scopeId));
            } else {
              _push2(ssrRenderComponent(_sfc_main$f, null, null, _parent2, _scopeId));
            }
            _push2(`</div>`);
          } else {
            return [
              !isDetailExpanded.value ? (openBlock(), createBlock("div", {
                key: 0,
                class: "doomsday-scrollbar grid min-h-0 min-w-0 content-start gap-5 overflow-y-auto pr-1"
              }, [
                createVNode("div", { class: "relative min-w-0 overflow-hidden rounded-2xl border border-white/10 bg-black p-6 xl:p-8" }, [
                  createVNode(_sfc_main$l, {
                    src: __props.hero.desktop_image,
                    alt: "Earth horizon with red monitoring arcs",
                    sizes: "(min-width: 1280px) 42vw, 50vw",
                    media: "(min-width: 1024px)",
                    "inactive-media": "(max-width: 1023px)",
                    loading: "eager",
                    "fetch-priority": "high",
                    "picture-class": "absolute inset-0 block h-full w-full",
                    "img-class": "h-full w-full object-cover object-center opacity-45"
                  }, null, 8, ["src"]),
                  createVNode("div", { class: "absolute inset-0 bg-gradient-to-r from-black via-black/85 to-black/20" }),
                  createVNode("div", { class: "relative max-w-2xl" }, [
                    createVNode("div", { class: "mb-6 h-px w-24 bg-ui-primary/70" }),
                    createVNode("h1", { class: "doomsday-display text-3xl leading-tight text-white 2xl:text-5xl" }, [
                      createTextVNode(toDisplayString(__props.hero.headline_prefix), 1),
                      createVNode("br"),
                      createVNode("span", { class: "text-white/70" }, toDisplayString(__props.hero.headline_middle), 1),
                      createVNode("span", { class: "doomsday-red-text" }, toDisplayString(__props.hero.headline_accent), 1)
                    ]),
                    createVNode("p", { class: "mt-5 text-base text-ui-muted-foreground" }, toDisplayString(__props.hero.subtitle), 1)
                  ])
                ]),
                createVNode(_sfc_main$h, {
                  countdowns: __props.countdowns,
                  compact: true,
                  "selected-slug": __props.selectedSlug,
                  "pending-slug": __props.pendingSlug,
                  onSelect: ($event) => emit("select", $event)
                }, null, 8, ["countdowns", "selected-slug", "pending-slug", "onSelect"])
              ])) : createCommentVNode("", true),
              createVNode("div", { class: "min-h-0 min-w-0 self-stretch" }, [
                __props.selectedCountdown && !__props.isLoadingSelection ? (openBlock(), createBlock(_sfc_main$5, {
                  key: 0,
                  countdown: __props.selectedCountdown,
                  "current-locale": __props.currentLocale,
                  "forecast-section": __props.forecastSection,
                  "statistics-section": __props.statisticsSection,
                  "news-section": __props.newsSection,
                  "initiatives-section": __props.initiativesSection,
                  expanded: isDetailExpanded.value,
                  onToggleExpanded: ($event) => isDetailExpanded.value = !isDetailExpanded.value,
                  onClose: ($event) => emit("close")
                }, null, 8, ["countdown", "current-locale", "forecast-section", "statistics-section", "news-section", "initiatives-section", "expanded", "onToggleExpanded", "onClose"])) : (openBlock(), createBlock(_sfc_main$f, { key: 1 }))
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
});
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/SelectedMasterDetail.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const slideViewportClass = "relative min-h-[32rem] overflow-hidden sm:min-h-[35.5rem]";
const slideSurfaceClass = "grid min-h-[32rem] grid-rows-[auto_auto] overflow-hidden rounded-b-xl outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-ui-primary sm:min-h-[35.5rem]";
const slideContentClass = "grid grid-rows-[1.25rem_3.5rem_4.5rem_1.5rem] gap-3 p-5 sm:p-6";
const paginationClass = "flex min-h-11 items-center justify-center gap-0.5 overflow-x-auto px-4 py-2";
const _sfc_main$3 = /* @__PURE__ */ defineComponent({
  __name: "LatestNewsCarousel",
  __ssrInlineRender: true,
  props: {
    items: {},
    autoplayIntervalMs: { default: 7e3 }
  },
  setup(__props) {
    const props = __props;
    const carouselRoot = ref(null);
    const currentIndex = ref(0);
    const navigationDirection = ref(1);
    const hoverPaused = ref(false);
    const focusPaused = ref(false);
    const documentHidden = ref(false);
    const mounted = ref(false);
    const reducedMotion = useDoomsdayReducedMotion();
    let autoplayTimer = null;
    const activeItem = computed(() => props.items[currentIndex.value] ?? null);
    const hasMultipleItems = computed(() => props.items.length > 1);
    const hasPendingSlide = computed(() => props.items.length > 0 && activeItem.value === null);
    const autoplayEnabled = computed(() => mounted.value && hasMultipleItems.value && !reducedMotion.value && !hoverPaused.value && !focusPaused.value && !documentHidden.value);
    const ariaLive = computed(() => autoplayEnabled.value ? "off" : "polite");
    const activeExternalUrl = computed(() => activeItem.value ? externalSourceUrl(activeItem.value) : null);
    const slideInitial = computed(() => reducedMotion.value ? { opacity: 0 } : { opacity: 0, x: navigationDirection.value * 56 });
    const slideAnimate = computed(() => ({ opacity: 1, x: 0 }));
    const slideExit = computed(() => reducedMotion.value ? { opacity: 0 } : { opacity: 0, x: navigationDirection.value * -56 });
    const slideTransition = computed(() => reducedMotion.value ? { duration: 0.12 } : { duration: 0.32, ease: [0.22, 1, 0.36, 1] });
    function clearAutoplay() {
      if (autoplayTimer === null) {
        return;
      }
      clearInterval(autoplayTimer);
      autoplayTimer = null;
    }
    function navigateTo(index, direction) {
      if (!hasMultipleItems.value || index === currentIndex.value) {
        return;
      }
      navigationDirection.value = direction;
      currentIndex.value = index;
    }
    function advance(step) {
      if (!hasMultipleItems.value) {
        return;
      }
      const nextIndex = (currentIndex.value + step + props.items.length) % props.items.length;
      navigateTo(nextIndex, step);
    }
    function restartAutoplay() {
      clearAutoplay();
      if (!autoplayEnabled.value) {
        return;
      }
      autoplayTimer = setInterval(() => advance(1), props.autoplayIntervalMs);
    }
    function previous() {
      advance(-1);
      restartAutoplay();
    }
    function next() {
      advance(1);
      restartAutoplay();
    }
    function goTo(index) {
      if (!hasMultipleItems.value || index === currentIndex.value) {
        return;
      }
      const forwardDistance = (index - currentIndex.value + props.items.length) % props.items.length;
      const backwardDistance = (currentIndex.value - index + props.items.length) % props.items.length;
      navigateTo(index, forwardDistance <= backwardDistance ? 1 : -1);
      restartAutoplay();
    }
    function handleVisibilityChange() {
      documentHidden.value = document.hidden;
    }
    function handleFocusOut(event) {
      var _a;
      const nextTarget = event.relatedTarget;
      if (nextTarget instanceof Node && ((_a = carouselRoot.value) == null ? void 0 : _a.contains(nextTarget))) {
        return;
      }
      focusPaused.value = false;
    }
    function externalSourceUrl(item) {
      if (!item.source_url) {
        return null;
      }
      try {
        const source = new URL(item.source_url);
        return source.protocol === "https:" ? source.toString() : null;
      } catch {
        return null;
      }
    }
    function primaryLinkLabel(item, opensExternally) {
      return `${item.title}. ${opensExternally ? t("openSource") : t("viewCountdown")}`;
    }
    function formatPublishedAt(value) {
      const date = new Date(value);
      if (Number.isNaN(date.getTime())) {
        return value;
      }
      return new Intl.DateTimeFormat(currentLanguage.value || "en", { dateStyle: "medium" }).format(date);
    }
    watch(autoplayEnabled, restartAutoplay);
    watch(() => props.autoplayIntervalMs, restartAutoplay);
    watch(() => props.items.map((item) => item.id), (ids, previousIds) => {
      const previousId = previousIds[currentIndex.value] ?? null;
      const retainedIndex = previousId === null ? -1 : ids.indexOf(previousId);
      navigationDirection.value = 1;
      currentIndex.value = retainedIndex >= 0 ? retainedIndex : 0;
      restartAutoplay();
    });
    onMounted(() => {
      mounted.value = true;
      handleVisibilityChange();
      document.addEventListener("visibilitychange", handleVisibilityChange);
      restartAutoplay();
    });
    onBeforeUnmount(() => {
      mounted.value = false;
      clearAutoplay();
      document.removeEventListener("visibilitychange", handleVisibilityChange);
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(_sfc_main$m), mergeProps({ ui: { root: "doomsday-card min-w-0 overflow-hidden rounded-xl", header: "flex min-h-[4.25rem] items-center border-b border-white/10 px-5 py-4", body: "min-w-0 p-0" } }, _attrs), {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (hasPendingSlide.value) {
              _push2(ssrRenderComponent(unref(SkeletonLoader), {
                variant: "text",
                width: "42%",
                height: "1rem",
                animated: !unref(reducedMotion),
                "aria-hidden": "true"
              }, null, _parent2, _scopeId));
            } else {
              _push2(`<h2 class="doomsday-display min-w-0 text-ui-primary"${_scopeId}>${ssrInterpolate(unref(t)("latestNews"))}</h2>`);
            }
          } else {
            return [
              hasPendingSlide.value ? (openBlock(), createBlock(unref(SkeletonLoader), {
                key: 0,
                variant: "text",
                width: "42%",
                height: "1rem",
                animated: !unref(reducedMotion),
                "aria-hidden": "true"
              }, null, 8, ["animated"])) : (openBlock(), createBlock("h2", {
                key: 1,
                class: "doomsday-display min-w-0 text-ui-primary"
              }, toDisplayString(unref(t)("latestNews")), 1))
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<section class="min-w-0 outline-none" role="region" aria-roledescription="carousel"${ssrRenderAttr("aria-label", unref(t)("newsCarouselLabel"))} tabindex="0"${_scopeId}>`);
            if (!__props.items.length) {
              _push2(`<p class="min-h-32 px-5 py-8 text-sm leading-relaxed text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(unref(t)("latestNewsEmpty"))}</p>`);
            } else if (hasPendingSlide.value) {
              _push2(`<div aria-hidden="true"${_scopeId}><div class="${ssrRenderClass(slideViewportClass)}"${_scopeId}><div class="${ssrRenderClass(slideSurfaceClass)}"${_scopeId}><div class="relative aspect-video overflow-hidden bg-black"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(SkeletonLoader), {
                variant: "rect",
                width: "100%",
                height: "100%",
                animated: !unref(reducedMotion),
                class: "absolute inset-0"
              }, null, _parent2, _scopeId));
              _push2(`</div><div class="${ssrRenderClass(slideContentClass)}"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(SkeletonLoader), {
                variant: "text",
                width: "46%",
                height: "0.75rem",
                animated: !unref(reducedMotion)
              }, null, _parent2, _scopeId));
              _push2(ssrRenderComponent(unref(SkeletonLoader), {
                variant: "text",
                lines: 2,
                height: "1rem",
                animated: !unref(reducedMotion)
              }, null, _parent2, _scopeId));
              _push2(ssrRenderComponent(unref(SkeletonLoader), {
                variant: "text",
                lines: 3,
                height: "0.8rem",
                animated: !unref(reducedMotion)
              }, null, _parent2, _scopeId));
              _push2(ssrRenderComponent(unref(SkeletonLoader), {
                variant: "text",
                width: "38%",
                height: "0.75rem",
                animated: !unref(reducedMotion)
              }, null, _parent2, _scopeId));
              _push2(`</div></div></div><div class="${ssrRenderClass(paginationClass)}"${_scopeId}><!--[-->`);
              ssrRenderList(__props.items, (item) => {
                _push2(ssrRenderComponent(unref(SkeletonLoader), {
                  key: item.id,
                  variant: "circle",
                  width: "0.5rem",
                  height: "0.5rem",
                  animated: !unref(reducedMotion)
                }, null, _parent2, _scopeId));
              });
              _push2(`<!--]--></div></div>`);
            } else {
              _push2(`<div class="relative min-w-0"${ssrRenderAttr("aria-live", ariaLive.value)}${_scopeId}><div class="${ssrRenderClass(slideViewportClass)}"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(AnimatePresence), {
                mode: "wait",
                initial: false
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    if (activeItem.value) {
                      _push3(ssrRenderComponent(unref(motion).article, {
                        key: activeItem.value.id,
                        class: "min-w-0",
                        role: "group",
                        "aria-roledescription": "slide",
                        "aria-label": `${unref(t)("newsSlide")} ${currentIndex.value + 1} / ${__props.items.length}`,
                        initial: slideInitial.value,
                        animate: slideAnimate.value,
                        exit: slideExit.value,
                        transition: slideTransition.value
                      }, {
                        default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                          if (_push4) {
                            ssrRenderVNode(_push4, createVNode(resolveDynamicComponent(activeExternalUrl.value ? "a" : unref(Link)), {
                              href: activeExternalUrl.value ?? activeItem.value.countdown_url,
                              target: activeExternalUrl.value ? "_blank" : void 0,
                              rel: activeExternalUrl.value ? "noopener noreferrer" : void 0,
                              "aria-label": primaryLinkLabel(activeItem.value, Boolean(activeExternalUrl.value)),
                              class: slideSurfaceClass
                            }, {
                              default: withCtx((_4, _push5, _parent5, _scopeId4) => {
                                if (_push5) {
                                  _push5(`<div class="relative aspect-video overflow-hidden bg-black"${_scopeId4}>`);
                                  _push5(ssrRenderComponent(unref(_sfc_main$o), {
                                    src: activeItem.value.image_url,
                                    alt: activeItem.value.title,
                                    "aspect-ratio": "56.25%",
                                    "loading-type": "skeleton",
                                    rounded: "none",
                                    ui: { root: "absolute inset-0 h-full w-full rounded-none bg-black", image: "h-full w-full object-cover object-center" }
                                  }, null, _parent5, _scopeId4));
                                  _push5(`<div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-black/10"${_scopeId4}></div></div><div class="${ssrRenderClass(slideContentClass)}"${_scopeId4}><p class="flex min-w-0 items-center gap-2 overflow-hidden text-xs text-ui-muted-foreground"${_scopeId4}>`);
                                  if (activeItem.value.source_name) {
                                    _push5(`<span class="min-w-0 truncate text-ui-primary"${_scopeId4}>${ssrInterpolate(activeItem.value.source_name)}</span>`);
                                  } else {
                                    _push5(`<!---->`);
                                  }
                                  if (activeItem.value.source_name) {
                                    _push5(`<span aria-hidden="true"${_scopeId4}>•</span>`);
                                  } else {
                                    _push5(`<!---->`);
                                  }
                                  _push5(`<time class="shrink-0"${ssrRenderAttr("datetime", activeItem.value.published_at)}${_scopeId4}>${ssrInterpolate(formatPublishedAt(activeItem.value.published_at))}</time></p><h3 class="doomsday-display line-clamp-2 min-h-[3.5rem] overflow-hidden break-words text-xl leading-7 text-white"${_scopeId4}>${ssrInterpolate(activeItem.value.title)}</h3><p class="line-clamp-3 min-h-[4.5rem] overflow-hidden break-words text-sm leading-6 text-ui-muted-foreground"${_scopeId4}>${ssrInterpolate(activeItem.value.excerpt)}</p><p class="line-clamp-1 min-h-6 overflow-hidden text-xs font-semibold uppercase tracking-[0.08em] text-white/55"${_scopeId4}>${ssrInterpolate(activeItem.value.countdown_title)}</p></div>`);
                                } else {
                                  return [
                                    createVNode("div", { class: "relative aspect-video overflow-hidden bg-black" }, [
                                      createVNode(unref(_sfc_main$o), {
                                        src: activeItem.value.image_url,
                                        alt: activeItem.value.title,
                                        "aspect-ratio": "56.25%",
                                        "loading-type": "skeleton",
                                        rounded: "none",
                                        ui: { root: "absolute inset-0 h-full w-full rounded-none bg-black", image: "h-full w-full object-cover object-center" }
                                      }, null, 8, ["src", "alt"]),
                                      createVNode("div", { class: "pointer-events-none absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-black/10" })
                                    ]),
                                    createVNode("div", { class: slideContentClass }, [
                                      createVNode("p", { class: "flex min-w-0 items-center gap-2 overflow-hidden text-xs text-ui-muted-foreground" }, [
                                        activeItem.value.source_name ? (openBlock(), createBlock("span", {
                                          key: 0,
                                          class: "min-w-0 truncate text-ui-primary"
                                        }, toDisplayString(activeItem.value.source_name), 1)) : createCommentVNode("", true),
                                        activeItem.value.source_name ? (openBlock(), createBlock("span", {
                                          key: 1,
                                          "aria-hidden": "true"
                                        }, "•")) : createCommentVNode("", true),
                                        createVNode("time", {
                                          class: "shrink-0",
                                          datetime: activeItem.value.published_at
                                        }, toDisplayString(formatPublishedAt(activeItem.value.published_at)), 9, ["datetime"])
                                      ]),
                                      createVNode("h3", { class: "doomsday-display line-clamp-2 min-h-[3.5rem] overflow-hidden break-words text-xl leading-7 text-white" }, toDisplayString(activeItem.value.title), 1),
                                      createVNode("p", { class: "line-clamp-3 min-h-[4.5rem] overflow-hidden break-words text-sm leading-6 text-ui-muted-foreground" }, toDisplayString(activeItem.value.excerpt), 1),
                                      createVNode("p", { class: "line-clamp-1 min-h-6 overflow-hidden text-xs font-semibold uppercase tracking-[0.08em] text-white/55" }, toDisplayString(activeItem.value.countdown_title), 1)
                                    ])
                                  ];
                                }
                              }),
                              _: 1
                            }), _parent4, _scopeId3);
                          } else {
                            return [
                              (openBlock(), createBlock(resolveDynamicComponent(activeExternalUrl.value ? "a" : unref(Link)), {
                                href: activeExternalUrl.value ?? activeItem.value.countdown_url,
                                target: activeExternalUrl.value ? "_blank" : void 0,
                                rel: activeExternalUrl.value ? "noopener noreferrer" : void 0,
                                "aria-label": primaryLinkLabel(activeItem.value, Boolean(activeExternalUrl.value)),
                                class: slideSurfaceClass
                              }, {
                                default: withCtx(() => [
                                  createVNode("div", { class: "relative aspect-video overflow-hidden bg-black" }, [
                                    createVNode(unref(_sfc_main$o), {
                                      src: activeItem.value.image_url,
                                      alt: activeItem.value.title,
                                      "aspect-ratio": "56.25%",
                                      "loading-type": "skeleton",
                                      rounded: "none",
                                      ui: { root: "absolute inset-0 h-full w-full rounded-none bg-black", image: "h-full w-full object-cover object-center" }
                                    }, null, 8, ["src", "alt"]),
                                    createVNode("div", { class: "pointer-events-none absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-black/10" })
                                  ]),
                                  createVNode("div", { class: slideContentClass }, [
                                    createVNode("p", { class: "flex min-w-0 items-center gap-2 overflow-hidden text-xs text-ui-muted-foreground" }, [
                                      activeItem.value.source_name ? (openBlock(), createBlock("span", {
                                        key: 0,
                                        class: "min-w-0 truncate text-ui-primary"
                                      }, toDisplayString(activeItem.value.source_name), 1)) : createCommentVNode("", true),
                                      activeItem.value.source_name ? (openBlock(), createBlock("span", {
                                        key: 1,
                                        "aria-hidden": "true"
                                      }, "•")) : createCommentVNode("", true),
                                      createVNode("time", {
                                        class: "shrink-0",
                                        datetime: activeItem.value.published_at
                                      }, toDisplayString(formatPublishedAt(activeItem.value.published_at)), 9, ["datetime"])
                                    ]),
                                    createVNode("h3", { class: "doomsday-display line-clamp-2 min-h-[3.5rem] overflow-hidden break-words text-xl leading-7 text-white" }, toDisplayString(activeItem.value.title), 1),
                                    createVNode("p", { class: "line-clamp-3 min-h-[4.5rem] overflow-hidden break-words text-sm leading-6 text-ui-muted-foreground" }, toDisplayString(activeItem.value.excerpt), 1),
                                    createVNode("p", { class: "line-clamp-1 min-h-6 overflow-hidden text-xs font-semibold uppercase tracking-[0.08em] text-white/55" }, toDisplayString(activeItem.value.countdown_title), 1)
                                  ])
                                ]),
                                _: 1
                              }, 8, ["href", "target", "rel", "aria-label"]))
                            ];
                          }
                        }),
                        _: 1
                      }, _parent3, _scopeId2));
                    } else {
                      _push3(`<!---->`);
                    }
                  } else {
                    return [
                      activeItem.value ? (openBlock(), createBlock(unref(motion).article, {
                        key: activeItem.value.id,
                        class: "min-w-0",
                        role: "group",
                        "aria-roledescription": "slide",
                        "aria-label": `${unref(t)("newsSlide")} ${currentIndex.value + 1} / ${__props.items.length}`,
                        initial: slideInitial.value,
                        animate: slideAnimate.value,
                        exit: slideExit.value,
                        transition: slideTransition.value
                      }, {
                        default: withCtx(() => [
                          (openBlock(), createBlock(resolveDynamicComponent(activeExternalUrl.value ? "a" : unref(Link)), {
                            href: activeExternalUrl.value ?? activeItem.value.countdown_url,
                            target: activeExternalUrl.value ? "_blank" : void 0,
                            rel: activeExternalUrl.value ? "noopener noreferrer" : void 0,
                            "aria-label": primaryLinkLabel(activeItem.value, Boolean(activeExternalUrl.value)),
                            class: slideSurfaceClass
                          }, {
                            default: withCtx(() => [
                              createVNode("div", { class: "relative aspect-video overflow-hidden bg-black" }, [
                                createVNode(unref(_sfc_main$o), {
                                  src: activeItem.value.image_url,
                                  alt: activeItem.value.title,
                                  "aspect-ratio": "56.25%",
                                  "loading-type": "skeleton",
                                  rounded: "none",
                                  ui: { root: "absolute inset-0 h-full w-full rounded-none bg-black", image: "h-full w-full object-cover object-center" }
                                }, null, 8, ["src", "alt"]),
                                createVNode("div", { class: "pointer-events-none absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-black/10" })
                              ]),
                              createVNode("div", { class: slideContentClass }, [
                                createVNode("p", { class: "flex min-w-0 items-center gap-2 overflow-hidden text-xs text-ui-muted-foreground" }, [
                                  activeItem.value.source_name ? (openBlock(), createBlock("span", {
                                    key: 0,
                                    class: "min-w-0 truncate text-ui-primary"
                                  }, toDisplayString(activeItem.value.source_name), 1)) : createCommentVNode("", true),
                                  activeItem.value.source_name ? (openBlock(), createBlock("span", {
                                    key: 1,
                                    "aria-hidden": "true"
                                  }, "•")) : createCommentVNode("", true),
                                  createVNode("time", {
                                    class: "shrink-0",
                                    datetime: activeItem.value.published_at
                                  }, toDisplayString(formatPublishedAt(activeItem.value.published_at)), 9, ["datetime"])
                                ]),
                                createVNode("h3", { class: "doomsday-display line-clamp-2 min-h-[3.5rem] overflow-hidden break-words text-xl leading-7 text-white" }, toDisplayString(activeItem.value.title), 1),
                                createVNode("p", { class: "line-clamp-3 min-h-[4.5rem] overflow-hidden break-words text-sm leading-6 text-ui-muted-foreground" }, toDisplayString(activeItem.value.excerpt), 1),
                                createVNode("p", { class: "line-clamp-1 min-h-6 overflow-hidden text-xs font-semibold uppercase tracking-[0.08em] text-white/55" }, toDisplayString(activeItem.value.countdown_title), 1)
                              ])
                            ]),
                            _: 1
                          }, 8, ["href", "target", "rel", "aria-label"]))
                        ]),
                        _: 1
                      }, 8, ["aria-label", "initial", "animate", "exit", "transition"])) : createCommentVNode("", true)
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`</div>`);
              if (hasMultipleItems.value) {
                _push2(`<div class="pointer-events-none absolute inset-x-0 top-0 z-20 aspect-video" aria-hidden="false"${_scopeId}>`);
                _push2(ssrRenderComponent(unref(Button), {
                  variant: "secondary",
                  size: "sm",
                  icon: unref(ChevronLeft),
                  "aria-label": unref(t)("previousNews"),
                  ui: { root: "pointer-events-auto absolute left-3 top-1/2 h-11 min-h-11 w-11 min-w-11 -translate-y-1/2 rounded-full border-white/25 bg-black/75 p-0 text-white shadow-lg backdrop-blur-sm hover:border-ui-primary hover:bg-black/90 focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black" },
                  onClick: previous
                }, null, _parent2, _scopeId));
                _push2(ssrRenderComponent(unref(Button), {
                  variant: "secondary",
                  size: "sm",
                  icon: unref(ChevronRight),
                  "aria-label": unref(t)("nextNews"),
                  ui: { root: "pointer-events-auto absolute right-3 top-1/2 h-11 min-h-11 w-11 min-w-11 -translate-y-1/2 rounded-full border-white/25 bg-black/75 p-0 text-white shadow-lg backdrop-blur-sm hover:border-ui-primary hover:bg-black/90 focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black" },
                  onClick: next
                }, null, _parent2, _scopeId));
                _push2(`</div>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`<div class="${ssrRenderClass(paginationClass)}" role="group"${ssrRenderAttr("aria-label", unref(t)("newsCarouselLabel"))}${_scopeId}>`);
              if (hasMultipleItems.value) {
                _push2(`<!--[-->`);
                ssrRenderList(__props.items, (item, index) => {
                  _push2(ssrRenderComponent(unref(Button), {
                    key: item.id,
                    variant: "secondary",
                    size: "sm",
                    "aria-label": `${unref(t)("newsSlide")} ${index + 1}`,
                    "aria-current": index === currentIndex.value ? "true" : void 0,
                    ui: { root: index === currentIndex.value ? "h-6 min-h-0 w-6 min-w-0 rounded-full border-ui-primary/60 bg-ui-primary/10 p-0 focus-visible:ring-2 focus-visible:ring-ui-primary" : "h-6 min-h-0 w-6 min-w-0 rounded-full border-transparent bg-transparent p-0 hover:bg-white/5 focus-visible:ring-2 focus-visible:ring-ui-primary" },
                    onClick: ($event) => goTo(index)
                  }, {
                    default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                      if (_push3) {
                        _push3(`<span class="${ssrRenderClass(["block h-2 w-2 rounded-full transition-colors", index === currentIndex.value ? "bg-ui-primary" : "bg-white/30"])}" aria-hidden="true"${_scopeId2}></span>`);
                      } else {
                        return [
                          createVNode("span", {
                            class: ["block h-2 w-2 rounded-full transition-colors", index === currentIndex.value ? "bg-ui-primary" : "bg-white/30"],
                            "aria-hidden": "true"
                          }, null, 2)
                        ];
                      }
                    }),
                    _: 2
                  }, _parent2, _scopeId));
                });
                _push2(`<!--]-->`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</div></div>`);
            }
            _push2(`</section>`);
          } else {
            return [
              createVNode("section", {
                ref_key: "carouselRoot",
                ref: carouselRoot,
                class: "min-w-0 outline-none",
                role: "region",
                "aria-roledescription": "carousel",
                "aria-label": unref(t)("newsCarouselLabel"),
                tabindex: "0",
                onMouseenter: ($event) => hoverPaused.value = true,
                onMouseleave: ($event) => hoverPaused.value = false,
                onFocusin: ($event) => focusPaused.value = true,
                onFocusout: handleFocusOut,
                onKeydown: [
                  withKeys(withModifiers(previous, ["prevent"]), ["left"]),
                  withKeys(withModifiers(next, ["prevent"]), ["right"])
                ]
              }, [
                !__props.items.length ? (openBlock(), createBlock("p", {
                  key: 0,
                  class: "min-h-32 px-5 py-8 text-sm leading-relaxed text-ui-muted-foreground"
                }, toDisplayString(unref(t)("latestNewsEmpty")), 1)) : hasPendingSlide.value ? (openBlock(), createBlock("div", {
                  key: 1,
                  "aria-hidden": "true"
                }, [
                  createVNode("div", { class: slideViewportClass }, [
                    createVNode("div", { class: slideSurfaceClass }, [
                      createVNode("div", { class: "relative aspect-video overflow-hidden bg-black" }, [
                        createVNode(unref(SkeletonLoader), {
                          variant: "rect",
                          width: "100%",
                          height: "100%",
                          animated: !unref(reducedMotion),
                          class: "absolute inset-0"
                        }, null, 8, ["animated"])
                      ]),
                      createVNode("div", { class: slideContentClass }, [
                        createVNode(unref(SkeletonLoader), {
                          variant: "text",
                          width: "46%",
                          height: "0.75rem",
                          animated: !unref(reducedMotion)
                        }, null, 8, ["animated"]),
                        createVNode(unref(SkeletonLoader), {
                          variant: "text",
                          lines: 2,
                          height: "1rem",
                          animated: !unref(reducedMotion)
                        }, null, 8, ["animated"]),
                        createVNode(unref(SkeletonLoader), {
                          variant: "text",
                          lines: 3,
                          height: "0.8rem",
                          animated: !unref(reducedMotion)
                        }, null, 8, ["animated"]),
                        createVNode(unref(SkeletonLoader), {
                          variant: "text",
                          width: "38%",
                          height: "0.75rem",
                          animated: !unref(reducedMotion)
                        }, null, 8, ["animated"])
                      ])
                    ])
                  ]),
                  createVNode("div", { class: paginationClass }, [
                    (openBlock(true), createBlock(Fragment, null, renderList(__props.items, (item) => {
                      return openBlock(), createBlock(unref(SkeletonLoader), {
                        key: item.id,
                        variant: "circle",
                        width: "0.5rem",
                        height: "0.5rem",
                        animated: !unref(reducedMotion)
                      }, null, 8, ["animated"]);
                    }), 128))
                  ])
                ])) : (openBlock(), createBlock("div", {
                  key: 2,
                  class: "relative min-w-0",
                  "aria-live": ariaLive.value
                }, [
                  createVNode("div", { class: slideViewportClass }, [
                    createVNode(unref(AnimatePresence), {
                      mode: "wait",
                      initial: false
                    }, {
                      default: withCtx(() => [
                        activeItem.value ? (openBlock(), createBlock(unref(motion).article, {
                          key: activeItem.value.id,
                          class: "min-w-0",
                          role: "group",
                          "aria-roledescription": "slide",
                          "aria-label": `${unref(t)("newsSlide")} ${currentIndex.value + 1} / ${__props.items.length}`,
                          initial: slideInitial.value,
                          animate: slideAnimate.value,
                          exit: slideExit.value,
                          transition: slideTransition.value
                        }, {
                          default: withCtx(() => [
                            (openBlock(), createBlock(resolveDynamicComponent(activeExternalUrl.value ? "a" : unref(Link)), {
                              href: activeExternalUrl.value ?? activeItem.value.countdown_url,
                              target: activeExternalUrl.value ? "_blank" : void 0,
                              rel: activeExternalUrl.value ? "noopener noreferrer" : void 0,
                              "aria-label": primaryLinkLabel(activeItem.value, Boolean(activeExternalUrl.value)),
                              class: slideSurfaceClass
                            }, {
                              default: withCtx(() => [
                                createVNode("div", { class: "relative aspect-video overflow-hidden bg-black" }, [
                                  createVNode(unref(_sfc_main$o), {
                                    src: activeItem.value.image_url,
                                    alt: activeItem.value.title,
                                    "aspect-ratio": "56.25%",
                                    "loading-type": "skeleton",
                                    rounded: "none",
                                    ui: { root: "absolute inset-0 h-full w-full rounded-none bg-black", image: "h-full w-full object-cover object-center" }
                                  }, null, 8, ["src", "alt"]),
                                  createVNode("div", { class: "pointer-events-none absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-black/10" })
                                ]),
                                createVNode("div", { class: slideContentClass }, [
                                  createVNode("p", { class: "flex min-w-0 items-center gap-2 overflow-hidden text-xs text-ui-muted-foreground" }, [
                                    activeItem.value.source_name ? (openBlock(), createBlock("span", {
                                      key: 0,
                                      class: "min-w-0 truncate text-ui-primary"
                                    }, toDisplayString(activeItem.value.source_name), 1)) : createCommentVNode("", true),
                                    activeItem.value.source_name ? (openBlock(), createBlock("span", {
                                      key: 1,
                                      "aria-hidden": "true"
                                    }, "•")) : createCommentVNode("", true),
                                    createVNode("time", {
                                      class: "shrink-0",
                                      datetime: activeItem.value.published_at
                                    }, toDisplayString(formatPublishedAt(activeItem.value.published_at)), 9, ["datetime"])
                                  ]),
                                  createVNode("h3", { class: "doomsday-display line-clamp-2 min-h-[3.5rem] overflow-hidden break-words text-xl leading-7 text-white" }, toDisplayString(activeItem.value.title), 1),
                                  createVNode("p", { class: "line-clamp-3 min-h-[4.5rem] overflow-hidden break-words text-sm leading-6 text-ui-muted-foreground" }, toDisplayString(activeItem.value.excerpt), 1),
                                  createVNode("p", { class: "line-clamp-1 min-h-6 overflow-hidden text-xs font-semibold uppercase tracking-[0.08em] text-white/55" }, toDisplayString(activeItem.value.countdown_title), 1)
                                ])
                              ]),
                              _: 1
                            }, 8, ["href", "target", "rel", "aria-label"]))
                          ]),
                          _: 1
                        }, 8, ["aria-label", "initial", "animate", "exit", "transition"])) : createCommentVNode("", true)
                      ]),
                      _: 1
                    })
                  ]),
                  hasMultipleItems.value ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "pointer-events-none absolute inset-x-0 top-0 z-20 aspect-video",
                    "aria-hidden": "false"
                  }, [
                    createVNode(unref(Button), {
                      variant: "secondary",
                      size: "sm",
                      icon: unref(ChevronLeft),
                      "aria-label": unref(t)("previousNews"),
                      ui: { root: "pointer-events-auto absolute left-3 top-1/2 h-11 min-h-11 w-11 min-w-11 -translate-y-1/2 rounded-full border-white/25 bg-black/75 p-0 text-white shadow-lg backdrop-blur-sm hover:border-ui-primary hover:bg-black/90 focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black" },
                      onClick: previous
                    }, null, 8, ["icon", "aria-label"]),
                    createVNode(unref(Button), {
                      variant: "secondary",
                      size: "sm",
                      icon: unref(ChevronRight),
                      "aria-label": unref(t)("nextNews"),
                      ui: { root: "pointer-events-auto absolute right-3 top-1/2 h-11 min-h-11 w-11 min-w-11 -translate-y-1/2 rounded-full border-white/25 bg-black/75 p-0 text-white shadow-lg backdrop-blur-sm hover:border-ui-primary hover:bg-black/90 focus-visible:ring-2 focus-visible:ring-ui-primary focus-visible:ring-offset-2 focus-visible:ring-offset-black" },
                      onClick: next
                    }, null, 8, ["icon", "aria-label"])
                  ])) : createCommentVNode("", true),
                  createVNode("div", {
                    class: paginationClass,
                    role: "group",
                    "aria-label": unref(t)("newsCarouselLabel")
                  }, [
                    hasMultipleItems.value ? (openBlock(true), createBlock(Fragment, { key: 0 }, renderList(__props.items, (item, index) => {
                      return openBlock(), createBlock(unref(Button), {
                        key: item.id,
                        variant: "secondary",
                        size: "sm",
                        "aria-label": `${unref(t)("newsSlide")} ${index + 1}`,
                        "aria-current": index === currentIndex.value ? "true" : void 0,
                        ui: { root: index === currentIndex.value ? "h-6 min-h-0 w-6 min-w-0 rounded-full border-ui-primary/60 bg-ui-primary/10 p-0 focus-visible:ring-2 focus-visible:ring-ui-primary" : "h-6 min-h-0 w-6 min-w-0 rounded-full border-transparent bg-transparent p-0 hover:bg-white/5 focus-visible:ring-2 focus-visible:ring-ui-primary" },
                        onClick: ($event) => goTo(index)
                      }, {
                        default: withCtx(() => [
                          createVNode("span", {
                            class: ["block h-2 w-2 rounded-full transition-colors", index === currentIndex.value ? "bg-ui-primary" : "bg-white/30"],
                            "aria-hidden": "true"
                          }, null, 2)
                        ]),
                        _: 2
                      }, 1032, ["aria-label", "aria-current", "ui", "onClick"]);
                    }), 128)) : createCommentVNode("", true)
                  ], 8, ["aria-label"])
                ], 8, ["aria-live"]))
              ], 40, ["aria-label", "onMouseenter", "onMouseleave", "onFocusin", "onKeydown"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/LatestNewsCarousel.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const chartWidth = 240;
const chartHeight = 76;
const chartTop = 8;
const chartBaseline = 62;
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "PublicSignalActivityCard",
  __ssrInlineRender: true,
  props: {
    activity: {}
  },
  setup(__props) {
    const props = __props;
    const counts = computed(() => props.activity.bucket_counts.map((value) => {
      const count = Number(value);
      return Number.isFinite(count) && count > 0 ? count : 0;
    }));
    const labels = computed(() => props.activity.bucket_labels.map((value) => String(value)));
    const hasActivity = computed(() => counts.value.some((count) => count > 0));
    const maxCount = computed(() => Math.max(1, ...counts.value));
    const points = computed(() => counts.value.map((count, index, values) => {
      const x = values.length <= 1 ? chartWidth / 2 : index / (values.length - 1) * chartWidth;
      const y = chartBaseline - count / maxCount.value * (chartBaseline - chartTop);
      return { x: Number(x.toFixed(2)), y: Number(y.toFixed(2)), count };
    }));
    const linePoints = computed(() => points.value.map((point) => `${point.x},${point.y}`).join(" "));
    const areaPath = computed(() => {
      const chartPoints = points.value;
      if (!chartPoints.length) {
        return "";
      }
      const first = chartPoints[0];
      const last = chartPoints[chartPoints.length - 1];
      const line = chartPoints.map((point) => `L ${point.x} ${point.y}`).join(" ");
      return `M ${first.x} ${chartBaseline} ${line} L ${last.x} ${chartBaseline} Z`;
    });
    const firstBucketLabel = computed(() => formatDate(labels.value[0] ?? null, { month: "short", day: "numeric" }));
    const lastBucketLabel = computed(() => formatDate(labels.value[labels.value.length - 1] ?? null, { month: "short", day: "numeric" }));
    const latestPublishedLabel = computed(() => formatDate(props.activity.latest_published_at, { dateStyle: "medium" }));
    function formatDate(value, options) {
      if (!value) {
        return "—";
      }
      const date = new Date(value);
      if (Number.isNaN(date.getTime())) {
        return value;
      }
      return new Intl.DateTimeFormat(currentLanguage.value || "en", options).format(date);
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(_sfc_main$m), mergeProps({ ui: { root: "doomsday-card min-w-0 rounded-xl", body: "min-w-0 p-5 sm:p-6" } }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex min-w-0 items-start justify-between gap-4"${_scopeId}><div class="min-w-0"${_scopeId}><p class="doomsday-display text-ui-primary"${_scopeId}>${ssrInterpolate(unref(t)("publicSignalActivity"))}</p><p class="mt-2 text-sm leading-relaxed text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(unref(t)("publicSignalActivitySummary"))}</p></div>`);
            _push2(ssrRenderComponent(unref(Activity), {
              class: "h-5 w-5 shrink-0 text-ui-primary",
              "aria-hidden": "true"
            }, null, _parent2, _scopeId));
            _push2(`</div><div class="mt-5 flex items-end gap-3"${_scopeId}><strong class="doomsday-display text-5xl leading-none text-white"${_scopeId}>${ssrInterpolate(__props.activity.total_items)}</strong><span class="pb-1 text-xs uppercase tracking-[0.08em] text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(unref(t)("publishedItems"))}</span></div><div class="mt-5 min-w-0 rounded-lg border border-white/10 bg-black/35 p-3"${_scopeId}><svg class="h-24 w-full overflow-visible"${ssrRenderAttr("viewBox", `0 0 ${chartWidth} ${chartHeight}`)} role="img"${ssrRenderAttr("aria-label", unref(t)("publicSignalActivityChart"))}${_scopeId}><title${_scopeId}>${ssrInterpolate(unref(t)("publicSignalActivityChart"))}</title><line x1="0"${ssrRenderAttr("y1", chartBaseline)}${ssrRenderAttr("x2", chartWidth)}${ssrRenderAttr("y2", chartBaseline)} stroke="currentColor" class="text-white/15"${_scopeId}></line>`);
            if (areaPath.value) {
              _push2(`<path${ssrRenderAttr("d", areaPath.value)} fill="currentColor" class="text-ui-primary/10"${_scopeId}></path>`);
            } else {
              _push2(`<!---->`);
            }
            if (linePoints.value) {
              _push2(`<polyline${ssrRenderAttr("points", linePoints.value)} fill="none" stroke="currentColor" stroke-width="2" vector-effect="non-scaling-stroke" class="text-ui-primary"${_scopeId}></polyline>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<!--[-->`);
            ssrRenderList(points.value, (point, index) => {
              _push2(`<circle${ssrRenderAttr("cx", point.x)}${ssrRenderAttr("cy", point.y)} r="2.4" fill="currentColor" class="text-ui-primary"${_scopeId}></circle>`);
            });
            _push2(`<!--]--></svg><div class="flex items-center justify-between text-[0.65rem] text-ui-muted-foreground"${_scopeId}><span${_scopeId}>${ssrInterpolate(firstBucketLabel.value)}</span><span${_scopeId}>${ssrInterpolate(lastBucketLabel.value)}</span></div>`);
            if (!hasActivity.value) {
              _push2(`<p class="mt-3 text-xs text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(unref(t)("noSignalActivity"))}</p>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><dl class="mt-5 grid min-w-0 gap-3 text-sm sm:grid-cols-2"${_scopeId}><div class="min-w-0 rounded-lg border border-white/10 bg-white/[0.03] p-3"${_scopeId}><dt class="flex items-center gap-2 text-xs text-ui-muted-foreground"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(RadioTower), {
              class: "h-3.5 w-3.5 text-ui-primary",
              "aria-hidden": "true"
            }, null, _parent2, _scopeId));
            _push2(`${ssrInterpolate(unref(t)("uniqueSources"))}</dt><dd class="mt-1 text-lg font-semibold text-white"${_scopeId}>${ssrInterpolate(__props.activity.unique_sources)}</dd></div><div class="min-w-0 rounded-lg border border-white/10 bg-white/[0.03] p-3"${_scopeId}><dt class="flex items-center gap-2 text-xs text-ui-muted-foreground"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(CalendarDays), {
              class: "h-3.5 w-3.5 text-ui-primary",
              "aria-hidden": "true"
            }, null, _parent2, _scopeId));
            _push2(`${ssrInterpolate(unref(t)("latestPublication"))}</dt><dd class="mt-1 break-words text-sm font-semibold text-white"${_scopeId}>${ssrInterpolate(latestPublishedLabel.value)}</dd></div>`);
            if (__props.activity.top_countdown_title) {
              _push2(`<div class="min-w-0 rounded-lg border border-white/10 bg-white/[0.03] p-3 sm:col-span-2"${_scopeId}><dt class="flex items-center gap-2 text-xs text-ui-muted-foreground"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(Newspaper), {
                class: "h-3.5 w-3.5 text-ui-primary",
                "aria-hidden": "true"
              }, null, _parent2, _scopeId));
              _push2(`${ssrInterpolate(unref(t)("topMonitoredCountdown"))}</dt><dd class="mt-1 flex min-w-0 items-center justify-between gap-3 text-sm font-semibold text-white"${_scopeId}><span class="min-w-0 break-words"${_scopeId}>${ssrInterpolate(__props.activity.top_countdown_title)}</span><span class="shrink-0 tabular-nums text-ui-primary"${_scopeId}>${ssrInterpolate(__props.activity.top_countdown_count)}</span></dd></div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</dl><p class="mt-5 border-t border-white/10 pt-4 text-xs leading-relaxed text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(unref(t)("publicSignalActivityDisclaimer"))}</p>`);
          } else {
            return [
              createVNode("div", { class: "flex min-w-0 items-start justify-between gap-4" }, [
                createVNode("div", { class: "min-w-0" }, [
                  createVNode("p", { class: "doomsday-display text-ui-primary" }, toDisplayString(unref(t)("publicSignalActivity")), 1),
                  createVNode("p", { class: "mt-2 text-sm leading-relaxed text-ui-muted-foreground" }, toDisplayString(unref(t)("publicSignalActivitySummary")), 1)
                ]),
                createVNode(unref(Activity), {
                  class: "h-5 w-5 shrink-0 text-ui-primary",
                  "aria-hidden": "true"
                })
              ]),
              createVNode("div", { class: "mt-5 flex items-end gap-3" }, [
                createVNode("strong", { class: "doomsday-display text-5xl leading-none text-white" }, toDisplayString(__props.activity.total_items), 1),
                createVNode("span", { class: "pb-1 text-xs uppercase tracking-[0.08em] text-ui-muted-foreground" }, toDisplayString(unref(t)("publishedItems")), 1)
              ]),
              createVNode("div", { class: "mt-5 min-w-0 rounded-lg border border-white/10 bg-black/35 p-3" }, [
                (openBlock(), createBlock("svg", {
                  class: "h-24 w-full overflow-visible",
                  viewBox: `0 0 ${chartWidth} ${chartHeight}`,
                  role: "img",
                  "aria-label": unref(t)("publicSignalActivityChart")
                }, [
                  createVNode("title", null, toDisplayString(unref(t)("publicSignalActivityChart")), 1),
                  createVNode("line", {
                    x1: "0",
                    y1: chartBaseline,
                    x2: chartWidth,
                    y2: chartBaseline,
                    stroke: "currentColor",
                    class: "text-white/15"
                  }),
                  areaPath.value ? (openBlock(), createBlock("path", {
                    key: 0,
                    d: areaPath.value,
                    fill: "currentColor",
                    class: "text-ui-primary/10"
                  }, null, 8, ["d"])) : createCommentVNode("", true),
                  linePoints.value ? (openBlock(), createBlock("polyline", {
                    key: 1,
                    points: linePoints.value,
                    fill: "none",
                    stroke: "currentColor",
                    "stroke-width": "2",
                    "vector-effect": "non-scaling-stroke",
                    class: "text-ui-primary"
                  }, null, 8, ["points"])) : createCommentVNode("", true),
                  (openBlock(true), createBlock(Fragment, null, renderList(points.value, (point, index) => {
                    return openBlock(), createBlock("circle", {
                      key: index,
                      cx: point.x,
                      cy: point.y,
                      r: "2.4",
                      fill: "currentColor",
                      class: "text-ui-primary"
                    }, null, 8, ["cx", "cy"]);
                  }), 128))
                ], 8, ["viewBox", "aria-label"])),
                createVNode("div", { class: "flex items-center justify-between text-[0.65rem] text-ui-muted-foreground" }, [
                  createVNode("span", null, toDisplayString(firstBucketLabel.value), 1),
                  createVNode("span", null, toDisplayString(lastBucketLabel.value), 1)
                ]),
                !hasActivity.value ? (openBlock(), createBlock("p", {
                  key: 0,
                  class: "mt-3 text-xs text-ui-muted-foreground"
                }, toDisplayString(unref(t)("noSignalActivity")), 1)) : createCommentVNode("", true)
              ]),
              createVNode("dl", { class: "mt-5 grid min-w-0 gap-3 text-sm sm:grid-cols-2" }, [
                createVNode("div", { class: "min-w-0 rounded-lg border border-white/10 bg-white/[0.03] p-3" }, [
                  createVNode("dt", { class: "flex items-center gap-2 text-xs text-ui-muted-foreground" }, [
                    createVNode(unref(RadioTower), {
                      class: "h-3.5 w-3.5 text-ui-primary",
                      "aria-hidden": "true"
                    }),
                    createTextVNode(toDisplayString(unref(t)("uniqueSources")), 1)
                  ]),
                  createVNode("dd", { class: "mt-1 text-lg font-semibold text-white" }, toDisplayString(__props.activity.unique_sources), 1)
                ]),
                createVNode("div", { class: "min-w-0 rounded-lg border border-white/10 bg-white/[0.03] p-3" }, [
                  createVNode("dt", { class: "flex items-center gap-2 text-xs text-ui-muted-foreground" }, [
                    createVNode(unref(CalendarDays), {
                      class: "h-3.5 w-3.5 text-ui-primary",
                      "aria-hidden": "true"
                    }),
                    createTextVNode(toDisplayString(unref(t)("latestPublication")), 1)
                  ]),
                  createVNode("dd", { class: "mt-1 break-words text-sm font-semibold text-white" }, toDisplayString(latestPublishedLabel.value), 1)
                ]),
                __props.activity.top_countdown_title ? (openBlock(), createBlock("div", {
                  key: 0,
                  class: "min-w-0 rounded-lg border border-white/10 bg-white/[0.03] p-3 sm:col-span-2"
                }, [
                  createVNode("dt", { class: "flex items-center gap-2 text-xs text-ui-muted-foreground" }, [
                    createVNode(unref(Newspaper), {
                      class: "h-3.5 w-3.5 text-ui-primary",
                      "aria-hidden": "true"
                    }),
                    createTextVNode(toDisplayString(unref(t)("topMonitoredCountdown")), 1)
                  ]),
                  createVNode("dd", { class: "mt-1 flex min-w-0 items-center justify-between gap-3 text-sm font-semibold text-white" }, [
                    createVNode("span", { class: "min-w-0 break-words" }, toDisplayString(__props.activity.top_countdown_title), 1),
                    createVNode("span", { class: "shrink-0 tabular-nums text-ui-primary" }, toDisplayString(__props.activity.top_countdown_count), 1)
                  ])
                ])) : createCommentVNode("", true)
              ]),
              createVNode("p", { class: "mt-5 border-t border-white/10 pt-4 text-xs leading-relaxed text-ui-muted-foreground" }, toDisplayString(unref(t)("publicSignalActivityDisclaimer")), 1)
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/PublicSignalActivityCard.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "SidebarCards",
  __ssrInlineRender: true,
  props: {
    sidebar: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<aside${ssrRenderAttrs(mergeProps({ class: "grid min-w-0 content-start gap-5" }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$3, {
        items: __props.sidebar.latest_news
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$2, {
        activity: __props.sidebar.signal_activity
      }, null, _parent));
      _push(`<p class="px-5 text-sm leading-relaxed text-ui-muted-foreground"><span class="text-3xl text-ui-primary">“</span> The end is not sudden. It is a series of events no one wants to believe in—until it is too late.</p></aside>`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/SidebarCards.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
function useDoomsdaySelection(initialSelectedCountdown, currentLocale) {
  const selectedCountdown = ref(initialSelectedCountdown.value);
  const pendingSelectedSlug = ref(null);
  const selectionLoading = ref(false);
  const selectionError = ref(false);
  const overviewCache = /* @__PURE__ */ new Map();
  if (initialSelectedCountdown.value !== null) {
    overviewCache.set(cacheKey(initialSelectedCountdown.value.slug, currentLocale.value), initialSelectedCountdown.value);
  }
  const activeSelectedSlug = computed(() => {
    var _a;
    return pendingSelectedSlug.value ?? ((_a = selectedCountdown.value) == null ? void 0 : _a.slug) ?? null;
  });
  const isReplacingSelection = computed(() => pendingSelectedSlug.value !== null && selectionLoading.value);
  const detailOpen = computed(() => selectedCountdown.value !== null || pendingSelectedSlug.value !== null || selectionLoading.value);
  watch(initialSelectedCountdown, (countdown) => {
    selectedCountdown.value = countdown;
    if (countdown !== null) {
      overviewCache.set(cacheKey(countdown.slug, currentLocale.value), countdown);
    }
  });
  async function selectCountdown(countdown) {
    if (activeSelectedSlug.value === countdown.slug && !selectionLoading.value) {
      closeSelectedCountdown();
      return;
    }
    const requestedSlug = countdown.slug;
    const requestedLocale = currentLocale.value;
    const key = cacheKey(requestedSlug, requestedLocale);
    const cached = overviewCache.get(key);
    selectionError.value = false;
    pendingSelectedSlug.value = requestedSlug;
    if (cached !== void 0) {
      selectedCountdown.value = cached;
      pendingSelectedSlug.value = null;
      selectionLoading.value = false;
      return;
    }
    selectionLoading.value = true;
    try {
      const response = await axios.get(route("countdowns.data.overview", {
        slug: requestedSlug,
        lang: requestedLocale
      }));
      if (pendingSelectedSlug.value === requestedSlug && currentLocale.value === requestedLocale) {
        overviewCache.set(key, response.data.data);
        selectedCountdown.value = response.data.data;
        pendingSelectedSlug.value = null;
      }
    } catch {
      if (pendingSelectedSlug.value === requestedSlug) {
        selectionError.value = true;
        pendingSelectedSlug.value = null;
      }
    } finally {
      if (pendingSelectedSlug.value === requestedSlug || pendingSelectedSlug.value === null) {
        selectionLoading.value = false;
      }
    }
  }
  function closeSelectedCountdown() {
    selectedCountdown.value = null;
    pendingSelectedSlug.value = null;
    selectionLoading.value = false;
    selectionError.value = false;
  }
  function cacheKey(slug, locale) {
    return `${slug}:${locale}`;
  }
  return {
    selectedCountdown,
    activeSelectedSlug,
    pendingSelectedSlug,
    detailOpen,
    isReplacingSelection,
    selectionLoading,
    selectionError,
    selectCountdown,
    closeSelectedCountdown
  };
}
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Home",
  __ssrInlineRender: true,
  props: {
    page: {},
    selected_countdown: {},
    forecast_section: {},
    statistics_section: {},
    news_section: {},
    initiatives_section: {}
  },
  setup(__props) {
    const props = __props;
    const hero = computed(() => props.page.hero);
    const initialSelectedCountdown = computed(() => props.selected_countdown ?? null);
    const currentLocale = computed(() => props.page.current_locale);
    const selection = useDoomsdaySelection(initialSelectedCountdown, currentLocale);
    const reducedMotion = useDoomsdayReducedMotion();
    const pageStateMotion = computed(() => resolveMotionPreset(panelReveal, reducedMotion.value));
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Home" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$r, {
        languages: __props.page.languages,
        "current-locale": __props.page.current_locale,
        "hide-mobile-header": unref(selection).detailOpen.value,
        "active-page": "home"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(AnimatePresence), {
              mode: "wait",
              initial: false
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (unref(selection).detailOpen.value) {
                    _push3(ssrRenderComponent(unref(motion).div, {
                      key: "detail",
                      class: "min-h-0",
                      initial: pageStateMotion.value.initial,
                      animate: pageStateMotion.value.animate,
                      exit: pageStateMotion.value.exit,
                      transition: pageStateMotion.value.transition
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          if (unref(selection).selectedCountdown.value && !unref(selection).isReplacingSelection.value) {
                            _push4(ssrRenderComponent(_sfc_main$7, {
                              countdown: unref(selection).selectedCountdown.value,
                              "current-locale": __props.page.current_locale,
                              "forecast-section": __props.forecast_section ?? null,
                              "statistics-section": __props.statistics_section ?? null,
                              "news-section": __props.news_section ?? null,
                              "initiatives-section": __props.initiatives_section ?? null,
                              onClose: unref(selection).closeSelectedCountdown
                            }, null, _parent4, _scopeId3));
                          } else {
                            _push4(ssrRenderComponent(_sfc_main$e, {
                              onClose: unref(selection).closeSelectedCountdown
                            }, null, _parent4, _scopeId3));
                          }
                          _push4(ssrRenderComponent(_sfc_main$4, {
                            countdowns: __props.page.countdowns,
                            "selected-countdown": unref(selection).selectedCountdown.value,
                            hero: hero.value,
                            "current-locale": __props.page.current_locale,
                            "forecast-section": __props.forecast_section ?? null,
                            "statistics-section": __props.statistics_section ?? null,
                            "news-section": __props.news_section ?? null,
                            "initiatives-section": __props.initiatives_section ?? null,
                            "selected-slug": unref(selection).activeSelectedSlug.value,
                            "pending-slug": unref(selection).pendingSelectedSlug.value,
                            "is-loading-selection": unref(selection).isReplacingSelection.value,
                            onSelect: unref(selection).selectCountdown,
                            onClose: unref(selection).closeSelectedCountdown
                          }, null, _parent4, _scopeId3));
                        } else {
                          return [
                            unref(selection).selectedCountdown.value && !unref(selection).isReplacingSelection.value ? (openBlock(), createBlock(_sfc_main$7, {
                              key: 0,
                              countdown: unref(selection).selectedCountdown.value,
                              "current-locale": __props.page.current_locale,
                              "forecast-section": __props.forecast_section ?? null,
                              "statistics-section": __props.statistics_section ?? null,
                              "news-section": __props.news_section ?? null,
                              "initiatives-section": __props.initiatives_section ?? null,
                              onClose: unref(selection).closeSelectedCountdown
                            }, null, 8, ["countdown", "current-locale", "forecast-section", "statistics-section", "news-section", "initiatives-section", "onClose"])) : (openBlock(), createBlock(_sfc_main$e, {
                              key: 1,
                              onClose: unref(selection).closeSelectedCountdown
                            }, null, 8, ["onClose"])),
                            createVNode(_sfc_main$4, {
                              countdowns: __props.page.countdowns,
                              "selected-countdown": unref(selection).selectedCountdown.value,
                              hero: hero.value,
                              "current-locale": __props.page.current_locale,
                              "forecast-section": __props.forecast_section ?? null,
                              "statistics-section": __props.statistics_section ?? null,
                              "news-section": __props.news_section ?? null,
                              "initiatives-section": __props.initiatives_section ?? null,
                              "selected-slug": unref(selection).activeSelectedSlug.value,
                              "pending-slug": unref(selection).pendingSelectedSlug.value,
                              "is-loading-selection": unref(selection).isReplacingSelection.value,
                              onSelect: unref(selection).selectCountdown,
                              onClose: unref(selection).closeSelectedCountdown
                            }, null, 8, ["countdowns", "selected-countdown", "hero", "current-locale", "forecast-section", "statistics-section", "news-section", "initiatives-section", "selected-slug", "pending-slug", "is-loading-selection", "onSelect", "onClose"])
                          ];
                        }
                      }),
                      _: 1
                    }, _parent3, _scopeId2));
                  } else {
                    _push3(ssrRenderComponent(unref(motion).div, {
                      key: "overview",
                      class: "min-h-0",
                      initial: pageStateMotion.value.initial,
                      animate: pageStateMotion.value.animate,
                      exit: pageStateMotion.value.exit,
                      transition: pageStateMotion.value.transition
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(ssrRenderComponent(_sfc_main$g, { hero: hero.value }, null, _parent4, _scopeId3));
                          _push4(`<div class="mx-auto grid items-start max-w-[1760px] gap-5 px-4 py-7 sm:px-7 lg:grid-cols-[minmax(0,1fr)_560px] 2xl:grid-cols-[minmax(0,1fr)_600px]"${_scopeId3}><div class="grid content-start items-start gap-5"${_scopeId3}>`);
                          _push4(ssrRenderComponent(_sfc_main$h, {
                            countdowns: __props.page.countdowns,
                            "selected-slug": unref(selection).activeSelectedSlug.value,
                            "pending-slug": unref(selection).pendingSelectedSlug.value,
                            onSelect: unref(selection).selectCountdown
                          }, null, _parent4, _scopeId3));
                          _push4(`</div>`);
                          _push4(ssrRenderComponent(_sfc_main$1, {
                            class: "hidden lg:grid",
                            sidebar: __props.page.sidebar
                          }, null, _parent4, _scopeId3));
                          _push4(`</div><footer class="mx-auto flex max-w-[1760px] items-center justify-between px-4 pb-8 text-xs text-ui-muted-foreground sm:px-7"${_scopeId3}><span${_scopeId3}>All countdowns are editorial estimates based on public-source scenario data.</span>`);
                          _push4(ssrRenderComponent(unref(Link), {
                            href: unref(P)("about", { lang: __props.page.current_locale }),
                            class: "text-ui-primary"
                          }, {
                            default: withCtx((_4, _push5, _parent5, _scopeId4) => {
                              if (_push5) {
                                _push5(`Learn more about our methodology`);
                              } else {
                                return [
                                  createTextVNode("Learn more about our methodology")
                                ];
                              }
                            }),
                            _: 1
                          }, _parent4, _scopeId3));
                          _push4(`</footer>`);
                        } else {
                          return [
                            createVNode(_sfc_main$g, { hero: hero.value }, null, 8, ["hero"]),
                            createVNode("div", { class: "mx-auto grid items-start max-w-[1760px] gap-5 px-4 py-7 sm:px-7 lg:grid-cols-[minmax(0,1fr)_560px] 2xl:grid-cols-[minmax(0,1fr)_600px]" }, [
                              createVNode("div", { class: "grid content-start items-start gap-5" }, [
                                createVNode(_sfc_main$h, {
                                  countdowns: __props.page.countdowns,
                                  "selected-slug": unref(selection).activeSelectedSlug.value,
                                  "pending-slug": unref(selection).pendingSelectedSlug.value,
                                  onSelect: unref(selection).selectCountdown
                                }, null, 8, ["countdowns", "selected-slug", "pending-slug", "onSelect"])
                              ]),
                              createVNode(_sfc_main$1, {
                                class: "hidden lg:grid",
                                sidebar: __props.page.sidebar
                              }, null, 8, ["sidebar"])
                            ]),
                            createVNode("footer", { class: "mx-auto flex max-w-[1760px] items-center justify-between px-4 pb-8 text-xs text-ui-muted-foreground sm:px-7" }, [
                              createVNode("span", null, "All countdowns are editorial estimates based on public-source scenario data."),
                              createVNode(unref(Link), {
                                href: unref(P)("about", { lang: __props.page.current_locale }),
                                class: "text-ui-primary"
                              }, {
                                default: withCtx(() => [
                                  createTextVNode("Learn more about our methodology")
                                ]),
                                _: 1
                              }, 8, ["href"])
                            ])
                          ];
                        }
                      }),
                      _: 1
                    }, _parent3, _scopeId2));
                  }
                } else {
                  return [
                    unref(selection).detailOpen.value ? (openBlock(), createBlock(unref(motion).div, {
                      key: "detail",
                      class: "min-h-0",
                      initial: pageStateMotion.value.initial,
                      animate: pageStateMotion.value.animate,
                      exit: pageStateMotion.value.exit,
                      transition: pageStateMotion.value.transition
                    }, {
                      default: withCtx(() => [
                        unref(selection).selectedCountdown.value && !unref(selection).isReplacingSelection.value ? (openBlock(), createBlock(_sfc_main$7, {
                          key: 0,
                          countdown: unref(selection).selectedCountdown.value,
                          "current-locale": __props.page.current_locale,
                          "forecast-section": __props.forecast_section ?? null,
                          "statistics-section": __props.statistics_section ?? null,
                          "news-section": __props.news_section ?? null,
                          "initiatives-section": __props.initiatives_section ?? null,
                          onClose: unref(selection).closeSelectedCountdown
                        }, null, 8, ["countdown", "current-locale", "forecast-section", "statistics-section", "news-section", "initiatives-section", "onClose"])) : (openBlock(), createBlock(_sfc_main$e, {
                          key: 1,
                          onClose: unref(selection).closeSelectedCountdown
                        }, null, 8, ["onClose"])),
                        createVNode(_sfc_main$4, {
                          countdowns: __props.page.countdowns,
                          "selected-countdown": unref(selection).selectedCountdown.value,
                          hero: hero.value,
                          "current-locale": __props.page.current_locale,
                          "forecast-section": __props.forecast_section ?? null,
                          "statistics-section": __props.statistics_section ?? null,
                          "news-section": __props.news_section ?? null,
                          "initiatives-section": __props.initiatives_section ?? null,
                          "selected-slug": unref(selection).activeSelectedSlug.value,
                          "pending-slug": unref(selection).pendingSelectedSlug.value,
                          "is-loading-selection": unref(selection).isReplacingSelection.value,
                          onSelect: unref(selection).selectCountdown,
                          onClose: unref(selection).closeSelectedCountdown
                        }, null, 8, ["countdowns", "selected-countdown", "hero", "current-locale", "forecast-section", "statistics-section", "news-section", "initiatives-section", "selected-slug", "pending-slug", "is-loading-selection", "onSelect", "onClose"])
                      ]),
                      _: 1
                    }, 8, ["initial", "animate", "exit", "transition"])) : (openBlock(), createBlock(unref(motion).div, {
                      key: "overview",
                      class: "min-h-0",
                      initial: pageStateMotion.value.initial,
                      animate: pageStateMotion.value.animate,
                      exit: pageStateMotion.value.exit,
                      transition: pageStateMotion.value.transition
                    }, {
                      default: withCtx(() => [
                        createVNode(_sfc_main$g, { hero: hero.value }, null, 8, ["hero"]),
                        createVNode("div", { class: "mx-auto grid items-start max-w-[1760px] gap-5 px-4 py-7 sm:px-7 lg:grid-cols-[minmax(0,1fr)_560px] 2xl:grid-cols-[minmax(0,1fr)_600px]" }, [
                          createVNode("div", { class: "grid content-start items-start gap-5" }, [
                            createVNode(_sfc_main$h, {
                              countdowns: __props.page.countdowns,
                              "selected-slug": unref(selection).activeSelectedSlug.value,
                              "pending-slug": unref(selection).pendingSelectedSlug.value,
                              onSelect: unref(selection).selectCountdown
                            }, null, 8, ["countdowns", "selected-slug", "pending-slug", "onSelect"])
                          ]),
                          createVNode(_sfc_main$1, {
                            class: "hidden lg:grid",
                            sidebar: __props.page.sidebar
                          }, null, 8, ["sidebar"])
                        ]),
                        createVNode("footer", { class: "mx-auto flex max-w-[1760px] items-center justify-between px-4 pb-8 text-xs text-ui-muted-foreground sm:px-7" }, [
                          createVNode("span", null, "All countdowns are editorial estimates based on public-source scenario data."),
                          createVNode(unref(Link), {
                            href: unref(P)("about", { lang: __props.page.current_locale }),
                            class: "text-ui-primary"
                          }, {
                            default: withCtx(() => [
                              createTextVNode("Learn more about our methodology")
                            ]),
                            _: 1
                          }, 8, ["href"])
                        ])
                      ]),
                      _: 1
                    }, 8, ["initial", "animate", "exit", "transition"]))
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(AnimatePresence), {
                mode: "wait",
                initial: false
              }, {
                default: withCtx(() => [
                  unref(selection).detailOpen.value ? (openBlock(), createBlock(unref(motion).div, {
                    key: "detail",
                    class: "min-h-0",
                    initial: pageStateMotion.value.initial,
                    animate: pageStateMotion.value.animate,
                    exit: pageStateMotion.value.exit,
                    transition: pageStateMotion.value.transition
                  }, {
                    default: withCtx(() => [
                      unref(selection).selectedCountdown.value && !unref(selection).isReplacingSelection.value ? (openBlock(), createBlock(_sfc_main$7, {
                        key: 0,
                        countdown: unref(selection).selectedCountdown.value,
                        "current-locale": __props.page.current_locale,
                        "forecast-section": __props.forecast_section ?? null,
                        "statistics-section": __props.statistics_section ?? null,
                        "news-section": __props.news_section ?? null,
                        "initiatives-section": __props.initiatives_section ?? null,
                        onClose: unref(selection).closeSelectedCountdown
                      }, null, 8, ["countdown", "current-locale", "forecast-section", "statistics-section", "news-section", "initiatives-section", "onClose"])) : (openBlock(), createBlock(_sfc_main$e, {
                        key: 1,
                        onClose: unref(selection).closeSelectedCountdown
                      }, null, 8, ["onClose"])),
                      createVNode(_sfc_main$4, {
                        countdowns: __props.page.countdowns,
                        "selected-countdown": unref(selection).selectedCountdown.value,
                        hero: hero.value,
                        "current-locale": __props.page.current_locale,
                        "forecast-section": __props.forecast_section ?? null,
                        "statistics-section": __props.statistics_section ?? null,
                        "news-section": __props.news_section ?? null,
                        "initiatives-section": __props.initiatives_section ?? null,
                        "selected-slug": unref(selection).activeSelectedSlug.value,
                        "pending-slug": unref(selection).pendingSelectedSlug.value,
                        "is-loading-selection": unref(selection).isReplacingSelection.value,
                        onSelect: unref(selection).selectCountdown,
                        onClose: unref(selection).closeSelectedCountdown
                      }, null, 8, ["countdowns", "selected-countdown", "hero", "current-locale", "forecast-section", "statistics-section", "news-section", "initiatives-section", "selected-slug", "pending-slug", "is-loading-selection", "onSelect", "onClose"])
                    ]),
                    _: 1
                  }, 8, ["initial", "animate", "exit", "transition"])) : (openBlock(), createBlock(unref(motion).div, {
                    key: "overview",
                    class: "min-h-0",
                    initial: pageStateMotion.value.initial,
                    animate: pageStateMotion.value.animate,
                    exit: pageStateMotion.value.exit,
                    transition: pageStateMotion.value.transition
                  }, {
                    default: withCtx(() => [
                      createVNode(_sfc_main$g, { hero: hero.value }, null, 8, ["hero"]),
                      createVNode("div", { class: "mx-auto grid items-start max-w-[1760px] gap-5 px-4 py-7 sm:px-7 lg:grid-cols-[minmax(0,1fr)_560px] 2xl:grid-cols-[minmax(0,1fr)_600px]" }, [
                        createVNode("div", { class: "grid content-start items-start gap-5" }, [
                          createVNode(_sfc_main$h, {
                            countdowns: __props.page.countdowns,
                            "selected-slug": unref(selection).activeSelectedSlug.value,
                            "pending-slug": unref(selection).pendingSelectedSlug.value,
                            onSelect: unref(selection).selectCountdown
                          }, null, 8, ["countdowns", "selected-slug", "pending-slug", "onSelect"])
                        ]),
                        createVNode(_sfc_main$1, {
                          class: "hidden lg:grid",
                          sidebar: __props.page.sidebar
                        }, null, 8, ["sidebar"])
                      ]),
                      createVNode("footer", { class: "mx-auto flex max-w-[1760px] items-center justify-between px-4 pb-8 text-xs text-ui-muted-foreground sm:px-7" }, [
                        createVNode("span", null, "All countdowns are editorial estimates based on public-source scenario data."),
                        createVNode(unref(Link), {
                          href: unref(P)("about", { lang: __props.page.current_locale }),
                          class: "text-ui-primary"
                        }, {
                          default: withCtx(() => [
                            createTextVNode("Learn more about our methodology")
                          ]),
                          _: 1
                        }, 8, ["href"])
                      ])
                    ]),
                    _: 1
                  }, 8, ["initial", "animate", "exit", "transition"]))
                ]),
                _: 1
              })
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Doomsday/Home.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
