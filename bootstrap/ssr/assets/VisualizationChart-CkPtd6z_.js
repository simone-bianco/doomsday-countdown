import { defineComponent, computed, mergeProps, useSSRContext, unref, withCtx, createVNode, toDisplayString, openBlock, createBlock, Fragment, renderList, createTextVNode, ref, onMounted, onBeforeUnmount } from "vue";
import { ssrRenderAttrs, ssrInterpolate, ssrRenderList, ssrRenderAttr, ssrRenderComponent, ssrRenderStyle, ssrRenderClass, ssrIncludeBooleanAttr } from "vue/server-renderer";
import { h as _sfc_main$3, t } from "../ssr.js";
import { X, Maximize2, Minus, Plus, RotateCcw } from "lucide-vue-next";
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "VisualizationEvidence",
  __ssrInlineRender: true,
  props: {
    sources: {},
    reasoning: {}
  },
  setup(__props) {
    const props = __props;
    function sourceLabel(source, index) {
      try {
        return new URL(source).hostname.replace(/^www\./, "");
      } catch {
        return `Source ${index + 1}`;
      }
    }
    const validSources = computed(() => props.sources.filter((source) => {
      try {
        return new URL(source).protocol === "https:";
      } catch {
        return false;
      }
    }));
    const reasoningText = computed(() => {
      var _a;
      return ((_a = props.reasoning) == null ? void 0 : _a.trim()) ?? "";
    });
    return (_ctx, _push, _parent, _attrs) => {
      if (reasoningText.value || validSources.value.length) {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "min-w-0 space-y-3 border-t border-white/10 pt-3 text-xs leading-relaxed text-white/55" }, _attrs))}>`);
        if (reasoningText.value) {
          _push(`<p class="min-w-0 break-words"><span class="font-semibold text-white/75">Reasoning:</span> ${ssrInterpolate(reasoningText.value)}</p>`);
        } else {
          _push(`<!---->`);
        }
        if (validSources.value.length) {
          _push(`<div class="min-w-0"><span class="font-semibold text-white/75">Sources:</span><!--[-->`);
          ssrRenderList(validSources.value, (source, index) => {
            _push(`<a${ssrRenderAttr("href", source)} target="_blank" rel="noopener noreferrer" class="ml-2 inline-block max-w-full break-words align-top text-ui-primary underline-offset-2 hover:underline"${ssrRenderAttr("title", source)}>${ssrInterpolate(sourceLabel(source, index))}</a>`);
          });
          _push(`<!--]--></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
});
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/VisualizationEvidence.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "KeyIndicatorsCard",
  __ssrInlineRender: true,
  props: {
    visualization: {}
  },
  setup(__props) {
    const props = __props;
    function isRecord(value) {
      return typeof value === "object" && value !== null;
    }
    const indicators = computed(() => {
      var _a;
      const payload = (_a = props.visualization) == null ? void 0 : _a.payload;
      const items = isRecord(payload) ? payload.items : null;
      if (!Array.isArray(items)) {
        return [];
      }
      return items.filter(isRecord).map((item) => ({
        label: String(item.label ?? ""),
        value: String(item.value ?? ""),
        direction: String(item.direction ?? "up"),
        sparkline: Array.isArray(item.sparkline) ? item.sparkline.map(Number).filter(Number.isFinite) : []
      }));
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(_sfc_main$3), mergeProps({ ui: { root: "doomsday-card min-w-0 rounded-xl", body: "min-w-0 p-5 pb-6 sm:p-6 sm:pb-8" } }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          var _a, _b, _c, _d;
          if (_push2) {
            _push2(`<h3 class="doomsday-display mb-5 text-lg text-white"${_scopeId}>${ssrInterpolate(unref(t)("keyIndicators"))}</h3><div class="grid min-w-0 gap-4"${_scopeId}><!--[-->`);
            ssrRenderList(indicators.value, (indicator) => {
              _push2(`<div class="grid min-w-0 grid-cols-[minmax(0,1fr)_auto] items-center gap-x-3 gap-y-2 border-b border-white/5 pb-3 last:border-b-0 last:pb-0 sm:grid-cols-[minmax(0,1fr)_auto_minmax(56px,90px)]"${_scopeId}><span class="min-w-0 break-words text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(indicator.label)}</span><span class="whitespace-nowrap text-sm text-white"${_scopeId}>${ssrInterpolate(indicator.value)} <span class="text-ui-primary"${_scopeId}>${ssrInterpolate(indicator.direction === "up" ? "↑" : "↓")}</span></span><div class="col-span-2 flex h-5 min-w-0 max-w-full items-end gap-1 overflow-hidden sm:col-span-1"${_scopeId}><!--[-->`);
              ssrRenderList(indicator.sparkline, (value, index) => {
                _push2(`<span class="min-w-0 flex-1 bg-ui-primary" style="${ssrRenderStyle({ height: `${Math.max(15, value)}%` })}"${_scopeId}></span>`);
              });
              _push2(`<!--]--></div></div>`);
            });
            _push2(`<!--]--></div><div class="mt-5 min-w-0 pb-1"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$2, {
              sources: ((_a = __props.visualization) == null ? void 0 : _a.sources) ?? [],
              reasoning: ((_b = __props.visualization) == null ? void 0 : _b.reasoning) ?? null
            }, null, _parent2, _scopeId));
            _push2(`</div>`);
          } else {
            return [
              createVNode("h3", { class: "doomsday-display mb-5 text-lg text-white" }, toDisplayString(unref(t)("keyIndicators")), 1),
              createVNode("div", { class: "grid min-w-0 gap-4" }, [
                (openBlock(true), createBlock(Fragment, null, renderList(indicators.value, (indicator) => {
                  return openBlock(), createBlock("div", {
                    key: indicator.label,
                    class: "grid min-w-0 grid-cols-[minmax(0,1fr)_auto] items-center gap-x-3 gap-y-2 border-b border-white/5 pb-3 last:border-b-0 last:pb-0 sm:grid-cols-[minmax(0,1fr)_auto_minmax(56px,90px)]"
                  }, [
                    createVNode("span", { class: "min-w-0 break-words text-sm text-ui-muted-foreground" }, toDisplayString(indicator.label), 1),
                    createVNode("span", { class: "whitespace-nowrap text-sm text-white" }, [
                      createTextVNode(toDisplayString(indicator.value) + " ", 1),
                      createVNode("span", { class: "text-ui-primary" }, toDisplayString(indicator.direction === "up" ? "↑" : "↓"), 1)
                    ]),
                    createVNode("div", { class: "col-span-2 flex h-5 min-w-0 max-w-full items-end gap-1 overflow-hidden sm:col-span-1" }, [
                      (openBlock(true), createBlock(Fragment, null, renderList(indicator.sparkline, (value, index) => {
                        return openBlock(), createBlock("span", {
                          key: index,
                          class: "min-w-0 flex-1 bg-ui-primary",
                          style: { height: `${Math.max(15, value)}%` }
                        }, null, 4);
                      }), 128))
                    ])
                  ]);
                }), 128))
              ]),
              createVNode("div", { class: "mt-5 min-w-0 pb-1" }, [
                createVNode(_sfc_main$2, {
                  sources: ((_c = __props.visualization) == null ? void 0 : _c.sources) ?? [],
                  reasoning: ((_d = __props.visualization) == null ? void 0 : _d.reasoning) ?? null
                }, null, 8, ["sources", "reasoning"])
              ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/KeyIndicatorsCard.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const MIN_CHART_SCALE = 1;
const MAX_CHART_SCALE = 4;
const CHART_ZOOM_STEP = 0.5;
function useMobileChartZoom(mobile) {
  const chartSurface = ref(null);
  const isZoomed = ref(false);
  const chartScale = ref(MIN_CHART_SCALE);
  const chartPanX = ref(0);
  const chartPanY = ref(0);
  const chartTransformStyle = computed(() => ({
    transform: `translate3d(${chartPanX.value}px, ${chartPanY.value}px, 0) scale(${chartScale.value})`,
    transformOrigin: "center center"
  }));
  let previousDocumentOverflow = null;
  let chartGesture = null;
  let lastChartTapAt = 0;
  function restoreDocumentScroll() {
    if (typeof document === "undefined" || previousDocumentOverflow === null) {
      return;
    }
    document.documentElement.style.overflow = previousDocumentOverflow;
    previousDocumentOverflow = null;
  }
  function clamp(value, minimum, maximum) {
    return Math.min(Math.max(value, minimum), maximum);
  }
  function touchDistance(first, second) {
    return Math.hypot(second.clientX - first.clientX, second.clientY - first.clientY);
  }
  function clampChartPan(x, y, scale = chartScale.value) {
    var _a;
    if (scale <= MIN_CHART_SCALE) {
      return { x: 0, y: 0 };
    }
    const bounds = (_a = chartSurface.value) == null ? void 0 : _a.getBoundingClientRect();
    const viewportWidth = (bounds == null ? void 0 : bounds.width) ?? (typeof window === "undefined" ? 0 : window.innerWidth);
    const viewportHeight = (bounds == null ? void 0 : bounds.height) ?? (typeof window === "undefined" ? 0 : window.innerHeight);
    const maxX = viewportWidth * (scale - 1) / 2;
    const maxY = viewportHeight * (scale - 1) / 2;
    return {
      x: clamp(x, -maxX, maxX),
      y: clamp(y, -maxY, maxY)
    };
  }
  function setChartScale(nextScale) {
    const scale = clamp(nextScale, MIN_CHART_SCALE, MAX_CHART_SCALE);
    chartScale.value = scale;
    const pan = clampChartPan(chartPanX.value, chartPanY.value, scale);
    chartPanX.value = pan.x;
    chartPanY.value = pan.y;
  }
  function resetChartMagnification() {
    chartScale.value = MIN_CHART_SCALE;
    chartPanX.value = 0;
    chartPanY.value = 0;
    chartGesture = null;
    lastChartTapAt = 0;
  }
  function zoomChartIn() {
    setChartScale(chartScale.value + CHART_ZOOM_STEP);
  }
  function zoomChartOut() {
    setChartScale(chartScale.value - CHART_ZOOM_STEP);
  }
  function toggleChartMagnification() {
    if (chartScale.value > MIN_CHART_SCALE) {
      resetChartMagnification();
      return;
    }
    setChartScale(2);
  }
  function handleChartTouchStart(event) {
    if (!isZoomed.value) {
      return;
    }
    event.stopPropagation();
    event.preventDefault();
    const first = event.touches.item(0);
    const second = event.touches.item(1);
    if (first && second) {
      chartGesture = {
        mode: "pinch",
        distance: Math.max(touchDistance(first, second), 1),
        scale: chartScale.value
      };
      return;
    }
    if (!first) {
      chartGesture = null;
      return;
    }
    chartGesture = chartScale.value > MIN_CHART_SCALE ? { mode: "pan", x: first.clientX, y: first.clientY, panX: chartPanX.value, panY: chartPanY.value } : { mode: "tap", x: first.clientX, y: first.clientY };
  }
  function handleChartTouchMove(event) {
    if (!isZoomed.value || chartGesture === null) {
      return;
    }
    event.stopPropagation();
    event.preventDefault();
    const first = event.touches.item(0);
    const second = event.touches.item(1);
    if (chartGesture.mode === "pinch" && first && second) {
      setChartScale(chartGesture.scale * (touchDistance(first, second) / chartGesture.distance));
      return;
    }
    if (chartGesture.mode === "pan" && first) {
      const pan = clampChartPan(
        chartGesture.panX + first.clientX - chartGesture.x,
        chartGesture.panY + first.clientY - chartGesture.y
      );
      chartPanX.value = pan.x;
      chartPanY.value = pan.y;
    }
  }
  function handleChartTouchEnd(event) {
    if (!isZoomed.value) {
      return;
    }
    event.stopPropagation();
    event.preventDefault();
    const remainingTouch = event.touches.item(0);
    if (remainingTouch && chartScale.value > MIN_CHART_SCALE) {
      chartGesture = {
        mode: "pan",
        x: remainingTouch.clientX,
        y: remainingTouch.clientY,
        panX: chartPanX.value,
        panY: chartPanY.value
      };
      return;
    }
    const changedTouch = event.changedTouches.item(0);
    if ((chartGesture == null ? void 0 : chartGesture.mode) === "tap" && changedTouch) {
      const movement = Math.hypot(changedTouch.clientX - chartGesture.x, changedTouch.clientY - chartGesture.y);
      const now = Date.now();
      if (movement < 12 && now - lastChartTapAt < 320) {
        toggleChartMagnification();
        lastChartTapAt = 0;
      } else if (movement < 12) {
        lastChartTapAt = now;
      }
    }
    chartGesture = null;
  }
  function resetChartGesture() {
    chartGesture = null;
  }
  function openZoom() {
    if (!mobile.value || isZoomed.value) {
      return;
    }
    isZoomed.value = true;
    resetChartMagnification();
    if (typeof document === "undefined") {
      return;
    }
    previousDocumentOverflow = document.documentElement.style.overflow;
    document.documentElement.style.overflow = "hidden";
    const surface = chartSurface.value;
    if ((surface == null ? void 0 : surface.requestFullscreen) && document.fullscreenElement === null) {
      void surface.requestFullscreen().catch(() => void 0);
    }
  }
  function closeZoom() {
    if (!mobile.value) {
      return;
    }
    if (typeof document !== "undefined" && document.fullscreenElement === chartSurface.value) {
      void document.exitFullscreen().catch(() => void 0);
    }
    isZoomed.value = false;
    resetChartMagnification();
    restoreDocumentScroll();
  }
  function handleFullscreenChange() {
    if (typeof document !== "undefined" && isZoomed.value && document.fullscreenElement !== chartSurface.value) {
      isZoomed.value = false;
      resetChartMagnification();
      restoreDocumentScroll();
    }
  }
  function handleEscape(event) {
    if (event.key === "Escape" && isZoomed.value) {
      closeZoom();
    }
  }
  function stopZoomedTouchPropagation(event) {
    if (isZoomed.value) {
      event.stopPropagation();
    }
  }
  onMounted(() => {
    document.addEventListener("fullscreenchange", handleFullscreenChange);
    document.addEventListener("keydown", handleEscape);
  });
  onBeforeUnmount(() => {
    document.removeEventListener("fullscreenchange", handleFullscreenChange);
    document.removeEventListener("keydown", handleEscape);
    resetChartMagnification();
    restoreDocumentScroll();
  });
  return {
    chartSurface,
    isZoomed,
    chartScale,
    chartPanX,
    chartPanY,
    chartTransformStyle,
    minChartScale: MIN_CHART_SCALE,
    maxChartScale: MAX_CHART_SCALE,
    openZoom,
    closeZoom,
    zoomChartIn,
    zoomChartOut,
    resetChartMagnification,
    toggleChartMagnification,
    handleChartTouchStart,
    handleChartTouchMove,
    handleChartTouchEnd,
    resetChartGesture,
    stopZoomedTouchPropagation
  };
}
const width = 680;
const height = 360;
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "VisualizationChart",
  __ssrInlineRender: true,
  props: {
    payload: {},
    type: {},
    sources: {},
    reasoning: {},
    compact: { type: Boolean, default: false },
    mobile: { type: Boolean, default: false }
  },
  setup(__props) {
    const props = __props;
    const plot = { left: 72, top: 44, right: 636, bottom: 286 };
    const fallbackColors = ["#ff2a23", "#22c55e", "#38bdf8", "#f59e0b"];
    function isRecord(value) {
      return typeof value === "object" && value !== null && !Array.isArray(value);
    }
    function toNumber(value) {
      const parsed = Number(value);
      return Number.isFinite(parsed) ? parsed : null;
    }
    function isChartType(value) {
      return ["line", "area", "bar"].includes(value);
    }
    function parseXAxis(value) {
      if (!isRecord(value) || typeof value.label !== "string" || !["temporal", "ordinal", "category"].includes(String(value.type))) {
        return null;
      }
      return { label: value.label, type: value.type };
    }
    function parseYAxis(value) {
      if (!isRecord(value) || typeof value.label !== "string" || typeof value.unit !== "string" || !["integer", "decimal", "percent", "currency"].includes(String(value.format))) {
        return null;
      }
      return { label: value.label, unit: value.unit, format: value.format };
    }
    const chartType = computed(() => isChartType(props.type) ? props.type : null);
    const source = computed(() => isRecord(props.payload) ? props.payload : {});
    const labels = computed(() => Array.isArray(source.value.labels) ? source.value.labels.filter((label) => typeof label === "string" && label.trim() !== "") : []);
    const axes = computed(() => isRecord(source.value.axes) ? source.value.axes : {});
    const xAxis = computed(() => parseXAxis(axes.value.x));
    const yAxis = computed(() => parseYAxis(axes.value.y));
    const validSources = computed(() => props.sources.filter((item) => item.startsWith("https://")));
    const {
      chartSurface,
      isZoomed,
      chartScale,
      chartPanX,
      chartPanY,
      chartTransformStyle,
      minChartScale,
      maxChartScale
    } = useMobileChartZoom(computed(() => props.mobile));
    const rawSeries = computed(() => {
      var _a;
      const raw = source.value.series;
      if (!Array.isArray(raw) || raw.length === 0) {
        return [];
      }
      if (raw.every(isRecord)) {
        return raw.map((entry, index) => {
          if (typeof entry.name !== "string" || !Array.isArray(entry.values)) {
            return null;
          }
          const values2 = entry.values.map(toNumber);
          if (values2.some((value) => value === null)) {
            return null;
          }
          return {
            name: entry.name,
            color: typeof entry.color === "string" ? entry.color : fallbackColors[index % fallbackColors.length],
            values: values2
          };
        }).filter((series2) => series2 !== null);
      }
      const values = raw.map(toNumber);
      if (values.some((value) => value === null)) {
        return [];
      }
      return [{
        name: ((_a = yAxis.value) == null ? void 0 : _a.label) || "Series",
        color: fallbackColors[0],
        values
      }];
    });
    const isPayloadValid = computed(() => {
      if (!chartType.value || !xAxis.value || !yAxis.value || labels.value.length === 0 || validSources.value.length === 0 || rawSeries.value.length === 0) {
        return false;
      }
      if (chartType.value === "bar" && xAxis.value.type !== "category") {
        return false;
      }
      if (chartType.value !== "bar" && xAxis.value.type === "category") {
        return false;
      }
      return rawSeries.value.every((series2) => series2.values.length === labels.value.length);
    });
    const allValues = computed(() => rawSeries.value.flatMap((series2) => [...series2.values]));
    const minValue = computed(() => Math.min(0, ...allValues.value));
    const maxValue = computed(() => Math.max(...allValues.value, 1));
    const range = computed(() => Math.max(maxValue.value - minValue.value, 1));
    const paddedMax = computed(() => maxValue.value + range.value * 0.15);
    const paddedMin = computed(() => Math.min(0, minValue.value - range.value * 0.08));
    const paddedRange = computed(() => Math.max(paddedMax.value - paddedMin.value, 1));
    function xAt(index, count) {
      if (chartType.value === "bar") {
        return plot.left + (index + 0.5) / Math.max(count, 1) * (plot.right - plot.left);
      }
      return plot.left + index / Math.max(count - 1, 1) * (plot.right - plot.left);
    }
    function yAt(value) {
      return plot.bottom - (value - paddedMin.value) / paddedRange.value * (plot.bottom - plot.top);
    }
    function formatValue(value) {
      var _a;
      const format = (_a = yAxis.value) == null ? void 0 : _a.format;
      if (format === "integer") {
        return Math.round(value).toLocaleString();
      }
      if (format === "percent") {
        return `${Math.round(value * 10) / 10}%`;
      }
      return value.toLocaleString(void 0, { maximumFractionDigits: 2 });
    }
    const series = computed(() => rawSeries.value.map((item) => {
      const points = item.values.map((value, index) => ({
        x: xAt(index, item.values.length),
        y: yAt(value),
        label: labels.value[index] ?? `${index + 1}`,
        value
      }));
      const path = points.map((point, index) => `${index === 0 ? "M" : "L"} ${point.x} ${point.y}`).join(" ");
      const baseline = yAt(0);
      const areaPath = points.length === 0 ? "" : `M ${points[0].x} ${baseline} L ${points.map((point) => `${point.x} ${point.y}`).join(" L ")} L ${points[points.length - 1].x} ${baseline} Z`;
      return { ...item, points, path, areaPath };
    }));
    const bars = computed(() => {
      if (chartType.value !== "bar" || labels.value.length === 0 || rawSeries.value.length === 0) {
        return [];
      }
      const categoryWidth = (plot.right - plot.left) / labels.value.length;
      const groupWidth = categoryWidth * 0.72;
      const slotWidth = groupWidth / rawSeries.value.length;
      const barWidth = Math.max(slotWidth - 4, 2);
      const zeroY = yAt(0);
      return rawSeries.value.flatMap((item, seriesIndex) => item.values.map((value, labelIndex) => {
        const valueY = yAt(value);
        return {
          x: plot.left + categoryWidth * labelIndex + (categoryWidth - groupWidth) / 2 + slotWidth * seriesIndex + 2,
          y: Math.min(zeroY, valueY),
          width: barWidth,
          height: Math.max(Math.abs(zeroY - valueY), 1),
          label: labels.value[labelIndex] ?? `${labelIndex + 1}`,
          value,
          name: item.name,
          color: item.color
        };
      }));
    });
    const yTicks = computed(() => [paddedMax.value, (paddedMax.value + paddedMin.value) / 2, paddedMin.value]);
    const rotateLabels = computed(() => labels.value.length > 5 || labels.value.some((label) => label.length > 14));
    const ariaLabel = computed(() => {
      var _a, _b, _c;
      return isPayloadValid.value ? `${chartType.value} chart. X axis: ${(_a = xAxis.value) == null ? void 0 : _a.label}. Y axis: ${(_b = yAxis.value) == null ? void 0 : _b.label}, unit ${(_c = yAxis.value) == null ? void 0 : _c.unit}.` : "Visualization unavailable because the schema v2 payload is invalid.";
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c;
      _push(`<div${ssrRenderAttrs(mergeProps({
        ref_key: "chartSurface",
        ref: chartSurface,
        class: [
          !__props.mobile ? "doomsday-scrollbar w-full overflow-x-auto rounded-lg border border-white/10 bg-black/35 py-4 pb-6" : "",
          !__props.mobile ? __props.compact ? "px-2 sm:px-3" : "px-3 sm:px-5" : "",
          __props.mobile && !unref(isZoomed) ? "relative w-full overflow-hidden border-0 bg-transparent px-0 py-2" : "",
          __props.mobile && unref(isZoomed) ? "fixed inset-0 z-[100] flex h-[100dvh] w-[100dvw] select-none items-center justify-center overflow-hidden bg-black/95 p-2" : ""
        ]
      }, _attrs))}>`);
      if (__props.mobile && unref(isZoomed)) {
        _push(`<button type="button" class="absolute right-3 top-3 z-30 inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/15 bg-black/80 text-white shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary"${ssrRenderAttr("aria-label", unref(t)("closeChart"))}>`);
        _push(ssrRenderComponent(unref(X), { class: "h-5 w-5" }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      if (isPayloadValid.value) {
        _push(`<!--[--><div class="${ssrRenderClass(unref(isZoomed) ? "relative flex h-full w-full touch-none items-center justify-center overflow-hidden" : "relative w-full")}"><svg${ssrRenderAttr("viewBox", `0 0 ${width} ${height}`)} class="${ssrRenderClass(__props.mobile ? unref(isZoomed) ? "block h-auto max-h-[calc(100dvh-3rem)] w-full max-w-[100dvw] will-change-transform" : "block h-auto w-full max-w-full" : "h-[22rem] min-w-[600px] w-full")}" style="${ssrRenderStyle(unref(isZoomed) ? unref(chartTransformStyle) : void 0)}" role="img"${ssrRenderAttr("aria-label", ariaLabel.value)}><g class="text-white/10"><!--[-->`);
        ssrRenderList(yTicks.value, (tick) => {
          _push(`<line${ssrRenderAttr("x1", plot.left)}${ssrRenderAttr("y1", yAt(tick))}${ssrRenderAttr("x2", plot.right)}${ssrRenderAttr("y2", yAt(tick))} stroke="currentColor"></line>`);
        });
        _push(`<!--]--><!--[-->`);
        ssrRenderList(labels.value, (label, index) => {
          _push(`<line${ssrRenderAttr("x1", xAt(index, labels.value.length))}${ssrRenderAttr("y1", plot.top)}${ssrRenderAttr("x2", xAt(index, labels.value.length))}${ssrRenderAttr("y2", plot.bottom)} stroke="currentColor"></line>`);
        });
        _push(`<!--]--><line${ssrRenderAttr("x1", plot.left)}${ssrRenderAttr("y1", plot.top)}${ssrRenderAttr("x2", plot.left)}${ssrRenderAttr("y2", plot.bottom)} stroke="currentColor"></line><line${ssrRenderAttr("x1", plot.left)}${ssrRenderAttr("y1", plot.bottom)}${ssrRenderAttr("x2", plot.right)}${ssrRenderAttr("y2", plot.bottom)} stroke="currentColor"></line></g><g class="${ssrRenderClass(["doomsday-display text-white/55", __props.mobile ? "text-[16px]" : "text-[11px]"])}"><!--[-->`);
        ssrRenderList(yTicks.value, (tick) => {
          _push(`<text${ssrRenderAttr("x", plot.left - 12)}${ssrRenderAttr("y", yAt(tick) + 4)} text-anchor="end" fill="currentColor">${ssrInterpolate(formatValue(tick))}</text>`);
        });
        _push(`<!--]--><!--[-->`);
        ssrRenderList(labels.value, (label, index) => {
          _push(`<text${ssrRenderAttr("x", xAt(index, labels.value.length))}${ssrRenderAttr("y", plot.bottom + 28)}${ssrRenderAttr("text-anchor", rotateLabels.value ? "end" : "middle")}${ssrRenderAttr("transform", rotateLabels.value ? `rotate(-22 ${xAt(index, labels.value.length)} ${plot.bottom + 28})` : void 0)} fill="currentColor"><title>${ssrInterpolate(label)}</title>${ssrInterpolate(label)}</text>`);
        });
        _push(`<!--]--><text${ssrRenderAttr("x", (plot.left + plot.right) / 2)}${ssrRenderAttr("y", height - 6)} text-anchor="middle" fill="currentColor">${ssrInterpolate((_a = xAxis.value) == null ? void 0 : _a.label)}</text><text${ssrRenderAttr("transform", `rotate(-90 16 ${(plot.top + plot.bottom) / 2})`)} x="16"${ssrRenderAttr("y", (plot.top + plot.bottom) / 2)} text-anchor="middle" fill="currentColor">${ssrInterpolate((_b = yAxis.value) == null ? void 0 : _b.label)} (${ssrInterpolate((_c = yAxis.value) == null ? void 0 : _c.unit)})</text></g>`);
        if (chartType.value === "bar") {
          _push(`<g><!--[-->`);
          ssrRenderList(bars.value, (bar) => {
            var _a2;
            _push(`<rect${ssrRenderAttr("x", bar.x)}${ssrRenderAttr("y", bar.y)}${ssrRenderAttr("width", bar.width)}${ssrRenderAttr("height", bar.height)}${ssrRenderAttr("fill", bar.color)} rx="2"><title>${ssrInterpolate(bar.name)} · ${ssrInterpolate(bar.label)}: ${ssrInterpolate(formatValue(bar.value))} ${ssrInterpolate((_a2 = yAxis.value) == null ? void 0 : _a2.unit)}</title></rect>`);
          });
          _push(`<!--]--></g>`);
        } else {
          _push(`<!--[-->`);
          ssrRenderList(series.value, (item) => {
            _push(`<g>`);
            if (chartType.value === "area" && item.points.length > 1) {
              _push(`<path${ssrRenderAttr("d", item.areaPath)}${ssrRenderAttr("fill", item.color)} fill-opacity="0.18"></path>`);
            } else {
              _push(`<!---->`);
            }
            if (item.points.length > 1) {
              _push(`<path${ssrRenderAttr("d", item.path)} fill="none"${ssrRenderAttr("stroke", item.color)}${ssrRenderAttr("stroke-width", __props.mobile ? 4 : 3)} stroke-linecap="round" stroke-linejoin="round"></path>`);
            } else {
              _push(`<!---->`);
            }
            _push(`<!--[-->`);
            ssrRenderList(item.points, (point) => {
              var _a2;
              _push(`<circle${ssrRenderAttr("cx", point.x)}${ssrRenderAttr("cy", point.y)}${ssrRenderAttr("r", __props.mobile ? 5 : 4)}${ssrRenderAttr("fill", item.color)}><title>${ssrInterpolate(item.name)} · ${ssrInterpolate(point.label)}: ${ssrInterpolate(formatValue(point.value))} ${ssrInterpolate((_a2 = yAxis.value) == null ? void 0 : _a2.unit)}</title></circle>`);
            });
            _push(`<!--]--></g>`);
          });
          _push(`<!--]-->`);
        }
        _push(`<g class="${ssrRenderClass(["doomsday-display", __props.mobile ? "text-[16px]" : "text-[11px]"])}"><!--[-->`);
        ssrRenderList(series.value, (item, index) => {
          _push(`<g${ssrRenderAttr("transform", `translate(${plot.left + index * 150}, 16)`)}><line x1="0" y1="0" x2="22" y2="0"${ssrRenderAttr("stroke", item.color)} stroke-width="3" stroke-linecap="round"></line><text x="30" y="4" fill="rgba(255,255,255,0.72)">${ssrInterpolate(item.name)}</text></g>`);
        });
        _push(`<!--]--></g></svg>`);
        if (__props.mobile && !unref(isZoomed)) {
          _push(`<button type="button" class="absolute inset-0 z-10 cursor-zoom-in rounded-lg bg-transparent focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-ui-primary"${ssrRenderAttr("aria-label", unref(t)("expandChart"))}><span class="pointer-events-none absolute bottom-2 right-2 inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-black/80 px-2.5 py-1.5 text-[10px] font-medium text-white shadow-lg">`);
          _push(ssrRenderComponent(unref(Maximize2), { class: "h-3.5 w-3.5" }, null, _parent));
          _push(` ${ssrInterpolate(unref(t)("chartZoomHint"))}</span></button>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
        if (__props.mobile && unref(isZoomed)) {
          _push(`<div class="absolute bottom-3 left-1/2 z-30 flex -translate-x-1/2 items-center gap-1 rounded-full border border-white/15 bg-black/85 p-1 shadow-xl backdrop-blur-xl"><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-white disabled:cursor-not-allowed disabled:opacity-35 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary"${ssrRenderAttr("aria-label", unref(t)("zoomOutChart"))}${ssrIncludeBooleanAttr(unref(chartScale) <= unref(minChartScale)) ? " disabled" : ""}>`);
          _push(ssrRenderComponent(unref(Minus), { class: "h-4 w-4" }, null, _parent));
          _push(`</button><span class="min-w-12 text-center text-xs tabular-nums text-white" aria-live="polite">${ssrInterpolate(Math.round(unref(chartScale) * 100))}%</span><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-white disabled:cursor-not-allowed disabled:opacity-35 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary"${ssrRenderAttr("aria-label", unref(t)("zoomInChart"))}${ssrIncludeBooleanAttr(unref(chartScale) >= unref(maxChartScale)) ? " disabled" : ""}>`);
          _push(ssrRenderComponent(unref(Plus), { class: "h-4 w-4" }, null, _parent));
          _push(`</button><button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-white disabled:cursor-not-allowed disabled:opacity-35 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-primary"${ssrRenderAttr("aria-label", unref(t)("resetChartZoom"))}${ssrIncludeBooleanAttr(unref(chartScale) === unref(minChartScale) && unref(chartPanX) === 0 && unref(chartPanY) === 0) ? " disabled" : ""}>`);
          _push(ssrRenderComponent(unref(RotateCcw), { class: "h-4 w-4" }, null, _parent));
          _push(`</button></div>`);
        } else {
          _push(`<!---->`);
        }
        if (__props.mobile && unref(isZoomed)) {
          _push(`<p class="pointer-events-none absolute left-3 top-3 z-20 rounded-full bg-black/70 px-3 py-1.5 text-[10px] text-white/70">${ssrInterpolate(unref(t)("chartZoomGestureHint"))}</p>`);
        } else {
          _push(`<!---->`);
        }
        if (!unref(isZoomed)) {
          _push(ssrRenderComponent(_sfc_main$2, {
            class: __props.mobile ? "min-w-0 px-0" : "min-w-[600px] px-2",
            sources: validSources.value,
            reasoning: __props.reasoning
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`<!--]-->`);
      } else {
        _push(`<p class="${ssrRenderClass(__props.mobile ? "min-w-0 px-2 py-10 text-center text-sm text-white/55" : "min-w-[600px] px-4 py-16 text-center text-sm text-white/55")}">Visualization unavailable: schema v2 axes, series and entity-level HTTPS sources are required.</p>`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Doomsday/VisualizationChart.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _,
  _sfc_main$1 as a,
  _sfc_main$2 as b
};
