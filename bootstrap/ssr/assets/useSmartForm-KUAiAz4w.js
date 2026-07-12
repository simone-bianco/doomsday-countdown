import { useForm } from "@inertiajs/vue3";
import { reactive } from "vue";
let currentLocale = "it";
let messages = {};
const ruleHandlers = {
  required: (value) => {
    if (value === null || value === void 0) return false;
    if (typeof value === "string") return value.trim().length > 0;
    if (Array.isArray(value)) return value.length > 0;
    if (value instanceof File) return true;
    return true;
  },
  string: (value) => {
    return value === null || value === void 0 || typeof value === "string";
  },
  numeric: (value) => {
    if (value === null || value === void 0 || value === "") return true;
    return !isNaN(Number(value));
  },
  integer: (value) => {
    if (value === null || value === void 0 || value === "") return true;
    return Number.isInteger(Number(value));
  },
  email: (value) => {
    if (!value || typeof value !== "string") return true;
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  },
  url: (value) => {
    if (!value || typeof value !== "string") return true;
    try {
      new URL(value);
      return true;
    } catch {
      return false;
    }
  },
  min: (value, params) => {
    const min = Number(params == null ? void 0 : params.min);
    if (typeof value === "string") return value.length >= min;
    if (typeof value === "number") return value >= min;
    if (Array.isArray(value)) return value.length >= min;
    if (value instanceof File) return value.size / 1024 >= min;
    return true;
  },
  max: (value, params) => {
    const max = Number(params == null ? void 0 : params.max);
    if (typeof value === "string") return value.length <= max;
    if (typeof value === "number") return value <= max;
    if (Array.isArray(value)) return value.length <= max;
    if (value instanceof File) return value.size / 1024 <= max;
    return true;
  },
  between: (value, params) => {
    const min = Number(params == null ? void 0 : params.min);
    const max = Number(params == null ? void 0 : params.max);
    if (typeof value === "string") return value.length >= min && value.length <= max;
    if (typeof value === "number") return value >= min && value <= max;
    return true;
  },
  size: (value, params) => {
    const size = Number(params == null ? void 0 : params.size);
    if (typeof value === "string") return value.length === size;
    if (typeof value === "number") return value === size;
    if (Array.isArray(value)) return value.length === size;
    if (value instanceof File) return value.size / 1024 === size;
    return true;
  },
  in: (value, params) => {
    if (value === null || value === void 0) return true;
    const allowed = String((params == null ? void 0 : params.values) ?? (params == null ? void 0 : params.param0) ?? "").split(",");
    return allowed.includes(String(value));
  },
  not_in: (value, params) => {
    if (value === null || value === void 0) return true;
    const forbidden = String((params == null ? void 0 : params.values) ?? (params == null ? void 0 : params.param0) ?? "").split(",");
    return !forbidden.includes(String(value));
  },
  regex: (value, params) => {
    if (!value || typeof value !== "string") return true;
    const pattern = String((params == null ? void 0 : params.pattern) ?? (params == null ? void 0 : params.regex) ?? "");
    const match = pattern.match(/^\/(.+)\/([gimsuy]*)$/);
    if (match) {
      return new RegExp(match[1], match[2]).test(value);
    }
    return new RegExp(pattern).test(value);
  },
  alpha: (value) => {
    if (!value || typeof value !== "string") return true;
    return /^[a-zA-Z]+$/.test(value);
  },
  alpha_num: (value) => {
    if (!value || typeof value !== "string") return true;
    return /^[a-zA-Z0-9]+$/.test(value);
  },
  alpha_dash: (value) => {
    if (!value || typeof value !== "string") return true;
    return /^[a-zA-Z0-9_-]+$/.test(value);
  },
  boolean: (value) => {
    return [true, false, 0, 1, "0", "1"].includes(value);
  },
  image: (value) => {
    if (!value || !(value instanceof File)) return true;
    return value.type.startsWith("image/");
  },
  mimes: (value, params) => {
    var _a;
    if (!value || !(value instanceof File)) return true;
    const allowed = String((params == null ? void 0 : params.types) ?? (params == null ? void 0 : params.param0) ?? "").split(",");
    const ext = ((_a = value.name.split(".").pop()) == null ? void 0 : _a.toLowerCase()) ?? "";
    return allowed.includes(ext);
  },
  confirmed: () => {
    return true;
  },
  // Server-only rules — always pass client-side
  unique: () => true,
  exists: () => true,
  uuid: () => true,
  date: () => true,
  date_format: () => true,
  after: () => true,
  before: () => true,
  starts_with: (value, params) => {
    if (!value || typeof value !== "string") return true;
    const prefixes = String((params == null ? void 0 : params.values) ?? (params == null ? void 0 : params.param0) ?? "").split(",");
    return prefixes.some((p) => value.startsWith(p));
  },
  ends_with: (value, params) => {
    if (!value || typeof value !== "string") return true;
    const suffixes = String((params == null ? void 0 : params.values) ?? (params == null ? void 0 : params.param0) ?? "").split(",");
    return suffixes.some((s) => value.endsWith(s));
  }
};
function validateAll(data, rules) {
  const errors = {};
  for (const [field, fieldRules] of Object.entries(rules)) {
    const error = validateSingle(field, data[field], fieldRules);
    if (error) {
      errors[field] = error;
    }
  }
  return {
    valid: Object.keys(errors).length === 0,
    errors
  };
}
function validateSingle(field, value, rules) {
  if (rules.some((r) => r.name === "sometimes") && value === void 0) {
    return null;
  }
  if (rules.some((r) => r.name === "nullable") && (value === null || value === void 0 || value === "")) {
    return null;
  }
  for (const rule of rules) {
    if (rule.name === "sometimes" || rule.name === "nullable") continue;
    const handler = ruleHandlers[rule.name];
    if (!handler) continue;
    if (!handler(value, rule.params)) {
      return formatMessage(rule.name, field, rule.params);
    }
  }
  return null;
}
function formatMessage(ruleName, field, params) {
  const localeMessages = messages[currentLocale] ?? {};
  let template = localeMessages[ruleName];
  if (!template) {
    return `The ${humanize(field)} field is invalid.`;
  }
  if (typeof template === "object") {
    template = template.string ?? template.numeric ?? Object.values(template)[0];
  }
  if (typeof template !== "string") {
    return `The ${humanize(field)} field is invalid.`;
  }
  let message = template.replace(/:attribute/g, humanize(field)).replace(/:Attribute/g, capitalize(humanize(field)));
  if (params) {
    for (const [key, val] of Object.entries(params)) {
      message = message.replace(new RegExp(`:${key}`, "g"), String(val));
    }
  }
  return message;
}
function humanize(field) {
  return field.replace(/[_-]/g, " ");
}
function capitalize(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}
let _globalDebugEnabled = typeof import.meta !== "undefined" && false;
function useSmartForm(definition) {
  const { defaults, rules } = parseDefinition(definition);
  const inertiaForm = useForm(defaults);
  let debugOverride = null;
  const clientErrors = reactive({});
  let persistentCallbacks = {};
  const isDebugEnabled = () => debugOverride !== null ? debugOverride : _globalDebugEnabled;
  const log = (step, data) => {
    if (!isDebugEnabled()) return;
    const entry = `[SmartForm] ${step}`;
    console.group(entry);
    if (data !== void 0) console.log(data);
    console.groupEnd();
  };
  const hasRules = Object.keys(rules).length > 0;
  const validate = () => {
    log("validate:start", { data: { ...inertiaForm.data() } });
    for (const key of Object.keys(clientErrors)) {
      delete clientErrors[key];
    }
    const result = validateAll(inertiaForm.data(), rules);
    if (!result.valid) {
      Object.assign(clientErrors, result.errors);
      log("validate:failed", result.errors);
      return false;
    }
    log("validate:passed");
    return true;
  };
  const validateField = (field) => {
    const formData = inertiaForm.data();
    const fieldStr = field;
    const value = formData[fieldStr];
    const fieldRules = rules[fieldStr];
    if (!fieldRules) return true;
    const error = validateSingle(fieldStr, value, fieldRules);
    if (error) {
      clientErrors[fieldStr] = error;
      return false;
    }
    delete clientErrors[fieldStr];
    return true;
  };
  const clearErrors = (...fields) => {
    if (fields.length === 0) {
      for (const key of Object.keys(clientErrors)) {
        delete clientErrors[key];
      }
      inertiaForm.clearErrors();
    } else {
      for (const f of fields) {
        delete clientErrors[f];
      }
      inertiaForm.clearErrors(...fields);
    }
  };
  const submit = (method, url, callOptions) => {
    var _a;
    const options = {
      ...persistentCallbacks,
      ...callOptions
    };
    log(`submit:${method}`, { url, options: Object.keys(options) });
    if (options.beforeValidation) {
      const result = options.beforeValidation();
      if (result === false) {
        log("submit:aborted-by-beforeValidation");
        return;
      }
    }
    if (hasRules && !validate()) {
      log("submit:aborted-client-validation");
      (_a = options.onError) == null ? void 0 : _a.call(options, { ...clientErrors });
      return;
    }
    log("submit:sending");
    const inertiaOptions = {
      preserveScroll: options.preserveScroll ?? true,
      onBefore: options.onBefore,
      onSuccess: (page) => {
        var _a2;
        log("submit:success");
        (_a2 = options.onSuccess) == null ? void 0 : _a2.call(options, page);
      },
      onError: (errors) => {
        var _a2;
        log("submit:server-errors", errors);
        (_a2 = options.onError) == null ? void 0 : _a2.call(options, errors);
      },
      onFinish: () => {
        var _a2;
        log("submit:finish");
        (_a2 = options.onFinish) == null ? void 0 : _a2.call(options);
      }
    };
    if (options.preserveState !== void 0) {
      inertiaOptions.preserveState = options.preserveState;
    }
    if (options.only !== void 0) {
      inertiaOptions.only = options.only;
    }
    if (options.forceFormData !== void 0) {
      inertiaOptions.forceFormData = options.forceFormData;
    }
    if (options.queryStringArrayFormat !== void 0) {
      inertiaOptions.queryStringArrayFormat = options.queryStringArrayFormat;
    }
    if (options.onProgress !== void 0) {
      inertiaOptions.onProgress = options.onProgress;
    }
    inertiaForm[method](url, inertiaOptions);
  };
  const smartForm = reactive({
    // Data proxy
    get data() {
      return inertiaForm.data();
    },
    // State proxies
    get processing() {
      return inertiaForm.processing;
    },
    get isDirty() {
      return inertiaForm.isDirty;
    },
    get wasSuccessful() {
      return inertiaForm.wasSuccessful;
    },
    get recentlySuccessful() {
      return inertiaForm.recentlySuccessful;
    },
    // Unified errors: client + server (server overrides client for same field)
    get errors() {
      return { ...clientErrors, ...inertiaForm.errors };
    },
    clientErrors,
    get serverErrors() {
      return inertiaForm.errors;
    },
    get hasErrors() {
      return Object.keys(clientErrors).length > 0 || Object.keys(inertiaForm.errors).length > 0;
    },
    // Validation
    validate,
    validateField,
    clearErrors(...fields) {
      clearErrors(...fields);
      return smartForm;
    },
    // Fill form fields from a data object
    fill(data) {
      for (const [key, value] of Object.entries(data)) {
        if (key in inertiaForm) {
          inertiaForm[key] = value;
        }
      }
      return smartForm;
    },
    // Fluent API (returns this for chaining)
    onSuccess(cb) {
      persistentCallbacks.onSuccess = cb;
      return smartForm;
    },
    onError(cb) {
      persistentCallbacks.onError = cb;
      return smartForm;
    },
    beforeValidation(cb) {
      persistentCallbacks.beforeValidation = cb;
      return smartForm;
    },
    onFinish(cb) {
      persistentCallbacks.onFinish = cb;
      return smartForm;
    },
    debug(enabled = true) {
      debugOverride = enabled;
      log("debug:enabled");
      return smartForm;
    },
    // HTTP methods (terminal — fire request)
    get: (url, opts) => submit("get", url, opts),
    post: (url, opts) => submit("post", url, opts),
    put: (url, opts) => submit("put", url, opts),
    patch: (url, opts) => submit("patch", url, opts),
    delete: (url, opts) => submit("delete", url, opts),
    // Utility
    reset(...fields) {
      if (fields.length === 0) {
        inertiaForm.reset();
      } else {
        inertiaForm.reset(...fields);
      }
      clearErrors(...fields);
    },
    transform(cb) {
      inertiaForm.transform(cb);
      return smartForm;
    },
    // Escape hatch
    inertiaForm
  });
  return new Proxy(smartForm, {
    get(target, prop, receiver) {
      if (Reflect.has(target, prop)) {
        return Reflect.get(target, prop, receiver);
      }
      return Reflect.get(inertiaForm, prop, inertiaForm);
    },
    set(target, prop, value, receiver) {
      if (Reflect.has(target, prop)) {
        return Reflect.set(target, prop, value, receiver);
      }
      return Reflect.set(inertiaForm, prop, value, inertiaForm);
    }
  });
}
function parseDefinition(def) {
  const defaults = {};
  const rules = {};
  for (const [field, config] of Object.entries(def)) {
    if (Array.isArray(config)) {
      const manual = config;
      defaults[field] = manual[0];
      rules[field] = parseManualRules(manual[1]);
    } else if (config && typeof config === "object" && "rules" in config) {
      const generated = config;
      defaults[field] = generated.default;
      rules[field] = generated.rules;
    }
  }
  return { defaults, rules };
}
function parseManualRules(rawRules) {
  return rawRules.map((rule) => {
    if (!rule.includes(":")) {
      return { name: rule };
    }
    const [name, ...paramParts] = rule.split(":");
    const paramString = paramParts.join(":");
    const values = paramString.split(",");
    const params = {};
    if (values.length === 1) {
      const v = values[0];
      params[name] = isNaN(Number(v)) ? v : Number(v);
    } else {
      values.forEach((v, i) => {
        params[`param${i}`] = isNaN(Number(v)) ? v : Number(v);
      });
    }
    return { name, params };
  });
}
export {
  useSmartForm as u
};
