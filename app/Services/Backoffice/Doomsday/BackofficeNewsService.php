<?php

declare(strict_types=1);

namespace App\Services\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveNewsData;
use App\Models\Countdown;
use App\Models\News;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BackofficeNewsService
{
    public function __construct(private readonly BackofficeDoomsdayInputNormalizer $normalizer)
    {
    }

    public function create(Countdown $countdown, SaveNewsData $data): News
    {
        return $countdown->news()->create($this->attributes($data));
    }

    public function update(Countdown $countdown, News $news, SaveNewsData $data): News
    {
        $this->assertBelongsToCountdown($countdown, $news);
        $news->update($this->attributes($data));

        return $news->refresh();
    }

    public function delete(Countdown $countdown, News $news): void
    {
        $this->assertBelongsToCountdown($countdown, $news);
        $news->delete();
    }

    /** @return array<string, mixed> */
    private function attributes(SaveNewsData $data): array
    {
        return [
            'locale' => $data->locale,
            'title' => $data->title,
            'excerpt' => $data->excerpt,
            'source_name' => $this->normalizer->nullableString($data->source_name),
            'source_url' => $this->normalizer->nullableString($data->source_url),
            'image_path' => $this->normalizer->nullableString($data->image_path),
            'published_at' => $this->normalizer->nullableDate($data->published_at),
            'sort_order' => $data->sort_order,
            'is_featured' => $data->is_featured,
        ];
    }

    private function assertBelongsToCountdown(Countdown $countdown, News $news): void
    {
        if ((int) $news->countdown_id !== (int) $countdown->getKey()) {
            throw new NotFoundHttpException();
        }
    }
}
