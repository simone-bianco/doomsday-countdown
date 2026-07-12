import { defineComponent, ref, computed, unref, withCtx, createTextVNode, createVNode, useSSRContext } from "vue";
import { ssrRenderComponent } from "vue/server-renderer";
import { Head, router } from "@inertiajs/vue3";
import { ArrowLeft } from "lucide-vue-next";
import { h as _sfc_main$2, B as Button } from "../ssr.js";
import { _ as _sfc_main$1 } from "./BackofficeShell-B2o2xGeb.js";
import { _ as _sfc_main$3 } from "./VisualizationForm-VlsgyIuA.js";
import { u as useBackofficePath } from "./useBackofficePath-DWj-NlKi.js";
import "@vue/server-renderer";
import "clsx";
import "tailwind-merge";
import "vue-advanced-cropper";
import "node:async_hooks";
import "i18next";
import "./AppLayout-CiOlqxSX.js";
import "./useSmartForm-KUAiAz4w.js";
import "./BackofficeSelectField-CNJDQmD8.js";
import "./FormActions-Wl8L_zsK.js";
import "./form-rules-C_jmOmBo.js";
import "./VisualizationPreview-CK8ZO4Lm.js";
import "./VisualizationChart-CgdRsG6k.js";
import "./formHelpers-BXTPv_Pd.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Create",
  __ssrInlineRender: true,
  props: {
    countdown: {},
    projection: {},
    visualization: {},
    options: {}
  },
  setup(__props) {
    const props = __props;
    const { backofficePath, normalizedBackofficePath, counts } = useBackofficePath();
    const activeSection = ref("countdowns");
    const returnTab = computed(() => props.projection === null ? "visualizations" : "projections");
    const returnUrl = computed(() => countdownEditUrl(returnTab.value));
    const submitUrl = computed(() => props.projection === null ? `${normalizedBackofficePath.value}/countdowns/${props.countdown.id}/visualizations` : `${normalizedBackofficePath.value}/countdowns/${props.countdown.id}/projections/${props.projection.id}/visualizations`);
    function countdownEditUrl(tab) {
      const params = new URLSearchParams(typeof window === "undefined" ? "" : window.location.search);
      params.set("tab", tab);
      return `${normalizedBackofficePath.value}/countdowns/${props.countdown.id}/edit?${params.toString()}`;
    }
    function backToCountdown() {
      router.visit(returnUrl.value, { preserveScroll: true, preserveState: true });
    }
    return (_ctx, _push, _parent, _attrs) => {
      var _a;
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: `Create visualization · ${__props.countdown.slug}`
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, {
        "active-section": activeSection.value,
        "onUpdate:activeSection": ($event) => activeSection.value = $event,
        title: "Create visualization",
        subtitle: `Add a visualization for ${((_a = __props.projection) == null ? void 0 : _a.title.en) ?? __props.countdown.title.en ?? __props.countdown.slug}.`,
        "backoffice-path": unref(backofficePath),
        counts: unref(counts)
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$2), { ui: { body: "space-y-5 p-6" } }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="flex flex-wrap items-center justify-between gap-3 border-b border-ui-border/60 pb-4"${_scopeId2}><div${_scopeId2}><p class="font-semibold"${_scopeId2}>Visualization payload</p><p class="text-sm text-ui-muted-foreground"${_scopeId2}>Use a dedicated edit surface for chart metadata, payload and preview setup.</p></div>`);
                  _push3(ssrRenderComponent(unref(Button), {
                    variant: "secondary",
                    size: "sm",
                    icon: unref(ArrowLeft),
                    onClick: backToCountdown
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Back to countdown`);
                      } else {
                        return [
                          createTextVNode("Back to countdown")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`</div>`);
                  _push3(ssrRenderComponent(_sfc_main$3, {
                    options: __props.options,
                    visualization: __props.visualization ?? void 0,
                    "submit-url": submitUrl.value,
                    method: "post",
                    "submit-label": "Create visualization",
                    "show-top-actions": "",
                    onSaved: backToCountdown,
                    onCancel: backToCountdown
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode("div", { class: "flex flex-wrap items-center justify-between gap-3 border-b border-ui-border/60 pb-4" }, [
                      createVNode("div", null, [
                        createVNode("p", { class: "font-semibold" }, "Visualization payload"),
                        createVNode("p", { class: "text-sm text-ui-muted-foreground" }, "Use a dedicated edit surface for chart metadata, payload and preview setup.")
                      ]),
                      createVNode(unref(Button), {
                        variant: "secondary",
                        size: "sm",
                        icon: unref(ArrowLeft),
                        onClick: backToCountdown
                      }, {
                        default: withCtx(() => [
                          createTextVNode("Back to countdown")
                        ]),
                        _: 1
                      }, 8, ["icon"])
                    ]),
                    createVNode(_sfc_main$3, {
                      options: __props.options,
                      visualization: __props.visualization ?? void 0,
                      "submit-url": submitUrl.value,
                      method: "post",
                      "submit-label": "Create visualization",
                      "show-top-actions": "",
                      onSaved: backToCountdown,
                      onCancel: backToCountdown
                    }, null, 8, ["options", "visualization", "submit-url"])
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$2), { ui: { body: "space-y-5 p-6" } }, {
                default: withCtx(() => [
                  createVNode("div", { class: "flex flex-wrap items-center justify-between gap-3 border-b border-ui-border/60 pb-4" }, [
                    createVNode("div", null, [
                      createVNode("p", { class: "font-semibold" }, "Visualization payload"),
                      createVNode("p", { class: "text-sm text-ui-muted-foreground" }, "Use a dedicated edit surface for chart metadata, payload and preview setup.")
                    ]),
                    createVNode(unref(Button), {
                      variant: "secondary",
                      size: "sm",
                      icon: unref(ArrowLeft),
                      onClick: backToCountdown
                    }, {
                      default: withCtx(() => [
                        createTextVNode("Back to countdown")
                      ]),
                      _: 1
                    }, 8, ["icon"])
                  ]),
                  createVNode(_sfc_main$3, {
                    options: __props.options,
                    visualization: __props.visualization ?? void 0,
                    "submit-url": submitUrl.value,
                    method: "post",
                    "submit-label": "Create visualization",
                    "show-top-actions": "",
                    onSaved: backToCountdown,
                    onCancel: backToCountdown
                  }, null, 8, ["options", "visualization", "submit-url"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Backoffice/Countdowns/Visualizations/Create.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
