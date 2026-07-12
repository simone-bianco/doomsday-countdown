import { defineComponent, ref, computed, unref, withCtx, createTextVNode, createVNode, toDisplayString, openBlock, createBlock, createCommentVNode, withModifiers, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { Head } from "@inertiajs/vue3";
import { _ as _sfc_main$a } from "./BackofficeShell-B2o2xGeb.js";
import { Plus, Edit3, Trash2 } from "lucide-vue-next";
import { f as _sfc_main$2, B as Button, k as _sfc_main$3, e as _sfc_main$4, M as Modal, a as _sfc_main$5, _ as _sfc_main$8 } from "../ssr.js";
import { u as useSmartForm } from "./useSmartForm-KUAiAz4w.js";
import "./AppLayout-CiOlqxSX.js";
import { _ as _sfc_main$6 } from "./BackofficeSelectField-CNJDQmD8.js";
import { _ as _sfc_main$7 } from "./FormActions-Wl8L_zsK.js";
import { _ as _sfc_main$9 } from "./DeleteConfirmationModal-7AxgbmJP.js";
import { b as backofficeTableSearchUi, a as backofficeInteractiveTableUi } from "./backofficeTableUi-5WvUAWKW.js";
import { e as SaveOpenAiKeyDataRules } from "./form-rules-C_jmOmBo.js";
import "@vue/server-renderer";
import "clsx";
import "tailwind-merge";
import "vue-advanced-cropper";
import "node:async_hooks";
import "i18next";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "OpenAiKeyManager",
  __ssrInlineRender: true,
  props: {
    backofficePath: {},
    apiKeys: {}
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
    const rows = computed(() => props.apiKeys.filter((apiKey) => matchesSearch(apiKey, normalizedSearchQuery.value)).map((apiKey) => ({ ...apiKey })));
    const columns = [
      { key: "label", label: "Key", class: "flex-[2]", headerClass: "flex-[2]" },
      { key: "status", label: "Status", class: "flex-1", headerClass: "flex-1" },
      { key: "usage", label: "Usage", class: "flex-[2]", headerClass: "flex-[2]" },
      { key: "actions", label: "Actions", class: "w-44 flex-none", headerClass: "w-44 flex-none text-right" }
    ];
    const baseLimitOptions = [
      { value: "none", label: "None" },
      { value: "fixed", label: "Fixed" },
      { value: "unlimited", label: "Unlimited" }
    ];
    const freeLimitOptions = [
      { value: "none", label: "None" },
      { value: "daily", label: "Daily" },
      { value: "monthly", label: "Monthly" }
    ];
    const form = useSmartForm({ ...SaveOpenAiKeyDataRules });
    const deleteForm = useSmartForm({});
    const formTitle = computed(() => editingId.value === null ? "Register OpenAI key" : "Edit OpenAI key");
    form.fill(defaults());
    function defaults() {
      return {
        label: "",
        key: "",
        base_limit_type: "unlimited",
        max_base_usage: null,
        free_limit_type: "none",
        max_free_usage: null,
        is_active: true
      };
    }
    function openAiKeyUrl(apiKeyId) {
      return apiKeyId === void 0 ? `${backofficeBasePath.value}/openai-keys` : `${backofficeBasePath.value}/openai-keys/${apiKeyId}`;
    }
    function asApiKey(item) {
      return item;
    }
    function matchesSearch(apiKey, query) {
      if (query === "") {
        return true;
      }
      const status = apiKey.is_active ? "active" : "inactive";
      const depletion = apiKey.is_depleted ? "depleted" : "available";
      return [
        apiKey.id,
        apiKey.label,
        apiKey.masked_key,
        apiKey.base_limit_type,
        apiKey.free_limit_type,
        apiKey.current_base_usage,
        apiKey.max_base_usage ?? "unlimited",
        apiKey.current_free_usage,
        apiKey.max_free_usage ?? "unlimited",
        status,
        depletion
      ].some((value) => String(value).toLowerCase().includes(query));
    }
    function chooseBaseLimit(value) {
      if (typeof value === "string") {
        form.base_limit_type = value;
      }
    }
    function chooseFreeLimit(value) {
      if (typeof value === "string") {
        form.free_limit_type = value;
      }
    }
    function openCreate() {
      editingId.value = null;
      form.fill(defaults());
      form.clearErrors();
      isFormOpen.value = true;
    }
    function edit(apiKey) {
      editingId.value = apiKey.id;
      form.fill({
        label: apiKey.label,
        key: "",
        base_limit_type: apiKey.base_limit_type,
        max_base_usage: apiKey.max_base_usage,
        free_limit_type: apiKey.free_limit_type,
        max_free_usage: apiKey.max_free_usage,
        is_active: apiKey.is_active
      });
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
      const url = editingId.value === null ? openAiKeyUrl() : openAiKeyUrl(editingId.value);
      const method = editingId.value === null ? form.post : form.put;
      method(url, { onSuccess: closeForm });
    }
    function destroy(apiKey) {
      pendingDelete.value = apiKey;
    }
    function confirmDestroy() {
      const apiKey = pendingDelete.value;
      if (apiKey === null) {
        return;
      }
      deletingId.value = apiKey.id;
      deleteForm.delete(openAiKeyUrl(apiKey.id), {
        onSuccess: () => {
          pendingDelete.value = null;
          if (editingId.value === apiKey.id) {
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
        placeholder: "Search OpenAI keys",
        ui: unref(backofficeTableSearchUi)
      }, null, _parent));
      _push(ssrRenderComponent(unref(Button), {
        icon: unref(Plus),
        onClick: openCreate
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`Register key`);
          } else {
            return [
              createTextVNode("Register key")
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
        onRowClick: (item) => edit(asApiKey(item))
      }, {
        "cell-label": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="space-y-1"${_scopeId}><div class="font-medium"${_scopeId}>${ssrInterpolate(item.label)}</div><div class="font-mono text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(item.masked_key)}</div></div>`);
          } else {
            return [
              createVNode("div", { class: "space-y-1" }, [
                createVNode("div", { class: "font-medium" }, toDisplayString(item.label), 1),
                createVNode("div", { class: "font-mono text-sm text-ui-muted-foreground" }, toDisplayString(item.masked_key), 1)
              ])
            ];
          }
        }),
        "cell-status": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex flex-wrap gap-2"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$4), {
              color: item.is_active ? "success" : "secondary"
            }, {
              default: withCtx((_, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(item.is_active ? "active" : "inactive")}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(item.is_active ? "active" : "inactive"), 1)
                  ];
                }
              }),
              _: 2
            }, _parent2, _scopeId));
            if (item.is_depleted) {
              _push2(ssrRenderComponent(unref(_sfc_main$4), { color: "danger" }, {
                default: withCtx((_, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`depleted`);
                  } else {
                    return [
                      createTextVNode("depleted")
                    ];
                  }
                }),
                _: 2
              }, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "flex flex-wrap gap-2" }, [
                createVNode(unref(_sfc_main$4), {
                  color: item.is_active ? "success" : "secondary"
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(item.is_active ? "active" : "inactive"), 1)
                  ]),
                  _: 2
                }, 1032, ["color"]),
                item.is_depleted ? (openBlock(), createBlock(unref(_sfc_main$4), {
                  key: 0,
                  color: "danger"
                }, {
                  default: withCtx(() => [
                    createTextVNode("depleted")
                  ]),
                  _: 1
                })) : createCommentVNode("", true)
              ])
            ];
          }
        }),
        "cell-usage": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="text-sm text-ui-muted-foreground"${_scopeId}> paid ${ssrInterpolate(item.current_base_usage)} / ${ssrInterpolate(item.max_base_usage ?? "∞")} · free ${ssrInterpolate(item.current_free_usage)} / ${ssrInterpolate(item.max_free_usage ?? "∞")}</div>`);
          } else {
            return [
              createVNode("div", { class: "text-sm text-ui-muted-foreground" }, " paid " + toDisplayString(item.current_base_usage) + " / " + toDisplayString(item.max_base_usage ?? "∞") + " · free " + toDisplayString(item.current_free_usage) + " / " + toDisplayString(item.max_free_usage ?? "∞"), 1)
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
              onClick: ($event) => edit(asApiKey(item))
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
              onClick: ($event) => destroy(asApiKey(item))
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
                  onClick: ($event) => edit(asApiKey(item))
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
                  onClick: ($event) => destroy(asApiKey(item))
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
            _push2(`<p class="text-sm text-ui-muted-foreground"${_scopeId}>No OpenAI keys match this search.</p>`);
          } else {
            return [
              createVNode("p", { class: "text-sm text-ui-muted-foreground" }, "No OpenAI keys match this search.")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div>`);
      _push(ssrRenderComponent(unref(Modal), {
        show: isFormOpen.value,
        title: formTitle.value,
        "max-width": "2xl",
        onClose: closeForm
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<form class="space-y-4"${_scopeId}><div class="grid gap-4 md:grid-cols-2"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$5), {
              modelValue: unref(form).label,
              "onUpdate:modelValue": ($event) => unref(form).label = $event,
              label: "Label",
              error: unref(form).errors.label
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(_sfc_main$5), {
              modelValue: unref(form).key,
              "onUpdate:modelValue": ($event) => unref(form).key = $event,
              label: "OpenAI key",
              type: "password",
              error: unref(form).errors.key,
              "helper-text": "Leave empty while editing to keep the stored key."
            }, null, _parent2, _scopeId));
            _push2(`</div><div class="grid gap-4 md:grid-cols-2"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$6, {
              label: "Paid pool",
              "model-value": unref(form).base_limit_type,
              options: baseLimitOptions,
              clearable: false,
              "onUpdate:modelValue": chooseBaseLimit
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(_sfc_main$5), {
              modelValue: unref(form).max_base_usage,
              "onUpdate:modelValue": ($event) => unref(form).max_base_usage = $event,
              label: "Paid token limit",
              type: "number",
              error: unref(form).errors.max_base_usage
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$6, {
              label: "Free pool",
              "model-value": unref(form).free_limit_type,
              options: freeLimitOptions,
              clearable: false,
              "onUpdate:modelValue": chooseFreeLimit
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(_sfc_main$5), {
              modelValue: unref(form).max_free_usage,
              "onUpdate:modelValue": ($event) => unref(form).max_free_usage = $event,
              label: "Free token limit",
              type: "number",
              error: unref(form).errors.max_free_usage
            }, null, _parent2, _scopeId));
            _push2(`</div>`);
            _push2(ssrRenderComponent(_sfc_main$7, { compact: "" }, {
              aside: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(unref(_sfc_main$8), {
                    modelValue: unref(form).is_active,
                    "onUpdate:modelValue": ($event) => unref(form).is_active = $event,
                    label: "Active",
                    "on-label": "Enabled",
                    "off-label": "Disabled"
                  }, null, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(unref(_sfc_main$8), {
                      modelValue: unref(form).is_active,
                      "onUpdate:modelValue": ($event) => unref(form).is_active = $event,
                      label: "Active",
                      "on-label": "Enabled",
                      "off-label": "Disabled"
                    }, null, 8, ["modelValue", "onUpdate:modelValue"])
                  ];
                }
              }),
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(unref(Button), {
                    type: "submit",
                    loading: unref(form).processing
                  }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`${ssrInterpolate(editingId.value === null ? "Register key" : "Save key")}`);
                      } else {
                        return [
                          createTextVNode(toDisplayString(editingId.value === null ? "Register key" : "Save key"), 1)
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
                        createTextVNode(toDisplayString(editingId.value === null ? "Register key" : "Save key"), 1)
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
                  createVNode(unref(_sfc_main$5), {
                    modelValue: unref(form).label,
                    "onUpdate:modelValue": ($event) => unref(form).label = $event,
                    label: "Label",
                    error: unref(form).errors.label
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                  createVNode(unref(_sfc_main$5), {
                    modelValue: unref(form).key,
                    "onUpdate:modelValue": ($event) => unref(form).key = $event,
                    label: "OpenAI key",
                    type: "password",
                    error: unref(form).errors.key,
                    "helper-text": "Leave empty while editing to keep the stored key."
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "error"])
                ]),
                createVNode("div", { class: "grid gap-4 md:grid-cols-2" }, [
                  createVNode(_sfc_main$6, {
                    label: "Paid pool",
                    "model-value": unref(form).base_limit_type,
                    options: baseLimitOptions,
                    clearable: false,
                    "onUpdate:modelValue": chooseBaseLimit
                  }, null, 8, ["model-value"]),
                  createVNode(unref(_sfc_main$5), {
                    modelValue: unref(form).max_base_usage,
                    "onUpdate:modelValue": ($event) => unref(form).max_base_usage = $event,
                    label: "Paid token limit",
                    type: "number",
                    error: unref(form).errors.max_base_usage
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                  createVNode(_sfc_main$6, {
                    label: "Free pool",
                    "model-value": unref(form).free_limit_type,
                    options: freeLimitOptions,
                    clearable: false,
                    "onUpdate:modelValue": chooseFreeLimit
                  }, null, 8, ["model-value"]),
                  createVNode(unref(_sfc_main$5), {
                    modelValue: unref(form).max_free_usage,
                    "onUpdate:modelValue": ($event) => unref(form).max_free_usage = $event,
                    label: "Free token limit",
                    type: "number",
                    error: unref(form).errors.max_free_usage
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "error"])
                ]),
                createVNode(_sfc_main$7, { compact: "" }, {
                  aside: withCtx(() => [
                    createVNode(unref(_sfc_main$8), {
                      modelValue: unref(form).is_active,
                      "onUpdate:modelValue": ($event) => unref(form).is_active = $event,
                      label: "Active",
                      "on-label": "Enabled",
                      "off-label": "Disabled"
                    }, null, 8, ["modelValue", "onUpdate:modelValue"])
                  ]),
                  default: withCtx(() => [
                    createVNode(unref(Button), {
                      type: "submit",
                      loading: unref(form).processing
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(editingId.value === null ? "Register key" : "Save key"), 1)
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
      _push(ssrRenderComponent(_sfc_main$9, {
        show: pendingDelete.value !== null,
        title: "Delete OpenAI key",
        description: `Delete OpenAI key ${((_a = pendingDelete.value) == null ? void 0 : _a.label) ?? ""}? This action cannot be undone.`,
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/OpenAiKeyManager.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    backofficePath: {},
    counts: {},
    apiKeys: {}
  },
  setup(__props) {
    const props = __props;
    const activeSection = ref("openai-keys");
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "OpenAI keys" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$a, {
        "active-section": activeSection.value,
        "onUpdate:activeSection": ($event) => activeSection.value = $event,
        title: "OpenAI keys",
        subtitle: "Manage OpenAI key rotation and usage limits.",
        "backoffice-path": props.backofficePath,
        counts: props.counts
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$1, {
              "api-keys": props.apiKeys,
              "backoffice-path": props.backofficePath
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$1, {
                "api-keys": props.apiKeys,
                "backoffice-path": props.backofficePath
              }, null, 8, ["api-keys", "backoffice-path"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Backoffice/OpenAiKeys/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
