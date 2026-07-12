import { defineComponent, computed, unref, mergeProps, withCtx, createVNode, toDisplayString, openBlock, createBlock, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { AlertTriangle } from "lucide-vue-next";
import { h as _sfc_main$1, e as _sfc_main$2 } from "../ssr.js";
import { _ as _sfc_main$3, a as _sfc_main$4 } from "./VisualizationChart-CgdRsG6k.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "VisualizationPreview",
  __ssrInlineRender: true,
  props: {
    visualization: {}
  },
  setup(__props) {
    const props = __props;
    const title = computed(() => props.visualization.title.en ?? props.visualization.key);
    const isChart = computed(() => ["line", "area", "bar"].includes(props.visualization.type));
    const isKpi = computed(() => props.visualization.type === "kpi");
    const publicVisualization = computed(() => {
      var _a;
      return {
        key: props.visualization.key,
        type: props.visualization.type,
        title: title.value,
        description: ((_a = props.visualization.description) == null ? void 0 : _a.en) ?? null,
        sources: [...props.visualization.sources],
        reasoning: props.visualization.reasoning.en ?? "",
        payload: props.visualization.payload,
        schema_version: props.visualization.schema_version,
        sort_order: props.visualization.sort_order
      };
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(_sfc_main$1), mergeProps({ ui: { body: "space-y-4 p-4" } }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex items-center justify-between gap-3"${_scopeId}><div${_scopeId}><p class="font-semibold"${_scopeId}>${ssrInterpolate(title.value)}</p><p class="text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(__props.visualization.key)} · ${ssrInterpolate(__props.visualization.type)}</p></div>`);
            _push2(ssrRenderComponent(unref(_sfc_main$2), {
              label: __props.visualization.type,
              variant: "soft"
            }, null, _parent2, _scopeId));
            _push2(`</div>`);
            if (isChart.value) {
              _push2(ssrRenderComponent(_sfc_main$3, {
                payload: __props.visualization.payload,
                type: __props.visualization.type,
                sources: __props.visualization.sources,
                reasoning: __props.visualization.reasoning.en ?? "",
                compact: ""
              }, null, _parent2, _scopeId));
            } else if (isKpi.value) {
              _push2(ssrRenderComponent(_sfc_main$4, { visualization: publicVisualization.value }, null, _parent2, _scopeId));
            } else {
              _push2(`<div class="rounded-lg border border-dashed border-ui-border p-4 text-sm text-ui-muted-foreground"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(_sfc_main$2), {
                label: "Preview unavailable",
                icon: unref(AlertTriangle),
                color: "warning",
                variant: "soft"
              }, null, _parent2, _scopeId));
              _push2(`<p class="mt-3"${_scopeId}>Only line, area, bar and KPI payloads can be edited with live preview in this backoffice UI.</p></div>`);
            }
          } else {
            return [
              createVNode("div", { class: "flex items-center justify-between gap-3" }, [
                createVNode("div", null, [
                  createVNode("p", { class: "font-semibold" }, toDisplayString(title.value), 1),
                  createVNode("p", { class: "text-sm text-ui-muted-foreground" }, toDisplayString(__props.visualization.key) + " · " + toDisplayString(__props.visualization.type), 1)
                ]),
                createVNode(unref(_sfc_main$2), {
                  label: __props.visualization.type,
                  variant: "soft"
                }, null, 8, ["label"])
              ]),
              isChart.value ? (openBlock(), createBlock(_sfc_main$3, {
                key: 0,
                payload: __props.visualization.payload,
                type: __props.visualization.type,
                sources: __props.visualization.sources,
                reasoning: __props.visualization.reasoning.en ?? "",
                compact: ""
              }, null, 8, ["payload", "type", "sources", "reasoning"])) : isKpi.value ? (openBlock(), createBlock(_sfc_main$4, {
                key: 1,
                visualization: publicVisualization.value
              }, null, 8, ["visualization"])) : (openBlock(), createBlock("div", {
                key: 2,
                class: "rounded-lg border border-dashed border-ui-border p-4 text-sm text-ui-muted-foreground"
              }, [
                createVNode(unref(_sfc_main$2), {
                  label: "Preview unavailable",
                  icon: unref(AlertTriangle),
                  color: "warning",
                  variant: "soft"
                }, null, 8, ["icon"]),
                createVNode("p", { class: "mt-3" }, "Only line, area, bar and KPI payloads can be edited with live preview in this backoffice UI.")
              ]))
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Doomsday/VisualizationPreview.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
