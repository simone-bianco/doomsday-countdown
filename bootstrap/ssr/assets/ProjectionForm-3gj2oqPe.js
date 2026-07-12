import { defineComponent, mergeProps, unref, withCtx, createTextVNode, toDisplayString, createVNode, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { B as Button, a as _sfc_main$2, N as NumberInput, b as _sfc_main$3 } from "../ssr.js";
import { u as useSmartForm } from "./useSmartForm-KUAiAz4w.js";
import "./AppLayout-CiOlqxSX.js";
import { _ as _sfc_main$1 } from "./BackofficeSelectField-CNJDQmD8.js";
import { _ as _sfc_main$4 } from "./FormActions-Wl8L_zsK.js";
import { c as SaveProjectionDataRules } from "./form-rules-C_jmOmBo.js";
import { l as localizedText, i as isoDate, f as first, o as optionItems } from "./formHelpers-BXTPv_Pd.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ProjectionForm",
  __ssrInlineRender: true,
  props: {
    options: {},
    submitUrl: {},
    method: {},
    submitLabel: {},
    projection: { default: void 0 },
    showTopActions: { type: Boolean, default: false }
  },
  emits: ["saved", "cancel"],
  setup(__props, { emit: __emit }) {
    var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k;
    const props = __props;
    const emit = __emit;
    const form = useSmartForm({ ...SaveProjectionDataRules });
    form.fill({
      type: ((_a = props.projection) == null ? void 0 : _a.type) ?? first(props.options.projection_types, "neutral"),
      target_date: isoDate((_b = props.projection) == null ? void 0 : _b.target_date),
      title: localizedText(((_c = props.projection) == null ? void 0 : _c.title.en) ?? ""),
      summary: localizedText(((_e = (_d = props.projection) == null ? void 0 : _d.summary) == null ? void 0 : _e.en) ?? ""),
      confidence_score: ((_f = props.projection) == null ? void 0 : _f.confidence_score) ?? 50,
      probability_score: ((_g = props.projection) == null ? void 0 : _g.probability_score) ?? 50,
      trend: ((_h = props.projection) == null ? void 0 : _h.trend) ?? "stable",
      methodology: localizedText(((_j = (_i = props.projection) == null ? void 0 : _i.methodology) == null ? void 0 : _j.en) ?? ""),
      sort_order: ((_k = props.projection) == null ? void 0 : _k.sort_order) ?? 0
    });
    function chooseType(value) {
      if (typeof value === "string") {
        form.type = value;
      }
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<form${ssrRenderAttrs(mergeProps({ class: "space-y-4" }, _attrs))}>`);
      if (__props.showTopActions) {
        _push(`<div class="flex flex-wrap items-center justify-end gap-2 border-b border-ui-border/60 pb-4">`);
        _push(ssrRenderComponent(unref(Button), {
          type: "submit",
          loading: unref(form).processing
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`${ssrInterpolate(__props.submitLabel)}`);
            } else {
              return [
                createTextVNode(toDisplayString(__props.submitLabel), 1)
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(ssrRenderComponent(unref(Button), {
          type: "button",
          variant: "secondary",
          onClick: ($event) => emit("cancel")
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`Cancel`);
            } else {
              return [
                createTextVNode("Cancel")
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="grid gap-4 md:grid-cols-3"><div>`);
      _push(ssrRenderComponent(_sfc_main$1, {
        label: "Projection type",
        "model-value": unref(form).type,
        options: unref(optionItems)(__props.options.projection_types),
        clearable: false,
        "onUpdate:modelValue": chooseType
      }, null, _parent));
      _push(`<p class="mt-1 text-xs text-ui-muted-foreground">Groups the projection by scenario category in the public UI.</p></div>`);
      _push(ssrRenderComponent(unref(_sfc_main$2), {
        modelValue: unref(form).target_date,
        "onUpdate:modelValue": ($event) => unref(form).target_date = $event,
        label: "Target date",
        type: "date",
        "helper-text": "Optional forecast date; shown as entered without timezone conversion.",
        error: unref(form).errors.target_date
      }, null, _parent));
      _push(`<div>`);
      _push(ssrRenderComponent(unref(NumberInput), {
        modelValue: unref(form).sort_order,
        "onUpdate:modelValue": ($event) => unref(form).sort_order = $event,
        label: "Sort order",
        min: 0,
        error: unref(form).errors.sort_order
      }, null, _parent));
      _push(`<p class="mt-1 text-xs text-ui-muted-foreground">Lower numbers appear first.</p></div></div><div class="grid gap-4 md:grid-cols-2">`);
      _push(ssrRenderComponent(unref(_sfc_main$2), {
        modelValue: unref(form).title.en,
        "onUpdate:modelValue": ($event) => unref(form).title.en = $event,
        label: "Title (EN)",
        error: unref(form).errors.title
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$2), {
        modelValue: unref(form).trend,
        "onUpdate:modelValue": ($event) => unref(form).trend = $event,
        label: "Trend",
        "helper-text": "Short trend token such as stable, rising, falling, or worsening.",
        error: unref(form).errors.trend
      }, null, _parent));
      _push(`</div>`);
      _push(ssrRenderComponent(unref(_sfc_main$3), {
        modelValue: unref(form).summary.en,
        "onUpdate:modelValue": ($event) => unref(form).summary.en = $event,
        label: "Summary (EN)",
        rows: 3,
        error: unref(form).errors.summary
      }, null, _parent));
      _push(`<div>`);
      _push(ssrRenderComponent(unref(_sfc_main$3), {
        modelValue: unref(form).methodology.en,
        "onUpdate:modelValue": ($event) => unref(form).methodology.en = $event,
        label: "Methodology (EN)",
        rows: 3,
        error: unref(form).errors.methodology
      }, null, _parent));
      _push(`<p class="mt-1 text-xs text-ui-muted-foreground">Briefly explain the data or assumptions behind the projection.</p></div><div class="grid gap-4 md:grid-cols-2">`);
      _push(ssrRenderComponent(unref(NumberInput), {
        modelValue: unref(form).confidence_score,
        "onUpdate:modelValue": ($event) => unref(form).confidence_score = $event,
        label: "Confidence score",
        min: 0,
        max: 100,
        error: unref(form).errors.confidence_score
      }, null, _parent));
      _push(ssrRenderComponent(unref(NumberInput), {
        modelValue: unref(form).probability_score,
        "onUpdate:modelValue": ($event) => unref(form).probability_score = $event,
        label: "Probability score",
        min: 0,
        max: 100,
        error: unref(form).errors.probability_score
      }, null, _parent));
      _push(`</div>`);
      _push(ssrRenderComponent(_sfc_main$4, { compact: "" }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(Button), {
              type: "submit",
              loading: unref(form).processing
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(__props.submitLabel)}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(__props.submitLabel), 1)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Button), {
              type: "button",
              variant: "secondary",
              onClick: ($event) => emit("cancel")
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Cancel`);
                } else {
                  return [
                    createTextVNode("Cancel")
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(Button), {
                type: "submit",
                loading: unref(form).processing
              }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(__props.submitLabel), 1)
                ]),
                _: 1
              }, 8, ["loading"]),
              createVNode(unref(Button), {
                type: "button",
                variant: "secondary",
                onClick: ($event) => emit("cancel")
              }, {
                default: withCtx(() => [
                  createTextVNode("Cancel")
                ]),
                _: 1
              }, 8, ["onClick"])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</form>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Doomsday/ProjectionForm.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
