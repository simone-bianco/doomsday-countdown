export type AboutPageData = {
app_name: string;
current_locale: string;
languages: Array<LanguageOptionData>;
title: string;
subtitle: string;
sections: Array<any>;
};
export type AgentPromptData = {
message: string;
};
export type BaseLimitType = 'fixed' | 'unlimited' | 'none';
export type CountdownDetailData = {
id: number;
slug: string;
title: string;
summary: string;
description: string;
image_url: string;
icon: string;
severity: string;
timer: CountdownTimerData;
main_projection: ProjectionData | null;
causes: Array<any>;
consequences: Array<any>;
recommended_actions: Array<any>;
projections: Array<ProjectionData>;
visualizations: Array<VisualizationData>;
news: Array<NewsData>;
};
export type CountdownIndexData = {
id: number;
slug: string;
title: string;
summary: string;
image_url: string;
icon: string;
status: string;
severity: string;
sort_order: number;
timer: CountdownTimerData;
main_projection: ProjectionData | null;
url: string;
is_selected: boolean;
};
export type CountdownPageData = {
app_name: string;
current_locale: string;
languages: Array<LanguageOptionData>;
hero: Array<any>;
countdowns: Array<CountdownIndexData>;
selected_countdown: CountdownDetailData | null;
};
export type CountdownSeverity = 'low' | 'moderate' | 'elevated' | 'high' | 'severe' | 'critical' | 'existential';
export type CountdownStatus = 'draft' | 'monitoring' | 'active' | 'stabilized' | 'averted' | 'occurred' | 'archived';
export type CountdownTimerData = {
target_date: string | null;
estimated_label: string;
is_elapsed: boolean;
};
export type FreeLimitType = 'daily' | 'monthly' | 'none';
export type LanguageOptionData = {
code: string;
label: string;
native_label: string;
flag: string;
url: string;
is_current: boolean;
};
export type LoginData = {
email: string;
password: string;
};
export type NewsData = {
locale: string;
title: string;
excerpt: string;
source_name: string | null;
source_url: string | null;
image_url: string | null;
published_at: string | null;
is_featured: boolean;
};
export type NewsLocale = 'all' | 'en' | 'it' | 'fr' | 'de' | 'es' | 'nl' | 'sv' | 'pl';
export type ProjectionData = {
type: string;
target_date: string | null;
title: string;
summary: string;
confidence_score: number;
probability_score: number;
trend: string;
visualizations: Array<VisualizationData>;
};
export type ProjectionType = 'optimistic' | 'neutral' | 'pessimistic' | 'other';
export type RotableKeyData = {
service: string | null;
key: string | null;
base_limit_type: string;
max_base_usage: number | null;
current_base_usage: number;
free_limit_type: string;
max_free_usage: number | null;
current_free_usage: number;
free_usage_resets_at: any | null;
reset_timezone: string;
extra_data: Array<any> | null;
is_active: boolean;
is_depleted: boolean;
depleted_at: string | null;
created_at: string | null;
updated_at: string | null;
last_used_at: string | null;
};
export type SaveOpenAiKeyData = {
label: string | null;
key: string | null;
base_limit_type: string;
max_base_usage: number | null;
free_limit_type: string;
max_free_usage: number | null;
is_active: boolean;
};
export type SaveUserData = {
name: string;
email: string;
password: string | null;
};
export type VisualizationData = {
key: string;
type: string;
title: string;
description: string | null;
payload: Array<any>;
schema_version: number;
sort_order: number;
};
export type VisualizationType = 'line' | 'area' | 'bar' | 'sparkline' | 'kpi' | 'timeline' | 'risk_matrix' | 'custom';
