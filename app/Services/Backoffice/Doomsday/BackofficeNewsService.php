<?php

declare(strict_types=1);

namespace App\Services\Backoffice\Doomsday;

use App\Data\Backoffice\Doomsday\SaveNewsData;
use App\Models\ContentSource;
use App\Models\Countdown;
use App\Models\News;
use App\Services\Doomsday\NewsUpdater\YouTubeVideoUrl;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class BackofficeNewsService
{
    public function __construct(
        private readonly BackofficeDoomsdayInputNormalizer $normalizer,
        private readonly YouTubeVideoUrl $youTubeUrls,
    ) {}

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
            ...$this->mediaAttributes($data),
            'image_path' => $this->normalizer->nullableString($data->image_path),
            'published_at' => $this->normalizer->nullableDate($data->published_at),
            'sort_order' => $data->sort_order,
            'is_featured' => $data->is_featured,
        ];
    }

    /** @return array<string, string|null> */
    private function mediaAttributes(SaveNewsData $data): array
    {
        $sourceUrl = $this->normalizer->nullableString($data->source_url);
        $previewImageUrl = $this->normalizer->nullableString($data->preview_image_url);
        $embedUrl = $this->normalizer->nullableString($data->embed_url);

        $this->assertHttpsUrl($sourceUrl, 'source_url');
        $this->assertHttpsUrl($previewImageUrl, 'preview_image_url');
        $this->assertHttpsUrl($embedUrl, 'embed_url');

        if ($data->content_type !== 'youtube_video') {
            return [
                'content_type' => 'article',
                'source_url' => $sourceUrl,
                'preview_image_url' => $previewImageUrl,
                'embed_url' => $embedUrl,
                'external_provider' => $this->normalizer->nullableString($data->external_provider),
                'external_id' => $this->normalizer->nullableString($data->external_id),
            ];
        }

        $videoId = $this->youTubeUrls->videoIdFromUrl($sourceUrl);
        if ($videoId === null) {
            throw ValidationException::withMessages(['source_url' => 'A valid HTTPS YouTube video URL is required for video content.']);
        }

        return [
            'content_type' => 'youtube_video',
            'source_url' => $this->youTubeUrls->watchUrl($videoId),
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

    private function assertBelongsToCountdown(Countdown $countdown, News $news): void
    {
        if ((int) $news->countdown_id !== (int) $countdown->getKey()) {
            throw new NotFoundHttpException;
        }
    }
}
