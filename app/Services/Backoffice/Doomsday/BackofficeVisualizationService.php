<?php

declare(strict_types=1);

namespace App\Services\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveVisualizationData;
use App\Enums\VisualizationType;
use App\Models\Countdown;
use App\Models\Projection;
use App\Models\Visualization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BackofficeVisualizationService
{
    private const CHART_SCHEMA_VERSION = 2;

    /** @var array<int, string> */
    private const CHART_X_TYPES = ['temporal', 'ordinal', 'category'];

    /** @var array<int, string> */
    private const CHART_Y_FORMATS = ['integer', 'decimal', 'percent', 'currency'];

    public function __construct(private readonly BackofficeDoomsdayInputNormalizer $normalizer) {}

    public function create(Countdown|Projection $visualizable, SaveVisualizationData $data): Visualization
    {
        $this->validateEvidence($data);
        $this->validatePayload($data);

        return $visualizable->visualizations()->create($this->attributes($data));
    }

    public function update(Countdown|Projection $visualizable, Visualization $visualization, SaveVisualizationData $data): Visualization
    {
        $this->assertBelongsToVisualizable($visualizable, $visualization);
        $this->validateEvidence($data);
        $this->validatePayload($data);
        $visualization->update($this->attributes($data));

        return $visualization->refresh();
    }

    public function delete(Countdown|Projection $visualizable, Visualization $visualization): void
    {
        $this->assertBelongsToVisualizable($visualizable, $visualization);
        $visualization->delete();
    }

    /** @return array<string, mixed> */
    private function attributes(SaveVisualizationData $data): array
    {
        return [
            'key' => $data->key,
            'type' => $data->type,
            'title' => $this->normalizer->localizedText($data->title, 'title'),
            'description' => $this->normalizer->optionalLocalizedText($data->description),
            'sources' => array_values(array_unique(array_map(static fn (string $source): string => trim($source), $data->sources))),
            'reasoning' => $this->normalizer->localizedText($data->reasoning, 'reasoning'),
            'payload' => $data->payload,
            'schema_version' => $data->schema_version,
            'sort_order' => $data->sort_order,
        ];
    }

    public function assertBelongsToVisualizable(Model $visualizable, Visualization $visualization): void
    {
        if ((int) $visualization->visualizable_id !== (int) $visualizable->getKey()) {
            throw new NotFoundHttpException;
        }

        if ($visualization->visualizable_type !== $visualizable->getMorphClass()) {
            throw new NotFoundHttpException;
        }
    }

    private function validateEvidence(SaveVisualizationData $data): void
    {
        if (! array_is_list($data->sources) || $data->sources === []) {
            throw ValidationException::withMessages(['sources' => 'At least one HTTPS source is required.']);
        }

        foreach ($data->sources as $source) {
            if (! is_string($source) || filter_var($source, FILTER_VALIDATE_URL) === false || parse_url($source, PHP_URL_SCHEME) !== 'https') {
                throw ValidationException::withMessages(['sources' => 'Every source must be a valid HTTPS URL.']);
            }
        }
    }

    private function validatePayload(SaveVisualizationData $data): void
    {
        match ($data->type) {
            VisualizationType::Line->value, VisualizationType::Area->value, VisualizationType::Bar->value => $this->validateChartPayload($data->payload, $data->type, $data->schema_version),
            VisualizationType::Sparkline->value => $this->validateSeriesPayload($data->payload),
            VisualizationType::Kpi->value => $this->validateKpiPayload($data->payload),
            default => $this->validateNonEmptyPayload($data->payload),
        };
    }

    /** @param array<string, mixed> $payload */
    private function validateChartPayload(array $payload, string $type, int $schemaVersion): void
    {
        if ($schemaVersion !== self::CHART_SCHEMA_VERSION) {
            throw ValidationException::withMessages(['schema_version' => 'Line, area and bar visualizations require schema version 2.']);
        }

        $labels = $payload['labels'] ?? null;
        $series = $payload['series'] ?? null;
        if (! is_array($labels) || ! array_is_list($labels) || $labels === [] || ! is_array($series) || ! array_is_list($series) || $series === []) {
            throw ValidationException::withMessages(['payload' => 'Schema v2 charts require non-empty labels and series arrays.']);
        }

        foreach ($labels as $label) {
            if (! is_string($label) || trim($label) === '') {
                throw ValidationException::withMessages(['payload.labels' => 'Each chart label must be a non-empty string.']);
            }
        }

        $this->validateChartSeries($series, count($labels));

        $axes = $payload['axes'] ?? null;
        $xAxis = is_array($axes) ? ($axes['x'] ?? null) : null;
        $yAxis = is_array($axes) ? ($axes['y'] ?? null) : null;
        if (! is_array($xAxis) || ! is_array($yAxis)) {
            throw ValidationException::withMessages(['payload.axes' => 'Schema v2 charts require x and y axis definitions.']);
        }

        $xLabel = $xAxis['label'] ?? null;
        $xType = $xAxis['type'] ?? null;
        if (! is_string($xLabel) || trim($xLabel) === '') {
            throw ValidationException::withMessages(['payload.axes.x.label' => 'The x-axis label is required.']);
        }
        if (! is_string($xType) || ! in_array($xType, self::CHART_X_TYPES, true)) {
            throw ValidationException::withMessages(['payload.axes.x.type' => 'The x-axis type must be temporal, ordinal or category.']);
        }
        if ($type === VisualizationType::Bar->value && $xType !== 'category') {
            throw ValidationException::withMessages(['payload.axes.x.type' => 'Bar visualizations require a category x-axis.']);
        }
        if (in_array($type, [VisualizationType::Line->value, VisualizationType::Area->value], true) && $xType === 'category') {
            throw ValidationException::withMessages(['payload.axes.x.type' => 'Line and area visualizations require a temporal or ordinal x-axis.']);
        }

        $yLabel = $yAxis['label'] ?? null;
        $yUnit = $yAxis['unit'] ?? null;
        $yFormat = $yAxis['format'] ?? null;
        if (! is_string($yLabel) || trim($yLabel) === '') {
            throw ValidationException::withMessages(['payload.axes.y.label' => 'The y-axis label is required.']);
        }
        if (! is_string($yUnit) || trim($yUnit) === '') {
            throw ValidationException::withMessages(['payload.axes.y.unit' => 'The shared y-axis unit is required.']);
        }
        if (! is_string($yFormat) || ! in_array($yFormat, self::CHART_Y_FORMATS, true)) {
            throw ValidationException::withMessages(['payload.axes.y.format' => 'The y-axis format must be integer, decimal, percent or currency.']);
        }

    }

    /** @param array<int, mixed> $series */
    private function validateChartSeries(array $series, int $labelCount): void
    {
        if ($this->isNumericList($series)) {
            $this->assertSeriesLength($series, $labelCount);

            return;
        }

        foreach ($series as $item) {
            if (! is_array($item) || trim((string) ($item['name'] ?? '')) === '' || ! isset($item['values']) || ! is_array($item['values']) || ! array_is_list($item['values'])) {
                throw ValidationException::withMessages(['payload.series' => 'Each chart series requires a name and values array.']);
            }
            if (array_key_exists('unit', $item) || array_key_exists('format', $item)) {
                throw ValidationException::withMessages(['payload.series' => 'All chart series must use the shared axes.y unit and format.']);
            }

            $this->assertSeriesLength($item['values'], $labelCount);
        }
    }

    /** @param array<string, mixed> $payload */
    private function validateSeriesPayload(array $payload): void
    {
        $labels = $payload['labels'] ?? null;
        $series = $payload['series'] ?? null;

        if (! is_array($labels) || $labels === [] || ! is_array($series) || $series === []) {
            throw ValidationException::withMessages(['payload' => 'Sparkline payloads require labels and series arrays.']);
        }

        $labelCount = count($labels);
        foreach ($labels as $label) {
            if (! is_scalar($label) || trim((string) $label) === '') {
                throw ValidationException::withMessages(['payload.labels' => 'Each label must be a non-empty value.']);
            }
        }

        if ($this->isNumericList($series)) {
            $this->assertSeriesLength($series, $labelCount);

            return;
        }

        foreach ($series as $item) {
            if (! is_array($item) || ! isset($item['values']) || ! is_array($item['values'])) {
                throw ValidationException::withMessages(['payload.series' => 'Each series item must provide a values array.']);
            }

            $this->assertSeriesLength($item['values'], $labelCount);
        }
    }

    /** @param array<string, mixed> $payload */
    private function validateKpiPayload(array $payload): void
    {
        $items = $payload['items'] ?? null;
        if (! is_array($items) || $items === []) {
            throw ValidationException::withMessages(['payload.items' => 'KPI payload requires at least one item.']);
        }

        foreach ($items as $item) {
            if (! is_array($item) || trim((string) ($item['label'] ?? '')) === '' || ! array_key_exists('value', $item)) {
                throw ValidationException::withMessages(['payload.items' => 'Each KPI item requires label and value.']);
            }
        }
    }

    /** @param array<string, mixed> $payload */
    private function validateNonEmptyPayload(array $payload): void
    {
        if ($payload === []) {
            throw ValidationException::withMessages(['payload' => 'Visualization payload cannot be empty.']);
        }
    }

    /** @param array<int|string, mixed> $values */
    private function assertSeriesLength(array $values, int $expected): void
    {
        if (count($values) !== $expected) {
            throw ValidationException::withMessages(['payload.series' => 'Series values must have the same length as labels.']);
        }

        foreach ($values as $value) {
            if (! is_numeric($value)) {
                throw ValidationException::withMessages(['payload.series' => 'Series values must be numeric.']);
            }
        }
    }

    /** @param array<int|string, mixed> $series */
    private function isNumericList(array $series): bool
    {
        foreach ($series as $value) {
            if (! is_numeric($value)) {
                return false;
            }
        }

        return true;
    }
}
