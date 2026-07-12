import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
function useBackofficePath() {
  const page = usePage();
  const backofficePath = computed(() => {
    var _a;
    return ((_a = page.props.app) == null ? void 0 : _a.backoffice_path) ?? "";
  });
  const normalizedBackofficePath = computed(() => backofficePath.value.replace(/\/+$/g, ""));
  const counts = computed(() => {
    var _a, _b, _c, _d, _e, _f;
    return {
      users: ((_b = (_a = page.props.app) == null ? void 0 : _a.backoffice_counts) == null ? void 0 : _b.users) ?? 0,
      apiKeys: ((_d = (_c = page.props.app) == null ? void 0 : _c.backoffice_counts) == null ? void 0 : _d.apiKeys) ?? 0,
      countdowns: ((_f = (_e = page.props.app) == null ? void 0 : _e.backoffice_counts) == null ? void 0 : _f.countdowns) ?? 0
    };
  });
  return { backofficePath, normalizedBackofficePath, counts };
}
export {
  useBackofficePath as u
};
