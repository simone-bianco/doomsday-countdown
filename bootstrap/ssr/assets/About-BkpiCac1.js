import { defineComponent, computed, unref, mergeProps, withCtx, createVNode, toDisplayString, useSSRContext, openBlock, createBlock, Fragment, renderList, createTextVNode, resolveDynamicComponent } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttrs, ssrRenderList, ssrRenderVNode } from "vue/server-renderer";
import { Head } from "@inertiajs/vue3";
import { h as _sfc_main$6, q as _sfc_main$7, e as _sfc_main$a, r as _sfc_main$b, s as _sfc_main$d } from "../ssr.js";
import { motion } from "motion-v";
import { _ as _sfc_main$8, a as _sfc_main$9, b as _sfc_main$c, c as _sfc_main$e } from "./PublicLayout-Cg7TjPHG.js";
import { u as useDoomsdayReducedMotion, r as resolveMotionPreset, f as fadeUp, w as withMotionDelay, c as cardStaggerDelay, a as cardReveal, b as fadeIn } from "./doomsdayMotion-D6KS_x2N.js";
import { HelpCircle, MessageCircleWarning, Orbit, RadioTower, Radar, Globe2, ShieldAlert, Activity, Eye } from "lucide-vue-next";
import "@vue/server-renderer";
import "clsx";
import "tailwind-merge";
import "vue-advanced-cropper";
import "node:async_hooks";
import "i18next";
const _sfc_main$5 = /* @__PURE__ */ defineComponent({
  __name: "AboutClosingBand",
  __ssrInlineRender: true,
  props: {
    label: {},
    title: {},
    body: {}
  },
  setup(__props) {
    const reducedMotion = useDoomsdayReducedMotion();
    const motionPreset = computed(() => resolveMotionPreset(fadeUp, reducedMotion.value));
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(motion).section, mergeProps({
        initial: motionPreset.value.initial,
        animate: motionPreset.value.animate,
        transition: motionPreset.value.transition
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$6), { ui: { root: "doomsday-card doomsday-glow rounded-[2rem]", body: "relative overflow-hidden p-7 sm:p-10 lg:p-12" } }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_40%,rgba(255,42,35,0.20),transparent_34%)]"${_scopeId2}></div><div class="relative grid gap-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-end"${_scopeId2}><div${_scopeId2}><div class="flex items-center gap-3 text-ui-primary"${_scopeId2}>`);
                  _push3(ssrRenderComponent(unref(_sfc_main$7), {
                    color: "error",
                    pulse: "",
                    size: "lg"
                  }, null, _parent3, _scopeId2));
                  _push3(`<span class="doomsday-display text-xs"${_scopeId2}>${ssrInterpolate(__props.label)}</span></div><h2 class="doomsday-display mt-5 text-3xl leading-tight text-white sm:text-5xl"${_scopeId2}>${ssrInterpolate(__props.title)}</h2></div><div class="grid gap-5"${_scopeId2}><p class="max-w-4xl text-base leading-relaxed text-white/70 sm:text-xl"${_scopeId2}>${ssrInterpolate(__props.body)}</p>`);
                  _push3(ssrRenderComponent(_sfc_main$8, { placement: "about" }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(_sfc_main$9, null, null, _parent3, _scopeId2));
                  _push3(`</div></div>`);
                } else {
                  return [
                    createVNode("div", { class: "absolute inset-0 bg-[radial-gradient(circle_at_85%_40%,rgba(255,42,35,0.20),transparent_34%)]" }),
                    createVNode("div", { class: "relative grid gap-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-end" }, [
                      createVNode("div", null, [
                        createVNode("div", { class: "flex items-center gap-3 text-ui-primary" }, [
                          createVNode(unref(_sfc_main$7), {
                            color: "error",
                            pulse: "",
                            size: "lg"
                          }),
                          createVNode("span", { class: "doomsday-display text-xs" }, toDisplayString(__props.label), 1)
                        ]),
                        createVNode("h2", { class: "doomsday-display mt-5 text-3xl leading-tight text-white sm:text-5xl" }, toDisplayString(__props.title), 1)
                      ]),
                      createVNode("div", { class: "grid gap-5" }, [
                        createVNode("p", { class: "max-w-4xl text-base leading-relaxed text-white/70 sm:text-xl" }, toDisplayString(__props.body), 1),
                        createVNode(_sfc_main$8, { placement: "about" }),
                        createVNode(_sfc_main$9)
                      ])
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$6), { ui: { root: "doomsday-card doomsday-glow rounded-[2rem]", body: "relative overflow-hidden p-7 sm:p-10 lg:p-12" } }, {
                default: withCtx(() => [
                  createVNode("div", { class: "absolute inset-0 bg-[radial-gradient(circle_at_85%_40%,rgba(255,42,35,0.20),transparent_34%)]" }),
                  createVNode("div", { class: "relative grid gap-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-end" }, [
                    createVNode("div", null, [
                      createVNode("div", { class: "flex items-center gap-3 text-ui-primary" }, [
                        createVNode(unref(_sfc_main$7), {
                          color: "error",
                          pulse: "",
                          size: "lg"
                        }),
                        createVNode("span", { class: "doomsday-display text-xs" }, toDisplayString(__props.label), 1)
                      ]),
                      createVNode("h2", { class: "doomsday-display mt-5 text-3xl leading-tight text-white sm:text-5xl" }, toDisplayString(__props.title), 1)
                    ]),
                    createVNode("div", { class: "grid gap-5" }, [
                      createVNode("p", { class: "max-w-4xl text-base leading-relaxed text-white/70 sm:text-xl" }, toDisplayString(__props.body), 1),
                      createVNode(_sfc_main$8, { placement: "about" }),
                      createVNode(_sfc_main$9)
                    ])
                  ])
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
});
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/AboutClosingBand.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const _sfc_main$4 = /* @__PURE__ */ defineComponent({
  __name: "AboutFAQSection",
  __ssrInlineRender: true,
  props: {
    title: {},
    subtitle: {},
    items: {}
  },
  setup(__props) {
    const props = __props;
    const reducedMotion = useDoomsdayReducedMotion();
    const items = computed(() => props.items ?? []);
    function motionFor(index) {
      return resolveMotionPreset(withMotionDelay(cardReveal, cardStaggerDelay(index)), reducedMotion.value);
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "grid gap-5 lg:grid-cols-[0.36fr_0.64fr]" }, _attrs))}>`);
      _push(ssrRenderComponent(unref(_sfc_main$6), { ui: { root: "doomsday-card rounded-[2rem]", body: "relative overflow-hidden p-6 sm:p-8" } }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-ui-primary/10 blur-3xl"${_scopeId}></div>`);
            _push2(ssrRenderComponent(unref(_sfc_main$a), {
              label: "FAQ",
              icon: unref(HelpCircle),
              variant: "soft",
              color: "primary",
              size: "md"
            }, null, _parent2, _scopeId));
            _push2(`<h2 class="doomsday-display mt-7 text-4xl leading-none text-white sm:text-5xl"${_scopeId}>${ssrInterpolate(__props.title)}</h2><p class="mt-5 text-sm leading-relaxed text-white/58 sm:text-base"${_scopeId}>${ssrInterpolate(__props.subtitle)}</p>`);
          } else {
            return [
              createVNode("div", { class: "absolute -right-16 -top-16 h-44 w-44 rounded-full bg-ui-primary/10 blur-3xl" }),
              createVNode(unref(_sfc_main$a), {
                label: "FAQ",
                icon: unref(HelpCircle),
                variant: "soft",
                color: "primary",
                size: "md"
              }, null, 8, ["icon"]),
              createVNode("h2", { class: "doomsday-display mt-7 text-4xl leading-none text-white sm:text-5xl" }, toDisplayString(__props.title), 1),
              createVNode("p", { class: "mt-5 text-sm leading-relaxed text-white/58 sm:text-base" }, toDisplayString(__props.subtitle), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<div class="grid gap-3"><!--[-->`);
      ssrRenderList(items.value, (item, index) => {
        _push(ssrRenderComponent(unref(motion).div, {
          key: item.question,
          initial: motionFor(index).initial,
          animate: motionFor(index).animate,
          transition: motionFor(index).transition
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(ssrRenderComponent(unref(_sfc_main$b), {
                title: item.question,
                "default-open": index === 0,
                icon: unref(MessageCircleWarning),
                ui: { root: "doomsday-card rounded-2xl border-white/10 bg-black/60", header: "p-5 hover:bg-ui-primary/10", headerTitle: "doomsday-display text-left text-sm leading-relaxed text-white sm:text-base", headerIcon: "text-ui-primary", chevron: "text-ui-primary", content: "px-5 pb-5" }
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<p class="border-t border-white/10 pt-4 text-sm leading-relaxed text-white/64 sm:text-base"${_scopeId2}>${ssrInterpolate(item.answer)}</p>`);
                  } else {
                    return [
                      createVNode("p", { class: "border-t border-white/10 pt-4 text-sm leading-relaxed text-white/64 sm:text-base" }, toDisplayString(item.answer), 1)
                    ];
                  }
                }),
                _: 2
              }, _parent2, _scopeId));
            } else {
              return [
                createVNode(unref(_sfc_main$b), {
                  title: item.question,
                  "default-open": index === 0,
                  icon: unref(MessageCircleWarning),
                  ui: { root: "doomsday-card rounded-2xl border-white/10 bg-black/60", header: "p-5 hover:bg-ui-primary/10", headerTitle: "doomsday-display text-left text-sm leading-relaxed text-white sm:text-base", headerIcon: "text-ui-primary", chevron: "text-ui-primary", content: "px-5 pb-5" }
                }, {
                  default: withCtx(() => [
                    createVNode("p", { class: "border-t border-white/10 pt-4 text-sm leading-relaxed text-white/64 sm:text-base" }, toDisplayString(item.answer), 1)
                  ]),
                  _: 2
                }, 1032, ["title", "default-open", "icon"])
              ];
            }
          }),
          _: 2
        }, _parent));
      });
      _push(`<!--]--></div></section>`);
    };
  }
});
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/AboutFAQSection.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const _sfc_main$3 = /* @__PURE__ */ defineComponent({
  __name: "AboutGreatFilterSection",
  __ssrInlineRender: true,
  props: {
    eyebrow: {},
    pipelineLabel: {},
    timeline: {}
  },
  setup(__props) {
    const reducedMotion = useDoomsdayReducedMotion();
    const panelMotion = computed(() => resolveMotionPreset(fadeUp, reducedMotion.value));
    const timelineMotion = computed(() => resolveMotionPreset(withMotionDelay(fadeUp, 0.08), reducedMotion.value));
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(motion).section, mergeProps({
        class: "grid gap-6 lg:grid-cols-[0.95fr_1.05fr]",
        initial: panelMotion.value.initial,
        animate: panelMotion.value.animate,
        transition: panelMotion.value.transition
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$6), { ui: { root: "doomsday-card rounded-[2rem]", body: "relative min-h-[430px] overflow-hidden p-0" } }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                var _a, _b;
                if (_push3) {
                  _push3(ssrRenderComponent(_sfc_main$c, {
                    src: "/images/doomsday/society_collapse_separate.png",
                    alt: "Civilization risk visual",
                    sizes: "(min-width: 1024px) 46vw, 100vw",
                    loading: "lazy",
                    "fetch-priority": "low",
                    "picture-class": "absolute inset-0 block h-full w-full",
                    "img-class": "h-full w-full object-cover object-center opacity-70"
                  }, null, _parent3, _scopeId2));
                  _push3(`<div class="absolute inset-0 bg-gradient-to-br from-black/20 via-black/72 to-black"${_scopeId2}></div><div class="absolute inset-0 bg-[radial-gradient(circle_at_22%_22%,rgba(255,42,35,0.25),transparent_32%)]"${_scopeId2}></div><div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8"${_scopeId2}>`);
                  _push3(ssrRenderComponent(unref(_sfc_main$a), {
                    label: "Great Filter",
                    icon: unref(Orbit),
                    variant: "gradient",
                    size: "md"
                  }, null, _parent3, _scopeId2));
                  _push3(`<h2 class="doomsday-display mt-6 max-w-2xl text-3xl leading-tight text-white sm:text-5xl"${_scopeId2}>${ssrInterpolate(__props.eyebrow)}</h2><p class="mt-5 max-w-xl text-sm leading-relaxed text-white/62 sm:text-base"${_scopeId2}>${ssrInterpolate((_a = __props.timeline[0]) == null ? void 0 : _a.body)}</p></div>`);
                } else {
                  return [
                    createVNode(_sfc_main$c, {
                      src: "/images/doomsday/society_collapse_separate.png",
                      alt: "Civilization risk visual",
                      sizes: "(min-width: 1024px) 46vw, 100vw",
                      loading: "lazy",
                      "fetch-priority": "low",
                      "picture-class": "absolute inset-0 block h-full w-full",
                      "img-class": "h-full w-full object-cover object-center opacity-70"
                    }),
                    createVNode("div", { class: "absolute inset-0 bg-gradient-to-br from-black/20 via-black/72 to-black" }),
                    createVNode("div", { class: "absolute inset-0 bg-[radial-gradient(circle_at_22%_22%,rgba(255,42,35,0.25),transparent_32%)]" }),
                    createVNode("div", { class: "absolute bottom-0 left-0 right-0 p-6 sm:p-8" }, [
                      createVNode(unref(_sfc_main$a), {
                        label: "Great Filter",
                        icon: unref(Orbit),
                        variant: "gradient",
                        size: "md"
                      }, null, 8, ["icon"]),
                      createVNode("h2", { class: "doomsday-display mt-6 max-w-2xl text-3xl leading-tight text-white sm:text-5xl" }, toDisplayString(__props.eyebrow), 1),
                      createVNode("p", { class: "mt-5 max-w-xl text-sm leading-relaxed text-white/62 sm:text-base" }, toDisplayString((_b = __props.timeline[0]) == null ? void 0 : _b.body), 1)
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(_sfc_main$6), { ui: { root: "doomsday-card rounded-[2rem]", body: "relative overflow-hidden p-6 sm:p-8" } }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="absolute right-8 top-8 hidden h-32 w-32 rounded-full border border-ui-primary/20 lg:block doomsday-about-orbit-slow"${_scopeId2}></div><div class="relative flex items-center gap-3 text-ui-primary"${_scopeId2}>`);
                  _push3(ssrRenderComponent(unref(RadioTower), {
                    class: "h-6 w-6",
                    "aria-hidden": "true"
                  }, null, _parent3, _scopeId2));
                  _push3(`<span class="doomsday-display text-xs"${_scopeId2}>${ssrInterpolate(__props.pipelineLabel)}</span></div><div class="relative mt-8 grid gap-5"${_scopeId2}><div class="absolute bottom-8 left-[1.18rem] top-8 w-px bg-gradient-to-b from-ui-primary via-ui-primary/40 to-transparent"${_scopeId2}></div><!--[-->`);
                  ssrRenderList(__props.timeline, (item) => {
                    _push3(ssrRenderComponent(unref(motion).div, {
                      key: item.label,
                      class: "relative grid grid-cols-[2.4rem_minmax(0,1fr)] gap-4",
                      initial: timelineMotion.value.initial,
                      animate: timelineMotion.value.animate,
                      transition: timelineMotion.value.transition
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`<div class="relative z-10 flex h-10 w-10 items-center justify-center rounded-full border border-ui-primary/60 bg-black text-ui-primary shadow-[0_0_24px_rgba(255,42,35,0.18)]"${_scopeId3}><span class="doomsday-display text-xs"${_scopeId3}>${ssrInterpolate(item.label)}</span></div><div class="rounded-2xl border border-white/10 bg-black/42 p-5"${_scopeId3}><div class="flex items-start justify-between gap-4"${_scopeId3}><h3 class="doomsday-display text-lg leading-tight text-white"${_scopeId3}>${ssrInterpolate(item.title)}</h3>`);
                          _push4(ssrRenderComponent(unref(_sfc_main$7), {
                            color: "error",
                            pulse: "",
                            size: "sm"
                          }, null, _parent4, _scopeId3));
                          _push4(`</div><p class="mt-3 text-sm leading-relaxed text-white/60"${_scopeId3}>${ssrInterpolate(item.body)}</p></div>`);
                        } else {
                          return [
                            createVNode("div", { class: "relative z-10 flex h-10 w-10 items-center justify-center rounded-full border border-ui-primary/60 bg-black text-ui-primary shadow-[0_0_24px_rgba(255,42,35,0.18)]" }, [
                              createVNode("span", { class: "doomsday-display text-xs" }, toDisplayString(item.label), 1)
                            ]),
                            createVNode("div", { class: "rounded-2xl border border-white/10 bg-black/42 p-5" }, [
                              createVNode("div", { class: "flex items-start justify-between gap-4" }, [
                                createVNode("h3", { class: "doomsday-display text-lg leading-tight text-white" }, toDisplayString(item.title), 1),
                                createVNode(unref(_sfc_main$7), {
                                  color: "error",
                                  pulse: "",
                                  size: "sm"
                                })
                              ]),
                              createVNode("p", { class: "mt-3 text-sm leading-relaxed text-white/60" }, toDisplayString(item.body), 1)
                            ])
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                  });
                  _push3(`<!--]--></div>`);
                } else {
                  return [
                    createVNode("div", { class: "absolute right-8 top-8 hidden h-32 w-32 rounded-full border border-ui-primary/20 lg:block doomsday-about-orbit-slow" }),
                    createVNode("div", { class: "relative flex items-center gap-3 text-ui-primary" }, [
                      createVNode(unref(RadioTower), {
                        class: "h-6 w-6",
                        "aria-hidden": "true"
                      }),
                      createVNode("span", { class: "doomsday-display text-xs" }, toDisplayString(__props.pipelineLabel), 1)
                    ]),
                    createVNode("div", { class: "relative mt-8 grid gap-5" }, [
                      createVNode("div", { class: "absolute bottom-8 left-[1.18rem] top-8 w-px bg-gradient-to-b from-ui-primary via-ui-primary/40 to-transparent" }),
                      (openBlock(true), createBlock(Fragment, null, renderList(__props.timeline, (item) => {
                        return openBlock(), createBlock(unref(motion).div, {
                          key: item.label,
                          class: "relative grid grid-cols-[2.4rem_minmax(0,1fr)] gap-4",
                          initial: timelineMotion.value.initial,
                          animate: timelineMotion.value.animate,
                          transition: timelineMotion.value.transition
                        }, {
                          default: withCtx(() => [
                            createVNode("div", { class: "relative z-10 flex h-10 w-10 items-center justify-center rounded-full border border-ui-primary/60 bg-black text-ui-primary shadow-[0_0_24px_rgba(255,42,35,0.18)]" }, [
                              createVNode("span", { class: "doomsday-display text-xs" }, toDisplayString(item.label), 1)
                            ]),
                            createVNode("div", { class: "rounded-2xl border border-white/10 bg-black/42 p-5" }, [
                              createVNode("div", { class: "flex items-start justify-between gap-4" }, [
                                createVNode("h3", { class: "doomsday-display text-lg leading-tight text-white" }, toDisplayString(item.title), 1),
                                createVNode(unref(_sfc_main$7), {
                                  color: "error",
                                  pulse: "",
                                  size: "sm"
                                })
                              ]),
                              createVNode("p", { class: "mt-3 text-sm leading-relaxed text-white/60" }, toDisplayString(item.body), 1)
                            ])
                          ]),
                          _: 2
                        }, 1032, ["initial", "animate", "transition"]);
                      }), 128))
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$6), { ui: { root: "doomsday-card rounded-[2rem]", body: "relative min-h-[430px] overflow-hidden p-0" } }, {
                default: withCtx(() => {
                  var _a;
                  return [
                    createVNode(_sfc_main$c, {
                      src: "/images/doomsday/society_collapse_separate.png",
                      alt: "Civilization risk visual",
                      sizes: "(min-width: 1024px) 46vw, 100vw",
                      loading: "lazy",
                      "fetch-priority": "low",
                      "picture-class": "absolute inset-0 block h-full w-full",
                      "img-class": "h-full w-full object-cover object-center opacity-70"
                    }),
                    createVNode("div", { class: "absolute inset-0 bg-gradient-to-br from-black/20 via-black/72 to-black" }),
                    createVNode("div", { class: "absolute inset-0 bg-[radial-gradient(circle_at_22%_22%,rgba(255,42,35,0.25),transparent_32%)]" }),
                    createVNode("div", { class: "absolute bottom-0 left-0 right-0 p-6 sm:p-8" }, [
                      createVNode(unref(_sfc_main$a), {
                        label: "Great Filter",
                        icon: unref(Orbit),
                        variant: "gradient",
                        size: "md"
                      }, null, 8, ["icon"]),
                      createVNode("h2", { class: "doomsday-display mt-6 max-w-2xl text-3xl leading-tight text-white sm:text-5xl" }, toDisplayString(__props.eyebrow), 1),
                      createVNode("p", { class: "mt-5 max-w-xl text-sm leading-relaxed text-white/62 sm:text-base" }, toDisplayString((_a = __props.timeline[0]) == null ? void 0 : _a.body), 1)
                    ])
                  ];
                }),
                _: 1
              }),
              createVNode(unref(_sfc_main$6), { ui: { root: "doomsday-card rounded-[2rem]", body: "relative overflow-hidden p-6 sm:p-8" } }, {
                default: withCtx(() => [
                  createVNode("div", { class: "absolute right-8 top-8 hidden h-32 w-32 rounded-full border border-ui-primary/20 lg:block doomsday-about-orbit-slow" }),
                  createVNode("div", { class: "relative flex items-center gap-3 text-ui-primary" }, [
                    createVNode(unref(RadioTower), {
                      class: "h-6 w-6",
                      "aria-hidden": "true"
                    }),
                    createVNode("span", { class: "doomsday-display text-xs" }, toDisplayString(__props.pipelineLabel), 1)
                  ]),
                  createVNode("div", { class: "relative mt-8 grid gap-5" }, [
                    createVNode("div", { class: "absolute bottom-8 left-[1.18rem] top-8 w-px bg-gradient-to-b from-ui-primary via-ui-primary/40 to-transparent" }),
                    (openBlock(true), createBlock(Fragment, null, renderList(__props.timeline, (item) => {
                      return openBlock(), createBlock(unref(motion).div, {
                        key: item.label,
                        class: "relative grid grid-cols-[2.4rem_minmax(0,1fr)] gap-4",
                        initial: timelineMotion.value.initial,
                        animate: timelineMotion.value.animate,
                        transition: timelineMotion.value.transition
                      }, {
                        default: withCtx(() => [
                          createVNode("div", { class: "relative z-10 flex h-10 w-10 items-center justify-center rounded-full border border-ui-primary/60 bg-black text-ui-primary shadow-[0_0_24px_rgba(255,42,35,0.18)]" }, [
                            createVNode("span", { class: "doomsday-display text-xs" }, toDisplayString(item.label), 1)
                          ]),
                          createVNode("div", { class: "rounded-2xl border border-white/10 bg-black/42 p-5" }, [
                            createVNode("div", { class: "flex items-start justify-between gap-4" }, [
                              createVNode("h3", { class: "doomsday-display text-lg leading-tight text-white" }, toDisplayString(item.title), 1),
                              createVNode(unref(_sfc_main$7), {
                                color: "error",
                                pulse: "",
                                size: "sm"
                              })
                            ]),
                            createVNode("p", { class: "mt-3 text-sm leading-relaxed text-white/60" }, toDisplayString(item.body), 1)
                          ])
                        ]),
                        _: 2
                      }, 1032, ["initial", "animate", "transition"]);
                    }), 128))
                  ])
                ]),
                _: 1
              })
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/AboutGreatFilterSection.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "AboutHero",
  __ssrInlineRender: true,
  props: {
    page: {}
  },
  setup(__props) {
    const props = __props;
    const reducedMotion = useDoomsdayReducedMotion();
    const lineMotion = computed(() => resolveMotionPreset(fadeIn, reducedMotion.value));
    const titleMotion = computed(() => resolveMotionPreset(fadeUp, reducedMotion.value));
    const bodyMotion = computed(() => resolveMotionPreset(withMotionDelay(fadeUp, 0.07), reducedMotion.value));
    const visualMotion = computed(() => resolveMotionPreset(withMotionDelay(fadeUp, 0.12), reducedMotion.value));
    const intro = computed(() => props.page.intro ?? []);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "grid items-stretch gap-6 lg:grid-cols-[minmax(0,1.05fr)_minmax(360px,0.95fr)]" }, _attrs))}>`);
      _push(ssrRenderComponent(unref(motion).div, {
        initial: titleMotion.value.initial,
        animate: titleMotion.value.animate,
        transition: titleMotion.value.transition
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$6), { ui: { root: "doomsday-card doomsday-glow h-full rounded-[2rem]", body: "relative overflow-hidden p-6 sm:p-9 lg:p-12" } }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_8%,rgba(255,42,35,0.22),transparent_34%)]"${_scopeId2}></div><div class="absolute -right-24 top-12 h-72 w-72 rounded-full border border-ui-primary/20 blur-[1px]"${_scopeId2}></div><div class="relative"${_scopeId2}>`);
                  _push3(ssrRenderComponent(unref(motion).div, {
                    class: "mb-8 h-px w-28 bg-ui-primary/90",
                    initial: lineMotion.value.initial,
                    animate: lineMotion.value.animate,
                    transition: lineMotion.value.transition
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(unref(_sfc_main$a), {
                    label: __props.page.hero_badge,
                    icon: unref(Radar),
                    dot: "",
                    pulse: "",
                    variant: "soft",
                    color: "primary",
                    size: "md",
                    ui: { root: "bg-black/55 tracking-[0.18em]" }
                  }, null, _parent3, _scopeId2));
                  _push3(`<p class="doomsday-display mt-7 max-w-3xl text-xs text-ui-primary/90 sm:text-sm"${_scopeId2}>${ssrInterpolate(__props.page.eyebrow)}</p><h1 class="doomsday-display mt-4 max-w-5xl text-[clamp(2.65rem,7vw,7.25rem)] leading-[0.92] text-white drop-shadow-[0_0_24px_rgba(255,42,35,0.18)]"${_scopeId2}>${ssrInterpolate(__props.page.title)}</h1><p class="mt-7 max-w-4xl text-[clamp(1.05rem,2vw,1.5rem)] leading-relaxed text-white/78"${_scopeId2}>${ssrInterpolate(__props.page.subtitle)}</p></div>`);
                } else {
                  return [
                    createVNode("div", { class: "absolute inset-0 bg-[radial-gradient(circle_at_18%_8%,rgba(255,42,35,0.22),transparent_34%)]" }),
                    createVNode("div", { class: "absolute -right-24 top-12 h-72 w-72 rounded-full border border-ui-primary/20 blur-[1px]" }),
                    createVNode("div", { class: "relative" }, [
                      createVNode(unref(motion).div, {
                        class: "mb-8 h-px w-28 bg-ui-primary/90",
                        initial: lineMotion.value.initial,
                        animate: lineMotion.value.animate,
                        transition: lineMotion.value.transition
                      }, null, 8, ["initial", "animate", "transition"]),
                      createVNode(unref(_sfc_main$a), {
                        label: __props.page.hero_badge,
                        icon: unref(Radar),
                        dot: "",
                        pulse: "",
                        variant: "soft",
                        color: "primary",
                        size: "md",
                        ui: { root: "bg-black/55 tracking-[0.18em]" }
                      }, null, 8, ["label", "icon"]),
                      createVNode("p", { class: "doomsday-display mt-7 max-w-3xl text-xs text-ui-primary/90 sm:text-sm" }, toDisplayString(__props.page.eyebrow), 1),
                      createVNode("h1", { class: "doomsday-display mt-4 max-w-5xl text-[clamp(2.65rem,7vw,7.25rem)] leading-[0.92] text-white drop-shadow-[0_0_24px_rgba(255,42,35,0.18)]" }, toDisplayString(__props.page.title), 1),
                      createVNode("p", { class: "mt-7 max-w-4xl text-[clamp(1.05rem,2vw,1.5rem)] leading-relaxed text-white/78" }, toDisplayString(__props.page.subtitle), 1)
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$6), { ui: { root: "doomsday-card doomsday-glow h-full rounded-[2rem]", body: "relative overflow-hidden p-6 sm:p-9 lg:p-12" } }, {
                default: withCtx(() => [
                  createVNode("div", { class: "absolute inset-0 bg-[radial-gradient(circle_at_18%_8%,rgba(255,42,35,0.22),transparent_34%)]" }),
                  createVNode("div", { class: "absolute -right-24 top-12 h-72 w-72 rounded-full border border-ui-primary/20 blur-[1px]" }),
                  createVNode("div", { class: "relative" }, [
                    createVNode(unref(motion).div, {
                      class: "mb-8 h-px w-28 bg-ui-primary/90",
                      initial: lineMotion.value.initial,
                      animate: lineMotion.value.animate,
                      transition: lineMotion.value.transition
                    }, null, 8, ["initial", "animate", "transition"]),
                    createVNode(unref(_sfc_main$a), {
                      label: __props.page.hero_badge,
                      icon: unref(Radar),
                      dot: "",
                      pulse: "",
                      variant: "soft",
                      color: "primary",
                      size: "md",
                      ui: { root: "bg-black/55 tracking-[0.18em]" }
                    }, null, 8, ["label", "icon"]),
                    createVNode("p", { class: "doomsday-display mt-7 max-w-3xl text-xs text-ui-primary/90 sm:text-sm" }, toDisplayString(__props.page.eyebrow), 1),
                    createVNode("h1", { class: "doomsday-display mt-4 max-w-5xl text-[clamp(2.65rem,7vw,7.25rem)] leading-[0.92] text-white drop-shadow-[0_0_24px_rgba(255,42,35,0.18)]" }, toDisplayString(__props.page.title), 1),
                    createVNode("p", { class: "mt-7 max-w-4xl text-[clamp(1.05rem,2vw,1.5rem)] leading-relaxed text-white/78" }, toDisplayString(__props.page.subtitle), 1)
                  ])
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(motion).div, {
        initial: visualMotion.value.initial,
        animate: visualMotion.value.animate,
        transition: visualMotion.value.transition
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$6), { ui: { root: "doomsday-card h-full rounded-[2rem]", body: "relative h-full min-h-[520px] overflow-hidden p-0" } }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(_sfc_main$c, {
                    src: "/images/doomsday/uninhabitable_earth_separate.png",
                    alt: "Doomsday scenario visual",
                    sizes: "(min-width: 1024px) 46vw, 100vw",
                    loading: "eager",
                    "fetch-priority": "high",
                    "picture-class": "absolute inset-0 block h-full w-full",
                    "img-class": "h-full w-full object-cover object-center opacity-65"
                  }, null, _parent3, _scopeId2));
                  _push3(`<div class="absolute inset-0 bg-gradient-to-b from-black/35 via-black/50 to-black/95"${_scopeId2}></div><div class="absolute inset-6 rounded-full border border-ui-primary/20 doomsday-about-orbit"${_scopeId2}></div><div class="absolute inset-14 rounded-full border border-white/10 doomsday-about-orbit doomsday-about-orbit-slow"${_scopeId2}></div><div class="absolute left-1/2 top-1/2 h-28 w-28 -translate-x-1/2 -translate-y-1/2 rounded-full border border-ui-primary/80 bg-ui-primary/10 shadow-[0_0_80px_rgba(255,42,35,0.28)]"${_scopeId2}></div><div class="absolute inset-x-8 top-1/2 h-px bg-ui-primary/50 doomsday-about-scan"${_scopeId2}></div><div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8"${_scopeId2}><div class="flex items-center gap-3 text-xs uppercase tracking-[0.22em] text-white/65"${_scopeId2}>`);
                  _push3(ssrRenderComponent(unref(_sfc_main$7), {
                    color: "error",
                    pulse: "",
                    size: "lg"
                  }, null, _parent3, _scopeId2));
                  _push3(`<span class="doomsday-display"${_scopeId2}>${ssrInterpolate(__props.page.filter_watch_label)}</span></div><div class="mt-6 grid gap-4"${_scopeId2}><!--[-->`);
                  ssrRenderList(intro.value, (paragraph) => {
                    _push3(ssrRenderComponent(unref(motion).p, {
                      key: paragraph,
                      class: "text-base leading-relaxed text-white/78 sm:text-lg",
                      initial: bodyMotion.value.initial,
                      animate: bodyMotion.value.animate,
                      transition: bodyMotion.value.transition
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`${ssrInterpolate(paragraph)}`);
                        } else {
                          return [
                            createTextVNode(toDisplayString(paragraph), 1)
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                  });
                  _push3(`<!--]--></div><div class="mt-7 flex items-center gap-3 text-ui-primary"${_scopeId2}>`);
                  _push3(ssrRenderComponent(unref(Globe2), {
                    class: "h-5 w-5",
                    "aria-hidden": "true"
                  }, null, _parent3, _scopeId2));
                  _push3(`<span class="doomsday-display text-xs"${_scopeId2}>${ssrInterpolate(__props.page.visual_label)}</span></div></div>`);
                } else {
                  return [
                    createVNode(_sfc_main$c, {
                      src: "/images/doomsday/uninhabitable_earth_separate.png",
                      alt: "Doomsday scenario visual",
                      sizes: "(min-width: 1024px) 46vw, 100vw",
                      loading: "eager",
                      "fetch-priority": "high",
                      "picture-class": "absolute inset-0 block h-full w-full",
                      "img-class": "h-full w-full object-cover object-center opacity-65"
                    }),
                    createVNode("div", { class: "absolute inset-0 bg-gradient-to-b from-black/35 via-black/50 to-black/95" }),
                    createVNode("div", { class: "absolute inset-6 rounded-full border border-ui-primary/20 doomsday-about-orbit" }),
                    createVNode("div", { class: "absolute inset-14 rounded-full border border-white/10 doomsday-about-orbit doomsday-about-orbit-slow" }),
                    createVNode("div", { class: "absolute left-1/2 top-1/2 h-28 w-28 -translate-x-1/2 -translate-y-1/2 rounded-full border border-ui-primary/80 bg-ui-primary/10 shadow-[0_0_80px_rgba(255,42,35,0.28)]" }),
                    createVNode("div", { class: "absolute inset-x-8 top-1/2 h-px bg-ui-primary/50 doomsday-about-scan" }),
                    createVNode("div", { class: "absolute bottom-0 left-0 right-0 p-6 sm:p-8" }, [
                      createVNode("div", { class: "flex items-center gap-3 text-xs uppercase tracking-[0.22em] text-white/65" }, [
                        createVNode(unref(_sfc_main$7), {
                          color: "error",
                          pulse: "",
                          size: "lg"
                        }),
                        createVNode("span", { class: "doomsday-display" }, toDisplayString(__props.page.filter_watch_label), 1)
                      ]),
                      createVNode("div", { class: "mt-6 grid gap-4" }, [
                        (openBlock(true), createBlock(Fragment, null, renderList(intro.value, (paragraph) => {
                          return openBlock(), createBlock(unref(motion).p, {
                            key: paragraph,
                            class: "text-base leading-relaxed text-white/78 sm:text-lg",
                            initial: bodyMotion.value.initial,
                            animate: bodyMotion.value.animate,
                            transition: bodyMotion.value.transition
                          }, {
                            default: withCtx(() => [
                              createTextVNode(toDisplayString(paragraph), 1)
                            ]),
                            _: 2
                          }, 1032, ["initial", "animate", "transition"]);
                        }), 128))
                      ]),
                      createVNode("div", { class: "mt-7 flex items-center gap-3 text-ui-primary" }, [
                        createVNode(unref(Globe2), {
                          class: "h-5 w-5",
                          "aria-hidden": "true"
                        }),
                        createVNode("span", { class: "doomsday-display text-xs" }, toDisplayString(__props.page.visual_label), 1)
                      ])
                    ])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$6), { ui: { root: "doomsday-card h-full rounded-[2rem]", body: "relative h-full min-h-[520px] overflow-hidden p-0" } }, {
                default: withCtx(() => [
                  createVNode(_sfc_main$c, {
                    src: "/images/doomsday/uninhabitable_earth_separate.png",
                    alt: "Doomsday scenario visual",
                    sizes: "(min-width: 1024px) 46vw, 100vw",
                    loading: "eager",
                    "fetch-priority": "high",
                    "picture-class": "absolute inset-0 block h-full w-full",
                    "img-class": "h-full w-full object-cover object-center opacity-65"
                  }),
                  createVNode("div", { class: "absolute inset-0 bg-gradient-to-b from-black/35 via-black/50 to-black/95" }),
                  createVNode("div", { class: "absolute inset-6 rounded-full border border-ui-primary/20 doomsday-about-orbit" }),
                  createVNode("div", { class: "absolute inset-14 rounded-full border border-white/10 doomsday-about-orbit doomsday-about-orbit-slow" }),
                  createVNode("div", { class: "absolute left-1/2 top-1/2 h-28 w-28 -translate-x-1/2 -translate-y-1/2 rounded-full border border-ui-primary/80 bg-ui-primary/10 shadow-[0_0_80px_rgba(255,42,35,0.28)]" }),
                  createVNode("div", { class: "absolute inset-x-8 top-1/2 h-px bg-ui-primary/50 doomsday-about-scan" }),
                  createVNode("div", { class: "absolute bottom-0 left-0 right-0 p-6 sm:p-8" }, [
                    createVNode("div", { class: "flex items-center gap-3 text-xs uppercase tracking-[0.22em] text-white/65" }, [
                      createVNode(unref(_sfc_main$7), {
                        color: "error",
                        pulse: "",
                        size: "lg"
                      }),
                      createVNode("span", { class: "doomsday-display" }, toDisplayString(__props.page.filter_watch_label), 1)
                    ]),
                    createVNode("div", { class: "mt-6 grid gap-4" }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(intro.value, (paragraph) => {
                        return openBlock(), createBlock(unref(motion).p, {
                          key: paragraph,
                          class: "text-base leading-relaxed text-white/78 sm:text-lg",
                          initial: bodyMotion.value.initial,
                          animate: bodyMotion.value.animate,
                          transition: bodyMotion.value.transition
                        }, {
                          default: withCtx(() => [
                            createTextVNode(toDisplayString(paragraph), 1)
                          ]),
                          _: 2
                        }, 1032, ["initial", "animate", "transition"]);
                      }), 128))
                    ]),
                    createVNode("div", { class: "mt-7 flex items-center gap-3 text-ui-primary" }, [
                      createVNode(unref(Globe2), {
                        class: "h-5 w-5",
                        "aria-hidden": "true"
                      }),
                      createVNode("span", { class: "doomsday-display text-xs" }, toDisplayString(__props.page.visual_label), 1)
                    ])
                  ])
                ]),
                _: 1
              })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</section>`);
    };
  }
});
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/AboutHero.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "AboutSignalGrid",
  __ssrInlineRender: true,
  props: {
    stats: {},
    sections: {}
  },
  setup(__props) {
    const props = __props;
    const statIcons = [Radar, ShieldAlert, Activity];
    const sectionIcons = [Eye, Activity, ShieldAlert];
    const reducedMotion = useDoomsdayReducedMotion();
    const stats = computed(() => props.stats ?? []);
    const sections = computed(() => props.sections ?? []);
    function motionFor(index) {
      return resolveMotionPreset(withMotionDelay(cardReveal, cardStaggerDelay(index)), reducedMotion.value);
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "grid gap-5" }, _attrs))}><div class="grid gap-4 md:grid-cols-3"><!--[-->`);
      ssrRenderList(stats.value, (stat, index) => {
        _push(ssrRenderComponent(unref(motion).div, {
          key: stat.label,
          initial: motionFor(index).initial,
          animate: motionFor(index).animate,
          transition: motionFor(index).transition
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(ssrRenderComponent(unref(_sfc_main$d), {
                label: stat.label,
                value: stat.value,
                description: stat.description,
                icon: statIcons[index % statIcons.length],
                color: "rose",
                ui: { root: "doomsday-card rounded-2xl border-white/10 bg-black/55 p-5 hover:border-ui-primary/50", label: "doomsday-display text-ui-primary/80", value: "doomsday-display text-3xl text-white sm:text-4xl", description: "text-sm leading-relaxed text-white/58", icon: "bg-ui-primary/12 text-ui-primary" }
              }, null, _parent2, _scopeId));
            } else {
              return [
                createVNode(unref(_sfc_main$d), {
                  label: stat.label,
                  value: stat.value,
                  description: stat.description,
                  icon: statIcons[index % statIcons.length],
                  color: "rose",
                  ui: { root: "doomsday-card rounded-2xl border-white/10 bg-black/55 p-5 hover:border-ui-primary/50", label: "doomsday-display text-ui-primary/80", value: "doomsday-display text-3xl text-white sm:text-4xl", description: "text-sm leading-relaxed text-white/58", icon: "bg-ui-primary/12 text-ui-primary" }
                }, null, 8, ["label", "value", "description", "icon"])
              ];
            }
          }),
          _: 2
        }, _parent));
      });
      _push(`<!--]--></div><div class="grid gap-5 lg:grid-cols-3"><!--[-->`);
      ssrRenderList(sections.value, (section, index) => {
        _push(ssrRenderComponent(unref(motion).div, {
          key: section.title,
          initial: motionFor(index + stats.value.length).initial,
          animate: motionFor(index + stats.value.length).animate,
          transition: motionFor(index + stats.value.length).transition
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(ssrRenderComponent(unref(_sfc_main$6), { ui: { root: "doomsday-card group h-full rounded-2xl transition duration-300 hover:-translate-y-1 hover:border-ui-primary/55 hover:shadow-[0_0_34px_rgba(255,42,35,0.12)]", body: "relative h-full overflow-hidden p-6 sm:p-7" } }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`<div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-ui-primary/60 to-transparent opacity-0 transition group-hover:opacity-100"${_scopeId2}></div>`);
                    ssrRenderVNode(_push3, createVNode(resolveDynamicComponent(sectionIcons[index % sectionIcons.length]), {
                      class: "h-6 w-6 text-ui-primary",
                      "aria-hidden": "true"
                    }, null), _parent3, _scopeId2);
                    _push3(`<h2 class="doomsday-display mt-5 text-xl leading-tight text-white"${_scopeId2}>${ssrInterpolate(section.title)}</h2><p class="mt-5 text-sm leading-relaxed text-white/62 sm:text-base"${_scopeId2}>${ssrInterpolate(section.body)}</p>`);
                  } else {
                    return [
                      createVNode("div", { class: "absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-ui-primary/60 to-transparent opacity-0 transition group-hover:opacity-100" }),
                      (openBlock(), createBlock(resolveDynamicComponent(sectionIcons[index % sectionIcons.length]), {
                        class: "h-6 w-6 text-ui-primary",
                        "aria-hidden": "true"
                      })),
                      createVNode("h2", { class: "doomsday-display mt-5 text-xl leading-tight text-white" }, toDisplayString(section.title), 1),
                      createVNode("p", { class: "mt-5 text-sm leading-relaxed text-white/62 sm:text-base" }, toDisplayString(section.body), 1)
                    ];
                  }
                }),
                _: 2
              }, _parent2, _scopeId));
            } else {
              return [
                createVNode(unref(_sfc_main$6), { ui: { root: "doomsday-card group h-full rounded-2xl transition duration-300 hover:-translate-y-1 hover:border-ui-primary/55 hover:shadow-[0_0_34px_rgba(255,42,35,0.12)]", body: "relative h-full overflow-hidden p-6 sm:p-7" } }, {
                  default: withCtx(() => [
                    createVNode("div", { class: "absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-ui-primary/60 to-transparent opacity-0 transition group-hover:opacity-100" }),
                    (openBlock(), createBlock(resolveDynamicComponent(sectionIcons[index % sectionIcons.length]), {
                      class: "h-6 w-6 text-ui-primary",
                      "aria-hidden": "true"
                    })),
                    createVNode("h2", { class: "doomsday-display mt-5 text-xl leading-tight text-white" }, toDisplayString(section.title), 1),
                    createVNode("p", { class: "mt-5 text-sm leading-relaxed text-white/62 sm:text-base" }, toDisplayString(section.body), 1)
                  ]),
                  _: 2
                }, 1024)
              ];
            }
          }),
          _: 2
        }, _parent));
      });
      _push(`<!--]--></div></section>`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/AboutSignalGrid.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "About",
  __ssrInlineRender: true,
  props: {
    page: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: __props.page.title
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$e, {
        languages: __props.page.languages,
        "current-locale": __props.page.current_locale,
        "active-page": "about"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="mx-auto grid max-w-[1760px] gap-7 px-4 py-7 sm:px-7 lg:gap-10 lg:py-12"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$2, { page: __props.page }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$1, {
              stats: __props.page.stats,
              sections: __props.page.sections
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$3, {
              eyebrow: __props.page.eyebrow,
              "pipeline-label": __props.page.pipeline_label,
              timeline: __props.page.timeline
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$4, {
              title: __props.page.faq_title,
              subtitle: __props.page.faq_subtitle,
              items: __props.page.faq
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$5, {
              label: __props.page.closing_label,
              title: __props.page.closing_title,
              body: __props.page.closing_body
            }, null, _parent2, _scopeId));
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "mx-auto grid max-w-[1760px] gap-7 px-4 py-7 sm:px-7 lg:gap-10 lg:py-12" }, [
                createVNode(_sfc_main$2, { page: __props.page }, null, 8, ["page"]),
                createVNode(_sfc_main$1, {
                  stats: __props.page.stats,
                  sections: __props.page.sections
                }, null, 8, ["stats", "sections"]),
                createVNode(_sfc_main$3, {
                  eyebrow: __props.page.eyebrow,
                  "pipeline-label": __props.page.pipeline_label,
                  timeline: __props.page.timeline
                }, null, 8, ["eyebrow", "pipeline-label", "timeline"]),
                createVNode(_sfc_main$4, {
                  title: __props.page.faq_title,
                  subtitle: __props.page.faq_subtitle,
                  items: __props.page.faq
                }, null, 8, ["title", "subtitle", "items"]),
                createVNode(_sfc_main$5, {
                  label: __props.page.closing_label,
                  title: __props.page.closing_title,
                  body: __props.page.closing_body
                }, null, 8, ["label", "title", "body"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Doomsday/About.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
