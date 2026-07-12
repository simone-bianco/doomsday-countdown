import { defineComponent, computed, unref, mergeProps, withCtx, createVNode, useSSRContext, useModel, renderSlot, mergeModels } from "vue";
import { ssrRenderComponent, ssrRenderSlot } from "vue/server-renderer";
import { o as _sfc_main$2, p as _sfc_main$4 } from "../ssr.js";
import { _ as _sfc_main$3 } from "./AppLayout-CiOlqxSX.js";
import { Link } from "@inertiajs/vue3";
import { Gauge, Target, Users, KeyRound, ExternalLink } from "lucide-vue-next";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "BackofficeSidebar",
  __ssrInlineRender: true,
  props: {
    activeSection: {},
    backofficePath: {},
    counts: {}
  },
  setup(__props) {
    const props = __props;
    const normalizedBackofficePath = computed(() => props.backofficePath.replace(/\/+$/g, ""));
    const dashboardPath = computed(() => normalizedBackofficePath.value);
    const usersPath = computed(() => `${normalizedBackofficePath.value}/users`);
    const openAiKeysPath = computed(() => `${normalizedBackofficePath.value}/openai-keys`);
    const countdownsPath = computed(() => `${normalizedBackofficePath.value}/countdowns`);
    const groups = computed(() => [
      {
        label: "Operations",
        items: [
          {
            label: "Dashboard",
            href: dashboardPath.value,
            icon: Gauge,
            active: props.activeSection === "dashboard"
          }
        ]
      },
      {
        label: "Doomsday CRUD",
        items: [
          {
            label: "Countdowns",
            href: countdownsPath.value,
            icon: Target,
            count: props.counts.countdowns,
            active: props.activeSection === "countdowns"
          }
        ]
      },
      {
        label: "Administration",
        items: [
          {
            label: "Users",
            href: usersPath.value,
            icon: Users,
            count: props.counts.users,
            active: props.activeSection === "users"
          },
          {
            label: "OpenAI keys",
            href: openAiKeysPath.value,
            icon: KeyRound,
            count: props.counts.apiKeys,
            active: props.activeSection === "openai-keys"
          }
        ]
      }
    ]);
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(_sfc_main$2), mergeProps({
        groups: groups.value,
        "site-name": "Doomsday Ops",
        position: "relative",
        ui: {
          root: "h-[calc(100vh-73px)] min-h-0 w-[17rem] shrink-0 overflow-hidden rounded-none border-y-0 border-l-0 border-r border-ui-border/70 bg-ui-card/95 shadow-none",
          header: "h-16 px-4",
          nav: "min-h-0 flex-none overflow-y-hidden overflow-x-hidden px-3 py-4 space-y-4",
          itemIcon: "mr-2 h-4 w-4"
        }
      }, _attrs), {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<span class="hidden" aria-hidden="true"${_scopeId}></span>`);
          } else {
            return [
              createVNode("span", {
                class: "hidden",
                "aria-hidden": "true"
              })
            ];
          }
        }),
        top: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(Link), {
              href: "/",
              target: "_blank",
              rel: "noopener noreferrer",
              class: "group flex items-center justify-between rounded-xl border border-ui-primary/25 bg-ui-primary/10 px-3 py-2 text-sm font-semibold text-ui-primary transition hover:border-ui-primary/50 hover:bg-ui-primary/15 hover:text-ui-primary-hover"
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<span${_scopeId2}>Visit site</span>`);
                  _push3(ssrRenderComponent(unref(ExternalLink), { class: "h-4 w-4 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode("span", null, "Visit site"),
                    createVNode(unref(ExternalLink), { class: "h-4 w-4 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" })
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(Link), {
                href: "/",
                target: "_blank",
                rel: "noopener noreferrer",
                class: "group flex items-center justify-between rounded-xl border border-ui-primary/25 bg-ui-primary/10 px-3 py-2 text-sm font-semibold text-ui-primary transition hover:border-ui-primary/50 hover:bg-ui-primary/15 hover:text-ui-primary-hover"
              }, {
                default: withCtx(() => [
                  createVNode("span", null, "Visit site"),
                  createVNode(unref(ExternalLink), { class: "h-4 w-4 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" })
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
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Navigation/BackofficeSidebar.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "BackofficeShell",
  __ssrInlineRender: true,
  props: /* @__PURE__ */ mergeModels({
    title: {},
    subtitle: {},
    backofficePath: {},
    counts: {}
  }, {
    "activeSection": { required: true },
    "activeSectionModifiers": {}
  }),
  emits: ["update:activeSection"],
  setup(__props) {
    const activeSection = useModel(__props, "activeSection");
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$3, mergeProps({
        title: __props.title,
        "show-title-card": false,
        "show-language-switcher": false,
        "app-name-override": "Doomsday Clock",
        "logo-href": __props.backofficePath,
        "content-class": "w-full px-0 py-0",
        "header-inner-class": "flex w-full items-center justify-between px-4 py-4 sm:px-6"
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="grid h-[calc(100vh-73px)] min-h-0 gap-0 overflow-hidden lg:grid-cols-[17rem_minmax(0,1fr)]"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$1, {
              "active-section": activeSection.value,
              "backoffice-path": __props.backofficePath,
              counts: __props.counts
            }, null, _parent2, _scopeId));
            _push2(`<section class="min-w-0 space-y-6 overflow-auto px-4 py-6 sm:px-6 lg:px-8 xl:px-10"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$4), {
              title: __props.title,
              subtitle: __props.subtitle
            }, {
              actions: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  ssrRenderSlot(_ctx.$slots, "actions", {}, null, _push3, _parent3, _scopeId2);
                } else {
                  return [
                    renderSlot(_ctx.$slots, "actions")
                  ];
                }
              }),
              _: 3
            }, _parent2, _scopeId));
            ssrRenderSlot(_ctx.$slots, "default", {}, null, _push2, _parent2, _scopeId);
            _push2(`</section></div>`);
          } else {
            return [
              createVNode("div", { class: "grid h-[calc(100vh-73px)] min-h-0 gap-0 overflow-hidden lg:grid-cols-[17rem_minmax(0,1fr)]" }, [
                createVNode(_sfc_main$1, {
                  "active-section": activeSection.value,
                  "backoffice-path": __props.backofficePath,
                  counts: __props.counts
                }, null, 8, ["active-section", "backoffice-path", "counts"]),
                createVNode("section", { class: "min-w-0 space-y-6 overflow-auto px-4 py-6 sm:px-6 lg:px-8 xl:px-10" }, [
                  createVNode(unref(_sfc_main$4), {
                    title: __props.title,
                    subtitle: __props.subtitle
                  }, {
                    actions: withCtx(() => [
                      renderSlot(_ctx.$slots, "actions")
                    ]),
                    _: 3
                  }, 8, ["title", "subtitle"]),
                  renderSlot(_ctx.$slots, "default")
                ])
              ])
            ];
          }
        }),
        _: 3
      }, _parent));
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Layout/BackofficeShell.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
