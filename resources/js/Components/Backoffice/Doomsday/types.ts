import type { PaginationLink, PaginationMeta } from '@simone-bianco/vue-ui-components';

export type BackofficeOptions = {
    readonly countdown_severities: readonly string[];
    readonly countdown_statuses: readonly string[];
    readonly projection_types: readonly string[];
    readonly visualization_types: readonly string[];
    readonly news_locales: readonly string[];
    readonly initiative_locales: readonly string[];
    readonly initiative_types: readonly string[];
    readonly localized_fields: readonly string[];
};

export type BackofficePaginatedCollection<T> = {
    readonly data: readonly T[];
    readonly links?: readonly PaginationLink[];
    readonly meta: PaginationMeta;
    readonly filters?: {
        readonly search?: string | null;
        readonly sort?: string | null;
        readonly direction?: string | null;
    };
};

export type BackofficeRelationCollection<T> = readonly T[] | BackofficePaginatedCollection<T>;

export type LocalizedText = Record<string, string>;
export type LocalizedList = Record<string, string[]>;

export type ChartXAxisType = 'temporal' | 'ordinal' | 'category';
export type ChartYAxisFormat = 'integer' | 'decimal' | 'percent' | 'currency';

export type ChartPayload = {
    readonly labels: string[];
    readonly series: number[] | Array<{ readonly name: string; readonly color?: string; readonly values: number[] }>;
    readonly axes: {
        readonly x: { readonly label: string; readonly type: ChartXAxisType };
        readonly y: { readonly label: string; readonly unit: string; readonly format: ChartYAxisFormat };
    };
};

export type KpiPayload = {
    readonly items: Array<{ readonly label: string; readonly value: string; readonly direction?: string; readonly sparkline?: number[] }>;
};

export type VisualizationPayload = ChartPayload | KpiPayload | Record<string, unknown>;

export type CountdownSummary = {
    readonly id: number;
    readonly slug: string;
    readonly title: LocalizedText;
    readonly summary: LocalizedText;
    readonly image_path?: string | null;
    readonly severity: string;
    readonly status: string;
    readonly is_published: boolean;
    readonly sort_order: number;
    readonly projections_count: number;
    readonly visualizations_count: number;
    readonly news_count: number;
    readonly initiatives_count: number;
    readonly updated_at: string | null;
};

export type ProjectionRecord = {
    readonly id: number;
    readonly type: string;
    readonly target_date: string | null;
    readonly title: LocalizedText;
    readonly summary: LocalizedText | null;
    readonly confidence_score: number;
    readonly probability_score: number;
    readonly trend: string;
    readonly methodology: LocalizedText | null;
    readonly sort_order: number;
    readonly visualizations: readonly VisualizationRecord[];
};

export type VisualizationRecord = {
    readonly id: number;
    readonly key: string;
    readonly type: string;
    readonly title: LocalizedText;
    readonly description: LocalizedText | null;
    readonly sources: readonly string[];
    readonly reasoning: LocalizedText;
    readonly payload: unknown;
    readonly schema_version: number;
    readonly sort_order: number;
};

export type NewsRecord = {
    readonly id: number;
    readonly locale: string;
    readonly title: string;
    readonly excerpt: string;
    readonly content_type?: string | null;
    readonly source_name: string | null;
    readonly source_url: string | null;
    readonly preview_image_url?: string | null;
    readonly embed_url?: string | null;
    readonly external_provider?: string | null;
    readonly external_id?: string | null;
    readonly image_path?: string | null;
    readonly published_at: string | null;
    readonly sort_order: number;
    readonly is_featured: boolean;
};

export type InitiativeRecord = {
    readonly id: number;
    readonly locale: string;
    readonly type: string;
    readonly title: string;
    readonly excerpt: string;
    readonly body?: string | null;
    readonly organization: string | null;
    readonly url: string;
    readonly content_type?: string | null;
    readonly preview_image_url?: string | null;
    readonly embed_url?: string | null;
    readonly external_provider?: string | null;
    readonly external_id?: string | null;
    readonly image_path?: string | null;
    readonly cta_label?: string | null;
    readonly starts_at: string | null;
    readonly ends_at: string | null;
    readonly sort_order: number;
    readonly is_featured: boolean;
};

export type CountdownDetail = CountdownSummary & {
    readonly description: LocalizedText | null;
    readonly causes: LocalizedList | null;
    readonly consequences: LocalizedList | null;
    readonly recommended_actions: LocalizedList | null;
    readonly target_date: string | null;
    readonly image_path: string;
    readonly accent_color: string;
    readonly projections: BackofficeRelationCollection<ProjectionRecord>;
    readonly visualizations: BackofficeRelationCollection<VisualizationRecord>;
    readonly news: BackofficeRelationCollection<NewsRecord>;
    readonly initiatives: BackofficeRelationCollection<InitiativeRecord>;
};
