import { defineComponent, ref, computed, unref, withCtx, createTextVNode, createVNode, toDisplayString, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { Head } from "@inertiajs/vue3";
import { _ as _sfc_main$7 } from "./BackofficeShell-B2o2xGeb.js";
import { Plus, Edit3, Trash2 } from "lucide-vue-next";
import { f as _sfc_main$2, B as Button, k as _sfc_main$3, M as Modal, a as _sfc_main$4 } from "../ssr.js";
import { u as useSmartForm } from "./useSmartForm-KUAiAz4w.js";
import "./AppLayout-CiOlqxSX.js";
import { _ as _sfc_main$6 } from "./DeleteConfirmationModal-7AxgbmJP.js";
import { _ as _sfc_main$5 } from "./FormActions-Wl8L_zsK.js";
import { b as backofficeTableSearchUi, a as backofficeInteractiveTableUi } from "./backofficeTableUi-5WvUAWKW.js";
import { f as SaveUserDataRules } from "./form-rules-C_jmOmBo.js";
import "@vue/server-renderer";
import "clsx";
import "tailwind-merge";
import "vue-advanced-cropper";
import "node:async_hooks";
import "i18next";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "UserManager",
  __ssrInlineRender: true,
  props: {
    backofficePath: {},
    users: {}
  },
  setup(__props) {
    const props = __props;
    const isFormOpen = ref(false);
    const editingId = ref(null);
    const deletingId = ref(null);
    const pendingDelete = ref(null);
    const searchQuery = ref("");
    const backofficeBasePath = computed(() => `/${props.backofficePath.replace(/^\/+|\/+$/g, "")}`);
    const normalizedSearchQuery = computed(() => searchQuery.value.trim().toLowerCase());
    const rows = computed(() => props.users.filter((user) => matchesSearch(user, normalizedSearchQuery.value)).map((user) => ({ ...user })));
    const columns = [
      { key: "name", label: "User", class: "flex-[2]", headerClass: "flex-[2]" },
      { key: "email", label: "Email", class: "flex-[2]", headerClass: "flex-[2]" },
      { key: "actions", label: "Actions", class: "w-44 flex-none", headerClass: "w-44 flex-none text-right" }
    ];
    const form = useSmartForm({ ...SaveUserDataRules });
    const deleteForm = useSmartForm({});
    const formTitle = computed(() => editingId.value === null ? "Create user" : "Edit user");
    form.fill(defaults());
    function defaults() {
      return { name: "", email: "", password: null };
    }
    function userUrl(userId) {
      return userId === void 0 ? `${backofficeBasePath.value}/users` : `${backofficeBasePath.value}/users/${userId}`;
    }
    function asUser(item) {
      return item;
    }
    function matchesSearch(user, query) {
      if (query === "") {
        return true;
      }
      return [user.id, user.name, user.email].some((value) => String(value).toLowerCase().includes(query));
    }
    function openCreate() {
      editingId.value = null;
      form.fill(defaults());
      form.clearErrors();
      isFormOpen.value = true;
    }
    function edit(user) {
      editingId.value = user.id;
      form.fill({ name: user.name, email: user.email, password: null });
      form.clearErrors();
      isFormOpen.value = true;
    }
    function closeForm() {
      isFormOpen.value = false;
      editingId.value = null;
      form.fill(defaults());
      form.clearErrors();
    }
    function save() {
      const url = editingId.value === null ? userUrl() : userUrl(editingId.value);
      const method = editingId.value === null ? form.post : form.put;
      method(url, { onSuccess: closeForm });
    }
    function destroy(user) {
      pendingDelete.value = user;
    }
    function confirmDestroy() {
      const user = pendingDelete.value;
      if (user === null) {
        return;
      }
      deletingId.value = user.id;
      deleteForm.delete(userUrl(user.id), {
        onSuccess: () => {
          pendingDelete.value = null;
          if (editingId.value === user.id) {
            closeForm();
          }
        },
        onFinish: () => {
          deletingId.value = null;
        }
      });
    }
    return (_ctx, _push, _parent, _attrs) => {
      var _a;
      _push(`<!--[--><div class="space-y-4"><div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">`);
      _push(ssrRenderComponent(unref(_sfc_main$2), {
        modelValue: searchQuery.value,
        "onUpdate:modelValue": ($event) => searchQuery.value = $event,
        placeholder: "Search users",
        ui: unref(backofficeTableSearchUi)
      }, null, _parent));
      _push(ssrRenderComponent(unref(Button), {
        icon: unref(Plus),
        onClick: openCreate
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`Create user`);
          } else {
            return [
              createTextVNode("Create user")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div>`);
      _push(ssrRenderComponent(unref(_sfc_main$3), {
        items: rows.value,
        columns,
        "item-key": "id",
        density: "comfortable",
        "enable-row-click": "",
        ui: unref(backofficeInteractiveTableUi),
        onRowClick: (item) => edit(asUser(item))
      }, {
        "cell-name": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="font-medium"${_scopeId}>${ssrInterpolate(item.name)}</div>`);
          } else {
            return [
              createVNode("div", { class: "font-medium" }, toDisplayString(item.name), 1)
            ];
          }
        }),
        "cell-email": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(item.email)}</div>`);
          } else {
            return [
              createVNode("div", { class: "text-sm text-ui-muted-foreground" }, toDisplayString(item.email), 1)
            ];
          }
        }),
        "cell-actions": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex items-center justify-end gap-2" data-no-row-click${_scopeId}>`);
            _push2(ssrRenderComponent(unref(Button), {
              variant: "secondary",
              size: "sm",
              icon: unref(Edit3),
              onClick: ($event) => edit(asUser(item))
            }, {
              default: withCtx((_, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Edit`);
                } else {
                  return [
                    createTextVNode("Edit")
                  ];
                }
              }),
              _: 2
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Button), {
              variant: "danger",
              size: "sm",
              icon: unref(Trash2),
              loading: unref(deleteForm).processing && deletingId.value === item.id,
              disabled: unref(deleteForm).processing,
              onClick: ($event) => destroy(asUser(item))
            }, {
              default: withCtx((_, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Delete`);
                } else {
                  return [
                    createTextVNode("Delete")
                  ];
                }
              }),
              _: 2
            }, _parent2, _scopeId));
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", {
                class: "flex items-center justify-end gap-2",
                "data-no-row-click": ""
              }, [
                createVNode(unref(Button), {
                  variant: "secondary",
                  size: "sm",
                  icon: unref(Edit3),
                  onClick: ($event) => edit(asUser(item))
                }, {
                  default: withCtx(() => [
                    createTextVNode("Edit")
                  ]),
                  _: 1
                }, 8, ["icon", "onClick"]),
                createVNode(unref(Button), {
                  variant: "danger",
                  size: "sm",
                  icon: unref(Trash2),
                  loading: unref(deleteForm).processing && deletingId.value === item.id,
                  disabled: unref(deleteForm).processing,
                  onClick: ($event) => destroy(asUser(item))
                }, {
                  default: withCtx(() => [
                    createTextVNode("Delete")
                  ]),
                  _: 1
                }, 8, ["icon", "loading", "disabled", "onClick"])
              ])
            ];
          }
        }),
        empty: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<p class="text-sm text-ui-muted-foreground"${_scopeId}>No users match this search.</p>`);
          } else {
            return [
              createVNode("p", { class: "text-sm text-ui-muted-foreground" }, "No users match this search.")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div>`);
      _push(ssrRenderComponent(unref(Modal), {
        show: isFormOpen.value,
        title: formTitle.value,
        "max-width": "xl",
        onClose: closeForm
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<form class="space-y-4"${_scopeId}><div class="grid gap-4 md:grid-cols-2"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$4), {
              modelValue: unref(form).name,
              "onUpdate:modelValue": ($event) => unref(form).name = $event,
              label: "Name",
              error: unref(form).errors.name,
              onBlur: ($event) => unref(form).validateField("name")
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(_sfc_main$4), {
              modelValue: unref(form).email,
              "onUpdate:modelValue": ($event) => unref(form).email = $event,
              label: "Email",
              type: "email",
              error: unref(form).errors.email,
              onBlur: ($event) => unref(form).validateField("email")
            }, null, _parent2, _scopeId));
            _push2(`</div>`);
            _push2(ssrRenderComponent(unref(_sfc_main$4), {
              modelValue: unref(form).password,
              "onUpdate:modelValue": ($event) => unref(form).password = $event,
              label: "Password",
              type: "password",
              error: unref(form).errors.password,
              "helper-text": "Required only for new users or password changes."
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$5, { compact: "" }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(unref(Button), {
                    type: "submit",
                    loading: unref(form).processing
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(editingId.value === null ? "Create user" : "Save user")}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(editingId.value === null ? "Create user" : "Save user"), 1)
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(unref(Button), {
                    variant: "secondary",
                    onClick: closeForm
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Cancel`);
                      } else {
                        return [
                          createTextVNode("Cancel")
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(unref(Button), {
                      type: "submit",
                      loading: unref(form).processing
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(editingId.value === null ? "Create user" : "Save user"), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"]),
                    createVNode(unref(Button), {
                      variant: "secondary",
                      onClick: closeForm
                    }, {
                      default: withCtx(() => [
                        createTextVNode("Cancel")
                      ]),
                      _: 1
                    })
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</form>`);
          } else {
            return [
              createVNode("form", {
                class: "space-y-4",
                onSubmit: withModifiers(save, ["prevent"])
              }, [
                createVNode("div", { class: "grid gap-4 md:grid-cols-2" }, [
                  createVNode(unref(_sfc_main$4), {
                    modelValue: unref(form).name,
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    label: "Name",
                    error: unref(form).errors.name,
                    onBlur: ($event) => unref(form).validateField("name")
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "onBlur"]),
                  createVNode(unref(_sfc_main$4), {
                    modelValue: unref(form).email,
                    "onUpdate:modelValue": ($event) => unref(form).email = $event,
                    label: "Email",
                    type: "email",
                    error: unref(form).errors.email,
                    onBlur: ($event) => unref(form).validateField("email")
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "onBlur"])
                ]),
                createVNode(unref(_sfc_main$4), {
                  modelValue: unref(form).password,
                  "onUpdate:modelValue": ($event) => unref(form).password = $event,
                  label: "Password",
                  type: "password",
                  error: unref(form).errors.password,
                  "helper-text": "Required only for new users or password changes."
                }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                createVNode(_sfc_main$5, { compact: "" }, {
                  default: withCtx(() => [
                    createVNode(unref(Button), {
                      type: "submit",
                      loading: unref(form).processing
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(editingId.value === null ? "Create user" : "Save user"), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"]),
                    createVNode(unref(Button), {
                      variant: "secondary",
                      onClick: closeForm
                    }, {
                      default: withCtx(() => [
                        createTextVNode("Cancel")
                      ]),
                      _: 1
                    })
                  ]),
                  _: 1
                })
              ], 32)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$6, {
        show: pendingDelete.value !== null,
        title: "Delete user",
        description: `Delete user ${((_a = pendingDelete.value) == null ? void 0 : _a.email) ?? ""}? This action cannot be undone.`,
        loading: unref(deleteForm).processing,
        onClose: ($event) => pendingDelete.value = null,
        onConfirm: confirmDestroy
      }, null, _parent));
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/UserManager.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    backofficePath: {},
    counts: {},
    users: {}
  },
  setup(__props) {
    const props = __props;
    const activeSection = ref("users");
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Backoffice users" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$7, {
        "active-section": activeSection.value,
        "onUpdate:activeSection": ($event) => activeSection.value = $event,
        title: "Users",
        subtitle: "Manage authenticated backoffice users.",
        "backoffice-path": props.backofficePath,
        counts: props.counts
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$1, {
              users: props.users,
              "backoffice-path": props.backofficePath
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$1, {
                users: props.users,
                "backoffice-path": props.backofficePath
              }, null, 8, ["users", "backoffice-path"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Backoffice/Users/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
