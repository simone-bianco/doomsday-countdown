import { defineComponent, unref, withCtx, createTextVNode, createVNode, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent } from "vue/server-renderer";
import { Head } from "@inertiajs/vue3";
import { h as _sfc_main$2, a as _sfc_main$3, x as _sfc_main$4, B as Button } from "../ssr.js";
import { u as useSmartForm } from "./useSmartForm-KUAiAz4w.js";
import { _ as _sfc_main$1 } from "./AppLayout-CiOlqxSX.js";
import { L as LoginDataRules } from "./form-rules-C_jmOmBo.js";
import "@vue/server-renderer";
import "clsx";
import "tailwind-merge";
import "lucide-vue-next";
import "vue-advanced-cropper";
import "node:async_hooks";
import "i18next";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Login",
  __ssrInlineRender: true,
  props: {
    backofficePath: {}
  },
  setup(__props) {
    const form = useSmartForm({ ...LoginDataRules });
    form.fill({ email: "", password: "", remember: false });
    function submit() {
      form.post("/login");
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Login" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, { title: "Backoffice login" }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$2), {
              "max-width": "32rem",
              ui: { root: "mx-auto", body: "p-6" }
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<form class="space-y-4"${_scopeId2}>`);
                  _push3(ssrRenderComponent(unref(_sfc_main$3), {
                    modelValue: unref(form).email,
                    "onUpdate:modelValue": ($event) => unref(form).email = $event,
                    label: "Email",
                    type: "email",
                    error: unref(form).errors.email,
                    onBlur: ($event) => unref(form).validateField("email")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(unref(_sfc_main$3), {
                    modelValue: unref(form).password,
                    "onUpdate:modelValue": ($event) => unref(form).password = $event,
                    label: "Password",
                    type: "password",
                    error: unref(form).errors.password,
                    onBlur: ($event) => unref(form).validateField("password")
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(unref(_sfc_main$4), {
                    id: "remember",
                    checked: unref(form).remember,
                    "onUpdate:checked": ($event) => unref(form).remember = $event,
                    label: "Remember me"
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(unref(Button), {
                    type: "submit",
                    loading: unref(form).processing
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Login`);
                      } else {
                        return [
                          createTextVNode("Login")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(`</form>`);
                } else {
                  return [
                    createVNode("form", {
                      class: "space-y-4",
                      onSubmit: withModifiers(submit, ["prevent"])
                    }, [
                      createVNode(unref(_sfc_main$3), {
                        modelValue: unref(form).email,
                        "onUpdate:modelValue": ($event) => unref(form).email = $event,
                        label: "Email",
                        type: "email",
                        error: unref(form).errors.email,
                        onBlur: ($event) => unref(form).validateField("email")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "onBlur"]),
                      createVNode(unref(_sfc_main$3), {
                        modelValue: unref(form).password,
                        "onUpdate:modelValue": ($event) => unref(form).password = $event,
                        label: "Password",
                        type: "password",
                        error: unref(form).errors.password,
                        onBlur: ($event) => unref(form).validateField("password")
                      }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "onBlur"]),
                      createVNode(unref(_sfc_main$4), {
                        id: "remember",
                        checked: unref(form).remember,
                        "onUpdate:checked": ($event) => unref(form).remember = $event,
                        label: "Remember me"
                      }, null, 8, ["checked", "onUpdate:checked"]),
                      createVNode(unref(Button), {
                        type: "submit",
                        loading: unref(form).processing
                      }, {
                        default: withCtx(() => [
                          createTextVNode("Login")
                        ]),
                        _: 1
                      }, 8, ["loading"])
                    ], 32)
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$2), {
                "max-width": "32rem",
                ui: { root: "mx-auto", body: "p-6" }
              }, {
                default: withCtx(() => [
                  createVNode("form", {
                    class: "space-y-4",
                    onSubmit: withModifiers(submit, ["prevent"])
                  }, [
                    createVNode(unref(_sfc_main$3), {
                      modelValue: unref(form).email,
                      "onUpdate:modelValue": ($event) => unref(form).email = $event,
                      label: "Email",
                      type: "email",
                      error: unref(form).errors.email,
                      onBlur: ($event) => unref(form).validateField("email")
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "onBlur"]),
                    createVNode(unref(_sfc_main$3), {
                      modelValue: unref(form).password,
                      "onUpdate:modelValue": ($event) => unref(form).password = $event,
                      label: "Password",
                      type: "password",
                      error: unref(form).errors.password,
                      onBlur: ($event) => unref(form).validateField("password")
                    }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "onBlur"]),
                    createVNode(unref(_sfc_main$4), {
                      id: "remember",
                      checked: unref(form).remember,
                      "onUpdate:checked": ($event) => unref(form).remember = $event,
                      label: "Remember me"
                    }, null, 8, ["checked", "onUpdate:checked"]),
                    createVNode(unref(Button), {
                      type: "submit",
                      loading: unref(form).processing
                    }, {
                      default: withCtx(() => [
                        createTextVNode("Login")
                      ]),
                      _: 1
                    }, 8, ["loading"])
                  ], 32)
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Login.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
