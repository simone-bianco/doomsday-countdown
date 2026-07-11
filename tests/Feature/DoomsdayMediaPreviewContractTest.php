<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Data\Backoffice\Doomsday\SaveInitiativeData;
use App\Data\Backoffice\Doomsday\SaveNewsData;
use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use App\Models\ContentSource;
use App\Models\Countdown;
use App\Services\Backoffice\Doomsday\BackofficeInitiativeService;
use App\Services\Backoffice\Doomsday\BackofficeNewsService;
use App\Services\Doomsday\CountdownPublicDataService;
use App\Services\Doomsday\NewsUpdater\YouTubeVideoUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

final class DoomsdayMediaPreviewContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_preview_contract_limits_excerpt_and_resolves_images_in_order(): void
    {
        config(['doomsday.content.preview_excerpt_limit' => 10]);
        $countdown = $this->countdown();

        $news = $countdown->news()->create([
            'locale' => 'all',
            'title' => 'Fallback image news',
            'excerpt' => 'News excerpt longer than the configured limit.',
            'content_type' => 'article',
            'source_url' => 'https://example.com/news',
            'preview_image_url' => 'http://example.com/insecure.jpg',
        ]);

        $initiative = $countdown->initiatives()->create([
            'locale' => 'all',
            'type' => 'campaign',
            'title' => 'Remote image initiative',
            'excerpt' => '   ',
            'body' => 'Fallback body remains complete after preview truncation.',
            'organization' => 'Test organization',
            'url' => 'https://example.com/initiative',
            'content_type' => 'youtube_video',
            'preview_image_url' => 'https://cdn.example.com/initiative.jpg',
            'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'external_provider' => ContentSource::PROVIDER_YOUTUBE,
            'external_id' => 'dQw4w9WgXcQ',
            'sort_order' => 1,
        ]);

        $service = app(CountdownPublicDataService::class);
        $newsItem = collect($service->newsSection($countdown->slug, 'en')['news'])->firstWhere('title', $news->title);
        $initiativeItem = collect($service->initiativesSection($countdown->slug, 'en')['initiatives'])->firstWhere('title', $initiative->title);

        $this->assertSame(Str::limit(trim($news->excerpt), 10, '…'), $newsItem['excerpt']);
        $this->assertSame(asset($countdown->image_path), $newsItem['image_url']);
        $this->assertSame(Str::limit(trim((string) $initiative->body), 10, '…'), $initiativeItem['excerpt']);
        $this->assertSame($initiative->body, $initiativeItem['body']);
        $this->assertSame('https://cdn.example.com/initiative.jpg', $initiativeItem['image_url']);
        $this->assertSame('youtube_video', $initiativeItem['content_type']);
        $this->assertSame(ContentSource::PROVIDER_YOUTUBE, $initiativeItem['external_provider']);
    }

    public function test_invalid_excerpt_limit_falls_back_to_220(): void
    {
        config(['doomsday.content.preview_excerpt_limit' => 0]);
        $countdown = $this->countdown('invalid-limit');
        $excerpt = str_repeat('a', 230);

        $countdown->news()->create([
            'locale' => 'all',
            'title' => 'Configured fallback',
            'excerpt' => $excerpt,
            'source_url' => 'https://example.com/fallback',
        ]);

        $item = app(CountdownPublicDataService::class)->newsSection($countdown->slug, 'en')['news'][0];

        $this->assertSame(Str::limit($excerpt, 220, '…'), $item['excerpt']);
    }

    public function test_youtube_url_utility_accepts_supported_formats_and_rejects_spoofed_hosts(): void
    {
        $videoId = 'dQw4w9WgXcQ';
        $utility = app(YouTubeVideoUrl::class);

        foreach ([
            'https://www.youtube.com/watch?v='.$videoId,
            'https://youtu.be/'.$videoId,
            'https://www.youtube.com/shorts/'.$videoId,
            'https://www.youtube.com/embed/'.$videoId,
        ] as $url) {
            $this->assertSame($videoId, $utility->videoIdFromUrl($url));
        }

        $this->assertNull($utility->videoIdFromUrl('https://evil-youtube.com/watch?v='.$videoId));
        $this->assertNull($utility->videoIdFromUrl('https://youtu.be.evil.com/'.$videoId));
    }

    public function test_backoffice_services_derive_youtube_media_for_news_and_initiatives(): void
    {
        $countdown = $this->countdown('youtube-media');
        $videoId = 'dQw4w9WgXcQ';
        $shortUrl = 'https://youtu.be/'.$videoId;

        $news = app(BackofficeNewsService::class)->create($countdown, new SaveNewsData(
            locale: 'en',
            title: 'YouTube news',
            excerpt: 'Video preview.',
            source_name: 'Video source',
            source_url: $shortUrl,
            image_path: 'images/doomsday/news.png',
            published_at: null,
            sort_order: 1,
            is_featured: true,
            content_type: 'youtube_video',
        ));

        $initiative = app(BackofficeInitiativeService::class)->create($countdown, new SaveInitiativeData(
            locale: 'en',
            type: 'resource',
            title: 'YouTube initiative',
            excerpt: 'Initiative video preview.',
            body: 'Full initiative body.',
            organization: 'Video organization',
            url: $shortUrl,
            image_path: 'images/doomsday/initiative.png',
            cta_label: 'Watch',
            starts_at: null,
            ends_at: null,
            sort_order: 2,
            is_featured: false,
            content_type: 'youtube_video',
        ));

        foreach ([$news, $initiative] as $item) {
            $this->assertSame('youtube_video', $item->content_type);
            $this->assertSame(ContentSource::PROVIDER_YOUTUBE, $item->external_provider);
            $this->assertSame($videoId, $item->external_id);
            $this->assertSame('https://www.youtube.com/embed/'.$videoId, $item->embed_url);
            $this->assertSame('https://i.ytimg.com/vi/'.$videoId.'/hqdefault.jpg', $item->preview_image_url);
        }

        $this->assertSame('https://www.youtube.com/watch?v='.$videoId, $news->source_url);
        $this->assertSame('https://www.youtube.com/watch?v='.$videoId, $initiative->url);
    }

    public function test_backoffice_news_rejects_spoofed_youtube_host(): void
    {
        $countdown = $this->countdown('spoofed-news-media');

        $this->expectException(ValidationException::class);

        app(BackofficeNewsService::class)->create($countdown, new SaveNewsData(
            locale: 'en',
            title: 'Spoofed YouTube news',
            excerpt: 'Spoofed media.',
            source_name: 'Source',
            source_url: 'https://evil-youtube.com/watch?v=dQw4w9WgXcQ',
            image_path: null,
            published_at: null,
            sort_order: 0,
            is_featured: false,
            content_type: 'youtube_video',
        ));
    }

    public function test_backoffice_initiative_rejects_spoofed_short_youtube_host(): void
    {
        $countdown = $this->countdown('spoofed-initiative-media');

        $this->expectException(ValidationException::class);

        app(BackofficeInitiativeService::class)->create($countdown, new SaveInitiativeData(
            locale: 'en',
            type: 'resource',
            title: 'Spoofed YouTube initiative',
            excerpt: 'Spoofed initiative media.',
            body: null,
            organization: 'Source',
            url: 'https://youtu.be.evil.com/dQw4w9WgXcQ',
            image_path: null,
            cta_label: 'Watch',
            starts_at: null,
            ends_at: null,
            sort_order: 0,
            is_featured: false,
            content_type: 'youtube_video',
        ));
    }

    public function test_backoffice_rejects_non_https_media_urls(): void
    {
        $countdown = $this->countdown('invalid-media');

        $this->expectException(ValidationException::class);

        app(BackofficeNewsService::class)->create($countdown, new SaveNewsData(
            locale: 'en',
            title: 'Insecure news',
            excerpt: 'Insecure media.',
            source_name: 'Source',
            source_url: 'http://example.com/news',
            image_path: null,
            published_at: null,
            sort_order: 0,
            is_featured: false,
        ));
    }

    public function test_initiative_url_over_255_characters_persists_and_round_trips(): void
    {
        $countdown = $this->countdown('long-initiative-url');
        $url = 'https://example.com/initiative?reference='.str_repeat('a', 300);

        $initiative = app(BackofficeInitiativeService::class)->create($countdown, new SaveInitiativeData(
            locale: 'en',
            type: 'resource',
            title: 'Long URL initiative',
            excerpt: 'The URL remains within the 500 character contract.',
            body: null,
            organization: 'Test organization',
            url: $url,
            image_path: null,
            cta_label: 'Open',
            starts_at: null,
            ends_at: null,
            sort_order: 0,
            is_featured: false,
            content_type: 'article',
        ));

        $this->assertGreaterThan(255, strlen($url));
        $this->assertLessThanOrEqual(500, strlen($url));
        $this->assertSame($url, $initiative->url);
        $this->assertSame($url, $initiative->fresh()->url);
    }

    private function countdown(string $slug = 'media-preview'): Countdown
    {
        return Countdown::query()->create([
            'slug' => $slug,
            'title' => ['en' => 'Media preview'],
            'summary' => ['en' => 'Media preview summary'],
            'description' => ['en' => 'Media preview description'],
            'severity' => CountdownSeverity::High,
            'status' => CountdownStatus::Active,
            'target_date' => now()->addYear(),
            'image_path' => 'images/doomsday/taiwan_invasion.png',
            'is_published' => true,
            'sort_order' => 1,
        ]);
    }
}
