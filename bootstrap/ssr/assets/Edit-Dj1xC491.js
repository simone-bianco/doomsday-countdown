import { defineComponent, mergeProps, unref, withCtx, createTextVNode, toDisplayString, createVNode, useSSRContext, ref, computed, openBlock, createBlock, createCommentVNode, watch } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrInterpolate } from "vue/server-renderer";
import { router, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$n } from "./BackofficeShell-B2o2xGeb.js";
import { Plus, Edit3, Trash2, Settings2, Activity, BarChart3, Newspaper, Megaphone } from "lucide-vue-next";
import { N as NumberInput, _ as _sfc_main$9, a as _sfc_main$a, b as _sfc_main$b, B as Button, c as _sfc_main$d, d as _sfc_main$e, e as _sfc_main$f, f as _sfc_main$g, M as Modal, g as _sfc_main$j, h as _sfc_main$k, i as _sfc_main$l } from "../ssr.js";
import { _ as _sfc_main$m } from "./CountdownForm-CavKe4_z.js";
import { u as useSmartForm } from "./useSmartForm-KUAiAz4w.js";
import "./AppLayout-CiOlqxSX.js";
import { _ as _sfc_main$h } from "./DeleteConfirmationModal-7AxgbmJP.js";
import { _ as _sfc_main$8 } from "./BackofficeSelectField-CNJDQmD8.js";
import { _ as _sfc_main$c } from "./FormActions-Wl8L_zsK.js";
import { S as SaveInitiativeDataRules, a as SaveNewsDataRules } from "./form-rules-C_jmOmBo.js";
import { o as optionItems, i as isoDate, f as first } from "./formHelpers-BXTPv_Pd.js";
import { _ as _sfc_main$i } from "./VisualizationPreview-CPwyEvMQ.js";
import { u as useBackofficePath } from "./useBackofficePath-DWj-NlKi.js";
import "@vue/server-renderer";
import "clsx";
import "tailwind-merge";
import "vue-advanced-cropper";
import "node:async_hooks";
import "i18next";
import "./VisualizationChart-CkPtd6z_.js";
const _sfc_main$7 = /* @__PURE__ */ defineComponent({
  __name: "InitiativeForm",
  __ssrInlineRender: true,
  props: {
    options: {},
    submitUrl: {},
    method: {},
    submitLabel: {},
    initiative: {}
  },
  emits: ["saved", "cancel"],
  setup(__props, { emit: __emit }) {
    var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k, _l, _m, _n, _o, _p, _q, _r;
    const props = __props;
    const emit = __emit;
    const mediaTypeOptions = optionItems(["article", "youtube_video"]);
    const form = useSmartForm({ ...SaveInitiativeDataRules });
    form.fill({
      locale: ((_a = props.initiative) == null ? void 0 : _a.locale) ?? first(props.options.initiative_locales, "en"),
      type: ((_b = props.initiative) == null ? void 0 : _b.type) ?? first(props.options.initiative_types, "resource"),
      title: ((_c = props.initiative) == null ? void 0 : _c.title) ?? "",
      excerpt: ((_d = props.initiative) == null ? void 0 : _d.excerpt) ?? "",
      body: ((_e = props.initiative) == null ? void 0 : _e.body) ?? null,
      organization: ((_f = props.initiative) == null ? void 0 : _f.organization) ?? null,
      url: ((_g = props.initiative) == null ? void 0 : _g.url) ?? "",
      content_type: ((_h = props.initiative) == null ? void 0 : _h.content_type) ?? "article",
      preview_image_url: ((_i = props.initiative) == null ? void 0 : _i.preview_image_url) ?? null,
      embed_url: ((_j = props.initiative) == null ? void 0 : _j.embed_url) ?? null,
      external_provider: ((_k = props.initiative) == null ? void 0 : _k.external_provider) ?? null,
      external_id: ((_l = props.initiative) == null ? void 0 : _l.external_id) ?? null,
      image_path: ((_m = props.initiative) == null ? void 0 : _m.image_path) ?? null,
      cta_label: ((_n = props.initiative) == null ? void 0 : _n.cta_label) ?? null,
      starts_at: isoDate((_o = props.initiative) == null ? void 0 : _o.starts_at),
      ends_at: isoDate((_p = props.initiative) == null ? void 0 : _p.ends_at),
      sort_order: ((_q = props.initiative) == null ? void 0 : _q.sort_order) ?? 0,
      is_featured: ((_r = props.initiative) == null ? void 0 : _r.is_featured) ?? false
    });
    function chooseLocale(value) {
      if (typeof value === "string") form.locale = value;
    }
    function chooseType(value) {
      if (typeof value === "string") form.type = value;
    }
    function chooseContentType(value) {
      if (typeof value === "string") form.content_type = value;
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<form${ssrRenderAttrs(mergeProps({ class: "space-y-4" }, _attrs))}><div class="grid gap-4 md:grid-cols-5">`);
      _push(ssrRenderComponent(_sfc_main$8, {
        label: "Locale",
        "model-value": unref(form).locale,
        options: unref(optionItems)(__props.options.initiative_locales),
        clearable: false,
        "onUpdate:modelValue": chooseLocale
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$8, {
        label: "Initiative type",
        "model-value": unref(form).type,
        options: unref(optionItems)(__props.options.initiative_types),
        clearable: false,
        "onUpdate:modelValue": chooseType
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$8, {
        label: "Content type",
        "model-value": unref(form).content_type,
        options: unref(mediaTypeOptions),
        clearable: false,
        error: unref(form).errors.content_type,
        "onUpdate:modelValue": chooseContentType
      }, null, _parent));
      _push(`<div>`);
      _push(ssrRenderComponent(unref(NumberInput), {
        modelValue: unref(form).sort_order,
        "onUpdate:modelValue": ($event) => unref(form).sort_order = $event,
        label: "Sort order",
        min: 0,
        error: unref(form).errors.sort_order
      }, null, _parent));
      _push(`<p class="mt-1 text-xs text-ui-muted-foreground">Lower numbers appear first.</p></div><div class="pt-6">`);
      _push(ssrRenderComponent(unref(_sfc_main$9), {
        modelValue: unref(form).is_featured,
        "onUpdate:modelValue": ($event) => unref(form).is_featured = $event,
        label: "Featured",
        description: "Prioritizes and highlights this initiative in public surfaces."
      }, null, _parent));
      _push(`</div></div>`);
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).title,
        "onUpdate:modelValue": ($event) => unref(form).title = $event,
        label: "Title",
        error: unref(form).errors.title
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$b), {
        modelValue: unref(form).excerpt,
        "onUpdate:modelValue": ($event) => unref(form).excerpt = $event,
        label: "Excerpt",
        rows: 3,
        "helper-text": "Canonical preview text; public output is truncated without changing the stored value.",
        error: unref(form).errors.excerpt
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$b), {
        modelValue: unref(form).body,
        "onUpdate:modelValue": ($event) => unref(form).body = $event,
        label: "Body",
        rows: 4,
        "helper-text": "Extended content and defensive preview fallback only when excerpt is empty.",
        error: unref(form).errors.body
      }, null, _parent));
      _push(`<div class="grid gap-4 md:grid-cols-3">`);
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).organization,
        "onUpdate:modelValue": ($event) => unref(form).organization = $event,
        label: "Organization",
        "helper-text": "Organization or partner responsible for the initiative.",
        error: unref(form).errors.organization
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).url,
        "onUpdate:modelValue": ($event) => unref(form).url = $event,
        label: "URL",
        "helper-text": "Canonical HTTPS destination. For video content, enter a YouTube video URL.",
        error: unref(form).errors.url
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).cta_label,
        "onUpdate:modelValue": ($event) => unref(form).cta_label = $event,
        label: "CTA label",
        "helper-text": "Public button text, for example Learn more or Sign up.",
        error: unref(form).errors.cta_label
      }, null, _parent));
      _push(`</div><div class="grid gap-4 md:grid-cols-3">`);
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).preview_image_url,
        "onUpdate:modelValue": ($event) => unref(form).preview_image_url = $event,
        label: "Preview image URL",
        "helper-text": "Optional HTTPS image. YouTube thumbnails are derived when omitted.",
        error: unref(form).errors.preview_image_url
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).image_path,
        "onUpdate:modelValue": ($event) => unref(form).image_path = $event,
        label: "Local image path",
        "helper-text": "Local fallback used before the countdown image.",
        error: unref(form).errors.image_path
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).external_provider,
        "onUpdate:modelValue": ($event) => unref(form).external_provider = $event,
        label: "External provider",
        disabled: unref(form).content_type === "youtube_video",
        "helper-text": "Derived as youtube for video content.",
        error: unref(form).errors.external_provider
      }, null, _parent));
      _push(`</div><div class="grid gap-4 md:grid-cols-3">`);
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).external_id,
        "onUpdate:modelValue": ($event) => unref(form).external_id = $event,
        label: "External ID",
        disabled: unref(form).content_type === "youtube_video",
        "helper-text": "Derived from the YouTube URL for video content.",
        error: unref(form).errors.external_id
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).embed_url,
        "onUpdate:modelValue": ($event) => unref(form).embed_url = $event,
        label: "Embed URL",
        disabled: unref(form).content_type === "youtube_video",
        "helper-text": "Persisted but never rendered inline publicly. Derived for YouTube videos.",
        error: unref(form).errors.embed_url
      }, null, _parent));
      _push(`<div class="grid grid-cols-2 gap-4">`);
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).starts_at,
        "onUpdate:modelValue": ($event) => unref(form).starts_at = $event,
        label: "Starts at",
        type: "date",
        error: unref(form).errors.starts_at
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).ends_at,
        "onUpdate:modelValue": ($event) => unref(form).ends_at = $event,
        label: "Ends at",
        type: "date",
        error: unref(form).errors.ends_at
      }, null, _parent));
      _push(`</div></div>`);
      _push(ssrRenderComponent(_sfc_main$c, { compact: "" }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(Button), {
              type: "submit",
              loading: unref(form).processing
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
                loading: unref(form).processing
              }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(__props.submitLabel), 1)
                ]),
                _: 1
              }, 8, ["loading"]),
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
const _sfc_setup$7 = _sfc_main$7.setup;
_sfc_main$7.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Doomsday/InitiativeForm.vue");
  return _sfc_setup$7 ? _sfc_setup$7(props, ctx) : void 0;
};
function isPaginatedRelation(collection) {
  return typeof collection === "object" && collection !== null && !Array.isArray(collection) && Array.isArray(collection.data);
}
function relationData(collection) {
  return isPaginatedRelation(collection) ? collection.data : collection;
}
function relationRows(collection) {
  return relationData(collection).map((item) => ({ ...item }));
}
function relationMeta(collection) {
  if (isPaginatedRelation(collection)) {
    return collection.meta;
  }
  const total = collection.length;
  return {
    current_page: 1,
    last_page: 1,
    per_page: total,
    total,
    from: total > 0 ? 1 : null,
    to: total > 0 ? total : null
  };
}
function relationLinks(collection) {
  return isPaginatedRelation(collection) ? [...collection.links ?? []] : [];
}
function relationSearch(collection, searchParam) {
  var _a;
  if (isPaginatedRelation(collection)) {
    const search = (_a = collection.filters) == null ? void 0 : _a.search;
    if (typeof search === "string") {
      return search;
    }
  }
  if (typeof window === "undefined") {
    return "";
  }
  return new URL(window.location.href).searchParams.get(searchParam) ?? "";
}
function relationSort(collection, sortParam, directionParam) {
  var _a, _b;
  const defaultSort = { key: "id", direction: "asc" };
  if (isPaginatedRelation(collection)) {
    const sort2 = (_a = collection.filters) == null ? void 0 : _a.sort;
    const direction2 = (_b = collection.filters) == null ? void 0 : _b.direction;
    if (typeof sort2 === "string" && (direction2 === "asc" || direction2 === "desc")) {
      return { key: sort2, direction: direction2 };
    }
  }
  if (typeof window === "undefined") {
    return defaultSort;
  }
  const params = new URL(window.location.href).searchParams;
  const sort = params.get(sortParam);
  const direction = params.get(directionParam);
  return sort !== null && (direction === "asc" || direction === "desc") ? { key: sort, direction } : defaultSort;
}
function queryObject(params) {
  const query = {};
  for (const [key, value] of params.entries()) {
    query[key] = value;
  }
  return query;
}
function currentParams(tab) {
  const url = new URL(window.location.href);
  const params = new URLSearchParams(url.search);
  params.set("tab", tab);
  return params;
}
function updateRelationSearch(searchParam, pageParam, tab, query) {
  if (typeof window === "undefined") {
    return;
  }
  const params = currentParams(tab);
  const normalizedQuery = query.trim();
  if (normalizedQuery === "") {
    params.delete(searchParam);
  } else {
    params.set(searchParam, normalizedQuery);
  }
  params.delete(pageParam);
  router.get(window.location.pathname, queryObject(params), {
    preserveScroll: true,
    preserveState: true,
    replace: true
  });
}
function updateRelationSort(sortParam, directionParam, pageParam, tab, sort) {
  if (typeof window === "undefined") {
    return;
  }
  const params = currentParams(tab);
  params.set(sortParam, sort.key);
  params.set(directionParam, sort.direction);
  params.delete(pageParam);
  router.get(window.location.pathname, queryObject(params), {
    preserveScroll: true,
    preserveState: true,
    replace: true
  });
}
function urlWithCurrentBackofficeQuery(url, tab) {
  if (typeof window === "undefined") {
    return url;
  }
  const query = currentParams(tab).toString();
  if (query === "") {
    return url;
  }
  return `${url}${url.includes("?") ? "&" : "?"}${query}`;
}
const flatRelationTableUi = {
  toolbar: "rounded-none border-0 bg-transparent px-0 pt-0 pb-4",
  root: "space-y-0 rounded-none border-0 bg-transparent",
  header: "border-b border-ui-border bg-ui-muted/30",
  row: "hover:bg-ui-primary/20 focus-within:bg-ui-primary/20",
  footer: "border-t border-ui-border bg-transparent px-0 py-4"
};
function formatBackofficeDateTime(value) {
  if (!value) {
    return "";
  }
  return value.split(".")[0].slice(0, 19).replace("T", " ");
}
const TAB_VALUE$2 = "initiatives";
const SEARCH_PARAM$2 = "initiatives_search";
const PAGE_PARAM$2 = "initiatives_page";
const SORT_PARAM$2 = "initiatives_sort";
const DIRECTION_PARAM$2 = "initiatives_direction";
const _sfc_main$6 = /* @__PURE__ */ defineComponent({
  __name: "InitiativeManager",
  __ssrInlineRender: true,
  props: {
    basePath: {},
    countdownId: {},
    initiatives: {},
    options: {}
  },
  setup(__props) {
    const props = __props;
    const showForm = ref(false);
    const editing = ref(null);
    const pendingDelete = ref(null);
    const deletingId = ref(null);
    const formRevision = ref(0);
    const deleteForm = useSmartForm({});
    const rows = computed(() => relationRows(props.initiatives));
    const meta = computed(() => relationMeta(props.initiatives));
    const links = computed(() => relationLinks(props.initiatives));
    const searchQuery = computed(() => relationSearch(props.initiatives, SEARCH_PARAM$2));
    const sortState = computed(() => relationSort(props.initiatives, SORT_PARAM$2, DIRECTION_PARAM$2));
    const formTitle = computed(() => editing.value === null ? "Create initiative" : "Edit initiative");
    const columns = [
      { key: "id", label: "ID", sortable: true, class: "w-20 flex-none", headerClass: "w-20 flex-none" },
      { key: "title", label: "Initiative", sortable: true, class: "flex-[2]", headerClass: "flex-[2]" },
      { key: "meta", label: "Meta", class: "flex-1", headerClass: "flex-1" },
      { key: "sort_order", label: "Sort", sortable: true, class: "w-24 flex-none", headerClass: "w-24 flex-none" },
      { key: "actions", label: "Actions", class: "w-44 flex-none", headerClass: "w-44 flex-none text-right" }
    ];
    function collectionUrl() {
      return `${props.basePath}/countdowns/${props.countdownId}/initiatives`;
    }
    function itemUrl(initiative) {
      return `${collectionUrl()}/${initiative.id}`;
    }
    function contextualUrl(url) {
      return urlWithCurrentBackofficeQuery(url, TAB_VALUE$2);
    }
    function asInitiative(item) {
      return item;
    }
    function startCreate() {
      editing.value = null;
      formRevision.value += 1;
      showForm.value = true;
    }
    function startEdit(initiative) {
      editing.value = initiative;
      formRevision.value += 1;
      showForm.value = true;
    }
    function closeForm() {
      showForm.value = false;
      editing.value = null;
      formRevision.value += 1;
    }
    function handleSearch(query) {
      updateRelationSearch(SEARCH_PARAM$2, PAGE_PARAM$2, TAB_VALUE$2, query);
    }
    function handleSort(sort) {
      updateRelationSort(SORT_PARAM$2, DIRECTION_PARAM$2, PAGE_PARAM$2, TAB_VALUE$2, sort);
    }
    function displayPeriod(initiative) {
      const startsAt = formatBackofficeDateTime(initiative.starts_at);
      const endsAt = formatBackofficeDateTime(initiative.ends_at);
      if (startsAt && endsAt) {
        return `${startsAt} → ${endsAt}`;
      }
      return startsAt || endsAt || "no dates";
    }
    function confirmDestroy() {
      const initiative = pendingDelete.value;
      if (initiative === null) return;
      deletingId.value = initiative.id;
      deleteForm.delete(contextualUrl(itemUrl(initiative)), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
          var _a;
          pendingDelete.value = null;
          if (((_a = editing.value) == null ? void 0 : _a.id) === initiative.id) {
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
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "space-y-4" }, _attrs))}><div><p class="font-semibold">Initiatives</p><p class="text-sm text-ui-muted-foreground">Create, edit and delete action initiatives.</p></div>`);
      _push(ssrRenderComponent(unref(_sfc_main$d), {
        data: rows.value,
        columns,
        meta: meta.value,
        links: links.value,
        "item-key": "id",
        searchable: "",
        "search-placeholder": "Search initiatives...",
        "search-query": searchQuery.value,
        sort: sortState.value,
        "enable-row-click": "",
        density: "comfortable",
        ui: unref(flatRelationTableUi),
        onSearch: handleSearch,
        onSortChange: handleSort,
        onRowClick: (item) => startEdit(asInitiative(item))
      }, {
        toolbar: withCtx(({ searchQuery: tableSearchQuery, updateSearch }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$g), {
              "model-value": String(tableSearchQuery),
              placeholder: "Search initiatives...",
              ui: { root: "w-full md:w-96" },
              "onUpdate:modelValue": updateSearch
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Button), {
              icon: unref(Plus),
              size: "sm",
              onClick: startCreate
            }, {
              default: withCtx((_, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Add initiative`);
                } else {
                  return [
                    createTextVNode("Add initiative")
                  ];
                }
              }),
              _: 2
            }, _parent2, _scopeId));
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between" }, [
                createVNode(unref(_sfc_main$g), {
                  "model-value": String(tableSearchQuery),
                  placeholder: "Search initiatives...",
                  ui: { root: "w-full md:w-96" },
                  "onUpdate:modelValue": updateSearch
                }, null, 8, ["model-value", "onUpdate:modelValue"]),
                createVNode(unref(Button), {
                  icon: unref(Plus),
                  size: "sm",
                  onClick: startCreate
                }, {
                  default: withCtx(() => [
                    createTextVNode("Add initiative")
                  ]),
                  _: 1
                }, 8, ["icon"])
              ])
            ];
          }
        }),
        "cell-id": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<span class="font-mono text-xs text-ui-muted-foreground"${_scopeId}>#${ssrInterpolate(item.id)}</span>`);
          } else {
            return [
              createVNode("span", { class: "font-mono text-xs text-ui-muted-foreground" }, "#" + toDisplayString(item.id), 1)
            ];
          }
        }),
        "cell-title": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="font-medium"${_scopeId}>${ssrInterpolate(item.title)}</div><div class="text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(item.excerpt)}</div>`);
          } else {
            return [
              createVNode("div", { class: "font-medium" }, toDisplayString(item.title), 1),
              createVNode("div", { class: "text-sm text-ui-muted-foreground" }, toDisplayString(item.excerpt), 1)
            ];
          }
        }),
        "cell-meta": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex flex-wrap gap-2"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$f), {
              label: String(item.locale),
              variant: "soft"
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(_sfc_main$f), {
              label: String(item.type),
              variant: "soft"
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(_sfc_main$f), {
              label: String(item.content_type ?? "article"),
              variant: "soft"
            }, null, _parent2, _scopeId));
            if (item.external_provider) {
              _push2(ssrRenderComponent(unref(_sfc_main$f), {
                label: String(item.external_provider),
                variant: "soft"
              }, null, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
            _push2(ssrRenderComponent(unref(_sfc_main$f), {
              label: item.is_featured ? "featured" : "standard",
              color: item.is_featured ? "success" : "secondary",
              variant: "soft"
            }, null, _parent2, _scopeId));
            _push2(`</div><div class="mt-1 text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(displayPeriod(asInitiative(item)))}</div>`);
          } else {
            return [
              createVNode("div", { class: "flex flex-wrap gap-2" }, [
                createVNode(unref(_sfc_main$f), {
                  label: String(item.locale),
                  variant: "soft"
                }, null, 8, ["label"]),
                createVNode(unref(_sfc_main$f), {
                  label: String(item.type),
                  variant: "soft"
                }, null, 8, ["label"]),
                createVNode(unref(_sfc_main$f), {
                  label: String(item.content_type ?? "article"),
                  variant: "soft"
                }, null, 8, ["label"]),
                item.external_provider ? (openBlock(), createBlock(unref(_sfc_main$f), {
                  key: 0,
                  label: String(item.external_provider),
                  variant: "soft"
                }, null, 8, ["label"])) : createCommentVNode("", true),
                createVNode(unref(_sfc_main$f), {
                  label: item.is_featured ? "featured" : "standard",
                  color: item.is_featured ? "success" : "secondary",
                  variant: "soft"
                }, null, 8, ["label", "color"])
              ]),
              createVNode("div", { class: "mt-1 text-sm text-ui-muted-foreground" }, toDisplayString(displayPeriod(asInitiative(item))), 1)
            ];
          }
        }),
        "cell-sort_order": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<span class="font-mono text-xs text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(item.sort_order)}</span>`);
          } else {
            return [
              createVNode("span", { class: "font-mono text-xs text-ui-muted-foreground" }, toDisplayString(item.sort_order), 1)
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
              onClick: ($event) => startEdit(asInitiative(item))
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
              onClick: ($event) => pendingDelete.value = asInitiative(item)
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
                  onClick: ($event) => startEdit(asInitiative(item))
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
                  onClick: ($event) => pendingDelete.value = asInitiative(item)
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
            _push2(ssrRenderComponent(unref(_sfc_main$e), {
              title: "No initiatives",
              description: "No initiatives match this search.",
              icon: unref(Plus)
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$e), {
                title: "No initiatives",
                description: "No initiatives match this search.",
                icon: unref(Plus)
              }, null, 8, ["icon"])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(Modal), {
        show: showForm.value,
        title: formTitle.value,
        "max-width": "3xl",
        onClose: closeForm
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          var _a2, _b;
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$7, {
              key: `${((_a2 = editing.value) == null ? void 0 : _a2.id) ?? "new"}-${formRevision.value}`,
              options: __props.options,
              initiative: editing.value ?? void 0,
              "submit-url": contextualUrl(editing.value ? itemUrl(editing.value) : collectionUrl()),
              method: editing.value ? "put" : "post",
              "submit-label": editing.value ? "Save initiative" : "Create initiative",
              onSaved: closeForm,
              onCancel: closeForm
            }, null, _parent2, _scopeId));
          } else {
            return [
              (openBlock(), createBlock(_sfc_main$7, {
                key: `${((_b = editing.value) == null ? void 0 : _b.id) ?? "new"}-${formRevision.value}`,
                options: __props.options,
                initiative: editing.value ?? void 0,
                "submit-url": contextualUrl(editing.value ? itemUrl(editing.value) : collectionUrl()),
                method: editing.value ? "put" : "post",
                "submit-label": editing.value ? "Save initiative" : "Create initiative",
                onSaved: closeForm,
                onCancel: closeForm
              }, null, 8, ["options", "initiative", "submit-url", "method", "submit-label"]))
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$h, {
        show: pendingDelete.value !== null,
        title: "Delete initiative",
        description: `Delete initiative ${((_a = pendingDelete.value) == null ? void 0 : _a.title) ?? ""}?`,
        loading: unref(deleteForm).processing,
        onClose: ($event) => pendingDelete.value = null,
        onConfirm: confirmDestroy
      }, null, _parent));
      _push(`</section>`);
    };
  }
});
const _sfc_setup$6 = _sfc_main$6.setup;
_sfc_main$6.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Doomsday/InitiativeManager.vue");
  return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
