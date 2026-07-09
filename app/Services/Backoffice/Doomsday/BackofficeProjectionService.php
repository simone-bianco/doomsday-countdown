<?php

declare(strict_types=1);

namespace App\Services\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveProjectionData;
use App\Models\Countdown;
use App\Models\Projection;
use App\Models\Visualization;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BackofficeProjectionService
{
    public function __construct(private readonly BackofficeDoomsdayInputNormalizer $normalizer)
    {
    }

    public function create(Countdown $countdown, SaveProjectionData $data): Projection
    {
        return $countdown->projections()->create($this->attributes($data));
    }

    public function update(Countdown $countdown, Projection $projection, SaveProjectionData $data): Projection
    {
        $this->assertBelongsToCountdown($countdown, $projection);
        $projection->update($this->attributes($data));

        return $projection->refresh();
    }

    public function delete(Countdown $countdown, Projection $projection): void
    {
        $this->assertBelongsToCountdown($countdown, $projection);

        DB::transaction(function () use ($projection): void {
            $projection->loadMissing('visualizations');
            $projection->visualizations->each(fn (Visualization $visualization): bool|null => $visualization->delete());
            $projection->delete();
        });
    }

    public function assertBelongsToCountdown(Countdown $countdown, Projection $projection): void
    {
        if ((int) $projection->countdown_id !== (int) $countdown->getKey()) {
            throw new NotFoundHttpException();
        }
    }

    /** @return array<string, mixed> */
    private function attributes(SaveProjectionData $data): array
    {
        return [
            'type' => $data->type,
            'target_date' => $this->normalizer->nullableDate($data->target_date),
            'title' => $this->normalizer->localizedText($data->title, 'title'),
            'summary' => $this->normalizer->optionalLocalizedText($data->summary),
            'confidence_score' => $data->confidence_score,
            'probability_score' => $data->probability_score,
            'trend' => $data->trend,
            'methodology' => $this->normalizer->optionalLocalizedText($data->methodology),
            'sort_order' => $data->sort_order,
        ];
    }
}
