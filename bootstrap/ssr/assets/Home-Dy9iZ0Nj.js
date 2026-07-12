import { defineComponent, mergeProps, useSSRContext } from "vue";
import { ssrRenderComponent } from "vue/server-renderer";
import _sfc_main$1 from "./Home-By4aqdMv.js";
import "motion-v";
import "@inertiajs/vue3";
import "../ssr.js";
import "@vue/server-renderer";
import "clsx";
import "tailwind-merge";
import "lucide-vue-next";
import "vue-advanced-cropper";
import "node:async_hooks";
import "i18next";
import "./PublicLayout-Cg7TjPHG.js";
import "./doomsdayMotion-D6KS_x2N.js";
import "./VisualizationChart-CkPtd6z_.js";
import "axios";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Home",
  __ssrInlineRender: true,
  props: {
    page: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$1, mergeProps({ page: __props.page }, _attrs), null, _parent));
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Home.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