const _sfc_main$5 = /* @__PURE__ */ defineComponent({
  __name: "NewsForm",
  __ssrInlineRender: true,
  props: {
    options: {},
    submitUrl: {},
    method: {},
    submitLabel: {},
    news: {}
  },
  emits: ["saved", "cancel"],
  setup(__props, { emit: __emit }) {
    var _a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k, _l, _m, _n;
    const props = __props;
    const emit = __emit;
    const mediaTypeOptions = optionItems(["article", "youtube_video"]);
    const form = useSmartForm({ ...SaveNewsDataRules });
    form.fill({
      locale: ((_a = props.news) == null ? void 0 : _a.locale) ?? first(props.options.news_locales, "en"),
      title: ((_b = props.news) == null ? void 0 : _b.title) ?? "",
      excerpt: ((_c = props.news) == null ? void 0 : _c.excerpt) ?? "",
      content_type: ((_d = props.news) == null ? void 0 : _d.content_type) ?? "article",
      source_name: ((_e = props.news) == null ? void 0 : _e.source_name) ?? null,
      source_url: ((_f = props.news) == null ? void 0 : _f.source_url) ?? null,
      preview_image_url: ((_g = props.news) == null ? void 0 : _g.preview_image_url) ?? null,
      embed_url: ((_h = props.news) == null ? void 0 : _h.embed_url) ?? null,
      external_provider: ((_i = props.news) == null ? void 0 : _i.external_provider) ?? null,
      external_id: ((_j = props.news) == null ? void 0 : _j.external_id) ?? null,
      image_path: ((_k = props.news) == null ? void 0 : _k.image_path) ?? null,
      published_at: isoDate((_l = props.news) == null ? void 0 : _l.published_at),
      sort_order: ((_m = props.news) == null ? void 0 : _m.sort_order) ?? 0,
      is_featured: ((_n = props.news) == null ? void 0 : _n.is_featured) ?? false
    });
    function chooseLocale(value) {
      if (typeof value === "string") {
        form.locale = value;
      }
    }
    function chooseContentType(value) {
      if (typeof value === "string") {
        form.content_type = value;
      }
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<form${ssrRenderAttrs(mergeProps({ class: "space-y-4" }, _attrs))}><div class="grid gap-4 md:grid-cols-5">`);
      _push(ssrRenderComponent(_sfc_main$8, {
        label: "Locale",
        "model-value": unref(form).locale,
        options: unref(optionItems)(__props.options.news_locales),
        clearable: false,
        "onUpdate:modelValue": chooseLocale
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$8, {
        label: "Content type",
        "model-value": unref(form).content_type,
        options: unref(mediaTypeOptions),
        clearable: false,
        error: unref(form).errors.content_type,
        "onUpdate:modelValue": chooseContentType
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).published_at,
        "onUpdate:modelValue": ($event) => unref(form).published_at = $event,
        label: "Published at",
        type: "date",
        "helper-text": "Optional publication date shown in backoffice without microseconds.",
        error: unref(form).errors.published_at
      }, null, _parent));
      _push(`<div>`);
      _push(ssrRenderComponent(unref(NumberInput), {
        modelValue: unref(form).sort_order,
        "onUpdate:modelValue": ($event) => unref(form).sort_order = $event,
        label: "Sort order",
        min: 0,
        error: unref(form).errors.sort_order
      }, null, _parent));
      _push(`<p class="mt-1 text-xs text-ui-muted-foreground">Lower numbers appear first after publication date ordering.</p></div><div class="pt-6">`);
      _push(ssrRenderComponent(unref(_sfc_main$9), {
        modelValue: unref(form).is_featured,
        "onUpdate:modelValue": ($event) => unref(form).is_featured = $event,
        label: "Featured",
        description: "Prioritizes and highlights this news item in public surfaces."
      }, null, _parent));
      _push(`</div></div>`);
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).title,
        "onUpdate:modelValue": ($event) => unref(form).title = $event,
        label: "Title",
        error: unref(form).errors.title
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$b), {
        modelValue: unref(form).excerpt,
        "onUpdate:modelValue": ($event) => unref(form).excerpt = $event,
        label: "Excerpt",
        rows: 3,
        "helper-text": "Public previews are truncated by the configured content limit; the stored text remains complete.",
        error: unref(form).errors.excerpt
      }, null, _parent));
      _push(`<div class="grid gap-4 md:grid-cols-3">`);
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).source_name,
        "onUpdate:modelValue": ($event) => unref(form).source_name = $event,
        label: "Source name",
        "helper-text": "Publisher or outlet shown with the article.",
        error: unref(form).errors.source_name
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).source_url,
        "onUpdate:modelValue": ($event) => unref(form).source_url = $event,
        label: "Source URL",
        "helper-text": "Use an HTTPS article URL or YouTube video URL.",
        error: unref(form).errors.source_url
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).preview_image_url,
        "onUpdate:modelValue": ($event) => unref(form).preview_image_url = $event,
        label: "Preview image URL",
        "helper-text": "Optional HTTPS image. YouTube thumbnails are derived when omitted.",
        error: unref(form).errors.preview_image_url
      }, null, _parent));
      _push(`</div><div class="grid gap-4 md:grid-cols-3">`);
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).image_path,
        "onUpdate:modelValue": ($event) => unref(form).image_path = $event,
        label: "Local image path",
        "helper-text": "Local fallback path used when the remote preview is missing or invalid.",
        error: unref(form).errors.image_path
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).external_provider,
        "onUpdate:modelValue": ($event) => unref(form).external_provider = $event,
        label: "External provider",
        disabled: unref(form).content_type === "youtube_video",
        "helper-text": "Derived as youtube for video content.",
        error: unref(form).errors.external_provider
      }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).external_id,
        "onUpdate:modelValue": ($event) => unref(form).external_id = $event,
        label: "External ID",
        disabled: unref(form).content_type === "youtube_video",
        "helper-text": "Derived from the YouTube URL for video content.",
        error: unref(form).errors.external_id
      }, null, _parent));
      _push(`</div>`);
      _push(ssrRenderComponent(unref(_sfc_main$a), {
        modelValue: unref(form).embed_url,
        "onUpdate:modelValue": ($event) => unref(form).embed_url = $event,
        label: "Embed URL",
        disabled: unref(form).content_type === "youtube_video",
        "helper-text": "Persisted for future use, but never rendered inline on the public page. Derived for YouTube videos.",
        error: unref(form).errors.embed_url
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$c, { compact: "" }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(Button), {
              type: "submit",
              loading: unref(form).processing
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
                loading: unref(form).processing
              }, {
                default: withCtx(() => [
                  createTextVNode(toDisplayString(__props.submitLabel), 1)
                ]),
                _: 1
              }, 8, ["loading"]),
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
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Doomsday/NewsForm.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const TAB_VALUE$1 = "news";
const SEARCH_PARAM$1 = "news_search";
const PAGE_PARAM$1 = "news_page";
const SORT_PARAM$1 = "news_sort";
const DIRECTION_PARAM$1 = "news_direction";
const _sfc_main$4 = /* @__PURE__ */ defineComponent({
  __name: "NewsManager",
  __ssrInlineRender: true,
  props: {
    basePath: {},
    countdownId: {},
    news: {},
    options: {}
  },
  setup(__props) {
    const props = __props;
    const showForm = ref(false);
    const editing = ref(null);
    const pendingDelete = ref(null);
    const deletingId = ref(null);
    const formRevision = ref(0);
    const deleteForm = useSmartForm({});
    const rows = computed(() => relationRows(props.news));
    const meta = computed(() => relationMeta(props.news));
    const links = computed(() => relationLinks(props.news));
    const searchQuery = computed(() => relationSearch(props.news, SEARCH_PARAM$1));
    const sortState = computed(() => relationSort(props.news, SORT_PARAM$1, DIRECTION_PARAM$1));
    const formTitle = computed(() => editing.value === null ? "Create news" : "Edit news");
    const columns = [
      { key: "id", label: "ID", sortable: true, class: "w-20 flex-none", headerClass: "w-20 flex-none" },
      { key: "title", label: "News", sortable: true, class: "flex-[2]", headerClass: "flex-[2]" },
      { key: "meta", label: "Meta", class: "flex-1", headerClass: "flex-1" },
      { key: "sort_order", label: "Sort", sortable: true, class: "w-24 flex-none", headerClass: "w-24 flex-none" },
      { key: "actions", label: "Actions", class: "w-44 flex-none", headerClass: "w-44 flex-none text-right" }
    ];
    function collectionUrl() {
      return `${props.basePath}/countdowns/${props.countdownId}/news`;
    }
    function itemUrl(news) {
      return `${collectionUrl()}/${news.id}`;
    }
    function contextualUrl(url) {
      return urlWithCurrentBackofficeQuery(url, TAB_VALUE$1);
    }
    function asNews(item) {
      return item;
    }
    function startCreate() {
      editing.value = null;
      formRevision.value += 1;
      showForm.value = true;
    }
    function startEdit(news) {
      editing.value = news;
      formRevision.value += 1;
      showForm.value = true;
    }
    function closeForm() {
      showForm.value = false;
      editing.value = null;
      formRevision.value += 1;
    }
    function handleSearch(query) {
      updateRelationSearch(SEARCH_PARAM$1, PAGE_PARAM$1, TAB_VALUE$1, query);
    }
    function handleSort(sort) {
      updateRelationSort(SORT_PARAM$1, DIRECTION_PARAM$1, PAGE_PARAM$1, TAB_VALUE$1, sort);
    }
    function displayPublishedAt(value) {
      return formatBackofficeDateTime(value) || "not published";
    }
    function confirmDestroy() {
      const news = pendingDelete.value;
      if (news === null) return;
      deletingId.value = news.id;
      deleteForm.delete(contextualUrl(itemUrl(news)), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
          var _a;
          pendingDelete.value = null;
          if (((_a = editing.value) == null ? void 0 : _a.id) === news.id) {
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
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "space-y-4" }, _attrs))}><div><p class="font-semibold">News</p><p class="text-sm text-ui-muted-foreground">Create, edit and delete localized countdown news.</p></div>`);
      _push(ssrRenderComponent(unref(_sfc_main$d), {
        data: rows.value,
        columns,
        meta: meta.value,
        links: links.value,
        "item-key": "id",
        searchable: "",
        "search-placeholder": "Search news...",
        "search-query": searchQuery.value,
        sort: sortState.value,
        "enable-row-click": "",
        density: "comfortable",
        ui: unref(flatRelationTableUi),
        onSearch: handleSearch,
        onSortChange: handleSort,
        onRowClick: (item) => startEdit(asNews(item))
      }, {
        toolbar: withCtx(({ searchQuery: tableSearchQuery, updateSearch }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$g), {
              "model-value": String(tableSearchQuery),
              placeholder: "Search news...",
              ui: { root: "w-full md:w-96" },
              "onUpdate:modelValue": updateSearch
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Button), {
              icon: unref(Plus),
              size: "sm",
              onClick: startCreate
            }, {
              default: withCtx((_, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Add news`);
                } else {
                  return [
                    createTextVNode("Add news")
                  ];
                }
              }),
              _: 2
            }, _parent2, _scopeId));
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between" }, [
                createVNode(unref(_sfc_main$g), {
                  "model-value": String(tableSearchQuery),
                  placeholder: "Search news...",
                  ui: { root: "w-full md:w-96" },
                  "onUpdate:modelValue": updateSearch
                }, null, 8, ["model-value", "onUpdate:modelValue"]),
                createVNode(unref(Button), {
                  icon: unref(Plus),
                  size: "sm",
                  onClick: startCreate
                }, {
                  default: withCtx(() => [
                    createTextVNode("Add news")
                  ]),
                  _: 1
                }, 8, ["icon"])
              ])
            ];
          }
        }),
        "cell-id": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<span class="font-mono text-xs text-ui-muted-foreground"${_scopeId}>#${ssrInterpolate(item.id)}</span>`);
          } else {
            return [
              createVNode("span", { class: "font-mono text-xs text-ui-muted-foreground" }, "#" + toDisplayString(item.id), 1)
            ];
          }
        }),
        "cell-title": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="font-medium"${_scopeId}>${ssrInterpolate(item.title)}</div><div class="text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(item.excerpt)}</div>`);
          } else {
            return [
              createVNode("div", { class: "font-medium" }, toDisplayString(item.title), 1),
              createVNode("div", { class: "text-sm text-ui-muted-foreground" }, toDisplayString(item.excerpt), 1)
            ];
          }
        }),
        "cell-meta": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex flex-wrap gap-2"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$f), {
              label: String(item.locale),
              variant: "soft"
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(_sfc_main$f), {
              label: String(item.content_type ?? "article"),
              variant: "soft"
            }, null, _parent2, _scopeId));
            if (item.external_provider) {
              _push2(ssrRenderComponent(unref(_sfc_main$f), {
                label: String(item.external_provider),
                variant: "soft"
              }, null, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
            _push2(ssrRenderComponent(unref(_sfc_main$f), {
              label: item.is_featured ? "featured" : "standard",
              color: item.is_featured ? "success" : "secondary",
              variant: "soft"
            }, null, _parent2, _scopeId));
            _push2(`</div><div class="mt-1 text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(displayPublishedAt(String(item.published_at ?? "") || null))}</div>`);
          } else {
            return [
              createVNode("div", { class: "flex flex-wrap gap-2" }, [
                createVNode(unref(_sfc_main$f), {
                  label: String(item.locale),
                  variant: "soft"
                }, null, 8, ["label"]),
                createVNode(unref(_sfc_main$f), {
                  label: String(item.content_type ?? "article"),
                  variant: "soft"
                }, null, 8, ["label"]),
                item.external_provider ? (openBlock(), createBlock(unref(_sfc_main$f), {
                  key: 0,
                  label: String(item.external_provider),
                  variant: "soft"
                }, null, 8, ["label"])) : createCommentVNode("", true),
                createVNode(unref(_sfc_main$f), {
                  label: item.is_featured ? "featured" : "standard",
                  color: item.is_featured ? "success" : "secondary",
                  variant: "soft"
                }, null, 8, ["label", "color"])
              ]),
              createVNode("div", { class: "mt-1 text-sm text-ui-muted-foreground" }, toDisplayString(displayPublishedAt(String(item.published_at ?? "") || null)), 1)
            ];
          }
        }),
        "cell-sort_order": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<span class="font-mono text-xs text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(item.sort_order)}</span>`);
          } else {
            return [
              createVNode("span", { class: "font-mono text-xs text-ui-muted-foreground" }, toDisplayString(item.sort_order), 1)
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
              onClick: ($event) => startEdit(asNews(item))
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
              onClick: ($event) => pendingDelete.value = asNews(item)
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
                  onClick: ($event) => startEdit(asNews(item))
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
                  onClick: ($event) => pendingDelete.value = asNews(item)
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
            _push2(ssrRenderComponent(unref(_sfc_main$e), {
              title: "No news",
              description: "No news items match this search.",
              icon: unref(Plus)
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$e), {
                title: "No news",
                description: "No news items match this search.",
                icon: unref(Plus)
              }, null, 8, ["icon"])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(Modal), {
        show: showForm.value,
        title: formTitle.value,
        "max-width": "3xl",
        onClose: closeForm
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          var _a2, _b;
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$5, {
              key: `${((_a2 = editing.value) == null ? void 0 : _a2.id) ?? "new"}-${formRevision.value}`,
              options: __props.options,
              news: editing.value ?? void 0,
              "submit-url": contextualUrl(editing.value ? itemUrl(editing.value) : collectionUrl()),
              method: editing.value ? "put" : "post",
              "submit-label": editing.value ? "Save news" : "Create news",
              onSaved: closeForm,
              onCancel: closeForm
            }, null, _parent2, _scopeId));
          } else {
            return [
              (openBlock(), createBlock(_sfc_main$5, {
                key: `${((_b = editing.value) == null ? void 0 : _b.id) ?? "new"}-${formRevision.value}`,
                options: __props.options,
                news: editing.value ?? void 0,
                "submit-url": contextualUrl(editing.value ? itemUrl(editing.value) : collectionUrl()),
                method: editing.value ? "put" : "post",
                "submit-label": editing.value ? "Save news" : "Create news",
                onSaved: closeForm,
                onCancel: closeForm
              }, null, 8, ["options", "news", "submit-url", "method", "submit-label"]))
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$h, {
        show: pendingDelete.value !== null,
        title: "Delete news",
        description: `Delete news ${((_a = pendingDelete.value) == null ? void 0 : _a.title) ?? ""}?`,
        loading: unref(deleteForm).processing,
        onClose: ($event) => pendingDelete.value = null,
        onConfirm: confirmDestroy
      }, null, _parent));
      _push(`</section>`);
    };
  }
});
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Doomsday/NewsManager.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const COUNTDOWN_TAB = "visualizations";
const PROJECTION_TAB = "projections";
const COUNTDOWN_SEARCH_PARAM = "visualizations_search";
const COUNTDOWN_PAGE_PARAM = "visualizations_page";
const COUNTDOWN_SORT_PARAM = "visualizations_sort";
const COUNTDOWN_DIRECTION_PARAM = "visualizations_direction";
const PROJECTION_SEARCH_PARAM = "projection_visualizations_search";
const PROJECTION_PAGE_PARAM = "projection_visualizations_page";
const _sfc_main$3 = /* @__PURE__ */ defineComponent({
  __name: "VisualizationManager",
  __ssrInlineRender: true,
  props: {
    basePath: {},
    countdownId: {},
    projectionId: {},
    visualizations: {},
    options: {},
    title: {},
    searchable: { type: Boolean, default: true }
  },
  setup(__props) {
    const props = __props;
    const pendingDelete = ref(null);
    const deletingId = ref(null);
    const expandedVisualizationRows = ref([]);
    const deleteForm = useSmartForm({});
    const isCountdownRelation = computed(() => props.projectionId === void 0);
    const tabValue = computed(() => isCountdownRelation.value ? COUNTDOWN_TAB : PROJECTION_TAB);
    const searchParam = computed(() => isCountdownRelation.value ? COUNTDOWN_SEARCH_PARAM : PROJECTION_SEARCH_PARAM);
    const pageParam = computed(() => isCountdownRelation.value ? COUNTDOWN_PAGE_PARAM : PROJECTION_PAGE_PARAM);
    const sortState = computed(() => relationSort(props.visualizations, COUNTDOWN_SORT_PARAM, COUNTDOWN_DIRECTION_PARAM));
    const rows = computed(() => relationRows(props.visualizations));
    const meta = computed(() => relationMeta(props.visualizations));
    const links = computed(() => relationLinks(props.visualizations));
    const searchQuery = computed(() => props.searchable ? relationSearch(props.visualizations, searchParam.value) : "");
    const columns = computed(() => [
      { key: "id", label: "ID", sortable: isCountdownRelation.value, class: "w-20 flex-none", headerClass: "w-20 flex-none" },
      { key: "title", label: "Visualization", sortable: isCountdownRelation.value, class: "flex-[2]", headerClass: "flex-[2]" },
      { key: "key", label: "Key / type", sortable: isCountdownRelation.value, class: "flex-1", headerClass: "flex-1" },
      { key: "sort_order", label: "Sort", sortable: isCountdownRelation.value, class: "w-24 flex-none", headerClass: "w-24 flex-none" },
      { key: "actions", label: "Actions", class: "w-44 flex-none", headerClass: "w-44 flex-none text-right" }
    ]);
    function collectionUrl() {
      if (props.projectionId !== void 0) {
        return `${props.basePath}/countdowns/${props.countdownId}/projections/${props.projectionId}/visualizations`;
      }
      return `${props.basePath}/countdowns/${props.countdownId}/visualizations`;
    }
    function itemUrl(visualization) {
      return `${collectionUrl()}/${visualization.id}`;
    }
    function createUrl() {
      return `${collectionUrl()}/create`;
    }
    function editUrl(visualization) {
      return `${itemUrl(visualization)}/edit`;
    }
    function contextualUrl(url) {
      return urlWithCurrentBackofficeQuery(url, tabValue.value);
    }
    function asVisualization(item) {
      return item;
    }
    function startCreate() {
      router.visit(contextualUrl(createUrl()), { preserveScroll: true, preserveState: true });
    }
    function startEdit(visualization) {
      router.visit(contextualUrl(editUrl(visualization)), { preserveScroll: true, preserveState: true });
    }
    function handleSearch(query) {
      updateRelationSearch(searchParam.value, pageParam.value, tabValue.value, query);
    }
    function handleSort(sort) {
      if (isCountdownRelation.value) {
        updateRelationSort(COUNTDOWN_SORT_PARAM, COUNTDOWN_DIRECTION_PARAM, COUNTDOWN_PAGE_PARAM, COUNTDOWN_TAB, sort);
      }
    }
    function toggleVisualizationRow(item) {
      const visualization = asVisualization(item);
      expandedVisualizationRows.value = expandedVisualizationRows.value.includes(visualization.id) ? expandedVisualizationRows.value.filter((id) => id !== visualization.id) : [...expandedVisualizationRows.value, visualization.id];
    }
    function confirmDestroy() {
      const visualization = pendingDelete.value;
      if (visualization === null) return;
      deletingId.value = visualization.id;
      deleteForm.delete(contextualUrl(itemUrl(visualization)), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
          pendingDelete.value = null;
        },
        onFinish: () => {
          deletingId.value = null;
        }
      });
    }
    return (_ctx, _push, _parent, _attrs) => {
      var _a;
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "space-y-4" }, _attrs))}><div class="flex flex-wrap items-center justify-between gap-3"><div><p class="font-semibold">${ssrInterpolate(__props.title)}</p><p class="text-sm text-ui-muted-foreground">Create, edit, delete and preview line/area/KPI payloads. Rows are collapsed until opened.</p></div>`);
      if (!__props.searchable) {
        _push(ssrRenderComponent(unref(Button), {
          icon: unref(Plus),
          size: "sm",
          onClick: startCreate
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`Add visualization`);
            } else {
              return [
                createTextVNode("Add visualization")
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
      _push(ssrRenderComponent(unref(_sfc_main$d), {
        "expanded-rows": expandedVisualizationRows.value,
        "onUpdate:expandedRows": ($event) => expandedVisualizationRows.value = $event,
        data: rows.value,
        columns: columns.value,
        meta: meta.value,
        links: links.value,
        "item-key": "id",
        searchable: __props.searchable,
        "search-placeholder": "Search visualizations...",
        "search-query": searchQuery.value,
        sort: sortState.value,
        expandable: "",
        "enable-row-click": "",
        density: "comfortable",
        ui: unref(flatRelationTableUi),
        onSearch: handleSearch,
        onSortChange: handleSort,
        onRowClick: (item) => toggleVisualizationRow(item)
      }, {
        toolbar: withCtx(({ searchQuery: tableSearchQuery, updateSearch }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$g), {
              "model-value": String(tableSearchQuery),
              placeholder: "Search visualizations...",
              ui: { root: "w-full md:w-96" },
              "onUpdate:modelValue": updateSearch
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Button), {
              icon: unref(Plus),
              size: "sm",
              onClick: startCreate
            }, {
              default: withCtx((_, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Add visualization`);
                } else {
                  return [
                    createTextVNode("Add visualization")
                  ];
                }
              }),
              _: 2
            }, _parent2, _scopeId));
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between" }, [
                createVNode(unref(_sfc_main$g), {
                  "model-value": String(tableSearchQuery),
                  placeholder: "Search visualizations...",
                  ui: { root: "w-full md:w-96" },
                  "onUpdate:modelValue": updateSearch
                }, null, 8, ["model-value", "onUpdate:modelValue"]),
                createVNode(unref(Button), {
                  icon: unref(Plus),
                  size: "sm",
                  onClick: startCreate
                }, {
                  default: withCtx(() => [
                    createTextVNode("Add visualization")
                  ]),
                  _: 1
                }, 8, ["icon"])
              ])
            ];
          }
        }),
        "cell-id": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<span class="font-mono text-xs text-ui-muted-foreground"${_scopeId}>#${ssrInterpolate(item.id)}</span>`);
          } else {
            return [
              createVNode("span", { class: "font-mono text-xs text-ui-muted-foreground" }, "#" + toDisplayString(item.id), 1)
            ];
          }
        }),
        "cell-title": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          var _a2, _b;
          if (_push2) {
            _push2(`<div class="font-medium"${_scopeId}>${ssrInterpolate(((_a2 = item.title) == null ? void 0 : _a2.en) ?? item.key)}</div><div class="text-sm text-ui-muted-foreground"${_scopeId}>schema ${ssrInterpolate(item.schema_version)}</div>`);
          } else {
            return [
              createVNode("div", { class: "font-medium" }, toDisplayString(((_b = item.title) == null ? void 0 : _b.en) ?? item.key), 1),
              createVNode("div", { class: "text-sm text-ui-muted-foreground" }, "schema " + toDisplayString(item.schema_version), 1)
            ];
          }
        }),
        "cell-key": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(item.key)}</div>`);
            _push2(ssrRenderComponent(unref(_sfc_main$f), {
              label: String(item.type),
              variant: "soft"
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode("div", { class: "text-sm text-ui-muted-foreground" }, toDisplayString(item.key), 1),
              createVNode(unref(_sfc_main$f), {
                label: String(item.type),
                variant: "soft"
              }, null, 8, ["label"])
            ];
          }
        }),
        "cell-sort_order": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<span class="font-mono text-xs text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(item.sort_order)}</span>`);
          } else {
            return [
              createVNode("span", { class: "font-mono text-xs text-ui-muted-foreground" }, toDisplayString(item.sort_order), 1)
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
              onClick: ($event) => startEdit(asVisualization(item))
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
              onClick: ($event) => pendingDelete.value = asVisualization(item)
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
                  onClick: ($event) => startEdit(asVisualization(item))
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
                  onClick: ($event) => pendingDelete.value = asVisualization(item)
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
        expansion: withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$i, {
              visualization: asVisualization(item)
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$i, {
                visualization: asVisualization(item)
              }, null, 8, ["visualization"])
            ];
          }
        }),
        empty: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$e), {
              title: "No visualizations",
              description: "No visualizations match this search.",
              icon: unref(Plus)
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$e), {
                title: "No visualizations",
                description: "No visualizations match this search.",
                icon: unref(Plus)
              }, null, 8, ["icon"])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$h, {
        show: pendingDelete.value !== null,
        title: "Delete visualization",
        description: `Delete visualization ${((_a = pendingDelete.value) == null ? void 0 : _a.key) ?? ""}?`,
        loading: unref(deleteForm).processing,
        onClose: ($event) => pendingDelete.value = null,
        onConfirm: confirmDestroy
      }, null, _parent));
      _push(`</section>`);
    };
  }
});
const _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Doomsday/VisualizationManager.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const TAB_VALUE = "projections";
const SEARCH_PARAM = "projections_search";
const PAGE_PARAM = "projections_page";
const SORT_PARAM = "projections_sort";
const DIRECTION_PARAM = "projections_direction";
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "ProjectionManager",
  __ssrInlineRender: true,
  props: {
    basePath: {},
    countdownId: {},
    projections: {},
    options: {}
  },
  setup(__props) {
    const props = __props;
    const pendingDelete = ref(null);
    const deletingId = ref(null);
    const expandedProjectionRows = ref([]);
    const deleteForm = useSmartForm({});
    const rows = computed(() => relationRows(props.projections));
    const meta = computed(() => relationMeta(props.projections));
    const links = computed(() => relationLinks(props.projections));
    const searchQuery = computed(() => relationSearch(props.projections, SEARCH_PARAM));
    const sortState = computed(() => relationSort(props.projections, SORT_PARAM, DIRECTION_PARAM));
    const columns = [
      { key: "id", label: "ID", sortable: true, class: "w-20 flex-none", headerClass: "w-20 flex-none" },
      { key: "title", label: "Projection", sortable: true, class: "flex-[2]", headerClass: "flex-[2]" },
      { key: "scores", label: "Scores", class: "flex-1", headerClass: "flex-1" },
      { key: "sort_order", label: "Sort", sortable: true, class: "w-24 flex-none", headerClass: "w-24 flex-none" },
      { key: "actions", label: "Actions", class: "w-44 flex-none", headerClass: "w-44 flex-none text-right" }
    ];
    function collectionUrl() {
      return `${props.basePath}/countdowns/${props.countdownId}/projections`;
    }
    function itemUrl(projection) {
      return `${collectionUrl()}/${projection.id}`;
    }
    function createUrl() {
      return `${collectionUrl()}/create`;
    }
    function editUrl(projection) {
      return `${itemUrl(projection)}/edit`;
    }
    function contextualUrl(url) {
      return urlWithCurrentBackofficeQuery(url, TAB_VALUE);
    }
    function asProjection(item) {
      return item;
    }
    function startCreate() {
      router.visit(contextualUrl(createUrl()), { preserveScroll: true, preserveState: true });
    }
    function startEdit(projection) {
      router.visit(contextualUrl(editUrl(projection)), { preserveScroll: true, preserveState: true });
    }
    function handleSearch(query) {
      updateRelationSearch(SEARCH_PARAM, PAGE_PARAM, TAB_VALUE, query);
    }
    function handleSort(sort) {
      updateRelationSort(SORT_PARAM, DIRECTION_PARAM, PAGE_PARAM, TAB_VALUE, sort);
    }
    function displayTargetDate(value) {
      return formatBackofficeDateTime(typeof value === "string" ? value : null) || "no target date";
    }
    function toggleProjectionRow(item) {
      const projection = asProjection(item);
      expandedProjectionRows.value = expandedProjectionRows.value.includes(projection.id) ? expandedProjectionRows.value.filter((id) => id !== projection.id) : [...expandedProjectionRows.value, projection.id];
    }
    function confirmDestroy() {
      const projection = pendingDelete.value;
      if (projection === null) return;
      deletingId.value = projection.id;
      deleteForm.delete(contextualUrl(itemUrl(projection)), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
          pendingDelete.value = null;
        },
        onFinish: () => {
          deletingId.value = null;
        }
      });
    }
    return (_ctx, _push, _parent, _attrs) => {
      var _a;
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "space-y-4" }, _attrs))}><div><p class="font-semibold">Projections</p><p class="text-sm text-ui-muted-foreground">Manage scenario projections and expand rows for nested projection visualizations.</p></div>`);
      _push(ssrRenderComponent(unref(_sfc_main$d), {
        "expanded-rows": expandedProjectionRows.value,
        "onUpdate:expandedRows": ($event) => expandedProjectionRows.value = $event,
        data: rows.value,
        columns,
        meta: meta.value,
        links: links.value,
        "item-key": "id",
        searchable: "",
        "search-placeholder": "Search projections...",
        "search-query": searchQuery.value,
        sort: sortState.value,
        expandable: "",
        "enable-row-click": "",
        density: "comfortable",
        ui: unref(flatRelationTableUi),
        onSearch: handleSearch,
        onSortChange: handleSort,
        onRowClick: (item) => toggleProjectionRow(item)
      }, {
        toolbar: withCtx(({ searchQuery: tableSearchQuery, updateSearch }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$g), {
              "model-value": String(tableSearchQuery),
              placeholder: "Search projections...",
              ui: { root: "w-full md:w-96" },
              "onUpdate:modelValue": updateSearch
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(Button), {
              icon: unref(Plus),
              size: "sm",
              onClick: startCreate
            }, {
              default: withCtx((_, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`Add projection`);
                } else {
                  return [
                    createTextVNode("Add projection")
                  ];
                }
              }),
              _: 2
            }, _parent2, _scopeId));
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between" }, [
                createVNode(unref(_sfc_main$g), {
                  "model-value": String(tableSearchQuery),
                  placeholder: "Search projections...",
                  ui: { root: "w-full md:w-96" },
                  "onUpdate:modelValue": updateSearch
                }, null, 8, ["model-value", "onUpdate:modelValue"]),
                createVNode(unref(Button), {
                  icon: unref(Plus),
                  size: "sm",
                  onClick: startCreate
                }, {
                  default: withCtx(() => [
                    createTextVNode("Add projection")
                  ]),
                  _: 1
                }, 8, ["icon"])
              ])
            ];
          }
        }),
        "cell-id": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<span class="font-mono text-xs text-ui-muted-foreground"${_scopeId}>#${ssrInterpolate(item.id)}</span>`);
          } else {
            return [
              createVNode("span", { class: "font-mono text-xs text-ui-muted-foreground" }, "#" + toDisplayString(item.id), 1)
            ];
          }
        }),
        "cell-title": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          var _a2, _b;
          if (_push2) {
            _push2(`<div class="font-medium"${_scopeId}>${ssrInterpolate(((_a2 = item.title) == null ? void 0 : _a2.en) ?? "Untitled projection")}</div><div class="text-sm text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(item.type)} · ${ssrInterpolate(displayTargetDate(item.target_date))}</div>`);
          } else {
            return [
              createVNode("div", { class: "font-medium" }, toDisplayString(((_b = item.title) == null ? void 0 : _b.en) ?? "Untitled projection"), 1),
              createVNode("div", { class: "text-sm text-ui-muted-foreground" }, toDisplayString(item.type) + " · " + toDisplayString(displayTargetDate(item.target_date)), 1)
            ];
          }
        }),
        "cell-scores": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="text-sm text-ui-muted-foreground"${_scopeId}>confidence ${ssrInterpolate(item.confidence_score)} · probability ${ssrInterpolate(item.probability_score)}</div>`);
          } else {
            return [
              createVNode("div", { class: "text-sm text-ui-muted-foreground" }, "confidence " + toDisplayString(item.confidence_score) + " · probability " + toDisplayString(item.probability_score), 1)
            ];
          }
        }),
        "cell-sort_order": withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<span class="font-mono text-xs text-ui-muted-foreground"${_scopeId}>${ssrInterpolate(item.sort_order)}</span>`);
          } else {
            return [
              createVNode("span", { class: "font-mono text-xs text-ui-muted-foreground" }, toDisplayString(item.sort_order), 1)
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
              onClick: ($event) => startEdit(asProjection(item))
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
              onClick: ($event) => pendingDelete.value = asProjection(item)
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
                  onClick: ($event) => startEdit(asProjection(item))
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
                  onClick: ($event) => pendingDelete.value = asProjection(item)
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
        expansion: withCtx(({ item }, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$3, {
              "base-path": __props.basePath,
              "countdown-id": __props.countdownId,
              "projection-id": Number(item.id),
              visualizations: asProjection(item).visualizations,
              options: __props.options,
              title: "Projection visualizations",
              searchable: false
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$3, {
                "base-path": __props.basePath,
                "countdown-id": __props.countdownId,
                "projection-id": Number(item.id),
                visualizations: asProjection(item).visualizations,
                options: __props.options,
                title: "Projection visualizations",
                searchable: false
              }, null, 8, ["base-path", "countdown-id", "projection-id", "visualizations", "options"])
            ];
          }
        }),
        empty: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$e), {
              title: "No projections",
              description: "No projections match this search.",
              icon: unref(Plus)
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$e), {
                title: "No projections",
                description: "No projections match this search.",
                icon: unref(Plus)
              }, null, 8, ["icon"])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$h, {
        show: pendingDelete.value !== null,
        title: "Delete projection",
        description: `Delete projection ${((_a = pendingDelete.value) == null ? void 0 : _a.title.en) ?? ""}? Nested visualizations will be removed by backend services.`,
        loading: unref(deleteForm).processing,
        onClose: ($event) => pendingDelete.value = null,
        onConfirm: confirmDestroy
      }, null, _parent));
      _push(`</section>`);
    };
  }
});
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Doomsday/ProjectionManager.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "CountdownRelationsManager",
  __ssrInlineRender: true,
  props: {
    basePath: {},
    countdown: {},
    options: {},
    submitUrl: {},
    method: {},
    submitLabel: {}
  },
  setup(__props) {
    const tabs = [
      { value: "main", label: "Main", icon: Settings2 },
      { value: "projections", label: "Projections", icon: Activity },
      { value: "visualizations", label: "Visualizations", icon: BarChart3 },
      { value: "news", label: "News", icon: Newspaper },
      { value: "initiatives", label: "Initiatives", icon: Megaphone }
    ];
    function initialTab() {
      if (typeof window === "undefined") {
        return "main";
      }
      const queryTab = new URLSearchParams(window.location.search).get("tab");
      return queryTab !== null && tabs.some((tab) => tab.value === queryTab) ? queryTab : "main";
    }
    function replaceTabQuery(tab) {
      if (typeof window === "undefined") {
        return;
      }
      const url = new URL(window.location.href);
      url.searchParams.set("tab", tab);
      window.history.replaceState(window.history.state, "", `${url.pathname}${url.search}${url.hash}`);
    }
    const activeTab = ref(initialTab());
    watch(activeTab, (tab) => replaceTabQuery(tab));
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(_sfc_main$j), mergeProps({
        modelValue: activeTab.value,
        "onUpdate:modelValue": ($event) => activeTab.value = $event,
        items: tabs,
        variant: "bordered",
        ui: { panels: "pt-5" }
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(unref(_sfc_main$k), { ui: { body: "space-y-5 p-6" } }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(ssrRenderComponent(unref(_sfc_main$l), { value: "main" }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(ssrRenderComponent(_sfc_main$m, {
                          options: __props.options,
                          countdown: __props.countdown,
                          "submit-url": __props.submitUrl,
                          method: __props.method,
                          "submit-label": __props.submitLabel,
                          embedded: ""
                        }, null, _parent4, _scopeId3));
                      } else {
                        return [
                          createVNode(_sfc_main$m, {
                            options: __props.options,
                            countdown: __props.countdown,
                            "submit-url": __props.submitUrl,
                            method: __props.method,
                            "submit-label": __props.submitLabel,
                            embedded: ""
                          }, null, 8, ["options", "countdown", "submit-url", "method", "submit-label"])
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(unref(_sfc_main$l), { value: "projections" }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(ssrRenderComponent(_sfc_main$2, {
                          "base-path": __props.basePath,
                          "countdown-id": __props.countdown.id,
                          projections: __props.countdown.projections,
                          options: __props.options
                        }, null, _parent4, _scopeId3));
                      } else {
                        return [
                          createVNode(_sfc_main$2, {
                            "base-path": __props.basePath,
                            "countdown-id": __props.countdown.id,
                            projections: __props.countdown.projections,
                            options: __props.options
                          }, null, 8, ["base-path", "countdown-id", "projections", "options"])
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(unref(_sfc_main$l), { value: "visualizations" }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(ssrRenderComponent(_sfc_main$3, {
                          "base-path": __props.basePath,
                          "countdown-id": __props.countdown.id,
                          visualizations: __props.countdown.visualizations,
                          options: __props.options,
                          title: "Countdown visualizations"
                        }, null, _parent4, _scopeId3));
                      } else {
                        return [
                          createVNode(_sfc_main$3, {
                            "base-path": __props.basePath,
                            "countdown-id": __props.countdown.id,
                            visualizations: __props.countdown.visualizations,
                            options: __props.options,
                            title: "Countdown visualizations"
                          }, null, 8, ["base-path", "countdown-id", "visualizations", "options"])
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(unref(_sfc_main$l), { value: "news" }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(ssrRenderComponent(_sfc_main$4, {
                          "base-path": __props.basePath,
                          "countdown-id": __props.countdown.id,
                          news: __props.countdown.news,
                          options: __props.options
                        }, null, _parent4, _scopeId3));
                      } else {
                        return [
                          createVNode(_sfc_main$4, {
                            "base-path": __props.basePath,
                            "countdown-id": __props.countdown.id,
                            news: __props.countdown.news,
                            options: __props.options
                          }, null, 8, ["base-path", "countdown-id", "news", "options"])
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                  _push3(ssrRenderComponent(unref(_sfc_main$l), { value: "initiatives" }, {
                    default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                      if (_push4) {
                        _push4(ssrRenderComponent(_sfc_main$6, {
                          "base-path": __props.basePath,
                          "countdown-id": __props.countdown.id,
                          initiatives: __props.countdown.initiatives,
                          options: __props.options
                        }, null, _parent4, _scopeId3));
                      } else {
                        return [
                          createVNode(_sfc_main$6, {
                            "base-path": __props.basePath,
                            "countdown-id": __props.countdown.id,
                            initiatives: __props.countdown.initiatives,
                            options: __props.options
                          }, null, 8, ["base-path", "countdown-id", "initiatives", "options"])
                        ];
                      }
                    }),
                    _: 1
                  }, _parent3, _scopeId2));
                } else {
                  return [
                    createVNode(unref(_sfc_main$l), { value: "main" }, {
                      default: withCtx(() => [
                        createVNode(_sfc_main$m, {
                          options: __props.options,
                          countdown: __props.countdown,
                          "submit-url": __props.submitUrl,
                          method: __props.method,
                          "submit-label": __props.submitLabel,
                          embedded: ""
                        }, null, 8, ["options", "countdown", "submit-url", "method", "submit-label"])
                      ]),
                      _: 1
                    }),
                    createVNode(unref(_sfc_main$l), { value: "projections" }, {
                      default: withCtx(() => [
                        createVNode(_sfc_main$2, {
                          "base-path": __props.basePath,
                          "countdown-id": __props.countdown.id,
                          projections: __props.countdown.projections,
                          options: __props.options
                        }, null, 8, ["base-path", "countdown-id", "projections", "options"])
                      ]),
                      _: 1
                    }),
                    createVNode(unref(_sfc_main$l), { value: "visualizations" }, {
                      default: withCtx(() => [
                        createVNode(_sfc_main$3, {
                          "base-path": __props.basePath,
                          "countdown-id": __props.countdown.id,
                          visualizations: __props.countdown.visualizations,
                          options: __props.options,
                          title: "Countdown visualizations"
                        }, null, 8, ["base-path", "countdown-id", "visualizations", "options"])
                      ]),
                      _: 1
                    }),
                    createVNode(unref(_sfc_main$l), { value: "news" }, {
                      default: withCtx(() => [
                        createVNode(_sfc_main$4, {
                          "base-path": __props.basePath,
                          "countdown-id": __props.countdown.id,
                          news: __props.countdown.news,
                          options: __props.options
                        }, null, 8, ["base-path", "countdown-id", "news", "options"])
                      ]),
                      _: 1
                    }),
                    createVNode(unref(_sfc_main$l), { value: "initiatives" }, {
                      default: withCtx(() => [
                        createVNode(_sfc_main$6, {
                          "base-path": __props.basePath,
                          "countdown-id": __props.countdown.id,
                          initiatives: __props.countdown.initiatives,
                          options: __props.options
                        }, null, 8, ["base-path", "countdown-id", "initiatives", "options"])
                      ]),
                      _: 1
                    })
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
          } else {
            return [
              createVNode(unref(_sfc_main$k), { ui: { body: "space-y-5 p-6" } }, {
                default: withCtx(() => [
                  createVNode(unref(_sfc_main$l), { value: "main" }, {
                    default: withCtx(() => [
                      createVNode(_sfc_main$m, {
                        options: __props.options,
                        countdown: __props.countdown,
                        "submit-url": __props.submitUrl,
                        method: __props.method,
                        "submit-label": __props.submitLabel,
                        embedded: ""
                      }, null, 8, ["options", "countdown", "submit-url", "method", "submit-label"])
                    ]),
                    _: 1
                  }),
                  createVNode(unref(_sfc_main$l), { value: "projections" }, {
                    default: withCtx(() => [
                      createVNode(_sfc_main$2, {
                        "base-path": __props.basePath,
                        "countdown-id": __props.countdown.id,
                        projections: __props.countdown.projections,
                        options: __props.options
                      }, null, 8, ["base-path", "countdown-id", "projections", "options"])
                    ]),
                    _: 1
                  }),
                  createVNode(unref(_sfc_main$l), { value: "visualizations" }, {
                    default: withCtx(() => [
                      createVNode(_sfc_main$3, {
                        "base-path": __props.basePath,
                        "countdown-id": __props.countdown.id,
                        visualizations: __props.countdown.visualizations,
                        options: __props.options,
                        title: "Countdown visualizations"
                      }, null, 8, ["base-path", "countdown-id", "visualizations", "options"])
                    ]),
                    _: 1
                  }),
                  createVNode(unref(_sfc_main$l), { value: "news" }, {
                    default: withCtx(() => [
                      createVNode(_sfc_main$4, {
                        "base-path": __props.basePath,
                        "countdown-id": __props.countdown.id,
                        news: __props.countdown.news,
                        options: __props.options
                      }, null, 8, ["base-path", "countdown-id", "news", "options"])
                    ]),
                    _: 1
                  }),
                  createVNode(unref(_sfc_main$l), { value: "initiatives" }, {
                    default: withCtx(() => [
                      createVNode(_sfc_main$6, {
                        "base-path": __props.basePath,
                        "countdown-id": __props.countdown.id,
                        initiatives: __props.countdown.initiatives,
                        options: __props.options
                      }, null, 8, ["base-path", "countdown-id", "initiatives", "options"])
                    ]),
                    _: 1
                  })
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Doomsday/CountdownRelationsManager.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Edit",
  __ssrInlineRender: true,
  props: {
    countdown: {},
    options: {}
  },
  setup(__props) {
    const props = __props;
    const { backofficePath, normalizedBackofficePath, counts } = useBackofficePath();
    const activeSection = ref("countdowns");
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), {
        title: `Edit ${__props.countdown.slug}`
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$n, {
        "active-section": activeSection.value,
        "onUpdate:activeSection": ($event) => activeSection.value = $event,
        title: `Edit ${__props.countdown.title.en ?? __props.countdown.slug}`,
        subtitle: "Manage the countdown record and all projections, visualizations, news and initiatives.",
        "backoffice-path": unref(backofficePath),
        counts: unref(counts)
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$1, {
              "base-path": unref(normalizedBackofficePath),
              countdown: __props.countdown,
              options: __props.options,
              "submit-url": `${unref(normalizedBackofficePath)}/countdowns/${props.countdown.id}`,
              method: "put",
              "submit-label": "Save countdown"
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$1, {
                "base-path": unref(normalizedBackofficePath),
                countdown: __props.countdown,
                options: __props.options,
                "submit-url": `${unref(normalizedBackofficePath)}/countdowns/${props.countdown.id}`,
                method: "put",
                "submit-label": "Save countdown"
              }, null, 8, ["base-path", "countdown", "options", "submit-url"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Backoffice/Countdowns/Edit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
