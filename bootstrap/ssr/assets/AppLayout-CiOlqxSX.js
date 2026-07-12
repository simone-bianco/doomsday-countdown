import { ref, defineComponent, unref, useSSRContext, mergeProps, withCtx, createTextVNode, toDisplayString, computed, createVNode } from "vue";
import { ssrRenderTeleport, ssrInterpolate, ssrRenderAttr, ssrRenderList, ssrRenderComponent, ssrRenderAttrs, ssrRenderClass, ssrRenderSlot } from "vue/server-renderer";
import { usePage, Link, router } from "@inertiajs/vue3";
import { y as _export_sfc, B as Button, u as currentLanguage, v as setLanguage, T as ToastNotification, h as _sfc_main$3 } from "../ssr.js";
const ddOverlayState = {
  visible: ref(false),
  html: ref(""),
  dumps: ref([])
};
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "DebugOverlay",
  __ssrInlineRender: true,
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      ssrRenderTeleport(_push, (_push2) => {
        if (unref(ddOverlayState).visible.value) {
          _push2(`<div class="debug-overlay" data-v-6fe5be8d><div class="debug-overlay__panel" data-v-6fe5be8d><div class="debug-overlay__header" data-v-6fe5be8d><div class="debug-overlay__title-group" data-v-6fe5be8d><span class="debug-overlay__badge" data-v-6fe5be8d>DEBUG</span><span class="debug-overlay__title" data-v-6fe5be8d>${ssrInterpolate(unref(ddOverlayState).html.value ? "dd() Output" : "dump() Output")}</span>`);
          if (unref(ddOverlayState).dumps.value.length > 0) {
            _push2(`<span class="debug-overlay__count" data-v-6fe5be8d>${ssrInterpolate(unref(ddOverlayState).dumps.value.length)} dump(s) </span>`);
          } else {
            _push2(`<!---->`);
          }
          _push2(`</div><button class="debug-overlay__close" title="Close (Esc)" data-v-6fe5be8d> × </button></div><div class="debug-overlay__content" data-v-6fe5be8d>`);
          if (unref(ddOverlayState).html.value) {
            _push2(`<iframe${ssrRenderAttr("srcdoc", unref(ddOverlayState).html.value)} class="debug-overlay__iframe" sandbox="allow-same-origin" data-v-6fe5be8d></iframe>`);
          } else {
            _push2(`<!---->`);
          }
          _push2(`<!--[-->`);
          ssrRenderList(unref(ddOverlayState).dumps.value, (dump, i) => {
            _push2(`<div class="debug-overlay__dump" data-v-6fe5be8d>${dump ?? ""}</div>`);
          });
          _push2(`<!--]--></div></div></div>`);
        } else {
          _push2(`<!---->`);
        }
      }, "body", false, _parent);
    };
  }
});
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("packages/simone-bianco/vue-form-core/src/components/DebugOverlay.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const DebugOverlay = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["__scopeId", "data-v-6fe5be8d"]]);
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "LanguageSwitcher",
  __ssrInlineRender: true,
  setup(__props) {
    async function toggleLanguage() {
      await setLanguage(currentLanguage.value === "it" ? "en" : "it");
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(Button), mergeProps({
        variant: "secondary",
        size: "sm",
        onClick: toggleLanguage
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`${ssrInterpolate(unref(currentLanguage).toUpperCase())}`);
          } else {
            return [
              createTextVNode(toDisplayString(unref(currentLanguage).toUpperCase()), 1)
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/LanguageSwitcher.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "AppLayout",
  __ssrInlineRender: true,
  props: {
    title: {},
    showTitleCard: { type: Boolean, default: true },
    showLanguageSwitcher: { type: Boolean, default: true },
    contentClass: { default: "mx-auto max-w-6xl px-4 py-8" },
    headerInnerClass: { default: "mx-auto flex max-w-6xl items-center justify-between px-4 py-4" },
    appNameOverride: { default: void 0 },
    logoHref: { default: "/" }
  },
  setup(__props) {
    const props = __props;
    const page = usePage();
    const user = computed(() => {
      var _a;
      return ((_a = page.props.auth) == null ? void 0 : _a.user) ?? null;
    });
    const appName = computed(() => {
      var _a;
      return props.appNameOverride ?? ((_a = page.props.app) == null ? void 0 : _a.name) ?? "Doomsday Clock";
    });
    const backofficePath = computed(() => {
      var _a;
      return ((_a = page.props.app) == null ? void 0 : _a.backoffice_path) ?? "";
    });
    function logout() {
      router.post("/logout");
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "min-h-screen bg-ui-background text-ui-foreground" }, _attrs))}>`);
      _push(ssrRenderComponent(unref(ToastNotification), null, null, _parent));
      _push(ssrRenderComponent(unref(DebugOverlay), null, null, _parent));
      _push(`<header class="border-b border-ui-border bg-ui-card/80 backdrop-blur"><div class="${ssrRenderClass(__props.headerInnerClass)}">`);
      _push(ssrRenderComponent(unref(Link), {
        href: __props.logoHref,
        class: "inline-flex items-center"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<img src="/images/doomsday/doomsday_logo_transparent.png"${ssrRenderAttr("alt", appName.value)} class="h-8 w-auto object-contain"${_scopeId}>`);
          } else {
            return [
              createVNode("img", {
                src: "/images/doomsday/doomsday_logo_transparent.png",
                alt: appName.value,
                class: "h-8 w-auto object-contain"
              }, null, 8, ["alt"])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<div class="flex items-center gap-3">`);
      if (__props.showLanguageSwitcher) {
        _push(ssrRenderComponent(_sfc_main$1, null, null, _parent));
      } else {
        _push(`<!---->`);
      }
      if (user.value) {
        _push(ssrRenderComponent(unref(Link), {
          href: backofficePath.value,
          class: "text-sm text-ui-muted-foreground hover:text-ui-foreground"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`${ssrInterpolate(user.value.name)}`);
            } else {
              return [
                createTextVNode(toDisplayString(user.value.name), 1)
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(ssrRenderComponent(unref(Link), {
          href: "/login",
          class: "text-sm text-ui-muted-foreground hover:text-ui-foreground"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`Login`);
            } else {
              return [
                createTextVNode("Login")
              ];
            }
          }),
          _: 1
        }, _parent));
      }
      if (user.value) {
        _push(ssrRenderComponent(unref(Button), {
          variant: "ghost",
          size: "sm",
          onClick: logout
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`Logout`);
            } else {
              return [
                createTextVNode("Logout")
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div></header><main class="${ssrRenderClass(__props.contentClass)}">`);
      if (__props.showTitleCard) {
        _push(ssrRenderComponent(unref(_sfc_main$3), { ui: { root: "mb-6 bg-ui-card/60", body: "p-6" } }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<h1 class="text-2xl font-bold"${_scopeId}>${ssrInterpolate(__props.title)}</h1>`);
            } else {
              return [
                createVNode("h1", { class: "text-2xl font-bold" }, toDisplayString(__props.title), 1)
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<!---->`);
      }
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</main></div>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/AppLayout.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
