import { defineComponent, ref, computed, unref, withCtx, createTextVNode, createVNode, toDisplayString, openBlock, createBlock, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { Head, router } from "@inertiajs/vue3";
import { Edit3, Trash2, Target, Plus } from "lucide-vue-next";
import { c as _sfc_main$2, B as Button, e as _sfc_main$3, j as _sfc_main$4, f as _sfc_main$5 } from "../ssr.js";
import { u as useSmartForm } from "./useSmartForm-KUAiAz4w.js";
import "./AppLayout-CiOlqxSX.js";
import { _ as _sfc_main$1 } from "./BackofficeShell-B2o2xGeb.js";
import { _ as _sfc_main$6 } from "./DeleteConfirmationModal-7AxgbmJP.js";
import { u as useBackofficePath } from "./useBackofficePath-DWj-NlKi.js";
import "@vue/server-renderer";
import "clsx";
import "tailwind-merge";
import "vue-advanced-cropper";
import "node:async_hooks";
import "i18next";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    countdowns: {},
    options: {}
  },
  setup(__props) {
    const SORT_KEYS = ["id", "title", "sort_order"];
    const props = __props;
    const { backofficePath, normalizedBackofficePath, counts } = useBackofficePath();
    const activeSection = ref("countdowns");
    const pendingDelete = ref(null);
    const deletingId = ref(null);
    const deleteForm = useSmartForm({});
    const rows = computed(() => props.countdowns.data.map((countdown) => ({ ...countdown })));
    const searchQuery = computed(() => {
      var _a;
      return ((_a = props.countdowns.filters) == null ? void 0 : _a.search) ?? currentSearchQuery();
    });
    const currentSort = computed(() => ({
      key: currentSortKey(),
      direction: currentSortDirection()
    }));
    const columns = [
      { key: "id", label: "ID", class: "w-20 flex-none", headerClass: "w-20 flex-none", sortable: true },
      { key: "image", label: "", class: "w-20 flex-none", headerClass: "w-20 flex-none" },
      { key: "title", label: "Countdown", class: "flex-[2]", headerClass: "flex-[2]", sortable: true },
      { key: "sort_order", label: "Sort", class: "w-24 flex-none", headerClass: "w-24 flex-none", sortable: true },
      { key: "status", label: "Status", class: "w-40 flex-none", headerClass: "w-40 flex-none" },
      { key: "relations", label: "Relations", class: "w-48 flex-none", headerClass: "w-48 flex-none" },
      { key: "actions", label: "Actions", class: "w-40 flex-none", headerClass: "w-40 flex-none text-right" }
    ];
    const flatCountdownTableUi = {
      toolbar: "rounded-none border-0 bg-transparent px-0 pt-0 pb-4",
      root: "space-y-0 rounded-none border-0 bg-transparent",
      header: "border-b border-ui-border bg-ui-muted/30",
      row: "hover:bg-ui-primary/20 focus-within:bg-ui-primary/20",
      footer: "border-t border-ui-border bg-transparent px-0 py-4"
    };
    function countdownUrl(countdown, suffix = "") {
      return countdown === void 0 ? `${normalizedBackofficePath.value}/countdowns${suffix}` : `${normalizedBackofficePath.value}/countdowns/${countdown.id}${suffix}`;
    }
    function asCountdown(item) {
      return item;
    }
    function visit(url) {
      router.visit(url);
    }
    function currentSearchQuery() {
      if (typeof window === "undefined") {
        return "";
      }
      return new URL(window.location.href).searchParams.get("search") ?? "";
    }
    function queryParam(key) {
      if (typeof window === "undefined") {
        return null;
      }
      return new URL(window.location.href).searchParams.get(key);
    }
    function normalizeSortKey(value) {
      return SORT_KEYS.includes(value) ? value : "sort_order";
    }
    function normalizeSortDirection(value) {
      return value === "desc" ? "desc" : "asc";
    }
    function currentSortKey() {
      var _a;
      return normalizeSortKey(((_a = props.countdowns.filters) == null ? void 0 : _a.sort) ?? queryParam("sort"));
    }
    function currentSortDirection() {
      var _a;
      return normalizeSortDirection(((_a = props.countdowns.filters) == null ? void 0 : _a.direction) ?? queryParam("direction"));
    }
    function queryObject(params) {
      const query = {};
      for (const [key, value] of params.entries()) {
        query[key] = value;
      }
      return query;
    }
    function handleSearch(query) {
      const params = typeof window === "undefined" ? new URLSearchParams() : new URLSearchParams(new URL(window.location.href).search);
      const normalizedQuery = query.trim();
      if (normalizedQuery === "") {
        params.delete("search");
      } else {
        params.set("search", normalizedQuery);
      }
      params.set("sort", currentSort.value.key);
      params.set("direction", currentSort.value.direction);
      params.delete("page");
      router.get(countdownUrl(void 0), queryObject(params), {
        preserveScroll: true,
        preserveState: true,
        replace: true
      });
    }
    function handleSortChange(sort) {
      const params = typeof window === "undefined" ? new URLSearchParams() : new URLSearchParams(new URL(window.location.href).search);
      params.set("sort", normalizeSortKey(sort.key));
      params.set("direction", normalizeSortDirection(sort.direction));
      params.delete("page");
      router.get(countdownUrl(void 0), queryObject(params), {
        preserveScroll: true,
        preserveState: true,
        replace: true
      });
    }
    function visitEdit(item) {
      visit(countdownUrl(asCountdown(item), "/edit"));
    }
    function imageSource(item) {
      const value = item.image_path;
      if (typeof value !== "string") {
        return "";
      }
      const trimmed = value.trim();
      if (trimmed === "") {
        return "";
      }
      if (/^(?:[a-z][a-z0-9+.-]*:|\/\/|\/)/i.test(trimmed)) {
        return trimmed;
      }
      return `/${trimmed.replace(/^\.?\//, "")}`;
    }
    function confirmDestroy() {
      const countdown = pendingDelete.value;
      if (countdown === null) {
        return;
      }
      deletingId.value = countdown.id;
      deleteForm.delete(countdownUrl(countdown), {
        onSuccess: () => {
          pendingDelete.value = null;
        },
        onFinish: () => {
          deletingId.value = null;
        }
      });
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Countdowns" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, {
        "active-section": activeSection.value,
        "onUpdate:activeSection": ($event) => activeSection.value = $event,
        title: "Countdowns",
        subtitle: "Manage public Doomsday Clock records.",
        "backoffice-path": unref(backofficePath),
        counts: unref(counts)
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          var _a, _b;
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$2), {
              data: rows.value,
              columns,
              meta: __props.countdowns.meta,
              links: __props.countdowns.links,
              "item-key": "id",
              searchable: "",
              "search-placeholder": "Search countdowns...",
              "search-query": searchQuery.value,
              sort: currentSort.value,
              "enable-row-click": "",
              density: "comfortable",
              ui: flatCountdownTableUi,
              onSearch: handleSearch,
              onSortChange: handleSortChange,
              onRowClick: (item) => visitEdit(item)
            }, {
              toolbar: withCtx(({ searchQuery: tableSearchQuery, updateSearch }, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between"${_scopeId2}>`);
                  _push3(ssrRenderComponent(unref(_sfc_main$5), {
                    "model-value": String(tableSearchQuery),
                    placeholder: "Search countdowns...",
                    ui: { root: "w-full md:w-96" },
                    "onUpdate:modelValue": updateSearch
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(unref(Button), {
                    icon: unref(Plus),
                    onClick: ($event) => visit(countdownUrl(void 0, "/create"))
                  }, {
                    default: withCtx((_2, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Create countdown`);
                      } else {
                        return [
                          createTextVNode("Create countdown")
                        ];
                      }
                    }),
                    _: 2
                  }, _parent3, _scopeId2));
                  _push3(`</div>`);
                } else {
                  return [
                    createVNode("div", { class: "flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between" }, [
                      createVNode(unref(_sfc_main$5), {
                        "model-value": String(tableSearchQuery),
                        placeholder: "Search countdowns...",
                        ui: { root: "w-full md:w-96" },
                        "onUpdate:modelValue": updateSearch
                      }, null, 8, ["model-value", "onUpdate:modelValue"]),
                      createVNode(unref(Button), {
                        icon: unref(Plus),
                        onClick: ($event) => visit(countdownUrl(void 0, "/create"))
                      }, {
                        default: withCtx(() => [
                          createTextVNode("Create countdown")
                        ]),
                        _: 1
                      }, 8, ["icon", "onClick"])
                    ])
                  ];
                }
              }),
              "cell-id": withCtx(({ item }, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<span class="font-mono text-xs text-ui-muted-foreground"${_scopeId2}>#${ssrInterpolate(item.id)}</span>`);
                } else {
                  return [
                    createVNode("span", { class: "font-mono text-xs text-ui-muted-foreground" }, "#" + toDisplayString(item.id), 1)
                  ];
                }
              }),
              "cell-image": withCtx(({ item }, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  if (imageSource(item)) {
                    _push3(ssrRenderComponent(unref(_sfc_main$4), {
                      src: imageSource(item),
                      alt: String(item.slug),
                      "aspect-ratio": "1/1",
                      rounded: "lg",
                      ui: { root: "h-14 w-14 overflow-hidden" }
                    }, null, _parent3, _scopeId2));
                  } else {
                    _push3(`<div class="flex h-14 w-14 items-center justify-center rounded-lg bg-ui-muted text-ui-muted-foreground"${_scopeId2}>`);
                    _push3(ssrRenderComponent(unref(Target), { class: "h-5 w-5" }, null, _parent3, _scopeId2));
                    _push3(`</div>`);
                  }
                } else {
                  return [
                    imageSource(item) ? (openBlock(), createBlock(unref(_sfc_main$4), {
                      key: 0,
                      src: imageSource(item),
                      alt: String(item.slug),
                      "aspect-ratio": "1/1",
                      rounded: "lg",
                      ui: { root: "h-14 w-14 overflow-hidden" }
                    }, null, 8, ["src", "alt"])) : (openBlock(), createBlock("div", {
                      key: 1,
                      class: "flex h-14 w-14 items-center justify-center rounded-lg bg-ui-muted text-ui-muted-foreground"
                    }, [
                      createVNode(unref(Target), { class: "h-5 w-5" })
                    ]))
                  ];
                }
              }),
              "cell-title": withCtx(({ item }, _push3, _parent3, _scopeId2) => {
                var _a2, _b2;
                if (_push3) {
                  _push3(`<div class="font-medium"${_scopeId2}>${ssrInterpolate(((_a2 = item.title) == null ? void 0 : _a2.en) ?? item.slug)}</div><div class="text-sm text-ui-muted-foreground"${_scopeId2}>${ssrInterpolate(item.slug)}</div>`);
                } else {
                  return [
                    createVNode("div", { class: "font-medium" }, toDisplayString(((_b2 = item.title) == null ? void 0 : _b2.en) ?? item.slug), 1),
                    createVNode("div", { class: "text-sm text-ui-muted-foreground" }, toDisplayString(item.slug), 1)
                  ];
                }
              }),
              "cell-sort_order": withCtx(({ item }, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<span class="font-mono text-sm text-ui-muted-foreground"${_scopeId2}>${ssrInterpolate(item.sort_order)}</span>`);
                } else {
                  return [
                    createVNode("span", { class: "font-mono text-sm text-ui-muted-foreground" }, toDisplayString(item.sort_order), 1)
                  ];
                }
              }),
              "cell-status": withCtx(({ item }, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="space-y-1"${_scopeId2}>`);
                  _push3(ssrRenderComponent(unref(_sfc_main$3), {
                    label: String(item.status),
                    variant: "soft"
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(unref(_sfc_main$3), {
                    label: String(item.severity),
                    color: "warning",
                    variant: "soft"
                  }, null, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(unref(_sfc_main$3), {
                    label: item.is_published ? "published" : "draft",
                    color: item.is_published ? "success" : "secondary",
                    variant: "soft"
                  }, null, _parent3, _scopeId2));
                  _push3(`</div>`);
                } else {
                  return [
                    createVNode("div", { class: "space-y-1" }, [
                      createVNode(unref(_sfc_main$3), {
                        label: String(item.status),
                        variant: "soft"
                      }, null, 8, ["label"]),
                      createVNode(unref(_sfc_main$3), {
                        label: String(item.severity),
                        color: "warning",
                        variant: "soft"
                      }, null, 8, ["label"]),
                      createVNode(unref(_sfc_main$3), {
                        label: item.is_published ? "published" : "draft",
                        color: item.is_published ? "success" : "secondary",
                        variant: "soft"
                      }, null, 8, ["label", "color"])
                    ])
                  ];
                }
              }),
              "cell-relations": withCtx(({ item }, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div class="space-y-1 text-sm text-ui-muted-foreground"${_scopeId2}><p${_scopeId2}>${ssrInterpolate(item.projections_count)} projections</p><p${_scopeId2}>${ssrInterpolate(item.visualizations_count)} visualizations</p><p${_scopeId2}>${ssrInterpolate(item.news_count)} news</p><p${_scopeId2}>${ssrInterpolate(item.initiatives_count)} initiatives</p></div>`);
                } else {
                  return [
                    createVNode("div", { class: "space-y-1 text-sm text-ui-muted-foreground" }, [
                      createVNode("p", null, toDisplayString(item.projections_count) + " projections", 1),
                      createVNode("p", null, toDisplayString(item.visualizations_count) + " visualizations", 1),
                      createVNode("p", null, toDisplayString(item.news_count) + " news", 1),
                      createVNode("p", null, toDisplayString(item.initiatives_count) + " initiatives", 1)
                    ])
                  ];
                }
              }),
              "cell-actions": withCtx(({ item }, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<div data-no-row-click class="flex items-center justify-end gap-2"${_scopeId2}>`);
                  _push3(ssrRenderComponent(unref(Button), {
                    variant: "secondary",
                    size: "sm",
                    icon: unref(Edit3),
                    onClick: ($event) => visitEdit(item)
                  }, {
                    default: withCtx((_2, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Edit`);
                      } else {
                        return [
                          createTextVNode("Edit")
                        ];
                      }
                    }),
                    _: 2
                  }, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(unref(Button), {
                    variant: "danger",
                    size: "sm",
                    icon: unref(Trash2),
                    loading: unref(deleteForm).processing && deletingId.value === item.id,
                    disabled: unref(deleteForm).processing,
                    onClick: ($event) => pendingDelete.value = asCountdown(item)
                  }, {
                    default: withCtx((_2, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(`Delete`);
                      } else {
                        return [
                          createTextVNode("Delete")
                        ];
                      }
                    }),
                    _: 2
                  }, _parent3, _scopeId2));
                  _push3(`</div>`);
                } else {
                  return [
                    createVNode("div", {
                      "data-no-row-click": "",
                      class: "flex items-center justify-end gap-2"
                    }, [
                      createVNode(unref(Button), {
                        variant: "secondary",
                        size: "sm",
                        icon: unref(Edit3),
                        onClick: ($event) => visitEdit(item)
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
                        onClick: ($event) => pendingDelete.value = asCountdown(item)
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
              _: 1
            }, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$6, {
              show: pendingDelete.value !== null,
              title: "Delete countdown",
              description: `Delete countdown ${((_a = pendingDelete.value) == null ? void 0 : _a.slug) ?? ""}? Related public data will be removed by backend services.`,
              loading: unref(deleteForm).processing,
              onClose: ($event) => pendingDelete.value = null,
              onConfirm: confirmDestroy
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$2), {
                data: rows.value,
                columns,
                meta: __props.countdowns.meta,
                links: __props.countdowns.links,
                "item-key": "id",
                searchable: "",
                "search-placeholder": "Search countdowns...",
                "search-query": searchQuery.value,
                sort: currentSort.value,
                "enable-row-click": "",
                density: "comfortable",
                ui: flatCountdownTableUi,
                onSearch: handleSearch,
                onSortChange: handleSortChange,
                onRowClick: (item) => visitEdit(item)
              }, {
                toolbar: withCtx(({ searchQuery: tableSearchQuery, updateSearch }) => [
                  createVNode("div", { class: "flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between" }, [
                    createVNode(unref(_sfc_main$5), {
                      "model-value": String(tableSearchQuery),
                      placeholder: "Search countdowns...",
                      ui: { root: "w-full md:w-96" },
                      "onUpdate:modelValue": updateSearch
                    }, null, 8, ["model-value", "onUpdate:modelValue"]),
                    createVNode(unref(Button), {
                      icon: unref(Plus),
                      onClick: ($event) => visit(countdownUrl(void 0, "/create"))
                    }, {
                      default: withCtx(() => [
                        createTextVNode("Create countdown")
                      ]),
                      _: 1
                    }, 8, ["icon", "onClick"])
                  ])
                ]),
                "cell-id": withCtx(({ item }) => [
                  createVNode("span", { class: "font-mono text-xs text-ui-muted-foreground" }, "#" + toDisplayString(item.id), 1)
                ]),
                "cell-image": withCtx(({ item }) => [
                  imageSource(item) ? (openBlock(), createBlock(unref(_sfc_main$4), {
                    key: 0,
                    src: imageSource(item),
                    alt: String(item.slug),
                    "aspect-ratio": "1/1",
                    rounded: "lg",
                    ui: { root: "h-14 w-14 overflow-hidden" }
                  }, null, 8, ["src", "alt"])) : (openBlock(), createBlock("div", {
                    key: 1,
                    class: "flex h-14 w-14 items-center justify-center rounded-lg bg-ui-muted text-ui-muted-foreground"
                  }, [
                    createVNode(unref(Target), { class: "h-5 w-5" })
                  ]))
                ]),
                "cell-title": withCtx(({ item }) => {
                  var _a2;
                  return [
                    createVNode("div", { class: "font-medium" }, toDisplayString(((_a2 = item.title) == null ? void 0 : _a2.en) ?? item.slug), 1),
                    createVNode("div", { class: "text-sm text-ui-muted-foreground" }, toDisplayString(item.slug), 1)
                  ];
                }),
                "cell-sort_order": withCtx(({ item }) => [
                  createVNode("span", { class: "font-mono text-sm text-ui-muted-foreground" }, toDisplayString(item.sort_order), 1)
                ]),
                "cell-status": withCtx(({ item }) => [
                  createVNode("div", { class: "space-y-1" }, [
                    createVNode(unref(_sfc_main$3), {
                      label: String(item.status),
                      variant: "soft"
                    }, null, 8, ["label"]),
                    createVNode(unref(_sfc_main$3), {
                      label: String(item.severity),
                      color: "warning",
                      variant: "soft"
                    }, null, 8, ["label"]),
                    createVNode(unref(_sfc_main$3), {
                      label: item.is_published ? "published" : "draft",
                      color: item.is_published ? "success" : "secondary",
                      variant: "soft"
                    }, null, 8, ["label", "color"])
                  ])
                ]),
                "cell-relations": withCtx(({ item }) => [
                  createVNode("div", { class: "space-y-1 text-sm text-ui-muted-foreground" }, [
                    createVNode("p", null, toDisplayString(item.projections_count) + " projections", 1),
                    createVNode("p", null, toDisplayString(item.visualizations_count) + " visualizations", 1),
                    createVNode("p", null, toDisplayString(item.news_count) + " news", 1),
                    createVNode("p", null, toDisplayString(item.initiatives_count) + " initiatives", 1)
                  ])
                ]),
                "cell-actions": withCtx(({ item }) => [
                  createVNode("div", {
                    "data-no-row-click": "",
                    class: "flex items-center justify-end gap-2"
                  }, [
                    createVNode(unref(Button), {
                      variant: "secondary",
                      size: "sm",
                      icon: unref(Edit3),
                      onClick: ($event) => visitEdit(item)
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
                      onClick: ($event) => pendingDelete.value = asCountdown(item)
                    }, {
                      default: withCtx(() => [
                        createTextVNode("Delete")
                      ]),
                      _: 1
                    }, 8, ["icon", "loading", "disabled", "onClick"])
                  ])
                ]),
                _: 1
              }, 8, ["data", "meta", "links", "search-query", "sort", "onRowClick"]),
              createVNode(_sfc_main$6, {
                show: pendingDelete.value !== null,
                title: "Delete countdown",
                description: `Delete countdown ${((_b = pendingDelete.value) == null ? void 0 : _b.slug) ?? ""}? Related public data will be removed by backend services.`,
                loading: unref(deleteForm).processing,
                onClose: ($event) => pendingDelete.value = null,
                onConfirm: confirmDestroy
              }, null, 8, ["show", "description", "loading", "onClose"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Backoffice/Countdowns/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
