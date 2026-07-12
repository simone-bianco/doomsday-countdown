import { defineComponent, useModel, mergeProps, unref, mergeModels, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent } from "vue/server-renderer";
import { l as _sfc_main$1, m as _sfc_main$2, n as _sfc_main$3 } from "../ssr.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "BackofficeSelectField",
  __ssrInlineRender: true,
  props: /* @__PURE__ */ mergeModels({
    label: {},
    options: {},
    clearable: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    placeholder: { default: "Select an option..." },
    error: { default: void 0 }
  }, {
    "modelValue": { required: false, default: null },
    "modelModifiers": {}
  }),
  emits: ["update:modelValue"],
  setup(__props) {
    const model = useModel(__props, "modelValue");
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "w-full min-h-[76px]" }, _attrs))}>`);
      _push(ssrRenderComponent(unref(_sfc_main$1), { value: __props.label }, null, _parent));
      _push(ssrRenderComponent(unref(_sfc_main$2), {
        modelValue: model.value,
        "onUpdate:modelValue": ($event) => model.value = $event,
        options: __props.options,
        clearable: __props.clearable,
        disabled: __props.disabled,
        placeholder: __props.placeholder,
        ui: { trigger: "min-h-[42px]" }
      }, null, _parent));
      _push(`<div class="mt-1 min-h-[20px]">`);
      _push(ssrRenderComponent(unref(_sfc_main$3), { error: __props.error }, null, _parent));
      _push(`</div></div>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Shared/BackofficeSelectField.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
