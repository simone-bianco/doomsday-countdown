<?php

declare(strict_types=1);

namespace App\Services\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveInitiativeData;
use App\Models\Countdown;
use App\Models\Initiative;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BackofficeInitiativeService
{
    public function __construct(private readonly BackofficeDoomsdayInputNormalizer $normalizer)
    {
    }

    public function create(Countdown $countdown, SaveInitiativeData $data): Initiative
    {
        $this->validateDateRange($data);

        return $countdown->initiatives()->create($this->attributes($data));
    }

    public function update(Countdown $countdown, Initiative $initiative, SaveInitiativeData $data): Initiative
    {
        $this->assertBelongsToCountdown($countdown, $initiative);
        $this->validateDateRange($data);
        $initiative->update($this->attributes($data));

        return $initiative->refresh();
    }

    public function delete(Countdown $countdown, Initiative $initiative): void
    {
        $this->assertBelongsToCountdown($countdown, $initiative);
        $initiative->delete();
    }

    /** @return array<string, mixed> */
    private function attributes(SaveInitiativeData $data): array
    {
        return [
            'locale' => $data->locale,
            'type' => $data->type,
            'title' => $data->title,
            'excerpt' => $data->excerpt,
            'body' => $this->normalizer->nullableString($data->body),
            'organization' => $this->normalizer->nullableString($data->organization),
            'url' => $data->url,
            'image_path' => $this->normalizer->nullableString($data->image_path),
            'cta_label' => $this->normalizer->nullableString($data->cta_label),
            'starts_at' => $this->normalizer->nullableDate($data->starts_at),
            'ends_at' => $this->normalizer->nullableDate($data->ends_at),
            'sort_order' => $data->sort_order,
            'is_featured' => $data->is_featured,
        ];
    }

    private function assertBelongsToCountdown(Countdown $countdown, Initiative $initiative): void
    {
        if ((int) $initiative->countdown_id !== (int) $countdown->getKey()) {
            throw new NotFoundHttpException();
        }
    }

    private function validateDateRange(SaveInitiativeData $data): void
    {
        if ($data->starts_at !== null && $data->ends_at !== null && strtotime($data->starts_at) > strtotime($data->ends_at)) {
            throw ValidationException::withMessages(['ends_at' => 'The end date must be after or equal to the start date.']);
        }
    }
}
