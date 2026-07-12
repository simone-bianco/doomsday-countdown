const SaveCountdownDataRules = {
  slug: { default: null, rules: [{ name: "required" }, { name: "string" }, { name: "max", params: { max: 140 } }, { name: "regex", params: { pattern: "/^[a-z0-9]+(?:-[a-z0-9]+)*$/" } }] },
  title: { default: [], rules: [{ name: "required" }, { name: "array" }, { name: "required_array_keys", params: { param0: "en" } }] },
  summary: { default: [], rules: [{ name: "required" }, { name: "array" }, { name: "required_array_keys", params: { param0: "en" } }] },
  description: { default: [], rules: [{ name: "nullable" }, { name: "array" }] },
  causes: { default: [], rules: [{ name: "nullable" }, { name: "array" }] },
  consequences: { default: [], rules: [{ name: "nullable" }, { name: "array" }] },
  recommended_actions: { default: [], rules: [{ name: "nullable" }, { name: "array" }] },
  severity: { default: null, rules: [{ name: "string" }, { name: "required" }] },
  status: { default: null, rules: [{ name: "string" }, { name: "required" }] },
  target_date: { default: null, rules: [{ name: "string" }, { name: "nullable" }, { name: "date" }] },
  image_path: { default: null, rules: [{ name: "required" }, { name: "string" }, { name: "max", params: { max: 255 } }] },
  accent_color: { default: null, rules: [{ name: "required" }, { name: "string" }, { name: "max", params: { max: 7 } }, { name: "regex", params: { pattern: "/^#[0-9A-Fa-f]{6}$/" } }] },
  sort_order: { default: null, rules: [{ name: "numeric" }, { name: "required" }, { name: "integer" }, { name: "min", params: { min: 0 } }] },
  is_published: { default: null, rules: [{ name: "required" }, { name: "boolean" }] }
};
const SaveInitiativeDataRules = {
  locale: { default: null, rules: [{ name: "string" }, { name: "required" }] },
  type: { default: null, rules: [{ name: "string" }, { name: "required" }] },
  title: { default: null, rules: [{ name: "required" }, { name: "string" }, { name: "max", params: { max: 255 } }] },
  excerpt: { default: null, rules: [{ name: "required" }, { name: "string" }, { name: "max", params: { max: 1e3 } }] },
  body: { default: null, rules: [{ name: "nullable" }, { name: "string" }] },
  organization: { default: null, rules: [{ name: "nullable" }, { name: "string" }, { name: "max", params: { max: 255 } }] },
  url: { default: null, rules: [{ name: "required" }, { name: "string" }, { name: "max", params: { max: 500 } }] },
  image_path: { default: null, rules: [{ name: "nullable" }, { name: "string" }, { name: "max", params: { max: 255 } }] },
  cta_label: { default: null, rules: [{ name: "nullable" }, { name: "string" }, { name: "max", params: { max: 80 } }] },
  starts_at: { default: null, rules: [{ name: "string" }, { name: "nullable" }, { name: "date" }] },
  ends_at: { default: null, rules: [{ name: "string" }, { name: "nullable" }, { name: "date" }] },
  sort_order: { default: null, rules: [{ name: "numeric" }, { name: "required" }, { name: "integer" }, { name: "min", params: { min: 0 } }] },
  is_featured: { default: null, rules: [{ name: "required" }, { name: "boolean" }] },
  content_type: { default: null, rules: [{ name: "nullable" }] },
  preview_image_url: { default: null, rules: [{ name: "nullable" }] },
  embed_url: { default: null, rules: [{ name: "nullable" }] },
  external_provider: { default: null, rules: [{ name: "nullable" }] },
  external_id: { default: null, rules: [{ name: "nullable" }] }
};
const SaveNewsDataRules = {
  locale: { default: null, rules: [{ name: "string" }, { name: "required" }] },
  title: { default: null, rules: [{ name: "required" }, { name: "string" }, { name: "max", params: { max: 255 } }] },
  excerpt: { default: null, rules: [{ name: "required" }, { name: "string" }, { name: "max", params: { max: 1e3 } }] },
  source_name: { default: null, rules: [{ name: "nullable" }, { name: "string" }, { name: "max", params: { max: 255 } }] },
  source_url: { default: null, rules: [{ name: "nullable" }, { name: "string" }, { name: "max", params: { max: 2e3 } }] },
  image_path: { default: null, rules: [{ name: "nullable" }, { name: "string" }, { name: "max", params: { max: 255 } }] },
  published_at: { default: null, rules: [{ name: "string" }, { name: "nullable" }, { name: "date" }] },
  sort_order: { default: null, rules: [{ name: "numeric" }, { name: "required" }, { name: "integer" }, { name: "min", params: { min: 0 } }] },
  is_featured: { default: null, rules: [{ name: "required" }, { name: "boolean" }] },
  content_type: { default: null, rules: [{ name: "nullable" }] },
  preview_image_url: { default: null, rules: [{ name: "nullable" }] },
  embed_url: { default: null, rules: [{ name: "nullable" }] },
  external_provider: { default: null, rules: [{ name: "nullable" }] },
  external_id: { default: null, rules: [{ name: "nullable" }] }
};
const SaveProjectionDataRules = {
  type: { default: null, rules: [{ name: "string" }, { name: "required" }] },
  target_date: { default: null, rules: [{ name: "string" }, { name: "nullable" }, { name: "date" }] },
  title: { default: [], rules: [{ name: "required" }, { name: "array" }, { name: "required_array_keys", params: { param0: "en" } }] },
  summary: { default: [], rules: [{ name: "nullable" }, { name: "array" }] },
  confidence_score: { default: null, rules: [{ name: "numeric" }, { name: "required" }, { name: "integer" }, { name: "min", params: { min: 0 } }, { name: "max", params: { max: 100 } }] },
  probability_score: { default: null, rules: [{ name: "numeric" }, { name: "required" }, { name: "integer" }, { name: "min", params: { min: 0 } }, { name: "max", params: { max: 100 } }] },
  trend: { default: null, rules: [{ name: "required" }, { name: "string" }, { name: "max", params: { max: 80 } }] },
  methodology: { default: [], rules: [{ name: "nullable" }, { name: "array" }] },
  sort_order: { default: null, rules: [{ name: "numeric" }, { name: "required" }, { name: "integer" }, { name: "min", params: { min: 0 } }] }
};
const SaveVisualizationDataRules = {
  key: { default: null, rules: [{ name: "required" }, { name: "string" }, { name: "max", params: { max: 120 } }, { name: "regex", params: { pattern: "/^[a-z0-9]+(?:[_-][a-z0-9]+)*$/" } }] },
  type: { default: null, rules: [{ name: "string" }, { name: "required" }] },
  title: { default: [], rules: [{ name: "required" }, { name: "array" }, { name: "required_array_keys", params: { param0: "en" } }] },
  description: { default: [], rules: [{ name: "nullable" }, { name: "array" }] },
  sources: { default: [], rules: [{ name: "required" }, { name: "array" }] },
  reasoning: { default: [], rules: [{ name: "required" }, { name: "array" }, { name: "required_array_keys", params: { param0: "en" } }] },
  payload: { default: [], rules: [{ name: "required" }, { name: "array" }] },
  schema_version: { default: null, rules: [{ name: "numeric" }, { name: "required" }, { name: "integer" }, { name: "min", params: { min: 1 } }] },
  sort_order: { default: null, rules: [{ name: "numeric" }, { name: "required" }, { name: "integer" }, { name: "min", params: { min: 0 } }] }
};
const LoginDataRules = {
  email: { default: null, rules: [{ name: "string" }, { name: "required" }, { name: "email", params: { param0: "rfc" } }] },
  password: { default: null, rules: [{ name: "required" }, { name: "string" }] }
};
const SaveOpenAiKeyDataRules = {
  label: { default: null, rules: [{ name: "nullable" }, { name: "string" }, { name: "max", params: { max: 255 } }] },
  key: { default: null, rules: [{ name: "nullable" }, { name: "string" }, { name: "max", params: { max: 500 } }] },
  base_limit_type: { default: null, rules: [{ name: "string" }, { name: "required" }] },
  max_base_usage: { default: null, rules: [{ name: "nullable" }, { name: "numeric" }] },
  free_limit_type: { default: null, rules: [{ name: "string" }, { name: "required" }] },
  max_free_usage: { default: null, rules: [{ name: "nullable" }, { name: "numeric" }] },
  is_active: { default: null, rules: [{ name: "required" }, { name: "boolean" }] }
};
const SaveUserDataRules = {
  name: { default: null, rules: [{ name: "required" }, { name: "string" }, { name: "max", params: { max: 255 } }] },
  email: { default: null, rules: [{ name: "string" }, { name: "required" }, { name: "email", params: { param0: "rfc" } }, { name: "max", params: { max: 255 } }] },
  password: { default: null, rules: [{ name: "nullable" }, { name: "string" }, { name: "max", params: { max: 255 } }] }
};
export {
  LoginDataRules as L,
  SaveInitiativeDataRules as S,
  SaveNewsDataRules as a,
  SaveCountdownDataRules as b,
  SaveProjectionDataRules as c,
  SaveVisualizationDataRules as d,
  SaveOpenAiKeyDataRules as e,
  SaveUserDataRules as f
};
