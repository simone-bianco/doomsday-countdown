import { defineComponent, unref, mergeProps, useSSRContext } from "vue";
import { ssrRenderComponent } from "vue/server-renderer";
import { D as DangerModal } from "../ssr.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "DeleteConfirmationModal",
  __ssrInlineRender: true,
  props: {
    show: { type: Boolean },
    title: {},
    description: {},
    loading: { type: Boolean, default: false }
  },
  emits: ["close", "confirm"],
  setup(__props, { emit: __emit }) {
    const emit = __emit;
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(DangerModal), mergeProps({
        show: __props.show,
        title: __props.title,
        description: __props.description,
        "confirm-label": "Delete",
        "cancel-label": "Keep",
        loading: __props.loading,
        onClose: ($event) => emit("close"),
        onConfirm: ($event) => emit("confirm")
      }, _attrs), null, _parent));
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Backoffice/Shared/DeleteConfirmationModal.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
