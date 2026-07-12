import { defineComponent, computed, mergeProps, unref, withCtx, createVNode, resolveDynamicComponent, openBlock, createBlock, toDisplayString, createTextVNode, useSSRContext, ref } from "vue";
import { ssrRenderAttrs, ssrRenderList, ssrRenderComponent, ssrRenderVNode, ssrInterpolate } from "vue/server-renderer";
import { router, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$6 } from "./BackofficeShell-B2o2xGeb.js";
import { Target, BarChart3, Newspaper, Users, Activity, KeyRound, Clock3 } from "lucide-vue-next";
import { h as _sfc_main$2, k as _sfc_main$3, e as _sfc_main$4, j as _sfc_main$5, B as Button } from "../ssr.js";
import "./AppLayout-CiOlqxSX.js";
import "@vue/server-renderer";
import "clsx";
import "tailwind-merge";
import "vue-advanced-cropper";
import "node:async_hooks";
import "i18next";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "DashboardOverview",
  __ssrInlineRender: true,
  props: {
    backofficePath: {},
    metrics: {},
    recentCountdowns: {}
  },
  setup(__props) {
    const props = __props;
    const normalizedBackofficePath = computed(() => props.backofficePath.replace(/\/+$/g, ""));
    const metricCards = computed(() => [
      { label: "Countdowns", value: props.metrics.countdowns, helper: `${props.metrics.published} published · ${props.metrics.drafts} drafts`, icon: Target },
      { label: "Relations", value: props.metrics.projections + props.metrics.visualizations, helper: `${props.metrics.projections} projections · ${props.metrics.visualizations} visualizations`, icon: BarChart3 },
      { label: "Content", value: props.metrics.news + props.metrics.initiatives, helper: `${props.metrics.news} news · ${props.metrics.initiatives} initiatives`, icon: Newspaper },
      { label: "Administration", value: props.metrics.users, helper: `${props.metrics.activeApiKeys} active API keys`, icon: Users }
    ]);
    const rows = computed(() => props.recentCountdowns.map((countdown) => ({ ...countdown })));
    const columns = [
      { key: "countdown", label: "Recent countdowns", class: "flex-[2]", headerClass: "flex-[2]" },
      { key: "status", label: "Status", class: "flex-1", headerClass: "flex-1" },
      { key: "relations", label: "Relations", class: "flex-[2]", headerClass: "flex-[2]" }
    ];
    function visit(url) {
      router.visit(url);
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "space-y-6" }, _attrs))}><div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4"><!--[-->`);
      ssrRenderList(metricCards.value, (metric) => {
        _push(ssrRenderComponent(unref(_sfc_main$2), {
          key: metric.label,
          ui: { body: "flex items-start gap-4 p-5" }
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<div class="rounded-2xl bg-ui-primary/10 p-3 text-ui-primary"${_scopeId}>`);
              ssrRenderVNode(_push2, createVNode(resolveDynamicComponent(metric.icon), { class: "h-6 w-6" }, null), _parent2, _scopeId);
              _push2(`</div><div class="min-w-0"${_scopeId}><p class="text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(metric.label)}</p><p class="text-3xl font-bold tracking-tight"${_scopeId}>${ssrInterpolate(metric.value)}</p><p class="mt-1 text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(metric.helper)}</p></div>`);
            } else {
              return [
                createVNode("div", { class: "rounded-2xl bg-ui-primary/10 p-3 text-ui-primary" }, [
                  (openBlock(), createBlock(resolveDynamicComponent(metric.icon), { class: "h-6 w-6" }))
                ]),
                createVNode("div", { class: "min-w-0" }, [
                  createVNode("p", { class: "text-sm text-ui-muted-foreground" }, toDisplayString(metric.label), 1),
                  createVNode("p", { class: "text-3xl font-bold tracking-tight" }, toDisplayString(metric.value), 1),
                  createVNode("p", { class: "mt-1 text-sm text-ui-muted-foreground" }, toDisplayString(metric.helper), 1)
                ])
              ];
            }
          }),
          _: 2
        }, _parent));
      });
      _push(`<!--]--></div><div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_22rem]">`);
      if (rows.value.length > 0) {
        _push(ssrRenderComponent(unref(_sfc_main$3), {
          items: rows.value,
          columns,
          "item-key": "id",
          density: "comfortable"
        }, {
          "cell-countdown": withCtx(({ item }, _push2, _parent2, _scopeId) => {
            var _a, _b;
            if (_push2) {
              _push2(`<div class="flex items-center gap-3"${_scopeId}>`);
              if (item.image_path) {
                _push2(ssrRenderComponent(unref(_sfc_main$5), {
                  src: String(item.image_path),
                  alt: String(item.slug),
                  "aspect-ratio": "1/1",
                  rounded: "lg",
                  ui: { root: "h-12 w-12 overflow-hidden" }
                }, null, _parent2, _scopeId));
              } else {
                _push2(`<div class="flex h-12 w-12 items-center justify-center rounded-lg bg-ui-muted text-ui-muted-foreground"${_scopeId}>`);
                _push2(ssrRenderComponent(unref(Target), { class: "h-5 w-5" }, null, _parent2, _scopeId));
                _push2(`</div>`);
              }
              _push2(`<div class="min-w-0"${_scopeId}><p class="truncate font-medium"${_scopeId}>${ssrInterpolate(((_a = item.title) == null ? void 0 : _a.en) ?? item.slug)}</p><p class="truncate text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(item.slug)}</p></div></div>`);
            } else {
              return [
                createVNode("div", { class: "flex items-center gap-3" }, [
                  item.image_path ? (openBlock(), createBlock(unref(_sfc_main$5), {
                    key: 0,
                    src: String(item.image_path),
                    alt: String(item.slug),
                    "aspect-ratio": "1/1",
                    rounded: "lg",
                    ui: { root: "h-12 w-12 overflow-hidden" }
                  }, null, 8, ["src", "alt"])) : (openBlock(), createBlock("div", {
                    key: 1,
                    class: "flex h-12 w-12 items-center justify-center rounded-lg bg-ui-muted text-ui-muted-foreground"
                  }, [
                    createVNode(unref(Target), { class: "h-5 w-5" })
                  ])),
                  createVNode("div", { class: "min-w-0" }, [
                    createVNode("p", { class: "truncate font-medium" }, toDisplayString(((_b = item.title) == null ? void 0 : _b.en) ?? item.slug), 1),
                    createVNode("p", { class: "truncate text-sm text-ui-muted-foreground" }, toDisplayString(item.slug), 1)
                  ])
                ])
              ];
            }
          }),
          "cell-status": withCtx(({ item }, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<div class="space-y-1"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(_sfc_main$4), {
                label: String(item.status),
                variant: "soft"
              }, null, _parent2, _scopeId));
              _push2(ssrRenderComponent(unref(_sfc_main$4), {
                label: item.is_published ? "published" : "draft",
                color: item.is_published ? "success" : "secondary",
                variant: "soft"
              }, null, _parent2, _scopeId));
              _push2(`</div>`);
            } else {
              return [
                createVNode("div", { class: "space-y-1" }, [
                  createVNode(unref(_sfc_main$4), {
                    label: String(item.status),
                    variant: "soft"
                  }, null, 8, ["label"]),
                  createVNode(unref(_sfc_main$4), {
                    label: item.is_published ? "published" : "draft",
                    color: item.is_published ? "success" : "secondary",
                    variant: "soft"
                  }, null, 8, ["label", "color"])
                ])
              ];
            }
          }),
          "cell-relations": withCtx(({ item }, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<div class="space-y-1 text-sm text-ui-muted-foreground"${_scopeId}><p${_scopeId}>${ssrInterpolate(item.projections_count)} projections</p><p${_scopeId}>${ssrInterpolate(item.visualizations_count)} visualizations</p><p${_scopeId}>${ssrInterpolate(item.news_count)} news · ${ssrInterpolate(item.initiatives_count)} initiatives</p></div>`);
            } else {
              return [
                createVNode("div", { class: "space-y-1 text-sm text-ui-muted-foreground" }, [
                  createVNode("p", null, toDisplayString(item.projections_count) + " projections", 1),
                  createVNode("p", null, toDisplayString(item.visualizations_count) + " visualizations", 1),
                  createVNode("p", null, toDisplayString(item.news_count) + " news · " + toDisplayString(item.initiatives_count) + " initiatives", 1)
                ])
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<div class="p-6 text-sm text-ui-muted-foreground">No recent countdowns are available yet.</div>`);
      }
      _push(`<div class="space-y-4">`);
      _push(ssrRenderComponent(unref(_sfc_main$2), { ui: { body: "space-y-3 p-5" } }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$4), {
              label: "CRUD ready",
              icon: unref(Activity),
              color: "success",
              variant: "soft"
            }, null, _parent2, _scopeId));
            _push2(`<p class="text-sm text-ui-muted-foreground"${_scopeId}>Manage countdown records and all relation entities from the Countdowns edit page.</p>`);
            _push2(ssrRenderComponent(unref(Button), {
              variant: "secondary",
              icon: unref(Target),
              onClick: ($event) => visit(`${normalizedBackofficePath.value}/countdowns`)
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Open countdowns`);
                } else {
                  return [
                    createTextVNode("Open countdowns")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$4), {
                label: "CRUD ready",
                icon: unref(Activity),
                color: "success",
                variant: "soft"
              }, null, 8, ["icon"]),
              createVNode("p", { class: "text-sm text-ui-muted-foreground" }, "Manage countdown records and all relation entities from the Countdowns edit page."),
              createVNode(unref(Button), {
                variant: "secondary",
                icon: unref(Target),
                onClick: ($event) => visit(`${normalizedBackofficePath.value}/countdowns`)
              }, {
                default: withCtx(() => [
                  createTextVNode("Open countdowns")
                ]),
                _: 1
              }, 8, ["icon", "onClick"])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$2), { ui: { body: "space-y-3 p-5" } }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$4), {
              label: "API keys",
              icon: unref(KeyRound),
              color: __props.metrics.activeApiKeys > 0 ? "success" : "warning",
              variant: "soft"
            }, null, _parent2, _scopeId));
            _push2(`<p class="text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(__props.metrics.activeApiKeys)} of ${ssrInterpolate(__props.metrics.apiKeys)} OpenAI keys are active.</p>`);
            _push2(ssrRenderComponent(unref(Button), {
              variant: "secondary",
              icon: unref(Clock3),
              onClick: ($event) => visit(`${normalizedBackofficePath.value}/openai-keys`)
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Manage keys`);
                } else {
                  return [
                    createTextVNode("Manage keys")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$4), {
                label: "API keys",
                icon: unref(KeyRound),
                color: __props.metrics.activeApiKeys > 0 ? "success" : "warning",
                variant: "soft"
              }, null, 8, ["icon", "color"]),
              createVNode("p", { class: "text-sm text-ui-muted-foreground" }, toDisplayString(__props.metrics.activeApiKeys) + " of " + toDisplayString(__props.metrics.apiKeys) + " OpenAI keys are active.", 1),
              createVNode(unref(Button), {
                variant: "secondary",
                icon: unref(Clock3),
                onClick: ($event) => visit(`${normalizedBackofficePath.value}/openai-keys`)
              }, {
                default: withCtx(() => [
                  createTextVNode("Manage keys")
                ]),
                _: 1
              }, 8, ["icon", "onClick"])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></div></div>`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Dashboard/DashboardOverview.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    backofficePath: {},
    counts: {},
    metrics: {},
    recentCountdowns: {}
  },
  setup(__props) {
    const props = __props;
    const activeSection = ref("dashboard");
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Backoffice" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$6, {
        "active-section": activeSection.value,
        "onUpdate:activeSection": ($event) => activeSection.value = $event,
        title: "Backoffice",
        subtitle: "Operational cockpit for Doomsday Clock administration.",
        "backoffice-path": props.backofficePath,
        counts: props.counts
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$1, {
              "backoffice-path": props.backofficePath,
              metrics: props.metrics,
              "recent-countdowns": props.recentCountdowns
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$1, {
                "backoffice-path": props.backofficePath,
                metrics: props.metrics,
                "recent-countdowns": props.recentCountdowns
              }, null, 8, ["backoffice-path", "metrics", "recent-countdowns"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Backoffice/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
