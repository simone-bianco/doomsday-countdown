import { h as h$1, defineComponent, computed, createVNode, resolveDynamicComponent, mergeProps, unref, useSSRContext, useAttrs, useSlots, ref, onBeforeUnmount, watch, nextTick, cloneVNode, withCtx, renderSlot, createTextVNode, toDisplayString, openBlock, createBlock, createCommentVNode, onMounted, onUnmounted, useId, toValue, useModel, mergeModels, provide, inject, watchEffect, createSlots, renderList, createSSRApp } from "vue";
import { renderToString } from "@vue/server-renderer";
import { Link, useForm, router, usePage, createInertiaApp } from "@inertiajs/vue3";
import { ssrRenderVNode, ssrRenderComponent, ssrRenderTeleport, ssrRenderClass, ssrRenderStyle, ssrRenderSlot, ssrInterpolate, ssrRenderAttr, ssrIncludeBooleanAttr, ssrRenderAttrs, ssrLooseContain, ssrRenderList, ssrGetDynamicModelProps } from "vue/server-renderer";
import { clsx } from "clsx";
import { twMerge } from "tailwind-merge";
import { Shield, BarChart2, Users, CreditCard, Star, Home, Minus, Layers, Inbox, FolderPlus, HelpCircle, Bell, Zap, Activity, TrendingUp, Clock, PanelLeftOpen, CloudUpload, ArrowUpDown, ArrowDownAZ, Code, Archive, Table, Music, Video, Image, File as File$1, Copy, ExternalLink, User, LogOut, Filter, WandSparkles, Sparkles, RotateCw, UploadCloud, Download, Palette, Component, Folder, LayoutDashboard, Circle, Info, AlertCircle, XCircle, CheckCircle2, CheckCircle, Tag, Calendar, RefreshCw, Check, Trash2, Edit2, Upload, Braces, FolderOpen, Loader2, AlertTriangle, ChevronUp, ChevronRight, ChevronDown, Settings, Search, ArrowLeft, FileText, X, Plus, Pencil, PanelLeftClose, Menu, Sun, Moon, ArrowUp, ArrowDown, MoreHorizontal, ChevronLeft, Camera, ImageOff, ImagePlus } from "lucide-vue-next";
import { Cropper, CircleStencil } from "vue-advanced-cropper";
import { AsyncLocalStorage } from "node:async_hooks";
import i18next from "i18next";
async function resolvePageComponent(path, pages) {
  for (const p2 of Array.isArray(path) ? path : [path]) {
    const page = pages[p2];
    if (typeof page === "undefined") {
      continue;
    }
    return typeof page === "function" ? page() : page;
  }
  throw new Error(`Page not found: ${path}`);
}
function cn(...inputs) {
  return twMerge(clsx(inputs));
}
const lucideIcons = {
  // Most used
  "plus": Plus,
  "x": X,
  "file-text": FileText,
  "arrow-left": ArrowLeft,
  "search": Search,
  // Frequent
  "settings": Settings,
  "chevron-down": ChevronDown,
  "chevron-right": ChevronRight,
  "chevron-up": ChevronUp,
  "alert-triangle": AlertTriangle,
  "loader-2": Loader2,
  "loader": Loader2,
  // Alias
  "folder-open": FolderOpen,
  // Common
  "braces": Braces,
  "upload": Upload,
  "edit-2": Edit2,
  "edit": Edit2,
  // Alias
  "trash-2": Trash2,
  "trash": Trash2,
  // Alias
  "check": Check,
  "refresh-cw": RefreshCw,
  "refresh": RefreshCw,
  // Alias
  "calendar": Calendar,
  "tag": Tag,
  // Status
  "check-circle": CheckCircle,
  "check-circle-2": CheckCircle2,
  "x-circle": XCircle,
  "alert-circle": AlertCircle,
  "info": Info,
  "circle": Circle,
  // Navigation
  "layout-dashboard": LayoutDashboard,
  "dashboard": LayoutDashboard,
  // Alias
  "folder": Folder,
  "component": Component,
  "palette": Palette,
  // Actions
  "download": Download,
  "upload-cloud": UploadCloud,
  "rotate-cw": RotateCw,
  "sparkles": Sparkles,
  "wand-sparkles": WandSparkles,
  "filter": Filter,
  "log-out": LogOut,
  "logout": LogOut,
  // Alias
  "user": User,
  "external-link": ExternalLink,
  "copy": Copy,
  // File types
  "file": File$1,
  "image": Image,
  "video": Video,
  "music": Music,
  "table": Table,
  "archive": Archive,
  "code": Code,
  // Arrows & Chevrons
  "arrow-down-a-z": ArrowDownAZ,
  "arrow-up-down": ArrowUpDown,
  "cloud-upload": CloudUpload,
  "panel-left-open": PanelLeftOpen,
  // Misc
  "clock": Clock,
  "trending-up": TrendingUp,
  "activity": Activity,
  "zap": Zap,
  "bell": Bell,
  "help-circle": HelpCircle,
  "help": HelpCircle,
  // Alias
  "folder-plus": FolderPlus,
  "inbox": Inbox,
  "layers": Layers,
  "minus": Minus,
  // Additional
  "home": Home,
  "star": Star,
  "credit-card": CreditCard,
  "users": Users,
  "bar-chart-2": BarChart2,
  "shield": Shield
};
const customIconRegistry = /* @__PURE__ */ new Map();
function resolveIcon(icon) {
  if (typeof icon !== "string") {
    return icon;
  }
  if (customIconRegistry.has(icon)) {
    return customIconRegistry.get(icon);
  }
  if (icon.startsWith("mdi-")) {
    console.warn(`MDI icon "${icon}" requested but MDI support is not yet implemented`);
    return null;
  }
  const lucideIcon = lucideIcons[icon];
  if (lucideIcon) {
    return lucideIcon;
  }
  console.warn(`Icon "${icon}" not found in registry`);
  return null;
}
function getFallbackIcon() {
  return {
    render() {
      return h$1("svg", { viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [
        h$1("circle", { cx: "12", cy: "12", r: "10" }),
        h$1("path", { d: "M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" }),
        h$1("line", { x1: "12", y1: "17", x2: "12.01", y2: "17" })
      ]);
    }
  };
}
const _sfc_main$13 = /* @__PURE__ */ defineComponent({
  __name: "Icon",
  __ssrInlineRender: true,
  props: {
    icon: {},
    size: { default: void 0 },
    filled: { type: Boolean, default: false },
    class: { default: "" },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const iconComponent = computed(() => {
      const resolved = resolveIcon(props.icon);
      return resolved || getFallbackIcon();
    });
    const sizeStyles = computed(() => {
      if (!props.size) return {};
      const size = typeof props.size === "number" ? `${props.size}px` : props.size;
      return {
        width: size,
        height: size
      };
    });
    const rootStyleObject = computed(() => {
      var _a;
      const style = (_a = props.ui) == null ? void 0 : _a.rootStyle;
      if (!style) return {};
      if (typeof style === "string") return {};
      return style;
    });
    return (_ctx, _push, _parent, _attrs) => {
      ssrRenderVNode(_push, createVNode(resolveDynamicComponent(iconComponent.value), mergeProps({
        fill: __props.filled ? "currentColor" : "none",
        stroke: __props.filled ? "none" : "currentColor",
        "stroke-width": __props.filled ? 0 : void 0,
        class: unref(cn)("icon-component", props.class, __props.ui.root),
        style: { ...sizeStyles.value, ...rootStyleObject.value }
      }, _attrs), null), _parent);
    };
  }
});
const _sfc_setup$13 = _sfc_main$13.setup;
_sfc_main$13.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Icon.vue");
  return _sfc_setup$13 ? _sfc_setup$13(props, ctx) : void 0;
};
const _sfc_main$12 = /* @__PURE__ */ defineComponent({
  ...{ inheritAttrs: false },
  __name: "Tooltip",
  __ssrInlineRender: true,
  props: {
    text: { default: void 0 },
    position: { default: "top" },
    delay: { default: 500 },
    disabled: { type: Boolean, default: false },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    let activeHide = null;
    const props = __props;
    const attrs = useAttrs();
    const slots = useSlots();
    const visible = ref(false);
    const ready = ref(false);
    const referenceEl = ref(null);
    const tooltipEl = ref(null);
    const tooltipPos = ref({ top: 0, left: 0 });
    let showTimeout = null;
    let hideTimeout = null;
    const hasContent = computed(() => !!(props.text || slots.content));
    function hideImmediate() {
      if (showTimeout) {
        clearTimeout(showTimeout);
        showTimeout = null;
      }
      if (hideTimeout) {
        clearTimeout(hideTimeout);
        hideTimeout = null;
      }
      visible.value = false;
      ready.value = false;
      if (activeHide === hideImmediate) activeHide = null;
    }
    function show() {
      if (props.disabled || !hasContent.value) return;
      if (activeHide && activeHide !== hideImmediate) activeHide();
      if (hideTimeout) {
        clearTimeout(hideTimeout);
        hideTimeout = null;
      }
      showTimeout = setTimeout(() => {
        visible.value = true;
        activeHide = hideImmediate;
      }, props.delay);
    }
    function hide() {
      if (showTimeout) {
        clearTimeout(showTimeout);
        showTimeout = null;
      }
      hideTimeout = setTimeout(() => {
        visible.value = false;
        ready.value = false;
        if (activeHide === hideImmediate) activeHide = null;
      }, 100);
    }
    function handleFocusIn(e2) {
      var _a, _b;
      try {
        if ((_b = (_a = e2.target) == null ? void 0 : _a.matches) == null ? void 0 : _b.call(_a, ":focus-visible")) show();
      } catch {
      }
    }
    function handleFocusOut() {
      hide();
    }
    onBeforeUnmount(() => {
      hideImmediate();
    });
    async function computePosition() {
      var _a;
      await nextTick();
      const refEl = ((_a = referenceEl.value) == null ? void 0 : _a.$el) ?? referenceEl.value;
      if (!refEl || !tooltipEl.value) return;
      const refRect = refEl.getBoundingClientRect();
      const tipRect = tooltipEl.value.getBoundingClientRect();
      const GAP = 8;
      const vw = window.innerWidth;
      const vh = window.innerHeight;
      let top = 0;
      let left = 0;
      switch (props.position) {
        case "top":
          top = refRect.top - tipRect.height - GAP;
          left = refRect.left + (refRect.width - tipRect.width) / 2;
          break;
        case "bottom":
          top = refRect.bottom + GAP;
          left = refRect.left + (refRect.width - tipRect.width) / 2;
          break;
        case "left":
          top = refRect.top + (refRect.height - tipRect.height) / 2;
          left = refRect.left - tipRect.width - GAP;
          break;
        case "right":
          top = refRect.top + (refRect.height - tipRect.height) / 2;
          left = refRect.right + GAP;
          break;
      }
      left = Math.max(GAP, Math.min(left, vw - tipRect.width - GAP));
      top = Math.max(GAP, Math.min(top, vh - tipRect.height - GAP));
      tooltipPos.value = { top, left };
      requestAnimationFrame(() => {
        ready.value = true;
      });
    }
    watch(visible, (val) => {
      if (val) {
        ready.value = false;
        computePosition();
      }
    });
    watch(() => props.disabled, (val) => {
      if (val) {
        visible.value = false;
        ready.value = false;
      }
    });
    const arrowClasses = computed(() => {
      const map = {
        top: "bottom-[-4px] left-1/2 -translate-x-1/2 rotate-45",
        bottom: "top-[-4px] left-1/2 -translate-x-1/2 rotate-45",
        left: "right-[-4px] top-1/2 -translate-y-1/2 rotate-45",
        right: "left-[-4px] top-1/2 -translate-y-1/2 rotate-45"
      };
      return map[props.position] || map.top;
    });
    function renderTrigger() {
      var _a;
      const defaultSlot = (_a = slots.default) == null ? void 0 : _a.call(slots);
      if (!(defaultSlot == null ? void 0 : defaultSlot[0])) return null;
      return cloneVNode(defaultSlot[0], mergeProps(attrs, {
        ref: referenceEl,
        onMouseenter: show,
        onMouseleave: hide,
        onFocusin: handleFocusIn,
        onFocusout: handleFocusOut,
        onClick: hideImmediate
      }));
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(renderTrigger, null, null, _parent));
      ssrRenderTeleport(_push, (_push2) => {
        if (visible.value && hasContent.value) {
          _push2(`<div role="tooltip" class="${ssrRenderClass(unref(cn)(
            "fixed px-2.5 py-1.5 text-xs font-medium rounded-md shadow-lg whitespace-pre-line max-w-xs",
            "bg-ui-foreground text-ui-background",
            "pointer-events-none",
            __props.ui.content
          ))}" style="${ssrRenderStyle({
            top: `${tooltipPos.value.top}px`,
            left: `${tooltipPos.value.left}px`,
            zIndex: 9999,
            visibility: ready.value ? "visible" : "hidden",
            ...typeof __props.ui.contentStyle === "object" ? __props.ui.contentStyle : {}
          })}">`);
          ssrRenderSlot(_ctx.$slots, "content", { text: __props.text }, () => {
            _push2(`${ssrInterpolate(__props.text)}`);
          }, _push2, _parent);
          _push2(`<div class="${ssrRenderClass(unref(cn)(
            "absolute w-2 h-2 bg-ui-foreground",
            arrowClasses.value,
            __props.ui.arrow
          ))}" style="${ssrRenderStyle(__props.ui.arrowStyle)}"></div></div>`);
        } else {
          _push2(`<!---->`);
        }
      }, "body", false, _parent);
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup$12 = _sfc_main$12.setup;
_sfc_main$12.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Tooltip.vue");
  return _sfc_setup$12 ? _sfc_setup$12(props, ctx) : void 0;
};
const _export_sfc = (sfc, props) => {
  const target = sfc.__vccOpts || sfc;
  for (const [key, val] of props) {
    target[key] = val;
  }
  return target;
};
const _sfc_main$11 = {};
const _sfc_setup$11 = _sfc_main$11.setup;
_sfc_main$11.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/ApplicationLogo.vue");
  return _sfc_setup$11 ? _sfc_setup$11(props, ctx) : void 0;
};
const _sfc_main$10 = /* @__PURE__ */ defineComponent({
  __name: "Button",
  __ssrInlineRender: true,
  props: {
    type: { default: "button" },
    variant: { default: "primary" },
    size: { default: "md" },
    disabled: { type: Boolean, default: false },
    loading: { type: Boolean, default: false },
    icon: { default: void 0 },
    iconFilled: { type: Boolean, default: false },
    iconPosition: { default: "left" },
    label: { default: void 0 },
    bgColor: { default: void 0 },
    textColor: { default: void 0 },
    ui: { default: () => ({}) },
    gradient: { default: void 0 },
    tooltip: { default: void 0 },
    tooltipLoading: { default: void 0 },
    tooltipDisabled: { default: void 0 }
  },
  emits: ["click"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    function handleClick(event) {
      emit("click", event);
    }
    const variantClasses = computed(() => {
      if (props.gradient) {
        return `border-transparent bg-gradient-to-r ${props.gradient} text-white hover:opacity-90 shadow-lg`;
      }
      const variants = {
        primary: "border-transparent bg-ui-primary text-ui-primary-foreground hover:bg-ui-primary-hover focus:ring-ui-ring shadow-[0_0_10px_rgb(var(--ui-glow)/0.3)]",
        secondary: "bg-ui-secondary text-ui-secondary-foreground border border-ui-border hover:bg-ui-secondary-hover focus:ring-ui-ring",
        danger: "border-transparent bg-ui-destructive text-ui-destructive-foreground hover:bg-ui-destructive-hover focus:ring-ui-ring active:opacity-90",
        warning: "border-transparent bg-ui-warning text-ui-warning-foreground hover:opacity-90 font-semibold focus:ring-ui-ring",
        ghost: "border-transparent bg-transparent text-ui-muted-foreground hover:text-ui-foreground hover:bg-ui-secondary focus:ring-ui-ring",
        link: "border-transparent bg-transparent text-ui-primary hover:underline focus:ring-ui-ring p-0 h-auto"
      };
      return variants[props.variant] || variants.primary;
    });
    const sizeClasses = computed(() => {
      const sizes = {
        sm: "px-2.5 py-1.5 text-xs",
        md: "px-4 py-2 text-sm",
        lg: "px-6 py-3 text-base"
      };
      return sizes[props.size] || sizes.md;
    });
    const activeTooltipText = computed(() => {
      if (props.disabled && props.tooltipDisabled) return props.tooltipDisabled;
      if (props.loading && props.tooltipLoading) return props.tooltipLoading;
      return props.tooltip;
    });
    const hasTooltip = computed(() => !!activeTooltipText.value);
    const slots = useSlots();
    const iconMarginClass = computed(() => {
      if (!props.label && !slots.default) return "";
      return props.iconPosition === "right" ? "ml-2" : "mr-2";
    });
    const inlineColorStyle = computed(() => ({
      ...props.bgColor ? { backgroundColor: props.bgColor } : {},
      ...props.textColor ? { color: props.textColor } : {}
    }));
    const mergedRootStyle = computed(() => {
      var _a;
      const uiStyle = (_a = props.ui) == null ? void 0 : _a.rootStyle;
      if (!uiStyle) return inlineColorStyle.value;
      if (typeof uiStyle === "string") {
        return inlineColorStyle.value;
      }
      return { ...inlineColorStyle.value, ...uiStyle };
    });
    const buttonClasses = computed(() => {
      var _a;
      return cn(
        "inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-ui-background disabled:opacity-50 disabled:pointer-events-none transition-all duration-200 ease-in-out cursor-pointer",
        variantClasses.value,
        sizeClasses.value,
        props.loading ? "relative overflow-hidden" : "",
        (_a = props.ui) == null ? void 0 : _a.root
      );
    });
    return (_ctx, _push, _parent, _attrs) => {
      if (hasTooltip.value) {
        _push(ssrRenderComponent(_sfc_main$12, mergeProps({
          text: activeTooltipText.value,
          ui: __props.ui.tooltip,
          disabled: false
        }, _attrs), {
          default: withCtx((_2, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<button${ssrRenderAttr("type", __props.type)}${ssrIncludeBooleanAttr(__props.disabled || __props.loading) ? " disabled" : ""}${ssrRenderAttr("aria-busy", __props.loading || void 0)} class="${ssrRenderClass(buttonClasses.value)}" style="${ssrRenderStyle(mergedRootStyle.value)}" data-v-3eb3bdb4${_scopeId}>`);
              if (__props.icon && __props.iconPosition === "left") {
                _push2(ssrRenderComponent(_sfc_main$13, {
                  icon: __props.icon,
                  filled: __props.iconFilled,
                  ui: { root: unref(cn)("h-4 w-4", iconMarginClass.value, __props.ui.icon), rootStyle: __props.ui.iconStyle }
                }, null, _parent2, _scopeId));
              } else {
                _push2(`<!---->`);
              }
              _push2(`<span class="${ssrRenderClass(unref(cn)(
                "inline-flex items-center",
                __props.loading ? "opacity-60 transition-opacity duration-200" : "",
                __props.ui.content
              ))}" style="${ssrRenderStyle(__props.ui.contentStyle)}" data-v-3eb3bdb4${_scopeId}>${ssrInterpolate(__props.label)}`);
              ssrRenderSlot(_ctx.$slots, "default", {}, null, _push2, _parent2, _scopeId);
              _push2(`</span>`);
              if (__props.icon && __props.iconPosition === "right") {
                _push2(ssrRenderComponent(_sfc_main$13, {
                  icon: __props.icon,
                  filled: __props.iconFilled,
                  ui: { root: unref(cn)("h-4 w-4", iconMarginClass.value, __props.ui.icon), rootStyle: __props.ui.iconStyle }
                }, null, _parent2, _scopeId));
              } else {
                _push2(`<!---->`);
              }
              if (__props.loading) {
                _push2(`<div class="absolute inset-x-0 bottom-0 h-0.5 overflow-hidden" data-v-3eb3bdb4${_scopeId}><div class="${ssrRenderClass(unref(cn)("ui-loading-bar absolute top-0 h-full w-2/5 rounded-full bg-current opacity-70", __props.ui.loadingBar))}" data-v-3eb3bdb4${_scopeId}></div></div>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</button>`);
            } else {
              return [
                createVNode("button", {
                  type: __props.type,
                  disabled: __props.disabled || __props.loading,
                  "aria-busy": __props.loading || void 0,
                  class: buttonClasses.value,
                  style: mergedRootStyle.value,
                  onClick: handleClick
                }, [
                  __props.icon && __props.iconPosition === "left" ? (openBlock(), createBlock(_sfc_main$13, {
                    key: 0,
                    icon: __props.icon,
                    filled: __props.iconFilled,
                    ui: { root: unref(cn)("h-4 w-4", iconMarginClass.value, __props.ui.icon), rootStyle: __props.ui.iconStyle }
                  }, null, 8, ["icon", "filled", "ui"])) : createCommentVNode("", true),
                  createVNode("span", {
                    class: unref(cn)(
                      "inline-flex items-center",
                      __props.loading ? "opacity-60 transition-opacity duration-200" : "",
                      __props.ui.content
                    ),
                    style: __props.ui.contentStyle
                  }, [
                    createTextVNode(toDisplayString(__props.label), 1),
                    renderSlot(_ctx.$slots, "default", {}, void 0, true)
                  ], 6),
                  __props.icon && __props.iconPosition === "right" ? (openBlock(), createBlock(_sfc_main$13, {
                    key: 1,
                    icon: __props.icon,
                    filled: __props.iconFilled,
                    ui: { root: unref(cn)("h-4 w-4", iconMarginClass.value, __props.ui.icon), rootStyle: __props.ui.iconStyle }
                  }, null, 8, ["icon", "filled", "ui"])) : createCommentVNode("", true),
                  __props.loading ? (openBlock(), createBlock("div", {
                    key: 2,
                    class: "absolute inset-x-0 bottom-0 h-0.5 overflow-hidden"
                  }, [
                    createVNode("div", {
                      class: unref(cn)("ui-loading-bar absolute top-0 h-full w-2/5 rounded-full bg-current opacity-70", __props.ui.loadingBar)
                    }, null, 2)
                  ])) : createCommentVNode("", true)
                ], 14, ["type", "disabled", "aria-busy"])
              ];
            }
          }),
          content: withCtx(({ text: tooltipText }, _push2, _parent2, _scopeId) => {
            if (_push2) {
              ssrRenderSlot(_ctx.$slots, "tooltip", {
                text: tooltipText,
                loading: __props.loading,
                disabled: __props.disabled
              }, () => {
                _push2(`${ssrInterpolate(tooltipText)}`);
              }, _push2, _parent2, _scopeId);
            } else {
              return [
                renderSlot(_ctx.$slots, "tooltip", {
                  text: tooltipText,
                  loading: __props.loading,
                  disabled: __props.disabled
                }, () => [
                  createTextVNode(toDisplayString(tooltipText), 1)
                ], true)
              ];
            }
          }),
          _: 3
        }, _parent));
      } else {
        _push(`<button${ssrRenderAttrs(mergeProps({
          type: __props.type,
          disabled: __props.disabled || __props.loading,
          "aria-busy": __props.loading || void 0,
          class: buttonClasses.value,
          style: mergedRootStyle.value
        }, _attrs))} data-v-3eb3bdb4>`);
        if (__props.icon && __props.iconPosition === "left") {
          _push(ssrRenderComponent(_sfc_main$13, {
            icon: __props.icon,
            filled: __props.iconFilled,
            ui: { root: unref(cn)("h-4 w-4", iconMarginClass.value, __props.ui.icon), rootStyle: __props.ui.iconStyle }
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`<span class="${ssrRenderClass(unref(cn)(
          "inline-flex items-center",
          __props.loading ? "opacity-60 transition-opacity duration-200" : "",
          __props.ui.content
        ))}" style="${ssrRenderStyle(__props.ui.contentStyle)}" data-v-3eb3bdb4>${ssrInterpolate(__props.label)}`);
        ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
        _push(`</span>`);
        if (__props.icon && __props.iconPosition === "right") {
          _push(ssrRenderComponent(_sfc_main$13, {
            icon: __props.icon,
            filled: __props.iconFilled,
            ui: { root: unref(cn)("h-4 w-4", iconMarginClass.value, __props.ui.icon), rootStyle: __props.ui.iconStyle }
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        if (__props.loading) {
          _push(`<div class="absolute inset-x-0 bottom-0 h-0.5 overflow-hidden" data-v-3eb3bdb4><div class="${ssrRenderClass(unref(cn)("ui-loading-bar absolute top-0 h-full w-2/5 rounded-full bg-current opacity-70", __props.ui.loadingBar))}" data-v-3eb3bdb4></div></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</button>`);
      }
    };
  }
});
const _sfc_setup$10 = _sfc_main$10.setup;
_sfc_main$10.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Button.vue");
  return _sfc_setup$10 ? _sfc_setup$10(props, ctx) : void 0;
};
const Button = /* @__PURE__ */ _export_sfc(_sfc_main$10, [["__scopeId", "data-v-3eb3bdb4"]]);
const _sfc_main$$ = /* @__PURE__ */ defineComponent({
  ...{ inheritAttrs: false },
  __name: "Checkbox",
  __ssrInlineRender: true,
  props: {
    checked: { type: [Boolean, Array] },
    value: { default: null },
    ui: { default: () => ({}) },
    label: {},
    description: {},
    disabled: { type: Boolean, default: false },
    clickable: { type: Boolean, default: false }
  },
  emits: ["update:checked"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const proxyChecked = computed({
      get() {
        return props.checked;
      },
      set(val) {
        emit("update:checked", val);
      }
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)(
          "flex",
          __props.description ? "items-start" : "items-center",
          __props.clickable && !__props.disabled ? "cursor-pointer" : "",
          __props.ui.root
        ),
        style: __props.ui.rootStyle
      }, _attrs))}><div class="${ssrRenderClass(unref(cn)("flex flex-none items-center", __props.description && __props.label ? "h-5" : ""))}"><input${ssrRenderAttr("id", _ctx.$attrs.id)} type="checkbox"${ssrRenderAttr("value", __props.value)}${ssrIncludeBooleanAttr(Array.isArray(proxyChecked.value) ? ssrLooseContain(proxyChecked.value, __props.value) : proxyChecked.value) ? " checked" : ""}${ssrIncludeBooleanAttr(__props.disabled) ? " disabled" : ""} class="${ssrRenderClass(unref(cn)(
        "rounded border-ui-border text-ui-primary shadow-sm focus:ring-ui-ring disabled:opacity-50 disabled:cursor-not-allowed dark:bg-ui-input dark:border-ui-border dark:focus:ring-offset-ui-background",
        __props.ui.checkbox,
        __props.ui.input,
        _ctx.$attrs.class
      ))}" style="${ssrRenderStyle(__props.ui.inputStyle)}"></div>`);
      if (__props.label || _ctx.$slots.default || __props.description) {
        _push(`<div class="ml-3 text-sm">`);
        if (__props.label || _ctx.$slots.default) {
          _push(`<label${ssrRenderAttr("for", _ctx.$attrs.id)} class="${ssrRenderClass(unref(cn)("font-medium text-ui-foreground", __props.clickable && !__props.disabled ? "cursor-pointer" : "", __props.ui.label))}" style="${ssrRenderStyle(__props.ui.labelStyle)}">`);
          ssrRenderSlot(_ctx.$slots, "default", {}, () => {
            _push(`${ssrInterpolate(__props.label)}`);
          }, _push, _parent);
          _push(`</label>`);
        } else {
          _push(`<!---->`);
        }
        if (__props.description) {
          _push(`<p class="${ssrRenderClass(unref(cn)("text-ui-muted-foreground", __props.ui.description))}" style="${ssrRenderStyle(__props.ui.descriptionStyle)}">${ssrInterpolate(__props.description)}</p>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$$ = _sfc_main$$.setup;
_sfc_main$$.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Checkbox.vue");
  return _sfc_setup$$ ? _sfc_setup$$(props, ctx) : void 0;
};
const _sfc_main$_ = /* @__PURE__ */ defineComponent({
  __name: "DropdownItem",
  __ssrInlineRender: true,
  props: {
    label: {},
    href: {},
    icon: {},
    image: {},
    subtitle: {},
    active: { type: Boolean },
    danger: { type: Boolean },
    disabled: { type: Boolean },
    onClick: { type: Function },
    as: {},
    ui: {}
  },
  setup(__props) {
    const props = __props;
    const componentType = computed(() => {
      if (props.as) return props.as;
      return props.href ? Link : "button";
    });
    const itemClasses = computed(() => {
      var _a;
      return cn(
        "group flex items-center w-full px-4 py-2 text-sm text-start transition-colors duration-150 ease-in-out font-medium",
        "focus:outline-none focus:bg-ui-muted/50",
        props.danger ? "text-ui-error hover:bg-ui-error/10" : "text-ui-foreground hover:bg-ui-muted/50",
        props.active ? "bg-ui-muted/50 text-ui-primary" : "",
        props.disabled ? "opacity-50 cursor-not-allowed pointer-events-none" : "",
        (_a = props.ui) == null ? void 0 : _a.item
        // User override
      );
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a;
      ssrRenderVNode(_push, createVNode(resolveDynamicComponent(componentType.value), mergeProps({
        href: __props.href,
        onClick: __props.onClick,
        class: itemClasses.value,
        style: (_a = props.ui) == null ? void 0 : _a.itemStyle,
        disabled: __props.disabled
      }, _attrs), {
        default: withCtx((_2, _push2, _parent2, _scopeId) => {
          var _a2, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k, _l;
          if (_push2) {
            if (__props.icon || __props.image) {
              _push2(`<div class="shrink-0 me-3 flex items-center justify-center w-5 h-5"${_scopeId}>`);
              if (__props.image) {
                _push2(`<img${ssrRenderAttr("src", __props.image)} class="${ssrRenderClass([(_a2 = props.ui) == null ? void 0 : _a2.itemImage, "w-5 h-5 rounded-full object-cover ring-1 ring-ui-border"])}" style="${ssrRenderStyle((_b = props.ui) == null ? void 0 : _b.itemImageStyle)}" alt=""${_scopeId}>`);
              } else if (__props.icon) {
                _push2(ssrRenderComponent(_sfc_main$13, {
                  icon: __props.icon,
                  class: unref(cn)(
                    "w-5 h-5 opacity-50 group-hover:opacity-100 transition-opacity",
                    __props.danger ? "text-ui-error" : "text-ui-muted-foreground"
                  )
                }, null, _parent2, _scopeId));
              } else {
                _push2(`<!---->`);
              }
              _push2(`</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<div class="flex flex-col min-w-0"${_scopeId}><span class="${ssrRenderClass([(_c = props.ui) == null ? void 0 : _c.itemTitle, "truncate leading-tight"])}" style="${ssrRenderStyle((_d = props.ui) == null ? void 0 : _d.itemTitleStyle)}"${_scopeId}>${ssrInterpolate(__props.label)}</span>`);
            if (__props.subtitle) {
              _push2(`<span class="${ssrRenderClass([(_e = props.ui) == null ? void 0 : _e.itemSubtitle, "text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate font-normal"])}" style="${ssrRenderStyle((_f = props.ui) == null ? void 0 : _f.itemSubtitleStyle)}"${_scopeId}>${ssrInterpolate(__props.subtitle)}</span>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div>`);
          } else {
            return [
              __props.icon || __props.image ? (openBlock(), createBlock("div", {
                key: 0,
                class: "shrink-0 me-3 flex items-center justify-center w-5 h-5"
              }, [
                __props.image ? (openBlock(), createBlock("img", {
                  key: 0,
                  src: __props.image,
                  class: ["w-5 h-5 rounded-full object-cover ring-1 ring-ui-border", (_g = props.ui) == null ? void 0 : _g.itemImage],
                  style: (_h = props.ui) == null ? void 0 : _h.itemImageStyle,
                  alt: ""
                }, null, 14, ["src"])) : __props.icon ? (openBlock(), createBlock(_sfc_main$13, {
                  key: 1,
                  icon: __props.icon,
                  class: unref(cn)(
                    "w-5 h-5 opacity-50 group-hover:opacity-100 transition-opacity",
                    __props.danger ? "text-ui-error" : "text-ui-muted-foreground"
                  )
                }, null, 8, ["icon", "class"])) : createCommentVNode("", true)
              ])) : createCommentVNode("", true),
              createVNode("div", { class: "flex flex-col min-w-0" }, [
                createVNode("span", {
                  class: ["truncate leading-tight", (_i = props.ui) == null ? void 0 : _i.itemTitle],
                  style: (_j = props.ui) == null ? void 0 : _j.itemTitleStyle
                }, toDisplayString(__props.label), 7),
                __props.subtitle ? (openBlock(), createBlock("span", {
                  key: 0,
                  class: ["text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate font-normal", (_k = props.ui) == null ? void 0 : _k.itemSubtitle],
                  style: (_l = props.ui) == null ? void 0 : _l.itemSubtitleStyle
                }, toDisplayString(__props.subtitle), 7)) : createCommentVNode("", true)
              ])
            ];
          }
        }),
        _: 1
      }), _parent);
    };
  }
});
const _sfc_setup$_ = _sfc_main$_.setup;
_sfc_main$_.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/DropdownItem.vue");
  return _sfc_setup$_ ? _sfc_setup$_(props, ctx) : void 0;
};
const _sfc_main$Z = /* @__PURE__ */ defineComponent({
  __name: "Dropdown",
  __ssrInlineRender: true,
  props: {
    align: {},
    width: {},
    contentClasses: {},
    items: {},
    ui: {}
  },
  setup(__props) {
    const props = __props;
    const open = ref(false);
    const triggerRef = ref(null);
    const menuStyle = ref({});
    const closeOnEscape = (e2) => {
      if (open.value && e2.key === "Escape") {
        open.value = false;
      }
    };
    onMounted(() => document.addEventListener("keydown", closeOnEscape));
    onUnmounted(() => document.removeEventListener("keydown", closeOnEscape));
    function handleScroll() {
      if (open.value) {
        open.value = false;
      }
    }
    onMounted(() => window.addEventListener("scroll", handleScroll, true));
    onUnmounted(() => window.removeEventListener("scroll", handleScroll, true));
    const widthClass = computed(() => {
      var _a;
      const w2 = ((_a = props.width) == null ? void 0 : _a.toString()) || "48";
      if (w2 === "48") return "w-48";
      if (isNaN(Number(w2))) return w2;
      return `w-${w2}`;
    });
    function updatePosition() {
      if (!triggerRef.value) return;
      const rect = triggerRef.value.getBoundingClientRect();
      const spaceBelow = window.innerHeight - rect.bottom;
      const openUpward = spaceBelow < 200;
      const style = {};
      if (openUpward) {
        style.bottom = `${window.innerHeight - rect.top + 4}px`;
        style.transformOrigin = props.align === "right" ? "bottom right" : "bottom left";
      } else {
        style.top = `${rect.bottom + 4}px`;
        style.transformOrigin = props.align === "right" ? "top right" : "top left";
      }
      if (props.align === "right") {
        style.right = `${window.innerWidth - rect.right}px`;
      } else {
        style.left = `${rect.left}px`;
      }
      menuStyle.value = style;
    }
    watch(open, async (val) => {
      if (val) {
        await nextTick();
        updatePosition();
      }
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        ref_key: "triggerRef",
        ref: triggerRef,
        class: "relative inline-flex"
      }, _attrs))}><div>`);
      ssrRenderSlot(_ctx.$slots, "trigger", {}, null, _push, _parent);
      _push(`</div>`);
      ssrRenderTeleport(_push, (_push2) => {
        var _a, _b;
        _push2(`<div class="fixed inset-0 z-40" style="${ssrRenderStyle(open.value ? null : { display: "none" })}"></div><div class="${ssrRenderClass(unref(cn)("fixed z-50 rounded-md shadow-lg ring-1 ring-ui-border", widthClass.value))}" style="${ssrRenderStyle([
          menuStyle.value,
          open.value ? null : { display: "none" }
        ])}"><div class="${ssrRenderClass(unref(cn)(
          "rounded-md ring-1 ring-ui-border overflow-hidden",
          props.contentClasses || "py-1 bg-ui-card text-ui-foreground",
          (_a = props.ui) == null ? void 0 : _a.content
        ))}" style="${ssrRenderStyle((_b = props.ui) == null ? void 0 : _b.contentStyle)}">`);
        ssrRenderSlot(_ctx.$slots, "content", {}, () => {
          if (__props.items && __props.items.length > 0) {
            _push2(`<!--[-->`);
            ssrRenderList(__props.items, (item, index) => {
              var _a2, _b2;
              _push2(`<!--[-->`);
              if (item.type === "divider") {
                _push2(`<div class="${ssrRenderClass([(_a2 = props.ui) == null ? void 0 : _a2.divider, "border-t border-ui-border my-1"])}" style="${ssrRenderStyle((_b2 = props.ui) == null ? void 0 : _b2.dividerStyle)}"></div>`);
              } else {
                _push2(ssrRenderComponent(_sfc_main$_, mergeProps({ ref_for: true }, item, {
                  ui: props.ui
                }), null, _parent));
              }
              _push2(`<!--]-->`);
            });
            _push2(`<!--]-->`);
          } else {
            _push2(`<!---->`);
          }
        }, _push2, _parent);
        _push2(`</div></div>`);
      }, "body", false, _parent);
      _push(`</div>`);
    };
  }
});
const _sfc_setup$Z = _sfc_main$Z.setup;
_sfc_main$Z.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Dropdown.vue");
  return _sfc_setup$Z ? _sfc_setup$Z(props, ctx) : void 0;
};
const _sfc_main$Y = /* @__PURE__ */ defineComponent({
  __name: "DropdownLink",
  __ssrInlineRender: true,
  props: {
    href: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(Link), mergeProps({
        href: __props.href,
        class: unref(cn)(
          "block w-full px-4 py-2 text-start text-sm leading-5 text-ui-foreground transition duration-150 ease-in-out hover:bg-ui-secondary focus:bg-ui-secondary focus:outline-none",
          _ctx.$attrs.class
        )
      }, _attrs), {
        default: withCtx((_2, _push2, _parent2, _scopeId) => {
          if (_push2) {
            ssrRenderSlot(_ctx.$slots, "default", {}, null, _push2, _parent2, _scopeId);
          } else {
            return [
              renderSlot(_ctx.$slots, "default")
            ];
          }
        }),
        _: 3
      }, _parent));
    };
  }
});
const _sfc_setup$Y = _sfc_main$Y.setup;
_sfc_main$Y.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/DropdownLink.vue");
  return _sfc_setup$Y ? _sfc_setup$Y(props, ctx) : void 0;
};
const _sfc_main$X = /* @__PURE__ */ defineComponent({
  __name: "InputError",
  __ssrInlineRender: true,
  props: {
    message: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<p${ssrRenderAttrs(mergeProps({ class: "text-sm text-ui-destructive" }, _attrs, {
        style: __props.message ? null : { display: "none" }
      }))}>${ssrInterpolate(__props.message)}</p>`);
    };
  }
});
const _sfc_setup$X = _sfc_main$X.setup;
_sfc_main$X.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/InputError.vue");
  return _sfc_setup$X ? _sfc_setup$X(props, ctx) : void 0;
};
function parseErrors(input, fieldKey) {
  if (input === null || input === void 0) {
    return [];
  }
  if (typeof input === "string") {
    return input.trim() ? [{ message: input }] : [];
  }
  if (Array.isArray(input)) {
    return input.filter((msg) => typeof msg === "string" && msg.trim() !== "").map((message) => ({ message }));
  }
  if (typeof input === "object") {
    if (fieldKey && fieldKey in input) {
      const fieldErrors = input[fieldKey];
      if (typeof fieldErrors === "string") {
        return fieldErrors.trim() ? [{ key: fieldKey, message: fieldErrors }] : [];
      }
      if (Array.isArray(fieldErrors)) {
        return fieldErrors.filter((msg) => typeof msg === "string" && msg.trim() !== "").map((message) => ({ key: fieldKey, message }));
      }
      return [];
    }
    const result = [];
    for (const [key, value] of Object.entries(input)) {
      if (typeof value === "string" && value.trim()) {
        result.push({ key, message: value });
      } else if (Array.isArray(value)) {
        for (const msg of value) {
          if (typeof msg === "string" && msg.trim()) {
            result.push({ key, message: msg });
          }
        }
      }
    }
    return result;
  }
  return [];
}
const defaultWrapperClass = "space-y-1";
const defaultItemClass = "text-sm text-red-600 dark:text-red-400 flex items-start gap-1.5";
const defaultIconClass = "w-3.5 h-3.5 mt-0.5 flex-shrink-0";
const defaultMoreClass = "text-xs text-red-500/70 dark:text-red-400/70 italic";
const _sfc_main$W = /* @__PURE__ */ defineComponent({
  __name: "InputErrors",
  __ssrInlineRender: true,
  props: {
    error: { default: void 0 },
    fieldKey: { default: void 0 },
    maxVisible: { default: 5 },
    showKeys: { type: Boolean, default: false },
    mode: { default: "list" },
    showIcon: { type: Boolean, default: false },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const normalizedErrors = computed(
      () => parseErrors(props.error, props.fieldKey)
    );
    const visibleErrors = computed(
      () => normalizedErrors.value.slice(0, props.maxVisible)
    );
    const hiddenCount = computed(
      () => Math.max(0, normalizedErrors.value.length - props.maxVisible)
    );
    const inlineErrorString = computed(
      () => normalizedErrors.value.map((e2) => e2.message).join(", ")
    );
    return (_ctx, _push, _parent, _attrs) => {
      if (normalizedErrors.value.length > 0) {
        _push(`<div${ssrRenderAttrs(mergeProps({
          class: unref(cn)(defaultWrapperClass, __props.ui.wrapper),
          style: __props.ui.wrapperStyle,
          role: "alert",
          "aria-live": "polite"
        }, _attrs))}>`);
        if (__props.mode === "list") {
          _push(`<!--[--><!--[-->`);
          ssrRenderList(visibleErrors.value, (err, idx) => {
            _push(`<p class="${ssrRenderClass(unref(cn)(defaultItemClass, __props.ui.item))}" style="${ssrRenderStyle(__props.ui.itemStyle)}">`);
            if (__props.showIcon) {
              _push(ssrRenderComponent(unref(AlertCircle), {
                class: unref(cn)(defaultIconClass, __props.ui.icon),
                style: __props.ui.iconStyle
              }, null, _parent));
            } else {
              _push(`<!---->`);
            }
            _push(`<span>`);
            if (__props.showKeys && err.key) {
              _push(`<span class="font-medium">${ssrInterpolate(err.key)}: </span>`);
            } else {
              _push(`<!---->`);
            }
            _push(` ${ssrInterpolate(err.message)}</span></p>`);
          });
          _push(`<!--]-->`);
          if (hiddenCount.value > 0) {
            _push(`<p class="${ssrRenderClass(unref(cn)(defaultMoreClass, __props.ui.more))}" style="${ssrRenderStyle(__props.ui.moreStyle)}"> +${ssrInterpolate(hiddenCount.value)} more error${ssrInterpolate(hiddenCount.value > 1 ? "s" : "")}</p>`);
          } else {
            _push(`<!---->`);
          }
          _push(`<!--]-->`);
        } else {
          _push(`<p class="${ssrRenderClass(unref(cn)(defaultItemClass, __props.ui.item))}" style="${ssrRenderStyle(__props.ui.itemStyle)}">`);
          if (__props.showIcon) {
            _push(ssrRenderComponent(unref(AlertCircle), {
              class: unref(cn)(defaultIconClass, __props.ui.icon),
              style: __props.ui.iconStyle
            }, null, _parent));
          } else {
            _push(`<!---->`);
          }
          _push(`<span>${ssrInterpolate(inlineErrorString.value)}</span></p>`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
});
const _sfc_setup$W = _sfc_main$W.setup;
_sfc_main$W.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/InputErrors.vue");
  return _sfc_setup$W ? _sfc_setup$W(props, ctx) : void 0;
};
const __default__ = {
  inheritAttrs: false
};
const _sfc_main$V = /* @__PURE__ */ defineComponent({
  ...__default__,
  __name: "InputLabel",
  __ssrInlineRender: true,
  props: {
    value: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<label${ssrRenderAttrs(mergeProps({
        class: unref(cn)("block text-sm font-medium text-ui-foreground mb-1", _ctx.$attrs.class),
        style: _ctx.$attrs.style
      }, _attrs))}>`);
      if (__props.value) {
        _push(`<span>${ssrInterpolate(__props.value)}</span>`);
      } else {
        _push(`<span>`);
        ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
        _push(`</span>`);
      }
      _push(`</label>`);
    };
  }
});
const _sfc_setup$V = _sfc_main$V.setup;
_sfc_main$V.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/InputLabel.vue");
  return _sfc_setup$V ? _sfc_setup$V(props, ctx) : void 0;
};
const ANIMATION_DURATION = 200;
const _sfc_main$U = /* @__PURE__ */ defineComponent({
  __name: "Modal",
  __ssrInlineRender: true,
  props: {
    show: { type: Boolean },
    maxWidth: { default: "2xl" },
    title: {},
    closeable: { type: Boolean, default: true },
    closeOnBackdrop: { type: Boolean, default: true },
    panelWidth: {},
    panelHeight: {},
    panelMinWidth: {},
    panelMaxWidth: {},
    panelMinHeight: {},
    panelMaxHeight: {},
    ui: { default: () => ({}) }
  },
  emits: ["close"],
  setup(__props, { expose: __expose, emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const dialogRef = ref(null);
    const titleId = useId();
    const isAnimatingIn = ref(false);
    const isClosing = ref(false);
    watch(() => props.show, async (val) => {
      var _a;
      if (val) {
        isClosing.value = false;
        (_a = dialogRef.value) == null ? void 0 : _a.showModal();
        await nextTick();
        isAnimatingIn.value = true;
      } else {
        isAnimatingIn.value = false;
        isClosing.value = true;
        setTimeout(() => {
          var _a2;
          (_a2 = dialogRef.value) == null ? void 0 : _a2.close();
          isClosing.value = false;
        }, ANIMATION_DURATION);
      }
    });
    onMounted(async () => {
      var _a;
      if (props.show) {
        (_a = dialogRef.value) == null ? void 0 : _a.showModal();
        await nextTick();
        isAnimatingIn.value = true;
      }
    });
    const close = () => {
      if (props.closeable) {
        emit("close");
      }
    };
    const panelDimensionStyle = computed(() => ({
      ...props.panelWidth ? { width: props.panelWidth } : {},
      ...props.panelHeight ? { height: props.panelHeight } : {},
      ...props.panelMinWidth ? { minWidth: props.panelMinWidth } : {},
      ...props.panelMaxWidth ? { maxWidth: props.panelMaxWidth } : {},
      ...props.panelMinHeight ? { minHeight: props.panelMinHeight } : {},
      ...props.panelMaxHeight ? { maxHeight: props.panelMaxHeight } : {}
    }));
    const maxWidthClasses = {
      sm: "max-w-sm",
      md: "max-w-md",
      lg: "max-w-lg",
      xl: "max-w-xl",
      "2xl": "max-w-2xl",
      "3xl": "max-w-3xl",
      full: "max-w-full m-4"
    };
    __expose({ close });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<dialog${ssrRenderAttrs(mergeProps({
        ref_key: "dialogRef",
        ref: dialogRef,
        class: unref(cn)(
          "modal-dialog",
          isAnimatingIn.value && "is-open",
          isClosing.value && "is-closing",
          __props.ui.backdrop
        ),
        style: __props.ui.backdropStyle,
        "aria-labelledby": __props.title ? unref(titleId) : void 0
      }, _attrs))} data-v-a205f00e><div role="document" class="${ssrRenderClass(unref(cn)(
        "modal-panel",
        maxWidthClasses[__props.maxWidth],
        __props.ui.panel
      ))}" style="${ssrRenderStyle([__props.ui.panelStyle, panelDimensionStyle.value])}" data-v-a205f00e>`);
      if (__props.title) {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "modal-header",
          __props.ui.header
        ))}" style="${ssrRenderStyle(__props.ui.headerStyle)}" data-v-a205f00e><h3${ssrRenderAttr("id", unref(titleId))} class="${ssrRenderClass(unref(cn)(
          "text-lg font-semibold text-ui-foreground",
          __props.ui.title
        ))}" style="${ssrRenderStyle(__props.ui.titleStyle)}" data-v-a205f00e>${ssrInterpolate(__props.title)}</h3>`);
        if (__props.closeable) {
          _push(`<button type="button" class="${ssrRenderClass(unref(cn)(
            "modal-close-button",
            __props.ui.closeButton
          ))}" style="${ssrRenderStyle(__props.ui.closeButtonStyle)}" aria-label="Close dialog" data-v-a205f00e>`);
          _push(ssrRenderComponent(unref(X), { class: "h-5 w-5" }, null, _parent));
          _push(`</button>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="${ssrRenderClass(unref(cn)(
        "modal-body",
        __props.title ? "p-6" : "",
        __props.ui.body
      ))}" style="${ssrRenderStyle(__props.ui.bodyStyle)}" data-v-a205f00e>`);
      ssrRenderSlot(_ctx.$slots, "default", { close }, null, _push, _parent);
      _push(`</div>`);
      if (_ctx.$slots.footer) {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "modal-footer",
          __props.ui.footer
        ))}" style="${ssrRenderStyle(__props.ui.footerStyle)}" data-v-a205f00e>`);
        ssrRenderSlot(_ctx.$slots, "footer", { close }, null, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></dialog>`);
    };
  }
});
const _sfc_setup$U = _sfc_main$U.setup;
_sfc_main$U.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Modal.vue");
  return _sfc_setup$U ? _sfc_setup$U(props, ctx) : void 0;
};
const Modal = /* @__PURE__ */ _export_sfc(_sfc_main$U, [["__scopeId", "data-v-a205f00e"]]);
const _sfc_main$T = /* @__PURE__ */ defineComponent({
  __name: "WarningModal",
  __ssrInlineRender: true,
  props: {
    show: { type: Boolean },
    maxWidth: { default: "md" },
    title: {},
    description: {},
    confirmLabel: { default: "Proceed" },
    cancelLabel: { default: "Cancel" },
    loading: { type: Boolean, default: false },
    closeable: { type: Boolean, default: true },
    closeOnBackdrop: { type: Boolean, default: true },
    panelWidth: {},
    panelHeight: {},
    panelMinWidth: {},
    panelMaxWidth: {},
    panelMinHeight: {},
    panelMaxHeight: {},
    ui: { default: () => ({}) }
  },
  emits: ["close", "confirm"],
  setup(__props, { expose: __expose, emit: __emit }) {
    const emit = __emit;
    const modalRef = ref(null);
    const close = () => {
      var _a;
      (_a = modalRef.value) == null ? void 0 : _a.close();
    };
    __expose({ close });
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(Modal, mergeProps({
        ref_key: "modalRef",
        ref: modalRef,
        show: __props.show,
        "max-width": __props.maxWidth,
        closeable: __props.closeable,
        "close-on-backdrop": __props.closeOnBackdrop,
        "panel-width": __props.panelWidth,
        "panel-height": __props.panelHeight,
        "panel-min-width": __props.panelMinWidth,
        "panel-max-width": __props.panelMaxWidth,
        "panel-min-height": __props.panelMinHeight,
        "panel-max-height": __props.panelMaxHeight,
        ui: __props.ui,
        onClose: ($event) => emit("close")
      }, _attrs), {
        default: withCtx(({ close: closeFn }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (__props.title) {
              _push2(`<div class="${ssrRenderClass(unref(cn)(
                "variant-modal-header border-b border-amber-500/30",
                __props.ui.header
              ))}" style="${ssrRenderStyle(__props.ui.headerStyle)}" data-v-fe4c3a59${_scopeId}><div class="flex items-center gap-2.5" data-v-fe4c3a59${_scopeId}><div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-amber-500/15" data-v-fe4c3a59${_scopeId}><svg class="w-4.5 h-4.5 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-v-fe4c3a59${_scopeId}><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" data-v-fe4c3a59${_scopeId}></path><path d="M12 9v4" data-v-fe4c3a59${_scopeId}></path><path d="M12 17h.01" data-v-fe4c3a59${_scopeId}></path></svg></div><h3 class="${ssrRenderClass(unref(cn)(
                "text-lg font-semibold text-ui-foreground",
                __props.ui.title
              ))}" style="${ssrRenderStyle(__props.ui.titleStyle)}" data-v-fe4c3a59${_scopeId}>${ssrInterpolate(__props.title)}</h3></div>`);
              if (__props.closeable) {
                _push2(`<button class="${ssrRenderClass(unref(cn)(
                  "variant-modal-close-button",
                  __props.ui.closeButton
                ))}" style="${ssrRenderStyle(__props.ui.closeButtonStyle)}" aria-label="Close dialog" data-v-fe4c3a59${_scopeId}>`);
                _push2(ssrRenderComponent(unref(X), { class: "h-5 w-5" }, null, _parent2, _scopeId));
                _push2(`</button>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<div class="${ssrRenderClass(unref(cn)("px-6 pt-6 pb-4", __props.ui.body))}" style="${ssrRenderStyle(__props.ui.bodyStyle)}" data-v-fe4c3a59${_scopeId}>`);
            ssrRenderSlot(_ctx.$slots, "default", { close: closeFn }, () => {
              if (__props.description) {
                _push2(`<p class="text-sm text-ui-foreground" data-v-fe4c3a59${_scopeId}>${ssrInterpolate(__props.description)}</p>`);
              } else {
                _push2(`<!---->`);
              }
            }, _push2, _parent2, _scopeId);
            _push2(`</div><div class="flex justify-end gap-2 px-6 pb-6" data-v-fe4c3a59${_scopeId}>`);
            ssrRenderSlot(_ctx.$slots, "footer", { close: closeFn }, () => {
              _push2(ssrRenderComponent(Button, {
                variant: "ghost",
                onClick: closeFn
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(__props.cancelLabel)}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(__props.cancelLabel), 1)
                    ];
                  }
                }),
                _: 2
              }, _parent2, _scopeId));
              _push2(ssrRenderComponent(Button, {
                loading: __props.loading,
                onClick: ($event) => emit("confirm")
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(__props.confirmLabel)}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(__props.confirmLabel), 1)
                    ];
                  }
                }),
                _: 2
              }, _parent2, _scopeId));
            }, _push2, _parent2, _scopeId);
            _push2(`</div>`);
          } else {
            return [
              __props.title ? (openBlock(), createBlock("div", {
                key: 0,
                class: unref(cn)(
                  "variant-modal-header border-b border-amber-500/30",
                  __props.ui.header
                ),
                style: __props.ui.headerStyle
              }, [
                createVNode("div", { class: "flex items-center gap-2.5" }, [
                  createVNode("div", { class: "flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-amber-500/15" }, [
                    (openBlock(), createBlock("svg", {
                      class: "w-4.5 h-4.5 text-amber-500",
                      xmlns: "http://www.w3.org/2000/svg",
                      viewBox: "0 0 24 24",
                      fill: "none",
                      stroke: "currentColor",
                      "stroke-width": "2",
                      "stroke-linecap": "round",
                      "stroke-linejoin": "round"
                    }, [
                      createVNode("path", { d: "m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" }),
                      createVNode("path", { d: "M12 9v4" }),
                      createVNode("path", { d: "M12 17h.01" })
                    ]))
                  ]),
                  createVNode("h3", {
                    class: unref(cn)(
                      "text-lg font-semibold text-ui-foreground",
                      __props.ui.title
                    ),
                    style: __props.ui.titleStyle
                  }, toDisplayString(__props.title), 7)
                ]),
                __props.closeable ? (openBlock(), createBlock("button", {
                  key: 0,
                  onClick: closeFn,
                  class: unref(cn)(
                    "variant-modal-close-button",
                    __props.ui.closeButton
                  ),
                  style: __props.ui.closeButtonStyle,
                  "aria-label": "Close dialog"
                }, [
                  createVNode(unref(X), { class: "h-5 w-5" })
                ], 14, ["onClick"])) : createCommentVNode("", true)
              ], 6)) : createCommentVNode("", true),
              createVNode("div", {
                class: unref(cn)("px-6 pt-6 pb-4", __props.ui.body),
                style: __props.ui.bodyStyle
              }, [
                renderSlot(_ctx.$slots, "default", { close: closeFn }, () => [
                  __props.description ? (openBlock(), createBlock("p", {
                    key: 0,
                    class: "text-sm text-ui-foreground"
                  }, toDisplayString(__props.description), 1)) : createCommentVNode("", true)
                ], true)
              ], 6),
              createVNode("div", { class: "flex justify-end gap-2 px-6 pb-6" }, [
                renderSlot(_ctx.$slots, "footer", { close: closeFn }, () => [
                  createVNode(Button, {
                    variant: "ghost",
                    onClick: closeFn
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(__props.cancelLabel), 1)
                    ]),
                    _: 1
                  }, 8, ["onClick"]),
                  createVNode(Button, {
                    loading: __props.loading,
                    onClick: ($event) => emit("confirm")
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(__props.confirmLabel), 1)
                    ]),
                    _: 1
                  }, 8, ["loading", "onClick"])
                ], true)
              ])
            ];
          }
        }),
        _: 3
      }, _parent));
    };
  }
});
const _sfc_setup$T = _sfc_main$T.setup;
_sfc_main$T.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/WarningModal.vue");
  return _sfc_setup$T ? _sfc_setup$T(props, ctx) : void 0;
};
const _sfc_main$S = /* @__PURE__ */ defineComponent({
  __name: "DangerModal",
  __ssrInlineRender: true,
  props: {
    show: { type: Boolean },
    maxWidth: { default: "md" },
    title: {},
    description: {},
    confirmLabel: { default: "Confirm" },
    cancelLabel: { default: "Cancel" },
    loading: { type: Boolean, default: false },
    closeable: { type: Boolean, default: true },
    closeOnBackdrop: { type: Boolean, default: true },
    panelWidth: {},
    panelHeight: {},
    panelMinWidth: {},
    panelMaxWidth: {},
    panelMinHeight: {},
    panelMaxHeight: {},
    ui: { default: () => ({}) }
  },
  emits: ["close", "confirm"],
  setup(__props, { expose: __expose, emit: __emit }) {
    const emit = __emit;
    const modalRef = ref(null);
    const close = () => {
      var _a;
      (_a = modalRef.value) == null ? void 0 : _a.close();
    };
    __expose({ close });
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(Modal, mergeProps({
        ref_key: "modalRef",
        ref: modalRef,
        show: __props.show,
        "max-width": __props.maxWidth,
        closeable: __props.closeable,
        "close-on-backdrop": __props.closeOnBackdrop,
        "panel-width": __props.panelWidth,
        "panel-height": __props.panelHeight,
        "panel-min-width": __props.panelMinWidth,
        "panel-max-width": __props.panelMaxWidth,
        "panel-min-height": __props.panelMinHeight,
        "panel-max-height": __props.panelMaxHeight,
        ui: __props.ui,
        onClose: ($event) => emit("close")
      }, _attrs), {
        default: withCtx(({ close: closeFn }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (__props.title) {
              _push2(`<div class="${ssrRenderClass(unref(cn)(
                "variant-modal-header border-b border-red-500/30",
                __props.ui.header
              ))}" style="${ssrRenderStyle(__props.ui.headerStyle)}" data-v-ead60393${_scopeId}><div class="flex items-center gap-2.5" data-v-ead60393${_scopeId}><div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-red-500/15" data-v-ead60393${_scopeId}><svg class="w-4.5 h-4.5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-v-ead60393${_scopeId}><circle cx="12" cy="12" r="10" data-v-ead60393${_scopeId}></circle><path d="m15 9-6 6" data-v-ead60393${_scopeId}></path><path d="m9 9 6 6" data-v-ead60393${_scopeId}></path></svg></div><h3 class="${ssrRenderClass(unref(cn)(
                "text-lg font-semibold text-ui-foreground",
                __props.ui.title
              ))}" style="${ssrRenderStyle(__props.ui.titleStyle)}" data-v-ead60393${_scopeId}>${ssrInterpolate(__props.title)}</h3></div>`);
              if (__props.closeable) {
                _push2(`<button class="${ssrRenderClass(unref(cn)(
                  "variant-modal-close-button",
                  __props.ui.closeButton
                ))}" style="${ssrRenderStyle(__props.ui.closeButtonStyle)}" aria-label="Close dialog" data-v-ead60393${_scopeId}>`);
                _push2(ssrRenderComponent(unref(X), { class: "h-5 w-5" }, null, _parent2, _scopeId));
                _push2(`</button>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<div class="${ssrRenderClass(unref(cn)("px-6 pt-6 pb-4", __props.ui.body))}" style="${ssrRenderStyle(__props.ui.bodyStyle)}" data-v-ead60393${_scopeId}>`);
            ssrRenderSlot(_ctx.$slots, "default", { close: closeFn }, () => {
              if (__props.description) {
                _push2(`<p class="text-sm text-ui-foreground" data-v-ead60393${_scopeId}>${ssrInterpolate(__props.description)}</p>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`<p class="text-sm text-ui-muted-foreground mt-2" data-v-ead60393${_scopeId}>This action cannot be undone.</p>`);
            }, _push2, _parent2, _scopeId);
            _push2(`</div><div class="flex justify-end gap-2 px-6 pb-6" data-v-ead60393${_scopeId}>`);
            ssrRenderSlot(_ctx.$slots, "footer", { close: closeFn }, () => {
              _push2(ssrRenderComponent(Button, {
                variant: "ghost",
                onClick: closeFn
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(__props.cancelLabel)}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(__props.cancelLabel), 1)
                    ];
                  }
                }),
                _: 2
              }, _parent2, _scopeId));
              _push2(ssrRenderComponent(Button, {
                variant: "danger",
                loading: __props.loading,
                onClick: ($event) => emit("confirm")
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(__props.confirmLabel)}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(__props.confirmLabel), 1)
                    ];
                  }
                }),
                _: 2
              }, _parent2, _scopeId));
            }, _push2, _parent2, _scopeId);
            _push2(`</div>`);
          } else {
            return [
              __props.title ? (openBlock(), createBlock("div", {
                key: 0,
                class: unref(cn)(
                  "variant-modal-header border-b border-red-500/30",
                  __props.ui.header
                ),
                style: __props.ui.headerStyle
              }, [
                createVNode("div", { class: "flex items-center gap-2.5" }, [
                  createVNode("div", { class: "flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-red-500/15" }, [
                    (openBlock(), createBlock("svg", {
                      class: "w-4.5 h-4.5 text-red-500",
                      xmlns: "http://www.w3.org/2000/svg",
                      viewBox: "0 0 24 24",
                      fill: "none",
                      stroke: "currentColor",
                      "stroke-width": "2",
                      "stroke-linecap": "round",
                      "stroke-linejoin": "round"
                    }, [
                      createVNode("circle", {
                        cx: "12",
                        cy: "12",
                        r: "10"
                      }),
                      createVNode("path", { d: "m15 9-6 6" }),
                      createVNode("path", { d: "m9 9 6 6" })
                    ]))
                  ]),
                  createVNode("h3", {
                    class: unref(cn)(
                      "text-lg font-semibold text-ui-foreground",
                      __props.ui.title
                    ),
                    style: __props.ui.titleStyle
                  }, toDisplayString(__props.title), 7)
                ]),
                __props.closeable ? (openBlock(), createBlock("button", {
                  key: 0,
                  onClick: closeFn,
                  class: unref(cn)(
                    "variant-modal-close-button",
                    __props.ui.closeButton
                  ),
                  style: __props.ui.closeButtonStyle,
                  "aria-label": "Close dialog"
                }, [
                  createVNode(unref(X), { class: "h-5 w-5" })
                ], 14, ["onClick"])) : createCommentVNode("", true)
              ], 6)) : createCommentVNode("", true),
              createVNode("div", {
                class: unref(cn)("px-6 pt-6 pb-4", __props.ui.body),
                style: __props.ui.bodyStyle
              }, [
                renderSlot(_ctx.$slots, "default", { close: closeFn }, () => [
                  __props.description ? (openBlock(), createBlock("p", {
                    key: 0,
                    class: "text-sm text-ui-foreground"
                  }, toDisplayString(__props.description), 1)) : createCommentVNode("", true),
                  createVNode("p", { class: "text-sm text-ui-muted-foreground mt-2" }, "This action cannot be undone.")
                ], true)
              ], 6),
              createVNode("div", { class: "flex justify-end gap-2 px-6 pb-6" }, [
                renderSlot(_ctx.$slots, "footer", { close: closeFn }, () => [
                  createVNode(Button, {
                    variant: "ghost",
                    onClick: closeFn
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(__props.cancelLabel), 1)
                    ]),
                    _: 1
                  }, 8, ["onClick"]),
                  createVNode(Button, {
                    variant: "danger",
                    loading: __props.loading,
                    onClick: ($event) => emit("confirm")
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(__props.confirmLabel), 1)
                    ]),
                    _: 1
                  }, 8, ["loading", "onClick"])
                ], true)
              ])
            ];
          }
        }),
        _: 3
      }, _parent));
    };
  }
});
const _sfc_setup$S = _sfc_main$S.setup;
_sfc_main$S.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/DangerModal.vue");
  return _sfc_setup$S ? _sfc_setup$S(props, ctx) : void 0;
};
const DangerModal = /* @__PURE__ */ _export_sfc(_sfc_main$S, [["__scopeId", "data-v-ead60393"]]);
const _sfc_main$R = /* @__PURE__ */ defineComponent({
  __name: "InfoModal",
  __ssrInlineRender: true,
  props: {
    show: { type: Boolean },
    maxWidth: { default: "md" },
    title: {},
    description: {},
    confirmLabel: { default: "OK" },
    loading: { type: Boolean, default: false },
    closeable: { type: Boolean, default: true },
    closeOnBackdrop: { type: Boolean, default: true },
    panelWidth: {},
    panelHeight: {},
    panelMinWidth: {},
    panelMaxWidth: {},
    panelMinHeight: {},
    panelMaxHeight: {},
    ui: { default: () => ({}) }
  },
  emits: ["close", "confirm"],
  setup(__props, { expose: __expose, emit: __emit }) {
    const emit = __emit;
    const modalRef = ref(null);
    const close = () => {
      var _a;
      (_a = modalRef.value) == null ? void 0 : _a.close();
    };
    __expose({ close });
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(Modal, mergeProps({
        ref_key: "modalRef",
        ref: modalRef,
        show: __props.show,
        "max-width": __props.maxWidth,
        closeable: __props.closeable,
        "close-on-backdrop": __props.closeOnBackdrop,
        "panel-width": __props.panelWidth,
        "panel-height": __props.panelHeight,
        "panel-min-width": __props.panelMinWidth,
        "panel-max-width": __props.panelMaxWidth,
        "panel-min-height": __props.panelMinHeight,
        "panel-max-height": __props.panelMaxHeight,
        ui: __props.ui,
        onClose: ($event) => emit("close")
      }, _attrs), {
        default: withCtx(({ close: closeFn }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (__props.title) {
              _push2(`<div class="${ssrRenderClass(unref(cn)(
                "variant-modal-header border-b border-blue-500/30",
                __props.ui.header
              ))}" style="${ssrRenderStyle(__props.ui.headerStyle)}" data-v-4856d60d${_scopeId}><div class="flex items-center gap-2.5" data-v-4856d60d${_scopeId}><div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-blue-500/15" data-v-4856d60d${_scopeId}><svg class="w-4.5 h-4.5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-v-4856d60d${_scopeId}><circle cx="12" cy="12" r="10" data-v-4856d60d${_scopeId}></circle><path d="M12 16v-4" data-v-4856d60d${_scopeId}></path><path d="M12 8h.01" data-v-4856d60d${_scopeId}></path></svg></div><h3 class="${ssrRenderClass(unref(cn)(
                "text-lg font-semibold text-ui-foreground",
                __props.ui.title
              ))}" style="${ssrRenderStyle(__props.ui.titleStyle)}" data-v-4856d60d${_scopeId}>${ssrInterpolate(__props.title)}</h3></div>`);
              if (__props.closeable) {
                _push2(`<button class="${ssrRenderClass(unref(cn)(
                  "variant-modal-close-button",
                  __props.ui.closeButton
                ))}" style="${ssrRenderStyle(__props.ui.closeButtonStyle)}" aria-label="Close dialog" data-v-4856d60d${_scopeId}>`);
                _push2(ssrRenderComponent(unref(X), { class: "h-5 w-5" }, null, _parent2, _scopeId));
                _push2(`</button>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<div class="${ssrRenderClass(unref(cn)("px-6 pt-6 pb-4", __props.ui.body))}" style="${ssrRenderStyle(__props.ui.bodyStyle)}" data-v-4856d60d${_scopeId}>`);
            ssrRenderSlot(_ctx.$slots, "default", { close: closeFn }, () => {
              if (__props.description) {
                _push2(`<p class="text-sm text-ui-foreground" data-v-4856d60d${_scopeId}>${ssrInterpolate(__props.description)}</p>`);
              } else {
                _push2(`<!---->`);
              }
            }, _push2, _parent2, _scopeId);
            _push2(`</div><div class="flex justify-end gap-2 px-6 pb-6" data-v-4856d60d${_scopeId}>`);
            ssrRenderSlot(_ctx.$slots, "footer", { close: closeFn }, () => {
              _push2(ssrRenderComponent(Button, {
                onClick: ($event) => {
                  emit("confirm");
                  closeFn();
                }
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`${ssrInterpolate(__props.confirmLabel)}`);
                  } else {
                    return [
                      createTextVNode(toDisplayString(__props.confirmLabel), 1)
                    ];
                  }
                }),
                _: 2
              }, _parent2, _scopeId));
            }, _push2, _parent2, _scopeId);
            _push2(`</div>`);
          } else {
            return [
              __props.title ? (openBlock(), createBlock("div", {
                key: 0,
                class: unref(cn)(
                  "variant-modal-header border-b border-blue-500/30",
                  __props.ui.header
                ),
                style: __props.ui.headerStyle
              }, [
                createVNode("div", { class: "flex items-center gap-2.5" }, [
                  createVNode("div", { class: "flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-blue-500/15" }, [
                    (openBlock(), createBlock("svg", {
                      class: "w-4.5 h-4.5 text-blue-500",
                      xmlns: "http://www.w3.org/2000/svg",
                      viewBox: "0 0 24 24",
                      fill: "none",
                      stroke: "currentColor",
                      "stroke-width": "2",
                      "stroke-linecap": "round",
                      "stroke-linejoin": "round"
                    }, [
                      createVNode("circle", {
                        cx: "12",
                        cy: "12",
                        r: "10"
                      }),
                      createVNode("path", { d: "M12 16v-4" }),
                      createVNode("path", { d: "M12 8h.01" })
                    ]))
                  ]),
                  createVNode("h3", {
                    class: unref(cn)(
                      "text-lg font-semibold text-ui-foreground",
                      __props.ui.title
                    ),
                    style: __props.ui.titleStyle
                  }, toDisplayString(__props.title), 7)
                ]),
                __props.closeable ? (openBlock(), createBlock("button", {
                  key: 0,
                  onClick: closeFn,
                  class: unref(cn)(
                    "variant-modal-close-button",
                    __props.ui.closeButton
                  ),
                  style: __props.ui.closeButtonStyle,
                  "aria-label": "Close dialog"
                }, [
                  createVNode(unref(X), { class: "h-5 w-5" })
                ], 14, ["onClick"])) : createCommentVNode("", true)
              ], 6)) : createCommentVNode("", true),
              createVNode("div", {
                class: unref(cn)("px-6 pt-6 pb-4", __props.ui.body),
                style: __props.ui.bodyStyle
              }, [
                renderSlot(_ctx.$slots, "default", { close: closeFn }, () => [
                  __props.description ? (openBlock(), createBlock("p", {
                    key: 0,
                    class: "text-sm text-ui-foreground"
                  }, toDisplayString(__props.description), 1)) : createCommentVNode("", true)
                ], true)
              ], 6),
              createVNode("div", { class: "flex justify-end gap-2 px-6 pb-6" }, [
                renderSlot(_ctx.$slots, "footer", { close: closeFn }, () => [
                  createVNode(Button, {
                    onClick: ($event) => {
                      emit("confirm");
                      closeFn();
                    }
                  }, {
                    default: withCtx(() => [
                      createTextVNode(toDisplayString(__props.confirmLabel), 1)
                    ]),
                    _: 1
                  }, 8, ["onClick"])
                ], true)
              ])
            ];
          }
        }),
        _: 3
      }, _parent));
    };
  }
});
const _sfc_setup$R = _sfc_main$R.setup;
_sfc_main$R.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/InfoModal.vue");
  return _sfc_setup$R ? _sfc_setup$R(props, ctx) : void 0;
};
const _sfc_main$Q = /* @__PURE__ */ defineComponent({
  __name: "NavLink",
  __ssrInlineRender: true,
  props: {
    href: {},
    active: { type: Boolean }
  },
  setup(__props) {
    const props = __props;
    const classes = computed(
      () => props.active ? "inline-flex items-center px-1 pt-1 border-b-2 border-ui-primary text-sm font-medium leading-5 text-ui-foreground focus:outline-none focus:border-ui-primary-active transition duration-150 ease-in-out" : "inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-ui-muted-foreground hover:text-ui-foreground hover:border-ui-border focus:outline-none focus:text-ui-foreground focus:border-ui-border transition duration-150 ease-in-out"
    );
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(Link), mergeProps({
        href: __props.href,
        class: unref(cn)(classes.value, _ctx.$attrs.class)
      }, _attrs), {
        default: withCtx((_2, _push2, _parent2, _scopeId) => {
          if (_push2) {
            ssrRenderSlot(_ctx.$slots, "default", {}, null, _push2, _parent2, _scopeId);
          } else {
            return [
              renderSlot(_ctx.$slots, "default")
            ];
          }
        }),
        _: 3
      }, _parent));
    };
  }
});
const _sfc_setup$Q = _sfc_main$Q.setup;
_sfc_main$Q.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/NavLink.vue");
  return _sfc_setup$Q ? _sfc_setup$Q(props, ctx) : void 0;
};
const _sfc_main$P = /* @__PURE__ */ defineComponent({
  __name: "ResponsiveNavLink",
  __ssrInlineRender: true,
  props: {
    href: {},
    active: { type: Boolean }
  },
  setup(__props) {
    const props = __props;
    const classes = computed(
      () => props.active ? "block w-full ps-3 pe-4 py-2 border-l-4 border-ui-primary text-start text-base font-medium text-ui-primary bg-ui-primary/10 focus:outline-none focus:text-ui-primary focus:bg-ui-primary/15 focus:border-ui-primary-active transition duration-150 ease-in-out" : "block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-ui-muted-foreground hover:text-ui-foreground hover:bg-ui-secondary hover:border-ui-border focus:outline-none focus:text-ui-foreground focus:bg-ui-secondary focus:border-ui-border transition duration-150 ease-in-out"
    );
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(Link), mergeProps({
        href: __props.href,
        class: unref(cn)(classes.value, _ctx.$attrs.class)
      }, _attrs), {
        default: withCtx((_2, _push2, _parent2, _scopeId) => {
          if (_push2) {
            ssrRenderSlot(_ctx.$slots, "default", {}, null, _push2, _parent2, _scopeId);
          } else {
            return [
              renderSlot(_ctx.$slots, "default")
            ];
          }
        }),
        _: 3
      }, _parent));
    };
  }
});
const _sfc_setup$P = _sfc_main$P.setup;
_sfc_main$P.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/ResponsiveNavLink.vue");
  return _sfc_setup$P ? _sfc_setup$P(props, ctx) : void 0;
};
function useFormError(options) {
  const { error, fieldKey, config = {} } = options;
  const errors = computed(() => {
    return parseErrors(toValue(error), fieldKey);
  });
  const hasError = computed(() => {
    return errors.value.length > 0;
  });
  const firstError = computed(() => {
    return errors.value.length > 0 ? errors.value[0].message : null;
  });
  const visibleErrors = computed(() => {
    const max = config.maxVisible ?? Infinity;
    return errors.value.slice(0, max);
  });
  const hiddenCount = computed(() => {
    const max = config.maxVisible ?? Infinity;
    return Math.max(0, errors.value.length - max);
  });
  const errorString = computed(() => {
    const separator = config.separator ?? ", ";
    return errors.value.map((e2) => e2.message).join(separator);
  });
  const errorClass = computed(() => {
    return hasError.value ? "has-error" : "";
  });
  return {
    errors,
    hasError,
    firstError,
    visibleErrors,
    hiddenCount,
    errorString,
    errorClass
  };
}
const defaultInputClass$3 = "w-full rounded-lg border-ui-border bg-ui-input text-ui-foreground shadow-sm focus:border-ui-ring focus:ring-ui-ring dark:bg-ui-input dark:border-ui-border dark:text-ui-foreground dark:focus:border-ui-ring dark:focus:ring-ui-ring transition-all duration-200 placeholder:text-ui-muted-foreground disabled:opacity-50 disabled:cursor-not-allowed";
const errorInputClass$3 = "border-ui-error focus:border-ui-error focus:ring-ui-error dark:border-ui-error dark:focus:border-ui-error dark:focus:ring-ui-error";
const _sfc_main$O = /* @__PURE__ */ defineComponent({
  __name: "TextInput",
  __ssrInlineRender: true,
  props: /* @__PURE__ */ mergeModels({
    ui: { default: () => ({}) },
    label: { default: void 0 },
    helperText: { default: void 0 },
    error: { default: void 0 },
    fieldKey: { default: void 0 },
    maxLength: { default: void 0 },
    disabled: { type: Boolean, default: false },
    placeholder: { default: void 0 },
    id: { default: void 0 },
    compact: { type: Boolean, default: false }
  }, {
    "modelValue": { required: false, default: null },
    "modelModifiers": {}
  }),
  emits: ["update:modelValue"],
  setup(__props, { expose: __expose }) {
    const model = useModel(__props, "modelValue");
    const props = __props;
    const input = ref(null);
    const slots = useSlots();
    const hasInnerSlot = computed(() => Boolean(slots.inner));
    const { hasError } = useFormError({
      error: () => props.error,
      fieldKey: props.fieldKey
    });
    onMounted(() => {
      var _a;
      if ((_a = input.value) == null ? void 0 : _a.hasAttribute("autofocus")) {
        input.value.focus();
      }
    });
    __expose({ focus: () => {
      var _a;
      return (_a = input.value) == null ? void 0 : _a.focus();
    } });
    const computedInputClass = computed(() => {
      return cn(
        defaultInputClass$3,
        hasError.value ? errorInputClass$3 : "",
        hasInnerSlot.value ? "pr-11" : "",
        props.compact ? "h-8 px-2 py-1 text-xs" : "",
        props.ui.input
      );
    });
    const currentLength = computed(() => {
      if (typeof model.value === "string") {
        return model.value.length;
      }
      return 0;
    });
    return (_ctx, _push, _parent, _attrs) => {
      let _temp0;
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)("w-full", __props.ui.wrapper),
        style: __props.ui.wrapperStyle
      }, _attrs))}>`);
      if (__props.label) {
        _push(ssrRenderComponent(_sfc_main$V, {
          value: __props.label,
          for: __props.id,
          class: __props.ui.label,
          style: __props.ui.labelStyle
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="relative">`);
      ssrRenderSlot(_ctx.$slots, "prepend", {}, null, _push, _parent);
      _push(`<input${ssrRenderAttrs((_temp0 = mergeProps({
        ref_key: "input",
        ref: input,
        id: __props.id,
        class: computedInputClass.value,
        style: __props.ui.inputStyle,
        disabled: __props.disabled,
        placeholder: __props.placeholder,
        maxlength: __props.maxLength
      }, _ctx.$attrs), mergeProps(_temp0, ssrGetDynamicModelProps(_temp0, model.value))))}>`);
      if (hasInnerSlot.value) {
        _push(`<div class="pointer-events-none absolute inset-y-0 right-2 flex items-center"><div class="pointer-events-auto">`);
        ssrRenderSlot(_ctx.$slots, "inner", {}, null, _push, _parent);
        _push(`</div></div>`);
      } else {
        _push(`<!---->`);
      }
      ssrRenderSlot(_ctx.$slots, "append", {}, null, _push, _parent);
      _push(`</div>`);
      if (!__props.compact && __props.helperText) {
        _push(`<p class="${ssrRenderClass(unref(cn)("mt-1 text-xs text-ui-muted-foreground", __props.ui.helper))}" style="${ssrRenderStyle(__props.ui.helperStyle)}">${ssrInterpolate(__props.helperText)}</p>`);
      } else {
        _push(`<!---->`);
      }
      if (!__props.compact) {
        _push(`<div class="flex justify-between mt-1 min-h-[20px]"><div class="flex-1">`);
        _push(ssrRenderComponent(_sfc_main$W, {
          error: __props.error,
          "field-key": __props.fieldKey,
          ui: { wrapper: __props.ui.error, wrapperStyle: __props.ui.errorStyle }
        }, null, _parent));
        _push(`</div>`);
        if (__props.maxLength) {
          _push(`<div class="${ssrRenderClass(unref(cn)("text-xs text-ui-muted-foreground ml-2", __props.ui.counter))}" style="${ssrRenderStyle(__props.ui.counterStyle)}">${ssrInterpolate(currentLength.value)} / ${ssrInterpolate(__props.maxLength)}</div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$O = _sfc_main$O.setup;
_sfc_main$O.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/TextInput.vue");
  return _sfc_setup$O ? _sfc_setup$O(props, ctx) : void 0;
};
const defaultInputClass$2 = "w-full rounded-lg border-ui-border bg-ui-input text-ui-foreground shadow-sm focus:border-ui-ring focus:ring-ui-ring dark:bg-ui-input dark:border-ui-border dark:text-ui-foreground dark:focus:border-ui-ring dark:focus:ring-ui-ring transition-all duration-200 placeholder:text-ui-muted-foreground disabled:opacity-50 disabled:cursor-not-allowed";
const errorInputClass$2 = "border-ui-error focus:border-ui-error focus:ring-ui-error dark:border-ui-error dark:focus:border-ui-error dark:focus:ring-ui-error";
const _sfc_main$N = /* @__PURE__ */ defineComponent({
  __name: "DateInput",
  __ssrInlineRender: true,
  props: /* @__PURE__ */ mergeModels({
    ui: { default: () => ({}) },
    label: { default: void 0 },
    helperText: { default: void 0 },
    error: { default: void 0 },
    fieldKey: { default: void 0 },
    min: { default: void 0 },
    max: { default: void 0 },
    step: { default: void 0 },
    disabled: { type: Boolean, default: false },
    placeholder: { default: void 0 },
    id: { default: void 0 },
    compact: { type: Boolean, default: false }
  }, {
    "modelValue": { required: false, default: null },
    "modelModifiers": {}
  }),
  emits: ["update:modelValue"],
  setup(__props, { expose: __expose }) {
    const model = useModel(__props, "modelValue");
    const props = __props;
    const input = ref(null);
    const { hasError } = useFormError({
      error: () => props.error,
      fieldKey: props.fieldKey
    });
    onMounted(() => {
      var _a;
      if ((_a = input.value) == null ? void 0 : _a.hasAttribute("autofocus")) {
        input.value.focus();
      }
    });
    __expose({ focus: () => {
      var _a;
      return (_a = input.value) == null ? void 0 : _a.focus();
    } });
    const computedInputClass = computed(() => cn(
      defaultInputClass$2,
      hasError.value ? errorInputClass$2 : "",
      props.compact ? "h-8 px-2 py-1 text-xs" : "",
      props.ui.input
    ));
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)("w-full", __props.ui.wrapper),
        style: __props.ui.wrapperStyle
      }, _attrs))}>`);
      if (__props.label) {
        _push(ssrRenderComponent(_sfc_main$V, {
          value: __props.label,
          for: __props.id,
          class: __props.ui.label,
          style: __props.ui.labelStyle
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<input${ssrRenderAttrs(mergeProps({
        ref_key: "input",
        ref: input
      }, _ctx.$attrs, {
        type: "date",
        id: __props.id,
        class: computedInputClass.value,
        style: __props.ui.inputStyle,
        disabled: __props.disabled,
        placeholder: __props.placeholder,
        min: __props.min,
        max: __props.max,
        step: __props.step,
        value: model.value ?? "",
        "aria-invalid": unref(hasError) ? "true" : void 0
      }))}>`);
      if (!__props.compact && __props.helperText) {
        _push(`<p class="${ssrRenderClass(unref(cn)("mt-1 text-xs text-ui-muted-foreground", __props.ui.helper))}" style="${ssrRenderStyle(__props.ui.helperStyle)}">${ssrInterpolate(__props.helperText)}</p>`);
      } else {
        _push(`<!---->`);
      }
      if (!__props.compact) {
        _push(`<div class="flex justify-between mt-1 min-h-[20px]"><div class="flex-1">`);
        _push(ssrRenderComponent(_sfc_main$W, {
          error: __props.error,
          "field-key": __props.fieldKey,
          ui: { wrapper: __props.ui.error, wrapperStyle: __props.ui.errorStyle }
        }, null, _parent));
        _push(`</div></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$N = _sfc_main$N.setup;
_sfc_main$N.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/DateInput.vue");
  return _sfc_setup$N ? _sfc_setup$N(props, ctx) : void 0;
};
const _sfc_main$M = /* @__PURE__ */ defineComponent({
  __name: "DynamicFieldList",
  __ssrInlineRender: true,
  props: /* @__PURE__ */ mergeModels({
    label: { default: void 0 },
    description: { default: void 0 },
    itemLabel: { default: "Value" },
    placeholder: { default: void 0 },
    addLabel: { default: "Add item" },
    removeLabel: { default: "Remove item" },
    minItems: { default: 1 },
    maxItems: { default: void 0 },
    error: { default: void 0 },
    fieldKey: { default: void 0 },
    ui: { default: () => ({}) }
  }, {
    "modelValue": { required: false, default: () => [] },
    "modelModifiers": {}
  }),
  emits: ["update:modelValue"],
  setup(__props) {
    const model = useModel(__props, "modelValue");
    const props = __props;
    const normalizedMinItems = computed(() => Math.max(0, props.minItems));
    const canAddMore = computed(() => props.maxItems === void 0 || model.value.length < props.maxItems);
    const ensureMinItems = () => {
      if (model.value.length >= normalizedMinItems.value) {
        return;
      }
      const missing = normalizedMinItems.value - model.value.length;
      model.value = [
        ...model.value,
        ...Array.from({ length: missing }, () => "")
      ];
    };
    watch(
      () => normalizedMinItems.value,
      () => ensureMinItems(),
      { immediate: true }
    );
    watch(
      () => model.value.length,
      () => {
        if (model.value.length < normalizedMinItems.value) {
          ensureMinItems();
        }
      }
    );
    const addItem = () => {
      if (!canAddMore.value) {
        return;
      }
      model.value = [...model.value, ""];
    };
    const removeItem = (index) => {
      if (model.value.length <= normalizedMinItems.value) {
        return;
      }
      model.value = model.value.filter((_2, currentIndex) => currentIndex !== index);
    };
    const updateItem = (index, value) => {
      const stringValue = value === null ? "" : String(value);
      model.value = model.value.map((item, currentIndex) => currentIndex === index ? stringValue : item);
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)("space-y-3", __props.ui.root),
        style: __props.ui.rootStyle
      }, _attrs))}>`);
      if (__props.label || __props.description) {
        _push(`<div class="${ssrRenderClass(unref(cn)("space-y-1", __props.ui.header))}" style="${ssrRenderStyle(__props.ui.headerStyle)}">`);
        if (__props.label) {
          _push(`<p class="text-sm font-semibold text-ui-foreground">${ssrInterpolate(__props.label)}</p>`);
        } else {
          _push(`<!---->`);
        }
        if (__props.description) {
          _push(`<p class="${ssrRenderClass(unref(cn)("text-xs text-ui-muted-foreground", __props.ui.description))}" style="${ssrRenderStyle(__props.ui.descriptionStyle)}">${ssrInterpolate(__props.description)}</p>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="${ssrRenderClass(unref(cn)("space-y-2", __props.ui.list))}" style="${ssrRenderStyle(__props.ui.listStyle)}"><!--[-->`);
      ssrRenderList(model.value, (item, index) => {
        _push(`<div class="${ssrRenderClass(unref(cn)("flex items-start gap-2", __props.ui.row))}" style="${ssrRenderStyle(__props.ui.rowStyle)}">`);
        _push(ssrRenderComponent(_sfc_main$O, {
          "model-value": item,
          label: `${__props.itemLabel} ${index + 1}`,
          placeholder: __props.placeholder,
          ui: { input: __props.ui.input, inputStyle: __props.ui.inputStyle },
          "onUpdate:modelValue": (value) => updateItem(index, value)
        }, null, _parent));
        _push(ssrRenderComponent(Button, {
          type: "button",
          variant: "ghost",
          size: "sm",
          icon: "trash-2",
          disabled: model.value.length <= normalizedMinItems.value,
          title: __props.removeLabel,
          "aria-label": `${__props.removeLabel} ${index + 1}`,
          ui: { root: unref(cn)("mt-[1.95rem] shrink-0", __props.ui.removeButton), rootStyle: __props.ui.removeButtonStyle },
          onClick: ($event) => removeItem(index)
        }, null, _parent));
        _push(`</div>`);
      });
      _push(`<!--]--></div><div class="flex items-center justify-between gap-3">`);
      _push(ssrRenderComponent(_sfc_main$W, {
        error: __props.error,
        "field-key": __props.fieldKey,
        ui: { wrapper: __props.ui.error, wrapperStyle: __props.ui.errorStyle }
      }, null, _parent));
      _push(ssrRenderComponent(Button, {
        type: "button",
        variant: "secondary",
        size: "sm",
        icon: "plus",
        disabled: !canAddMore.value,
        ui: { root: unref(cn)("shrink-0", __props.ui.addButton), rootStyle: __props.ui.addButtonStyle },
        onClick: addItem
      }, {
        default: withCtx((_2, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`${ssrInterpolate(__props.addLabel)}`);
          } else {
            return [
              createTextVNode(toDisplayString(__props.addLabel), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></div>`);
    };
  }
});
const _sfc_setup$M = _sfc_main$M.setup;
_sfc_main$M.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/DynamicFieldList.vue");
  return _sfc_setup$M ? _sfc_setup$M(props, ctx) : void 0;
};
const _sfc_main$L = /* @__PURE__ */ defineComponent({
  __name: "CopyUrlField",
  __ssrInlineRender: true,
  props: {
    value: {},
    label: { default: void 0 },
    copyLabel: { default: "Copy URL" },
    copiedLabel: { default: "Copied" },
    disabled: { type: Boolean, default: false },
    ui: { default: () => ({}) }
  },
  emits: ["copied"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const isCopied = ref(false);
    let resetTimer = null;
    const canCopy = computed(() => !props.disabled && props.value.trim() !== "");
    const clearResetTimer = () => {
      if (resetTimer !== null) {
        clearTimeout(resetTimer);
        resetTimer = null;
      }
    };
    const markAsCopied = () => {
      isCopied.value = true;
      clearResetTimer();
      resetTimer = setTimeout(() => {
        isCopied.value = false;
      }, 1800);
    };
    const fallbackCopy = (text) => {
      const textarea = document.createElement("textarea");
      textarea.value = text;
      textarea.setAttribute("readonly", "true");
      textarea.style.position = "fixed";
      textarea.style.opacity = "0";
      textarea.style.pointerEvents = "none";
      textarea.style.top = "0";
      textarea.style.left = "0";
      document.body.appendChild(textarea);
      textarea.select();
      const successful = document.execCommand("copy");
      document.body.removeChild(textarea);
      return successful;
    };
    const copyToClipboard = async () => {
      var _a;
      if (!canCopy.value) {
        return;
      }
      const text = props.value.trim();
      let successful = false;
      if (typeof navigator !== "undefined" && ((_a = navigator.clipboard) == null ? void 0 : _a.writeText)) {
        try {
          await navigator.clipboard.writeText(text);
          successful = true;
        } catch {
          successful = fallbackCopy(text);
        }
      } else {
        successful = fallbackCopy(text);
      }
      if (successful) {
        emit("copied", text);
        markAsCopied();
      }
    };
    onBeforeUnmount(() => {
      clearResetTimer();
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)("w-full", __props.ui.wrapper),
        style: __props.ui.wrapperStyle
      }, _attrs))}>`);
      if (__props.label) {
        _push(`<p class="${ssrRenderClass(unref(cn)("mb-1 text-sm font-medium text-ui-foreground", __props.ui.label))}" style="${ssrRenderStyle(__props.ui.labelStyle)}">${ssrInterpolate(__props.label)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="${ssrRenderClass(unref(cn)("relative rounded-lg border border-ui-border bg-ui-input px-3 py-2 pr-12 text-sm text-ui-foreground", __props.ui.field))}" style="${ssrRenderStyle(__props.ui.fieldStyle)}"><p class="${ssrRenderClass(unref(cn)("truncate font-mono", __props.ui.value))}" style="${ssrRenderStyle(__props.ui.valueStyle)}">${ssrInterpolate(__props.value || "-")}</p>`);
      _push(ssrRenderComponent(Button, {
        type: "button",
        variant: "ghost",
        size: "sm",
        icon: isCopied.value ? "check" : "copy",
        disabled: !canCopy.value,
        tooltip: isCopied.value ? __props.copiedLabel : __props.copyLabel,
        class: unref(cn)("absolute right-1 top-1/2 -translate-y-1/2", __props.ui.button),
        style: __props.ui.buttonStyle,
        onClick: copyToClipboard
      }, null, _parent));
      _push(`</div><p role="status" aria-live="polite" class="${ssrRenderClass(unref(cn)("mt-1 text-xs text-ui-muted-foreground", __props.ui.status))}" style="${ssrRenderStyle(__props.ui.statusStyle)}">${ssrInterpolate(isCopied.value ? __props.copiedLabel : "")}</p></div>`);
    };
  }
});
const _sfc_setup$L = _sfc_main$L.setup;
_sfc_main$L.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/CopyUrlField.vue");
  return _sfc_setup$L ? _sfc_setup$L(props, ctx) : void 0;
};
const defaultInputClass$1 = "w-full rounded-lg border-ui-border bg-ui-input text-ui-foreground shadow-sm focus:border-ui-ring focus:ring-ui-ring dark:bg-ui-input dark:border-ui-border dark:text-ui-foreground dark:focus:border-ui-ring dark:focus:ring-ui-ring transition-all duration-200 placeholder:text-gray-400 disabled:opacity-50 disabled:cursor-not-allowed";
const errorInputClass$1 = "border-ui-error focus:border-ui-error focus:ring-ui-error dark:border-ui-error dark:focus:border-ui-error dark:focus:ring-ui-error";
const buttonClass = "absolute top-1/2 -translate-y-1/2 p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent";
const _sfc_main$K = /* @__PURE__ */ defineComponent({
  __name: "NumberInput",
  __ssrInlineRender: true,
  props: /* @__PURE__ */ mergeModels({
    ui: { default: () => ({}) },
    label: { default: void 0 },
    error: { default: void 0 },
    fieldKey: { default: void 0 },
    min: { default: void 0 },
    max: { default: void 0 },
    step: { default: 1 },
    disabled: { type: Boolean, default: false },
    placeholder: { default: void 0 },
    id: { default: void 0 },
    showButtons: { type: Boolean, default: true },
    precision: { default: void 0 }
  }, {
    "modelValue": { required: true },
    "modelModifiers": {}
  }),
  emits: /* @__PURE__ */ mergeModels(["increment", "decrement"], ["update:modelValue"]),
  setup(__props, { expose: __expose, emit: __emit }) {
    const model = useModel(__props, "modelValue");
    const props = __props;
    const { hasError } = useFormError({
      error: () => props.error,
      fieldKey: props.fieldKey
    });
    const input = ref(null);
    onMounted(() => {
      var _a;
      if ((_a = input.value) == null ? void 0 : _a.hasAttribute("autofocus")) {
        input.value.focus();
      }
    });
    __expose({ focus: () => {
      var _a;
      return (_a = input.value) == null ? void 0 : _a.focus();
    } });
    const computedInputClass = computed(() => {
      return cn(
        defaultInputClass$1,
        props.showButtons ? "px-12" : "",
        hasError.value ? errorInputClass$1 : "",
        props.ui.input
      );
    });
    const canDecrement = computed(() => {
      if (props.disabled) return false;
      if (model.value === null || model.value === void 0) return true;
      if (props.min !== void 0) return model.value > props.min;
      return true;
    });
    const canIncrement = computed(() => {
      if (props.disabled) return false;
      if (model.value === null || model.value === void 0) return true;
      if (props.max !== void 0) return model.value < props.max;
      return true;
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)("w-full", __props.ui.wrapper),
        style: __props.ui.wrapperStyle
      }, _attrs))} data-v-c8900f28>`);
      if (__props.label) {
        _push(ssrRenderComponent(_sfc_main$V, {
          value: __props.label,
          for: __props.id,
          class: __props.ui.label,
          style: __props.ui.labelStyle
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="relative" data-v-c8900f28>`);
      if (__props.showButtons) {
        _push(`<button type="button" class="${ssrRenderClass(unref(cn)(buttonClass, "left-1", __props.ui.button))}" style="${ssrRenderStyle(__props.ui.buttonStyle)}"${ssrIncludeBooleanAttr(!canDecrement.value) ? " disabled" : ""} tabindex="-1" data-v-c8900f28>`);
        _push(ssrRenderComponent(unref(Minus), { class: "w-4 h-4" }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<input${ssrRenderAttrs(mergeProps({
        ref_key: "input",
        ref: input,
        type: "number",
        id: __props.id,
        class: computedInputClass.value,
        style: __props.ui.inputStyle,
        disabled: __props.disabled,
        placeholder: __props.placeholder,
        min: __props.min,
        max: __props.max,
        step: __props.step,
        value: model.value ?? ""
      }, _ctx.$attrs))} data-v-c8900f28>`);
      if (__props.showButtons) {
        _push(`<button type="button" class="${ssrRenderClass(unref(cn)(buttonClass, "right-1", __props.ui.button))}" style="${ssrRenderStyle(__props.ui.buttonStyle)}"${ssrIncludeBooleanAttr(!canIncrement.value) ? " disabled" : ""} tabindex="-1" data-v-c8900f28>`);
        _push(ssrRenderComponent(unref(Plus), { class: "w-4 h-4" }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="flex justify-between mt-1 min-h-[20px]" data-v-c8900f28><div class="flex-1" data-v-c8900f28>`);
      _push(ssrRenderComponent(_sfc_main$W, {
        error: __props.error,
        "field-key": __props.fieldKey,
        ui: { wrapper: __props.ui.error, wrapperStyle: __props.ui.errorStyle }
      }, null, _parent));
      _push(`</div>`);
      if (__props.min !== void 0 || __props.max !== void 0) {
        _push(`<div class="${ssrRenderClass(unref(cn)("text-xs text-gray-500 dark:text-gray-400 ml-2", __props.ui.range))}" style="${ssrRenderStyle(__props.ui.rangeStyle)}" data-v-c8900f28>`);
        if (__props.min !== void 0) {
          _push(`<span data-v-c8900f28>Min: ${ssrInterpolate(__props.min)}</span>`);
        } else {
          _push(`<!---->`);
        }
        if (__props.min !== void 0 && __props.max !== void 0) {
          _push(`<span data-v-c8900f28> · </span>`);
        } else {
          _push(`<!---->`);
        }
        if (__props.max !== void 0) {
          _push(`<span data-v-c8900f28>Max: ${ssrInterpolate(__props.max)}</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div>`);
    };
  }
});
const _sfc_setup$K = _sfc_main$K.setup;
_sfc_main$K.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/NumberInput.vue");
  return _sfc_setup$K ? _sfc_setup$K(props, ctx) : void 0;
};
const NumberInput = /* @__PURE__ */ _export_sfc(_sfc_main$K, [["__scopeId", "data-v-c8900f28"]]);
const defaultInputClass = "w-full rounded-lg border-ui-border bg-ui-input text-ui-foreground shadow-sm focus:border-ui-ring focus:ring-ui-ring dark:bg-ui-input dark:border-ui-border dark:text-ui-foreground dark:focus:border-ui-ring dark:focus:ring-ui-ring transition-all duration-200 placeholder:text-gray-400 disabled:opacity-50 disabled:cursor-not-allowed";
const errorInputClass = "border-ui-error focus:border-ui-error focus:ring-ui-error dark:border-ui-error dark:focus:border-ui-error dark:focus:ring-ui-error";
const _sfc_main$J = /* @__PURE__ */ defineComponent({
  __name: "Textarea",
  __ssrInlineRender: true,
  props: /* @__PURE__ */ mergeModels({
    ui: { default: () => ({}) },
    label: { default: void 0 },
    error: { default: void 0 },
    fieldKey: { default: void 0 },
    maxLength: { default: void 0 },
    rows: { default: 4 },
    disabled: { type: Boolean, default: false },
    placeholder: { default: void 0 },
    id: { default: void 0 },
    resize: { default: "vertical" },
    autoGrow: { type: Boolean, default: false },
    minRows: { default: 2 },
    maxRows: { default: 10 }
  }, {
    "modelValue": { required: false, default: null },
    "modelModifiers": {}
  }),
  emits: ["update:modelValue"],
  setup(__props, { expose: __expose }) {
    const model = useModel(__props, "modelValue");
    const props = __props;
    const { hasError } = useFormError({
      error: () => props.error,
      fieldKey: props.fieldKey
    });
    const textarea = ref(null);
    onMounted(() => {
      var _a;
      if ((_a = textarea.value) == null ? void 0 : _a.hasAttribute("autofocus")) {
        textarea.value.focus();
      }
      if (props.autoGrow) {
        adjustHeight();
      }
    });
    __expose({ focus: () => {
      var _a;
      return (_a = textarea.value) == null ? void 0 : _a.focus();
    } });
    const resizeClasses = {
      none: "resize-none",
      vertical: "resize-y",
      horizontal: "resize-x",
      both: "resize"
    };
    const computedInputClass = computed(() => {
      return cn(
        defaultInputClass,
        props.autoGrow ? "resize-none overflow-hidden" : resizeClasses[props.resize],
        hasError.value ? errorInputClass : "",
        props.ui.textarea
      );
    });
    const currentLength = computed(() => {
      var _a;
      return ((_a = model.value) == null ? void 0 : _a.length) ?? 0;
    });
    const adjustHeight = () => {
      if (!textarea.value || !props.autoGrow) return;
      textarea.value.style.height = "auto";
      const lineHeight = parseInt(getComputedStyle(textarea.value).lineHeight) || 24;
      const minHeight = props.minRows * lineHeight;
      const maxHeight = props.maxRows * lineHeight;
      const scrollHeight = textarea.value.scrollHeight;
      const newHeight = Math.max(minHeight, Math.min(scrollHeight, maxHeight));
      textarea.value.style.height = `${newHeight}px`;
      if (scrollHeight > maxHeight) {
        textarea.value.style.overflowY = "auto";
      } else {
        textarea.value.style.overflowY = "hidden";
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      let _temp0;
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)("w-full", __props.ui.wrapper),
        style: __props.ui.wrapperStyle
      }, _attrs))}>`);
      if (__props.label) {
        _push(ssrRenderComponent(_sfc_main$V, {
          value: __props.label,
          for: __props.id,
          class: __props.ui.label,
          style: __props.ui.labelStyle
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<textarea${ssrRenderAttrs(_temp0 = mergeProps({
        ref_key: "textarea",
        ref: textarea,
        id: __props.id,
        class: computedInputClass.value,
        style: __props.ui.textareaStyle,
        disabled: __props.disabled,
        placeholder: __props.placeholder,
        maxlength: __props.maxLength,
        rows: __props.rows
      }, _ctx.$attrs, { value: model.value }), "textarea")}>${ssrInterpolate("value" in _temp0 ? _temp0.value : "")}</textarea><div class="flex justify-between mt-1 min-h-[20px]"><div class="flex-1">`);
      _push(ssrRenderComponent(_sfc_main$W, {
        error: __props.error,
        "field-key": __props.fieldKey,
        ui: { wrapper: __props.ui.error, wrapperStyle: __props.ui.errorStyle }
      }, null, _parent));
      _push(`</div>`);
      if (__props.maxLength) {
        _push(`<div class="${ssrRenderClass(unref(cn)("text-xs text-gray-500 dark:text-gray-400 ml-2", __props.ui.counter))}" style="${ssrRenderStyle(__props.ui.counterStyle)}">${ssrInterpolate(currentLength.value)} / ${ssrInterpolate(__props.maxLength)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div>`);
    };
  }
});
const _sfc_setup$J = _sfc_main$J.setup;
_sfc_main$J.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Textarea.vue");
  return _sfc_setup$J ? _sfc_setup$J(props, ctx) : void 0;
};
const _sfc_main$I = /* @__PURE__ */ defineComponent({
  __name: "Toggle",
  __ssrInlineRender: true,
  props: {
    modelValue: { type: Boolean },
    size: { default: "md" },
    disabled: { type: Boolean, default: false },
    label: {},
    description: {},
    onLabel: {},
    offLabel: {},
    ui: { default: () => ({}) }
  },
  emits: ["update:modelValue"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const sizeConfig = computed(() => {
      const sizes = {
        sm: { track: "w-8 h-[18px]", thumb: "h-3.5 w-3.5", translate: "translate-x-[14px]", text: "text-xs" },
        md: { track: "w-11 h-6", thumb: "h-5 w-5", translate: "translate-x-5", text: "text-sm" },
        lg: { track: "w-14 h-7", thumb: "h-6 w-6", translate: "translate-x-7", text: "text-base" }
      };
      return sizes[props.size];
    });
    const trackClass = computed(() => cn(
      "relative inline-flex shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-ui-ring focus-visible:ring-offset-2 focus-visible:ring-offset-ui-background",
      sizeConfig.value.track,
      props.modelValue ? "bg-ui-primary shadow-[0_0_8px_rgb(var(--ui-glow)/0.3)]" : "bg-ui-secondary",
      props.disabled && "opacity-50 cursor-not-allowed",
      props.ui.track
    ));
    const thumbClass = computed(() => cn(
      "pointer-events-none inline-block rounded-full bg-white shadow-lg ring-0 transition-transform duration-200 ease-in-out",
      sizeConfig.value.thumb,
      props.modelValue ? sizeConfig.value.translate : "translate-x-0",
      props.ui.thumb
    ));
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)("flex items-start gap-3", __props.ui.root),
        style: __props.ui.rootStyle
      }, _attrs))}><button type="button" role="switch"${ssrRenderAttr("aria-checked", __props.modelValue)}${ssrIncludeBooleanAttr(__props.disabled) ? " disabled" : ""} class="${ssrRenderClass(trackClass.value)}" style="${ssrRenderStyle(__props.ui.trackStyle)}"><span aria-hidden="true" class="${ssrRenderClass(thumbClass.value)}" style="${ssrRenderStyle(__props.ui.thumbStyle)}"></span></button>`);
      if (__props.label || __props.description || __props.onLabel || __props.offLabel || _ctx.$slots.default) {
        _push(`<div class="flex flex-col">`);
        if (__props.label || _ctx.$slots.default) {
          _push(`<span class="${ssrRenderClass(unref(cn)(
            "font-medium text-ui-foreground cursor-pointer select-none",
            sizeConfig.value.text,
            __props.disabled && "opacity-50 cursor-not-allowed",
            __props.ui.label
          ))}" style="${ssrRenderStyle(__props.ui.labelStyle)}">`);
          ssrRenderSlot(_ctx.$slots, "default", {}, () => {
            _push(`${ssrInterpolate(__props.label)}`);
          }, _push, _parent);
          _push(`</span>`);
        } else {
          _push(`<!---->`);
        }
        if (__props.description) {
          _push(`<span class="${ssrRenderClass(unref(cn)("text-ui-muted-foreground text-xs mt-0.5", __props.ui.description))}" style="${ssrRenderStyle(__props.ui.descriptionStyle)}">${ssrInterpolate(__props.description)}</span>`);
        } else {
          _push(`<!---->`);
        }
        if (__props.onLabel || __props.offLabel) {
          _push(`<span class="${ssrRenderClass(unref(cn)(
            "text-xs mt-1 font-medium",
            __props.modelValue ? "text-ui-primary" : "text-ui-muted-foreground",
            __props.modelValue ? __props.ui.onLabel : __props.ui.offLabel
          ))}" style="${ssrRenderStyle(__props.modelValue ? __props.ui.onLabelStyle : __props.ui.offLabelStyle)}">${ssrInterpolate(__props.modelValue ? __props.onLabel : __props.offLabel)}</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$I = _sfc_main$I.setup;
_sfc_main$I.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Toggle.vue");
  return _sfc_setup$I ? _sfc_setup$I(props, ctx) : void 0;
};
const _sfc_main$H = /* @__PURE__ */ defineComponent({
  __name: "RadioGroup",
  __ssrInlineRender: true,
  props: {
    modelValue: {},
    options: {},
    label: {},
    layout: { default: "vertical" },
    compact: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    error: {},
    name: {},
    ui: { default: () => ({}) }
  },
  emits: ["update:modelValue"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const { hasError, firstError } = useFormError({ error: computed(() => props.error) });
    const isSelected = (option) => props.modelValue === option.value;
    const groupName = computed(() => props.name || `radio-group-${Math.random().toString(36).slice(2, 9)}`);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<fieldset${ssrRenderAttrs(mergeProps({
        class: unref(cn)(__props.compact ? "space-y-2" : "space-y-3", __props.ui.root),
        style: __props.ui.rootStyle,
        disabled: __props.disabled
      }, _attrs))}>`);
      if (__props.label) {
        _push(`<legend class="${ssrRenderClass(unref(cn)(
          __props.compact ? "text-xs font-medium text-ui-foreground mb-1 block" : "text-sm font-medium text-ui-foreground mb-2 block",
          __props.ui.legend
        ))}" style="${ssrRenderStyle(__props.ui.legendStyle)}">${ssrInterpolate(__props.label)}</legend>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="${ssrRenderClass(unref(cn)(
        __props.layout === "horizontal" ? __props.compact ? "flex flex-wrap gap-2" : "flex flex-wrap gap-3" : __props.compact ? "space-y-1.5" : "space-y-2",
        __props.ui.optionsWrapper
      ))}" style="${ssrRenderStyle(__props.ui.optionsWrapperStyle)}"><!--[-->`);
      ssrRenderList(__props.options, (option) => {
        _push(`<label class="${ssrRenderClass(unref(cn)(
          __props.compact ? "group relative flex cursor-pointer rounded-md border px-3 py-2 transition-all duration-150" : "group relative flex cursor-pointer rounded-lg border px-4 py-3 transition-all duration-150",
          isSelected(option) ? "border-ui-primary bg-ui-primary/5 ring-1 ring-ui-primary/30 shadow-[0_0_10px_rgb(var(--ui-glow)/0.1)]" : "border-ui-border bg-ui-card hover:border-ui-muted-foreground/30 hover:bg-ui-muted/30",
          (__props.disabled || option.disabled) && "opacity-50 cursor-not-allowed hover:border-ui-border hover:bg-ui-card",
          isSelected(option) ? __props.ui.optionSelected : __props.ui.option
        ))}" style="${ssrRenderStyle(isSelected(option) ? __props.ui.optionSelectedStyle : __props.ui.optionStyle)}"><div class="${ssrRenderClass(unref(cn)("flex items-start", __props.compact ? "pt-0" : "pt-0.5"))}"><div class="${ssrRenderClass(unref(cn)(
          __props.compact ? "flex h-3.5 w-3.5 shrink-0 items-center justify-center rounded-full border-2 transition-all duration-150" : "flex h-4 w-4 shrink-0 items-center justify-center rounded-full border-2 transition-all duration-150",
          isSelected(option) ? "border-ui-primary" : "border-ui-muted-foreground/40 group-hover:border-ui-muted-foreground/60",
          isSelected(option) ? __props.ui.radioSelected : __props.ui.radio
        ))}" style="${ssrRenderStyle(isSelected(option) ? __props.ui.radioSelectedStyle : __props.ui.radioStyle)}"><div class="${ssrRenderClass(unref(cn)(
          "rounded-full bg-ui-primary transition-all duration-150",
          __props.compact ? isSelected(option) ? "h-1.5 w-1.5 scale-100" : "h-0 w-0 scale-0" : isSelected(option) ? "h-2 w-2 scale-100" : "h-0 w-0 scale-0"
        ))}"></div></div><input type="radio"${ssrRenderAttr("name", groupName.value)}${ssrRenderAttr("value", option.value)}${ssrIncludeBooleanAttr(isSelected(option)) ? " checked" : ""}${ssrIncludeBooleanAttr(__props.disabled || option.disabled) ? " disabled" : ""} class="sr-only"></div><div class="${ssrRenderClass(unref(cn)(__props.compact ? "ml-2.5" : "ml-3"))}"><span class="${ssrRenderClass(unref(cn)(
          __props.compact ? "block text-xs font-medium" : "block text-sm font-medium",
          isSelected(option) ? "text-ui-foreground" : "text-ui-muted-foreground group-hover:text-ui-foreground",
          __props.ui.optionLabel
        ))}" style="${ssrRenderStyle(__props.ui.optionLabelStyle)}">${ssrInterpolate(option.label)}</span>`);
        if (option.description) {
          _push(`<span class="${ssrRenderClass(unref(cn)(
            __props.compact ? "block text-[11px] text-ui-muted-foreground mt-0.5" : "block text-xs text-ui-muted-foreground mt-0.5",
            __props.ui.optionDescription
          ))}" style="${ssrRenderStyle(__props.ui.optionDescriptionStyle)}">${ssrInterpolate(option.description)}</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div></label>`);
      });
      _push(`<!--]--></div>`);
      if (unref(hasError)) {
        _push(`<p class="${ssrRenderClass(unref(cn)("text-sm text-ui-destructive mt-2", __props.ui.error))}" style="${ssrRenderStyle(__props.ui.errorStyle)}">${ssrInterpolate(unref(firstError))}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</fieldset>`);
    };
  }
});
const _sfc_setup$H = _sfc_main$H.setup;
_sfc_main$H.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/RadioGroup.vue");
  return _sfc_setup$H ? _sfc_setup$H(props, ctx) : void 0;
};
const _sfc_main$G = /* @__PURE__ */ defineComponent({
  __name: "Chip",
  __ssrInlineRender: true,
  props: {
    label: {},
    variant: { default: "soft" },
    color: { default: "primary" },
    size: { default: "md" },
    removable: { type: Boolean, default: false },
    clickable: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    icon: {},
    ui: { default: () => ({}) }
  },
  emits: ["remove", "click"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const colorMap = {
      primary: {
        solid: "bg-ui-primary text-white",
        outline: "border-ui-primary/50 text-ui-primary bg-transparent",
        soft: "bg-ui-primary/15 text-ui-primary border-ui-primary/20"
      },
      success: {
        solid: "bg-ui-success text-white",
        outline: "border-ui-success/50 text-[rgb(var(--ui-success))] bg-transparent",
        soft: "bg-[rgb(var(--ui-success)/0.15)] text-[rgb(var(--ui-success))] border-[rgb(var(--ui-success)/0.2)]"
      },
      warning: {
        solid: "bg-ui-warning text-black",
        outline: "border-[rgb(var(--ui-warning)/0.5)] text-[rgb(var(--ui-warning))] bg-transparent",
        soft: "bg-[rgb(var(--ui-warning)/0.15)] text-[rgb(var(--ui-warning))] border-[rgb(var(--ui-warning)/0.2)]"
      },
      error: {
        solid: "bg-ui-destructive text-white",
        outline: "border-ui-destructive/50 text-[rgb(var(--ui-destructive))] bg-transparent",
        soft: "bg-[rgb(var(--ui-destructive)/0.15)] text-[rgb(var(--ui-destructive))] border-[rgb(var(--ui-destructive)/0.2)]"
      },
      info: {
        solid: "bg-[rgb(var(--ui-info))] text-white",
        outline: "border-[rgb(var(--ui-info)/0.5)] text-[rgb(var(--ui-info))] bg-transparent",
        soft: "bg-[rgb(var(--ui-info)/0.15)] text-[rgb(var(--ui-info))] border-[rgb(var(--ui-info)/0.2)]"
      },
      neutral: {
        solid: "bg-ui-secondary text-ui-secondary-foreground",
        outline: "border-ui-border text-ui-muted-foreground bg-transparent",
        soft: "bg-ui-muted/50 text-ui-muted-foreground border-ui-border/50"
      }
    };
    const sizeMap = {
      sm: "text-xs px-2 py-0.5 gap-1",
      md: "text-xs px-2.5 py-1 gap-1.5",
      lg: "text-sm px-3 py-1.5 gap-2"
    };
    const iconSizeMap = {
      sm: "h-3 w-3",
      md: "h-3.5 w-3.5",
      lg: "h-4 w-4"
    };
    const semanticVars = computed(() => {
      var _a;
      const colors = {
        primary: "var(--ui-primary)",
        success: "rgb(var(--ui-success))",
        warning: "rgb(var(--ui-warning))",
        error: "rgb(var(--ui-destructive))",
        info: "rgb(var(--ui-info))",
        neutral: "var(--ui-muted)"
      };
      const color = colors[props.color] || props.color;
      const rootStyle = (_a = props.ui) == null ? void 0 : _a.rootStyle;
      return {
        "--ui-primary": color,
        ...typeof rootStyle === "object" ? rootStyle : {}
      };
    });
    const rootClass = computed(() => {
      var _a;
      return cn(
        "inline-flex items-center rounded-full border font-medium transition-all duration-150",
        sizeMap[props.size],
        ((_a = colorMap[props.color]) == null ? void 0 : _a[props.variant]) ?? colorMap.primary.soft,
        props.clickable && !props.disabled && "cursor-pointer hover:opacity-80 active:scale-95",
        props.disabled && "opacity-50 cursor-not-allowed",
        props.ui.root
      );
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<span${ssrRenderAttrs(mergeProps({
        class: rootClass.value,
        style: semanticVars.value,
        role: "status"
      }, _attrs))}>`);
      if (__props.icon) {
        _push(ssrRenderComponent(_sfc_main$13, {
          icon: __props.icon,
          ui: { root: unref(cn)(iconSizeMap[__props.size], __props.ui.icon), rootStyle: __props.ui.iconStyle },
          "aria-hidden": "true"
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      ssrRenderSlot(_ctx.$slots, "prefix", {}, null, _push, _parent);
      _push(`<span class="${ssrRenderClass(unref(cn)("truncate", __props.ui.label))}" style="${ssrRenderStyle(__props.ui.labelStyle)}">`);
      ssrRenderSlot(_ctx.$slots, "default", {}, () => {
        _push(`${ssrInterpolate(__props.label)}`);
      }, _push, _parent);
      _push(`</span>`);
      if (__props.removable && !__props.disabled) {
        _push(`<button type="button" class="${ssrRenderClass(unref(cn)(
          "inline-flex items-center justify-center rounded-full -mr-0.5 transition-colors hover:bg-black/10 dark:hover:bg-white/10",
          __props.size === "sm" ? "h-3.5 w-3.5" : __props.size === "md" ? "h-4 w-4" : "h-5 w-5",
          __props.ui.removeButton
        ))}" style="${ssrRenderStyle(__props.ui.removeButtonStyle)}" aria-label="Remove">`);
        _push(ssrRenderComponent(_sfc_main$13, {
          icon: "x",
          ui: {
            root: unref(cn)(
              __props.size === "sm" ? "h-2.5 w-2.5" : __props.size === "md" ? "h-3 w-3" : "h-3.5 w-3.5",
              __props.ui.removeIcon
            ),
            rootStyle: __props.ui.removeIconStyle
          }
        }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</span>`);
    };
  }
});
const _sfc_setup$G = _sfc_main$G.setup;
_sfc_main$G.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Chip.vue");
  return _sfc_setup$G ? _sfc_setup$G(props, ctx) : void 0;
};
const _sfc_main$F = /* @__PURE__ */ defineComponent({
  __name: "Badge",
  __ssrInlineRender: true,
  props: {
    label: {},
    variant: { default: "soft" },
    color: { default: "primary" },
    size: { default: "sm" },
    icon: {},
    dot: { type: Boolean, default: false },
    pulse: { type: Boolean, default: false },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const predefinedColors = {
      primary: {
        solid: "bg-ui-primary text-white border-transparent",
        outline: "border-ui-primary/50 text-ui-primary bg-transparent",
        soft: "bg-ui-primary/15 text-ui-primary border-ui-primary/20",
        gradient: "bg-gradient-to-r from-ui-primary to-ui-primary-hover text-white border-transparent shadow-lg shadow-ui-primary/25"
      },
      success: {
        solid: "bg-ui-success text-white border-transparent",
        outline: "border-ui-success/50 text-[rgb(var(--ui-success))] bg-transparent",
        soft: "bg-[rgb(var(--ui-success)/0.15)] text-[rgb(var(--ui-success))] border-[rgb(var(--ui-success)/0.2)]",
        gradient: "bg-gradient-to-r from-[rgb(var(--ui-success))] to-[rgb(var(--ui-success)/0.7)] text-white border-transparent shadow-lg shadow-[rgb(var(--ui-success)/0.25)]"
      },
      warning: {
        solid: "bg-ui-warning text-black border-transparent",
        outline: "border-[rgb(var(--ui-warning)/0.5)] text-[rgb(var(--ui-warning))] bg-transparent",
        soft: "bg-[rgb(var(--ui-warning)/0.15)] text-[rgb(var(--ui-warning))] border-[rgb(var(--ui-warning)/0.2)]",
        gradient: "bg-gradient-to-r from-[rgb(var(--ui-warning))] to-[rgb(var(--ui-warning)/0.7)] text-black border-transparent shadow-lg shadow-[rgb(var(--ui-warning)/0.25)]"
      },
      error: {
        solid: "bg-ui-destructive text-white border-transparent",
        outline: "border-ui-destructive/50 text-[rgb(var(--ui-destructive))] bg-transparent",
        soft: "bg-[rgb(var(--ui-destructive)/0.15)] text-[rgb(var(--ui-destructive))] border-[rgb(var(--ui-destructive)/0.2)]",
        gradient: "bg-gradient-to-r from-[rgb(var(--ui-destructive))] to-[rgb(var(--ui-destructive)/0.7)] text-white border-transparent shadow-lg shadow-[rgb(var(--ui-destructive)/0.25)]"
      },
      info: {
        solid: "bg-[rgb(var(--ui-info))] text-white border-transparent",
        outline: "border-[rgb(var(--ui-info)/0.5)] text-[rgb(var(--ui-info))] bg-transparent",
        soft: "bg-[rgb(var(--ui-info)/0.15)] text-[rgb(var(--ui-info))] border-[rgb(var(--ui-info)/0.2)]",
        gradient: "bg-gradient-to-r from-[rgb(var(--ui-info))] to-[rgb(var(--ui-info)/0.7)] text-white border-transparent shadow-lg shadow-[rgb(var(--ui-info)/0.25)]"
      },
      neutral: {
        solid: "bg-ui-secondary text-ui-secondary-foreground border-transparent",
        outline: "border-ui-border text-ui-muted-foreground bg-transparent",
        soft: "bg-ui-muted/50 text-ui-muted-foreground border-ui-border/50",
        gradient: "bg-gradient-to-r from-ui-secondary to-ui-muted text-ui-foreground border-transparent shadow-lg shadow-ui-muted/25"
      }
    };
    const sizeMap = {
      xs: "text-[10px] px-1.5 py-0.5 gap-1",
      sm: "text-xs px-2 py-0.5 gap-1",
      md: "text-xs px-2.5 py-1 gap-1.5"
    };
    const iconSizeMap = {
      xs: "h-2.5 w-2.5",
      sm: "h-3 w-3",
      md: "h-3.5 w-3.5"
    };
    const dotSizeMap = {
      xs: "h-1.5 w-1.5",
      sm: "h-2 w-2",
      md: "h-2 w-2"
    };
    const isPredefinedColor = computed(() => {
      return props.color in predefinedColors;
    });
    const semanticVars = computed(() => {
      var _a;
      const colors = {
        primary: "var(--ui-primary)",
        success: "rgb(var(--ui-success))",
        warning: "rgb(var(--ui-warning))",
        error: "rgb(var(--ui-destructive))",
        info: "rgb(var(--ui-info))",
        neutral: "var(--ui-muted)"
      };
      const color = colors[props.color] || props.color;
      const rootStyle = (_a = props.ui) == null ? void 0 : _a.rootStyle;
      return {
        "--badge-color": color,
        ...typeof rootStyle === "object" ? rootStyle : {}
      };
    });
    const customColorClasses = computed(() => ({
      solid: "bg-[var(--badge-color)] text-white border-transparent",
      outline: "border-[var(--badge-color)]/50 text-[var(--badge-color)] bg-transparent",
      soft: "bg-[var(--badge-color)]/15 text-[var(--badge-color)] border-[var(--badge-color)]/20",
      gradient: "bg-gradient-to-r from-[var(--badge-color)] to-[var(--badge-color)]/70 text-white border-transparent shadow-lg shadow-[var(--badge-color)]/25"
    }));
    const rootClass = computed(() => {
      var _a;
      return cn(
        "inline-flex items-center rounded-full border font-semibold tracking-wide uppercase",
        sizeMap[props.size],
        isPredefinedColor.value ? ((_a = predefinedColors[props.color]) == null ? void 0 : _a[props.variant]) ?? predefinedColors.primary.soft : customColorClasses.value[props.variant],
        props.ui.root
      );
    });
    const rootStyles = computed(() => {
      var _a;
      if (isPredefinedColor.value) {
        const rootStyle = (_a = props.ui) == null ? void 0 : _a.rootStyle;
        if (!rootStyle) return void 0;
        if (typeof rootStyle === "string") return rootStyle;
        return rootStyle;
      }
      return semanticVars.value;
    });
    const dotBgStyle = computed(() => {
      var _a, _b;
      if (isPredefinedColor.value) {
        const dotStyle = (_a = props.ui) == null ? void 0 : _a.dotStyle;
        if (dotStyle) {
          return typeof dotStyle === "string" ? dotStyle : dotStyle;
        }
        return { backgroundColor: "currentColor" };
      }
      const base = { backgroundColor: "var(--badge-color)" };
      if ((_b = props.ui) == null ? void 0 : _b.dotStyle) {
        const ds = props.ui.dotStyle;
        if (typeof ds === "object") return { ...base, ...ds };
      }
      return base;
    });
    const iconSizeClass = computed(() => iconSizeMap[props.size]);
    const dotSizeClass = computed(() => dotSizeMap[props.size]);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<span${ssrRenderAttrs(mergeProps({
        class: rootClass.value,
        style: rootStyles.value,
        role: "status"
      }, _attrs))}>`);
      if (__props.dot) {
        _push(`<span class="${ssrRenderClass(unref(cn)("relative flex shrink-0", __props.ui.dot))}" style="${ssrRenderStyle(typeof __props.ui.dotStyle === "string" ? __props.ui.dotStyle : void 0)}">`);
        if (__props.pulse) {
          _push(`<span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="${ssrRenderStyle(dotBgStyle.value)}"></span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<span class="${ssrRenderClass([dotSizeClass.value, "relative inline-flex rounded-full"])}" style="${ssrRenderStyle(dotBgStyle.value)}"></span></span>`);
      } else {
        _push(`<!---->`);
      }
      if (__props.icon) {
        _push(ssrRenderComponent(_sfc_main$13, {
          icon: __props.icon,
          ui: { root: unref(cn)(iconSizeClass.value, __props.ui.icon), rootStyle: __props.ui.iconStyle },
          "aria-hidden": "true"
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<span class="${ssrRenderClass(unref(cn)("truncate", __props.ui.label))}" style="${ssrRenderStyle(__props.ui.labelStyle)}">`);
      ssrRenderSlot(_ctx.$slots, "default", {}, () => {
        _push(`${ssrInterpolate(__props.label)}`);
      }, _push, _parent);
      _push(`</span></span>`);
    };
  }
});
const _sfc_setup$F = _sfc_main$F.setup;
_sfc_main$F.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Badge.vue");
  return _sfc_setup$F ? _sfc_setup$F(props, ctx) : void 0;
};
const _sfc_main$E = /* @__PURE__ */ defineComponent({
  __name: "Card",
  __ssrInlineRender: true,
  props: {
    hover: { type: Boolean, default: false },
    noPadding: { type: Boolean, default: false },
    allowOverflow: { type: Boolean, default: false },
    width: {},
    height: {},
    minWidth: {},
    maxWidth: {},
    minHeight: {},
    maxHeight: {},
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const dimensionStyle = computed(() => ({
      ...props.width ? { width: props.width } : {},
      ...props.height ? { height: props.height } : {},
      ...props.minWidth ? { minWidth: props.minWidth } : {},
      ...props.maxWidth ? { maxWidth: props.maxWidth } : {},
      ...props.minHeight ? { minHeight: props.minHeight } : {},
      ...props.maxHeight ? { maxHeight: props.maxHeight } : {}
    }));
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)(
          "bg-ui-card border border-ui-border rounded-md transition-all duration-200",
          __props.hover ? "hover:-translate-y-0.5 hover:shadow-md hover:bg-ui-secondary" : "",
          __props.allowOverflow ? "overflow-visible" : "overflow-hidden",
          __props.ui.root
        ),
        style: [__props.ui.rootStyle, dimensionStyle.value]
      }, _attrs))}>`);
      if (_ctx.$slots.header) {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "p-4 border-b border-ui-border font-semibold text-ui-foreground",
          __props.ui.header
        ))}" style="${ssrRenderStyle(__props.ui.headerStyle)}">`);
        ssrRenderSlot(_ctx.$slots, "header", {}, null, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="${ssrRenderClass(unref(cn)(
        __props.noPadding ? "" : "p-4",
        __props.ui.body
      ))}" style="${ssrRenderStyle(__props.ui.bodyStyle)}">`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</div>`);
      if (_ctx.$slots.footer) {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "py-3 px-4 bg-black/20 border-t border-ui-border",
          __props.ui.footer
        ))}" style="${ssrRenderStyle(__props.ui.footerStyle)}">`);
        ssrRenderSlot(_ctx.$slots, "footer", {}, null, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$E = _sfc_main$E.setup;
_sfc_main$E.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/Card.vue");
  return _sfc_setup$E ? _sfc_setup$E(props, ctx) : void 0;
};
const _sfc_main$D = /* @__PURE__ */ defineComponent({
  __name: "InplaceInput",
  __ssrInlineRender: true,
  props: {
    modelValue: {},
    fieldName: { default: "name" },
    action: {},
    method: { default: "patch" },
    size: { default: "base" },
    weight: { default: "normal" },
    only: {},
    placeholder: { default: "Click to edit..." },
    ui: { default: () => ({}) }
  },
  emits: ["update:modelValue", "success", "error"],
  setup(__props, { emit: __emit }) {
    const sizeClasses = {
      sm: "text-sm",
      base: "text-base",
      lg: "text-lg",
      xl: "text-xl",
      "2xl": "text-2xl",
      "3xl": "text-3xl"
    };
    const weightClasses = {
      normal: "font-normal",
      medium: "font-medium",
      semibold: "font-semibold",
      bold: "font-bold"
    };
    const props = __props;
    const isEditing = ref(false);
    const inputRef = ref(null);
    const typographyClass = computed(() => {
      return `${sizeClasses[props.size]} ${weightClasses[props.weight]}`;
    });
    const computedInputUI = computed(() => {
      var _a, _b, _c;
      const baseInputClass = `${typographyClass.value} !bg-transparent !border-0 !border-b-2 !border-ui-primary !shadow-none !ring-0 !rounded-none !px-0 !py-0 focus:!ring-0 focus:!border-ui-primary`;
      return {
        ...(_a = props.ui) == null ? void 0 : _a.input,
        input: cn(baseInputClass, (_c = (_b = props.ui) == null ? void 0 : _b.input) == null ? void 0 : _c.input)
      };
    });
    const form = useForm({
      [props.fieldName]: props.modelValue
    });
    watch(() => props.modelValue, (val) => {
      if (!isEditing.value) {
        form[props.fieldName] = val;
      }
    });
    const startEditing = async () => {
      var _a;
      form[props.fieldName] = props.modelValue;
      form.clearErrors();
      isEditing.value = true;
      await nextTick();
      (_a = inputRef.value) == null ? void 0 : _a.focus();
    };
    const cancelEditing = () => {
      isEditing.value = false;
      form.reset();
      form.clearErrors();
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)("group relative flex items-center gap-2", __props.ui.root)
      }, _attrs))}>`);
      if (isEditing.value) {
        _push(`<form class="flex items-start gap-2 min-w-0 w-full">`);
        _push(ssrRenderComponent(_sfc_main$O, {
          ref_key: "inputRef",
          ref: inputRef,
          modelValue: unref(form)[props.fieldName],
          "onUpdate:modelValue": ($event) => unref(form)[props.fieldName] = $event,
          placeholder: __props.placeholder,
          error: unref(form).errors[props.fieldName],
          ui: computedInputUI.value,
          class: "flex-1 min-w-0",
          autofocus: ""
        }, null, _parent));
        _push(`<div class="flex items-center gap-1 mt-0.5">`);
        _push(ssrRenderComponent(Button, {
          type: "submit",
          variant: "primary",
          loading: unref(form).processing,
          ui: { root: unref(cn)("h-9 w-9 p-0", __props.ui.checkButton) },
          title: "Save (Enter)"
        }, {
          default: withCtx((_2, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(ssrRenderComponent(unref(Check), { class: "h-4 w-4" }, null, _parent2, _scopeId));
            } else {
              return [
                createVNode(unref(Check), { class: "h-4 w-4" })
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(ssrRenderComponent(Button, {
          type: "button",
          variant: "ghost",
          onClick: cancelEditing,
          ui: { root: unref(cn)("h-9 w-9 p-0", __props.ui.cancelButton) },
          title: "Cancel (Esc)",
          disabled: unref(form).processing
        }, {
          default: withCtx((_2, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(ssrRenderComponent(unref(X), { class: "h-4 w-4" }, null, _parent2, _scopeId));
            } else {
              return [
                createVNode(unref(X), { class: "h-4 w-4" })
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div></form>`);
      } else {
        _push(`<div class="flex items-center gap-2 group/view"><div role="button" tabindex="0" class="${ssrRenderClass([unref(cn)("truncate", typographyClass.value, __props.ui.text), "cursor-text"])}">`);
        ssrRenderSlot(_ctx.$slots, "default", {}, () => {
          _push(`${ssrInterpolate(__props.modelValue || __props.placeholder)}`);
        }, _push, _parent);
        _push(`</div>`);
        _push(ssrRenderComponent(Button, {
          type: "button",
          variant: "ghost",
          onClick: startEditing,
          ui: {
            root: unref(cn)(
              "h-8 w-8 p-0 opacity-0 group-hover/view:opacity-100 transition-opacity duration-200",
              isEditing.value ? "hidden" : "",
              __props.ui.editButton
            )
          },
          title: "Edit"
        }, {
          default: withCtx((_2, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(ssrRenderComponent(unref(Pencil), { class: "h-4 w-4 text-ui-muted-foreground hover:text-ui-foreground" }, null, _parent2, _scopeId));
            } else {
              return [
                createVNode(unref(Pencil), { class: "h-4 w-4 text-ui-muted-foreground hover:text-ui-foreground" })
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div>`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$D = _sfc_main$D.setup;
_sfc_main$D.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/InplaceInput.vue");
  return _sfc_setup$D ? _sfc_setup$D(props, ctx) : void 0;
};
const _sfc_main$C = /* @__PURE__ */ defineComponent({
  __name: "CollapsibleSection",
  __ssrInlineRender: true,
  props: {
    modelValue: { type: Boolean, default: void 0 },
    title: {},
    defaultOpen: { type: Boolean, default: true },
    collapsible: { type: Boolean, default: true },
    showHeader: { type: Boolean, default: true },
    icon: { default: void 0 },
    ui: { default: () => ({}) }
  },
  emits: ["update:modelValue"],
  setup(__props, { expose: __expose, emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const internalOpen = ref(props.modelValue !== void 0 ? props.modelValue : props.defaultOpen);
    const isOpen = computed({
      get: () => props.modelValue !== void 0 ? props.modelValue : internalOpen.value,
      set: (val) => {
        internalOpen.value = val;
        emit("update:modelValue", val);
      }
    });
    watch(() => props.modelValue, (val) => {
      if (val !== void 0) {
        internalOpen.value = val;
      }
    });
    const toggle = () => {
      if (!props.collapsible) {
        return;
      }
      isOpen.value = !isOpen.value;
    };
    __expose({ isOpen, toggle });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)(
          "border border-ui-border rounded-lg bg-ui-card overflow-hidden",
          __props.ui.root
        ),
        style: __props.ui.rootStyle
      }, _attrs))}>`);
      if (__props.showHeader) {
        _push(`<button type="button" class="${ssrRenderClass(unref(cn)(
          "w-full flex items-center justify-between p-4 bg-transparent border-none transition-colors duration-200",
          __props.collapsible ? "cursor-pointer hover:bg-ui-secondary" : "cursor-default",
          __props.ui.header
        ))}" style="${ssrRenderStyle(__props.ui.headerStyle)}"${ssrRenderAttr("aria-expanded", __props.collapsible ? isOpen.value : true)}${ssrRenderAttr("aria-disabled", !__props.collapsible)}><div class="flex items-center gap-2">`);
        ssrRenderSlot(_ctx.$slots, "header-prefix", {}, null, _push, _parent);
        if (__props.icon) {
          _push(ssrRenderComponent(_sfc_main$13, {
            icon: __props.icon,
            ui: {
              root: unref(cn)("h-5 w-5 text-ui-primary", __props.ui.headerIcon),
              rootStyle: __props.ui.headerIconStyle
            }
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`<span class="${ssrRenderClass(unref(cn)(
          "font-semibold text-ui-foreground",
          __props.ui.headerTitle
        ))}" style="${ssrRenderStyle(__props.ui.headerTitleStyle)}">${ssrInterpolate(__props.title)}</span></div>`);
        if (__props.collapsible) {
          _push(ssrRenderComponent(_sfc_main$13, {
            icon: isOpen.value ? "chevron-down" : "chevron-right",
            ui: {
              root: unref(cn)("h-5 w-5 text-ui-muted-foreground transition-transform duration-200", __props.ui.chevron),
              rootStyle: __props.ui.chevronStyle
            }
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="${ssrRenderClass(unref(cn)(
        "px-4 pb-4",
        __props.ui.content
      ))}" style="${ssrRenderStyle([
        __props.ui.contentStyle,
        (__props.showHeader ? __props.collapsible ? isOpen.value : true : true) ? null : { display: "none" }
      ])}">`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</div></div>`);
    };
  }
});
const _sfc_setup$C = _sfc_main$C.setup;
_sfc_main$C.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/CollapsibleSection.vue");
  return _sfc_setup$C ? _sfc_setup$C(props, ctx) : void 0;
};
const _sfc_main$B = /* @__PURE__ */ defineComponent({
  __name: "PageTransition",
  __ssrInlineRender: true,
  setup(__props) {
    const isNavigating = ref(false);
    let removeStartListener = void 0;
    let removeFinishListener = void 0;
    onMounted(() => {
      removeStartListener = router.on("start", () => {
        isNavigating.value = true;
      });
      removeFinishListener = router.on("finish", () => {
        isNavigating.value = false;
      });
    });
    onUnmounted(() => {
      if (removeStartListener) removeStartListener();
      if (removeFinishListener) removeFinishListener();
    });
    return (_ctx, _push, _parent, _attrs) => {
      ssrRenderTeleport(_push, (_push2) => {
        if (isNavigating.value) {
          _push2(`<div class="page-loader-overlay" data-v-52fe446a><div class="page-loader" data-v-52fe446a>`);
          _push2(ssrRenderComponent(unref(Loader2), { class: "loader-icon" }, null, _parent));
          _push2(`<span class="loader-text" data-v-52fe446a>Loading...</span></div></div>`);
        } else {
          _push2(`<!---->`);
        }
      }, "body", false, _parent);
    };
  }
});
const _sfc_setup$B = _sfc_main$B.setup;
_sfc_main$B.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/PageTransition.vue");
  return _sfc_setup$B ? _sfc_setup$B(props, ctx) : void 0;
};
const _sfc_main$A = /* @__PURE__ */ defineComponent({
  __name: "SearchableDropdown",
  __ssrInlineRender: true,
  props: {
    modelValue: { default: null },
    options: { default: () => [] },
    labelKey: { default: "name" },
    valueKey: { default: "id" },
    placeholder: { default: "Select an option..." },
    searchPlaceholder: { default: "Search..." },
    loading: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    clearable: { type: Boolean, default: true }
  },
  emits: ["update:modelValue", "search", "select"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const isOpen = ref(false);
    const searchQuery = ref("");
    const dropdownRef = ref(null);
    ref(null);
    const selectedOption = computed(() => {
      if (!props.modelValue) return null;
      const valueToFind = typeof props.modelValue === "object" ? props.modelValue[props.valueKey] : props.modelValue;
      return props.options.find((opt) => opt[props.valueKey] === valueToFind);
    });
    const displayLabel = computed(() => {
      return selectedOption.value ? selectedOption.value[props.labelKey] : "";
    });
    const filteredOptions = computed(() => {
      if (!searchQuery.value) return props.options;
      const query = searchQuery.value.toLowerCase();
      return props.options.filter(
        (opt) => {
          var _a;
          return (_a = opt[props.labelKey]) == null ? void 0 : _a.toLowerCase().includes(query);
        }
      );
    });
    const handleSearch = () => {
      emit("search", searchQuery.value);
    };
    const handleClickOutside = (event) => {
      if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        isOpen.value = false;
      }
    };
    onMounted(() => {
      document.addEventListener("click", handleClickOutside);
    });
    onUnmounted(() => {
      document.removeEventListener("click", handleClickOutside);
    });
    watch(searchQuery, handleSearch);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        ref_key: "dropdownRef",
        ref: dropdownRef,
        class: ["searchable-dropdown", { disabled: __props.disabled }]
      }, _attrs))} data-v-4785c4a8><button type="button" class="${ssrRenderClass([{ "is-open": isOpen.value }, "dropdown-trigger"])}" data-v-4785c4a8>`);
      if (selectedOption.value) {
        _push(`<span class="selected-value" data-v-4785c4a8>${ssrInterpolate(displayLabel.value)}</span>`);
      } else {
        _push(`<span class="placeholder" data-v-4785c4a8>${ssrInterpolate(__props.placeholder)}</span>`);
      }
      _push(`<div class="trigger-actions" data-v-4785c4a8>`);
      if (__props.clearable && selectedOption.value) {
        _push(`<button type="button" class="clear-btn" title="Clear" data-v-4785c4a8>`);
        _push(ssrRenderComponent(unref(X), { class: "h-4 w-4" }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(ssrRenderComponent(unref(ChevronDown), {
        class: ["chevron h-4 w-4", { "rotate-180": isOpen.value }]
      }, null, _parent));
      _push(`</div></button>`);
      if (isOpen.value) {
        _push(`<div class="dropdown-menu" data-v-4785c4a8><div class="search-box" data-v-4785c4a8>`);
        _push(ssrRenderComponent(unref(Search), { class: "search-icon h-4 w-4" }, null, _parent));
        _push(`<input${ssrRenderAttr("value", searchQuery.value)} type="text"${ssrRenderAttr("placeholder", __props.searchPlaceholder)} class="search-input" data-v-4785c4a8></div><div class="options-list" data-v-4785c4a8>`);
        if (__props.loading) {
          _push(`<div class="loading-state" data-v-4785c4a8><div class="spinner" data-v-4785c4a8></div><span data-v-4785c4a8>Loading...</span></div>`);
        } else if (filteredOptions.value.length === 0) {
          _push(`<div class="empty-state" data-v-4785c4a8> No options found </div>`);
        } else {
          _push(`<!--[-->`);
          ssrRenderList(filteredOptions.value, (option) => {
            var _a, _b;
            _push(`<button type="button" class="${ssrRenderClass([{ "is-selected": ((_a = selectedOption.value) == null ? void 0 : _a[__props.valueKey]) === option[__props.valueKey] }, "option-item"])}" data-v-4785c4a8><span data-v-4785c4a8>${ssrInterpolate(option[__props.labelKey])}</span>`);
            if (((_b = selectedOption.value) == null ? void 0 : _b[__props.valueKey]) === option[__props.valueKey]) {
              _push(ssrRenderComponent(unref(Check), { class: "h-4 w-4 text-primary" }, null, _parent));
            } else {
              _push(`<!---->`);
            }
            _push(`</button>`);
          });
          _push(`<!--]-->`);
        }
        _push(`</div></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$A = _sfc_main$A.setup;
_sfc_main$A.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/SearchableDropdown.vue");
  return _sfc_setup$A ? _sfc_setup$A(props, ctx) : void 0;
};
const _sfc_main$z = /* @__PURE__ */ defineComponent({
  __name: "SkeletonLoader",
  __ssrInlineRender: true,
  props: {
    variant: { default: "text" },
    width: { default: "100%" },
    height: { default: null },
    lines: { default: 1 },
    animated: { type: Boolean, default: true },
    withCircle: { type: Boolean, default: true }
  },
  setup(__props) {
    const getHeight = (variant) => {
      const heights = {
        text: "1rem",
        circle: "3rem",
        rect: "4rem",
        card: "12rem",
        stat: "6rem",
        row: "3.5rem",
        avatar: "2.5rem"
      };
      return heights[variant] || "1rem";
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "skeleton-container" }, _attrs))} data-v-37860098>`);
      if (__props.variant === "text") {
        _push(`<!--[-->`);
        ssrRenderList(__props.lines, (i2) => {
          _push(`<div class="${ssrRenderClass([{ "skeleton-animated": __props.animated }, "skeleton skeleton-text"])}" style="${ssrRenderStyle({
            width: i2 === __props.lines && __props.lines > 1 ? "70%" : __props.width,
            height: __props.height || getHeight(__props.variant)
          })}" data-v-37860098></div>`);
        });
        _push(`<!--]-->`);
      } else if (__props.variant === "circle" || __props.variant === "avatar") {
        _push(`<div class="${ssrRenderClass([{ "skeleton-animated": __props.animated }, "skeleton skeleton-circle"])}" style="${ssrRenderStyle({
          width: __props.width === "100%" ? getHeight(__props.variant) : __props.width,
          height: __props.height || getHeight(__props.variant)
        })}" data-v-37860098></div>`);
      } else if (__props.variant === "rect") {
        _push(`<div class="${ssrRenderClass([{ "skeleton-animated": __props.animated }, "skeleton skeleton-rect"])}" style="${ssrRenderStyle({ width: __props.width, height: __props.height || getHeight(__props.variant) })}" data-v-37860098></div>`);
      } else if (__props.variant === "stat") {
        _push(`<div class="skeleton-stat" data-v-37860098><div class="flex items-start justify-between" data-v-37860098><div class="flex-1" data-v-37860098><div class="skeleton skeleton-text skeleton-animated" style="${ssrRenderStyle({ "width": "60%", "height": "0.75rem" })}" data-v-37860098></div><div class="skeleton skeleton-text skeleton-animated mt-3" style="${ssrRenderStyle({ "width": "40%", "height": "2rem" })}" data-v-37860098></div><div class="skeleton skeleton-text skeleton-animated mt-2" style="${ssrRenderStyle({ "width": "80%", "height": "0.625rem" })}" data-v-37860098></div></div><div class="skeleton skeleton-rect skeleton-animated" style="${ssrRenderStyle({ "width": "2.5rem", "height": "2.5rem", "border-radius": "0.5rem" })}" data-v-37860098></div></div><div class="skeleton skeleton-text skeleton-animated mt-4" style="${ssrRenderStyle({ "width": "100%", "height": "3px" })}" data-v-37860098></div></div>`);
      } else if (__props.variant === "card") {
        _push(`<div class="skeleton-card" data-v-37860098><div class="skeleton skeleton-rect skeleton-animated" style="${ssrRenderStyle({ "width": "100%", "height": "8rem", "border-radius": "0.5rem 0.5rem 0 0" })}" data-v-37860098></div><div class="p-4" data-v-37860098><div class="skeleton skeleton-text skeleton-animated" style="${ssrRenderStyle({ "width": "70%", "height": "1.25rem" })}" data-v-37860098></div><div class="skeleton skeleton-text skeleton-animated mt-2" style="${ssrRenderStyle({ "width": "100%", "height": "0.875rem" })}" data-v-37860098></div><div class="skeleton skeleton-text skeleton-animated mt-1" style="${ssrRenderStyle({ "width": "85%", "height": "0.875rem" })}" data-v-37860098></div></div></div>`);
      } else if (__props.variant === "row") {
        _push(`<div class="skeleton-row" data-v-37860098><div class="flex items-center gap-4 flex-1" data-v-37860098>`);
        if (__props.withCircle) {
          _push(`<div class="skeleton skeleton-circle skeleton-animated" style="${ssrRenderStyle({ "width": "2rem", "height": "2rem" })}" data-v-37860098></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<div class="flex-1" data-v-37860098><div class="skeleton skeleton-text skeleton-animated" style="${ssrRenderStyle({ "width": "60%", "height": "0.875rem" })}" data-v-37860098></div><div class="skeleton skeleton-text skeleton-animated mt-1" style="${ssrRenderStyle({ "width": "40%", "height": "0.625rem" })}" data-v-37860098></div></div></div><div class="skeleton skeleton-rect skeleton-animated" style="${ssrRenderStyle({ "width": "5rem", "height": "1.5rem", "border-radius": "9999px" })}" data-v-37860098></div></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$z = _sfc_main$z.setup;
_sfc_main$z.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/SkeletonLoader.vue");
  return _sfc_setup$z ? _sfc_setup$z(props, ctx) : void 0;
};
const SkeletonLoader = /* @__PURE__ */ _export_sfc(_sfc_main$z, [["__scopeId", "data-v-37860098"]]);
const _sfc_main$y = /* @__PURE__ */ defineComponent({
  __name: "StatusBadge",
  __ssrInlineRender: true,
  props: {
    status: { default: "pending" },
    label: {},
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const statusConfig = computed(() => {
      var _a;
      switch ((_a = props.status) == null ? void 0 : _a.toLowerCase()) {
        case "processing":
          return {
            bg: "bg-[rgb(var(--ui-warning)/0.1)]",
            text: "text-[rgb(var(--ui-warning))]",
            icon: Loader2,
            spin: true,
            defaultLabel: "Processing"
          };
        case "completed":
        case "complete":
          return {
            bg: "bg-[rgb(var(--ui-success)/0.1)]",
            text: "text-[rgb(var(--ui-success))]",
            icon: CheckCircle2,
            spin: false,
            defaultLabel: "Completed"
          };
        case "error":
        case "failed":
          return {
            bg: "bg-[rgb(var(--ui-destructive)/0.1)]",
            text: "text-[rgb(var(--ui-destructive))]",
            icon: XCircle,
            spin: false,
            defaultLabel: "Error"
          };
        case "pending":
        default:
          return {
            bg: "bg-[rgb(var(--ui-muted-foreground)/0.1)]",
            text: "text-[rgb(var(--ui-muted-foreground))]",
            icon: Circle,
            spin: false,
            defaultLabel: "Pending"
          };
      }
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        role: "status",
        "aria-label": __props.label || statusConfig.value.defaultLabel,
        class: unref(cn)(
          "inline-flex items-center gap-2 px-2.5 py-1 rounded-md",
          statusConfig.value.bg,
          statusConfig.value.text,
          __props.ui.root
        ),
        style: __props.ui.rootStyle
      }, _attrs))}>`);
      ssrRenderVNode(_push, createVNode(resolveDynamicComponent(statusConfig.value.icon), {
        class: unref(cn)(
          "h-4 w-4",
          statusConfig.value.spin ? "animate-spin" : "",
          __props.ui.icon
        ),
        style: __props.ui.iconStyle
      }, null), _parent);
      _push(`<span class="${ssrRenderClass(unref(cn)(
        "text-xs font-medium uppercase tracking-wide",
        __props.ui.label
      ))}" style="${ssrRenderStyle(__props.ui.labelStyle)}">${ssrInterpolate(__props.label || statusConfig.value.defaultLabel)}</span></div>`);
    };
  }
});
const _sfc_setup$y = _sfc_main$y.setup;
_sfc_main$y.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/StatusBadge.vue");
  return _sfc_setup$y ? _sfc_setup$y(props, ctx) : void 0;
};
const _sfc_main$x = /* @__PURE__ */ defineComponent({
  __name: "StatusDot",
  __ssrInlineRender: true,
  props: {
    color: { default: "neutral" },
    size: { default: "md" },
    pulse: { type: Boolean, default: false },
    bordered: { type: Boolean, default: false },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const bgColorClass = computed(() => {
      const colorMap = {
        success: "bg-[rgb(var(--ui-success))]",
        error: "bg-[rgb(var(--ui-destructive))]",
        warning: "bg-[rgb(var(--ui-warning))]",
        info: "bg-[rgb(var(--ui-info))]",
        neutral: "bg-[rgb(var(--ui-muted-foreground))]"
      };
      return colorMap[props.color];
    });
    const sizeClass = computed(() => {
      const sizeMap = {
        sm: "w-2 h-2",
        md: "w-2.5 h-2.5",
        lg: "w-3.5 h-3.5"
      };
      return sizeMap[props.size];
    });
    return (_ctx, _push, _parent, _attrs) => {
      if (__props.pulse) {
        _push(`<span${ssrRenderAttrs(mergeProps({
          role: "status",
          "aria-label": `${__props.color} status`,
          class: unref(cn)("relative inline-flex", __props.ui.root),
          style: __props.ui.rootStyle
        }, _attrs))}><span class="${ssrRenderClass(unref(cn)(
          "animate-ping absolute inline-flex h-full w-full rounded-full opacity-75",
          bgColorClass.value
        ))}"></span><span class="${ssrRenderClass(unref(cn)(
          "relative inline-flex rounded-full",
          sizeClass.value,
          bgColorClass.value,
          __props.bordered ? "border border-current" : "",
          __props.ui.dot
        ))}" style="${ssrRenderStyle(__props.ui.dotStyle)}"></span></span>`);
      } else {
        _push(`<span${ssrRenderAttrs(mergeProps({
          role: "status",
          "aria-label": `${__props.color} status`,
          class: unref(cn)(
            "inline-flex rounded-full",
            sizeClass.value,
            bgColorClass.value,
            __props.bordered ? "border border-current" : "",
            __props.ui.root,
            __props.ui.dot
          ),
          style: __props.ui.rootStyle ?? __props.ui.dotStyle
        }, _attrs))}></span>`);
      }
    };
  }
});
const _sfc_setup$x = _sfc_main$x.setup;
_sfc_main$x.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/StatusDot.vue");
  return _sfc_setup$x ? _sfc_setup$x(props, ctx) : void 0;
};
const pendingToasts = ref([]);
const _sfc_main$w = /* @__PURE__ */ defineComponent({
  __name: "ToastNotification",
  __ssrInlineRender: true,
  setup(__props, { expose: __expose }) {
    const toasts = ref([]);
    let toastId = 0;
    const recentMessages = /* @__PURE__ */ new Set();
    const page = usePage();
    const teleportTarget = ref("body");
    let observer = null;
    const updateTeleportTarget = () => {
      if (typeof window === "undefined") return;
      const openDialogs = document.querySelectorAll("dialog[open]");
      const topMostDialog = openDialogs.length > 0 ? openDialogs.item(openDialogs.length - 1) : null;
      teleportTarget.value = topMostDialog ?? "body";
    };
    const icons = {
      success: CheckCircle,
      error: XCircle,
      warning: AlertTriangle,
      info: Info
    };
    const isDuplicate = (message, type) => {
      const key = `${type}:${message}`;
      if (recentMessages.has(key)) return true;
      recentMessages.add(key);
      setTimeout(() => recentMessages.delete(key), 1e3);
      return false;
    };
    const addToast = (message, type = "success", duration = 4e3) => {
      if (isDuplicate(message, type)) return;
      const id = ++toastId;
      toasts.value.push({ id, message, type, visible: true });
      setTimeout(() => {
        removeToast(id);
      }, duration);
    };
    const removeToast = (id) => {
      const index = toasts.value.findIndex((t3) => t3.id === id);
      if (index > -1) {
        toasts.value[index].visible = false;
        setTimeout(() => {
          toasts.value = toasts.value.filter((t3) => t3.id !== id);
        }, 300);
      }
    };
    watch(() => page.props.flash, (flash) => {
      if (flash == null ? void 0 : flash.success) {
        addToast(flash.success, "success");
      }
      if (flash == null ? void 0 : flash.error) {
        addToast(flash.error, "error");
      }
      if (flash == null ? void 0 : flash.warning) {
        addToast(flash.warning, "warning");
      }
      if (flash == null ? void 0 : flash.info) {
        addToast(flash.info, "info");
      }
    }, { immediate: true, deep: true });
    watch(() => page.props.errors, (errors) => {
      if (!errors || typeof errors !== "object") return;
      Object.values(errors).forEach((msg) => {
        if (msg) addToast(String(msg), "error", 6e3);
      });
    }, { deep: true });
    watch(pendingToasts, (queue) => {
      while (queue.length > 0) {
        const item = queue.shift();
        addToast(item.message, item.type, item.duration);
      }
    }, { deep: true });
    __expose({ addToast });
    onMounted(() => {
      updateTeleportTarget();
      observer = new MutationObserver(() => {
        updateTeleportTarget();
      });
      observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ["open"]
      });
    });
    onUnmounted(() => {
      observer == null ? void 0 : observer.disconnect();
      observer = null;
    });
    return (_ctx, _push, _parent, _attrs) => {
      ssrRenderTeleport(_push, (_push2) => {
        _push2(`<div class="toast-container" data-v-25965b49><!--[-->`);
        ssrRenderList(toasts.value, (toast) => {
          _push2(`<div class="${ssrRenderClass([[`toast-${toast.type}`, { "toast-exit": !toast.visible }], "toast"])}" data-v-25965b49><div class="toast-icon" data-v-25965b49>`);
          ssrRenderVNode(_push2, createVNode(resolveDynamicComponent(icons[toast.type]), { class: "h-5 w-5" }, null), _parent);
          _push2(`</div><p class="toast-message" data-v-25965b49>${ssrInterpolate(toast.message)}</p><button class="toast-close" data-v-25965b49>`);
          _push2(ssrRenderComponent(unref(X), { class: "h-4 w-4" }, null, _parent));
          _push2(`</button><div class="${ssrRenderClass([`progress-${toast.type}`, "toast-progress"])}" data-v-25965b49></div></div>`);
        });
        _push2(`<!--]--></div>`);
      }, teleportTarget.value, false, _parent);
    };
  }
});
const _sfc_setup$w = _sfc_main$w.setup;
_sfc_main$w.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/ToastNotification.vue");
  return _sfc_setup$w ? _sfc_setup$w(props, ctx) : void 0;
};
const ToastNotification = /* @__PURE__ */ _export_sfc(_sfc_main$w, [["__scopeId", "data-v-25965b49"]]);
const _sfc_main$v = /* @__PURE__ */ defineComponent({
  __name: "Loader",
  __ssrInlineRender: true,
  props: {
    size: { default: "md" },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const sizeClasses = computed(() => {
      const sizes = {
        xs: "w-3 h-3",
        sm: "w-4 h-4",
        md: "w-6 h-6",
        lg: "w-8 h-8",
        xl: "w-12 h-12"
      };
      return sizes[props.size] || sizes.md;
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)("inline-flex items-center justify-center", __props.ui.root),
        style: __props.ui.rootStyle
      }, _attrs))}>`);
      _push(ssrRenderComponent(unref(Loader2), {
        class: unref(cn)("animate-spin text-ui-muted-foreground", sizeClasses.value, __props.ui.icon),
        style: __props.ui.iconStyle
      }, null, _parent));
      _push(`</div>`);
    };
  }
});
const _sfc_setup$v = _sfc_main$v.setup;
_sfc_main$v.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/Loader.vue");
  return _sfc_setup$v ? _sfc_setup$v(props, ctx) : void 0;
};
const _sfc_main$u = /* @__PURE__ */ defineComponent({
  __name: "Tabs",
  __ssrInlineRender: true,
  props: {
    modelValue: { default: void 0 },
    items: { default: () => [] },
    variant: { default: "underlined" },
    grow: { type: Boolean, default: false },
    centered: { type: Boolean, default: false },
    vertical: { type: Boolean, default: false },
    ui: { default: () => ({}) }
  },
  emits: ["update:modelValue", "tab-change"],
  setup(__props, { expose: __expose, emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const tabListRef = ref(null);
    const tabRefs = ref([]);
    const indicatorStyle = ref({});
    const getInitialTab = () => {
      var _a;
      if (props.modelValue !== void 0) return props.modelValue;
      const firstEnabled = props.items.find((item) => !item.disabled);
      return (firstEnabled == null ? void 0 : firstEnabled.value) ?? ((_a = props.items[0]) == null ? void 0 : _a.value);
    };
    const internalValue = ref(getInitialTab());
    const activeValue = computed({
      get: () => props.modelValue !== void 0 ? props.modelValue : internalValue.value,
      set: (val) => {
        const oldVal = activeValue.value;
        internalValue.value = val;
        emit("update:modelValue", val);
        if (val !== oldVal) {
          emit("tab-change", val, oldVal);
        }
      }
    });
    provide("tabs-active-value", activeValue);
    provide("tabs-ui", computed(() => props.ui));
    watch(() => props.modelValue, (val) => {
      if (val !== void 0) {
        internalValue.value = val;
      }
    });
    const updateIndicator = async () => {
      await nextTick();
      const activeIndex = props.items.findIndex((item) => item.value === activeValue.value);
      const activeTab = tabRefs.value[activeIndex];
      if (!activeTab || !tabListRef.value) {
        indicatorStyle.value = { opacity: "0" };
        return;
      }
      if (props.vertical) {
        indicatorStyle.value = {
          top: `${activeTab.offsetTop}px`,
          height: `${activeTab.offsetHeight}px`,
          left: props.variant === "underlined" ? "auto" : "0",
          right: props.variant === "underlined" ? "0" : "auto",
          width: props.variant === "underlined" ? "2px" : "100%",
          opacity: "1"
        };
      } else {
        indicatorStyle.value = {
          left: `${activeTab.offsetLeft}px`,
          width: `${activeTab.offsetWidth}px`,
          bottom: props.variant === "underlined" ? "0" : "auto",
          top: props.variant === "underlined" ? "auto" : "0",
          height: props.variant === "underlined" ? "2px" : "100%",
          opacity: "1"
        };
      }
    };
    watch(activeValue, updateIndicator);
    watch(() => props.items, updateIndicator, { deep: true });
    watch(() => props.vertical, updateIndicator);
    watch(() => props.variant, updateIndicator);
    onMounted(() => {
      updateIndicator();
      window.addEventListener("resize", updateIndicator);
    });
    const selectTab = (item) => {
      if (!item.disabled) {
        activeValue.value = item.value;
      }
    };
    const variantClasses = computed(() => {
      switch (props.variant) {
        case "pills":
          return {
            tabList: "bg-ui-muted/50 rounded-lg p-1 gap-1",
            tab: "rounded-md px-4 py-2",
            tabActive: "bg-ui-card text-ui-foreground shadow-sm",
            tabInactive: "text-ui-muted-foreground hover:text-ui-foreground hover:bg-ui-muted/50",
            indicator: "bg-ui-card rounded-md shadow-sm"
          };
        case "bordered":
          return {
            tabList: "border-b-2 border-ui-border gap-0",
            tab: "px-5 py-3 border-b-[3px] -mb-[2px] font-semibold",
            tabActive: "border-ui-primary text-ui-primary bg-ui-primary/5",
            tabInactive: "border-transparent text-ui-muted-foreground hover:text-ui-foreground hover:border-ui-muted-foreground/50 hover:bg-ui-muted/20",
            indicator: "hidden"
          };
        case "enclosed":
          return {
            tabList: "border-b border-ui-border gap-0",
            tab: "px-4 py-2 border border-transparent rounded-t-lg -mb-px",
            tabActive: "bg-ui-card border-ui-border border-b-ui-card text-ui-foreground",
            tabInactive: "text-ui-muted-foreground hover:text-ui-foreground hover:bg-ui-muted/30",
            indicator: "hidden"
          };
        case "underlined":
        default:
          return {
            tabList: "border-b border-ui-border/50 gap-2",
            tab: "px-4 py-3 -mb-px",
            tabActive: "text-ui-primary",
            tabInactive: "text-ui-muted-foreground hover:text-ui-foreground",
            indicator: "bg-ui-primary h-0.5 bottom-0"
          };
      }
    });
    const showIndicator = computed(
      () => props.variant === "underlined" || props.variant === "pills"
    );
    __expose({
      activeValue,
      selectTab,
      updateIndicator
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)(
          "w-full",
          __props.vertical && "flex gap-4",
          __props.ui.root
        ),
        style: __props.ui.rootStyle
      }, _attrs))}><div role="tablist"${ssrRenderAttr("aria-orientation", __props.vertical ? "vertical" : "horizontal")} class="${ssrRenderClass(unref(cn)(
        "relative flex",
        __props.vertical ? "flex-col" : "flex-row",
        __props.centered && !__props.vertical && "justify-center",
        variantClasses.value.tabList,
        __props.ui.tabList
      ))}" style="${ssrRenderStyle(__props.ui.tabListStyle)}">`);
      if (showIndicator.value) {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "absolute transition-all duration-300 ease-out pointer-events-none z-0",
          variantClasses.value.indicator,
          __props.ui.indicator
        ))}" style="${ssrRenderStyle({ ...indicatorStyle.value, ...typeof __props.ui.indicatorStyle === "object" ? __props.ui.indicatorStyle : {} })}" aria-hidden="true"></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<!--[-->`);
      ssrRenderList(__props.items, (item, index) => {
        _push(`<button type="button" role="tab"${ssrRenderAttr("id", `tab-${item.value}`)}${ssrRenderAttr("aria-selected", activeValue.value === item.value)}${ssrRenderAttr("aria-controls", `panel-${item.value}`)}${ssrRenderAttr("tabindex", activeValue.value === item.value ? 0 : -1)}${ssrIncludeBooleanAttr(item.disabled) ? " disabled" : ""} class="${ssrRenderClass(unref(cn)(
          "relative z-10 flex items-center gap-2 font-medium transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-ring focus-visible:ring-offset-2 focus-visible:ring-offset-ui-background",
          __props.grow && "flex-1 justify-center",
          variantClasses.value.tab,
          activeValue.value === item.value ? unref(cn)(variantClasses.value.tabActive, __props.ui.tabActive) : unref(cn)(variantClasses.value.tabInactive, __props.ui.tabInactive),
          item.disabled && unref(cn)(
            "opacity-50 cursor-not-allowed",
            __props.ui.tabDisabled
          ),
          __props.ui.tab
        ))}" style="${ssrRenderStyle([
          __props.ui.tabStyle,
          activeValue.value === item.value ? __props.ui.tabActiveStyle : __props.ui.tabInactiveStyle,
          item.disabled ? __props.ui.tabDisabledStyle : void 0
        ])}">`);
        if (item.icon) {
          _push(ssrRenderComponent(_sfc_main$13, {
            icon: item.icon,
            ui: {
              root: unref(cn)("w-4 h-4 shrink-0", __props.ui.tabIcon),
              rootStyle: __props.ui.tabIconStyle
            }
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`<span>${ssrInterpolate(item.label)}</span></button>`);
      });
      _push(`<!--]--></div><div class="${ssrRenderClass(unref(cn)(
        "flex-1",
        __props.ui.panels
      ))}" style="${ssrRenderStyle(__props.ui.panelsStyle)}">`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</div></div>`);
    };
  }
});
const _sfc_setup$u = _sfc_main$u.setup;
_sfc_main$u.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/Tabs.vue");
  return _sfc_setup$u ? _sfc_setup$u(props, ctx) : void 0;
};
const _sfc_main$t = /* @__PURE__ */ defineComponent({
  __name: "TabPanel",
  __ssrInlineRender: true,
  props: {
    value: {},
    lazy: { type: Boolean, default: false },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const activeValue = inject("tabs-active-value");
    const isActive = computed(() => (activeValue == null ? void 0 : activeValue.value) === props.value);
    const hasBeenActive = ref(isActive.value);
    watch(isActive, (active) => {
      if (active && !hasBeenActive.value) {
        hasBeenActive.value = true;
      }
    });
    const shouldRender = computed(() => {
      if (!props.lazy) return true;
      return hasBeenActive.value;
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        role: "tabpanel",
        id: `panel-${__props.value}`,
        "aria-labelledby": `tab-${__props.value}`,
        tabindex: isActive.value ? 0 : -1,
        class: unref(cn)(
          "focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ui-ring focus-visible:ring-offset-2",
          __props.ui.root
        ),
        style: __props.ui.rootStyle
      }, _attrs, {
        style: isActive.value ? null : { display: "none" }
      }))}>`);
      if (shouldRender.value) {
        ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$t = _sfc_main$t.setup;
_sfc_main$t.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/TabPanel.vue");
  return _sfc_setup$t ? _sfc_setup$t(props, ctx) : void 0;
};
const _sfc_main$s = /* @__PURE__ */ defineComponent({
  __name: "EventLogItem",
  __ssrInlineRender: true,
  props: {
    log: {},
    typeConfigs: {},
    ui: {}
  },
  setup(__props) {
    const props = __props;
    const getTypeConfig = (type) => {
      if (!type) return { bg: "bg-ui-muted/20", border: "border-ui-border", text: "text-ui-foreground" };
      return props.typeConfigs[type] || { bg: "bg-ui-muted/20", border: "border-ui-border", text: "text-ui-foreground" };
    };
    const config = computed(() => getTypeConfig(props.log.type));
    return (_ctx, _push, _parent, _attrs) => {
      var _a;
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)(
          "p-3 rounded-lg border backdrop-blur-sm transition-all",
          config.value.bg,
          config.value.border,
          config.value.text,
          (_a = __props.ui) == null ? void 0 : _a.item
        )
      }, _attrs))}><div class="flex justify-between items-center opacity-50 mb-1 text-[10px] uppercase tracking-wider"><span>${ssrInterpolate(__props.log.type || __props.log.name)}</span><span>${ssrInterpolate(__props.log.time)}</span></div><div class="break-words">${ssrInterpolate(__props.log.text || __props.log.data)}</div></div>`);
    };
  }
});
const _sfc_setup$s = _sfc_main$s.setup;
_sfc_main$s.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/EventLogItem.vue");
  return _sfc_setup$s ? _sfc_setup$s(props, ctx) : void 0;
};
const _sfc_main$r = /* @__PURE__ */ defineComponent({
  __name: "EventLog",
  __ssrInlineRender: true,
  props: {
    /** Array of log entries */
    logs: {
      type: Array,
      default: () => []
    },
    /** Title displayed in header */
    title: {
      type: String,
      default: "Event Log"
    },
    /** Height of the component */
    height: {
      type: String,
      default: "400px"
    },
    /** Enable sticky positioning */
    sticky: {
      type: Boolean,
      default: false
    },
    /** Sticky top offset */
    stickyTop: {
      type: String,
      default: "2rem"
    },
    /** Show detach button */
    detachable: {
      type: Boolean,
      default: true
    },
    /** Type configs for colored entries */
    typeConfigs: {
      type: Object,
      default: () => ({
        public: {
          bg: "bg-indigo-900/20",
          border: "border-indigo-500/30",
          text: "text-indigo-300"
        },
        private: {
          bg: "bg-purple-900/20",
          border: "border-purple-500/30",
          text: "text-purple-300"
        },
        success: {
          bg: "bg-green-900/20",
          border: "border-green-500/30",
          text: "text-green-300"
        },
        error: {
          bg: "bg-red-900/20",
          border: "border-red-500/30",
          text: "text-red-300"
        },
        warning: {
          bg: "bg-yellow-900/20",
          border: "border-yellow-500/30",
          text: "text-yellow-300"
        },
        info: {
          bg: "bg-blue-900/20",
          border: "border-blue-500/30",
          text: "text-blue-300"
        }
      })
    },
    /** UI injection */
    ui: {
      type: Object,
      default: () => ({})
    }
  },
  emits: ["clear", "detach", "attach"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const isDetached = ref(false);
    const detachedWindow = ref(null);
    const detachedBody = ref(null);
    const sortedLogs = computed(() => {
      return [...props.logs].sort((a2, b2) => {
        if (typeof a2.id === "number" && typeof b2.id === "number") {
          return a2.id - b2.id;
        }
        return 0;
      });
    });
    const contentRef = ref(null);
    const detachedContentRef = ref(null);
    const scrollToBottom = () => {
      nextTick(() => {
        if (contentRef.value) {
          contentRef.value.scrollTop = contentRef.value.scrollHeight;
        }
        if (detachedContentRef.value) {
          detachedContentRef.value.scrollTop = detachedContentRef.value.scrollHeight;
        }
      });
    };
    watch(() => props.logs, () => {
      scrollToBottom();
    }, { deep: true });
    const copyStylesToPopup = (popup) => {
      const styles = document.querySelectorAll('style, link[rel="stylesheet"]');
      styles.forEach((style) => {
        const clone = style.cloneNode(true);
        popup.document.head.appendChild(clone);
      });
      popup.document.documentElement.className = document.documentElement.className;
      const dataTheme = document.documentElement.getAttribute("data-theme");
      if (dataTheme) {
        popup.document.documentElement.setAttribute("data-theme", dataTheme);
      }
    };
    const detach = async () => {
      if (isDetached.value) return;
      const popup = window.open(
        "",
        "EventLog_Detached",
        "width=600,height=800,resizable=yes,scrollbars=yes"
      );
      if (!popup) {
        console.error("Failed to open popup window. Please allow popups.");
        return;
      }
      detachedWindow.value = popup;
      popup.document.title = `${props.title} - Detached`;
      copyStylesToPopup(popup);
      detachedBody.value = popup.document.body;
      isDetached.value = true;
      scrollToBottom();
      const checkClosed = setInterval(() => {
        if (popup.closed) {
          clearInterval(checkClosed);
          attach();
        }
      }, 500);
      window.addEventListener("beforeunload", () => {
        popup.close();
      });
      emit("detach");
    };
    const attach = () => {
      if (!isDetached.value) return;
      if (detachedWindow.value && !detachedWindow.value.closed) {
        detachedWindow.value.close();
      }
      detachedWindow.value = null;
      detachedBody.value = null;
      isDetached.value = false;
      scrollToBottom();
      emit("attach");
    };
    onUnmounted(() => {
      attach();
    });
    const clearLogs = () => {
      emit("clear");
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)(
          "bg-ui-card border border-ui-border rounded-xl overflow-hidden flex flex-col",
          __props.sticky && "sticky",
          __props.ui.root
        ),
        style: {
          height: __props.height,
          top: __props.sticky ? __props.stickyTop : void 0
        }
      }, _attrs))} data-v-df7d4fb3><div class="${ssrRenderClass(unref(cn)(
        "px-4 py-3 bg-ui-muted/30 border-b border-ui-border flex justify-between items-center shrink-0",
        __props.ui.header
      ))}" data-v-df7d4fb3><h3 class="${ssrRenderClass(unref(cn)("text-sm font-semibold text-ui-foreground", __props.ui.title))}" data-v-df7d4fb3>${ssrInterpolate(__props.title)}</h3><div class="flex items-center gap-2" data-v-df7d4fb3>`);
      if (__props.detachable && !isDetached.value) {
        _push(ssrRenderComponent(Button, {
          onClick: detach,
          variant: "ghost",
          size: "sm",
          class: unref(cn)("h-7 px-2 text-xs", __props.ui.detachButton),
          title: "Open in new window"
        }, {
          default: withCtx((_2, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<div class="flex items-center" data-v-df7d4fb3${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$13, {
                icon: "external-link",
                class: "w-3.5 h-3.5 mr-1"
              }, null, _parent2, _scopeId));
              _push2(`<span class="hidden sm:inline" data-v-df7d4fb3${_scopeId}>Detach</span></div>`);
            } else {
              return [
                createVNode("div", { class: "flex items-center" }, [
                  createVNode(_sfc_main$13, {
                    icon: "external-link",
                    class: "w-3.5 h-3.5 mr-1"
                  }),
                  createVNode("span", { class: "hidden sm:inline" }, "Detach")
                ])
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<!---->`);
      }
      if (isDetached.value) {
        _push(ssrRenderComponent(Button, {
          onClick: attach,
          variant: "ghost",
          size: "sm",
          color: "primary",
          class: unref(cn)("h-7 px-2 text-xs", __props.ui.detachButton),
          title: "Close popup and return here"
        }, {
          default: withCtx((_2, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(ssrRenderComponent(_sfc_main$13, {
                icon: "x",
                class: "w-3.5 h-3.5 mr-1"
              }, null, _parent2, _scopeId));
              _push2(`<span data-v-df7d4fb3${_scopeId}>Close Popup</span>`);
            } else {
              return [
                createVNode(_sfc_main$13, {
                  icon: "x",
                  class: "w-3.5 h-3.5 mr-1"
                }),
                createVNode("span", null, "Close Popup")
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(ssrRenderComponent(Button, {
        onClick: clearLogs,
        variant: "ghost",
        size: "sm",
        class: unref(cn)("h-7 px-2 text-xs", __props.ui.clearButton)
      }, {
        default: withCtx((_2, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` Clear `);
          } else {
            return [
              createTextVNode(" Clear ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></div>`);
      if (!isDetached.value) {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "flex-1 overflow-y-auto p-4 space-y-3 font-mono text-xs",
          __props.ui.content
        ))}" data-v-df7d4fb3>`);
        if (__props.logs.length === 0) {
          _push(`<div class="${ssrRenderClass(unref(cn)(
            "text-center text-ui-muted-foreground/50 py-12 italic flex flex-col items-center gap-2",
            __props.ui.empty
          ))}" data-v-df7d4fb3><div class="w-2 h-2 rounded-full bg-ui-muted-foreground/30 animate-pulse" data-v-df7d4fb3></div><span data-v-df7d4fb3>No events received yet...</span></div>`);
        } else {
          _push(`<!--[-->`);
          ssrRenderList(sortedLogs.value, (log) => {
            _push(ssrRenderComponent(_sfc_main$s, {
              key: log.id || log.time + log.text,
              log,
              typeConfigs: __props.typeConfigs,
              ui: { item: __props.ui.item }
            }, null, _parent));
          });
          _push(`<!--]-->`);
        }
        _push(`</div>`);
      } else {
        _push(`<div class="flex-1 flex flex-col items-center justify-center text-ui-muted-foreground gap-3 p-6" data-v-df7d4fb3>`);
        _push(ssrRenderComponent(_sfc_main$13, {
          icon: "external-link",
          class: "w-8 h-8 opacity-50"
        }, null, _parent));
        _push(`<p class="text-sm" data-v-df7d4fb3>Logs displayed in popup window</p><div class="text-xs opacity-75 flex items-center gap-2" data-v-df7d4fb3><span class="w-2 h-2 rounded-full bg-green-500 animate-pulse" data-v-df7d4fb3></span> Live Sync Active </div>`);
        _push(ssrRenderComponent(Button, {
          onClick: attach,
          variant: "link",
          size: "sm",
          class: "text-xs"
        }, {
          default: withCtx((_2, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(` Click to bring back here `);
            } else {
              return [
                createTextVNode(" Click to bring back here ")
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div>`);
      }
      if (isDetached.value && detachedBody.value) {
        ssrRenderTeleport(_push, (_push2) => {
          _push2(`<div class="h-screen flex flex-col bg-ui-background text-ui-foreground font-sans" data-v-df7d4fb3><div class="px-4 py-3 bg-ui-card border-b border-ui-border flex justify-between items-center shrink-0" data-v-df7d4fb3><h3 class="text-sm font-semibold flex items-center gap-2" data-v-df7d4fb3>${ssrInterpolate(__props.title)} <span class="text-[10px] uppercase bg-ui-muted px-1.5 py-0.5 rounded text-ui-muted-foreground" data-v-df7d4fb3>Detached</span></h3><div class="flex items-center gap-2" data-v-df7d4fb3>`);
          _push2(ssrRenderComponent(Button, {
            onClick: clearLogs,
            variant: "ghost",
            size: "sm",
            class: "h-7 px-2 text-xs"
          }, {
            default: withCtx((_2, _push3, _parent2, _scopeId) => {
              if (_push3) {
                _push3(` Clear `);
              } else {
                return [
                  createTextVNode(" Clear ")
                ];
              }
            }),
            _: 1
          }, _parent));
          _push2(`</div></div><div class="flex-1 overflow-y-auto p-4 space-y-3 font-mono text-xs bg-ui-background" data-v-df7d4fb3>`);
          if (__props.logs.length === 0) {
            _push2(`<div class="text-center text-ui-muted-foreground/50 py-12 italic" data-v-df7d4fb3> No events received yet... </div>`);
          } else {
            _push2(`<!---->`);
          }
          _push2(`<!--[-->`);
          ssrRenderList(sortedLogs.value, (log) => {
            _push2(ssrRenderComponent(_sfc_main$s, {
              key: log.id || log.time + log.text,
              log,
              typeConfigs: __props.typeConfigs,
              ui: { item: __props.ui.item }
            }, null, _parent));
          });
          _push2(`<!--]--></div></div>`);
        }, detachedBody.value, false, _parent);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$r = _sfc_main$r.setup;
_sfc_main$r.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/EventLog.vue");
  return _sfc_setup$r ? _sfc_setup$r(props, ctx) : void 0;
};
const _sfc_main$q = /* @__PURE__ */ defineComponent({
  __name: "CodeBlock",
  __ssrInlineRender: true,
  props: {
    /** Code content to display */
    code: {
      type: String,
      default: ""
    },
    /** Programming language for syntax highlighting */
    lang: {
      type: String,
      default: "vue"
    },
    /** Optional filename to display in header */
    filename: {
      type: String,
      default: ""
    },
    /** Show line numbers */
    showLineNumbers: {
      type: Boolean,
      default: false
    },
    /** UI injection for styling */
    ui: {
      type: Object,
      default: () => ({})
    }
  },
  setup(__props) {
    const props = __props;
    const colors = {
      keyword: "var(--code-keyword, #c678dd)",
      // Purple
      string: "var(--code-string, #98c379)",
      // Green
      function: "var(--code-function, #61afef)",
      // Blue
      type: "var(--code-type, #e5c07b)",
      // Yellow
      variable: "var(--code-variable, #e06c75)",
      // Red
      number: "var(--code-number, #d19a66)",
      // Orange
      comment: "var(--code-comment, #7f848e)",
      // Grey
      tag: "var(--code-tag, #e06c75)",
      // Red (HTML tags)
      attr: "var(--code-attr, #d19a66)",
      // Orange (Attributes)
      operator: "var(--code-operator, #56b6c2)"
      // Cyan
    };
    const highlightedCode = computed(() => {
      let code = props.code.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
      const tokens = [];
      const save = (text, type) => {
        const id = `__T${tokens.length}__`;
        tokens.push({ id, text, type });
        return id;
      };
      const span = (color, text) => `<span style="color: ${color}">${text}</span>`;
      code = code.replace(/('.*?')/g, (m2) => save(m2, "string"));
      code = code.replace(/(".*?")/g, (m2) => save(m2, "string"));
      code = code.replace(/(`.*?`)/gs, (m2) => save(m2, "string"));
      if (props.lang === "php") {
        const keywords = "class|function|public|private|protected|return|extends|implements|use|namespace|new|static|const|final|if|else|foreach|as|array|void|bool|int|string|float|mixed";
        code = code.replace(new RegExp(`\\b(${keywords})\\b`, "g"), (m2) => save(m2, "keyword"));
        code = code.replace(/(\$[a-zA-Z0-9_]+)/g, (m2) => save(m2, "variable"));
        code = code.replace(/\b([A-Z][a-zA-Z0-9_]*)\b/g, (m2) => save(m2, "type"));
        code = code.replace(/\b([a-zA-Z0-9_]+)(?=\()/g, (m2) => save(m2, "function"));
        code = code.replace(/(=>|->|::)/g, (m2) => save(m2, "operator"));
      } else if (props.lang === "json") {
        code = code.replace(/"(\w+)":/g, (_m, p1) => `${save('"' + p1 + '"', "variable")}:`);
        code = code.replace(/\b(true|false|null)\b/g, (m2) => save(m2, "keyword"));
        code = code.replace(/: (\d+)/g, (_m, p1) => `: ${save(p1, "number")}`);
      } else if (props.lang === "bash") {
        code = code.replace(/(#.*)$/gm, (m2) => save(m2, "comment"));
        code = code.replace(/\b(npm|npx|php|artisan|composer|git|cd|mkdir|rm|cp|mv|echo|export)\b/g, (m2) => save(m2, "function"));
        code = code.replace(/(--?[\w-]+)/g, (m2) => save(m2, "variable"));
      } else {
        const keywords = "const|let|var|import|export|default|return|function|if|else|from|async|await|new|class|extends|implements|interface|type|enum";
        const vueKeywords = "defineProps|defineEmits|defineExpose|withDefaults|computed|ref|reactive|watch|watchEffect|onMounted|onUnmounted|provide|inject";
        code = code.replace(new RegExp(`\\b(${keywords}|${vueKeywords})\\b`, "g"), (m2) => save(m2, "keyword"));
        code = code.replace(/(&lt;\/?)(\\[A-Z][\\w-]*)/g, (_m, p1, p2) => `${p1}${save(p2, "type")}`);
        code = code.replace(/(&lt;\/?)(\\[a-z][\\w-]*)/g, (_m, p1, p2) => `${p1}${save(p2, "tag")}`);
        code = code.replace(/([\w-:@]+)=/g, (_m, p1) => `${save(p1, "attr")}=`);
      }
      let result = code;
      for (let i2 = tokens.length - 1; i2 >= 0; i2--) {
        const t3 = tokens[i2];
        const color = colors[t3.type] || "inherit";
        result = result.replace(t3.id, span(color, t3.text));
      }
      return result;
    });
    const codeLines = computed(() => {
      return highlightedCode.value.split("\n");
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)("rounded-lg overflow-hidden border border-ui-border", __props.ui.root)
      }, _attrs))}>`);
      if (__props.filename) {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "px-4 py-2 bg-ui-muted/50 border-b border-ui-border text-xs font-mono text-ui-muted-foreground",
          __props.ui.header
        ))}">${ssrInterpolate(__props.filename)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="${ssrRenderClass(unref(cn)(
        "font-mono text-xs leading-relaxed overflow-x-auto whitespace-pre p-4 bg-ui-input text-ui-foreground",
        __props.ui.code
      ))}">`);
      if (__props.showLineNumbers) {
        _push(`<div class="flex"><div class="pr-4 text-ui-muted-foreground/50 select-none text-right border-r border-ui-border/30 mr-4"><!--[-->`);
        ssrRenderList(codeLines.value, (_2, i2) => {
          _push(`<div>${ssrInterpolate(i2 + 1)}</div>`);
        });
        _push(`<!--]--></div><code><!--[-->`);
        ssrRenderList(codeLines.value, (line, i2) => {
          _push(`<div>${(line || " ") ?? ""}</div>`);
        });
        _push(`<!--]--></code></div>`);
      } else {
        _push(`<code>${highlightedCode.value ?? ""}</code>`);
      }
      _push(`</div></div>`);
    };
  }
});
const _sfc_setup$q = _sfc_main$q.setup;
_sfc_main$q.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/CodeBlock.vue");
  return _sfc_setup$q ? _sfc_setup$q(props, ctx) : void 0;
};
const _sfc_main$p = /* @__PURE__ */ defineComponent({
  __name: "ExpandableContent",
  __ssrInlineRender: true,
  props: {
    content: {},
    lines: { default: 4 },
    showMoreText: { default: "Show more" },
    showLessText: { default: "Show less" },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const isExpanded = ref(false);
    const needsToggle = ref(false);
    const contentRef = ref(null);
    const checkOverflow = () => {
      if (!contentRef.value) return;
      const el = contentRef.value;
      const wasExpanded = isExpanded.value;
      if (wasExpanded) {
        el.style.webkitLineClamp = String(props.lines);
        el.style.display = "-webkit-box";
        el.style.webkitBoxOrient = "vertical";
        el.style.overflow = "hidden";
      }
      nextTick(() => {
        if (!contentRef.value) return;
        needsToggle.value = contentRef.value.scrollHeight > contentRef.value.clientHeight;
        if (wasExpanded) {
          el.style.webkitLineClamp = "";
          el.style.display = "";
          el.style.webkitBoxOrient = "";
          el.style.overflow = "";
        }
      });
    };
    onMounted(() => {
      checkOverflow();
    });
    watch(() => props.content, () => {
      nextTick(() => checkOverflow());
    });
    watch(() => props.lines, () => {
      nextTick(() => checkOverflow());
    });
    const contentClampStyle = computed(() => {
      if (isExpanded.value) return {};
      return {
        display: "-webkit-box",
        "-webkit-line-clamp": String(props.lines),
        "-webkit-box-orient": "vertical",
        overflow: "hidden"
      };
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)("", __props.ui.root),
        style: __props.ui.rootStyle
      }, _attrs))}><p class="${ssrRenderClass(unref(cn)(
        "whitespace-pre-wrap break-words text-sm text-ui-foreground/90 leading-relaxed font-mono",
        __props.ui.content
      ))}" style="${ssrRenderStyle([contentClampStyle.value, __props.ui.contentStyle ?? {}])}">${ssrInterpolate(__props.content)}</p>`);
      if (needsToggle.value) {
        _push(`<button type="button" class="${ssrRenderClass(unref(cn)(
          "text-xs text-ui-primary hover:underline cursor-pointer mt-1 select-none inline-block bg-transparent border-none p-0",
          __props.ui.button
        ))}" style="${ssrRenderStyle(__props.ui.buttonStyle)}">${ssrInterpolate(isExpanded.value ? __props.showLessText : __props.showMoreText)}</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$p = _sfc_main$p.setup;
_sfc_main$p.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UI/ExpandableContent.vue");
  return _sfc_setup$p ? _sfc_setup$p(props, ctx) : void 0;
};
const _sfc_main$o = /* @__PURE__ */ defineComponent({
  __name: "SidebarNavigation",
  __ssrInlineRender: true,
  props: {
    groups: { default: () => [] },
    bottomActions: { default: () => [] },
    siteName: { default: "Application" },
    ui: { default: () => ({}) },
    position: { default: "fixed" },
    closable: { type: Boolean, default: false }
  },
  emits: ["close"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const groupOpenState = ref({});
    const hoveredSubmenuKey = ref(null);
    const activeSubmenuChildren = ref([]);
    const activeSubmenuPosition = ref({ top: 0, left: 0 });
    const initGroupState = () => {
      props.groups.forEach((group, index) => {
        if (group.collapsible) {
          const hasActive = group.items.some((item) => item.active);
          groupOpenState.value[index] = hasActive ? true : false;
        } else {
          groupOpenState.value[index] = true;
        }
      });
    };
    const clearSubmenuTimer = () => {
    };
    const getItemKey = (groupIndex, itemIndex) => `${groupIndex}-${itemIndex}`;
    const hasChildren = (item) => Array.isArray(item.children) && item.children.length > 0;
    const isSubmenuOpen = (groupIndex, itemIndex) => hoveredSubmenuKey.value === getItemKey(groupIndex, itemIndex);
    onMounted(initGroupState);
    watch(() => props.groups, initGroupState, { deep: true });
    onBeforeUnmount(() => clearSubmenuTimer());
    const styles = computed(() => {
      const { ui, position } = props;
      return {
        root: cn(
          "h-screen w-64 flex flex-col z-20 transition-all duration-300",
          position === "fixed" ? "fixed left-0 top-0" : position === "absolute" ? "absolute left-0 top-0" : "relative",
          "bg-ui-card/95 backdrop-blur-xl border-r border-ui-border/50",
          "shadow-[4px_0_24px_-2px_rgba(0,0,0,0.3)]",
          ui.root
        ),
        header: cn(
          "h-20 flex items-center justify-between px-6 border-b border-ui-border/50",
          ui.header
        ),
        logoWrapper: cn(
          "p-2.5 rounded-xl bg-gradient-to-br from-ui-primary to-ui-primary-hover shadow-lg shadow-ui-primary/20 mr-3",
          "ring-1 ring-white/10",
          ui.logoWrapper
        ),
        logoIcon: cn(
          "h-5 w-5 text-ui-primary-foreground",
          ui.logoIcon
        ),
        title: cn(
          "text-lg font-bold text-ui-foreground tracking-tight truncate",
          ui.title
        ),
        closeButton: cn(
          "p-1.5 rounded-lg text-ui-muted-foreground hover:text-ui-foreground hover:bg-ui-muted transition-colors ml-auto",
          ui.closeButton
        ),
        topSlot: cn(
          "",
          ui.projectSelector
        ),
        nav: cn(
          "flex-1 px-4 py-6 overflow-y-auto overflow-x-visible space-y-6",
          ui.nav
        ),
        groupLabel: cn(
          "flex items-center justify-between w-full px-3 mb-2 text-sm font-bold text-ui-muted-foreground hover:text-ui-foreground uppercase tracking-widest transition-colors",
          ui.groupLabel
        ),
        item: cn(
          "group relative flex items-center justify-between px-3 py-2.5 my-1 rounded-lg transition-all duration-200",
          "hover:bg-ui-muted/50",
          "cursor-pointer",
          ui.item
        ),
        itemActive: cn(
          "bg-gradient-to-r from-ui-primary/10 to-ui-primary/5 border-l-[3px] border-ui-primary",
          ui.itemActive
        ),
        itemInactive: cn(
          "text-ui-muted-foreground hover:text-ui-foreground border-l-[3px] border-transparent",
          ui.itemInactive
        ),
        itemIcon: cn(
          "mr-3 h-5 w-5 transition-colors",
          ui.itemIcon
        ),
        itemIconActive: cn(
          "text-ui-primary drop-shadow-[0_0_8px_rgba(var(--ui-primary),0.5)]",
          ui.itemIconActive
        ),
        itemIconInactive: cn(
          "text-ui-muted-foreground group-hover:text-ui-foreground",
          ui.itemIconInactive
        ),
        itemCount: cn(
          "px-2 py-0.5 rounded-md text-[10px] font-bold",
          "bg-ui-muted text-ui-muted-foreground group-hover:text-ui-foreground transition-colors",
          ui.itemCount
        ),
        submenuContainer: cn(
          "fixed min-w-[220px] rounded-xl border border-ui-border/60",
          "bg-ui-card/95 backdrop-blur-xl shadow-2xl shadow-black/30 p-1.5 z-50",
          ui.submenuContainer
        ),
        submenuItem: cn(
          "flex items-center gap-2.5 w-full rounded-lg px-3 py-2 text-sm font-medium transition-colors",
          "text-ui-muted-foreground hover:text-ui-foreground hover:bg-ui-muted/60",
          ui.submenuItem
        ),
        submenuItemActive: cn(
          "bg-ui-primary/10 text-ui-primary ring-1 ring-inset ring-ui-primary/20",
          ui.submenuItemActive
        ),
        bottomActions: cn(
          "p-4 border-t border-ui-border/50 bg-ui-card/50 backdrop-blur-sm",
          ui.bottomActions
        )
      };
    });
    const resolveTag = (item) => {
      return item.href ? "a" : "button";
    };
    const handleItemClick = (item, event) => {
      if (item.onClick) {
        item.onClick(event);
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<aside${ssrRenderAttrs(mergeProps({
        class: styles.value.root,
        style: __props.ui.rootStyle
      }, _attrs))}>`);
      ssrRenderSlot(_ctx.$slots, "header", {}, () => {
        _push(`<div class="${ssrRenderClass(styles.value.header)}" style="${ssrRenderStyle(__props.ui.headerStyle)}"><div class="flex items-center"><div class="${ssrRenderClass(styles.value.logoWrapper)}" style="${ssrRenderStyle(__props.ui.logoWrapperStyle)}">`);
        _push(ssrRenderComponent(unref(Layers), {
          class: styles.value.logoIcon,
          style: __props.ui.logoIconStyle
        }, null, _parent));
        _push(`</div><div class="${ssrRenderClass(styles.value.title)}" style="${ssrRenderStyle(__props.ui.titleStyle)}">${ssrInterpolate(__props.siteName)}</div></div>`);
        if (__props.closable) {
          _push(`<button class="${ssrRenderClass(styles.value.closeButton)}" style="${ssrRenderStyle(__props.ui.closeButtonStyle)}">`);
          _push(ssrRenderComponent(unref(PanelLeftClose), { class: "w-5 h-5" }, null, _parent));
          _push(`</button>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      }, _push, _parent);
      if (_ctx.$slots["top"]) {
        _push(`<div class="${ssrRenderClass([styles.value.topSlot, "px-6 py-4"])}" style="${ssrRenderStyle(__props.ui.projectSelectorStyle)}">`);
        ssrRenderSlot(_ctx.$slots, "top", {}, null, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<nav class="${ssrRenderClass(styles.value.nav)}" style="${ssrRenderStyle(__props.ui.navStyle)}"><!--[-->`);
      ssrRenderList(__props.groups, (group, gIndex) => {
        _push(`<!--[-->`);
        if (group.label) {
          _push(`<div class="${ssrRenderClass(unref(cn)(styles.value.groupLabel, group.collapsible ? "cursor-pointer" : ""))}" style="${ssrRenderStyle(__props.ui.groupLabelStyle)}"><span>${ssrInterpolate(group.label)}</span>`);
          if (group.collapsible) {
            ssrRenderVNode(_push, createVNode(resolveDynamicComponent(groupOpenState.value[gIndex] ? unref(ChevronDown) : unref(ChevronRight)), { class: "w-4 h-4 text-ui-muted-foreground" }, null), _parent);
          } else {
            _push(`<!---->`);
          }
          _push(`</div>`);
        } else {
          _push(`<!---->`);
        }
        if (!group.collapsible || group.collapsible && groupOpenState.value[gIndex]) {
          _push(`<div class="space-y-0.5"><!--[-->`);
          ssrRenderList(group.items, (item, iIndex) => {
            _push(`<div class="relative">`);
            ssrRenderVNode(_push, createVNode(resolveDynamicComponent(resolveTag(item)), {
              href: item.href,
              class: unref(cn)(
                styles.value.item,
                item.active ? styles.value.itemActive : styles.value.itemInactive,
                "w-full text-left"
              ),
              style: [
                __props.ui.itemStyle,
                item.active ? __props.ui.itemActiveStyle : __props.ui.itemInactiveStyle
              ],
              onClick: ($event) => handleItemClick(item, $event),
              "aria-haspopup": hasChildren(item) ? "menu" : void 0,
              "aria-expanded": hasChildren(item) ? String(isSubmenuOpen(gIndex, iIndex)) : void 0
            }, {
              default: withCtx((_2, _push2, _parent2, _scopeId) => {
                if (_push2) {
                  _push2(`<span class="flex items-center"${_scopeId}>`);
                  if (item.icon) {
                    ssrRenderVNode(_push2, createVNode(resolveDynamicComponent(item.icon), {
                      class: unref(cn)(
                        styles.value.itemIcon,
                        item.active ? styles.value.itemIconActive : styles.value.itemIconInactive
                      ),
                      style: [
                        __props.ui.itemIconStyle,
                        item.active ? __props.ui.itemIconActiveStyle : __props.ui.itemIconInactiveStyle
                      ]
                    }, null), _parent2, _scopeId);
                  } else {
                    _push2(`<!---->`);
                  }
                  _push2(`<span class="${ssrRenderClass(item.active ? "text-ui-foreground" : "")}"${_scopeId}>${ssrInterpolate(item.label)}</span></span><span class="flex items-center gap-2"${_scopeId}>`);
                  if (item.count !== null && item.count !== void 0) {
                    _push2(`<span class="${ssrRenderClass(styles.value.itemCount)}" style="${ssrRenderStyle(__props.ui.itemCountStyle)}"${_scopeId}>${ssrInterpolate(item.count)}</span>`);
                  } else {
                    _push2(`<!---->`);
                  }
                  if (hasChildren(item)) {
                    _push2(ssrRenderComponent(unref(ChevronRight), { class: "w-4 h-4 text-ui-muted-foreground/80 group-hover:text-ui-foreground transition-colors" }, null, _parent2, _scopeId));
                  } else {
                    _push2(`<!---->`);
                  }
                  _push2(`</span>`);
                } else {
                  return [
                    createVNode("span", { class: "flex items-center" }, [
                      item.icon ? (openBlock(), createBlock(resolveDynamicComponent(item.icon), {
                        key: 0,
                        class: unref(cn)(
                          styles.value.itemIcon,
                          item.active ? styles.value.itemIconActive : styles.value.itemIconInactive
                        ),
                        style: [
                          __props.ui.itemIconStyle,
                          item.active ? __props.ui.itemIconActiveStyle : __props.ui.itemIconInactiveStyle
                        ]
                      }, null, 8, ["class", "style"])) : createCommentVNode("", true),
                      createVNode("span", {
                        class: item.active ? "text-ui-foreground" : ""
                      }, toDisplayString(item.label), 3)
                    ]),
                    createVNode("span", { class: "flex items-center gap-2" }, [
                      item.count !== null && item.count !== void 0 ? (openBlock(), createBlock("span", {
                        key: 0,
                        class: styles.value.itemCount,
                        style: __props.ui.itemCountStyle
                      }, toDisplayString(item.count), 7)) : createCommentVNode("", true),
                      hasChildren(item) ? (openBlock(), createBlock(unref(ChevronRight), {
                        key: 1,
                        class: "w-4 h-4 text-ui-muted-foreground/80 group-hover:text-ui-foreground transition-colors"
                      })) : createCommentVNode("", true)
                    ])
                  ];
                }
              }),
              _: 2
            }), _parent);
            _push(`</div>`);
          });
          _push(`<!--]--></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></nav>`);
      ssrRenderTeleport(_push, (_push2) => {
        if (hoveredSubmenuKey.value && activeSubmenuChildren.value.length) {
          _push2(`<div class="${ssrRenderClass(styles.value.submenuContainer)}" style="${ssrRenderStyle([
            __props.ui.submenuContainerStyle,
            { top: `${activeSubmenuPosition.value.top}px`, left: `${activeSubmenuPosition.value.left}px` }
          ])}"><!--[-->`);
          ssrRenderList(activeSubmenuChildren.value, (child) => {
            ssrRenderVNode(_push2, createVNode(resolveDynamicComponent(resolveTag(child)), {
              key: `submenu-${child.label}`,
              href: child.href,
              class: unref(cn)(styles.value.submenuItem, child.active ? styles.value.submenuItemActive : ""),
              style: [
                __props.ui.submenuItemStyle,
                child.active ? __props.ui.submenuItemActiveStyle : void 0
              ],
              onClick: ($event) => handleItemClick(child, $event)
            }, {
              default: withCtx((_2, _push3, _parent2, _scopeId) => {
                if (_push3) {
                  if (child.icon) {
                    ssrRenderVNode(_push3, createVNode(resolveDynamicComponent(child.icon), { class: "h-4 w-4" }, null), _parent2, _scopeId);
                  } else {
                    _push3(`<!---->`);
                  }
                  _push3(`<span${_scopeId}>${ssrInterpolate(child.label)}</span>`);
                } else {
                  return [
                    child.icon ? (openBlock(), createBlock(resolveDynamicComponent(child.icon), {
                      key: 0,
                      class: "h-4 w-4"
                    })) : createCommentVNode("", true),
                    createVNode("span", null, toDisplayString(child.label), 1)
                  ];
                }
              }),
              _: 2
            }), _parent);
          });
          _push2(`<!--]--></div>`);
        } else {
          _push2(`<!---->`);
        }
      }, "body", false, _parent);
      if (_ctx.$slots["bottom"]) {
        _push(`<div class="mt-auto">`);
        ssrRenderSlot(_ctx.$slots, "bottom", {}, null, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      if (__props.bottomActions && __props.bottomActions.length > 0) {
        _push(`<div class="${ssrRenderClass(styles.value.bottomActions)}" style="${ssrRenderStyle(__props.ui.bottomActionsStyle)}"><!--[-->`);
        ssrRenderList(__props.bottomActions, (item) => {
          ssrRenderVNode(_push, createVNode(resolveDynamicComponent(resolveTag(item)), {
            key: item.label,
            href: item.href,
            class: unref(cn)(styles.value.item, styles.value.itemInactive, "w-full text-left"),
            style: __props.ui.footerItemStyle,
            onClick: ($event) => handleItemClick(item, $event)
          }, {
            default: withCtx((_2, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`<div class="flex items-center"${_scopeId}>`);
                if (item.icon) {
                  ssrRenderVNode(_push2, createVNode(resolveDynamicComponent(item.icon), { class: "mr-3 h-5 w-5 text-ui-muted-foreground group-hover:text-ui-primary transition-colors" }, null), _parent2, _scopeId);
                } else {
                  _push2(`<!---->`);
                }
                _push2(`<span class="text-sm font-medium text-ui-muted-foreground group-hover:text-ui-foreground transition-colors"${_scopeId}>${ssrInterpolate(item.label)}</span></div>`);
              } else {
                return [
                  createVNode("div", { class: "flex items-center" }, [
                    item.icon ? (openBlock(), createBlock(resolveDynamicComponent(item.icon), {
                      key: 0,
                      class: "mr-3 h-5 w-5 text-ui-muted-foreground group-hover:text-ui-primary transition-colors"
                    })) : createCommentVNode("", true),
                    createVNode("span", { class: "text-sm font-medium text-ui-muted-foreground group-hover:text-ui-foreground transition-colors" }, toDisplayString(item.label), 1)
                  ])
                ];
              }
            }),
            _: 2
          }), _parent);
        });
        _push(`<!--]--></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</aside>`);
    };
  }
});
const _sfc_setup$o = _sfc_main$o.setup;
_sfc_main$o.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Organisms/SidebarNavigation.vue");
  return _sfc_setup$o ? _sfc_setup$o(props, ctx) : void 0;
};
const ThemeKey = Symbol("Theme");
function useTheme() {
  const themeContext = inject(ThemeKey);
  if (!themeContext) {
    throw new Error("useTheme must be used within a ThemeProvider");
  }
  return {
    theme: themeContext.theme,
    setTheme: themeContext.setTheme,
    themes: ["dark-indigo", "dark-borg"]
  };
}
const _sfc_main$n = /* @__PURE__ */ defineComponent({
  __name: "Topbar",
  __ssrInlineRender: true,
  props: {
    ui: { default: () => ({}) },
    showThemeSwitcher: { type: Boolean, default: true },
    showMenuButton: { type: Boolean, default: false }
  },
  emits: ["menu-click"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    let theme = { value: "dark-indigo" };
    let setTheme = () => {
    };
    let themes = ["dark-indigo", "dark-borg"];
    try {
      const themeContext = useTheme();
      theme = themeContext.theme;
      setTheme = themeContext.setTheme;
      themes = themeContext.themes;
    } catch {
    }
    const styles = computed(() => {
      const { ui } = props;
      return {
        root: cn(
          "h-16 flex items-center justify-between px-6",
          "bg-ui-card/80 backdrop-blur-xl border-b border-ui-border/50",
          "sticky top-0 z-30",
          "shadow-sm",
          ui.root
        ),
        content: cn(
          "flex items-center justify-between w-full",
          ui.content
        ),
        left: cn(
          "flex items-center gap-4",
          ui.left
        ),
        right: cn(
          "flex items-center gap-3",
          ui.right
        ),
        themeSwitcher: cn(
          "p-2 rounded-lg",
          "text-ui-muted-foreground hover:text-ui-foreground",
          "hover:bg-ui-muted/50",
          "transition-all duration-200",
          "focus:outline-none focus:ring-2 focus:ring-ui-ring/50",
          ui.themeSwitcher
        ),
        menuButton: cn(
          "p-2 rounded-lg",
          "text-ui-muted-foreground hover:text-ui-foreground",
          "hover:bg-ui-muted/50",
          "transition-all duration-200",
          "focus:outline-none focus:ring-2 focus:ring-ui-ring/50",
          "lg:hidden",
          ui.menuButton
        )
      };
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<header${ssrRenderAttrs(mergeProps({
        class: styles.value.root,
        style: __props.ui.rootStyle
      }, _attrs))}><div class="${ssrRenderClass(styles.value.content)}" style="${ssrRenderStyle(__props.ui.contentStyle)}"><div class="${ssrRenderClass(styles.value.left)}" style="${ssrRenderStyle(__props.ui.leftStyle)}">`);
      if (__props.showMenuButton) {
        _push(`<button class="${ssrRenderClass(styles.value.menuButton)}" style="${ssrRenderStyle(__props.ui.menuButtonStyle)}">`);
        _push(ssrRenderComponent(unref(Menu), { class: "w-5 h-5" }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      ssrRenderSlot(_ctx.$slots, "left", {}, () => {
        ssrRenderSlot(_ctx.$slots, "title", {}, null, _push, _parent);
      }, _push, _parent);
      _push(`</div><div class="${ssrRenderClass(styles.value.right)}" style="${ssrRenderStyle(__props.ui.rightStyle)}">`);
      ssrRenderSlot(_ctx.$slots, "center", {}, null, _push, _parent);
      ssrRenderSlot(_ctx.$slots, "notifications", {}, () => {
        if (_ctx.$slots.notifications === void 0) {
          _push(`<button class="p-2 rounded-lg text-ui-muted-foreground hover:text-ui-foreground hover:bg-ui-muted/50 transition-colors relative">`);
          _push(ssrRenderComponent(unref(Bell), { class: "w-5 h-5" }, null, _parent));
          _push(`<span class="absolute top-1.5 right-1.5 w-2 h-2 bg-ui-primary rounded-full ring-2 ring-ui-card"></span></button>`);
        } else {
          _push(`<!---->`);
        }
      }, _push, _parent);
      if (__props.showThemeSwitcher) {
        _push(`<button class="${ssrRenderClass(styles.value.themeSwitcher)}" style="${ssrRenderStyle(__props.ui.themeSwitcherStyle)}"${ssrRenderAttr("title", `Current theme: ${unref(theme).value}`)}>`);
        if (unref(theme).value === "dark-indigo") {
          _push(ssrRenderComponent(unref(Sun), { class: "w-5 h-5" }, null, _parent));
        } else {
          _push(ssrRenderComponent(unref(Moon), { class: "w-5 h-5" }, null, _parent));
        }
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      ssrRenderSlot(_ctx.$slots, "user", {}, null, _push, _parent);
      _push(`</div></div></header>`);
    };
  }
});
const _sfc_setup$n = _sfc_main$n.setup;
_sfc_main$n.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Organisms/Topbar.vue");
  return _sfc_setup$n ? _sfc_setup$n(props, ctx) : void 0;
};
const _sfc_main$m = /* @__PURE__ */ defineComponent({
  __name: "PageHeader",
  __ssrInlineRender: true,
  props: {
    title: {},
    subtitle: {},
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const styles = computed(() => {
      const { ui } = props;
      return {
        root: cn(
          "flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4",
          ui.root
        ),
        content: cn(
          "space-y-1",
          ui.content
        ),
        title: cn(
          "text-3xl font-bold text-ui-foreground tracking-tight",
          ui.title
        ),
        subtitle: cn(
          "text-ui-muted-foreground text-base",
          ui.subtitle
        ),
        actions: cn(
          "flex items-center gap-3 flex-shrink-0",
          ui.actions
        )
      };
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d, _e;
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: "mb-8",
        style: (_a = __props.ui) == null ? void 0 : _a.rootStyle
      }, _attrs))}>`);
      if (_ctx.$slots.breadcrumbs) {
        _push(`<div class="mb-4">`);
        ssrRenderSlot(_ctx.$slots, "breadcrumbs", {}, null, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="${ssrRenderClass(styles.value.root)}"><div class="${ssrRenderClass(styles.value.content)}" style="${ssrRenderStyle((_b = __props.ui) == null ? void 0 : _b.contentStyle)}"><h1 class="${ssrRenderClass(styles.value.title)}" style="${ssrRenderStyle((_c = __props.ui) == null ? void 0 : _c.titleStyle)}">${ssrInterpolate(__props.title)}</h1>`);
      if (__props.subtitle) {
        _push(`<p class="${ssrRenderClass(styles.value.subtitle)}" style="${ssrRenderStyle((_d = __props.ui) == null ? void 0 : _d.subtitleStyle)}">${ssrInterpolate(__props.subtitle)}</p>`);
      } else {
        _push(`<!---->`);
      }
      ssrRenderSlot(_ctx.$slots, "description", {}, null, _push, _parent);
      _push(`</div><div class="${ssrRenderClass(styles.value.actions)}" style="${ssrRenderStyle((_e = __props.ui) == null ? void 0 : _e.actionsStyle)}">`);
      ssrRenderSlot(_ctx.$slots, "actions", {}, null, _push, _parent);
      _push(`</div></div></div>`);
    };
  }
});
const _sfc_setup$m = _sfc_main$m.setup;
_sfc_main$m.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/PageHeader.vue");
  return _sfc_setup$m ? _sfc_setup$m(props, ctx) : void 0;
};
const _sfc_main$l = /* @__PURE__ */ defineComponent({
  __name: "SectionPanel",
  __ssrInlineRender: true,
  props: {
    title: {},
    icon: {},
    noPadding: { type: Boolean, default: false },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const styles = computed(() => {
      const { ui } = props;
      return {
        root: cn(
          "bg-ui-card border border-ui-border rounded-xl",
          "shadow-sm",
          ui.root
        ),
        header: cn(
          "flex items-center gap-3 px-6 py-4",
          "border-b border-ui-border/50",
          ui.header
        ),
        icon: cn(
          "text-ui-primary",
          ui.icon
        ),
        title: cn(
          "font-semibold text-ui-foreground",
          ui.title
        ),
        content: cn(
          props.noPadding ? "" : "p-6",
          ui.content
        ),
        footer: cn(
          "px-6 py-4 border-t border-ui-border/50",
          "bg-ui-muted/20",
          ui.footer
        )
      };
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d;
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: styles.value.root,
        style: (_a = __props.ui) == null ? void 0 : _a.rootStyle
      }, _attrs))}>`);
      if (__props.title || _ctx.$slots.header || __props.icon) {
        _push(`<div class="${ssrRenderClass(styles.value.header)}" style="${ssrRenderStyle((_b = __props.ui) == null ? void 0 : _b.headerStyle)}">`);
        ssrRenderSlot(_ctx.$slots, "header", {}, () => {
          var _a2, _b2;
          if (__props.icon) {
            _push(ssrRenderComponent(_sfc_main$13, {
              icon: __props.icon,
              ui: { root: unref(cn)(styles.value.icon, "w-5 h-5"), rootStyle: (_a2 = __props.ui) == null ? void 0 : _a2.iconStyle }
            }, null, _parent));
          } else {
            _push(`<!---->`);
          }
          if (__props.title) {
            _push(`<h2 class="${ssrRenderClass(styles.value.title)}" style="${ssrRenderStyle((_b2 = __props.ui) == null ? void 0 : _b2.titleStyle)}">${ssrInterpolate(__props.title)}</h2>`);
          } else {
            _push(`<!---->`);
          }
        }, _push, _parent);
        ssrRenderSlot(_ctx.$slots, "header-actions", {}, null, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="${ssrRenderClass(styles.value.content)}" style="${ssrRenderStyle((_c = __props.ui) == null ? void 0 : _c.contentStyle)}">`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</div>`);
      if (_ctx.$slots.footer) {
        _push(`<div class="${ssrRenderClass(styles.value.footer)}" style="${ssrRenderStyle((_d = __props.ui) == null ? void 0 : _d.footerStyle)}">`);
        ssrRenderSlot(_ctx.$slots, "footer", {}, null, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$l = _sfc_main$l.setup;
_sfc_main$l.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/SectionPanel.vue");
  return _sfc_setup$l ? _sfc_setup$l(props, ctx) : void 0;
};
const _sfc_main$k = /* @__PURE__ */ defineComponent({
  __name: "StatCard",
  __ssrInlineRender: true,
  props: {
    label: {},
    value: {},
    description: {},
    icon: {},
    color: { default: "primary" },
    showBar: { type: Boolean, default: true },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const colorClasses = computed(() => {
      const colors = {
        primary: {
          icon: "bg-ui-primary/15 text-ui-primary",
          bar: "from-ui-primary"
        },
        cyan: {
          icon: "bg-cyan-500/15 text-cyan-400",
          bar: "from-cyan-500"
        },
        indigo: {
          icon: "bg-indigo-500/15 text-indigo-400",
          bar: "from-indigo-500"
        },
        emerald: {
          icon: "bg-emerald-500/15 text-emerald-400",
          bar: "from-emerald-500"
        },
        amber: {
          icon: "bg-amber-500/15 text-amber-400",
          bar: "from-amber-500"
        },
        rose: {
          icon: "bg-rose-500/15 text-rose-400",
          bar: "from-rose-500"
        }
      };
      return colors[props.color] || colors.primary;
    });
    const styles = computed(() => {
      const { ui } = props;
      return {
        root: cn(
          "bg-ui-card border border-ui-border rounded-xl p-6",
          "transition-all duration-300 ease-out",
          "hover:border-ui-border/80 hover:shadow-lg hover:shadow-black/20",
          "hover:-translate-y-0.5",
          "group",
          ui.root
        ),
        header: cn(
          "flex items-start justify-between",
          ui.header
        ),
        icon: cn(
          "p-3 rounded-xl transition-transform duration-200",
          "group-hover:scale-110",
          colorClasses.value.icon,
          ui.icon
        ),
        label: cn(
          "text-ui-muted-foreground text-sm font-medium uppercase tracking-wider",
          ui.label
        ),
        value: cn(
          "text-4xl font-bold text-ui-foreground leading-none mt-2",
          "tabular-nums",
          ui.value
        ),
        description: cn(
          "text-ui-muted-foreground text-xs mt-1 opacity-70",
          ui.description
        ),
        bar: cn(
          "h-1 rounded-full mt-4 opacity-60",
          "bg-gradient-to-r to-transparent",
          colorClasses.value.bar,
          ui.bar
        )
      };
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d, _e, _f, _g;
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: styles.value.root,
        style: (_a = __props.ui) == null ? void 0 : _a.rootStyle
      }, _attrs))}><div class="${ssrRenderClass(styles.value.header)}" style="${ssrRenderStyle((_b = __props.ui) == null ? void 0 : _b.headerStyle)}"><div><p class="${ssrRenderClass(styles.value.label)}" style="${ssrRenderStyle((_c = __props.ui) == null ? void 0 : _c.labelStyle)}">${ssrInterpolate(__props.label)}</p><p class="${ssrRenderClass(styles.value.value)}" style="${ssrRenderStyle((_d = __props.ui) == null ? void 0 : _d.valueStyle)}">${ssrInterpolate(typeof __props.value === "number" ? __props.value.toLocaleString() : __props.value)}</p>`);
      if (__props.description) {
        _push(`<p class="${ssrRenderClass(styles.value.description)}" style="${ssrRenderStyle((_e = __props.ui) == null ? void 0 : _e.descriptionStyle)}">${ssrInterpolate(__props.description)}</p>`);
      } else {
        _push(`<!---->`);
      }
      ssrRenderSlot(_ctx.$slots, "footer", {}, null, _push, _parent);
      _push(`</div><div class="${ssrRenderClass(styles.value.icon)}" style="${ssrRenderStyle((_f = __props.ui) == null ? void 0 : _f.iconStyle)}">`);
      ssrRenderSlot(_ctx.$slots, "icon", {}, () => {
        if (__props.icon) {
          _push(ssrRenderComponent(_sfc_main$13, {
            icon: __props.icon,
            class: "w-6 h-6"
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
      }, _push, _parent);
      _push(`</div></div>`);
      if (__props.showBar) {
        _push(`<div class="${ssrRenderClass(styles.value.bar)}" style="${ssrRenderStyle((_g = __props.ui) == null ? void 0 : _g.barStyle)}"></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$k = _sfc_main$k.setup;
_sfc_main$k.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/StatCard.vue");
  return _sfc_setup$k ? _sfc_setup$k(props, ctx) : void 0;
};
const _sfc_main$j = /* @__PURE__ */ defineComponent({
  __name: "EmptyState",
  __ssrInlineRender: true,
  props: {
    title: { default: "No data found" },
    description: {},
    icon: { default: "inbox" },
    bordered: { type: Boolean, default: true },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const styles = computed(() => {
      const { ui } = props;
      return {
        root: cn(
          "flex flex-col items-center justify-center text-center py-16 px-6",
          props.bordered && "border border-dashed border-ui-border rounded-xl bg-ui-card/50",
          ui.root
        ),
        icon: cn(
          "text-ui-muted-foreground opacity-30 mb-4",
          ui.icon
        ),
        title: cn(
          "text-lg font-medium text-ui-muted-foreground",
          ui.title
        ),
        description: cn(
          "text-sm text-ui-muted-foreground/70 mt-1",
          ui.description
        ),
        actions: cn(
          "mt-6 flex items-center gap-3",
          ui.actions
        )
      };
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d;
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: styles.value.root,
        style: (_a = __props.ui) == null ? void 0 : _a.rootStyle
      }, _attrs))}>`);
      ssrRenderSlot(_ctx.$slots, "icon", {}, () => {
        var _a2;
        _push(ssrRenderComponent(_sfc_main$13, {
          icon: __props.icon,
          ui: { root: unref(cn)(styles.value.icon, "w-16 h-16"), rootStyle: (_a2 = __props.ui) == null ? void 0 : _a2.iconStyle }
        }, null, _parent));
      }, _push, _parent);
      _push(`<p class="${ssrRenderClass(styles.value.title)}" style="${ssrRenderStyle((_b = __props.ui) == null ? void 0 : _b.titleStyle)}">${ssrInterpolate(__props.title)}</p>`);
      if (__props.description) {
        _push(`<p class="${ssrRenderClass(styles.value.description)}" style="${ssrRenderStyle((_c = __props.ui) == null ? void 0 : _c.descriptionStyle)}">${ssrInterpolate(__props.description)}</p>`);
      } else {
        _push(`<!---->`);
      }
      ssrRenderSlot(_ctx.$slots, "description-extra", {}, null, _push, _parent);
      if (_ctx.$slots.actions) {
        _push(`<div class="${ssrRenderClass(styles.value.actions)}" style="${ssrRenderStyle((_d = __props.ui) == null ? void 0 : _d.actionsStyle)}">`);
        ssrRenderSlot(_ctx.$slots, "actions", {}, null, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$j = _sfc_main$j.setup;
_sfc_main$j.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/EmptyState.vue");
  return _sfc_setup$j ? _sfc_setup$j(props, ctx) : void 0;
};
const _sfc_main$i = /* @__PURE__ */ defineComponent({
  __name: "DataTable",
  __ssrInlineRender: true,
  props: {
    columns: { default: () => [] },
    items: {},
    itemKey: {},
    loading: { type: Boolean, default: false },
    sort: {},
    sorts: {},
    sortable: { type: Boolean },
    responsive: { type: Boolean, default: true },
    ui: { default: () => ({}) },
    selectable: { type: Boolean, default: false },
    selectedItems: { default: () => [] },
    enableRowClick: { type: Boolean, default: false },
    expandable: { type: Boolean, default: false },
    expandedRows: { default: () => [] },
    stickyHeader: { type: Boolean, default: false },
    density: { default: "normal" },
    striped: { type: Boolean, default: false }
  },
  emits: ["sort-change", "sorts-change", "update:selectedItems", "update:expandedRows", "row-click", "select-all"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const headerCheckboxEl = ref(null);
    function getItemKey(item) {
      return item[props.itemKey ?? "id"];
    }
    const visibleItemKeys = computed(() => props.items.map(getItemKey));
    const isAllSelected = computed(() => {
      if (props.items.length === 0) return false;
      return visibleItemKeys.value.every((key) => props.selectedItems.includes(key));
    });
    const isSomeSelected = computed(() => {
      if (props.items.length === 0) return false;
      return visibleItemKeys.value.some((key) => props.selectedItems.includes(key));
    });
    watchEffect(() => {
      if (headerCheckboxEl.value) {
        headerCheckboxEl.value.indeterminate = isSomeSelected.value && !isAllSelected.value;
      }
    });
    function isItemSelected(item) {
      return props.selectedItems.includes(getItemKey(item));
    }
    function isItemExpanded(item) {
      return props.expandedRows.includes(getItemKey(item));
    }
    function isSortableColumn(col) {
      return col.sortable === true || props.sortable === true;
    }
    const isMultiSortMode = computed(() => props.sorts !== void 0);
    function getSortPosition(key) {
      if (!props.sorts) return 0;
      const idx = props.sorts.findIndex((s2) => s2.key === key);
      return idx === -1 ? 0 : idx + 1;
    }
    function getSortIconForMulti(key) {
      if (!props.sorts) return ArrowUpDown;
      const entry = props.sorts.find((s2) => s2.key === key);
      if (!entry) return ArrowUpDown;
      return entry.direction === "asc" ? ArrowUp : ArrowDown;
    }
    function getSortIcon(key) {
      if (isMultiSortMode.value) {
        return getSortIconForMulti(key);
      }
      const current = props.sort;
      if (current && current.key === key) {
        return current.direction === "asc" ? ArrowUp : ArrowDown;
      }
      return ArrowUpDown;
    }
    function getSortIconColor(key) {
      var _a;
      if (isMultiSortMode.value) {
        const pos = getSortPosition(key);
        return pos > 0 ? "text-ui-primary" : "text-ui-muted-foreground/50";
      }
      return ((_a = props.sort) == null ? void 0 : _a.key) === key ? "text-ui-primary" : "text-ui-muted-foreground/50";
    }
    const densityConfig = computed(() => {
      switch (props.density) {
        case "compact":
          return {
            headerPadding: "px-4 py-1",
            rowNonResponsive: "flex items-center px-4 py-1",
            rowResponsive: "block p-2 space-y-2 md:space-y-0 md:flex md:items-center md:px-4 md:py-1"
          };
        case "comfortable":
          return {
            headerPadding: "px-6 py-5",
            rowNonResponsive: "flex items-center px-6 py-6",
            rowResponsive: "block p-6 space-y-2 md:space-y-0 md:flex md:items-center md:px-6 md:py-6"
          };
        default:
          return {
            headerPadding: "px-6 py-3",
            rowNonResponsive: "flex items-center px-6 py-4",
            rowResponsive: "block p-4 space-y-2 md:space-y-0 md:flex md:items-center md:px-6 md:py-4"
          };
      }
    });
    const styles = computed(() => {
      const { ui, responsive, stickyHeader, enableRowClick } = props;
      const dc = densityConfig.value;
      return {
        root: cn("bg-ui-card border border-ui-border rounded-xl overflow-x-auto", ui.root),
        header: cn(
          "bg-ui-muted/30 border-b border-ui-border",
          stickyHeader && "sticky top-0 z-10",
          ui.header
        ),
        headerRow: cn(
          responsive ? "hidden md:flex" : "flex",
          "items-center",
          dc.headerPadding,
          "text-xs font-bold text-ui-muted-foreground uppercase tracking-wider",
          ui.headerRow
        ),
        headerCell: cn(
          "text-xs font-bold text-ui-muted-foreground uppercase tracking-wider",
          ui == null ? void 0 : ui.headerCell
        ),
        body: cn("divide-y divide-ui-border", ui.body),
        row: cn(
          responsive ? dc.rowResponsive : dc.rowNonResponsive,
          "hover:bg-ui-muted/20 transition-colors",
          enableRowClick && "cursor-pointer",
          ui.row
        ),
        cell: cn("text-sm text-ui-foreground", ui.cell),
        cardLabel: cn(
          "block mb-0.5 text-xs font-semibold text-ui-muted-foreground uppercase tracking-wider md:hidden",
          ui.cardLabel
        ),
        empty: cn("py-16 text-center", ui.empty),
        footer: cn("border-t border-ui-border px-6 py-4 bg-ui-muted/20", ui == null ? void 0 : ui.footer),
        selectionCell: cn("w-10 flex-none flex items-center", ui == null ? void 0 : ui.selectionCell),
        expansionCell: cn("w-10 flex-none flex items-center justify-center", ui == null ? void 0 : ui.expansionCell),
        expansionRow: cn("px-6 py-4 bg-ui-muted/10 border-t border-ui-border/50", ui == null ? void 0 : ui.expansionRow)
      };
    });
    function getRowStripedClass(index) {
      if (!props.striped) return "";
      return index % 2 === 0 ? "bg-ui-muted/10" : "";
    }
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d, _e;
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: styles.value.root,
        style: (_a = __props.ui) == null ? void 0 : _a.rootStyle
      }, _attrs))}>`);
      if (_ctx.$slots.header || __props.columns.length > 0 || __props.selectable) {
        _push(`<div class="${ssrRenderClass(styles.value.header)}" style="${ssrRenderStyle((_b = __props.ui) == null ? void 0 : _b.headerStyle)}">`);
        ssrRenderSlot(_ctx.$slots, "header", {}, () => {
          var _a2, _b2, _c2;
          _push(`<div class="${ssrRenderClass(styles.value.headerRow)}" style="${ssrRenderStyle((_a2 = __props.ui) == null ? void 0 : _a2.headerRowStyle)}">`);
          if (__props.selectable) {
            _push(`<div class="${ssrRenderClass(styles.value.selectionCell)}" style="${ssrRenderStyle((_b2 = __props.ui) == null ? void 0 : _b2.selectionCellStyle)}"><input type="checkbox" class="rounded border-ui-border text-ui-primary shadow-sm focus:ring-ui-ring"${ssrIncludeBooleanAttr(isAllSelected.value) ? " checked" : ""} aria-label="Select all rows"></div>`);
          } else {
            _push(`<!---->`);
          }
          _push(`<!--[-->`);
          ssrRenderList(__props.columns, (col) => {
            var _a3, _b3;
            _push(`<div class="${ssrRenderClass([col.headerClass || "flex-1", isSortableColumn(col) && "cursor-pointer select-none hover:text-ui-foreground"])}"${ssrRenderAttr("role", isSortableColumn(col) ? "button" : void 0)}${ssrRenderAttr("tabindex", isSortableColumn(col) ? 0 : void 0)}${ssrRenderAttr("aria-sort", isSortableColumn(col) ? ((_a3 = __props.sort) == null ? void 0 : _a3.key) === col.key ? __props.sort.direction === "asc" ? "ascending" : "descending" : "none" : void 0)}><div class="flex items-center gap-1">${ssrInterpolate(col.label)} `);
            if (isSortableColumn(col)) {
              _push(`<span class="inline-flex items-center">`);
              ssrRenderVNode(_push, createVNode(resolveDynamicComponent(getSortIcon(col.key)), {
                class: unref(cn)("h-3.5 w-3.5 transition-colors", getSortIconColor(col.key), (_b3 = __props.ui) == null ? void 0 : _b3.sortIcon)
              }, null), _parent);
              if (isMultiSortMode.value && __props.sorts && __props.sorts.length > 1 && getSortPosition(col.key) > 0) {
                _push(`<span class="text-[10px] font-bold text-ui-primary leading-none ml-0.5">${ssrInterpolate(getSortPosition(col.key))}</span>`);
              } else {
                _push(`<!---->`);
              }
              _push(`</span>`);
            } else {
              _push(`<!---->`);
            }
            _push(`</div></div>`);
          });
          _push(`<!--]-->`);
          if (__props.expandable) {
            _push(`<div class="${ssrRenderClass(styles.value.expansionCell)}" style="${ssrRenderStyle((_c2 = __props.ui) == null ? void 0 : _c2.expansionCellStyle)}"></div>`);
          } else {
            _push(`<!---->`);
          }
          _push(`</div>`);
        }, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      if (__props.loading) {
        _push(`<div class="py-8">`);
        ssrRenderSlot(_ctx.$slots, "loading", {}, () => {
          _push(`<div class="flex items-center justify-center gap-3"><div class="w-5 h-5 border-2 border-ui-primary/30 border-t-ui-primary rounded-full animate-spin"></div><span class="text-ui-muted-foreground text-sm">Loading...</span></div>`);
        }, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<div class="${ssrRenderClass(styles.value.body)}" style="${ssrRenderStyle((_c = __props.ui) == null ? void 0 : _c.bodyStyle)}">`);
        if (__props.items.length > 0) {
          _push(`<!--[-->`);
          ssrRenderList(__props.items, (item, index) => {
            var _a2, _b2;
            _push(`<!--[--><div class="${ssrRenderClass([styles.value.row, getRowStripedClass(index)])}" style="${ssrRenderStyle((_a2 = __props.ui) == null ? void 0 : _a2.rowStyle)}">`);
            ssrRenderSlot(_ctx.$slots, "row", {
              item,
              index
            }, () => {
              var _a3, _b3;
              if (__props.selectable) {
                _push(`<div class="${ssrRenderClass(styles.value.selectionCell)}" style="${ssrRenderStyle((_a3 = __props.ui) == null ? void 0 : _a3.selectionCellStyle)}" data-no-row-click><input type="checkbox" class="rounded border-ui-border text-ui-primary shadow-sm focus:ring-ui-ring"${ssrIncludeBooleanAttr(isItemSelected(item)) ? " checked" : ""}${ssrRenderAttr("aria-label", `Select row ${index + 1}`)}></div>`);
              } else {
                _push(`<!---->`);
              }
              _push(`<!--[-->`);
              ssrRenderList(__props.columns, (col) => {
                var _a4, _b4;
                _push(`<div class="${ssrRenderClass(unref(cn)(col.class || "flex-1", styles.value.cell))}" style="${ssrRenderStyle((_a4 = __props.ui) == null ? void 0 : _a4.cellStyle)}">`);
                if (__props.responsive) {
                  _push(`<span class="${ssrRenderClass(styles.value.cardLabel)}" style="${ssrRenderStyle((_b4 = __props.ui) == null ? void 0 : _b4.cardLabelStyle)}">${ssrInterpolate(col.label)}</span>`);
                } else {
                  _push(`<!---->`);
                }
                ssrRenderSlot(_ctx.$slots, `cell-${col.key}`, {
                  item,
                  value: item[col.key]
                }, () => {
                  _push(`${ssrInterpolate(item[col.key])}`);
                }, _push, _parent);
                _push(`</div>`);
              });
              _push(`<!--]-->`);
              if (__props.expandable) {
                _push(`<div class="${ssrRenderClass(styles.value.expansionCell)}" style="${ssrRenderStyle((_b3 = __props.ui) == null ? void 0 : _b3.expansionCellStyle)}" data-no-row-click><button type="button" class="p-1 rounded hover:bg-ui-muted/40 transition-colors"${ssrRenderAttr("aria-label", isItemExpanded(item) ? "Collapse row" : "Expand row")}${ssrRenderAttr("aria-expanded", isItemExpanded(item))}>`);
                _push(ssrRenderComponent(unref(ChevronDown), {
                  class: ["h-4 w-4 text-ui-muted-foreground transition-transform duration-200", { "rotate-180": isItemExpanded(item) }]
                }, null, _parent));
                _push(`</button></div>`);
              } else {
                _push(`<!---->`);
              }
            }, _push, _parent);
            _push(`</div>`);
            if (__props.expandable && isItemExpanded(item)) {
              _push(`<div class="${ssrRenderClass(styles.value.expansionRow)}" style="${ssrRenderStyle((_b2 = __props.ui) == null ? void 0 : _b2.expansionRowStyle)}">`);
              ssrRenderSlot(_ctx.$slots, "expansion", {
                item,
                index
              }, null, _push, _parent);
              _push(`</div>`);
            } else {
              _push(`<!---->`);
            }
            _push(`<!--]-->`);
          });
          _push(`<!--]-->`);
        } else {
          _push(`<div class="${ssrRenderClass(styles.value.empty)}" style="${ssrRenderStyle((_d = __props.ui) == null ? void 0 : _d.emptyStyle)}">`);
          ssrRenderSlot(_ctx.$slots, "empty", {}, () => {
            _push(`<p class="text-ui-muted-foreground">No data available</p>`);
          }, _push, _parent);
          _push(`</div>`);
        }
        _push(`</div>`);
      }
      if (_ctx.$slots.footer) {
        _push(`<div class="${ssrRenderClass(styles.value.footer)}" style="${ssrRenderStyle((_e = __props.ui) == null ? void 0 : _e.footerStyle)}">`);
        ssrRenderSlot(_ctx.$slots, "footer", {}, null, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$i = _sfc_main$i.setup;
_sfc_main$i.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/DataTable.vue");
  return _sfc_setup$i ? _sfc_setup$i(props, ctx) : void 0;
};
const _sfc_main$h = /* @__PURE__ */ defineComponent({
  __name: "DataTableRowActions",
  __ssrInlineRender: true,
  props: {
    items: {},
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)("flex items-center justify-end", props.ui.root)
      }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$Z, {
        items: __props.items,
        align: "right",
        width: "48"
      }, {
        trigger: withCtx((_2, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<button type="button" class="${ssrRenderClass(unref(cn)(
              "p-1.5 rounded-md hover:bg-ui-muted text-ui-muted-foreground hover:text-ui-foreground transition-colors",
              props.ui.trigger
            ))}" aria-label="Row actions"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(MoreHorizontal), {
              class: unref(cn)("h-4 w-4", props.ui.icon)
            }, null, _parent2, _scopeId));
            _push2(`</button>`);
          } else {
            return [
              createVNode("button", {
                type: "button",
                class: unref(cn)(
                  "p-1.5 rounded-md hover:bg-ui-muted text-ui-muted-foreground hover:text-ui-foreground transition-colors",
                  props.ui.trigger
                ),
                "aria-label": "Row actions"
              }, [
                createVNode(unref(MoreHorizontal), {
                  class: unref(cn)("h-4 w-4", props.ui.icon)
                }, null, 8, ["class"])
              ], 2)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div>`);
    };
  }
});
const _sfc_setup$h = _sfc_main$h.setup;
_sfc_main$h.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/DataTableRowActions.vue");
  return _sfc_setup$h ? _sfc_setup$h(props, ctx) : void 0;
};
function useDebounce(source, delay = 300) {
  const debounced = ref(source.value);
  let timeout = null;
  function flush() {
    if (timeout) {
      clearTimeout(timeout);
      timeout = null;
    }
    debounced.value = source.value;
  }
  watch(source, (val) => {
    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(() => {
      debounced.value = val;
      timeout = null;
    }, delay);
  });
  onUnmounted(() => {
    if (timeout) clearTimeout(timeout);
  });
  return { debounced, flush };
}
const _sfc_main$g = /* @__PURE__ */ defineComponent({
  __name: "Pagination",
  __ssrInlineRender: true,
  props: {
    links: { default: void 0 },
    meta: { default: void 0 },
    simple: { type: Boolean, default: false },
    preserveState: { type: Boolean, default: true },
    preserveScroll: { type: Boolean, default: true },
    ui: { default: () => ({}) }
  },
  emits: ["page-change"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const isLinkMode = computed(() => Array.isArray(props.links) && props.links.length > 0);
    const isEventMode = computed(() => !isLinkMode.value && props.meta != null);
    const shouldRender = computed(() => {
      if (isLinkMode.value && props.links) {
        const pageLinks = props.links.slice(1, -1);
        return pageLinks.length > 1;
      }
      if (isEventMode.value && props.meta) {
        return props.meta.last_page > 1;
      }
      return false;
    });
    const currentPage = computed(() => {
      var _a;
      if (isLinkMode.value && props.links) {
        const pageLinks = props.links.slice(1, -1);
        const active = pageLinks.find((l2) => l2.active);
        if (active) return parseInt(active.label, 10) || 1;
        return 1;
      }
      return ((_a = props.meta) == null ? void 0 : _a.current_page) ?? 1;
    });
    const lastPage = computed(() => {
      if (isEventMode.value && props.meta) {
        return props.meta.last_page;
      }
      if (isLinkMode.value && props.links) {
        const pageLinks = props.links.slice(1, -1);
        if (pageLinks.length > 0) {
          return parseInt(pageLinks[pageLinks.length - 1].label, 10) || pageLinks.length;
        }
      }
      return 1;
    });
    const visiblePages = computed(() => {
      const total = lastPage.value;
      const current = currentPage.value;
      if (total <= 7) {
        return Array.from({ length: total }, (_2, i2) => i2 + 1);
      }
      const pages = [];
      const delta = 1;
      const rangeStart = Math.max(2, current - delta);
      const rangeEnd = Math.min(total - 1, current + delta);
      pages.push(1);
      if (rangeStart > 2) {
        pages.push(null);
      }
      for (let i2 = rangeStart; i2 <= rangeEnd; i2++) {
        pages.push(i2);
      }
      if (rangeEnd < total - 1) {
        pages.push(null);
      }
      pages.push(total);
      return pages;
    });
    const prevLink = computed(() => {
      if (!isLinkMode.value || !props.links) return null;
      return props.links[0] ?? null;
    });
    const nextLink = computed(() => {
      if (!isLinkMode.value || !props.links) return null;
      return props.links[props.links.length - 1] ?? null;
    });
    const pageLinkMap = computed(() => {
      const map = /* @__PURE__ */ new Map();
      if (!isLinkMode.value || !props.links) return map;
      const pageLinks = props.links.slice(1, -1);
      for (const link of pageLinks) {
        const num = parseInt(link.label, 10);
        if (!isNaN(num)) {
          map.set(num, link);
        }
      }
      return map;
    });
    const styles = computed(() => {
      const { ui } = props;
      return {
        root: cn("flex flex-col sm:flex-row items-center justify-between gap-4", ui == null ? void 0 : ui.root),
        nav: cn("flex items-center gap-1.5", ui == null ? void 0 : ui.nav),
        button: cn(
          "px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-ui-ring focus:ring-offset-1 focus:ring-offset-ui-background",
          "bg-ui-card text-ui-muted-foreground hover:bg-ui-secondary hover:text-ui-foreground border border-ui-border",
          ui == null ? void 0 : ui.button
        ),
        activeButton: cn(
          "px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-ui-ring focus:ring-offset-1 focus:ring-offset-ui-background",
          "bg-ui-primary text-ui-primary-foreground shadow-md shadow-ui-primary/20 border border-transparent",
          ui == null ? void 0 : ui.activeButton
        ),
        disabledButton: cn(
          "px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200",
          "bg-ui-card text-ui-muted-foreground border border-ui-border opacity-40 pointer-events-none",
          ui == null ? void 0 : ui.disabledButton
        ),
        ellipsis: cn(
          "px-2 py-1.5 text-sm text-ui-muted-foreground select-none",
          ui == null ? void 0 : ui.ellipsis
        ),
        info: cn(
          "text-sm text-ui-muted-foreground",
          ui == null ? void 0 : ui.info
        )
      };
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k;
      if (shouldRender.value) {
        _push(`<div${ssrRenderAttrs(mergeProps({
          class: styles.value.root,
          style: (_a = __props.ui) == null ? void 0 : _a.rootStyle
        }, _attrs))}>`);
        if (__props.meta && __props.meta.from != null && __props.meta.to != null) {
          _push(`<p class="${ssrRenderClass(styles.value.info)}" style="${ssrRenderStyle((_b = __props.ui) == null ? void 0 : _b.infoStyle)}"> Showing <span class="font-medium text-ui-foreground">${ssrInterpolate(__props.meta.from)}</span> to <span class="font-medium text-ui-foreground">${ssrInterpolate(__props.meta.to)}</span> of <span class="font-medium text-ui-foreground">${ssrInterpolate(__props.meta.total)}</span> results </p>`);
        } else {
          _push(`<div class="hidden sm:block"></div>`);
        }
        _push(`<nav class="${ssrRenderClass(styles.value.nav)}" style="${ssrRenderStyle((_c = __props.ui) == null ? void 0 : _c.navStyle)}" aria-label="Pagination">`);
        if (isLinkMode.value) {
          _push(`<!--[-->`);
          if (prevLink.value && prevLink.value.url) {
            _push(ssrRenderComponent(unref(Link), {
              href: prevLink.value.url,
              "preserve-state": __props.preserveState,
              "preserve-scroll": __props.preserveScroll,
              class: styles.value.button,
              style: (_d = __props.ui) == null ? void 0 : _d.buttonStyle,
              "aria-label": "Previous page"
            }, {
              default: withCtx((_2, _push2, _parent2, _scopeId) => {
                if (_push2) {
                  _push2(ssrRenderComponent(unref(ChevronLeft), {
                    class: "h-4 w-4",
                    "aria-hidden": "true"
                  }, null, _parent2, _scopeId));
                } else {
                  return [
                    createVNode(unref(ChevronLeft), {
                      class: "h-4 w-4",
                      "aria-hidden": "true"
                    })
                  ];
                }
              }),
              _: 1
            }, _parent));
          } else {
            _push(`<span class="${ssrRenderClass(styles.value.disabledButton)}" style="${ssrRenderStyle((_e = __props.ui) == null ? void 0 : _e.disabledButtonStyle)}" aria-disabled="true" aria-label="Previous page">`);
            _push(ssrRenderComponent(unref(ChevronLeft), {
              class: "h-4 w-4",
              "aria-hidden": "true"
            }, null, _parent));
            _push(`</span>`);
          }
          if (!__props.simple) {
            _push(`<!--[-->`);
            ssrRenderList(visiblePages.value, (pageNum, idx) => {
              var _a2, _b2, _c2, _d2, _e2, _f2, _g2;
              _push(`<!--[-->`);
              if (pageNum === null) {
                _push(`<span class="${ssrRenderClass(styles.value.ellipsis)}" style="${ssrRenderStyle((_a2 = __props.ui) == null ? void 0 : _a2.ellipsisStyle)}" aria-hidden="true"> … </span>`);
              } else {
                _push(`<!--[-->`);
                if (((_b2 = pageLinkMap.value.get(pageNum)) == null ? void 0 : _b2.url) && !((_c2 = pageLinkMap.value.get(pageNum)) == null ? void 0 : _c2.active)) {
                  _push(ssrRenderComponent(unref(Link), {
                    href: pageLinkMap.value.get(pageNum).url,
                    "preserve-state": __props.preserveState,
                    "preserve-scroll": __props.preserveScroll,
                    class: styles.value.button,
                    style: (_d2 = __props.ui) == null ? void 0 : _d2.buttonStyle,
                    "aria-label": `Page ${pageNum}`
                  }, {
                    default: withCtx((_2, _push2, _parent2, _scopeId) => {
                      if (_push2) {
                        _push2(`${ssrInterpolate(pageNum)}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(pageNum), 1)
                        ];
                      }
                    }),
                    _: 2
                  }, _parent));
                } else if ((_e2 = pageLinkMap.value.get(pageNum)) == null ? void 0 : _e2.active) {
                  _push(`<span class="${ssrRenderClass(styles.value.activeButton)}" style="${ssrRenderStyle((_f2 = __props.ui) == null ? void 0 : _f2.activeButtonStyle)}" aria-current="page"${ssrRenderAttr("aria-label", `Page ${pageNum}, current`)}>${ssrInterpolate(pageNum)}</span>`);
                } else {
                  _push(`<span class="${ssrRenderClass(styles.value.disabledButton)}" style="${ssrRenderStyle((_g2 = __props.ui) == null ? void 0 : _g2.disabledButtonStyle)}" aria-disabled="true"${ssrRenderAttr("aria-label", `Page ${pageNum}`)}>${ssrInterpolate(pageNum)}</span>`);
                }
                _push(`<!--]-->`);
              }
              _push(`<!--]-->`);
            });
            _push(`<!--]-->`);
          } else {
            _push(`<!---->`);
          }
          if (nextLink.value && nextLink.value.url) {
            _push(ssrRenderComponent(unref(Link), {
              href: nextLink.value.url,
              "preserve-state": __props.preserveState,
              "preserve-scroll": __props.preserveScroll,
              class: styles.value.button,
              style: (_f = __props.ui) == null ? void 0 : _f.buttonStyle,
              "aria-label": "Next page"
            }, {
              default: withCtx((_2, _push2, _parent2, _scopeId) => {
                if (_push2) {
                  _push2(ssrRenderComponent(unref(ChevronRight), {
                    class: "h-4 w-4",
                    "aria-hidden": "true"
                  }, null, _parent2, _scopeId));
                } else {
                  return [
                    createVNode(unref(ChevronRight), {
                      class: "h-4 w-4",
                      "aria-hidden": "true"
                    })
                  ];
                }
              }),
              _: 1
            }, _parent));
          } else {
            _push(`<span class="${ssrRenderClass(styles.value.disabledButton)}" style="${ssrRenderStyle((_g = __props.ui) == null ? void 0 : _g.disabledButtonStyle)}" aria-disabled="true" aria-label="Next page">`);
            _push(ssrRenderComponent(unref(ChevronRight), {
              class: "h-4 w-4",
              "aria-hidden": "true"
            }, null, _parent));
            _push(`</span>`);
          }
          _push(`<!--]-->`);
        } else if (isEventMode.value && __props.meta) {
          _push(`<!--[--><button type="button" class="${ssrRenderClass(currentPage.value <= 1 ? styles.value.disabledButton : styles.value.button)}" style="${ssrRenderStyle(currentPage.value <= 1 ? (_h = __props.ui) == null ? void 0 : _h.disabledButtonStyle : (_i = __props.ui) == null ? void 0 : _i.buttonStyle)}"${ssrIncludeBooleanAttr(currentPage.value <= 1) ? " disabled" : ""}${ssrRenderAttr("aria-disabled", currentPage.value <= 1 || void 0)} aria-label="Previous page">`);
          _push(ssrRenderComponent(unref(ChevronLeft), {
            class: "h-4 w-4",
            "aria-hidden": "true"
          }, null, _parent));
          _push(`</button>`);
          if (!__props.simple) {
            _push(`<!--[-->`);
            ssrRenderList(visiblePages.value, (pageNum, idx) => {
              var _a2, _b2, _c2;
              _push(`<!--[-->`);
              if (pageNum === null) {
                _push(`<span class="${ssrRenderClass(styles.value.ellipsis)}" style="${ssrRenderStyle((_a2 = __props.ui) == null ? void 0 : _a2.ellipsisStyle)}" aria-hidden="true"> … </span>`);
              } else {
                _push(`<button type="button" class="${ssrRenderClass(pageNum === currentPage.value ? styles.value.activeButton : styles.value.button)}" style="${ssrRenderStyle(pageNum === currentPage.value ? (_b2 = __props.ui) == null ? void 0 : _b2.activeButtonStyle : (_c2 = __props.ui) == null ? void 0 : _c2.buttonStyle)}"${ssrRenderAttr("aria-current", pageNum === currentPage.value ? "page" : void 0)}${ssrRenderAttr("aria-label", pageNum === currentPage.value ? `Page ${pageNum}, current` : `Page ${pageNum}`)}>${ssrInterpolate(pageNum)}</button>`);
              }
              _push(`<!--]-->`);
            });
            _push(`<!--]-->`);
          } else {
            _push(`<!---->`);
          }
          _push(`<button type="button" class="${ssrRenderClass(currentPage.value >= __props.meta.last_page ? styles.value.disabledButton : styles.value.button)}" style="${ssrRenderStyle(currentPage.value >= __props.meta.last_page ? (_j = __props.ui) == null ? void 0 : _j.disabledButtonStyle : (_k = __props.ui) == null ? void 0 : _k.buttonStyle)}"${ssrIncludeBooleanAttr(currentPage.value >= __props.meta.last_page) ? " disabled" : ""}${ssrRenderAttr("aria-disabled", currentPage.value >= __props.meta.last_page || void 0)} aria-label="Next page">`);
          _push(ssrRenderComponent(unref(ChevronRight), {
            class: "h-4 w-4",
            "aria-hidden": "true"
          }, null, _parent));
          _push(`</button><!--]-->`);
        } else {
          _push(`<!---->`);
        }
        _push(`</nav></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
});
const _sfc_setup$g = _sfc_main$g.setup;
_sfc_main$g.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Pagination.vue");
  return _sfc_setup$g ? _sfc_setup$g(props, ctx) : void 0;
};
const _sfc_main$f = /* @__PURE__ */ defineComponent({
  __name: "SearchBox",
  __ssrInlineRender: true,
  props: {
    modelValue: { default: "" },
    placeholder: { default: "Search..." },
    disabled: { type: Boolean, default: false },
    showClearButton: { type: Boolean, default: true },
    searchIcon: { default: "search" },
    clearIcon: { default: "x" },
    ui: { default: () => ({}) }
  },
  emits: ["update:modelValue", "clear", "search"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    ref(null);
    const styles = computed(() => {
      const { ui } = props;
      return {
        root: cn(
          "relative flex items-center",
          "bg-ui-input border border-ui-border rounded-lg",
          "transition-all duration-200",
          "focus-within:border-ui-ring focus-within:ring-2 focus-within:ring-ui-ring/20",
          props.disabled && "opacity-50 cursor-not-allowed",
          ui.root
        ),
        input: cn(
          "w-full bg-transparent py-2.5 pl-10 pr-10",
          "text-ui-foreground text-sm",
          "placeholder:text-ui-muted-foreground",
          "focus:outline-none",
          "disabled:cursor-not-allowed",
          ui.input
        ),
        icon: cn(
          "absolute left-3 top-1/2 -translate-y-1/2",
          "text-ui-muted-foreground",
          "pointer-events-none",
          ui.icon
        ),
        clearButton: cn(
          "absolute right-2 top-1/2 -translate-y-1/2",
          "p-1 rounded",
          "text-ui-muted-foreground hover:text-ui-foreground",
          "hover:bg-ui-muted/50",
          "transition-colors",
          "focus:outline-none focus:ring-2 focus:ring-ui-ring/50",
          ui.clearButton
        )
      };
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d;
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: styles.value.root,
        style: (_a = __props.ui) == null ? void 0 : _a.rootStyle
      }, _attrs))}>`);
      _push(ssrRenderComponent(_sfc_main$13, {
        icon: __props.searchIcon,
        class: [styles.value.icon, "w-4 h-4"],
        ui: { rootStyle: (_b = __props.ui) == null ? void 0 : _b.iconStyle }
      }, null, _parent));
      _push(`<input type="text"${ssrRenderAttr("value", __props.modelValue)}${ssrRenderAttr("placeholder", __props.placeholder)}${ssrIncludeBooleanAttr(__props.disabled) ? " disabled" : ""} class="${ssrRenderClass(styles.value.input)}" style="${ssrRenderStyle((_c = __props.ui) == null ? void 0 : _c.inputStyle)}">`);
      if (__props.showClearButton && __props.modelValue) {
        _push(`<button type="button" class="${ssrRenderClass(styles.value.clearButton)}" style="${ssrRenderStyle((_d = __props.ui) == null ? void 0 : _d.clearButtonStyle)}">`);
        _push(ssrRenderComponent(_sfc_main$13, {
          icon: __props.clearIcon,
          class: "w-4 h-4"
        }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$f = _sfc_main$f.setup;
_sfc_main$f.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/SearchBox.vue");
  return _sfc_setup$f ? _sfc_setup$f(props, ctx) : void 0;
};
const _sfc_main$e = /* @__PURE__ */ defineComponent({
  __name: "ServerDataTable",
  __ssrInlineRender: true,
  props: {
    data: {},
    meta: {},
    links: { default: void 0 },
    columns: { default: () => [] },
    itemKey: {},
    loading: { type: Boolean, default: false },
    searchable: { type: Boolean, default: false },
    searchQuery: { default: void 0 },
    searchPlaceholder: { default: "Search..." },
    sortable: { type: Boolean, default: false },
    sort: { default: void 0 },
    sorts: { default: void 0 },
    simplePagination: { type: Boolean, default: false },
    responsive: { type: Boolean, default: true },
    preserveState: { type: Boolean, default: true },
    preserveScroll: { type: Boolean, default: true },
    selectable: { type: Boolean, default: false },
    selectedItems: { default: void 0 },
    enableRowClick: { type: Boolean, default: false },
    expandable: { type: Boolean, default: false },
    expandedRows: { default: void 0 },
    stickyHeader: { type: Boolean, default: false },
    density: { default: "normal" },
    striped: { type: Boolean, default: false },
    ui: { default: () => ({}) }
  },
  emits: ["sort-change", "sorts-change", "search", "update:searchQuery", "update:selectedItems", "update:expandedRows", "row-click", "select-all"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const items = computed(() => props.data ?? []);
    const localSearch = ref(props.searchQuery ?? "");
    const { debounced: debouncedSearch } = useDebounce(localSearch, 300);
    function updateSearch(value) {
      localSearch.value = value;
      emit("update:searchQuery", value);
    }
    watch(debouncedSearch, (val) => {
      emit("search", val);
    });
    watch(
      () => props.searchQuery,
      (val) => {
        if (val !== void 0 && val !== localSearch.value) {
          localSearch.value = val;
        }
      }
    );
    const serverOnlyKeys = /* @__PURE__ */ new Set(["pagination", "toolbar", "toolbarStyle", "search", "searchStyle"]);
    const dataTableUi = computed(() => {
      if (!props.ui) {
        return props.searchable ? { root: "rounded-t-none border-t-0" } : {};
      }
      const rest = {};
      for (const [key, value] of Object.entries(props.ui)) {
        if (!serverOnlyKeys.has(key)) {
          rest[key] = value;
        }
      }
      if (props.searchable) {
        rest.root = cn("rounded-t-none border-t-0", rest.root);
      }
      return rest;
    });
    const styles = computed(() => {
      var _a, _b;
      return {
        root: cn("", (_a = props.ui) == null ? void 0 : _a.root),
        toolbar: cn(
          "px-6 py-4 bg-ui-card border border-ui-border border-b-0 rounded-t-xl",
          (_b = props.ui) == null ? void 0 : _b.toolbar
        )
      };
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b;
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: styles.value.root,
        style: (_a = __props.ui) == null ? void 0 : _a.rootStyle
      }, _attrs))}>`);
      if (__props.searchable) {
        _push(`<div class="${ssrRenderClass(styles.value.toolbar)}" style="${ssrRenderStyle((_b = __props.ui) == null ? void 0 : _b.toolbarStyle)}">`);
        ssrRenderSlot(_ctx.$slots, "toolbar", {
          searchQuery: localSearch.value,
          updateSearch
        }, () => {
          var _a2, _b2;
          _push(ssrRenderComponent(_sfc_main$f, {
            "model-value": localSearch.value,
            placeholder: __props.searchPlaceholder,
            ui: { root: unref(cn)("w-full md:w-80", (_a2 = props.ui) == null ? void 0 : _a2.search), rootStyle: (_b2 = __props.ui) == null ? void 0 : _b2.searchStyle },
            "onUpdate:modelValue": updateSearch
          }, null, _parent));
        }, _push, _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(ssrRenderComponent(_sfc_main$i, {
        columns: __props.columns,
        items: items.value,
        "item-key": __props.itemKey,
        loading: __props.loading,
        sort: __props.sort,
        sorts: __props.sorts,
        sortable: __props.sortable,
        responsive: __props.responsive,
        selectable: __props.selectable,
        "selected-items": __props.selectedItems,
        "enable-row-click": __props.enableRowClick,
        expandable: __props.expandable,
        "expanded-rows": __props.expandedRows,
        "sticky-header": __props.stickyHeader,
        density: __props.density,
        striped: __props.striped,
        ui: dataTableUi.value,
        onSortChange: ($event) => emit("sort-change", $event),
        onSortsChange: ($event) => emit("sorts-change", $event),
        "onUpdate:selectedItems": ($event) => emit("update:selectedItems", $event),
        "onUpdate:expandedRows": ($event) => emit("update:expandedRows", $event),
        onRowClick: (item, index, evt) => emit("row-click", item, index, evt),
        onSelectAll: ($event) => emit("select-all", $event)
      }, createSlots({
        footer: withCtx((_2, _push2, _parent2, _scopeId) => {
          if (_push2) {
            ssrRenderSlot(_ctx.$slots, "footer", {}, () => {
              var _a2;
              if (props.meta.last_page > 1) {
                _push2(ssrRenderComponent(_sfc_main$g, {
                  links: props.links,
                  meta: props.meta,
                  simple: __props.simplePagination,
                  "preserve-state": __props.preserveState,
                  "preserve-scroll": __props.preserveScroll,
                  ui: (_a2 = __props.ui) == null ? void 0 : _a2.pagination
                }, null, _parent2, _scopeId));
              } else {
                _push2(`<!---->`);
              }
            }, _push2, _parent2, _scopeId);
          } else {
            return [
              renderSlot(_ctx.$slots, "footer", {}, () => {
                var _a2;
                return [
                  props.meta.last_page > 1 ? (openBlock(), createBlock(_sfc_main$g, {
                    key: 0,
                    links: props.links,
                    meta: props.meta,
                    simple: __props.simplePagination,
                    "preserve-state": __props.preserveState,
                    "preserve-scroll": __props.preserveScroll,
                    ui: (_a2 = __props.ui) == null ? void 0 : _a2.pagination
                  }, null, 8, ["links", "meta", "simple", "preserve-state", "preserve-scroll", "ui"])) : createCommentVNode("", true)
                ];
              })
            ];
          }
        }),
        _: 2
      }, [
        _ctx.$slots.header ? {
          name: "header",
          fn: withCtx((_2, _push2, _parent2, _scopeId) => {
            if (_push2) {
              ssrRenderSlot(_ctx.$slots, "header", {}, null, _push2, _parent2, _scopeId);
            } else {
              return [
                renderSlot(_ctx.$slots, "header")
              ];
            }
          }),
          key: "0"
        } : void 0,
        _ctx.$slots.loading ? {
          name: "loading",
          fn: withCtx((_2, _push2, _parent2, _scopeId) => {
            if (_push2) {
              ssrRenderSlot(_ctx.$slots, "loading", {}, null, _push2, _parent2, _scopeId);
            } else {
              return [
                renderSlot(_ctx.$slots, "loading")
              ];
            }
          }),
          key: "1"
        } : void 0,
        _ctx.$slots.row ? {
          name: "row",
          fn: withCtx((slotProps, _push2, _parent2, _scopeId) => {
            if (_push2) {
              ssrRenderSlot(_ctx.$slots, "row", slotProps, null, _push2, _parent2, _scopeId);
            } else {
              return [
                renderSlot(_ctx.$slots, "row", slotProps)
              ];
            }
          }),
          key: "2"
        } : void 0,
        renderList(__props.columns ?? [], (col) => {
          return {
            name: `cell-${col.key}`,
            fn: withCtx((slotProps, _push2, _parent2, _scopeId) => {
              if (_push2) {
                ssrRenderSlot(_ctx.$slots, `cell-${col.key}`, slotProps, null, _push2, _parent2, _scopeId);
              } else {
                return [
                  renderSlot(_ctx.$slots, `cell-${col.key}`, slotProps)
                ];
              }
            })
          };
        }),
        _ctx.$slots.expansion ? {
          name: "expansion",
          fn: withCtx((slotProps, _push2, _parent2, _scopeId) => {
            if (_push2) {
              ssrRenderSlot(_ctx.$slots, "expansion", slotProps, null, _push2, _parent2, _scopeId);
            } else {
              return [
                renderSlot(_ctx.$slots, "expansion", slotProps)
              ];
            }
          }),
          key: "3"
        } : void 0,
        _ctx.$slots.empty ? {
          name: "empty",
          fn: withCtx((_2, _push2, _parent2, _scopeId) => {
            if (_push2) {
              ssrRenderSlot(_ctx.$slots, "empty", {}, null, _push2, _parent2, _scopeId);
            } else {
              return [
                renderSlot(_ctx.$slots, "empty")
              ];
            }
          }),
          key: "4"
        } : void 0
      ]), _parent));
      _push(`</div>`);
    };
  }
});
const _sfc_setup$e = _sfc_main$e.setup;
_sfc_main$e.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/ServerDataTable.vue");
  return _sfc_setup$e ? _sfc_setup$e(props, ctx) : void 0;
};
const _sfc_main$d = /* @__PURE__ */ defineComponent({
  __name: "AvatarInitials",
  __ssrInlineRender: true,
  props: {
    name: {},
    size: { default: "md" },
    color: { default: "gradient" },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const initials = computed(() => {
      if (!props.name) return "?";
      const parts = props.name.trim().split(/\s+/);
      if (parts.length === 1) {
        return parts[0].charAt(0).toUpperCase();
      }
      return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
    });
    const sizeClasses = computed(() => {
      const sizes = {
        xs: "w-6 h-6 text-xs",
        sm: "w-8 h-8 text-xs",
        md: "w-10 h-10 text-sm",
        lg: "w-12 h-12 text-base",
        xl: "w-16 h-16 text-xl"
      };
      return sizes[props.size];
    });
    const colorClasses = computed(() => {
      const colors = {
        primary: "bg-ui-primary text-ui-primary-foreground",
        gradient: "bg-gradient-to-br from-ui-primary to-ui-primary-hover text-ui-primary-foreground",
        muted: "bg-ui-muted text-ui-muted-foreground"
      };
      return colors[props.color];
    });
    const styles = computed(() => {
      const { ui } = props;
      return {
        root: cn(
          "inline-flex items-center justify-center rounded-xl",
          "font-bold",
          "ring-1 ring-white/20",
          "shadow-lg",
          sizeClasses.value,
          colorClasses.value,
          ui.root
        ),
        initials: cn(
          "select-none",
          ui.initials
        )
      };
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b;
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: styles.value.root,
        style: (_a = __props.ui) == null ? void 0 : _a.rootStyle,
        title: __props.name
      }, _attrs))}><span class="${ssrRenderClass(styles.value.initials)}" style="${ssrRenderStyle((_b = __props.ui) == null ? void 0 : _b.initialsStyle)}">${ssrInterpolate(initials.value)}</span></div>`);
    };
  }
});
const _sfc_setup$d = _sfc_main$d.setup;
_sfc_main$d.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/AvatarInitials.vue");
  return _sfc_setup$d ? _sfc_setup$d(props, ctx) : void 0;
};
const _sfc_main$c = /* @__PURE__ */ defineComponent({
  __name: "AvatarUploader",
  __ssrInlineRender: true,
  props: {
    src: { default: void 0 },
    name: { default: void 0 },
    size: { default: "lg" },
    loading: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    removable: { type: Boolean, default: true },
    croppable: { type: Boolean, default: true },
    accept: { default: "image/png, image/jpeg, image/gif, image/webp" },
    ui: { default: () => ({}) }
  },
  emits: ["update:src", "remove"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    ref(null);
    const previewUrl = ref(null);
    const isDragOver = ref(false);
    const cropperVisible = ref(false);
    const cropperImageUrl = ref(null);
    ref(null);
    const cropperRef = ref(null);
    const initials = computed(() => {
      if (!props.name) return null;
      const parts = props.name.trim().split(/\s+/);
      if (parts.length === 1) {
        return parts[0].charAt(0).toUpperCase();
      }
      return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
    });
    const displaySrc = computed(() => previewUrl.value ?? props.src ?? null);
    const sizeClasses = computed(() => {
      const sizes = {
        sm: "w-16 h-16",
        md: "w-24 h-24",
        lg: "w-32 h-32",
        xl: "w-40 h-40"
      };
      return sizes[props.size];
    });
    const initialsSizeClasses = computed(() => {
      const sizes = {
        sm: "text-lg",
        md: "text-2xl",
        lg: "text-3xl",
        xl: "text-4xl"
      };
      return sizes[props.size];
    });
    const iconSizeClasses = computed(() => {
      const sizes = {
        sm: "w-5 h-5",
        md: "w-7 h-7",
        lg: "w-9 h-9",
        xl: "w-11 h-11"
      };
      return sizes[props.size];
    });
    const overlayIconSizeClasses = computed(() => {
      const sizes = {
        sm: "w-4 h-4",
        md: "w-5 h-5",
        lg: "w-6 h-6",
        xl: "w-7 h-7"
      };
      return sizes[props.size];
    });
    const overlayTextSizeClasses = computed(() => {
      const sizes = {
        sm: "text-[10px]",
        md: "text-xs",
        lg: "text-xs",
        xl: "text-sm"
      };
      return sizes[props.size];
    });
    const styles = computed(() => {
      const { ui } = props;
      return {
        root: cn(
          "relative inline-block rounded-full group",
          sizeClasses.value,
          props.disabled && "opacity-50 cursor-not-allowed",
          ui.root
        ),
        image: cn(
          "w-full h-full rounded-full object-cover",
          "ring-2 ring-ui-border",
          ui.image
        ),
        placeholder: cn(
          "w-full h-full rounded-full flex items-center justify-center",
          "bg-gradient-to-br from-ui-primary to-ui-primary-hover",
          "ring-2 ring-ui-border",
          ui.placeholder
        ),
        initials: cn(
          "font-bold text-ui-primary-foreground select-none",
          initialsSizeClasses.value,
          ui.initials
        ),
        overlay: cn(
          "absolute inset-0 rounded-full flex flex-col items-center justify-center gap-0.5",
          "bg-black/50 opacity-0 group-hover:opacity-100",
          "transition-opacity duration-200",
          "cursor-pointer",
          props.disabled && "pointer-events-none",
          isDragOver.value && "opacity-100 bg-ui-primary/40",
          ui.overlay
        ),
        overlayIcon: cn(
          "text-white",
          overlayIconSizeClasses.value,
          ui.overlayIcon
        ),
        overlayText: cn(
          "text-white font-medium",
          overlayTextSizeClasses.value,
          ui.overlayText
        ),
        loader: cn(
          "absolute inset-0 rounded-full flex items-center justify-center",
          "bg-black/50",
          ui.loader
        ),
        removeButton: cn(
          "absolute -top-1 -right-1 z-10",
          "w-6 h-6 rounded-full flex items-center justify-center",
          "bg-ui-destructive text-ui-destructive-foreground",
          "hover:bg-ui-destructive-hover",
          "shadow-lg transition-all duration-200",
          "opacity-0 group-hover:opacity-100",
          "cursor-pointer",
          "focus:outline-none focus:ring-2 focus:ring-ui-ring focus:ring-offset-2 focus:ring-offset-ui-background",
          ui.removeButton
        ),
        removeIcon: cn(
          "w-3.5 h-3.5",
          ui.removeIcon
        ),
        cropperOverlay: cn(
          "fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm",
          ui.cropperOverlay
        ),
        cropperActions: cn(
          "flex items-center justify-center gap-3 mt-4",
          ui.cropperActions
        )
      };
    });
    onUnmounted(() => {
      if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
      }
      if (cropperImageUrl.value) {
        URL.revokeObjectURL(cropperImageUrl.value);
      }
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j;
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: styles.value.root,
        style: (_a = __props.ui) == null ? void 0 : _a.rootStyle,
        role: "group",
        "aria-label": __props.name ? `Avatar for ${__props.name}` : "Avatar uploader"
      }, _attrs))}><input type="file"${ssrRenderAttr("accept", __props.accept)} class="hidden"${ssrIncludeBooleanAttr(__props.disabled || __props.loading) ? " disabled" : ""} aria-hidden="true" tabindex="-1">`);
      if (__props.removable && displaySrc.value && !__props.loading && !__props.disabled) {
        _push(`<button type="button" class="${ssrRenderClass(styles.value.removeButton)}" style="${ssrRenderStyle((_b = __props.ui) == null ? void 0 : _b.removeButtonStyle)}" aria-label="Remove avatar">`);
        _push(ssrRenderComponent(unref(X), {
          class: styles.value.removeIcon,
          style: (_c = __props.ui) == null ? void 0 : _c.removeIconStyle
        }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="w-full h-full rounded-full overflow-hidden" role="button"${ssrRenderAttr("tabindex", __props.disabled || __props.loading ? -1 : 0)}${ssrRenderAttr("aria-label", displaySrc.value ? "Change avatar image" : "Upload avatar image")}${ssrRenderAttr("aria-disabled", __props.disabled || __props.loading || void 0)}>`);
      if (displaySrc.value) {
        _push(`<img${ssrRenderAttr("src", displaySrc.value)}${ssrRenderAttr("alt", __props.name ? `${__props.name}'s avatar` : "User avatar")} class="${ssrRenderClass(styles.value.image)}" style="${ssrRenderStyle((_d = __props.ui) == null ? void 0 : _d.imageStyle)}">`);
      } else {
        _push(`<div class="${ssrRenderClass(styles.value.placeholder)}" style="${ssrRenderStyle((_e = __props.ui) == null ? void 0 : _e.placeholderStyle)}">`);
        if (initials.value) {
          _push(`<span class="${ssrRenderClass(styles.value.initials)}" style="${ssrRenderStyle((_f = __props.ui) == null ? void 0 : _f.initialsStyle)}">${ssrInterpolate(initials.value)}</span>`);
        } else {
          _push(ssrRenderComponent(unref(User), {
            class: unref(cn)("text-ui-primary-foreground", iconSizeClasses.value)
          }, null, _parent));
        }
        _push(`</div>`);
      }
      if (!__props.loading) {
        _push(`<div class="${ssrRenderClass(styles.value.overlay)}" style="${ssrRenderStyle((_g = __props.ui) == null ? void 0 : _g.overlayStyle)}">`);
        _push(ssrRenderComponent(unref(Camera), {
          class: styles.value.overlayIcon,
          style: (_h = __props.ui) == null ? void 0 : _h.overlayIconStyle
        }, null, _parent));
        _push(`<span class="${ssrRenderClass(styles.value.overlayText)}" style="${ssrRenderStyle((_i = __props.ui) == null ? void 0 : _i.overlayTextStyle)}">${ssrInterpolate(displaySrc.value ? "Change" : "Upload")}</span></div>`);
      } else {
        _push(`<!---->`);
      }
      if (__props.loading) {
        _push(`<div class="${ssrRenderClass(styles.value.loader)}" style="${ssrRenderStyle((_j = __props.ui) == null ? void 0 : _j.loaderStyle)}">`);
        _push(ssrRenderComponent(unref(Loader2), { class: "animate-spin text-white w-6 h-6" }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
      ssrRenderTeleport(_push, (_push2) => {
        var _a2, _b2;
        if (cropperVisible.value && cropperImageUrl.value) {
          _push2(`<div class="${ssrRenderClass(styles.value.cropperOverlay)}" style="${ssrRenderStyle((_a2 = __props.ui) == null ? void 0 : _a2.cropperOverlayStyle)}"><div class="flex flex-col items-center"><div class="w-[300px] h-[300px] rounded-2xl overflow-hidden bg-black">`);
          _push2(ssrRenderComponent(unref(Cropper), {
            ref_key: "cropperRef",
            ref: cropperRef,
            src: cropperImageUrl.value,
            "stencil-component": unref(CircleStencil),
            "stencil-props": { aspectRatio: 1 },
            class: "w-full h-full"
          }, null, _parent));
          _push2(`</div><div class="${ssrRenderClass(styles.value.cropperActions)}" style="${ssrRenderStyle((_b2 = __props.ui) == null ? void 0 : _b2.cropperActionsStyle)}"><button type="button" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-ui-muted text-ui-foreground hover:bg-ui-muted/80 transition-colors cursor-pointer">`);
          _push2(ssrRenderComponent(unref(X), { class: "w-4 h-4" }, null, _parent));
          _push2(` Cancel </button><button type="button" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-ui-primary text-ui-primary-foreground hover:bg-ui-primary-hover transition-colors cursor-pointer">`);
          _push2(ssrRenderComponent(unref(Check), { class: "w-4 h-4" }, null, _parent));
          _push2(` Confirm </button></div></div></div>`);
        } else {
          _push2(`<!---->`);
        }
      }, "body", false, _parent);
      _push(`</div>`);
    };
  }
});
const _sfc_setup$c = _sfc_main$c.setup;
_sfc_main$c.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/AvatarUploader.vue");
  return _sfc_setup$c ? _sfc_setup$c(props, ctx) : void 0;
};
const _sfc_main$b = /* @__PURE__ */ defineComponent({
  __name: "Image",
  __ssrInlineRender: true,
  props: {
    src: {},
    lazy: { default: void 0 },
    lazyFallback: { default: void 0 },
    fallback: { default: void 0 },
    alt: { default: "" },
    objectFit: { default: "cover" },
    rounded: { default: "md" },
    aspectRatio: { default: void 0 },
    lazyLoad: { type: Boolean, default: true },
    loadingType: { default: "spinner" },
    zoomOnClick: { type: Boolean, default: false },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const state = ref("idle");
    const currentSrc = ref(null);
    const isVisible = ref(!props.lazyLoad);
    const containerRef = ref(null);
    const zoomTriggerRef = ref(null);
    const zoomCloseRef = ref(null);
    const hasError = ref(false);
    const loadingProgress = ref(0);
    const isZoomed = ref(false);
    const failedSources = ref(/* @__PURE__ */ new Set());
    const roundedClasses = computed(() => {
      const classes = {
        none: "rounded-none",
        sm: "rounded-sm",
        md: "rounded-md",
        lg: "rounded-lg",
        xl: "rounded-xl",
        full: "rounded-full"
      };
      return classes[props.rounded] || classes.md;
    });
    const objectFitClass = computed(() => {
      const classes = {
        cover: "object-cover",
        contain: "object-contain",
        fill: "object-fill",
        none: "object-none",
        "scale-down": "object-scale-down"
      };
      return classes[props.objectFit] || classes.cover;
    });
    const getAllSources = () => {
      const sources = [];
      if (props.lazy) sources.push(props.lazy);
      if (props.lazyFallback) sources.push(props.lazyFallback);
      if (props.src) sources.push(props.src);
      if (props.fallback) sources.push(props.fallback);
      return sources;
    };
    const allSourcesFailed = computed(() => {
      const allSources = getAllSources();
      if (allSources.length === 0) return true;
      return allSources.every((src) => failedSources.value.has(src));
    });
    const getNextSource = (preferMain = false) => {
      if (preferMain) {
        if (props.src && !failedSources.value.has(props.src)) return props.src;
        if (props.fallback && !failedSources.value.has(props.fallback)) return props.fallback;
      } else {
        if (props.lazy && !failedSources.value.has(props.lazy)) return props.lazy;
        if (props.lazyFallback && !failedSources.value.has(props.lazyFallback)) return props.lazyFallback;
      }
      return null;
    };
    const loadImage = (src) => {
      return new Promise((resolve, reject) => {
        const img = new window.Image();
        img.onload = () => resolve();
        img.onerror = () => reject(new Error(`Failed to load: ${src}`));
        img.src = src;
      });
    };
    const startLoading = async () => {
      if (!isVisible.value) return;
      hasError.value = false;
      loadingProgress.value = 0;
      currentSrc.value = null;
      let progressInterval = null;
      if (props.loadingType === "progress") {
        progressInterval = setInterval(() => {
          if (loadingProgress.value < 90) {
            loadingProgress.value += Math.random() * 15;
          }
        }, 200);
      }
      const lazySrc = getNextSource(false);
      const mainSrc = getNextSource(true);
      if (!mainSrc && !lazySrc) {
        hasError.value = true;
        state.value = "error";
        if (progressInterval) clearInterval(progressInterval);
        return;
      }
      if (lazySrc) {
        state.value = "loading-lazy";
        try {
          await loadImage(lazySrc);
          currentSrc.value = lazySrc;
        } catch {
          failedSources.value.add(lazySrc);
          const fallbackLazy = getNextSource(false);
          if (fallbackLazy) {
            try {
              await loadImage(fallbackLazy);
              currentSrc.value = fallbackLazy;
            } catch {
              failedSources.value.add(fallbackLazy);
            }
          }
        }
      } else {
        state.value = "loading-main";
      }
      if (mainSrc) {
        state.value = "loading-main";
        try {
          await loadImage(mainSrc);
          currentSrc.value = mainSrc;
          state.value = "loaded";
          loadingProgress.value = 100;
        } catch {
          failedSources.value.add(mainSrc);
          const fallbackSrc = getNextSource(true);
          if (fallbackSrc) {
            try {
              await loadImage(fallbackSrc);
              currentSrc.value = fallbackSrc;
              state.value = "loaded";
              loadingProgress.value = 100;
            } catch {
              failedSources.value.add(fallbackSrc);
              hasError.value = true;
              state.value = "error";
              currentSrc.value = null;
            }
          } else {
            hasError.value = true;
            state.value = "error";
            currentSrc.value = null;
          }
        }
      } else {
        hasError.value = true;
        state.value = "error";
        currentSrc.value = null;
      }
      if (progressInterval) clearInterval(progressInterval);
      if (allSourcesFailed.value) {
        hasError.value = true;
        state.value = "error";
        currentSrc.value = null;
      }
    };
    let observer = null;
    onMounted(() => {
      if (props.lazyLoad && containerRef.value) {
        observer = new IntersectionObserver(
          (entries) => {
            if (entries[0].isIntersecting) {
              isVisible.value = true;
              observer == null ? void 0 : observer.disconnect();
            }
          },
          { threshold: 0.1, rootMargin: "50px" }
        );
        observer.observe(containerRef.value);
      } else {
        startLoading();
      }
    });
    onUnmounted(() => {
      observer == null ? void 0 : observer.disconnect();
    });
    const closeZoom = () => {
      isZoomed.value = false;
    };
    const handleZoomKeydown = (event) => {
      if (event.key === "Escape") {
        closeZoom();
      }
    };
    watch(isZoomed, (zoomed) => {
      if (typeof document === "undefined") {
        return;
      }
      if (zoomed) {
        document.body.style.overflow = "hidden";
        window.addEventListener("keydown", handleZoomKeydown);
        nextTick(() => {
          var _a;
          (_a = zoomCloseRef.value) == null ? void 0 : _a.focus();
        });
        return;
      }
      document.body.style.overflow = "";
      window.removeEventListener("keydown", handleZoomKeydown);
      nextTick(() => {
        var _a;
        (_a = zoomTriggerRef.value) == null ? void 0 : _a.focus();
      });
    });
    onUnmounted(() => {
      if (typeof document !== "undefined") {
        document.body.style.overflow = "";
      }
      window.removeEventListener("keydown", handleZoomKeydown);
    });
    watch(isVisible, (visible) => {
      if (visible) {
        startLoading();
      }
    });
    watch(() => props.src, () => {
      failedSources.value.clear();
      currentSrc.value = null;
      state.value = "idle";
      hasError.value = false;
      if (isVisible.value) {
        startLoading();
      }
    });
    const showLoader = computed(() => {
      return (state.value === "loading-main" || state.value === "loading-lazy") && !currentSrc.value && !hasError.value;
    });
    const showImage = computed(() => {
      return currentSrc.value && !hasError.value;
    });
    const imageLoaded = computed(() => {
      return state.value === "loaded";
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        ref_key: "containerRef",
        ref: containerRef,
        class: unref(cn)(
          "relative overflow-hidden bg-ui-muted/20",
          roundedClasses.value,
          __props.ui.root
        ),
        style: [
          __props.aspectRatio ? { aspectRatio: __props.aspectRatio } : {},
          __props.ui.rootStyle
        ]
      }, _attrs))}>`);
      if (showLoader.value && __props.loadingType === "spinner") {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "absolute inset-0 flex items-center justify-center bg-ui-muted/30",
          roundedClasses.value
        ))}">`);
        _push(ssrRenderComponent(_sfc_main$v, {
          size: "lg",
          ui: __props.ui.loader
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      if (showLoader.value && __props.loadingType === "skeleton") {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "absolute inset-0",
          roundedClasses.value
        ))}">`);
        _push(ssrRenderComponent(SkeletonLoader, {
          variant: "rect",
          width: "100%",
          height: "100%"
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      if (showLoader.value && __props.loadingType === "progress") {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "absolute inset-0 flex flex-col items-center justify-center bg-ui-muted/30",
          roundedClasses.value
        ))}"><div class="w-3/4 max-w-[200px]"><div class="h-1 bg-ui-muted rounded-full overflow-hidden"><div class="h-full bg-ui-primary transition-all duration-200 ease-out rounded-full" style="${ssrRenderStyle({ width: `${Math.min(loadingProgress.value, 100)}%` })}"></div></div><p class="text-xs text-ui-muted-foreground text-center mt-2">Loading...</p></div></div>`);
      } else {
        _push(`<!---->`);
      }
      if (showImage.value) {
        _push(`<img${ssrRenderAttr("src", currentSrc.value)}${ssrRenderAttr("alt", __props.alt)}${ssrRenderAttr("role", __props.zoomOnClick ? "button" : void 0)}${ssrRenderAttr("tabindex", __props.zoomOnClick ? 0 : void 0)}${ssrRenderAttr("aria-label", __props.zoomOnClick ? `Open zoom view${__props.alt ? ` for ${__props.alt}` : ""}` : void 0)} class="${ssrRenderClass(unref(cn)(
          "w-full h-full transition-opacity duration-300",
          objectFitClass.value,
          roundedClasses.value,
          imageLoaded.value ? "opacity-100" : "opacity-70",
          __props.zoomOnClick && "cursor-zoom-in",
          __props.ui.image
        ))}" style="${ssrRenderStyle(__props.ui.imageStyle)}">`);
      } else {
        _push(`<!---->`);
      }
      if (hasError.value) {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "absolute inset-0 flex flex-col items-center justify-center gap-3 bg-ui-muted/50",
          roundedClasses.value,
          __props.ui.error
        ))}" style="${ssrRenderStyle(__props.ui.errorStyle)}">`);
        _push(ssrRenderComponent(unref(ImageOff), {
          class: unref(cn)(
            "w-10 h-10 text-ui-muted-foreground/50",
            __props.ui.errorIcon
          ),
          style: __props.ui.errorIconStyle
        }, null, _parent));
        _push(`<span class="${ssrRenderClass(unref(cn)(
          "text-sm text-ui-muted-foreground/70",
          __props.ui.errorText
        ))}" style="${ssrRenderStyle(__props.ui.errorTextStyle)}"> Image not available </span></div>`);
      } else {
        _push(`<!---->`);
      }
      ssrRenderTeleport(_push, (_push2) => {
        if (isZoomed.value && showImage.value) {
          _push2(`<div class="${ssrRenderClass(unref(cn)("fixed inset-0 z-[100] flex items-center justify-center bg-black/75 p-4", __props.ui.zoomOverlay))}" style="${ssrRenderStyle(__props.ui.zoomOverlayStyle)}" role="dialog" aria-modal="true"><button type="button" aria-label="Close zoom" class="${ssrRenderClass(unref(cn)("absolute right-4 top-4 rounded-md border border-white/30 bg-black/50 px-2 py-1 text-xs font-medium text-white hover:bg-black/70", __props.ui.zoomClose))}" style="${ssrRenderStyle(__props.ui.zoomCloseStyle)}"> Close </button><img${ssrRenderAttr("src", currentSrc.value)}${ssrRenderAttr("alt", __props.alt)} class="${ssrRenderClass(unref(cn)("max-h-[92vh] max-w-[92vw] object-contain", __props.ui.zoomImage))}" style="${ssrRenderStyle(__props.ui.zoomImageStyle)}"></div>`);
        } else {
          _push2(`<!---->`);
        }
      }, "body", false, _parent);
      _push(`</div>`);
    };
  }
});
const _sfc_setup$b = _sfc_main$b.setup;
_sfc_main$b.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Image.vue");
  return _sfc_setup$b ? _sfc_setup$b(props, ctx) : void 0;
};
const _sfc_main$a = /* @__PURE__ */ defineComponent({
  __name: "ImageGallery",
  __ssrInlineRender: true,
  props: {
    images: {},
    columns: { default: "auto" },
    gap: { default: "md" },
    maxImageHeight: { default: "400px" },
    zoomOnClick: { type: Boolean, default: false },
    ui: { default: () => ({}) }
  },
  emits: ["imageClick"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const containerRef = ref(null);
    const containerWidth = ref(0);
    const zoomedImage = ref(null);
    const closeZoom = () => {
      zoomedImage.value = null;
      document.body.style.overflow = "";
    };
    const handleKeyDown = (e2) => {
      if (e2.key === "Escape" && zoomedImage.value) {
        closeZoom();
      }
    };
    const columnCount = computed(() => {
      if (typeof props.columns === "number") {
        return props.columns;
      }
      if (containerWidth.value < 480) return 1;
      if (containerWidth.value < 768) return 2;
      if (containerWidth.value < 1024) return 3;
      if (containerWidth.value < 1440) return 4;
      return 5;
    });
    const gapClasses = computed(() => {
      const gaps = {
        none: "gap-0",
        sm: "gap-2",
        md: "gap-4",
        lg: "gap-6"
      };
      return gaps[props.gap] || gaps.md;
    });
    const gapSize = computed(() => {
      const sizes = {
        none: 0,
        sm: 8,
        md: 16,
        lg: 24
      };
      return sizes[props.gap] || sizes.md;
    });
    const distributeImages = computed(() => {
      const cols = columnCount.value;
      const columns = Array.from({ length: cols }, () => []);
      const heights = Array(cols).fill(0);
      props.images.forEach((image, originalIndex) => {
        const shortestCol = heights.indexOf(Math.min(...heights));
        columns[shortestCol].push({ ...image, originalIndex });
        const aspectRatio = image.width && image.height ? image.height / image.width : 1;
        const colWidth = containerWidth.value > 0 ? (containerWidth.value - gapSize.value * (cols - 1)) / cols : 300;
        const estimatedHeight = colWidth * aspectRatio;
        heights[shortestCol] += estimatedHeight + gapSize.value;
      });
      return columns;
    });
    let resizeObserver = null;
    onMounted(() => {
      if (containerRef.value) {
        containerWidth.value = containerRef.value.offsetWidth;
        resizeObserver = new ResizeObserver((entries) => {
          if (entries[0]) {
            containerWidth.value = entries[0].contentRect.width;
          }
        });
        resizeObserver.observe(containerRef.value);
      }
      document.addEventListener("keydown", handleKeyDown);
    });
    onUnmounted(() => {
      resizeObserver == null ? void 0 : resizeObserver.disconnect();
      document.removeEventListener("keydown", handleKeyDown);
      document.body.style.overflow = "";
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[--><div class="${ssrRenderClass(unref(cn)(
        "w-full flex",
        gapClasses.value,
        __props.ui.root
      ))}" style="${ssrRenderStyle(__props.ui.rootStyle)}"><!--[-->`);
      ssrRenderList(distributeImages.value, (column, colIndex) => {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "flex flex-col flex-1",
          gapClasses.value,
          __props.ui.column
        ))}" style="${ssrRenderStyle(__props.ui.columnStyle)}"><!--[-->`);
        ssrRenderList(column, (image, imgIndex) => {
          _push(`<div class="${ssrRenderClass(unref(cn)(
            "relative overflow-hidden group cursor-pointer",
            __props.ui.item
          ))}" style="${ssrRenderStyle([
            { maxHeight: __props.maxImageHeight },
            __props.ui.itemStyle
          ])}">`);
          _push(ssrRenderComponent(_sfc_main$b, {
            src: image.src,
            lazy: image.lazy,
            "lazy-fallback": image.lazyFallback,
            fallback: image.fallback,
            alt: image.alt || `Gallery image ${image.originalIndex + 1}`,
            "object-fit": "cover",
            rounded: __props.gap === "none" ? "none" : "lg",
            "aspect-ratio": image.width && image.height ? `${image.width}/${image.height}` : void 0,
            ui: __props.ui.image,
            class: "w-full h-auto transition-transform duration-300 group-hover:scale-105"
          }, null, _parent));
          _push(`<div class="${ssrRenderClass(unref(cn)(
            "absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none",
            __props.gap === "none" ? "" : "rounded-lg"
          ))}"></div></div>`);
        });
        _push(`<!--]--></div>`);
      });
      _push(`<!--]--></div>`);
      ssrRenderTeleport(_push, (_push2) => {
        if (zoomedImage.value) {
          _push2(`<div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 backdrop-blur-sm"><button class="absolute top-4 right-4 p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors z-10">`);
          _push2(ssrRenderComponent(unref(X), { class: "w-6 h-6" }, null, _parent));
          _push2(`</button><div class="max-w-[90vw] max-h-[90vh] relative"><img${ssrRenderAttr("src", zoomedImage.value.src)}${ssrRenderAttr("alt", zoomedImage.value.alt || "Zoomed image")} class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl">`);
          if (zoomedImage.value.alt) {
            _push2(`<p class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 to-transparent text-white text-center rounded-b-lg">${ssrInterpolate(zoomedImage.value.alt)}</p>`);
          } else {
            _push2(`<!---->`);
          }
          _push2(`</div></div>`);
        } else {
          _push2(`<!---->`);
        }
      }, "body", false, _parent);
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup$a = _sfc_main$a.setup;
_sfc_main$a.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/ImageGallery.vue");
  return _sfc_setup$a ? _sfc_setup$a(props, ctx) : void 0;
};
const _sfc_main$9 = /* @__PURE__ */ defineComponent({
  __name: "UsageBar",
  __ssrInlineRender: true,
  props: {
    current: {},
    max: {},
    label: { default: "" },
    color: { default: void 0 },
    icon: { default: void 0 },
    unit: { default: void 0 },
    density: { default: "normal" },
    size: { default: "md" },
    animated: { type: Boolean, default: true },
    showTooltip: { type: Boolean, default: true },
    colorIndex: { default: 0 },
    ui: { default: () => ({}) }
  },
  setup(__props) {
    const props = __props;
    const mounted = ref(false);
    onMounted(() => {
      nextTick(() => {
        mounted.value = true;
      });
    });
    const sizeConfig = {
      sm: { barHeight: "h-1", text: "text-[10px]", gap: "gap-1", iconSize: "h-3 w-3" },
      md: { barHeight: "h-1.5", text: "text-xs", gap: "gap-1.5", iconSize: "h-3.5 w-3.5" },
      lg: { barHeight: "h-2.5", text: "text-sm", gap: "gap-2", iconSize: "h-4 w-4" }
    };
    const predefinedColors = {
      primary: "rgb(var(--ui-primary))",
      success: "rgb(var(--ui-success))",
      warning: "rgb(var(--ui-warning))",
      error: "rgb(var(--ui-destructive))",
      info: "rgb(var(--ui-info))"
    };
    const defaultPalette = ["primary", "info", "success", "warning"];
    const percent = computed(() => {
      if (props.max <= 0) return 0;
      return Math.min(100, Math.round(props.current / props.max * 100));
    });
    const numberFormatter = new Intl.NumberFormat();
    const formattedValue = computed(() => {
      const c2 = numberFormatter.format(props.current);
      const m2 = numberFormatter.format(props.max);
      const suffix = props.unit ? ` ${props.unit}` : "";
      return `${c2} / ${m2}${suffix}`;
    });
    const tooltipContent = computed(() => {
      const prefix = props.label ? `${props.label}: ` : "";
      return `${prefix}${formattedValue.value} (${percent.value}%)`;
    });
    const resolvedColor = computed(() => {
      if (!props.color && percent.value >= 90) {
        return predefinedColors["error"];
      }
      const colorKey = props.color ?? defaultPalette[props.colorIndex % defaultPalette.length];
      if (colorKey in predefinedColors) {
        return predefinedColors[colorKey];
      }
      return colorKey;
    });
    const rootClass = computed(() => cn(
      "flex flex-col",
      sizeConfig[props.size].gap,
      props.ui.root
    ));
    const barTrackClass = computed(() => cn(
      "w-full rounded-full bg-ui-muted/30 overflow-hidden",
      sizeConfig[props.size].barHeight,
      props.ui.barTrack
    ));
    const barFillClass = computed(() => cn(
      "rounded-full h-full",
      props.animated ? "transition-all duration-700 ease-out" : "",
      props.ui.barFill
    ));
    const barFillStyle = computed(() => {
      const width = mounted.value ? `${percent.value}%` : "0%";
      const base = {
        width,
        backgroundColor: resolvedColor.value
      };
      const userStyle = props.ui.barFillStyle;
      if (userStyle && typeof userStyle === "object") {
        return { ...base, ...userStyle };
      }
      return base;
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: rootClass.value,
        style: __props.ui.rootStyle
      }, _attrs))}>`);
      if (__props.density !== "compact" && __props.label) {
        _push(`<div class="${ssrRenderClass(unref(cn)("flex items-center justify-between", sizeConfig[__props.size].text))}"><div class="flex items-center gap-1.5">`);
        if (__props.icon) {
          _push(ssrRenderComponent(_sfc_main$13, {
            icon: __props.icon,
            ui: { root: unref(cn)(sizeConfig[__props.size].iconSize, "opacity-60", __props.ui.icon), rootStyle: __props.ui.iconStyle },
            "aria-hidden": "true"
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`<span class="${ssrRenderClass(unref(cn)("text-ui-muted-foreground font-medium", __props.ui.label))}" style="${ssrRenderStyle(__props.ui.labelStyle)}">${ssrInterpolate(__props.label)}</span></div>`);
        if (__props.density === "detailed") {
          _push(`<div class="flex items-center gap-1.5 text-ui-muted-foreground"><span class="${ssrRenderClass(unref(cn)("tabular-nums", __props.ui.value))}" style="${ssrRenderStyle(__props.ui.valueStyle)}">${ssrInterpolate(formattedValue.value)}</span><span class="${ssrRenderClass(unref(cn)("text-ui-muted-foreground/60 tabular-nums", __props.ui.percentage))}" style="${ssrRenderStyle(__props.ui.percentageStyle)}">${ssrInterpolate(percent.value)}% </span></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      if (__props.showTooltip) {
        _push(ssrRenderComponent(_sfc_main$12, {
          text: tooltipContent.value,
          position: "top",
          ui: { root: "w-full" }
        }, {
          default: withCtx((_2, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<div class="${ssrRenderClass(barTrackClass.value)}" style="${ssrRenderStyle(__props.ui.barTrackStyle)}" role="progressbar"${ssrRenderAttr("aria-valuenow", __props.current)}${ssrRenderAttr("aria-valuemin", 0)}${ssrRenderAttr("aria-valuemax", __props.max)}${ssrRenderAttr("aria-label", `${__props.label}: ${percent.value}%`)}${_scopeId}><div class="${ssrRenderClass(barFillClass.value)}" style="${ssrRenderStyle(barFillStyle.value)}"${_scopeId}></div></div>`);
            } else {
              return [
                createVNode("div", {
                  class: barTrackClass.value,
                  style: __props.ui.barTrackStyle,
                  role: "progressbar",
                  "aria-valuenow": __props.current,
                  "aria-valuemin": 0,
                  "aria-valuemax": __props.max,
                  "aria-label": `${__props.label}: ${percent.value}%`
                }, [
                  createVNode("div", {
                    class: barFillClass.value,
                    style: barFillStyle.value
                  }, null, 6)
                ], 14, ["aria-valuenow", "aria-valuemax", "aria-label"])
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<div class="${ssrRenderClass(barTrackClass.value)}" style="${ssrRenderStyle(__props.ui.barTrackStyle)}" role="progressbar"${ssrRenderAttr("aria-valuenow", __props.current)}${ssrRenderAttr("aria-valuemin", 0)}${ssrRenderAttr("aria-valuemax", __props.max)}${ssrRenderAttr("aria-label", `${__props.label}: ${percent.value}%`)}><div class="${ssrRenderClass(barFillClass.value)}" style="${ssrRenderStyle(barFillStyle.value)}"></div></div>`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$9 = _sfc_main$9.setup;
_sfc_main$9.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UsageBar.vue");
  return _sfc_setup$9 ? _sfc_setup$9(props, ctx) : void 0;
};
const _sfc_main$8 = /* @__PURE__ */ defineComponent({
  __name: "UsageBars",
  __ssrInlineRender: true,
  props: {
    modelValue: { default: void 0 },
    items: { default: void 0 },
    density: { default: "normal" },
    size: { default: "md" },
    animated: { type: Boolean, default: true },
    showTooltip: { type: Boolean, default: true },
    ui: { default: () => ({}) }
  },
  emits: ["update:modelValue"],
  setup(__props) {
    const props = __props;
    const resolvedItems = computed(() => props.modelValue ?? props.items ?? []);
    const densitySpacing = {
      compact: "space-y-1",
      normal: "space-y-2.5",
      detailed: "space-y-3"
    };
    const rootClass = computed(() => cn(
      densitySpacing[props.density],
      props.ui.root
    ));
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: rootClass.value,
        style: __props.ui.rootStyle,
        role: "group",
        "aria-label": "Usage bars"
      }, _attrs))}><!--[-->`);
      ssrRenderList(resolvedItems.value, (item, index) => {
        _push(ssrRenderComponent(_sfc_main$9, {
          key: index,
          label: item.label,
          current: item.current,
          max: item.max,
          color: item.color,
          icon: item.icon,
          unit: item.unit,
          density: __props.density,
          size: __props.size,
          animated: __props.animated,
          "show-tooltip": __props.showTooltip,
          "color-index": index,
          ui: __props.ui.bar
        }, null, _parent));
      });
      _push(`<!--]--></div>`);
    };
  }
});
const _sfc_setup$8 = _sfc_main$8.setup;
_sfc_main$8.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/UsageBars.vue");
  return _sfc_setup$8 ? _sfc_setup$8(props, ctx) : void 0;
};
function useDropdownState(options = {}) {
  const isOpen = ref(false);
  const open = () => {
    var _a;
    if (!isOpen.value) {
      isOpen.value = true;
      (_a = options.onOpen) == null ? void 0 : _a.call(options);
    }
  };
  const close = () => {
    var _a;
    if (isOpen.value) {
      isOpen.value = false;
      (_a = options.onClose) == null ? void 0 : _a.call(options);
    }
  };
  const toggle = () => {
    if (isOpen.value) {
      close();
    } else {
      open();
    }
  };
  const handleKeydown = (e2) => {
    if (e2.key === "Escape" && isOpen.value) {
      e2.preventDefault();
      close();
    }
  };
  const handleClickOutside = (e2) => {
    var _a, _b;
    if (!((_a = options.containerRef) == null ? void 0 : _a.value)) return;
    const target = e2.target;
    if (options.containerRef.value.contains(target)) return;
    if ((_b = options.extraRefs) == null ? void 0 : _b.some((ref2) => {
      var _a2;
      return (_a2 = ref2.value) == null ? void 0 : _a2.contains(target);
    })) return;
    close();
  };
  onMounted(() => {
    document.addEventListener("keydown", handleKeydown);
    document.addEventListener("mousedown", handleClickOutside);
  });
  onUnmounted(() => {
    document.removeEventListener("keydown", handleKeydown);
    document.removeEventListener("mousedown", handleClickOutside);
  });
  return {
    isOpen,
    open,
    close,
    toggle
  };
}
function useOptionFilter(options) {
  const searchQuery = ref("");
  const labelKey = options.labelKey || "label";
  const filteredOptions = computed(() => {
    const query = searchQuery.value.toLowerCase().trim();
    if (!query) {
      return options.options.value;
    }
    if (options.filterFn) {
      return options.options.value.filter((opt) => options.filterFn(opt, query));
    }
    return options.options.value.filter((opt) => {
      const label = String(opt[labelKey] || "").toLowerCase();
      return label.includes(query);
    });
  });
  const clearSearch = () => {
    searchQuery.value = "";
  };
  const highlightMatch = (text) => {
    const query = searchQuery.value.toLowerCase().trim();
    if (!query) return null;
    const lowerText = text.toLowerCase();
    const index = lowerText.indexOf(query);
    if (index === -1) return null;
    return {
      before: text.slice(0, index),
      match: text.slice(index, index + query.length),
      after: text.slice(index + query.length)
    };
  };
  return {
    searchQuery,
    filteredOptions,
    clearSearch,
    highlightMatch
  };
}
const _sfc_main$7 = /* @__PURE__ */ defineComponent({
  __name: "Select",
  __ssrInlineRender: true,
  props: {
    modelValue: { default: null },
    options: { default: () => [] },
    placeholder: { default: "Select an option..." },
    searchable: { type: Boolean, default: false },
    searchPlaceholder: { default: "Search..." },
    clearable: { type: Boolean, default: true },
    disabled: { type: Boolean, default: false },
    loading: { type: Boolean, default: false },
    compact: { type: Boolean, default: false },
    serverSideSearch: { type: Boolean, default: false },
    loadingMore: { type: Boolean, default: false },
    ui: {}
  },
  emits: ["update:modelValue", "search", "select", "open", "close", "load-more"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const containerRef = ref(null);
    const triggerRef = ref(null);
    const dropdownRef = ref(null);
    const searchInputRef = ref(null);
    ref(null);
    const highlightedIndex = ref(-1);
    const teleportTarget = ref("body");
    const dropdownStyle = ref({});
    onMounted(() => {
      var _a;
      const dialog = (_a = containerRef.value) == null ? void 0 : _a.closest("dialog");
      if (dialog) {
        teleportTarget.value = dialog;
      }
    });
    function updateDropdownPosition() {
      if (!triggerRef.value) return;
      const rect = triggerRef.value.getBoundingClientRect();
      const minWidth = 360;
      const width = Math.max(rect.width, minWidth);
      let left = rect.left;
      if (left + width > window.innerWidth - 8) {
        left = Math.max(8, window.innerWidth - width - 8);
      }
      dropdownStyle.value = {
        position: "fixed",
        top: `${rect.bottom + 4}px`,
        left: `${left}px`,
        width: `${width}px`,
        zIndex: "9999"
      };
    }
    window.addEventListener("resize", updateDropdownPosition);
    window.addEventListener("scroll", updateDropdownPosition, true);
    onBeforeUnmount(() => {
      window.removeEventListener("resize", updateDropdownPosition);
      window.removeEventListener("scroll", updateDropdownPosition, true);
    });
    const { isOpen } = useDropdownState({
      containerRef,
      extraRefs: [dropdownRef],
      onClose: () => {
        emit("close");
        highlightedIndex.value = -1;
      },
      onOpen: () => {
        emit("open");
        updateDropdownPosition();
        nextTick(() => {
          var _a;
          updateDropdownPosition();
          if (props.searchable) {
            (_a = searchInputRef.value) == null ? void 0 : _a.focus();
          }
          const selectedIdx = displayOptions.value.findIndex(
            (opt) => opt.value === props.modelValue
          );
          if (selectedIdx > -1) {
            highlightedIndex.value = selectedIdx;
          }
        });
      }
    });
    const optionsRef = computed(() => props.options);
    const { searchQuery, filteredOptions } = useOptionFilter({
      options: optionsRef
    });
    const displayOptions = computed(
      () => props.serverSideSearch ? props.options : filteredOptions.value
    );
    watch(searchQuery, (query) => {
      emit("search", query);
      highlightedIndex.value = 0;
    });
    const selectedOption = computed(() => {
      if (props.modelValue == null) return null;
      return props.options.find((opt) => opt.value === props.modelValue) || null;
    });
    const displayLabel = computed(() => {
      var _a;
      return ((_a = selectedOption.value) == null ? void 0 : _a.label) || "";
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k, _l;
      _push(`<div${ssrRenderAttrs(mergeProps({
        ref_key: "containerRef",
        ref: containerRef,
        class: unref(cn)(
          "relative w-full",
          __props.disabled && "opacity-60 pointer-events-none",
          (_a = props.ui) == null ? void 0 : _a.root
        ),
        style: (_b = props.ui) == null ? void 0 : _b.rootStyle
      }, _attrs))}><button type="button" class="${ssrRenderClass(unref(cn)(
        "w-full flex items-center justify-between gap-2",
        __props.compact ? "px-3 py-1.5 text-xs" : "px-4 py-2.5 text-sm",
        "bg-ui-input border border-ui-border rounded-lg",
        "text-left text-ui-foreground",
        "hover:border-ui-ring/50 focus:outline-none focus:ring-2 focus:ring-ui-ring/40 focus:border-ui-ring",
        "transition-all duration-200",
        unref(isOpen) && "ring-2 ring-ui-ring/40 border-ui-ring",
        (_c = props.ui) == null ? void 0 : _c.trigger
      ))}" style="${ssrRenderStyle((_d = props.ui) == null ? void 0 : _d.triggerStyle)}"${ssrIncludeBooleanAttr(__props.disabled) ? " disabled" : ""}>`);
      if (selectedOption.value) {
        _push(`<span class="${ssrRenderClass(unref(cn)("truncate font-medium", (_e = props.ui) == null ? void 0 : _e.value))}" style="${ssrRenderStyle((_f = props.ui) == null ? void 0 : _f.valueStyle)}">${ssrInterpolate(displayLabel.value)}</span>`);
      } else {
        _push(`<span class="${ssrRenderClass(unref(cn)("truncate text-gray-500", (_g = props.ui) == null ? void 0 : _g.placeholder))}" style="${ssrRenderStyle((_h = props.ui) == null ? void 0 : _h.placeholderStyle)}">${ssrInterpolate(__props.placeholder)}</span>`);
      }
      _push(`<div class="${ssrRenderClass(unref(cn)("flex items-center gap-1 shrink-0", __props.compact && "gap-0.5"))}">`);
      if (__props.clearable && selectedOption.value) {
        _push(`<button type="button" class="${ssrRenderClass(unref(cn)(
          "rounded hover:bg-ui-card text-gray-400 hover:text-ui-foreground transition-colors",
          __props.compact ? "p-0.5" : "p-1",
          (_i = props.ui) == null ? void 0 : _i.clearButton
        ))}" style="${ssrRenderStyle((_j = props.ui) == null ? void 0 : _j.clearButtonStyle)}" title="Clear">`);
        _push(ssrRenderComponent(_sfc_main$13, {
          icon: __props.compact ? "x" : "x",
          class: unref(cn)(__props.compact ? "w-3 h-3" : "w-4 h-4")
        }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(ssrRenderComponent(_sfc_main$13, {
        icon: "chevron-down",
        class: unref(cn)(
          "text-gray-400 transition-transform duration-200",
          __props.compact ? "w-4 h-4" : "w-5 h-5",
          unref(isOpen) && "rotate-180",
          (_k = props.ui) == null ? void 0 : _k.chevron
        ),
        ui: { rootStyle: (_l = props.ui) == null ? void 0 : _l.chevronStyle }
      }, null, _parent));
      _push(`</div></button>`);
      ssrRenderTeleport(_push, (_push2) => {
        var _a2, _b2, _c2, _d2, _e2, _f2, _g2, _h2, _i2, _j2, _k2, _l2, _m, _n;
        _push2(`<div class="${ssrRenderClass(unref(cn)(
          "bg-ui-card border border-ui-border rounded-lg shadow-xl",
          "overflow-hidden",
          (_a2 = props.ui) == null ? void 0 : _a2.menu
        ))}" style="${ssrRenderStyle([
          [dropdownStyle.value, (_b2 = props.ui) == null ? void 0 : _b2.menuStyle],
          unref(isOpen) ? null : { display: "none" }
        ])}">`);
        if (__props.searchable) {
          _push2(`<div class="${ssrRenderClass(unref(cn)(
            "flex items-center gap-2 border-b border-ui-border",
            __props.compact ? "px-2 py-1.5" : "px-3 py-2",
            (_c2 = props.ui) == null ? void 0 : _c2.searchWrapper
          ))}" style="${ssrRenderStyle((_d2 = props.ui) == null ? void 0 : _d2.searchWrapperStyle)}">`);
          _push2(ssrRenderComponent(_sfc_main$13, {
            icon: "search",
            ui: {
              root: unref(cn)("text-gray-500 shrink-0", __props.compact ? "w-3.5 h-3.5" : "w-4 h-4", (_e2 = props.ui) == null ? void 0 : _e2.searchIcon),
              rootStyle: (_f2 = props.ui) == null ? void 0 : _f2.searchIconStyle
            }
          }, null, _parent));
          _push2(`<input${ssrRenderAttr("value", unref(searchQuery))} type="text"${ssrRenderAttr("placeholder", __props.searchPlaceholder)} class="${ssrRenderClass(unref(cn)(
            "flex-1 bg-transparent border-none outline-none text-ui-foreground placeholder-gray-500",
            __props.compact ? "text-xs" : "text-sm",
            (_g2 = props.ui) == null ? void 0 : _g2.searchInput
          ))}" style="${ssrRenderStyle((_h2 = props.ui) == null ? void 0 : _h2.searchInputStyle)}"></div>`);
        } else {
          _push2(`<!---->`);
        }
        _push2(`<div class="${ssrRenderClass(unref(cn)("max-h-60 overflow-y-auto", (_i2 = props.ui) == null ? void 0 : _i2.optionsList))}" style="${ssrRenderStyle((_j2 = props.ui) == null ? void 0 : _j2.optionsListStyle)}">`);
        if (__props.loading) {
          _push2(`<div class="${ssrRenderClass(unref(cn)(
            "flex items-center justify-center gap-2 py-8 text-gray-400 text-sm",
            (_k2 = props.ui) == null ? void 0 : _k2.loadingState
          ))}" style="${ssrRenderStyle((_l2 = props.ui) == null ? void 0 : _l2.loadingStateStyle)}"><div class="w-5 h-5 border-2 border-gray-600 border-t-ui-primary rounded-full animate-spin"></div><span>Loading...</span></div>`);
        } else if (displayOptions.value.length === 0) {
          _push2(`<div class="${ssrRenderClass(unref(cn)(
            "py-8 text-center text-gray-400 text-sm",
            (_m = props.ui) == null ? void 0 : _m.emptyState
          ))}" style="${ssrRenderStyle((_n = props.ui) == null ? void 0 : _n.emptyStateStyle)}"> No options found </div>`);
        } else {
          _push2(`<!--[-->`);
          ssrRenderList(displayOptions.value, (option, index) => {
            var _a3, _b3, _c3, _d3, _e3, _f3, _g3, _h3, _i3, _j3, _k3, _l3, _m2, _n2, _o;
            _push2(`<button type="button"${ssrRenderAttr("data-highlighted", index === highlightedIndex.value)} class="${ssrRenderClass(unref(cn)(
              "w-full flex items-center gap-3 text-left transition-colors",
              __props.compact ? "px-3 py-1.5 text-xs" : "px-4 py-2.5 text-sm",
              "hover:bg-ui-primary/20",
              index === highlightedIndex.value && "bg-ui-primary/15",
              option.value === __props.modelValue && "bg-ui-primary/25 text-ui-primary",
              option.disabled && "opacity-50 cursor-not-allowed",
              (_a3 = props.ui) == null ? void 0 : _a3.option,
              option.value === __props.modelValue && ((_b3 = props.ui) == null ? void 0 : _b3.optionSelected),
              index === highlightedIndex.value && ((_c3 = props.ui) == null ? void 0 : _c3.optionHighlighted),
              option.disabled && ((_d3 = props.ui) == null ? void 0 : _d3.optionDisabled)
            ))}" style="${ssrRenderStyle((_e3 = props.ui) == null ? void 0 : _e3.optionStyle)}"${ssrIncludeBooleanAttr(option.disabled) ? " disabled" : ""}>`);
            if (option.image) {
              _push2(`<img${ssrRenderAttr("src", option.image)} class="${ssrRenderClass(unref(cn)("rounded-full object-cover", __props.compact ? "w-5 h-5" : "w-6 h-6", (_f3 = props.ui) == null ? void 0 : _f3.optionIcon))}" style="${ssrRenderStyle((_g3 = props.ui) == null ? void 0 : _g3.optionIconStyle)}" alt="">`);
            } else if (option.icon) {
              _push2(ssrRenderComponent(_sfc_main$13, {
                icon: option.icon,
                ui: {
                  root: unref(cn)("text-gray-400", __props.compact ? "w-4 h-4" : "w-5 h-5", (_h3 = props.ui) == null ? void 0 : _h3.optionIcon),
                  rootStyle: (_i3 = props.ui) == null ? void 0 : _i3.optionIconStyle
                }
              }, null, _parent));
            } else {
              _push2(`<!---->`);
            }
            _push2(`<div class="flex-1 min-w-0"><div class="${ssrRenderClass(unref(cn)("truncate text-ui-foreground", __props.compact && "text-xs", (_j3 = props.ui) == null ? void 0 : _j3.optionLabel))}" style="${ssrRenderStyle((_k3 = props.ui) == null ? void 0 : _k3.optionLabelStyle)}">${ssrInterpolate(option.label)}</div>`);
            if (option.subtitle) {
              _push2(`<div class="${ssrRenderClass(unref(cn)("truncate text-gray-500", __props.compact ? "text-[0.65rem]" : "text-xs", (_l3 = props.ui) == null ? void 0 : _l3.optionSubtitle))}" style="${ssrRenderStyle((_m2 = props.ui) == null ? void 0 : _m2.optionSubtitleStyle)}">${ssrInterpolate(option.subtitle)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div>`);
            if (option.value === __props.modelValue) {
              _push2(ssrRenderComponent(_sfc_main$13, {
                icon: "check",
                ui: {
                  root: unref(cn)("shrink-0 text-ui-primary", __props.compact ? "w-4 h-4" : "w-5 h-5", (_n2 = props.ui) == null ? void 0 : _n2.checkIcon),
                  rootStyle: (_o = props.ui) == null ? void 0 : _o.checkIconStyle
                }
              }, null, _parent));
            } else {
              _push2(`<!---->`);
            }
            _push2(`</button>`);
          });
          _push2(`<!--]-->`);
        }
        if (__props.loadingMore) {
          _push2(`<div class="flex items-center justify-center py-3"><div class="w-4 h-4 border-2 border-gray-600 border-t-ui-primary rounded-full animate-spin"></div></div>`);
        } else {
          _push2(`<!---->`);
        }
        _push2(`</div></div>`);
      }, teleportTarget.value, false, _parent);
      _push(`</div>`);
    };
  }
});
const _sfc_setup$7 = _sfc_main$7.setup;
_sfc_main$7.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Select/Select.vue");
  return _sfc_setup$7 ? _sfc_setup$7(props, ctx) : void 0;
};
const _sfc_main$6 = /* @__PURE__ */ defineComponent({
  __name: "SelectChip",
  __ssrInlineRender: true,
  props: {
    label: {},
    removable: { type: Boolean, default: true },
    disabled: { type: Boolean, default: false },
    class: {},
    style: {}
  },
  emits: ["remove"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<span${ssrRenderAttrs(mergeProps({
        class: unref(cn)(
          "inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium",
          "bg-ui-primary/30 text-ui-primary border border-ui-primary/30",
          __props.disabled && "opacity-50",
          props.class
        ),
        style: props.style
      }, _attrs))}><span class="truncate max-w-[120px]">${ssrInterpolate(__props.label)}</span>`);
      if (__props.removable && !__props.disabled) {
        _push(`<button type="button" class="p-0.5 rounded hover:bg-ui-primary/30 transition-colors">`);
        _push(ssrRenderComponent(unref(X), { class: "w-3 h-3" }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</span>`);
    };
  }
});
const _sfc_setup$6 = _sfc_main$6.setup;
_sfc_main$6.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Select/SelectChip.vue");
  return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
const _sfc_main$5 = /* @__PURE__ */ defineComponent({
  __name: "SelectMultiple",
  __ssrInlineRender: true,
  props: {
    modelValue: { default: () => [] },
    options: { default: () => [] },
    placeholder: { default: "Select options..." },
    searchable: { type: Boolean, default: false },
    searchPlaceholder: { default: "Search..." },
    clearable: { type: Boolean, default: true },
    disabled: { type: Boolean, default: false },
    loading: { type: Boolean, default: false },
    max: { default: void 0 },
    closeOnSelect: { type: Boolean, default: false },
    showChips: { type: Boolean, default: true },
    ui: {}
  },
  emits: ["update:modelValue", "search", "select", "deselect", "open", "close"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const containerRef = ref(null);
    const triggerRef = ref(null);
    const dropdownRef = ref(null);
    const searchInputRef = ref(null);
    ref(null);
    const highlightedIndex = ref(-1);
    const teleportTarget = ref("body");
    const dropdownStyle = ref({});
    onMounted(() => {
      var _a;
      const dialog = (_a = containerRef.value) == null ? void 0 : _a.closest("dialog");
      if (dialog) teleportTarget.value = dialog;
    });
    function updateDropdownPosition() {
      if (!triggerRef.value) return;
      const rect = triggerRef.value.getBoundingClientRect();
      const minWidth = 360;
      const width = Math.max(rect.width, minWidth);
      let left = rect.left;
      if (left + width > window.innerWidth - 8) {
        left = Math.max(8, window.innerWidth - width - 8);
      }
      dropdownStyle.value = {
        position: "fixed",
        top: `${rect.bottom + 4}px`,
        left: `${left}px`,
        width: `${width}px`,
        zIndex: "9999"
      };
    }
    window.addEventListener("resize", updateDropdownPosition);
    window.addEventListener("scroll", updateDropdownPosition, true);
    onBeforeUnmount(() => {
      window.removeEventListener("resize", updateDropdownPosition);
      window.removeEventListener("scroll", updateDropdownPosition, true);
    });
    const { isOpen } = useDropdownState({
      containerRef,
      extraRefs: [dropdownRef],
      onClose: () => {
        emit("close");
        highlightedIndex.value = -1;
      },
      onOpen: () => {
        emit("open");
        updateDropdownPosition();
        nextTick(() => {
          var _a;
          updateDropdownPosition();
          if (props.searchable) {
            (_a = searchInputRef.value) == null ? void 0 : _a.focus();
          }
          highlightedIndex.value = 0;
        });
      }
    });
    const optionsRef = computed(() => props.options);
    const { searchQuery, filteredOptions } = useOptionFilter({
      options: optionsRef
    });
    watch(searchQuery, (query) => {
      emit("search", query);
      highlightedIndex.value = 0;
    });
    const selectedOptions = computed(() => {
      return props.options.filter((opt) => props.modelValue.includes(opt.value));
    });
    const isMaxReached = computed(() => {
      return props.max !== void 0 && props.modelValue.length >= props.max;
    });
    const displayText = computed(() => {
      if (props.showChips || selectedOptions.value.length === 0) return "";
      if (selectedOptions.value.length === 1) return selectedOptions.value[0].label;
      return `${selectedOptions.value.length} selected`;
    });
    const isSelected = (option) => {
      return props.modelValue.includes(option.value);
    };
    const removeOption = (option) => {
      const currentValues = props.modelValue.filter((v2) => v2 !== option.value);
      emit("update:modelValue", currentValues);
      emit("deselect", option);
    };
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k, _l;
      _push(`<div${ssrRenderAttrs(mergeProps({
        ref_key: "containerRef",
        ref: containerRef,
        class: unref(cn)(
          "relative w-full",
          __props.disabled && "opacity-60 pointer-events-none",
          (_a = props.ui) == null ? void 0 : _a.root
        ),
        style: (_b = props.ui) == null ? void 0 : _b.rootStyle
      }, _attrs))}><button type="button" class="${ssrRenderClass(unref(cn)(
        "w-full flex items-center justify-between gap-2 px-3 py-2 min-h-[42px]",
        "bg-ui-input border border-ui-border rounded-lg",
        "text-sm text-left text-ui-foreground",
        "hover:border-ui-ring/50 focus:outline-none focus:ring-2 focus:ring-ui-ring/40 focus:border-ui-ring",
        "transition-all duration-200",
        unref(isOpen) && "ring-2 ring-ui-ring/40 border-ui-ring",
        (_c = props.ui) == null ? void 0 : _c.trigger
      ))}" style="${ssrRenderStyle((_d = props.ui) == null ? void 0 : _d.triggerStyle)}"${ssrIncludeBooleanAttr(__props.disabled) ? " disabled" : ""}><div class="flex-1 min-w-0 flex flex-wrap gap-1">`);
      if (__props.showChips && selectedOptions.value.length > 0) {
        _push(`<!--[-->`);
        ssrRenderList(selectedOptions.value, (option) => {
          var _a2, _b2;
          _push(ssrRenderComponent(_sfc_main$6, {
            key: option.value,
            label: option.label,
            class: (_a2 = props.ui) == null ? void 0 : _a2.chip,
            style: (_b2 = props.ui) == null ? void 0 : _b2.chipStyle,
            onRemove: ($event) => removeOption(option)
          }, null, _parent));
        });
        _push(`<!--]-->`);
      } else if (displayText.value) {
        _push(`<span class="truncate font-medium">${ssrInterpolate(displayText.value)}</span>`);
      } else {
        _push(`<span class="${ssrRenderClass(unref(cn)("truncate text-gray-500", (_e = props.ui) == null ? void 0 : _e.placeholder))}" style="${ssrRenderStyle((_f = props.ui) == null ? void 0 : _f.placeholderStyle)}">${ssrInterpolate(__props.placeholder)}</span>`);
      }
      _push(`</div><div class="flex items-center gap-1 shrink-0">`);
      if (selectedOptions.value.length > 0 && !__props.showChips) {
        _push(`<span class="${ssrRenderClass(unref(cn)(
          "px-1.5 py-0.5 text-xs font-medium rounded-full bg-ui-primary/30 text-ui-primary",
          (_g = props.ui) == null ? void 0 : _g.counter
        ))}" style="${ssrRenderStyle((_h = props.ui) == null ? void 0 : _h.counterStyle)}">${ssrInterpolate(selectedOptions.value.length)}</span>`);
      } else {
        _push(`<!---->`);
      }
      if (__props.clearable && selectedOptions.value.length > 0) {
        _push(`<button type="button" class="${ssrRenderClass(unref(cn)(
          "p-1 rounded hover:bg-ui-card text-gray-400 hover:text-ui-foreground transition-colors",
          (_i = props.ui) == null ? void 0 : _i.clearButton
        ))}" style="${ssrRenderStyle((_j = props.ui) == null ? void 0 : _j.clearButtonStyle)}" title="Clear all">`);
        _push(ssrRenderComponent(unref(X), { class: "w-4 h-4" }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(ssrRenderComponent(unref(ChevronDown), {
        class: unref(cn)(
          "w-5 h-5 text-gray-400 transition-transform duration-200",
          unref(isOpen) && "rotate-180",
          (_k = props.ui) == null ? void 0 : _k.chevron
        ),
        style: (_l = props.ui) == null ? void 0 : _l.chevronStyle
      }, null, _parent));
      _push(`</div></button>`);
      ssrRenderTeleport(_push, (_push2) => {
        var _a2, _b2, _c2, _d2, _e2, _f2, _g2, _h2, _i2, _j2, _k2, _l2, _m, _n;
        _push2(`<div class="${ssrRenderClass(unref(cn)(
          "bg-ui-card border border-ui-border rounded-lg shadow-xl",
          "overflow-hidden",
          (_a2 = props.ui) == null ? void 0 : _a2.menu
        ))}" style="${ssrRenderStyle([
          [dropdownStyle.value, (_b2 = props.ui) == null ? void 0 : _b2.menuStyle],
          unref(isOpen) ? null : { display: "none" }
        ])}">`);
        if (__props.searchable) {
          _push2(`<div class="${ssrRenderClass(unref(cn)(
            "flex items-center gap-2 px-3 py-2 border-b border-ui-border",
            (_c2 = props.ui) == null ? void 0 : _c2.searchWrapper
          ))}" style="${ssrRenderStyle((_d2 = props.ui) == null ? void 0 : _d2.searchWrapperStyle)}">`);
          _push2(ssrRenderComponent(unref(Search), {
            class: unref(cn)("w-4 h-4 text-gray-500 shrink-0", (_e2 = props.ui) == null ? void 0 : _e2.searchIcon),
            style: (_f2 = props.ui) == null ? void 0 : _f2.searchIconStyle
          }, null, _parent));
          _push2(`<input${ssrRenderAttr("value", unref(searchQuery))} type="text"${ssrRenderAttr("placeholder", __props.searchPlaceholder)} class="${ssrRenderClass(unref(cn)(
            "flex-1 bg-transparent border-none outline-none text-sm text-ui-foreground placeholder-gray-500",
            (_g2 = props.ui) == null ? void 0 : _g2.searchInput
          ))}" style="${ssrRenderStyle((_h2 = props.ui) == null ? void 0 : _h2.searchInputStyle)}"></div>`);
        } else {
          _push2(`<!---->`);
        }
        if (isMaxReached.value) {
          _push2(`<div class="px-3 py-2 text-xs text-amber-400 bg-amber-500/10 border-b border-gray-700"> Maximum of ${ssrInterpolate(__props.max)} selections reached </div>`);
        } else {
          _push2(`<!---->`);
        }
        _push2(`<div class="${ssrRenderClass(unref(cn)("max-h-60 overflow-y-auto", (_i2 = props.ui) == null ? void 0 : _i2.optionsList))}" style="${ssrRenderStyle((_j2 = props.ui) == null ? void 0 : _j2.optionsListStyle)}">`);
        if (__props.loading) {
          _push2(`<div class="${ssrRenderClass(unref(cn)(
            "flex items-center justify-center gap-2 py-8 text-gray-400 text-sm",
            (_k2 = props.ui) == null ? void 0 : _k2.loadingState
          ))}" style="${ssrRenderStyle((_l2 = props.ui) == null ? void 0 : _l2.loadingStateStyle)}"><div class="w-5 h-5 border-2 border-gray-600 border-t-ui-primary rounded-full animate-spin"></div><span>Loading...</span></div>`);
        } else if (unref(filteredOptions).length === 0) {
          _push2(`<div class="${ssrRenderClass(unref(cn)(
            "py-8 text-center text-gray-400 text-sm",
            (_m = props.ui) == null ? void 0 : _m.emptyState
          ))}" style="${ssrRenderStyle((_n = props.ui) == null ? void 0 : _n.emptyStateStyle)}"> No options found </div>`);
        } else {
          _push2(`<!--[-->`);
          ssrRenderList(unref(filteredOptions), (option, index) => {
            var _a3, _b3, _c3, _d3, _e3, _f3, _g3, _h3, _i3, _j3, _k3, _l3, _m2;
            _push2(`<button type="button"${ssrRenderAttr("data-highlighted", index === highlightedIndex.value)} class="${ssrRenderClass(unref(cn)(
              "w-full flex items-center gap-3 px-4 py-2.5 text-left text-sm transition-colors",
              "hover:bg-ui-card/70",
              isSelected(option) && "bg-ui-primary/20 text-ui-primary",
              index === highlightedIndex.value && "bg-ui-card/70",
              option.disabled && "opacity-50 cursor-not-allowed",
              isMaxReached.value && !isSelected(option) && "opacity-50 cursor-not-allowed",
              (_a3 = props.ui) == null ? void 0 : _a3.option,
              isSelected(option) && ((_b3 = props.ui) == null ? void 0 : _b3.optionSelected),
              index === highlightedIndex.value && ((_c3 = props.ui) == null ? void 0 : _c3.optionHighlighted),
              option.disabled && ((_d3 = props.ui) == null ? void 0 : _d3.optionDisabled)
            ))}" style="${ssrRenderStyle((_e3 = props.ui) == null ? void 0 : _e3.optionStyle)}"${ssrIncludeBooleanAttr(option.disabled || isMaxReached.value && !isSelected(option)) ? " disabled" : ""}><div class="${ssrRenderClass(unref(cn)(
              "w-4 h-4 rounded border-2 flex items-center justify-center shrink-0 transition-colors",
              isSelected(option) ? "bg-ui-primary border-ui-primary" : "border-gray-500 bg-transparent"
            ))}">`);
            if (isSelected(option)) {
              _push2(ssrRenderComponent(unref(Check), { class: "w-3 h-3 text-ui-primary-foreground" }, null, _parent));
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div>`);
            if (option.image) {
              _push2(`<img${ssrRenderAttr("src", option.image)} class="${ssrRenderClass(unref(cn)("w-6 h-6 rounded-full object-cover", (_f3 = props.ui) == null ? void 0 : _f3.optionIcon))}" style="${ssrRenderStyle((_g3 = props.ui) == null ? void 0 : _g3.optionIconStyle)}" alt="">`);
            } else if (option.icon) {
              ssrRenderVNode(_push2, createVNode(resolveDynamicComponent(option.icon), {
                class: unref(cn)("w-5 h-5 text-gray-400", (_h3 = props.ui) == null ? void 0 : _h3.optionIcon),
                style: (_i3 = props.ui) == null ? void 0 : _i3.optionIconStyle
              }, null), _parent);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<div class="flex-1 min-w-0"><div class="${ssrRenderClass(unref(cn)("truncate text-ui-foreground", (_j3 = props.ui) == null ? void 0 : _j3.optionLabel))}" style="${ssrRenderStyle((_k3 = props.ui) == null ? void 0 : _k3.optionLabelStyle)}">${ssrInterpolate(option.label)}</div>`);
            if (option.subtitle) {
              _push2(`<div class="${ssrRenderClass(unref(cn)("truncate text-xs text-gray-500", (_l3 = props.ui) == null ? void 0 : _l3.optionSubtitle))}" style="${ssrRenderStyle((_m2 = props.ui) == null ? void 0 : _m2.optionSubtitleStyle)}">${ssrInterpolate(option.subtitle)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div></button>`);
          });
          _push2(`<!--]-->`);
        }
        _push2(`</div></div>`);
      }, teleportTarget.value, false, _parent);
      _push(`</div>`);
    };
  }
});
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/Select/SelectMultiple.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const FILE_TYPE_MAP = {
  // Images
  "image/jpeg": { type: "image", icon: "Image", color: "text-blue-400" },
  "image/png": { type: "image", icon: "Image", color: "text-blue-400" },
  "image/gif": { type: "image", icon: "Image", color: "text-blue-400" },
  "image/webp": { type: "image", icon: "Image", color: "text-blue-400" },
  "image/svg+xml": { type: "image", icon: "Image", color: "text-blue-400" },
  // Videos
  "video/mp4": { type: "video", icon: "Video", color: "text-purple-400" },
  "video/webm": { type: "video", icon: "Video", color: "text-purple-400" },
  "video/ogg": { type: "video", icon: "Video", color: "text-purple-400" },
  "video/quicktime": { type: "video", icon: "Video", color: "text-purple-400" },
  // Audio
  "audio/mpeg": { type: "audio", icon: "Music", color: "text-green-400" },
  "audio/wav": { type: "audio", icon: "Music", color: "text-green-400" },
  "audio/ogg": { type: "audio", icon: "Music", color: "text-green-400" },
  "audio/webm": { type: "audio", icon: "Music", color: "text-green-400" },
  // Documents
  "application/pdf": { type: "pdf", icon: "FileText", color: "text-red-400" },
  "application/msword": { type: "document", icon: "FileText", color: "text-blue-500" },
  "application/vnd.openxmlformats-officedocument.wordprocessingml.document": { type: "document", icon: "FileText", color: "text-blue-500" },
  "text/plain": { type: "document", icon: "FileText", color: "text-gray-400" },
  "text/markdown": { type: "document", icon: "FileText", color: "text-gray-400" },
  // Spreadsheets
  "application/vnd.ms-excel": { type: "spreadsheet", icon: "Table", color: "text-green-500" },
  "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet": { type: "spreadsheet", icon: "Table", color: "text-green-500" },
  "text/csv": { type: "spreadsheet", icon: "Table", color: "text-green-500" },
  // Archives
  "application/zip": { type: "archive", icon: "Archive", color: "text-yellow-400" },
  "application/x-rar-compressed": { type: "archive", icon: "Archive", color: "text-yellow-400" },
  "application/x-7z-compressed": { type: "archive", icon: "Archive", color: "text-yellow-400" },
  "application/gzip": { type: "archive", icon: "Archive", color: "text-yellow-400" },
  // Code
  "application/json": { type: "code", icon: "Code", color: "text-amber-400" },
  "application/javascript": { type: "code", icon: "Code", color: "text-amber-400" },
  "text/html": { type: "code", icon: "Code", color: "text-amber-400" },
  "text/css": { type: "code", icon: "Code", color: "text-amber-400" }
};
function formatFileSize(bytes) {
  if (bytes === 0) return "0 Bytes";
  const k2 = 1024;
  const sizes = ["Bytes", "KB", "MB", "GB", "TB"];
  const i2 = Math.floor(Math.log(bytes) / Math.log(k2));
  return `${parseFloat((bytes / Math.pow(k2, i2)).toFixed(2))} ${sizes[i2]}`;
}
function getFileType(mimeType) {
  const typeInfo = FILE_TYPE_MAP[mimeType];
  if (typeInfo) return typeInfo.type;
  if (mimeType.startsWith("image/")) return "image";
  if (mimeType.startsWith("video/")) return "video";
  if (mimeType.startsWith("audio/")) return "audio";
  if (mimeType.startsWith("text/")) return "document";
  return "unknown";
}
function createPreviewUrl(file) {
  return URL.createObjectURL(file);
}
function revokePreviewUrl(url) {
  URL.revokeObjectURL(url);
}
function validateFile(file, options) {
  var _a;
  const errors = [];
  if (options.accept) {
    const acceptedTypes = options.accept.split(",").map((t3) => t3.trim().toLowerCase());
    const fileType = file.type.toLowerCase();
    const fileExt = `.${(_a = file.name.split(".").pop()) == null ? void 0 : _a.toLowerCase()}`;
    const isAccepted = acceptedTypes.some((accepted) => {
      if (accepted.startsWith(".")) {
        return fileExt === accepted;
      } else if (accepted.endsWith("/*")) {
        const prefix = accepted.slice(0, -1);
        return fileType.startsWith(prefix);
      } else {
        return fileType === accepted;
      }
    });
    if (!isAccepted) {
      errors.push(`File type "${file.type || fileExt}" is not allowed. Accepted: ${options.accept}`);
    }
  }
  if (options.maxSize && file.size > options.maxSize) {
    errors.push(`File size (${formatFileSize(file.size)}) exceeds maximum allowed (${formatFileSize(options.maxSize)})`);
  }
  if (options.maxFiles && options.currentCount !== void 0) {
    if (options.currentCount >= options.maxFiles) {
      errors.push(`Maximum number of files (${options.maxFiles}) reached`);
    }
  }
  return {
    valid: errors.length === 0,
    errors
  };
}
function validateFiles(files, options) {
  const validFiles = [];
  const invalidFiles = [];
  let currentCount = options.currentCount || 0;
  for (const file of files) {
    const result = validateFile(file, { ...options, currentCount });
    if (result.valid) {
      validFiles.push(file);
      currentCount++;
    } else {
      invalidFiles.push({ file, errors: result.errors });
    }
  }
  return { validFiles, invalidFiles };
}
const _sfc_main$4 = /* @__PURE__ */ defineComponent({
  __name: "FileDropZone",
  __ssrInlineRender: true,
  props: {
    accept: { default: void 0 },
    maxSize: { default: 10485760 },
    maxFiles: { default: void 0 },
    multiple: { type: Boolean, default: true },
    disabled: { type: Boolean, default: false },
    ui: { default: () => ({}) }
  },
  emits: ["select", "validation-error", "drag-enter", "drag-leave"],
  setup(__props, { expose: __expose, emit: __emit }) {
    const props = __props;
    const isDragging = ref(false);
    const inputRef = ref(null);
    const rootClass = computed(() => {
      const baseClasses = `
        relative flex flex-col items-center justify-center
        min-h-[180px] p-8
        border-2 border-dashed rounded-xl
        transition-all duration-300 ease-out
        cursor-pointer
    `.trim();
      if (props.disabled) {
        return cn(
          baseClasses,
          "bg-ui-muted/20 border-ui-border/50 cursor-not-allowed opacity-50",
          props.ui.disabled,
          props.ui.root
        );
      }
      if (isDragging.value) {
        return cn(
          baseClasses,
          "bg-ui-primary/10 border-ui-primary scale-[1.02] shadow-lg shadow-ui-primary/20",
          props.ui.active,
          props.ui.root
        );
      }
      return cn(
        baseClasses,
        "bg-ui-muted/30 border-ui-border hover:border-ui-ring hover:bg-ui-muted/50",
        props.ui.root
      );
    });
    function open() {
      var _a;
      (_a = inputRef.value) == null ? void 0 : _a.click();
    }
    __expose({
      open,
      isDragging
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: rootClass.value,
        style: __props.ui.rootStyle
      }, _attrs))}><input type="file"${ssrRenderAttr("accept", __props.accept)}${ssrIncludeBooleanAttr(__props.multiple) ? " multiple" : ""}${ssrIncludeBooleanAttr(__props.disabled) ? " disabled" : ""} class="sr-only">`);
      ssrRenderSlot(_ctx.$slots, "default", {
        isDragging: isDragging.value,
        open
      }, () => {
        _push(`<div class="flex flex-col items-center gap-4"><div class="${ssrRenderClass(unref(cn)(
          "p-4 rounded-full bg-ui-primary/10 transition-all duration-300",
          isDragging.value ? "scale-110 bg-ui-primary/20" : ""
        ))}">`);
        _push(ssrRenderComponent(unref(CloudUpload), {
          class: unref(cn)(
            "w-10 h-10 transition-all duration-300",
            isDragging.value ? "text-ui-primary scale-110" : "text-ui-muted-foreground",
            __props.ui.icon
          ),
          style: __props.ui.iconStyle
        }, null, _parent));
        _push(`</div><div class="text-center"><p class="${ssrRenderClass(unref(cn)(
          "text-base font-medium transition-colors duration-300",
          isDragging.value ? "text-ui-primary" : "text-ui-foreground",
          __props.ui.text
        ))}" style="${ssrRenderStyle(__props.ui.textStyle)}">`);
        if (isDragging.value) {
          _push(`<!--[--> Drop files here <!--]-->`);
        } else {
          _push(`<!--[--><span class="text-ui-primary font-semibold">Click to upload</span><span class="text-ui-muted-foreground"> or drag and drop</span><!--]-->`);
        }
        _push(`</p><p class="${ssrRenderClass(unref(cn)("text-sm text-ui-muted-foreground mt-1", __props.ui.subtext))}" style="${ssrRenderStyle(__props.ui.subtextStyle)}">`);
        ssrRenderSlot(_ctx.$slots, "subtext", {}, () => {
          _push(`${ssrInterpolate(__props.accept || "Any file type")} up to ${ssrInterpolate(Math.round((__props.maxSize || 10485760) / 1048576))}MB `);
        }, _push, _parent);
        _push(`</p></div></div>`);
      }, _push, _parent);
      if (isDragging.value) {
        _push(`<div class="absolute inset-0 pointer-events-none"><div class="absolute inset-0 bg-gradient-to-br from-ui-primary/5 via-transparent to-ui-primary/5 animate-pulse"></div><div class="absolute inset-0 border-2 border-ui-primary rounded-xl"></div></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/FileUploader/FileDropZone.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const _sfc_main$3 = /* @__PURE__ */ defineComponent({
  __name: "FilePreview",
  __ssrInlineRender: true,
  props: {
    file: {},
    showInfo: { type: Boolean, default: true },
    showRemove: { type: Boolean, default: false },
    showDownload: { type: Boolean, default: false },
    showProgress: { type: Boolean, default: false },
    progress: { default: 0 },
    status: { default: "pending" },
    aspectRatio: { default: "square" },
    size: { default: "md" },
    ui: { default: () => ({}) }
  },
  emits: ["remove", "download"],
  setup(__props, { expose: __expose, emit: __emit }) {
    const props = __props;
    const previewUrl = ref(null);
    const isLoading = ref(true);
    const fileData = computed(() => {
      if (props.file instanceof File) {
        return {
          name: props.file.name,
          type: props.file.type,
          size: props.file.size,
          url: void 0,
          isFile: true
        };
      }
      const source = props.file;
      return {
        name: source.name,
        type: source.type,
        size: source.size,
        url: source.url,
        isFile: false
      };
    });
    const fileType = computed(() => {
      return getFileType(fileData.value.type);
    });
    const canPreview = computed(() => {
      return ["image", "video", "audio"].includes(fileType.value);
    });
    const iconComponent = computed(() => {
      const icons = {
        image: Image,
        video: Video,
        audio: Music,
        pdf: FileText,
        document: FileText,
        spreadsheet: Table,
        archive: Archive,
        code: Code,
        unknown: File$1
      };
      return icons[fileType.value] || File$1;
    });
    const iconColor = computed(() => {
      const colors = {
        image: "text-blue-400",
        video: "text-purple-400",
        audio: "text-green-400",
        pdf: "text-red-400",
        document: "text-blue-500",
        spreadsheet: "text-green-500",
        archive: "text-yellow-400",
        code: "text-amber-400",
        unknown: "text-gray-400"
      };
      return colors[fileType.value] || "text-gray-400";
    });
    const statusIcon = computed(() => {
      const icons = {
        pending: null,
        uploading: Loader2,
        success: CheckCircle,
        error: AlertCircle,
        cancelled: XCircle
      };
      return icons[props.status];
    });
    const statusColor = computed(() => {
      const colors = {
        pending: "text-gray-400",
        uploading: "text-blue-400",
        success: "text-green-400",
        error: "text-red-400",
        cancelled: "text-yellow-400"
      };
      return colors[props.status];
    });
    const sizeClasses = computed(() => {
      const sizes = {
        xs: { container: "w-16 h-16", icon: "w-6 h-6" },
        sm: { container: "w-24 h-24", icon: "w-8 h-8" },
        md: { container: "w-32 h-32", icon: "w-10 h-10" },
        lg: { container: "w-40 h-40", icon: "w-12 h-12" },
        xl: { container: "w-48 h-48", icon: "w-14 h-14" }
      };
      return sizes[props.size] || sizes.md;
    });
    const aspectClasses = computed(() => {
      const aspects = {
        square: "aspect-square",
        "16:9": "aspect-video",
        "4:3": "aspect-[4/3]",
        auto: ""
      };
      return aspects[props.aspectRatio] || "";
    });
    const rootClass = computed(() => {
      return cn(
        "relative group rounded-lg overflow-hidden bg-ui-muted/30 border border-ui-border transition-all duration-200",
        "hover:border-ui-ring hover:shadow-lg",
        sizeClasses.value.container,
        aspectClasses.value,
        props.ui.root
      );
    });
    const previewClass = computed(() => {
      return cn(
        "w-full h-full flex items-center justify-center",
        props.ui.preview
      );
    });
    onMounted(() => {
      if (canPreview.value) {
        if (props.file instanceof File) {
          previewUrl.value = createPreviewUrl(props.file);
        } else {
          previewUrl.value = props.file.url;
        }
      }
      setTimeout(() => {
        isLoading.value = false;
      }, 100);
    });
    onUnmounted(() => {
      if (previewUrl.value && props.file instanceof File) {
        revokePreviewUrl(previewUrl.value);
      }
    });
    __expose({
      getType: () => fileType.value,
      getPreviewUrl: () => previewUrl.value
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: rootClass.value,
        style: __props.ui.rootStyle
      }, _attrs))}><div class="${ssrRenderClass(previewClass.value)}" style="${ssrRenderStyle(__props.ui.previewStyle)}">`);
      if (isLoading.value) {
        _push(`<div class="absolute inset-0 flex items-center justify-center bg-ui-muted/50">`);
        _push(ssrRenderComponent(unref(Loader2), { class: "w-6 h-6 text-ui-muted-foreground animate-spin" }, null, _parent));
        _push(`</div>`);
      } else if (fileType.value === "image" && previewUrl.value) {
        _push(`<img${ssrRenderAttr("src", previewUrl.value)}${ssrRenderAttr("alt", fileData.value.name)} class="${ssrRenderClass(unref(cn)("w-full h-full object-cover", __props.ui.image))}" style="${ssrRenderStyle(__props.ui.imageStyle)}">`);
      } else if (fileType.value === "video" && previewUrl.value) {
        _push(`<video${ssrRenderAttr("src", previewUrl.value)} class="${ssrRenderClass(unref(cn)("w-full h-full object-cover", __props.ui.video))}" style="${ssrRenderStyle(__props.ui.videoStyle)}" controls preload="metadata"></video>`);
      } else if (fileType.value === "audio" && previewUrl.value) {
        _push(`<div class="flex flex-col items-center justify-center gap-2 p-2">`);
        ssrRenderVNode(_push, createVNode(resolveDynamicComponent(iconComponent.value), {
          class: unref(cn)(sizeClasses.value.icon, iconColor.value, __props.ui.icon),
          style: __props.ui.iconStyle
        }, null), _parent);
        _push(`<audio${ssrRenderAttr("src", previewUrl.value)} class="${ssrRenderClass(unref(cn)("w-full max-w-[120px]", __props.ui.audio))}" style="${ssrRenderStyle(__props.ui.audioStyle)}" controls preload="metadata"></audio></div>`);
      } else {
        _push(`<div class="flex flex-col items-center justify-center gap-2 p-2">`);
        ssrRenderVNode(_push, createVNode(resolveDynamicComponent(iconComponent.value), {
          class: unref(cn)(sizeClasses.value.icon, iconColor.value, __props.ui.icon),
          style: __props.ui.iconStyle
        }, null), _parent);
        _push(`</div>`);
      }
      _push(`</div><div class="${ssrRenderClass(unref(cn)(
        "absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-200",
        "flex items-center justify-center gap-2",
        __props.ui.overlay
      ))}" style="${ssrRenderStyle(__props.ui.overlayStyle)}">`);
      if (__props.showDownload) {
        _push(`<button type="button" class="${ssrRenderClass(unref(cn)(
          "p-2 rounded-full bg-white/20 hover:bg-white/30 text-white transition-colors",
          __props.ui.downloadButton
        ))}" style="${ssrRenderStyle(__props.ui.downloadButtonStyle)}" title="Download">`);
        _push(ssrRenderComponent(unref(Download), { class: "w-4 h-4" }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      if (__props.showRemove) {
        _push(`<button type="button" class="${ssrRenderClass(unref(cn)(
          "p-2 rounded-full bg-red-500/80 hover:bg-red-500 text-white transition-colors",
          __props.ui.removeButton
        ))}" style="${ssrRenderStyle(__props.ui.removeButtonStyle)}" title="Remove">`);
        _push(ssrRenderComponent(unref(X), { class: "w-4 h-4" }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
      if (__props.showProgress && __props.status === "uploading") {
        _push(`<div class="absolute bottom-0 left-0 right-0 h-1.5 bg-gray-700"><div class="${ssrRenderClass(unref(cn)("h-full bg-gradient-to-r from-blue-500 to-indigo-500 transition-all duration-300", __props.ui.progressBar))}" style="${ssrRenderStyle([{ width: `${__props.progress}%` }, __props.ui.progressBarStyle])}"></div></div>`);
      } else {
        _push(`<!---->`);
      }
      if (statusIcon.value) {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "absolute top-1 right-1 p-1 rounded-full",
          __props.status === "uploading" ? "bg-blue-500/20" : "",
          __props.status === "success" ? "bg-green-500/20" : "",
          __props.status === "error" ? "bg-red-500/20" : "",
          __props.status === "cancelled" ? "bg-yellow-500/20" : "",
          __props.ui.statusBadge
        ))}" style="${ssrRenderStyle(__props.ui.statusBadgeStyle)}">`);
        ssrRenderVNode(_push, createVNode(resolveDynamicComponent(statusIcon.value), {
          class: unref(cn)("w-4 h-4", statusColor.value, __props.status === "uploading" ? "animate-spin" : "")
        }, null), _parent);
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      if (__props.showInfo) {
        _push(`<div class="${ssrRenderClass(unref(cn)(
          "absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/80 to-transparent",
          __props.ui.info
        ))}" style="${ssrRenderStyle(__props.ui.infoStyle)}"><p class="${ssrRenderClass(unref(cn)("text-xs text-white font-medium truncate", __props.ui.name))}" style="${ssrRenderStyle(__props.ui.nameStyle)}"${ssrRenderAttr("title", fileData.value.name)}>${ssrInterpolate(fileData.value.name)}</p>`);
        if (fileData.value.size) {
          _push(`<p class="${ssrRenderClass(unref(cn)("text-xs text-gray-400", __props.ui.size))}" style="${ssrRenderStyle(__props.ui.sizeStyle)}">${ssrInterpolate(unref(formatFileSize)(fileData.value.size))}</p>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/FileUploader/FilePreview.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "FileUploader",
  __ssrInlineRender: true,
  props: {
    modelValue: { default: () => [] },
    accept: { default: void 0 },
    maxSize: { default: 10485760 },
    maxFiles: { default: void 0 },
    multiple: { type: Boolean, default: true },
    disabled: { type: Boolean, default: false },
    label: { default: void 0 },
    hint: { default: void 0 },
    error: { default: void 0 },
    ui: { default: () => ({}) }
  },
  emits: ["update:modelValue", "select", "remove", "validation-error"],
  setup(__props, { expose: __expose, emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const dropZoneRef = ref(null);
    const internalFiles = ref([]);
    watch(() => props.modelValue, (newFiles) => {
      internalFiles.value.forEach((f2) => {
        if (f2.previewUrl && !newFiles.includes(f2.file)) {
          revokePreviewUrl(f2.previewUrl);
        }
      });
      internalFiles.value = newFiles.map((file) => {
        const existing = internalFiles.value.find((f2) => f2.file === file);
        if (existing) return existing;
        return {
          file,
          previewUrl: createPreviewUrl(file)
        };
      });
    }, { immediate: true });
    function handleSelect(selectedFiles) {
      console.log("[FileUploader] handleSelect called with", selectedFiles.length, "files");
      let currentFiles = [...props.modelValue];
      if (!props.multiple) {
        console.log("[FileUploader] Single mode: clearing existing files");
        currentFiles = [];
      }
      const { validFiles, invalidFiles } = validateFiles(selectedFiles, {
        accept: props.accept,
        maxSize: props.maxSize,
        maxFiles: props.multiple ? props.maxFiles : 1,
        currentCount: props.multiple ? currentFiles.length : 0
      });
      console.log("[FileUploader] Validation result:", { valid: validFiles.length, invalid: invalidFiles.length });
      emit("select", { files: selectedFiles, validFiles, invalidFiles });
      if (invalidFiles.length > 0) {
        emit("validation-error", invalidFiles);
      }
      if (validFiles.length > 0) {
        const newFiles = [...currentFiles, ...validFiles];
        emit("update:modelValue", newFiles);
      }
    }
    function handleValidationError(errors) {
      emit("validation-error", errors);
    }
    function handleRemove(index) {
      var _a;
      const file = (_a = internalFiles.value[index]) == null ? void 0 : _a.file;
      if (!file) return;
      const newFiles = props.modelValue.filter((_2, i2) => i2 !== index);
      emit("remove", { file, index });
      emit("update:modelValue", newFiles);
    }
    function clear() {
      emit("update:modelValue", []);
    }
    function open() {
      var _a;
      (_a = dropZoneRef.value) == null ? void 0 : _a.open();
    }
    function getFiles() {
      return props.modelValue;
    }
    onUnmounted(() => {
      internalFiles.value.forEach((file) => {
        if (file.previewUrl) {
          revokePreviewUrl(file.previewUrl);
        }
      });
    });
    const rootClass = computed(() => {
      return cn("w-full", props.ui.root);
    });
    const previewContainerClass = computed(() => {
      return cn(
        "flex flex-wrap gap-4 mt-4",
        props.ui.previewContainer
      );
    });
    const hasFiles = computed(() => props.modelValue.length > 0);
    __expose({
      open,
      clear,
      removeFile: handleRemove,
      getFiles
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: rootClass.value,
        style: __props.ui.rootStyle
      }, _attrs))}>`);
      if (__props.label) {
        _push(ssrRenderComponent(_sfc_main$V, {
          value: __props.label,
          class: __props.ui.label,
          style: __props.ui.labelStyle
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(ssrRenderComponent(_sfc_main$4, {
        ref_key: "dropZoneRef",
        ref: dropZoneRef,
        accept: __props.accept,
        "max-size": __props.maxSize,
        "max-files": __props.maxFiles,
        multiple: __props.multiple,
        disabled: __props.disabled,
        ui: {
          root: __props.ui.dropZone,
          rootStyle: __props.ui.dropZoneStyle,
          active: __props.ui.dropZoneActive,
          activeStyle: __props.ui.dropZoneActiveStyle,
          disabled: __props.ui.dropZoneDisabled,
          disabledStyle: __props.ui.dropZoneDisabledStyle,
          icon: __props.ui.icon,
          iconStyle: __props.ui.iconStyle,
          text: __props.ui.text,
          textStyle: __props.ui.textStyle
        },
        onSelect: handleSelect,
        onValidationError: handleValidationError
      }, {
        subtext: withCtx((_2, _push2, _parent2, _scopeId) => {
          if (_push2) {
            ssrRenderSlot(_ctx.$slots, "hint", {}, () => {
              _push2(`${ssrInterpolate(__props.hint || `${__props.accept || "Any file"} up to ${Math.round((__props.maxSize || 10485760) / 1048576)}MB`)}`);
            }, _push2, _parent2, _scopeId);
          } else {
            return [
              renderSlot(_ctx.$slots, "hint", {}, () => [
                createTextVNode(toDisplayString(__props.hint || `${__props.accept || "Any file"} up to ${Math.round((__props.maxSize || 10485760) / 1048576)}MB`), 1)
              ])
            ];
          }
        }),
        _: 3
      }, _parent));
      if (hasFiles.value) {
        _push(`<div class="${ssrRenderClass(previewContainerClass.value)}" style="${ssrRenderStyle(__props.ui.previewContainerStyle)}"><!--[-->`);
        ssrRenderList(internalFiles.value, (internalFile, index) => {
          _push(`<div class="relative group shrink-0">`);
          _push(ssrRenderComponent(_sfc_main$3, {
            file: internalFile.file,
            "show-remove": true,
            "show-info": true,
            size: "md",
            ui: {
              root: __props.ui.previewItem,
              rootStyle: __props.ui.previewItemStyle,
              preview: __props.ui.preview,
              previewStyle: __props.ui.previewStyle,
              image: __props.ui.image,
              imageStyle: __props.ui.imageStyle,
              video: __props.ui.video,
              videoStyle: __props.ui.videoStyle,
              audio: __props.ui.audio,
              audioStyle: __props.ui.audioStyle,
              icon: __props.ui.icon,
              iconStyle: __props.ui.iconStyle,
              overlay: __props.ui.overlay,
              overlayStyle: __props.ui.overlayStyle,
              removeButton: __props.ui.removeButton,
              removeButtonStyle: __props.ui.removeButtonStyle,
              downloadButton: __props.ui.downloadButton,
              downloadButtonStyle: __props.ui.downloadButtonStyle,
              info: __props.ui.info,
              infoStyle: __props.ui.infoStyle,
              name: __props.ui.name,
              nameStyle: __props.ui.nameStyle,
              size: __props.ui.size,
              sizeStyle: __props.ui.sizeStyle
            },
            onRemove: ($event) => handleRemove(index)
          }, null, _parent));
          _push(`</div>`);
        });
        _push(`<!--]--></div>`);
      } else {
        _push(`<!---->`);
      }
      if (hasFiles.value) {
        _push(`<div class="flex items-center gap-3 mt-4"><button type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-ui-muted/50 hover:bg-ui-muted text-ui-foreground rounded-lg font-medium transition-colors">`);
        _push(ssrRenderComponent(unref(Trash2), { class: "w-4 h-4" }, null, _parent));
        _push(` Clear all </button></div>`);
      } else {
        _push(`<!---->`);
      }
      if (__props.error) {
        _push(ssrRenderComponent(_sfc_main$W, {
          error: __props.error,
          ui: { wrapper: __props.ui.error, wrapperStyle: __props.ui.errorStyle },
          class: "mt-2"
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      ssrRenderSlot(_ctx.$slots, "footer", { files: __props.modelValue }, null, _push, _parent);
      _push(`</div>`);
    };
  }
});
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/FileUploader/FileUploader.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const IMAGE_ACCEPT = "image/png,image/jpeg,image/gif,image/webp";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "ImageUploader",
  __ssrInlineRender: true,
  props: {
    modelValue: { default: null },
    maxSize: { default: 10485760 },
    disabled: { type: Boolean, default: false },
    label: { default: void 0 },
    error: { default: void 0 },
    ui: { default: () => ({}) }
  },
  emits: ["update:modelValue", "validation-error"],
  setup(__props, { expose: __expose, emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const isDragging = ref(false);
    const inputRef = ref(null);
    const previewUrl = ref(null);
    const localError = ref(null);
    function updatePreview(file) {
      if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
        previewUrl.value = null;
      }
      if (file) {
        previewUrl.value = URL.createObjectURL(file);
      }
    }
    const hasFile = computed(() => {
      const file = props.modelValue;
      if (file && !previewUrl.value) {
        updatePreview(file);
      }
      return file !== null && file !== void 0;
    });
    const fileName = computed(() => {
      var _a;
      return ((_a = props.modelValue) == null ? void 0 : _a.name) ?? "";
    });
    const fileSize = computed(() => props.modelValue ? formatFileSize(props.modelValue.size) : "");
    const displayError = computed(() => props.error ?? localError.value);
    function handleRemove() {
      localError.value = null;
      updatePreview(null);
      emit("update:modelValue", null);
    }
    onUnmounted(() => {
      if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
      }
    });
    const dropZoneClass = computed(() => {
      const base = `
        relative flex flex-col items-center justify-center
        min-h-[180px] p-8
        border-2 border-dashed rounded-xl
        transition-all duration-300 ease-out
        cursor-pointer select-none
    `.trim();
      if (props.disabled) {
        return cn(
          base,
          "bg-ui-muted/20 border-ui-border/50 cursor-not-allowed opacity-50",
          props.ui.dropZoneDisabled,
          props.ui.dropZone
        );
      }
      if (isDragging.value) {
        return cn(
          base,
          "bg-ui-primary/10 border-ui-primary scale-[1.02] shadow-lg shadow-ui-primary/20",
          props.ui.dropZoneActive,
          props.ui.dropZone
        );
      }
      return cn(
        base,
        "bg-ui-muted/30 border-ui-border hover:border-ui-ring hover:bg-ui-muted/50",
        props.ui.dropZone
      );
    });
    const previewContainerClass = computed(() => {
      return cn(
        "relative mt-4 rounded-xl overflow-hidden border border-ui-border bg-ui-muted/20",
        "transition-all duration-300",
        props.ui.preview
      );
    });
    __expose({
      open: () => {
        var _a;
        return (_a = inputRef.value) == null ? void 0 : _a.click();
      },
      clear: handleRemove
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: unref(cn)("w-full", __props.ui.root),
        style: __props.ui.rootStyle
      }, _attrs))}>`);
      if (__props.label) {
        _push(ssrRenderComponent(_sfc_main$V, {
          value: __props.label,
          class: __props.ui.label,
          style: __props.ui.labelStyle
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      if (!hasFile.value) {
        _push(`<div class="${ssrRenderClass(dropZoneClass.value)}" style="${ssrRenderStyle(__props.ui.dropZoneStyle)}" role="button" tabindex="0"${ssrRenderAttr("aria-disabled", __props.disabled)} aria-label="Upload image. Click or drag and drop an image file."><input type="file"${ssrRenderAttr("accept", IMAGE_ACCEPT)}${ssrIncludeBooleanAttr(__props.disabled) ? " disabled" : ""} class="sr-only" aria-hidden="true"><div class="${ssrRenderClass(unref(cn)(
          "p-4 rounded-full bg-ui-primary/10 transition-all duration-300",
          isDragging.value ? "scale-110 bg-ui-primary/20" : ""
        ))}">`);
        _push(ssrRenderComponent(unref(ImagePlus), {
          class: unref(cn)(
            "w-10 h-10 transition-all duration-300",
            isDragging.value ? "text-ui-primary scale-110" : "text-ui-muted-foreground",
            __props.ui.dropZoneIcon
          ),
          style: __props.ui.dropZoneIconStyle
        }, null, _parent));
        _push(`</div><div class="text-center mt-4"><p class="${ssrRenderClass(unref(cn)(
          "text-base font-medium transition-colors duration-300",
          isDragging.value ? "text-ui-primary" : "text-ui-foreground",
          __props.ui.dropZoneText
        ))}" style="${ssrRenderStyle(__props.ui.dropZoneTextStyle)}">`);
        if (isDragging.value) {
          _push(`<!--[--> Drop image here <!--]-->`);
        } else {
          _push(`<!--[--><span class="text-ui-primary font-semibold">Click to upload</span><span class="text-ui-muted-foreground"> or drag and drop</span><!--]-->`);
        }
        _push(`</p><p class="${ssrRenderClass(unref(cn)("text-sm text-ui-muted-foreground mt-1", __props.ui.dropZoneHint))}" style="${ssrRenderStyle(__props.ui.dropZoneHintStyle)}"> PNG, JPG, GIF or WebP up to ${ssrInterpolate(Math.round(__props.maxSize / 1048576))}MB </p></div>`);
        if (isDragging.value) {
          _push(`<div class="absolute inset-0 pointer-events-none"><div class="absolute inset-0 bg-gradient-to-br from-ui-primary/5 via-transparent to-ui-primary/5 animate-pulse"></div><div class="absolute inset-0 border-2 border-ui-primary rounded-xl"></div></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      if (hasFile.value) {
        _push(`<div class="${ssrRenderClass(previewContainerClass.value)}" style="${ssrRenderStyle(__props.ui.previewStyle)}"><div class="relative">`);
        if (previewUrl.value) {
          _push(`<img${ssrRenderAttr("src", previewUrl.value)}${ssrRenderAttr("alt", fileName.value)} class="${ssrRenderClass(unref(cn)("w-full max-h-80 object-contain bg-ui-muted/30", __props.ui.previewImage))}" style="${ssrRenderStyle(__props.ui.previewImageStyle)}">`);
        } else {
          _push(`<!---->`);
        }
        _push(`<button type="button" class="${ssrRenderClass(unref(cn)(
          "absolute top-2 right-2 p-1.5 rounded-full",
          "bg-black/60 hover:bg-red-500 text-white",
          "transition-colors duration-200",
          "focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 focus:ring-offset-transparent",
          __props.ui.removeButton
        ))}" style="${ssrRenderStyle(__props.ui.removeButtonStyle)}" aria-label="Remove image">`);
        _push(ssrRenderComponent(unref(X), { class: "w-4 h-4" }, null, _parent));
        _push(`</button></div><div class="${ssrRenderClass(unref(cn)(
          "flex items-center justify-between px-4 py-3 border-t border-ui-border",
          __props.ui.previewInfo
        ))}" style="${ssrRenderStyle(__props.ui.previewInfoStyle)}"><p class="${ssrRenderClass(unref(cn)("text-sm text-ui-foreground font-medium truncate mr-3", __props.ui.previewName))}" style="${ssrRenderStyle(__props.ui.previewNameStyle)}"${ssrRenderAttr("title", fileName.value)}>${ssrInterpolate(fileName.value)}</p><p class="${ssrRenderClass(unref(cn)("text-xs text-ui-muted-foreground whitespace-nowrap", __props.ui.previewSize))}" style="${ssrRenderStyle(__props.ui.previewSizeStyle)}">${ssrInterpolate(fileSize.value)}</p></div><input type="file"${ssrRenderAttr("accept", IMAGE_ACCEPT)}${ssrIncludeBooleanAttr(__props.disabled) ? " disabled" : ""} class="sr-only" aria-hidden="true"></div>`);
      } else {
        _push(`<!---->`);
      }
      if (displayError.value) {
        _push(ssrRenderComponent(_sfc_main$W, {
          error: displayError.value,
          ui: { wrapper: __props.ui.error, wrapperStyle: __props.ui.errorStyle },
          class: "mt-2"
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/FileUploader/ImageUploader.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ThemeProvider",
  __ssrInlineRender: true,
  props: {
    defaultTheme: { default: "dark-indigo" },
    storageKey: { default: "ui-theme" }
  },
  setup(__props) {
    const props = __props;
    const getInitialTheme = () => {
      if (typeof window !== "undefined" && localStorage.getItem(props.storageKey)) {
        return localStorage.getItem(props.storageKey);
      }
      return props.defaultTheme;
    };
    const theme = ref(getInitialTheme());
    const themes = ["dark-indigo", "dark-borg"];
    watch(theme, (val) => {
      if (typeof window !== "undefined") {
        localStorage.setItem(props.storageKey, val);
        document.documentElement.setAttribute("data-theme", val);
        if (val.startsWith("dark-")) {
          document.documentElement.classList.add("dark");
        } else {
          document.documentElement.classList.remove("dark");
        }
      }
    });
    onMounted(() => {
      document.documentElement.setAttribute("data-theme", theme.value);
      if (theme.value.startsWith("dark-")) {
        document.documentElement.classList.add("dark");
      } else {
        document.documentElement.classList.remove("dark");
      }
    });
    provide(ThemeKey, {
      theme,
      setTheme: (t3) => {
        if (themes.includes(t3)) {
          theme.value = t3;
        }
      },
      themes
    });
    return (_ctx, _push, _parent, _attrs) => {
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-ui-components/src/components/ThemeProvider.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
function t$1() {
  return t$1 = Object.assign ? Object.assign.bind() : function(t3) {
    for (var e2 = 1; e2 < arguments.length; e2++) {
      var o2 = arguments[e2];
      for (var n2 in o2) ({}).hasOwnProperty.call(o2, n2) && (t3[n2] = o2[n2]);
    }
    return t3;
  }, t$1.apply(null, arguments);
}
const e = String.prototype.replace, o = /%20/g, n = { RFC1738: function(t3) {
  return e.call(t3, o, "+");
}, RFC3986: function(t3) {
  return String(t3);
} };
var r = "RFC3986";
const i = Object.prototype.hasOwnProperty, s = Array.isArray, u = /* @__PURE__ */ new WeakMap();
var l = function(t3, e2) {
  return u.set(t3, e2), t3;
};
function c(t3) {
  return u.has(t3);
}
var a = function(t3) {
  return u.get(t3);
}, f = function(t3, e2) {
  u.set(t3, e2);
};
const p = (function() {
  const t3 = [];
  for (let e2 = 0; e2 < 256; ++e2) t3.push("%" + ((e2 < 16 ? "0" : "") + e2.toString(16)).toUpperCase());
  return t3;
})(), y = function(t3, e2) {
  const o2 = e2 && e2.plainObjects ? /* @__PURE__ */ Object.create(null) : {};
  for (let e3 = 0; e3 < t3.length; ++e3) void 0 !== t3[e3] && (o2[e3] = t3[e3]);
  return o2;
}, d = function t(e2, o2, n2) {
  if (!o2) return e2;
  if ("object" != typeof o2) {
    if (s(e2)) e2.push(o2);
    else {
      if (!e2 || "object" != typeof e2) return [e2, o2];
      if (c(e2)) {
        var r2 = a(e2) + 1;
        e2[r2] = o2, f(e2, r2);
      } else (n2 && (n2.plainObjects || n2.allowPrototypes) || !i.call(Object.prototype, o2)) && (e2[o2] = true);
    }
    return e2;
  }
  if (!e2 || "object" != typeof e2) {
    if (c(o2)) {
      for (var u2 = Object.keys(o2), p2 = n2 && n2.plainObjects ? { __proto__: null, 0: e2 } : { 0: e2 }, d2 = 0; d2 < u2.length; d2++) p2[parseInt(u2[d2], 10) + 1] = o2[u2[d2]];
      return l(p2, a(o2) + 1);
    }
    return [e2].concat(o2);
  }
  let h2 = e2;
  return s(e2) && !s(o2) && (h2 = y(e2, n2)), s(e2) && s(o2) ? (o2.forEach(function(o3, r3) {
    if (i.call(e2, r3)) {
      const i2 = e2[r3];
      i2 && "object" == typeof i2 && o3 && "object" == typeof o3 ? e2[r3] = t(i2, o3, n2) : e2.push(o3);
    } else e2[r3] = o3;
  }), e2) : Object.keys(o2).reduce(function(e3, r3) {
    const s2 = o2[r3];
    return e3[r3] = i.call(e3, r3) ? t(e3[r3], s2, n2) : s2, e3;
  }, h2);
}, h = 1024, b = function(t3, e2, o2, n2) {
  if (c(t3)) {
    var r2 = a(t3) + 1;
    return t3[r2] = e2, f(t3, r2), t3;
  }
  var i2 = [].concat(t3, e2);
  return i2.length > o2 ? l(y(i2, { plainObjects: n2 }), i2.length - 1) : i2;
}, m = function(t3, e2) {
  if (s(t3)) {
    const o2 = [];
    for (let n2 = 0; n2 < t3.length; n2 += 1) o2.push(e2(t3[n2]));
    return o2;
  }
  return e2(t3);
}, g = Object.prototype.hasOwnProperty, w = { brackets: function(t3) {
  return t3 + "[]";
}, comma: "comma", indices: function(t3, e2) {
  return t3 + "[" + e2 + "]";
}, repeat: function(t3) {
  return t3;
} }, v = Array.isArray, j = Array.prototype.push, $ = function(t3, e2) {
  j.apply(t3, v(e2) ? e2 : [e2]);
}, E = Date.prototype.toISOString, O = { addQueryPrefix: false, allowDots: false, allowEmptyArrays: false, arrayFormat: "indices", charset: "utf-8", charsetSentinel: false, delimiter: "&", encode: true, encodeDotInKeys: false, encoder: function(t3, e2, o2, n2, r2) {
  if (0 === t3.length) return t3;
  let i2 = t3;
  if ("symbol" == typeof t3 ? i2 = Symbol.prototype.toString.call(t3) : "string" != typeof t3 && (i2 = String(t3)), "iso-8859-1" === o2) return escape(i2).replace(/%u[0-9a-f]{4}/gi, function(t4) {
    return "%26%23" + parseInt(t4.slice(2), 16) + "%3B";
  });
  let s2 = "";
  for (let t4 = 0; t4 < i2.length; t4 += h) {
    const e3 = i2.length >= h ? i2.slice(t4, t4 + h) : i2, o3 = [];
    for (let t5 = 0; t5 < e3.length; ++t5) {
      let n3 = e3.charCodeAt(t5);
      45 === n3 || 46 === n3 || 95 === n3 || 126 === n3 || n3 >= 48 && n3 <= 57 || n3 >= 65 && n3 <= 90 || n3 >= 97 && n3 <= 122 || "RFC1738" === r2 && (40 === n3 || 41 === n3) ? o3[o3.length] = e3.charAt(t5) : n3 < 128 ? o3[o3.length] = p[n3] : n3 < 2048 ? o3[o3.length] = p[192 | n3 >> 6] + p[128 | 63 & n3] : n3 < 55296 || n3 >= 57344 ? o3[o3.length] = p[224 | n3 >> 12] + p[128 | n3 >> 6 & 63] + p[128 | 63 & n3] : (t5 += 1, n3 = 65536 + ((1023 & n3) << 10 | 1023 & e3.charCodeAt(t5)), o3[o3.length] = p[240 | n3 >> 18] + p[128 | n3 >> 12 & 63] + p[128 | n3 >> 6 & 63] + p[128 | 63 & n3]);
    }
    s2 += o3.join("");
  }
  return s2;
}, encodeValuesOnly: false, format: r, formatter: n[r], indices: false, serializeDate: function(t3) {
  return E.call(t3);
}, skipNulls: false, strictNullHandling: false }, T = {}, R = function(t3, e2, o2, n2, r2, i2, s2, u2, l2, c2, a2, f2, p2, y2, d2, h2, b2, g2) {
  let w2 = t3, j2 = g2, E2 = 0, _2 = false;
  for (; void 0 !== (j2 = j2.get(T)) && !_2; ) {
    const e3 = j2.get(t3);
    if (E2 += 1, void 0 !== e3) {
      if (e3 === E2) throw new RangeError("Cyclic object value");
      _2 = true;
    }
    void 0 === j2.get(T) && (E2 = 0);
  }
  if ("function" == typeof c2 ? w2 = c2(e2, w2) : w2 instanceof Date ? w2 = p2(w2) : "comma" === o2 && v(w2) && (w2 = m(w2, function(t4) {
    return t4 instanceof Date ? p2(t4) : t4;
  })), null === w2) {
    if (i2) return l2 && !h2 ? l2(e2, O.encoder, b2, "key", y2) : e2;
    w2 = "";
  }
  if ("string" == typeof (I2 = w2) || "number" == typeof I2 || "boolean" == typeof I2 || "symbol" == typeof I2 || "bigint" == typeof I2 || (function(t4) {
    return !(!t4 || "object" != typeof t4 || !(t4.constructor && t4.constructor.isBuffer && t4.constructor.isBuffer(t4)));
  })(w2)) return l2 ? [d2(h2 ? e2 : l2(e2, O.encoder, b2, "key", y2)) + "=" + d2(l2(w2, O.encoder, b2, "value", y2))] : [d2(e2) + "=" + d2(String(w2))];
  var I2;
  const S2 = [];
  if (void 0 === w2) return S2;
  let A2;
  if ("comma" === o2 && v(w2)) h2 && l2 && (w2 = m(w2, l2)), A2 = [{ value: w2.length > 0 ? w2.join(",") || null : void 0 }];
  else if (v(c2)) A2 = c2;
  else {
    const t4 = Object.keys(w2);
    A2 = a2 ? t4.sort(a2) : t4;
  }
  const D2 = u2 ? e2.replace(/\./g, "%2E") : e2, k2 = n2 && v(w2) && 1 === w2.length ? D2 + "[]" : D2;
  if (r2 && v(w2) && 0 === w2.length) return k2 + "[]";
  for (let e3 = 0; e3 < A2.length; ++e3) {
    const m2 = A2[e3], j3 = "object" == typeof m2 && void 0 !== m2.value ? m2.value : w2[m2];
    if (s2 && null === j3) continue;
    const O2 = f2 && u2 ? m2.replace(/\./g, "%2E") : m2, _3 = v(w2) ? "function" == typeof o2 ? o2(k2, O2) : k2 : k2 + (f2 ? "." + O2 : "[" + O2 + "]");
    g2.set(t3, E2);
    const I3 = /* @__PURE__ */ new WeakMap();
    I3.set(T, g2), $(S2, R(j3, _3, o2, n2, r2, i2, s2, u2, "comma" === o2 && h2 && v(w2) ? null : l2, c2, a2, f2, p2, y2, d2, h2, b2, I3));
  }
  return S2;
}, _ = Object.prototype.hasOwnProperty, I = Array.isArray, S = { allowDots: false, allowEmptyArrays: false, allowPrototypes: false, allowSparse: false, arrayLimit: 20, charset: "utf-8", charsetSentinel: false, comma: false, decodeDotInKeys: false, decoder: function(t3, e2, o2) {
  const n2 = t3.replace(/\+/g, " ");
  if ("iso-8859-1" === o2) return n2.replace(/%[0-9a-f]{2}/gi, unescape);
  try {
    return decodeURIComponent(n2);
  } catch (t4) {
    return n2;
  }
}, delimiter: "&", depth: 5, duplicates: "combine", ignoreQueryPrefix: false, interpretNumericEntities: false, parameterLimit: 1e3, parseArrays: true, plainObjects: false, strictNullHandling: false }, A = function(t3) {
  return t3.replace(/&#(\d+);/g, function(t4, e2) {
    return String.fromCharCode(parseInt(e2, 10));
  });
}, D = function(t3, e2) {
  return t3 && "string" == typeof t3 && e2.comma && t3.indexOf(",") > -1 ? t3.split(",") : t3;
}, k = function(t3, e2, o2, n2) {
  if (!t3) return;
  const r2 = o2.allowDots ? t3.replace(/\.([^.[]+)/g, "[$1]") : t3, i2 = /(\[[^[\]]*])/g;
  let s2 = o2.depth > 0 && /(\[[^[\]]*])/.exec(r2);
  const u2 = s2 ? r2.slice(0, s2.index) : r2, l2 = [];
  if (u2) {
    if (!o2.plainObjects && _.call(Object.prototype, u2) && !o2.allowPrototypes) return;
    l2.push(u2);
  }
  let a2 = 0;
  for (; o2.depth > 0 && null !== (s2 = i2.exec(r2)) && a2 < o2.depth; ) {
    if (a2 += 1, !o2.plainObjects && _.call(Object.prototype, s2[1].slice(1, -1)) && !o2.allowPrototypes) return;
    l2.push(s2[1]);
  }
  return s2 && l2.push("[" + r2.slice(s2.index) + "]"), (function(t4, e3, o3, n3) {
    let r3 = n3 ? e3 : D(e3, o3);
    for (let e4 = t4.length - 1; e4 >= 0; --e4) {
      let n4;
      const i3 = t4[e4];
      if ("[]" === i3 && o3.parseArrays) n4 = c(r3) ? r3 : o3.allowEmptyArrays && ("" === r3 || o3.strictNullHandling && null === r3) ? [] : b([], r3, o3.arrayLimit, o3.plainObjects);
      else {
        n4 = o3.plainObjects ? /* @__PURE__ */ Object.create(null) : {};
        const t5 = "[" === i3.charAt(0) && "]" === i3.charAt(i3.length - 1) ? i3.slice(1, -1) : i3, e5 = o3.decodeDotInKeys ? t5.replace(/%2E/g, ".") : t5, s3 = parseInt(e5, 10);
        o3.parseArrays || "" !== e5 ? !isNaN(s3) && i3 !== e5 && String(s3) === e5 && s3 >= 0 && o3.parseArrays && s3 <= o3.arrayLimit ? (n4 = [], n4[s3] = r3) : "__proto__" !== e5 && (n4[e5] = r3) : n4 = { 0: r3 };
      }
      r3 = n4;
    }
    return r3;
  })(l2, e2, o2, n2);
};
function N(t3, e2) {
  const o2 = /* @__PURE__ */ (function(t4) {
    return S;
  })();
  if ("" === t3 || null == t3) return o2.plainObjects ? /* @__PURE__ */ Object.create(null) : {};
  const n2 = "string" == typeof t3 ? (function(t4, e3) {
    const o3 = { __proto__: null }, n3 = (e3.ignoreQueryPrefix ? t4.replace(/^\?/, "") : t4).split(e3.delimiter, Infinity === e3.parameterLimit ? void 0 : e3.parameterLimit);
    let r3, i3 = -1, s2 = e3.charset;
    if (e3.charsetSentinel) for (r3 = 0; r3 < n3.length; ++r3) 0 === n3[r3].indexOf("utf8=") && ("utf8=%E2%9C%93" === n3[r3] ? s2 = "utf-8" : "utf8=%26%2310003%3B" === n3[r3] && (s2 = "iso-8859-1"), i3 = r3, r3 = n3.length);
    for (r3 = 0; r3 < n3.length; ++r3) {
      if (r3 === i3) continue;
      const t5 = n3[r3], u2 = t5.indexOf("]="), l2 = -1 === u2 ? t5.indexOf("=") : u2 + 1;
      let c2, a2;
      -1 === l2 ? (c2 = e3.decoder(t5, S.decoder, s2, "key"), a2 = e3.strictNullHandling ? null : "") : (c2 = e3.decoder(t5.slice(0, l2), S.decoder, s2, "key"), a2 = m(D(t5.slice(l2 + 1), e3), function(t6) {
        return e3.decoder(t6, S.decoder, s2, "value");
      })), a2 && e3.interpretNumericEntities && "iso-8859-1" === s2 && (a2 = A(a2)), t5.indexOf("[]=") > -1 && (a2 = I(a2) ? [a2] : a2);
      const f2 = _.call(o3, c2);
      f2 && "combine" === e3.duplicates ? o3[c2] = b(o3[c2], a2, e3.arrayLimit, e3.plainObjects) : f2 && "last" !== e3.duplicates || (o3[c2] = a2);
    }
    return o3;
  })(t3, o2) : t3;
  let r2 = o2.plainObjects ? /* @__PURE__ */ Object.create(null) : {};
  const i2 = Object.keys(n2);
  for (let e3 = 0; e3 < i2.length; ++e3) {
    const s2 = i2[e3], u2 = k(s2, n2[s2], o2, "string" == typeof t3);
    r2 = d(r2, u2, o2);
  }
  return true === o2.allowSparse ? r2 : (function(t4) {
    const e3 = [{ obj: { o: t4 }, prop: "o" }], o3 = [];
    for (let t5 = 0; t5 < e3.length; ++t5) {
      const n3 = e3[t5], r3 = n3.obj[n3.prop], i3 = Object.keys(r3);
      for (let t6 = 0; t6 < i3.length; ++t6) {
        const n4 = i3[t6], s2 = r3[n4];
        "object" == typeof s2 && null !== s2 && -1 === o3.indexOf(s2) && (e3.push({ obj: r3, prop: n4 }), o3.push(s2));
      }
    }
    return (function(t5) {
      for (; t5.length > 1; ) {
        const e4 = t5.pop(), o4 = e4.obj[e4.prop];
        if (s(o4)) {
          const t6 = [];
          for (let e5 = 0; e5 < o4.length; ++e5) void 0 !== o4[e5] && t6.push(o4[e5]);
          e4.obj[e4.prop] = t6;
        }
      }
    })(e3), t4;
  })(r2);
}
class x {
  constructor(t3, e2, o2) {
    var n2, r2;
    this.name = t3, this.definition = e2, this.bindings = null != (n2 = e2.bindings) ? n2 : {}, this.wheres = null != (r2 = e2.wheres) ? r2 : {}, this.config = o2;
  }
  get template() {
    const t3 = `${this.origin}/${this.definition.uri}`.replace(/\/+$/, "");
    return "" === t3 ? "/" : t3;
  }
  get origin() {
    return this.config.absolute ? this.definition.domain ? `${this.config.url.match(/^\w+:\/\//)[0]}${this.definition.domain}${this.config.port ? `:${this.config.port}` : ""}` : this.config.url : "";
  }
  get parameterSegments() {
    var t3, e2;
    return null != (t3 = null == (e2 = this.template.match(/{[^}?]+\??}/g)) ? void 0 : e2.map((t4) => ({ name: t4.replace(/{|\??}/g, ""), required: !/\?}$/.test(t4) }))) ? t3 : [];
  }
  matchesUrl(t3) {
    var e2;
    if (!this.definition.methods.includes("GET")) return false;
    const o2 = this.template.replace(/[.*+$()[\]]/g, "\\$&").replace(/(\/?){([^}?]*)(\??)}/g, (t4, e3, o3, n3) => {
      var r3;
      const i3 = `(?<${o3}>${(null == (r3 = this.wheres[o3]) ? void 0 : r3.replace(/(^\^)|(\$$)/g, "")) || "[^/?]+"})`;
      return n3 ? `(${e3}${i3})?` : `${e3}${i3}`;
    }).replace(/^\w+:\/\//, ""), [n2, r2] = t3.replace(/^\w+:\/\//, "").split("?"), i2 = null != (e2 = new RegExp(`^${o2}/?$`).exec(n2)) ? e2 : new RegExp(`^${o2}/?$`).exec(decodeURI(n2));
    if (i2) {
      for (const t4 in i2.groups) i2.groups[t4] = "string" == typeof i2.groups[t4] ? decodeURIComponent(i2.groups[t4]) : i2.groups[t4];
      return { params: i2.groups, query: N(r2) };
    }
    return false;
  }
  compile(t3) {
    return this.parameterSegments.length ? this.template.replace(/{([^}?]+)(\??)}/g, (e2, o2, n2) => {
      var r2, i2;
      if (!n2 && [null, void 0].includes(t3[o2])) throw new Error(`Ziggy error: '${o2}' parameter is required for route '${this.name}'.`);
      if (this.wheres[o2] && !new RegExp(`^${n2 ? `(${this.wheres[o2]})?` : this.wheres[o2]}$`).test(null != (i2 = t3[o2]) ? i2 : "")) throw new Error(`Ziggy error: '${o2}' parameter '${t3[o2]}' does not match required format '${this.wheres[o2]}' for route '${this.name}'.`);
      return encodeURI(null != (r2 = t3[o2]) ? r2 : "").replace(/%7C/g, "|").replace(/%25/g, "%").replace(/\$/g, "%24");
    }).replace(this.config.absolute ? /(\.[^/]+?)(\/\/)/ : /(^)(\/\/)/, "$1/").replace(/\/+$/, "") : this.template;
  }
}
class C extends String {
  constructor(e2, o2, n2 = true, r2) {
    if (super(), this.t = null != r2 ? r2 : "undefined" != typeof Ziggy ? Ziggy : null == globalThis ? void 0 : globalThis.Ziggy, !this.t && "undefined" != typeof document && document.getElementById("ziggy-routes-json") && (globalThis.Ziggy = JSON.parse(document.getElementById("ziggy-routes-json").textContent), this.t = globalThis.Ziggy), this.t = t$1({}, this.t, { absolute: n2 }), e2) {
      if (!this.t.routes[e2]) throw new Error(`Ziggy error: route '${e2}' is not in the route list.`);
      this.i = new x(e2, this.t.routes[e2], this.t), this.u = this.l(o2);
    }
  }
  toString() {
    const e2 = Object.keys(this.u).filter((t3) => !this.i.parameterSegments.some(({ name: e3 }) => e3 === t3)).filter((t3) => "_query" !== t3).reduce((e3, o2) => t$1({}, e3, { [o2]: this.u[o2] }), {});
    return this.i.compile(this.u) + (function(t3, e3) {
      let o2 = t3;
      const i2 = (function(t4) {
        if (!t4) return O;
        if (void 0 !== t4.allowEmptyArrays && "boolean" != typeof t4.allowEmptyArrays) throw new TypeError("`allowEmptyArrays` option can only be `true` or `false`, when provided");
        if (void 0 !== t4.encodeDotInKeys && "boolean" != typeof t4.encodeDotInKeys) throw new TypeError("`encodeDotInKeys` option can only be `true` or `false`, when provided");
        if (null != t4.encoder && "function" != typeof t4.encoder) throw new TypeError("Encoder has to be a function.");
        const e4 = t4.charset || O.charset;
        if (void 0 !== t4.charset && "utf-8" !== t4.charset && "iso-8859-1" !== t4.charset) throw new TypeError("The charset option must be either utf-8, iso-8859-1, or undefined");
        let o3 = r;
        if (void 0 !== t4.format) {
          if (!g.call(n, t4.format)) throw new TypeError("Unknown format option provided.");
          o3 = t4.format;
        }
        const i3 = n[o3];
        let s3, u3 = O.filter;
        if (("function" == typeof t4.filter || v(t4.filter)) && (u3 = t4.filter), s3 = t4.arrayFormat in w ? t4.arrayFormat : "indices" in t4 ? t4.indices ? "indices" : "repeat" : O.arrayFormat, "commaRoundTrip" in t4 && "boolean" != typeof t4.commaRoundTrip) throw new TypeError("`commaRoundTrip` must be a boolean, or absent");
        return { addQueryPrefix: "boolean" == typeof t4.addQueryPrefix ? t4.addQueryPrefix : O.addQueryPrefix, allowDots: void 0 === t4.allowDots ? true === t4.encodeDotInKeys || O.allowDots : !!t4.allowDots, allowEmptyArrays: "boolean" == typeof t4.allowEmptyArrays ? !!t4.allowEmptyArrays : O.allowEmptyArrays, arrayFormat: s3, charset: e4, charsetSentinel: "boolean" == typeof t4.charsetSentinel ? t4.charsetSentinel : O.charsetSentinel, commaRoundTrip: t4.commaRoundTrip, delimiter: void 0 === t4.delimiter ? O.delimiter : t4.delimiter, encode: "boolean" == typeof t4.encode ? t4.encode : O.encode, encodeDotInKeys: "boolean" == typeof t4.encodeDotInKeys ? t4.encodeDotInKeys : O.encodeDotInKeys, encoder: "function" == typeof t4.encoder ? t4.encoder : O.encoder, encodeValuesOnly: "boolean" == typeof t4.encodeValuesOnly ? t4.encodeValuesOnly : O.encodeValuesOnly, filter: u3, format: o3, formatter: i3, serializeDate: "function" == typeof t4.serializeDate ? t4.serializeDate : O.serializeDate, skipNulls: "boolean" == typeof t4.skipNulls ? t4.skipNulls : O.skipNulls, sort: "function" == typeof t4.sort ? t4.sort : null, strictNullHandling: "boolean" == typeof t4.strictNullHandling ? t4.strictNullHandling : O.strictNullHandling };
      })(e3);
      let s2, u2;
      "function" == typeof i2.filter ? (u2 = i2.filter, o2 = u2("", o2)) : v(i2.filter) && (u2 = i2.filter, s2 = u2);
      const l2 = [];
      if ("object" != typeof o2 || null === o2) return "";
      const c2 = w[i2.arrayFormat], a2 = "comma" === c2 && i2.commaRoundTrip;
      s2 || (s2 = Object.keys(o2)), i2.sort && s2.sort(i2.sort);
      const f2 = /* @__PURE__ */ new WeakMap();
      for (let t4 = 0; t4 < s2.length; ++t4) {
        const e4 = s2[t4];
        i2.skipNulls && null === o2[e4] || $(l2, R(o2[e4], e4, c2, a2, i2.allowEmptyArrays, i2.strictNullHandling, i2.skipNulls, i2.encodeDotInKeys, i2.encode ? i2.encoder : null, i2.filter, i2.sort, i2.allowDots, i2.serializeDate, i2.format, i2.formatter, i2.encodeValuesOnly, i2.charset, f2));
      }
      const p2 = l2.join(i2.delimiter);
      let y2 = true === i2.addQueryPrefix ? "?" : "";
      return i2.charsetSentinel && (y2 += "iso-8859-1" === i2.charset ? "utf8=%26%2310003%3B&" : "utf8=%E2%9C%93&"), p2.length > 0 ? y2 + p2 : "";
    })(t$1({}, e2, this.u._query), { addQueryPrefix: true, arrayFormat: "indices", encodeValuesOnly: true, skipNulls: true, encoder: (t3, e3) => "boolean" == typeof t3 ? Number(t3) : e3(t3) });
  }
  p(e2) {
    e2 ? this.t.absolute && e2.startsWith("/") && (e2 = this.h().host + e2) : e2 = this.m();
    let o2 = {};
    const [n2, r2] = Object.entries(this.t.routes).find(([t3, n3]) => o2 = new x(t3, n3, this.t).matchesUrl(e2)) || [void 0, void 0];
    return t$1({ name: n2 }, o2, { route: r2 });
  }
  m() {
    const { host: t3, pathname: e2, search: o2 } = this.h();
    return (this.t.absolute ? t3 + e2 : e2.replace(this.t.url.replace(/^\w*:\/\/[^/]+/, ""), "").replace(/^\/+/, "/")) + o2;
  }
  current(e2, o2) {
    const { name: n2, params: r2, query: i2, route: s2 } = this.p();
    if (!e2) return n2;
    const u2 = new RegExp(`^${e2.replace(/\./g, "\\.").replace(/\*/g, ".*")}$`).test(n2);
    if ([null, void 0].includes(o2) || !u2) return u2;
    const l2 = new x(n2, s2, this.t);
    o2 = this.l(o2, l2);
    const c2 = t$1({}, r2, i2);
    if (Object.values(o2).every((t3) => !t3) && !Object.values(c2).some((t3) => void 0 !== t3)) return true;
    const a2 = (t3, e3) => Object.entries(t3).every(([t4, o3]) => Array.isArray(o3) && Array.isArray(e3[t4]) ? o3.every((o4) => e3[t4].includes(o4) || e3[t4].includes(decodeURIComponent(o4))) : "object" == typeof o3 && "object" == typeof e3[t4] && null !== o3 && null !== e3[t4] ? a2(o3, e3[t4]) : e3[t4] == o3 || e3[t4] == decodeURIComponent(o3));
    return a2(o2, c2);
  }
  h() {
    var t3, e2, o2, n2, r2, i2;
    const { host: s2 = "", pathname: u2 = "", search: l2 = "" } = "undefined" != typeof window ? window.location : {};
    return { host: null != (t3 = null == (e2 = this.t.location) ? void 0 : e2.host) ? t3 : s2, pathname: null != (o2 = null == (n2 = this.t.location) ? void 0 : n2.pathname) ? o2 : u2, search: null != (r2 = null == (i2 = this.t.location) ? void 0 : i2.search) ? r2 : l2 };
  }
  get params() {
    const { params: e2, query: o2 } = this.p();
    return t$1({}, e2, o2);
  }
  get routeParams() {
    return this.p().params;
  }
  get queryParams() {
    return this.p().query;
  }
  has(t3) {
    return this.t.routes.hasOwnProperty(t3);
  }
  l(e2 = {}, o2 = this.i) {
    null != e2 || (e2 = {}), e2 = ["string", "number"].includes(typeof e2) ? [e2] : e2;
    const n2 = o2.parameterSegments.filter(({ name: t3 }) => !this.t.defaults[t3]);
    return Array.isArray(e2) ? e2 = e2.reduce((e3, o3, r2) => t$1({}, e3, n2[r2] ? { [n2[r2].name]: o3 } : "object" == typeof o3 ? o3 : { [o3]: "" }), {}) : 1 !== n2.length || e2.hasOwnProperty(n2[0].name) || !e2.hasOwnProperty(Object.values(o2.bindings)[0]) && !e2.hasOwnProperty("id") || (e2 = { [n2[0].name]: e2 }), t$1({}, this.v(o2), this.j(e2, o2));
  }
  v(e2) {
    return e2.parameterSegments.filter(({ name: t3 }) => this.t.defaults[t3]).reduce((e3, { name: o2 }, n2) => t$1({}, e3, { [o2]: this.t.defaults[o2] }), {});
  }
  j(e2, { bindings: o2, parameterSegments: n2 }) {
    return Object.entries(e2).reduce((e3, [r2, i2]) => {
      if (!i2 || "object" != typeof i2 || Array.isArray(i2) || !n2.some(({ name: t3 }) => t3 === r2)) return t$1({}, e3, { [r2]: i2 });
      const s2 = i2.hasOwnProperty(o2[r2]) ? o2[r2] : i2.hasOwnProperty("id") ? "id" : void 0;
      if (void 0 === s2) throw new Error(`Ziggy error: object passed as '${r2}' parameter is missing route model binding key '${o2[r2]}'.`);
      return t$1({}, e3, { [r2]: i2[s2] });
    }, {});
  }
  valueOf() {
    return this.toString();
  }
}
function P(t3, e2, o2, n2) {
  const r2 = new C(t3, e2, o2, n2);
  return t3 ? r2.toString() : r2;
}
const U = { install(t3, e2) {
  const o2 = (t4, o3, n2, r2 = e2) => P(t4, o3, n2, r2);
  parseInt(t3.version) > 2 ? (t3.config.globalProperties.route = o2, t3.provide("route", o2)) : t3.mixin({ methods: { route: o2 } });
} };
const Ziggy$1 = { "url": "https://doomsday-countdown.test", "port": null, "defaults": {}, "routes": { "test-components.index": { "uri": "test-components", "methods": ["GET", "HEAD"] }, "test-components.show": { "uri": "test-components/{component}", "methods": ["GET", "HEAD"], "wheres": { "component": ".*" }, "parameters": ["component"] }, "select-demo.users": { "uri": "api/select-demo/users", "methods": ["GET", "HEAD"] }, "select-demo.tags": { "uri": "api/select-demo/tags", "methods": ["GET", "HEAD"] }, "select-demo.users.paginated": { "uri": "api/select-demo/users/paginated", "methods": ["GET", "HEAD"] }, "file-upload.upload": { "uri": "api/file-upload/upload", "methods": ["POST"] }, "sitemap": { "uri": "sitemap.xml", "methods": ["GET", "HEAD"] }, "robots": { "uri": "robots.txt", "methods": ["GET", "HEAD"] }, "home": { "uri": "/", "methods": ["GET", "HEAD"] }, "about": { "uri": "about", "methods": ["GET", "HEAD"] }, "privacy": { "uri": "privacy", "methods": ["GET", "HEAD"] }, "cookie-policy": { "uri": "cookie-policy", "methods": ["GET", "HEAD"] }, "countdowns.show": { "uri": "countdowns/{slug}", "methods": ["GET", "HEAD"], "parameters": ["slug"] }, "countdowns.data.overview": { "uri": "countdowns/{slug}/overview-data", "methods": ["GET", "HEAD"], "parameters": ["slug"] }, "countdowns.data.forecasts": { "uri": "countdowns/{slug}/forecasts-data", "methods": ["GET", "HEAD"], "parameters": ["slug"] }, "countdowns.data.statistics": { "uri": "countdowns/{slug}/statistics-data", "methods": ["GET", "HEAD"], "parameters": ["slug"] }, "countdowns.data.news": { "uri": "countdowns/{slug}/news-data", "methods": ["GET", "HEAD"], "parameters": ["slug"] }, "countdowns.data.initiatives": { "uri": "countdowns/{slug}/initiatives-data", "methods": ["GET", "HEAD"], "parameters": ["slug"] }, "agent.demo": { "uri": "agent/demo", "methods": ["POST"] }, "login": { "uri": "login", "methods": ["GET", "HEAD"] }, "login.store": { "uri": "login", "methods": ["POST"] }, "logout": { "uri": "logout", "methods": ["POST"] }, "backoffice.index": { "uri": "backoffice", "methods": ["GET", "HEAD"] }, "backoffice.countdowns.index": { "uri": "backoffice/countdowns", "methods": ["GET", "HEAD"] }, "backoffice.countdowns.create": { "uri": "backoffice/countdowns/create", "methods": ["GET", "HEAD"] }, "backoffice.countdowns.store": { "uri": "backoffice/countdowns", "methods": ["POST"] }, "backoffice.countdowns.edit": { "uri": "backoffice/countdowns/{countdown}/edit", "methods": ["GET", "HEAD"], "parameters": ["countdown"], "bindings": { "countdown": "id" } }, "backoffice.countdowns.update": { "uri": "backoffice/countdowns/{countdown}", "methods": ["PUT", "PATCH"], "parameters": ["countdown"], "bindings": { "countdown": "id" } }, "backoffice.countdowns.destroy": { "uri": "backoffice/countdowns/{countdown}", "methods": ["DELETE"], "parameters": ["countdown"], "bindings": { "countdown": "id" } }, "backoffice.countdowns.projections.create": { "uri": "backoffice/countdowns/{countdown}/projections/create", "methods": ["GET", "HEAD"], "parameters": ["countdown"], "bindings": { "countdown": "id" } }, "backoffice.countdowns.projections.edit": { "uri": "backoffice/countdowns/{countdown}/projections/{projection}/edit", "methods": ["GET", "HEAD"], "parameters": ["countdown", "projection"], "bindings": { "countdown": "id", "projection": "id" } }, "backoffice.countdowns.projections.store": { "uri": "backoffice/countdowns/{countdown}/projections", "methods": ["POST"], "parameters": ["countdown"], "bindings": { "countdown": "id" } }, "backoffice.countdowns.projections.update": { "uri": "backoffice/countdowns/{countdown}/projections/{projection}", "methods": ["PUT"], "parameters": ["countdown", "projection"], "bindings": { "countdown": "id", "projection": "id" } }, "backoffice.countdowns.projections.destroy": { "uri": "backoffice/countdowns/{countdown}/projections/{projection}", "methods": ["DELETE"], "parameters": ["countdown", "projection"], "bindings": { "countdown": "id", "projection": "id" } }, "backoffice.countdowns.visualizations.create": { "uri": "backoffice/countdowns/{countdown}/visualizations/create", "methods": ["GET", "HEAD"], "parameters": ["countdown"], "bindings": { "countdown": "id" } }, "backoffice.countdowns.visualizations.edit": { "uri": "backoffice/countdowns/{countdown}/visualizations/{visualization}/edit", "methods": ["GET", "HEAD"], "parameters": ["countdown", "visualization"], "bindings": { "countdown": "id", "visualization": "id" } }, "backoffice.countdowns.visualizations.store": { "uri": "backoffice/countdowns/{countdown}/visualizations", "methods": ["POST"], "parameters": ["countdown"], "bindings": { "countdown": "id" } }, "backoffice.countdowns.visualizations.update": { "uri": "backoffice/countdowns/{countdown}/visualizations/{visualization}", "methods": ["PUT"], "parameters": ["countdown", "visualization"], "bindings": { "countdown": "id", "visualization": "id" } }, "backoffice.countdowns.visualizations.destroy": { "uri": "backoffice/countdowns/{countdown}/visualizations/{visualization}", "methods": ["DELETE"], "parameters": ["countdown", "visualization"], "bindings": { "countdown": "id", "visualization": "id" } }, "backoffice.countdowns.projections.visualizations.create": { "uri": "backoffice/countdowns/{countdown}/projections/{projection}/visualizations/create", "methods": ["GET", "HEAD"], "parameters": ["countdown", "projection"], "bindings": { "countdown": "id", "projection": "id" } }, "backoffice.countdowns.projections.visualizations.edit": { "uri": "backoffice/countdowns/{countdown}/projections/{projection}/visualizations/{visualization}/edit", "methods": ["GET", "HEAD"], "parameters": ["countdown", "projection", "visualization"], "bindings": { "countdown": "id", "projection": "id", "visualization": "id" } }, "backoffice.countdowns.projections.visualizations.store": { "uri": "backoffice/countdowns/{countdown}/projections/{projection}/visualizations", "methods": ["POST"], "parameters": ["countdown", "projection"], "bindings": { "countdown": "id", "projection": "id" } }, "backoffice.countdowns.projections.visualizations.update": { "uri": "backoffice/countdowns/{countdown}/projections/{projection}/visualizations/{visualization}", "methods": ["PUT"], "parameters": ["countdown", "projection", "visualization"], "bindings": { "countdown": "id", "projection": "id", "visualization": "id" } }, "backoffice.countdowns.projections.visualizations.destroy": { "uri": "backoffice/countdowns/{countdown}/projections/{projection}/visualizations/{visualization}", "methods": ["DELETE"], "parameters": ["countdown", "projection", "visualization"], "bindings": { "countdown": "id", "projection": "id", "visualization": "id" } }, "backoffice.countdowns.news.store": { "uri": "backoffice/countdowns/{countdown}/news", "methods": ["POST"], "parameters": ["countdown"], "bindings": { "countdown": "id" } }, "backoffice.countdowns.news.update": { "uri": "backoffice/countdowns/{countdown}/news/{news}", "methods": ["PUT"], "parameters": ["countdown", "news"], "bindings": { "countdown": "id", "news": "id" } }, "backoffice.countdowns.news.destroy": { "uri": "backoffice/countdowns/{countdown}/news/{news}", "methods": ["DELETE"], "parameters": ["countdown", "news"], "bindings": { "countdown": "id", "news": "id" } }, "backoffice.countdowns.initiatives.store": { "uri": "backoffice/countdowns/{countdown}/initiatives", "methods": ["POST"], "parameters": ["countdown"], "bindings": { "countdown": "id" } }, "backoffice.countdowns.initiatives.update": { "uri": "backoffice/countdowns/{countdown}/initiatives/{initiative}", "methods": ["PUT"], "parameters": ["countdown", "initiative"], "bindings": { "countdown": "id", "initiative": "id" } }, "backoffice.countdowns.initiatives.destroy": { "uri": "backoffice/countdowns/{countdown}/initiatives/{initiative}", "methods": ["DELETE"], "parameters": ["countdown", "initiative"], "bindings": { "countdown": "id", "initiative": "id" } }, "backoffice.users.index": { "uri": "backoffice/users", "methods": ["GET", "HEAD"] }, "backoffice.users.store": { "uri": "backoffice/users", "methods": ["POST"] }, "backoffice.users.update": { "uri": "backoffice/users/{user}", "methods": ["PUT"], "parameters": ["user"], "bindings": { "user": "id" } }, "backoffice.users.destroy": { "uri": "backoffice/users/{user}", "methods": ["DELETE"], "parameters": ["user"], "bindings": { "user": "id" } }, "backoffice.openai-keys.index": { "uri": "backoffice/openai-keys", "methods": ["GET", "HEAD"] }, "backoffice.openai-keys.store": { "uri": "backoffice/openai-keys", "methods": ["POST"] }, "backoffice.openai-keys.update": { "uri": "backoffice/openai-keys/{openAiKey}", "methods": ["PUT"], "parameters": ["openAiKey"], "bindings": { "openAiKey": "id" } }, "backoffice.openai-keys.destroy": { "uri": "backoffice/openai-keys/{openAiKey}", "methods": ["DELETE"], "parameters": ["openAiKey"], "bindings": { "openAiKey": "id" } }, "storage.local": { "uri": "storage/{path}", "methods": ["GET", "HEAD"], "wheres": { "path": ".*" }, "parameters": ["path"] }, "storage.local.upload": { "uri": "storage/{path}", "methods": ["PUT"], "wheres": { "path": ".*" }, "parameters": ["path"] } } };
if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
  Object.assign(Ziggy$1.routes, window.Ziggy.routes);
}
const supportedLocales = ["en", "it", "fr", "de", "es", "nl", "sv", "pl"];
const resources = {
  en: {
    translation: {
      home: "Home",
      about: "About",
      overview: "Overview",
      predictions: "Forecasts",
      statistics: "Statistics",
      news: "News",
      initiatives: "Initiatives",
      resources: "Resources",
      analysis: "Analysis",
      latestNews: "Latest news",
      latestNewsEmpty: "No published news is available for the monitored countdowns.",
      newsCarouselLabel: "Latest public-source news",
      newsSlide: "News item",
      previousNews: "Previous news item",
      nextNews: "Next news item",
      openSource: "Open source",
      viewCountdown: "View countdown",
      publicSignalActivity: "Public Signal Activity",
      publicSignalActivitySummary: "Publication volume across the latest 12 UTC weeks.",
      publicSignalActivityChart: "Weekly publication activity from monitored public sources",
      publishedItems: "published items",
      uniqueSources: "Unique sources",
      latestPublication: "Latest publication",
      topMonitoredCountdown: "Top monitored countdown",
      noSignalActivity: "No publications were recorded in this window.",
      publicSignalActivityDisclaimer: "Published items from monitored public sources. Volume measures source activity, not event probability.",
      projectionModel: "Projection model",
      estimatedTarget: "Estimated target",
      readMore: "Read more",
      viewDetails: "View details",
      viewAllNews: "View all news",
      keyIndicators: "Key indicators",
      summary: "Summary",
      methodology: "Learn more about our methodology",
      loadingSection: "Loading section",
      supportUs: "Support us",
      supportOnPatreon: "Support on Patreon",
      supportProjectDescription: "Help keep the project independent, maintained, and open to everyone.",
      opensInNewTab: "opens in a new tab"
    }
  },
  it: {
    translation: {
      home: "Home",
      about: "About",
      overview: "Sintesi",
      predictions: "Previsioni",
      statistics: "Statistiche",
      news: "Notizie",
      initiatives: "Iniziative",
      resources: "Risorse",
      analysis: "Analisi",
      latestNews: "Ultime notizie",
      latestNewsEmpty: "Non sono disponibili notizie pubblicate per i countdown monitorati.",
      newsCarouselLabel: "Ultime notizie da fonti pubbliche",
      newsSlide: "Notizia",
      previousNews: "Notizia precedente",
      nextNews: "Notizia successiva",
      openSource: "Apri la fonte",
      viewCountdown: "Vedi countdown",
      publicSignalActivity: "Attività dei segnali pubblici",
      publicSignalActivitySummary: "Volume delle pubblicazioni nelle ultime 12 settimane UTC.",
      publicSignalActivityChart: "Attività settimanale delle pubblicazioni da fonti pubbliche monitorate",
      publishedItems: "elementi pubblicati",
      uniqueSources: "Fonti uniche",
      latestPublication: "Ultima pubblicazione",
      topMonitoredCountdown: "Countdown più monitorato",
      noSignalActivity: "Nessuna pubblicazione registrata in questa finestra.",
      publicSignalActivityDisclaimer: "Elementi pubblicati da fonti pubbliche monitorate. Il volume misura l’attività delle fonti, non la probabilità di un evento.",
      projectionModel: "Modello di proiezione",
      estimatedTarget: "Obiettivo stimato",
      readMore: "Leggi di più",
      viewDetails: "Vedi dettagli",
      viewAllNews: "Vedi tutte le notizie",
      keyIndicators: "Indicatori chiave",
      summary: "Sintesi",
      methodology: "Scopri la metodologia",
      loadingSection: "Caricamento sezione",
      supportUs: "Sostienici",
      supportOnPatreon: "Sostieni su Patreon",
      supportProjectDescription: "Aiutaci a mantenere il progetto indipendente, aggiornato e aperto a tutti.",
      opensInNewTab: "si apre in una nuova scheda"
    }
  },
  fr: { translation: { home: "Home", about: "About", supportUs: "Nous soutenir", supportOnPatreon: "Soutenir sur Patreon", supportProjectDescription: "Aidez-nous à garder le projet indépendant, maintenu et accessible à tous.", opensInNewTab: "s’ouvre dans un nouvel onglet" } },
  de: { translation: { home: "Home", about: "About", supportUs: "Unterstützen", supportOnPatreon: "Auf Patreon unterstützen", supportProjectDescription: "Hilf uns, das Projekt unabhängig, gepflegt und für alle offen zu halten.", opensInNewTab: "öffnet in einem neuen Tab" } },
  es: { translation: { home: "Home", about: "About", supportUs: "Apóyanos", supportOnPatreon: "Apoyar en Patreon", supportProjectDescription: "Ayúdanos a mantener el proyecto independiente, actualizado y abierto para todos.", opensInNewTab: "se abre en una pestaña nueva" } },
  nl: { translation: { home: "Home", about: "About", supportUs: "Steun ons", supportOnPatreon: "Steun via Patreon", supportProjectDescription: "Help ons het project onafhankelijk, onderhouden en voor iedereen toegankelijk te houden.", opensInNewTab: "opent in een nieuw tabblad" } },
  sv: { translation: { home: "Home", about: "About", supportUs: "Stöd oss", supportOnPatreon: "Stöd på Patreon", supportProjectDescription: "Hjälp oss att hålla projektet oberoende, uppdaterat och öppet för alla.", opensInNewTab: "öppnas i en ny flik" } },
  pl: { translation: { home: "Home", about: "About", supportUs: "Wesprzyj nas", supportOnPatreon: "Wesprzyj na Patreon", supportProjectDescription: "Pomóż nam utrzymać projekt niezależny, aktualny i dostępny dla wszystkich.", opensInNewTab: "otwiera się w nowej karcie" } }
};
const clientLanguage = ref("en");
let clientInitialization = null;
let serverI18nResolver;
const i18n = i18next.createInstance();
function isSupportedLocale(locale) {
  return typeof locale === "string" && supportedLocales.includes(locale);
}
function assertSupportedLocale(locale) {
  if (!isSupportedLocale(locale)) {
    throw new Error(`Unsupported locale received from the page contract: ${String(locale)}`);
  }
  return locale;
}
function initializationOptions(locale) {
  return {
    lng: locale,
    fallbackLng: "en",
    showSupportNotice: false,
    supportedLngs: [...supportedLocales],
    resources,
    interpolation: {
      escapeValue: false
    }
  };
}
function activeI18n() {
  return (serverI18nResolver == null ? void 0 : serverI18nResolver()) ?? i18n;
}
const currentLanguage = computed({
  get() {
    const instance = activeI18n();
    const locale = instance.resolvedLanguage ?? instance.language;
    return isSupportedLocale(locale) ? locale : clientLanguage.value;
  },
  set(locale) {
    clientLanguage.value = assertSupportedLocale(locale);
  }
});
async function createI18nInstance(locale) {
  const instance = i18next.createInstance();
  await instance.init(initializationOptions(assertSupportedLocale(locale)));
  return instance;
}
function registerServerI18nResolver(resolver) {
  serverI18nResolver = resolver;
}
async function initializeClientI18n(locale) {
  const resolvedLocale = assertSupportedLocale(locale);
  if (!i18n.isInitialized) {
    clientInitialization ?? (clientInitialization = i18n.init(initializationOptions(resolvedLocale)).then(() => i18n));
    await clientInitialization;
  } else if (i18n.resolvedLanguage !== resolvedLocale) {
    await i18n.changeLanguage(resolvedLocale);
  }
  clientLanguage.value = resolvedLocale;
  if (typeof document !== "undefined") {
    document.documentElement.lang = resolvedLocale;
  }
  return i18n;
}
async function setLanguage(language) {
  await initializeClientI18n(language);
}
function t2(key) {
  const instance = activeI18n();
  return instance.isInitialized ? String(instance.t(key)) : key;
}
const requestI18n = new AsyncLocalStorage();
registerServerI18nResolver(() => requestI18n.getStore());
async function withServerI18n(locale, callback) {
  const instance = await createI18nInstance(locale);
  return requestI18n.run(instance, callback);
}
const appName = "Doomsday Clock";
const resolvePage = (name) => resolvePageComponent(`./Pages/${name}.vue`, /* @__PURE__ */ Object.assign({ "./Pages/Backoffice/Countdowns/Create.vue": () => import("./assets/Create-DjQ5GYy4.js"), "./Pages/Backoffice/Countdowns/Edit.vue": () => import("./assets/Edit-D6eiZHSj.js"), "./Pages/Backoffice/Countdowns/Index.vue": () => import("./assets/Index-ghR1-SGt.js"), "./Pages/Backoffice/Countdowns/Projections/Create.vue": () => import("./assets/Create-BMDM_fgf.js"), "./Pages/Backoffice/Countdowns/Projections/Edit.vue": () => import("./assets/Edit-D_CGC_d1.js"), "./Pages/Backoffice/Countdowns/Visualizations/Create.vue": () => import("./assets/Create-BRAawJkB.js"), "./Pages/Backoffice/Countdowns/Visualizations/Edit.vue": () => import("./assets/Edit-D6yrAiJB.js"), "./Pages/Backoffice/Index.vue": () => import("./assets/Index-BVJt1OiP.js"), "./Pages/Backoffice/OpenAiKeys/Index.vue": () => import("./assets/Index-Uz7F4dsV.js"), "./Pages/Backoffice/Users/Index.vue": () => import("./assets/Index-D5qMHbgr.js"), "./Pages/Doomsday/About.vue": () => import("./assets/About-BK44IgyS.js"), "./Pages/Doomsday/Home.vue": () => import("./assets/Home-BND_lgn7.js"), "./Pages/Doomsday/LegalPolicy.vue": () => import("./assets/LegalPolicy-CUa-41c5.js"), "./Pages/Home.vue": () => import("./assets/Home-BR5CTOL0.js"), "./Pages/Login.vue": () => import("./assets/Login-Q6hS_Dbv.js") }));
function ziggyConfigForPage(page) {
  const location = new URL(page.url, Ziggy$1.url);
  return {
    ...Ziggy$1,
    url: location.origin,
    port: location.port === "" ? null : location.port,
    location
  };
}
function render(page) {
  return withServerI18n(page.props.locale, () => createInertiaApp({
    page,
    render: renderToString,
    title: (title) => `${title} - ${appName}`,
    resolve: resolvePage,
    setup({ App, props, plugin }) {
      const app = createSSRApp({
        render: () => h$1(_sfc_main, { defaultTheme: "doomsday" }, () => h$1(App, props))
      });
      return app.use(U, ziggyConfigForPage(page)).use(plugin);
    }
  }));
}
export {
  Button as B,
  DangerModal as D,
  Modal as M,
  NumberInput as N,
  SkeletonLoader as S,
  ToastNotification as T,
  _sfc_main$I as _,
  _sfc_main$O as a,
  _sfc_main$J as b,
  _sfc_main$e as c,
  _sfc_main$j as d,
  render as default,
  _sfc_main$F as e,
  _sfc_main$f as f,
  _sfc_main$u as g,
  _sfc_main$E as h,
  _sfc_main$t as i,
  _sfc_main$b as j,
  _sfc_main$i as k,
  _sfc_main$V as l,
  _sfc_main$7 as m,
  _sfc_main$W as n,
  _sfc_main$o as o,
  _sfc_main$m as p,
  _sfc_main$x as q,
  _sfc_main$C as r,
  _sfc_main$k as s,
  t2 as t,
  currentLanguage as u,
  setLanguage as v,
  _sfc_main$Z as w,
  _sfc_main$$ as x,
  _export_sfc as y
};
