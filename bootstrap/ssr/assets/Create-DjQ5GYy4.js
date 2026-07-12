import { defineComponent, ref, unref, withCtx, createVNode, useSSRContext } from "vue";
import { ssrRenderComponent } from "vue/server-renderer";
import { Head, router } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./BackofficeShell-B2o2xGeb.js";
import { _ as _sfc_main$2 } from "./CountdownForm-CavKe4_z.js";
import { u as useBackofficePath } from "./useBackofficePath-DWj-NlKi.js";
import "../ssr.js";
import "@vue/server-renderer";
import "clsx";
import "tailwind-merge";
import "lucide-vue-next";
import "vue-advanced-cropper";
import "node:async_hooks";
import "i18next";
import "./AppLayout-CiOlqxSX.js";
import "./useSmartForm-KUAiAz4w.js";
import "./BackofficeSelectField-CNJDQmD8.js";
import "./form-rules-C_jmOmBo.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Create",
  __ssrInlineRender: true,
  props: {
    options: {}
  },
  setup(__props) {
    const { backofficePath, normalizedBackofficePath, counts } = useBackofficePath();
    const activeSection = ref("countdowns");
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Create countdown" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, {
        "active-section": activeSection.value,
        "onUpdate:activeSection": ($event) => activeSection.value = $event,
        title: "Create countdown",
        subtitle: "Create a public Doomsday Clock draft.",
        "backoffice-path": unref(backofficePath),
        counts: unref(counts)
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$2, {
              options: __props.options,
              "submit-url": `${unref(normalizedBackofficePath)}/countdowns`,
              method: "post",
              "submit-label": "Create countdown",
              onSaved: ($event) => unref(router).visit(`${unref(normalizedBackofficePath)}/countdowns`)
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$2, {
                options: __props.options,
                "submit-url": `${unref(normalizedBackofficePath)}/countdowns`,
                method: "post",
                "submit-label": "Create countdown",
                onSaved: ($event) => unref(router).visit(`${unref(normalizedBackofficePath)}/countdowns`)
              }, null, 8, ["options", "submit-url", "onSaved"])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Backoffice/Countdowns/Create.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
