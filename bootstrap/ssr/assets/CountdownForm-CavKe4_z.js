import { defineComponent, ref, computed, watch, createVNode, resolveDynamicComponent, unref, mergeProps, withCtx, createTextVNode, toDisplayString, openBlock, createBlock, Fragment, renderList, withModifiers, useSSRContext } from "vue";
import { ssrRenderVNode, ssrRenderComponent, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
import { h as _sfc_main$1, _ as _sfc_main$2, B as Button, a as _sfc_main$3, N as NumberInput, g as _sfc_main$5, i as _sfc_main$6, b as _sfc_main$7 } from "../ssr.js";
import { u as useSmartForm } from "./useSmartForm-KUAiAz4w.js";
import "./AppLayout-CiOlqxSX.js";
import { _ as _sfc_main$4 } from "./BackofficeSelectField-CNJDQmD8.js";
import { b as SaveCountdownDataRules } from "./form-rules-C_jmOmBo.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "CountdownForm",
  __ssrInlineRender: true,
  props: {
    options: {},
    submitUrl: {},
    method: {},
    submitLabel: {},
    countdown: { default: void 0 },
    embedded: { type: Boolean, default: false }
  },
  emits: ["saved"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const form = useSmartForm({ ...SaveCountdownDataRules });
    const activeLocale = ref("en");
    const listTexts = ref({
      causes: {},
      consequences: {},
      recommended_actions: {}
    });
    const localizedFields = computed(() => {
      const configured = props.options.localized_fields.filter((locale) => locale.trim() !== "");
      const unique = Array.from(/* @__PURE__ */ new Set(["en", ...configured]));
      return unique.length > 0 ? unique : ["en"];
    });
    const localeTabs = computed(() => localizedFields.value.map((locale) => ({
      value: locale,
      label: locale.toUpperCase()
    })));
    function isRecord(value) {
      return typeof value === "object" && value !== null && !Array.isArray(value);
    }
    function localizedText(source) {
      return localizedFields.value.reduce((values, locale) => {
        values[locale] = typeof (source == null ? void 0 : source[locale]) === "string" ? source[locale] : "";
        return values;
      }, {});
    }
    function localizedList(source) {
      return localizedFields.value.reduce((values, locale) => {
        const localizedValue = source == null ? void 0 : source[locale];
        values[locale] = Array.isArray(localizedValue) ? localizedValue.map(String) : [];
        return values;
      }, {});
    }
    function completeLocalizedText(value) {
      const source = isRecord(value) ? value : {};
      return localizedFields.value.reduce((values, locale) => {
        const localizedValue = source[locale];
        values[locale] = typeof localizedValue === "string" ? localizedValue : "";
        return values;
      }, {});
    }
    function completeLocalizedList(value) {
      const source = isRecord(value) ? value : {};
      return localizedFields.value.reduce((values, locale) => {
        const localizedValue = source[locale];
        values[locale] = Array.isArray(localizedValue) ? localizedValue.map(String) : [];
        return values;
      }, {});
    }
    function lines(value) {
      return value.split("\n").map((line) => line.trim()).filter((line) => line !== "");
    }
    function first(values, fallback) {
      return values[0] ?? fallback;
    }
    function isoDate(value) {
      return value ? value.slice(0, 10) : null;
    }
    function syncListTextsFromForm() {
      for (const field of Object.keys(listTexts.value)) {
        const localizedLists = form[field];
        listTexts.value[field] = localizedFields.value.reduce((values, locale) => {
          values[locale] = (localizedLists[locale] ?? []).join("\n");
          return values;
        }, {});
      }
    }
    function ensureLocalizedRecords() {
      form.title = completeLocalizedText(form.title);
      form.summary = completeLocalizedText(form.summary);
      form.description = completeLocalizedText(form.description);
      form.causes = completeLocalizedList(form.causes);
      form.consequences = completeLocalizedList(form.consequences);
      form.recommended_actions = completeLocalizedList(form.recommended_actions);
      syncListTextsFromForm();
    }
    function fill(countdown) {
      form.fill({
        slug: (countdown == null ? void 0 : countdown.slug) ?? "",
        title: localizedText(countdown == null ? void 0 : countdown.title),
        summary: localizedText(countdown == null ? void 0 : countdown.summary),
        description: localizedText(countdown == null ? void 0 : countdown.description),
        causes: localizedList(countdown == null ? void 0 : countdown.causes),
        consequences: localizedList(countdown == null ? void 0 : countdown.consequences),
        recommended_actions: localizedList(countdown == null ? void 0 : countdown.recommended_actions),
        severity: (countdown == null ? void 0 : countdown.severity) ?? first(props.options.countdown_severities, "moderate"),
        status: (countdown == null ? void 0 : countdown.status) ?? first(props.options.countdown_statuses, "draft"),
        target_date: isoDate(countdown == null ? void 0 : countdown.target_date),
        image_path: (countdown == null ? void 0 : countdown.image_path) ?? "/images/doomsday_hero_background_desktop.png",
        accent_color: (countdown == null ? void 0 : countdown.accent_color) ?? "#ff2a23",
        sort_order: (countdown == null ? void 0 : countdown.sort_order) ?? 0,
        is_published: (countdown == null ? void 0 : countdown.is_published) ?? false
      });
      syncListTextsFromForm();
    }
    function selectSeverity(value) {
      if (typeof value === "string") {
        form.severity = value;
      }
    }
    function selectStatus(value) {
      if (typeof value === "string") {
        form.status = value;
      }
    }
    function fieldPath(field, locale) {
      return `${field}.${locale}`;
    }
    function fieldError(field) {
      const errors = form.errors;
      return errors[fieldPath(field, activeLocale.value)] ?? errors[field];
    }
    function syncLists() {
      form.causes = localizedFields.value.reduce((values, locale) => {
        values[locale] = lines(listTexts.value.causes[locale] ?? "");
        return values;
      }, {});
      form.consequences = localizedFields.value.reduce((values, locale) => {
        values[locale] = lines(listTexts.value.consequences[locale] ?? "");
        return values;
      }, {});
      form.recommended_actions = localizedFields.value.reduce((values, locale) => {
        values[locale] = lines(listTexts.value.recommended_actions[locale] ?? "");
        return values;
      }, {});
    }
    function submit() {
      syncLists();
      const method = props.method === "post" ? form.post : form.put;
      method(props.submitUrl, { preserveScroll: true, onSuccess: () => emit("saved") });
    }
    watch(localizedFields, (fields) => {
      if (!fields.includes(activeLocale.value)) {
        activeLocale.value = fields[0] ?? "en";
      }
      ensureLocalizedRecords();
    });
    fill(props.countdown);
    return (_ctx, _push, _parent, _attrs) => {
      ssrRenderVNode(_push, createVNode(resolveDynamicComponent(__props.embedded ? "div" : unref(_sfc_main$1)), mergeProps({
        ui: __props.embedded ? void 0 : { body: "space-y-6 p-6" }
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<form class="space-y-6"${_scopeId}><div class="flex flex-col gap-4 border-b border-ui-border/60 pb-4 lg:flex-row lg:items-center lg:justify-between"${_scopeId}><div${_scopeId}><p class="text-sm font-semibold uppercase tracking-wide text-ui-muted-foreground"${_scopeId}>Main countdown</p><p class="mt-1 text-sm text-ui-muted-foreground"${_scopeId}>Edit global settings and localized public copy.</p></div><div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$2), {
              modelValue: unref(form).is_published,
              "onUpdate:modelValue": ($event) => unref(form).is_published = $event,
              label: "Published",
              "on-label": "Visible publicly",
              "off-label": "Draft"
            }, null, _parent2, _scopeId));
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
            _push2(`</div></div><div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$3), {
              modelValue: unref(form).slug,
              "onUpdate:modelValue": ($event) => unref(form).slug = $event,
              label: "Slug",
              error: unref(form).errors.slug,
              onBlur: ($event) => unref(form).validateField("slug")
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$4, {
              label: "Severity",
              "model-value": unref(form).severity,
              options: __props.options.countdown_severities.map((value) => ({ value, label: value })),
              clearable: false,
              "onUpdate:modelValue": selectSeverity
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(_sfc_main$4, {
              label: "Status",
              "model-value": unref(form).status,
              options: __props.options.countdown_statuses.map((value) => ({ value, label: value })),
              clearable: false,
              "onUpdate:modelValue": selectStatus
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(_sfc_main$3), {
              modelValue: unref(form).target_date,
              "onUpdate:modelValue": ($event) => unref(form).target_date = $event,
              label: "Target date",
              type: "date",
              error: unref(form).errors.target_date
            }, null, _parent2, _scopeId));
            _push2(`</div><div class="grid gap-4 md:grid-cols-3"${_scopeId}>`);
            _push2(ssrRenderComponent(unref(_sfc_main$3), {
              modelValue: unref(form).image_path,
              "onUpdate:modelValue": ($event) => unref(form).image_path = $event,
              label: "Image path",
              error: unref(form).errors.image_path
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(_sfc_main$3), {
              modelValue: unref(form).accent_color,
              "onUpdate:modelValue": ($event) => unref(form).accent_color = $event,
              label: "Accent color",
              error: unref(form).errors.accent_color
            }, null, _parent2, _scopeId));
            _push2(ssrRenderComponent(unref(NumberInput), {
              modelValue: unref(form).sort_order,
              "onUpdate:modelValue": ($event) => unref(form).sort_order = $event,
              label: "Sort order",
              min: 0,
              error: unref(form).errors.sort_order
            }, null, _parent2, _scopeId));
            _push2(`</div>`);
            _push2(ssrRenderComponent(unref(_sfc_main$5), {
              modelValue: activeLocale.value,
              "onUpdate:modelValue": ($event) => activeLocale.value = $event,
              items: localeTabs.value,
              variant: "bordered",
              ui: { panels: "pt-5" }
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`<!--[-->`);
                  ssrRenderList(localizedFields.value, (locale) => {
                    _push3(ssrRenderComponent(unref(_sfc_main$6), {
                      key: locale,
                      value: locale
                    }, {
                      default: withCtx((_3, _push4, _parent4, _scopeId3) => {
                        if (_push4) {
                          _push4(`<div class="space-y-5"${_scopeId3}><div class="grid gap-4 md:grid-cols-2"${_scopeId3}>`);
                          _push4(ssrRenderComponent(unref(_sfc_main$3), {
                            modelValue: unref(form).title[locale],
                            "onUpdate:modelValue": ($event) => unref(form).title[locale] = $event,
                            label: `Title (${locale.toUpperCase()})`,
                            error: fieldError("title"),
                            onBlur: ($event) => unref(form).validateField(fieldPath("title", locale))
                          }, null, _parent4, _scopeId3));
                          _push4(ssrRenderComponent(unref(_sfc_main$7), {
                            modelValue: unref(form).summary[locale],
                            "onUpdate:modelValue": ($event) => unref(form).summary[locale] = $event,
                            label: `Summary (${locale.toUpperCase()})`,
                            error: fieldError("summary"),
                            rows: 3,
                            onBlur: ($event) => unref(form).validateField(fieldPath("summary", locale))
                          }, null, _parent4, _scopeId3));
                          _push4(`</div>`);
                          _push4(ssrRenderComponent(unref(_sfc_main$7), {
                            modelValue: unref(form).description[locale],
                            "onUpdate:modelValue": ($event) => unref(form).description[locale] = $event,
                            label: `Description (${locale.toUpperCase()})`,
                            error: fieldError("description"),
                            rows: 4
                          }, null, _parent4, _scopeId3));
                          _push4(`<div class="grid gap-4 md:grid-cols-3"${_scopeId3}>`);
                          _push4(ssrRenderComponent(unref(_sfc_main$7), {
                            modelValue: listTexts.value.causes[locale],
                            "onUpdate:modelValue": ($event) => listTexts.value.causes[locale] = $event,
                            label: `Causes (${locale.toUpperCase()}, one per line)`,
                            error: fieldError("causes"),
                            rows: 5
                          }, null, _parent4, _scopeId3));
                          _push4(ssrRenderComponent(unref(_sfc_main$7), {
                            modelValue: listTexts.value.consequences[locale],
                            "onUpdate:modelValue": ($event) => listTexts.value.consequences[locale] = $event,
                            label: `Consequences (${locale.toUpperCase()}, one per line)`,
                            error: fieldError("consequences"),
                            rows: 5
                          }, null, _parent4, _scopeId3));
                          _push4(ssrRenderComponent(unref(_sfc_main$7), {
                            modelValue: listTexts.value.recommended_actions[locale],
                            "onUpdate:modelValue": ($event) => listTexts.value.recommended_actions[locale] = $event,
                            label: `Recommended actions (${locale.toUpperCase()}, one per line)`,
                            error: fieldError("recommended_actions"),
                            rows: 5
                          }, null, _parent4, _scopeId3));
                          _push4(`</div></div>`);
                        } else {
                          return [
                            createVNode("div", { class: "space-y-5" }, [
                              createVNode("div", { class: "grid gap-4 md:grid-cols-2" }, [
                                createVNode(unref(_sfc_main$3), {
                                  modelValue: unref(form).title[locale],
                                  "onUpdate:modelValue": ($event) => unref(form).title[locale] = $event,
                                  label: `Title (${locale.toUpperCase()})`,
                                  error: fieldError("title"),
                                  onBlur: ($event) => unref(form).validateField(fieldPath("title", locale))
                                }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error", "onBlur"]),
                                createVNode(unref(_sfc_main$7), {
                                  modelValue: unref(form).summary[locale],
                                  "onUpdate:modelValue": ($event) => unref(form).summary[locale] = $event,
                                  label: `Summary (${locale.toUpperCase()})`,
                                  error: fieldError("summary"),
                                  rows: 3,
                                  onBlur: ($event) => unref(form).validateField(fieldPath("summary", locale))
                                }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error", "onBlur"])
                              ]),
                              createVNode(unref(_sfc_main$7), {
                                modelValue: unref(form).description[locale],
                                "onUpdate:modelValue": ($event) => unref(form).description[locale] = $event,
                                label: `Description (${locale.toUpperCase()})`,
                                error: fieldError("description"),
                                rows: 4
                              }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"]),
                              createVNode("div", { class: "grid gap-4 md:grid-cols-3" }, [
                                createVNode(unref(_sfc_main$7), {
                                  modelValue: listTexts.value.causes[locale],
                                  "onUpdate:modelValue": ($event) => listTexts.value.causes[locale] = $event,
                                  label: `Causes (${locale.toUpperCase()}, one per line)`,
                                  error: fieldError("causes"),
                                  rows: 5
                                }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"]),
                                createVNode(unref(_sfc_main$7), {
                                  modelValue: listTexts.value.consequences[locale],
                                  "onUpdate:modelValue": ($event) => listTexts.value.consequences[locale] = $event,
                                  label: `Consequences (${locale.toUpperCase()}, one per line)`,
                                  error: fieldError("consequences"),
                                  rows: 5
                                }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"]),
                                createVNode(unref(_sfc_main$7), {
                                  modelValue: listTexts.value.recommended_actions[locale],
                                  "onUpdate:modelValue": ($event) => listTexts.value.recommended_actions[locale] = $event,
                                  label: `Recommended actions (${locale.toUpperCase()}, one per line)`,
                                  error: fieldError("recommended_actions"),
                                  rows: 5
                                }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"])
                              ])
                            ])
                          ];
                        }
                      }),
                      _: 2
                    }, _parent3, _scopeId2));
                  });
                  _push3(`<!--]-->`);
                } else {
                  return [
                    (openBlock(true), createBlock(Fragment, null, renderList(localizedFields.value, (locale) => {
                      return openBlock(), createBlock(unref(_sfc_main$6), {
                        key: locale,
                        value: locale
                      }, {
                        default: withCtx(() => [
                          createVNode("div", { class: "space-y-5" }, [
                            createVNode("div", { class: "grid gap-4 md:grid-cols-2" }, [
                              createVNode(unref(_sfc_main$3), {
                                modelValue: unref(form).title[locale],
                                "onUpdate:modelValue": ($event) => unref(form).title[locale] = $event,
                                label: `Title (${locale.toUpperCase()})`,
                                error: fieldError("title"),
                                onBlur: ($event) => unref(form).validateField(fieldPath("title", locale))
                              }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error", "onBlur"]),
                              createVNode(unref(_sfc_main$7), {
                                modelValue: unref(form).summary[locale],
                                "onUpdate:modelValue": ($event) => unref(form).summary[locale] = $event,
                                label: `Summary (${locale.toUpperCase()})`,
                                error: fieldError("summary"),
                                rows: 3,
                                onBlur: ($event) => unref(form).validateField(fieldPath("summary", locale))
                              }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error", "onBlur"])
                            ]),
                            createVNode(unref(_sfc_main$7), {
                              modelValue: unref(form).description[locale],
                              "onUpdate:modelValue": ($event) => unref(form).description[locale] = $event,
                              label: `Description (${locale.toUpperCase()})`,
                              error: fieldError("description"),
                              rows: 4
                            }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"]),
                            createVNode("div", { class: "grid gap-4 md:grid-cols-3" }, [
                              createVNode(unref(_sfc_main$7), {
                                modelValue: listTexts.value.causes[locale],
                                "onUpdate:modelValue": ($event) => listTexts.value.causes[locale] = $event,
                                label: `Causes (${locale.toUpperCase()}, one per line)`,
                                error: fieldError("causes"),
                                rows: 5
                              }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"]),
                              createVNode(unref(_sfc_main$7), {
                                modelValue: listTexts.value.consequences[locale],
                                "onUpdate:modelValue": ($event) => listTexts.value.consequences[locale] = $event,
                                label: `Consequences (${locale.toUpperCase()}, one per line)`,
                                error: fieldError("consequences"),
                                rows: 5
                              }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"]),
                              createVNode(unref(_sfc_main$7), {
                                modelValue: listTexts.value.recommended_actions[locale],
                                "onUpdate:modelValue": ($event) => listTexts.value.recommended_actions[locale] = $event,
                                label: `Recommended actions (${locale.toUpperCase()}, one per line)`,
                                error: fieldError("recommended_actions"),
                                rows: 5
                              }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"])
                            ])
                          ])
                        ]),
                        _: 2
                      }, 1032, ["value"]);
                    }), 128))
                  ];
                }
              }),
              _: 1
            }, _parent2, _scopeId));
            _push2(`</form>`);
          } else {
            return [
              createVNode("form", {
                class: "space-y-6",
                onSubmit: withModifiers(submit, ["prevent"])
              }, [
                createVNode("div", { class: "flex flex-col gap-4 border-b border-ui-border/60 pb-4 lg:flex-row lg:items-center lg:justify-between" }, [
                  createVNode("div", null, [
                    createVNode("p", { class: "text-sm font-semibold uppercase tracking-wide text-ui-muted-foreground" }, "Main countdown"),
                    createVNode("p", { class: "mt-1 text-sm text-ui-muted-foreground" }, "Edit global settings and localized public copy.")
                  ]),
                  createVNode("div", { class: "flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end" }, [
                    createVNode(unref(_sfc_main$2), {
                      modelValue: unref(form).is_published,
                      "onUpdate:modelValue": ($event) => unref(form).is_published = $event,
                      label: "Published",
                      "on-label": "Visible publicly",
                      "off-label": "Draft"
                    }, null, 8, ["modelValue", "onUpdate:modelValue"]),
                    createVNode(unref(Button), {
                      type: "submit",
                      loading: unref(form).processing
                    }, {
                      default: withCtx(() => [
                        createTextVNode(toDisplayString(__props.submitLabel), 1)
                      ]),
                      _: 1
                    }, 8, ["loading"])
                  ])
                ]),
                createVNode("div", { class: "grid gap-4 md:grid-cols-2 xl:grid-cols-4" }, [
                  createVNode(unref(_sfc_main$3), {
                    modelValue: unref(form).slug,
                    "onUpdate:modelValue": ($event) => unref(form).slug = $event,
                    label: "Slug",
                    error: unref(form).errors.slug,
                    onBlur: ($event) => unref(form).validateField("slug")
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "error", "onBlur"]),
                  createVNode(_sfc_main$4, {
                    label: "Severity",
                    "model-value": unref(form).severity,
                    options: __props.options.countdown_severities.map((value) => ({ value, label: value })),
                    clearable: false,
                    "onUpdate:modelValue": selectSeverity
                  }, null, 8, ["model-value", "options"]),
                  createVNode(_sfc_main$4, {
                    label: "Status",
                    "model-value": unref(form).status,
                    options: __props.options.countdown_statuses.map((value) => ({ value, label: value })),
                    clearable: false,
                    "onUpdate:modelValue": selectStatus
                  }, null, 8, ["model-value", "options"]),
                  createVNode(unref(_sfc_main$3), {
                    modelValue: unref(form).target_date,
                    "onUpdate:modelValue": ($event) => unref(form).target_date = $event,
                    label: "Target date",
                    type: "date",
                    error: unref(form).errors.target_date
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "error"])
                ]),
                createVNode("div", { class: "grid gap-4 md:grid-cols-3" }, [
                  createVNode(unref(_sfc_main$3), {
                    modelValue: unref(form).image_path,
                    "onUpdate:modelValue": ($event) => unref(form).image_path = $event,
                    label: "Image path",
                    error: unref(form).errors.image_path
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                  createVNode(unref(_sfc_main$3), {
                    modelValue: unref(form).accent_color,
                    "onUpdate:modelValue": ($event) => unref(form).accent_color = $event,
                    label: "Accent color",
                    error: unref(form).errors.accent_color
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "error"]),
                  createVNode(unref(NumberInput), {
                    modelValue: unref(form).sort_order,
                    "onUpdate:modelValue": ($event) => unref(form).sort_order = $event,
                    label: "Sort order",
                    min: 0,
                    error: unref(form).errors.sort_order
                  }, null, 8, ["modelValue", "onUpdate:modelValue", "error"])
                ]),
                createVNode(unref(_sfc_main$5), {
                  modelValue: activeLocale.value,
                  "onUpdate:modelValue": ($event) => activeLocale.value = $event,
                  items: localeTabs.value,
                  variant: "bordered",
                  ui: { panels: "pt-5" }
                }, {
                  default: withCtx(() => [
                    (openBlock(true), createBlock(Fragment, null, renderList(localizedFields.value, (locale) => {
                      return openBlock(), createBlock(unref(_sfc_main$6), {
                        key: locale,
                        value: locale
                      }, {
                        default: withCtx(() => [
                          createVNode("div", { class: "space-y-5" }, [
                            createVNode("div", { class: "grid gap-4 md:grid-cols-2" }, [
                              createVNode(unref(_sfc_main$3), {
                                modelValue: unref(form).title[locale],
                                "onUpdate:modelValue": ($event) => unref(form).title[locale] = $event,
                                label: `Title (${locale.toUpperCase()})`,
                                error: fieldError("title"),
                                onBlur: ($event) => unref(form).validateField(fieldPath("title", locale))
                              }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error", "onBlur"]),
                              createVNode(unref(_sfc_main$7), {
                                modelValue: unref(form).summary[locale],
                                "onUpdate:modelValue": ($event) => unref(form).summary[locale] = $event,
                                label: `Summary (${locale.toUpperCase()})`,
                                error: fieldError("summary"),
                                rows: 3,
                                onBlur: ($event) => unref(form).validateField(fieldPath("summary", locale))
                              }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error", "onBlur"])
                            ]),
                            createVNode(unref(_sfc_main$7), {
                              modelValue: unref(form).description[locale],
                              "onUpdate:modelValue": ($event) => unref(form).description[locale] = $event,
                              label: `Description (${locale.toUpperCase()})`,
                              error: fieldError("description"),
                              rows: 4
                            }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"]),
                            createVNode("div", { class: "grid gap-4 md:grid-cols-3" }, [
                              createVNode(unref(_sfc_main$7), {
                                modelValue: listTexts.value.causes[locale],
                                "onUpdate:modelValue": ($event) => listTexts.value.causes[locale] = $event,
                                label: `Causes (${locale.toUpperCase()}, one per line)`,
                                error: fieldError("causes"),
                                rows: 5
                              }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"]),
                              createVNode(unref(_sfc_main$7), {
                                modelValue: listTexts.value.consequences[locale],
                                "onUpdate:modelValue": ($event) => listTexts.value.consequences[locale] = $event,
                                label: `Consequences (${locale.toUpperCase()}, one per line)`,
                                error: fieldError("consequences"),
                                rows: 5
                              }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"]),
                              createVNode(unref(_sfc_main$7), {
                                modelValue: listTexts.value.recommended_actions[locale],
                                "onUpdate:modelValue": ($event) => listTexts.value.recommended_actions[locale] = $event,
                                label: `Recommended actions (${locale.toUpperCase()}, one per line)`,
                                error: fieldError("recommended_actions"),
                                rows: 5
                              }, null, 8, ["modelValue", "onUpdate:modelValue", "label", "error"])
                            ])
                          ])
                        ]),
                        _: 2
                      }, 1032, ["value"]);
                    }), 128))
                  ]),
                  _: 1
                }, 8, ["modelValue", "onUpdate:modelValue", "items"])
              ], 32)
            ];
          }
        }),
        _: 1
      }), _parent);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Doomsday/CountdownForm.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
