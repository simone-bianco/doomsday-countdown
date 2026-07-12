import { defineComponent, useModel, ref, computed, watch, unref, mergeProps, withCtx, createVNode, openBlock, createBlock, Fragment, renderList, toDisplayString, createCommentVNode, mergeModels, useSSRContext, createTextVNode } from "vue";
import { ssrRenderComponent, ssrRenderList, ssrInterpolate, ssrRenderAttrs } from "vue/server-renderer";
import { h as _sfc_main$2, e as _sfc_main$3, a as _sfc_main$4, b as _sfc_main$6, B as Button, N as NumberInput } from "../ssr.js";
import { u as useSmartForm } from "./useSmartForm-KUAiAz4w.js";
import "./AppLayout-CiOlqxSX.js";
import { _ as _sfc_main$5 } from "./BackofficeSelectField-CNJDQmD8.js";
import { _ as _sfc_main$8 } from "./FormActions-Wl8L_zsK.js";
import { d as SaveVisualizationDataRules } from "./form-rules-C_jmOmBo.js";
import { AlertTriangle } from "lucide-vue-next";
import { _ as _sfc_main$7 } from "./VisualizationPreview-CK8ZO4Lm.js";
import { o as optionItems, a as isRecord, d as defaultPayload, c as chartXAxisTypes, b as chartYAxisFormats, e as chartText, k as kpiText, p as parseChartPayload, g as parseKpiPayload, f as first, l as localizedText } from "./formHelpers-BXTPv_Pd.js";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "VisualizationPayloadEditor",
  __ssrInlineRender: true,
  props: /* @__PURE__ */ mergeModels({
    sources: {},
    reasoning: {}
  }, {
    "type": { required: true },
    "typeModifiers": {},
    "payload": { required: true },
    "payloadModifiers": {},
    "valid": { type: Boolean, ...{ default: true } },
    "validModifiers": {}
  }),
  emits: ["update:type", "update:payload", "update:valid"],
  setup(__props) {
    const props = __props;
    const typeModel = useModel(__props, "type");
    const payloadModel = useModel(__props, "payload");
    const validModel = useModel(__props, "valid");
    const labelsText = ref("");
    const xLabelText = ref("");
    const xTypeValue = ref("ordinal");
    const yLabelText = ref("");
    const yUnitText = ref("");
    const yFormatValue = ref("percent");
    const seriesText = ref("");
    const itemsText = ref("");
    const isHydrating = ref(false);
    const xTypeOptions = optionItems(chartXAxisTypes);
    const yFormatOptions = optionItems(chartYAxisFormats);
    const isChart = computed(() => ["line", "area", "bar"].includes(typeModel.value));
    const isKpi = computed(() => typeModel.value === "kpi");
    const isSupported = computed(() => isChart.value || isKpi.value);
    function chartErrors() {
      const payload = payloadModel.value;
      if (!isRecord(payload)) {
        return ["Chart payload must be an object."];
      }
      const errors2 = [];
      const labels = Array.isArray(payload.labels) ? payload.labels : [];
      if (labels.length === 0 || labels.some((label) => typeof label !== "string" || label.trim() === "")) {
        errors2.push("Add at least one non-empty chart label.");
      }
      const series = Array.isArray(payload.series) ? payload.series : [];
      if (series.length === 0) {
        errors2.push("Add at least one numeric series.");
      } else if (series.every((value) => typeof value === "number" && Number.isFinite(value))) {
        if (series.length !== labels.length) {
          errors2.push("Series values must have the same length as labels.");
        }
      } else {
        for (const item of series) {
          if (!isRecord(item) || typeof item.name !== "string" || item.name.trim() === "" || !Array.isArray(item.values)) {
            errors2.push("Each series requires a name and numeric values.");
            break;
          }
          if ("unit" in item || "format" in item) {
            errors2.push("Series must share the y-axis unit and format.");
            break;
          }
          if (item.values.length !== labels.length || item.values.some((value) => typeof value !== "number" || !Number.isFinite(value))) {
            errors2.push("Every series must contain one numeric value per label.");
            break;
          }
        }
      }
      const axes = isRecord(payload.axes) ? payload.axes : null;
      const xAxis = axes && isRecord(axes.x) ? axes.x : null;
      const yAxis = axes && isRecord(axes.y) ? axes.y : null;
      if (!xAxis || typeof xAxis.label !== "string" || xAxis.label.trim() === "") {
        errors2.push("Add an x-axis label.");
      }
      if (!xAxis || typeof xAxis.type !== "string" || !chartXAxisTypes.includes(xAxis.type)) {
        errors2.push("Choose a valid x-axis type.");
      } else if (typeModel.value === "bar" && xAxis.type !== "category") {
        errors2.push("Bar charts require a category x-axis.");
      } else if (["line", "area"].includes(typeModel.value) && xAxis.type === "category") {
        errors2.push("Line and area charts require a temporal or ordinal x-axis.");
      }
      if (!yAxis || typeof yAxis.label !== "string" || yAxis.label.trim() === "") {
        errors2.push("Add a y-axis label.");
      }
      if (!yAxis || typeof yAxis.unit !== "string" || yAxis.unit.trim() === "") {
        errors2.push("Add the shared y-axis unit.");
      }
      if (!yAxis || typeof yAxis.format !== "string" || !chartYAxisFormats.includes(yAxis.format)) {
        errors2.push("Choose a valid y-axis format.");
      }
      return errors2;
    }
    const errors = computed(() => {
      if (!isSupported.value) {
        return ["Live editing is available only for line, area, bar and KPI visualizations."];
      }
      if (isChart.value) {
        return chartErrors();
      }
      if (isKpi.value) {
        const payload = payloadModel.value;
        if (!isRecord(payload) || !Array.isArray(payload.items) || payload.items.length === 0) {
          return ["Add at least one KPI item."];
        }
      }
      return [];
    });
    const hasErrors = computed(() => errors.value.length > 0);
    const previewVisualization = computed(() => ({
      key: "draft-preview",
      type: typeModel.value,
      title: { en: "Draft preview" },
      description: { en: "Live preview before saving" },
      sources: [...props.sources],
      reasoning: { en: props.reasoning },
      payload: payloadModel.value,
      schema_version: isChart.value ? 2 : 1,
      sort_order: 0
    }));
    function hydrateText(payload) {
      isHydrating.value = true;
      const chart = chartText(payload);
      labelsText.value = chart.labels;
      xLabelText.value = chart.xLabel;
      xTypeValue.value = chart.xType;
      yLabelText.value = chart.yLabel;
      yUnitText.value = chart.yUnit;
      yFormatValue.value = chart.yFormat;
      seriesText.value = chart.series;
      itemsText.value = kpiText(payload);
      isHydrating.value = false;
    }
    function syncPayload() {
      if (isHydrating.value) {
        return;
      }
      if (isChart.value) {
        payloadModel.value = parseChartPayload({
          labels: labelsText.value,
          xLabel: xLabelText.value,
          xType: xTypeValue.value,
          yLabel: yLabelText.value,
          yUnit: yUnitText.value,
          yFormat: yFormatValue.value,
          series: seriesText.value
        });
      } else if (isKpi.value) {
        payloadModel.value = parseKpiPayload(itemsText.value);
      }
    }
    function chooseXType(value) {
      if (typeof value === "string" && chartXAxisTypes.includes(value)) {
        xTypeValue.value = value;
      }
    }
    function chooseYFormat(value) {
      if (typeof value === "string" && chartYAxisFormats.includes(value)) {
        yFormatValue.value = value;
      }
    }
    hydrateText(payloadModel.value);
    watch(() => typeModel.value, (type, previousType) => {
      if (previousType === void 0 || type === previousType) {
        return;
      }
      payloadModel.value = defaultPayload(type);
      hydrateText(payloadModel.value);
    });
    watch(errors, () => {
      validModel.value = !hasErrors.value;
    }, { immediate: true });
    watch([labelsText, xLabelText, xTypeValue, yLabelText, yUnitText, yFormatValue, seriesText, itemsText], syncPayload, { flush: "sync" });
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(_sfc_main$2), mergeProps({ ui: { body: "space-y-4 p-4" } }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex flex-wrap items-center justify-between gap-3"${_scopeId}><div${_scopeId}><p class="font-semibold"${_scopeId}>Payload editor</p><p class="text-sm text-ui-muted-foreground"${_scopeId}>Structured payload editor; sources and calculation reasoning are managed on the visualization.</p></div>`);
            _push2(ssrRenderComponent(unref(_sfc_main$3), {
              label: typeModel.value,
              color: isSupported.value ? "success" : "warning",
              variant: "soft"
            }, null, _parent2, _scopeId));
            _push2(`</div>`);
            if (isChart.value) {
              _push2(`<div class="space-y-4"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(_sfc_main$4), {
                modelValue: labelsText.value,
                "onUpdate:modelValue": ($event) => labelsText.value = $event,
                label: "Labels",
                "helper-text": "Comma-separated ordered values or categories."
              }, null, _parent2, _scopeId));
              _push2(`<div class="grid gap-4 md:grid-cols-2"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(_sfc_main$4), {
                modelValue: xLabelText.value,
                "onUpdate:modelValue": ($event) => xLabelText.value = $event,
                label: "X-axis label",
                "helper-text": "Describe the time, sequence or category dimension."
              }, null, _parent2, _scopeId));
              _push2(ssrRenderComponent(_sfc_main$5, {
                label: "X-axis type",
                "model-value": xTypeValue.value,
                options: unref(xTypeOptions),
                clearable: false,
                "onUpdate:modelValue": chooseXType
              }, null, _parent2, _scopeId));
              _push2(ssrRenderComponent(unref(_sfc_main$4), {
                modelValue: yLabelText.value,
                "onUpdate:modelValue": ($event) => yLabelText.value = $event,
                label: "Y-axis label",
                "helper-text": "Describe the measured quantity."
              }, null, _parent2, _scopeId));
              _push2(ssrRenderComponent(unref(_sfc_main$4), {
                modelValue: yUnitText.value,
                "onUpdate:modelValue": ($event) => yUnitText.value = $event,
                label: "Y-axis unit",
                "helper-text": "One shared unit for every series."
              }, null, _parent2, _scopeId));
              _push2(ssrRenderComponent(_sfc_main$5, {
                label: "Y-axis format",
                "model-value": yFormatValue.value,
                options: unref(yFormatOptions),
                clearable: false,
                "onUpdate:modelValue": chooseYFormat
              }, null, _parent2, _scopeId));
              _push2(`</div>`);
              _push2(ssrRenderComponent(unref(_sfc_main$6), {
                modelValue: seriesText.value,
                "onUpdate:modelValue": ($event) => seriesText.value = $event,
                label: "Series",
                rows: 4,
                "helper-text": "One series per line: Scenario: 20, 42, 64"
              }, null, _parent2, _scopeId));
              _push2(`</div>`);
            } else if (isKpi.value) {
              _push2(`<div class="space-y-4"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(_sfc_main$6), {
                modelValue: itemsText.value,
                "onUpdate:modelValue": ($event) => itemsText.value = $event,
                label: "KPI items",
                rows: 5,
                "helper-text": "One KPI per line: Label|Value|direction|sparkline comma values"
              }, null, _parent2, _scopeId));
              _push2(`</div>`);
            } else {
              _push2(`<div class="rounded-lg border border-dashed border-ui-border p-4 text-sm text-ui-muted-foreground"${_scopeId}>`);
              _push2(ssrRenderComponent(unref(_sfc_main$3), {
                label: "Unsupported editor",
                icon: unref(AlertTriangle),
                color: "warning",
                variant: "soft"
              }, null, _parent2, _scopeId));
              _push2(`<p class="mt-3"${_scopeId}>Select line, area, bar or KPI to save with a structured live preview.</p></div>`);
            }
            if (hasErrors.value) {
              _push2(`<div class="rounded-lg border border-ui-warning/40 bg-ui-warning/10 p-3 text-sm text-ui-muted-foreground"${_scopeId}><!--[-->`);
              ssrRenderList(errors.value, (error) => {
                _push2(`<p${_scopeId}>${ssrInterpolate(error)}</p>`);
              });
              _push2(`<!--]--></div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(ssrRenderComponent(_sfc_main$7, { visualization: previewVisualization.value }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode("div", { class: "flex flex-wrap items-center justify-between gap-3" }, [
                createVNode("div", null, [
                  createVNode("p", { class: "font-semibold" }, "Payload editor"),
                  createVNode("p", { class: "text-sm text-ui-muted-foreground" }, "Structured payload editor; sources and calculation reasoning are managed on the visualization.")
                ]),
                createVNode(unref(_sfc_main$3), {
                  label: typeModel.value,
                  color: isSupported.value ? "success" : "warning",
                  variant: "soft"
                }, null, 8, ["label", "color"])
              ]),
              isChart.value ? (openBlock(), createBlock("div", {
                key: 0,
                class: "space-y-4"
              }, [
                createVNode(unref(_sfc_main$4), {
                  modelValue: labelsText.value,
                  "onUpdate:modelValue": ($event) => labelsText.value = $event,
                  label: "Labels",
                  "helper-text": "Comma-separated ordered values or categories."
                }, null, 8, ["modelValue", "onUpdate:modelValue"]),
                createVNode("div", { class: "grid gap-4 md:grid-cols-2" }, [
                  createVNode(unref(_sfc_main$4), {
                    modelValue: xLabelText.value,
                    "onUpdate:modelValue": ($event) => xLabelText.value = $event,
                    label: "X-axis label",
                    "helper-text": "Describe the time, sequence or category dimension."
                  }, null, 8, ["modelValue", "onUpdate:modelValue"]),
                  createVNode(_sfc_main$5, {
                    label: "X-axis type",
                    "model-value": xTypeValue.value,
                    options: unref(xTypeOptions),
                    clearable: false,
                    "onUpdate:modelValue": chooseXType
                  }, null, 8, ["model-value", "options"]),
                  createVNode(unref(_sfc_main$4), {
                    modelValue: yLabelText.value,
                    "onUpdate:modelValue": ($event) => yLabelText.value = $event,
                    label: "Y-axis label",
                    "helper-text": "Describe the measured quantity."
                  }, null, 8, ["modelValue", "onUpdate:modelValue"]),
                  createVNode(unref(_sfc_main$4), {
                    modelValue: yUnitText.value,
                    "onUpdate:modelValue": ($event) => yUnitText.value = $event,
                    label: "Y-axis unit",
                    "helper-text": "One shared unit for every series."
                  }, null, 8, ["modelValue", "onUpdate:modelValue"]),
                  createVNode(_sfc_main$5, {
                    label: "Y-axis format",
                    "model-value": yFormatValue.value,
                    options: unref(yFormatOptions),
                    clearable: false,
                    "onUpdate:modelValue": chooseYFormat
                  }, null, 8, ["model-value", "options"])
                ]),
                createVNode(unref(_sfc_main$6), {
                  modelValue: seriesText.value,
                  "onUpdate:modelValue": ($event) => seriesText.value = $event,
                  label: "Series",
                  rows: 4,
                  "helper-text": "One series per line: Scenario: 20, 42, 64"
                }, null, 8, ["modelValue", "onUpdate:modelValue"])
              ])) : isKpi.value ? (openBlock(), createBlock("div", {
                key: 1,
                class: "space-y-4"
              }, [
                createVNode(unref(_sfc_main$6), {
                  modelValue: itemsText.value,
                  "onUpdate:modelValue": ($event) => itemsText.value = $event,
                  label: "KPI items",
                  rows: 5,
                  "helper-text": "One KPI per line: Label|Value|direction|sparkline comma values"
                }, null, 8, ["modelValue", "onUpdate:modelValue"])
              ])) : (openBlock(), createBlock("div", {
                key: 2,
                class: "rounded-lg border border-dashed border-ui-border p-4 text-sm text-ui-muted-foreground"
              }, [
                createVNode(unref(_sfc_main$3), {
                  label: "Unsupported editor",
                  icon: unref(AlertTriangle),
                  color: "warning",
                  variant: "soft"
                }, null, 8, ["icon"]),
                createVNode("p", { class: "mt-3" }, "Select line, area, bar or KPI to save with a structured live preview.")
              ])),
              hasErrors.value ? (openBlock(), createBlock("div", {
                key: 3,
                class: "rounded-lg border border-ui-warning/40 bg-ui-warning/10 p-3 text-sm text-ui-muted-foreground"
              }, [
                (openBlock(true), createBlock(Fragment, null, renderList(errors.value, (error) => {
                  return openBlock(), createBlock("p", { key: error }, toDisplayString(error), 1);
                }), 128))
              ])) : createCommentVNode("", true),
              createVNode(_sfc_main$7, { visualization: previewVisualization.value }, null, 8, ["visualization"])
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Doomsday/VisualizationPayloadEditor.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "VisualizationForm",
  __ssrInlineRender: true,
  props: {
    options: {},
    submitUrl: {},
    method: {},
    submitLabel: {},
    visualization: { default: void 0 },
    showTopActions: { type: Boolean, default: false }
  },
  emits: ["saved", "cancel"],
  setup(__props, { emit: __emit }) {
    var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j;
    const editableTypes = ["line", "area", "bar", "kpi"];
    const props = __props;
    function isChartType(type) {
      return ["line", "area", "bar"].includes(type);
    }
    function schemaVersion(type) {
      return isChartType(type) ? 2 : 1;
    }
    function isHttpsUrl(value) {
      try {
        return new URL(value).protocol === "https:";
      } catch {
        return false;
      }
    }
    const initialType = ((_a = props.visualization) == null ? void 0 : _a.type) ?? first(props.options.visualization_types.filter((type) => editableTypes.includes(type)), "line");
    const initialPayload = ((_b = props.visualization) == null ? void 0 : _b.payload) ?? defaultPayload(initialType);
    const emit = __emit;
    const form = useSmartForm({ ...SaveVisualizationDataRules });
    const payloadDraft = ref(initialPayload);
    const payloadIsValid = ref(true);
    const sourcesText = ref((((_c = props.visualization) == null ? void 0 : _c.sources) ?? []).join("\n"));
    const parsedSources = computed(() => sourcesText.value.split("\n").map((source) => source.trim()).filter((source) => source !== ""));
    const evidenceError = computed(() => {
      if (form.reasoning.en.trim() === "") {
        return "Explain how the values were calculated or selected.";
      }
      if (parsedSources.value.length === 0) {
        return "Add at least one HTTPS source URL.";
      }
      if (parsedSources.value.some((source) => !isHttpsUrl(source))) {
        return "Every source must be a valid HTTPS URL.";
      }
      return "";
    });
    const supportedOptions = computed(() => optionItems(props.options.visualization_types.filter((type) => editableTypes.includes(type))));
    const submitDisabled = computed(() => form.processing || !payloadIsValid.value || evidenceError.value !== "");
    form.fill({
      key: ((_d = props.visualization) == null ? void 0 : _d.key) ?? "",
      type: initialType,
      title: localizedText(((_e = props.visualization) == null ? void 0 : _e.title.en) ?? ""),
      description: localizedText(((_g = (_f = props.visualization) == null ? void 0 : _f.description) == null ? void 0 : _g.en) ?? ""),
      sources: [...((_h = props.visualization) == null ? void 0 : _h.sources) ?? []],
      reasoning: localizedText(((_i = props.visualization) == null ? void 0 : _i.reasoning.en) ?? ""),
      payload: initialPayload,
      schema_version: schemaVersion(initialType),
      sort_order: ((_j = props.visualization) == null ? void 0 : _j.sort_order) ?? 0
    });
    function chooseType(value) {
      if (typeof value === "string") {
        form.type = value;
      }
    }
    watch(() => form.type, (type) => {
      if (!editableTypes.includes(type)) {
        form.type = "line";
        return;
      }
      form.schema_version = schemaVersion(type);
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<form${ssrRenderAttrs(mergeProps({ class: "space-y-5" }, _attrs))}>`);
      if (__props.showTopActions) {
        _push(`<div class="flex flex-wrap items-center justify-end gap-2 border-b border-ui-border/60 pb-4">`);
        _push(ssrRenderComponent(unref(Button), {
          type: "submit",
          loading: unref(form).processing,
          disabled: submitDisabled.value
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
      _push(`<div class="grid gap-4 md:grid-cols-4">`);
      _push(ssrRenderComponent(unref(_sfc_main$4), {
        modelValue: unref(form).key,
        "onUpdate:modelValue": ($event) => unref(form).key = $event,
        label: "Key",
        "helper-text": "Stable identifier used by frontend chart integrations.",
        error: unref(form).errors.key
      }, null, _parent));
      _push(`<div>`);
      _push(ssrRenderComponent(_sfc_main$5, {
        label: "Type",
        "model-value": unref(form).type,
        options: supportedOptions.value,
        clearable: false,
        "onUpdate:modelValue": chooseType
      }, null, _parent));
      _push(`<p class="mt-1 text-xs text-ui-muted-foreground">Line and area are ordered series; bar is for categorical comparisons.</p></div><div>`);
      _push(ssrRenderComponent(unref(NumberInput), {
        modelValue: unref(form).schema_version,
        "onUpdate:modelValue": ($event) => unref(form).schema_version = $event,
        label: "Schema version",
        min: isChartType(unref(form).type) ? 2 : 1,
        error: unref(form).errors.schema_version
      }, null, _parent));
      _push(`<p class="mt-1 text-xs text-ui-muted-foreground">Line, area and bar use schema v2; KPI keeps its distinct payload contract.</p></div><div>`);
      _push(ssrRenderComponent(unref(NumberInput), {
        modelValue: unref(form).sort_order,
        "onUpdate:modelValue": ($event) => unref(form).sort_order = $event,
        label: "Sort order",
        min: 0,
        error: unref(form).errors.sort_order
      }, null, _parent));
      _push(`<p class="mt-1 text-xs text-ui-muted-foreground">Lower numbers appear first.</p></div></div><div class="grid gap-4 md:grid-cols-2">`);
      _push(ssrRenderComponent(unref(_sfc_main$4), {
        modelValue: unref(form).title.en,
        "onUpdate:modelValue": ($event) => unref(form).title.en = $event,
        label: "Title (EN)",
        error: unref(form).errors.title
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$6), {
        modelValue: unref(form).description.en,
        "onUpdate:modelValue": ($event) => unref(form).description.en = $event,
        label: "Description (EN)",
        rows: 2,
        error: unref(form).errors.description
      }, null, _parent));
      _push(`</div><div class="grid gap-4 md:grid-cols-2">`);
      _push(ssrRenderComponent(unref(_sfc_main$6), {
        modelValue: unref(form).reasoning.en,
        "onUpdate:modelValue": ($event) => unref(form).reasoning.en = $event,
        label: "Reasoning / calculation (EN)",
        rows: 5,
        "helper-text": "Explain formulas, transformations, assumptions and whether values are observed, projected or editorial.",
        error: unref(form).errors.reasoning
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$6), {
        modelValue: sourcesText.value,
        "onUpdate:modelValue": ($event) => sourcesText.value = $event,
        label: "Sources",
        rows: 5,
        "helper-text": "One authoritative HTTPS source URL per line. Stored on the visualization, not inside its payload.",
        error: unref(form).errors.sources
      }, null, _parent));
      _push(`</div>`);
      if (evidenceError.value) {
        _push(`<p class="text-sm text-ui-danger">${ssrInterpolate(evidenceError.value)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(ssrRenderComponent(_sfc_main$1, {
        type: unref(form).type,
        "onUpdate:type": ($event) => unref(form).type = $event,
        payload: payloadDraft.value,
        "onUpdate:payload": ($event) => payloadDraft.value = $event,
        valid: payloadIsValid.value,
        "onUpdate:valid": ($event) => payloadIsValid.value = $event,
        sources: parsedSources.value,
        reasoning: unref(form).reasoning.en
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$8, { compact: "" }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(Button), {
              type: "submit",
              loading: unref(form).processing,
              disabled: submitDisabled.value
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
                loading: unref(form).processing,
                disabled: submitDisabled.value
              }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(__props.submitLabel), 1)
                ]),
                _: 1
              }, 8, ["loading", "disabled"]),
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Doomsday/VisualizationForm.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
