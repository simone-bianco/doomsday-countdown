import type { ChartPayload, ChartXAxisType, ChartYAxisFormat, KpiPayload, LocalizedText, VisualizationPayload } from '@/Components/Backoffice/Doomsday/types';

export const chartXAxisTypes = ['temporal', 'ordinal', 'category'] as const;
export const chartYAxisFormats = ['integer', 'decimal', 'percent', 'currency'] as const;

export type ChartPayloadText = {
    readonly labels: string;
    readonly xLabel: string;
    readonly xType: ChartXAxisType;
    readonly yLabel: string;
    readonly yUnit: string;
    readonly yFormat: ChartYAxisFormat;
    readonly series: string;
};

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

function lines(value: string): string[] {
    return value.split('\n').map((item) => item.trim()).filter((item) => item !== '');
}

function isChartXAxisType(value: unknown): value is ChartXAxisType {
    return typeof value === 'string' && chartXAxisTypes.includes(value as ChartXAxisType);
}

function isChartYAxisFormat(value: unknown): value is ChartYAxisFormat {
    return typeof value === 'string' && chartYAxisFormats.includes(value as ChartYAxisFormat);
}

export function defaultPayload(type: string): VisualizationPayload {
    if (type === 'kpi') {
        return { items: [{ label: 'Risk index', value: '42', direction: 'up', sparkline: [24, 36, 42] }] } satisfies KpiPayload;
    }

    const isBar = type === 'bar';

    return {
        labels: isBar ? ['Scenario A', 'Scenario B', 'Scenario C'] : ['Now', 'Next', 'Later'],
        series: [{ name: 'Scenario', values: [20, 42, 64] }],
        axes: {
            x: { label: isBar ? 'Category' : 'Sequence', type: isBar ? 'category' : 'ordinal' },
            y: { label: 'Value', unit: '%', format: 'percent' },
        },
    } satisfies ChartPayload;
}

export function parseChartPayload(text: ChartPayloadText): ChartPayload {
    const labels = text.labels.split(',').map((label) => label.trim()).filter((label) => label !== '');
    const series = lines(text.series).map((line, index) => {
        const [rawName, rawValues] = line.includes(':') ? line.split(':', 2) : [`Series ${index + 1}`, line];
        return { name: rawName.trim() || `Series ${index + 1}`, values: numericValues(rawValues ?? '') };
    }).filter((item) => item.values.length > 0);

    return {
        labels,
        series,
        axes: {
            x: { label: text.xLabel.trim(), type: text.xType },
            y: { label: text.yLabel.trim(), unit: text.yUnit.trim(), format: text.yFormat },
        },
    };
}

export function parseKpiPayload(itemsText: string): KpiPayload {
    const items = itemsText.split('\n').map((line) => {
        const [label = '', value = '', direction = 'up', sparkline = ''] = line.split('|').map((item) => item.trim());
        return { label, value, direction, sparkline: numericValues(sparkline) };
    }).filter((item) => item.label !== '' && item.value !== '');

    return { items };
}

export function chartText(payload: unknown): ChartPayloadText {
    if (!isRecord(payload)) {
        return {
            labels: 'Now, Next, Later',
            xLabel: 'Sequence',
            xType: 'ordinal',
            yLabel: 'Value',
            yUnit: '%',
            yFormat: 'percent',
            series: 'Scenario: 20, 42, 64',
        };
    }

    const axes = isRecord(payload.axes) ? payload.axes : {};
    const xAxis = isRecord(axes.x) ? axes.x : {};
    const yAxis = isRecord(axes.y) ? axes.y : {};
    const rawSeries = Array.isArray(payload.series) ? payload.series : [];
    const series = rawSeries.every((item) => Number.isFinite(Number(item)))
        ? `Series: ${rawSeries.map(String).join(', ')}`
        : rawSeries.map((item, index) => {
            if (!isRecord(item)) {
                return '';
            }
            const name = typeof item.name === 'string' ? item.name : `Series ${index + 1}`;
            const values = Array.isArray(item.values) ? item.values.map(String).join(', ') : '';
            return `${name}: ${values}`;
        }).filter((item) => item !== '').join('\n');

    return {
        labels: Array.isArray(payload.labels) ? payload.labels.map(String).join(', ') : 'Now, Next, Later',
        xLabel: typeof xAxis.label === 'string' ? xAxis.label : 'Sequence',
        xType: isChartXAxisType(xAxis.type) ? xAxis.type : 'ordinal',
        yLabel: typeof yAxis.label === 'string' ? yAxis.label : 'Value',
        yUnit: typeof yAxis.unit === 'string' ? yAxis.unit : '%',
        yFormat: isChartYAxisFormat(yAxis.format) ? yAxis.format : 'percent',
        series: series || 'Scenario: 20, 42, 64',
    };
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
