import type { ChartPayload, KpiPayload, LocalizedText, VisualizationPayload } from '@/Components/Backoffice/Doomsday/types';

export function localizedText(value = ''): LocalizedText {
    return { en: value };
}

export function first(values: readonly string[], fallback: string): string {
    return values[0] ?? fallback;
}

export function isoDate(value: string | null | undefined): string | null {
    return value ? value.slice(0, 10) : null;
}

export function optionItems(values: readonly string[]) {
    return values.map((value) => ({ value, label: value }));
}

export function isRecord(value: unknown): value is Record<string, unknown> {
    return typeof value === 'object' && value !== null && !Array.isArray(value);
}

function numericValues(value: string): number[] {
    return value.split(',').map((item) => Number(item.trim())).filter(Number.isFinite);
}

export function defaultPayload(type: string): VisualizationPayload {
    if (type === 'kpi') {
        return { items: [{ label: 'Risk index', value: '42', direction: 'up', sparkline: [24, 36, 42] }] } satisfies KpiPayload;
    }

    return {
        labels: ['Now', 'Next', 'Later'],
        unit: '%',
        series: [{ name: 'Scenario', values: [20, 42, 64] }],
    } satisfies ChartPayload;
}

export function parseChartPayload(labelsText: string, unit: string, seriesText: string): ChartPayload {
    const labels = labelsText.split(',').map((label) => label.trim()).filter((label) => label !== '');
    const series = seriesText.split('\n').map((line, index) => {
        const [rawName, rawValues] = line.includes(':') ? line.split(':', 2) : [`Series ${index + 1}`, line];
        return { name: rawName.trim() || `Series ${index + 1}`, values: numericValues(rawValues ?? '') };
    }).filter((item) => item.values.length > 0);

    return { labels, unit: unit.trim() || undefined, series };
}

export function parseKpiPayload(itemsText: string): KpiPayload {
    const items = itemsText.split('\n').map((line) => {
        const [label = '', value = '', direction = 'up', sparkline = ''] = line.split('|').map((item) => item.trim());
        return { label, value, direction, sparkline: numericValues(sparkline) };
    }).filter((item) => item.label !== '' && item.value !== '');

    return { items };
}

export function chartText(payload: unknown): { labels: string; unit: string; series: string } {
    if (!isRecord(payload)) {
        return { labels: 'Now, Next, Later', unit: '%', series: 'Scenario: 20, 42, 64' };
    }

    const labels = Array.isArray(payload.labels) ? payload.labels.map(String).join(', ') : 'Now, Next, Later';
    const unit = typeof payload.unit === 'string' ? payload.unit : '';
    const rawSeries = Array.isArray(payload.series) ? payload.series : [];
    const series = rawSeries.map((item, index) => {
        if (isRecord(item)) {
            const name = typeof item.name === 'string' ? item.name : `Series ${index + 1}`;
            const values = Array.isArray(item.values) ? item.values.map(String).join(', ') : '';
            return `${name}: ${values}`;
        }
        return String(item);
    }).join('\n') || 'Scenario: 20, 42, 64';

    return { labels, unit, series };
}

export function kpiText(payload: unknown): string {
    if (!isRecord(payload) || !Array.isArray(payload.items)) {
        return 'Risk index|42|up|24,36,42';
    }

    return payload.items.filter(isRecord).map((item) => {
        const label = String(item.label ?? '');
        const value = String(item.value ?? '');
        const direction = String(item.direction ?? 'up');
        const sparkline = Array.isArray(item.sparkline) ? item.sparkline.map(String).join(',') : '';
        return `${label}|${value}|${direction}|${sparkline}`;
    }).join('\n') || 'Risk index|42|up|24,36,42';
}
