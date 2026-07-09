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
    public function __construct(private readonly BackofficeDoomsdayInputNormalizer $normalizer) {}

    public function create(Countdown|Projection $visualizable, SaveVisualizationData $data): Visualization
    {
        $this->validatePayload($data);

        return $visualizable->visualizations()->create($this->attributes($data));
    }

    public function update(Countdown|Projection $visualizable, Visualization $visualization, SaveVisualizationData $data): Visualization
    {
        $this->assertBelongsToVisualizable($visualizable, $visualization);
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

    private function validatePayload(SaveVisualizationData $data): void
    {
        match ($data->type) {
            VisualizationType::Line->value, VisualizationType::Area->value, VisualizationType::Bar->value, VisualizationType::Sparkline->value => $this->validateSeriesPayload($data->payload),
            VisualizationType::Kpi->value => $this->validateKpiPayload($data->payload),
            default => $this->validateNonEmptyPayload($data->payload),
        };
    }

    /** @param array<string, mixed> $payload */
    private function validateSeriesPayload(array $payload): void
    {
        $labels = $payload['labels'] ?? null;
        $series = $payload['series'] ?? null;

        if (! is_array($labels) || $labels === [] || ! is_array($series) || $series === []) {
            throw ValidationException::withMessages(['payload' => 'Line, area, bar and sparkline payloads require labels and series arrays.']);
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
