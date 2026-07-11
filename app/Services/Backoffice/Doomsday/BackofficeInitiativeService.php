<?php

declare(strict_types=1);

namespace App\Services\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveInitiativeData;
use App\Models\ContentSource;
use App\Models\Countdown;
use App\Models\Initiative;
use App\Services\Doomsday\NewsUpdater\YouTubeVideoUrl;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BackofficeInitiativeService
{
    public function __construct(
        private readonly BackofficeDoomsdayInputNormalizer $normalizer,
        private readonly YouTubeVideoUrl $youTubeUrls,
    ) {}

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
            ...$this->mediaAttributes($data),
            'image_path' => $this->normalizer->nullableString($data->image_path),
            'cta_label' => $this->normalizer->nullableString($data->cta_label),
            'starts_at' => $this->normalizer->nullableDate($data->starts_at),
            'ends_at' => $this->normalizer->nullableDate($data->ends_at),
            'sort_order' => $data->sort_order,
            'is_featured' => $data->is_featured,
        ];
    }

    /** @return array<string, string|null> */
    private function mediaAttributes(SaveInitiativeData $data): array
    {
        $url = trim($data->url);
        $previewImageUrl = $this->normalizer->nullableString($data->preview_image_url);
        $embedUrl = $this->normalizer->nullableString($data->embed_url);

        $this->assertHttpsUrl($url, 'url');
        $this->assertHttpsUrl($previewImageUrl, 'preview_image_url');
        $this->assertHttpsUrl($embedUrl, 'embed_url');

        if ($data->content_type !== 'youtube_video') {
            return [
                'url' => $url,
                'content_type' => 'article',
                'preview_image_url' => $previewImageUrl,
                'embed_url' => $embedUrl,
                'external_provider' => $this->normalizer->nullableString($data->external_provider),
                'external_id' => $this->normalizer->nullableString($data->external_id),
            ];
        }

        $videoId = $this->youTubeUrls->videoIdFromUrl($url);
        if ($videoId === null) {
            throw ValidationException::withMessages(['url' => 'A valid HTTPS YouTube video URL is required for video content.']);
        }

        return [
            'url' => $this->youTubeUrls->watchUrl($videoId),
            'content_type' => 'youtube_video',
            'preview_image_url' => $previewImageUrl ?? $this->youTubeUrls->thumbnailUrl($videoId),
            'embed_url' => $this->youTubeUrls->embedUrl($videoId),
            'external_provider' => ContentSource::PROVIDER_YOUTUBE,
            'external_id' => $videoId,
        ];
    }

    private function assertHttpsUrl(?string $url, string $field): void
    {
        if ($url === null) {
            return;
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false || strtolower((string) parse_url($url, PHP_URL_SCHEME)) !== 'https') {
            throw ValidationException::withMessages([$field => 'The '.$field.' field must be a valid HTTPS URL.']);
        }
    }

    private function assertBelongsToCountdown(Countdown $countdown, Initiative $initiative): void
    {
        if ((int) $initiative->countdown_id !== (int) $countdown->getKey()) {
            throw new NotFoundHttpException;
        }
    }

    private function validateDateRange(SaveInitiativeData $data): void
    {
        if ($data->starts_at !== null && $data->ends_at !== null && strtotime($data->starts_at) > strtotime($data->ends_at)) {
            throw ValidationException::withMessages(['ends_at' => 'The end date must be after or equal to the start date.']);
        }
    }
}
