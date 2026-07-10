<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Ai\Agents\ContentSourceSearchAgent;
use App\Ai\Agents\RotableOpenAiAgent;
use App\Ai\Tools\SearchContentSourceTool;
use App\Models\ContentSource;
use App\Services\Doomsday\NewsUpdater\ContentSourceAgentSearchService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

final class ContentSourceSearchAgentCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_source_search_agent_is_rotable_parallel_and_uses_required_model(): void
    {
        $source = file_get_contents(app_path('Ai/Agents/ContentSourceSearchAgent.php'));

        $this->assertTrue(is_subclass_of(ContentSourceSearchAgent::class, RotableOpenAiAgent::class));
        $this->assertIsString($source);
        $this->assertStringContainsString('self::modelName()', $source);
        $this->assertStringContainsString('protected $maxCompletionTokens = 8000', $source);
        $this->assertSame('gpt-5.4-mini', ContentSourceSearchAgent::modelName());
        $this->assertSame('content-source-search-agent', ContentSourceSearchAgent::slug());
        $this->assertTrue((new \ReflectionClass(ContentSourceSearchAgent::class))->getDefaultProperties()['parallelToolCalls']);
        $this->assertArrayHasKey('items', ContentSourceSearchAgent::outputSchema()['properties']);
    }

    public function test_agent_prompt_explicitly_passes_today_and_available_source_catalog(): void
    {
        $prompt = ContentSourceSearchAgent::buildPrompt(
            userRequest: 'trovami video di china uncensored su taiwan dell ultimo mese',
            currentDate: '2026-07-09',
            availableSources: [[
                'source_key' => 'china-uncensored',
                'name' => 'China Uncensored',
                'type' => 'youtube_channel',
                'provider' => 'youtube',
                'language' => 'en',
            ]],
            defaultLimit: 10,
        );

        $this->assertStringContainsString('CURRENT_DATE: 2026-07-09', $prompt);
        $this->assertStringContainsString('china-uncensored', $prompt);
        $this->assertStringContainsString('language', $prompt);
        $this->assertStringContainsString('ultimo mese', $prompt);
        $this->assertStringContainsString('AVAILABLE_SOURCES_JSON is supplied by the command from outside the agent', $prompt);
        $this->assertStringContainsString('Do not invent titles, URLs, IDs, hashes, dates, counts, or DB fields.', $prompt);
    }

    public function test_search_content_source_tool_returns_db_fillable_candidates_from_predefined_source(): void
    {
        $this->createChinaUncensoredSource();
        $this->fakeYouTubeResponses();

        $tool = app(SearchContentSourceTool::class);
        $properties = $tool->getProperties();

        $this->assertSame('search_content_source', $tool->getName());
        $this->assertContains('china-uncensored', $properties['source']['enum']);

        $result = $tool->execute([
            'source' => 'china-uncensored',
            'query' => 'taiwan blockade',
            'from_date' => '2026-06-09',
            'to_date' => '2026-07-09',
            'limit' => 5,
        ]);

        $this->assertTrue($result['ok']);
        $this->assertSame('china-uncensored', $result['source']['source_key']);
        $this->assertSame('en', $result['source']['language']);
        $this->assertSame(1, $result['items_count']);
        $this->assertSame('twblockade01', $result['items'][0]['external_id']);
        $this->assertSame('youtube_video', $result['items'][0]['content_type']);
        $this->assertSame('https://www.youtube.com/watch?v=twblockade01', $result['items'][0]['source_url']);
        $this->assertSame('https://www.youtube.com/embed/twblockade01', $result['items'][0]['embed_url']);
        $this->assertNotSame('', $result['items'][0]['canonical_source_hash']);
        $this->assertSame('2026-07-01 12:00:00', $result['items'][0]['published_at']);
    }

    public function test_seeded_sources_include_news_languages_and_all_fetch_deterministically(): void
    {
        $sources = $this->createSeededContentSources();
        $this->fakeAllSourceResponses();

        /** @var ContentSourceAgentSearchService $service */
        $service = app(ContentSourceAgentSearchService::class);
        $catalog = $service->availableSources();

        $this->assertCount($sources->count(), $catalog);
        $this->assertContains(ContentSource::TYPE_RSS_FEED, collect($catalog)->pluck('type')->all());
        $this->assertContains('it', collect($catalog)->pluck('language')->all());
        $this->assertContains('en', collect($catalog)->pluck('language')->all());

        foreach ($sources as $source) {
            $sourceKey = Str::slug((string) $source->name);
            $result = $service->search($sourceKey, 'china', '2026-06-01', '2026-07-09', 1);

            $this->assertTrue($result['ok'], 'Source should search successfully: '.$sourceKey);
            $this->assertSame(1, $result['items_count'], 'Source should return one deterministic item: '.$sourceKey);
            $this->assertNotSame('', $result['items'][0]['source_url']);
            $this->assertNotSame('', $result['items'][0]['canonical_source_hash']);

            if ($source->type === ContentSource::TYPE_RSS_FEED) {
                $this->assertSame('article', $result['items'][0]['content_type']);
                $this->assertSame((string) $source->language, $result['items'][0]['locale']);
                $this->assertSame('https://cdn.example-news.test/preview.jpg', $result['items'][0]['preview_image_url']);
                $this->assertSame('', $result['items'][0]['embed_url']);
            } else {
                $this->assertSame('youtube_video', $result['items'][0]['content_type']);
                $this->assertStringStartsWith('https://i.ytimg.com/vi/', $result['items'][0]['preview_image_url']);
                $this->assertStringStartsWith('https://www.youtube.com/embed/', $result['items'][0]['embed_url']);
            }
        }
    }

    public function test_catalog_can_be_limited_by_source_subset_and_language_before_agent_prompt(): void
    {
        $this->createSeededContentSources();

        /** @var ContentSourceAgentSearchService $service */
        $service = app(ContentSourceAgentSearchService::class);
        $catalog = $service->availableSources(['google-news-it'], 'it');

        $this->assertCount(1, $catalog);
        $this->assertSame('google-news-it', $catalog[0]['source_key']);
        $this->assertSame('it', $catalog[0]['language']);

        $prompt = ContentSourceSearchAgent::buildPrompt(
            userRequest: 'cerca news in italiano su Taiwan',
            currentDate: '2026-07-09',
            availableSources: $catalog,
            defaultLimit: 10,
        );

        $this->assertStringContainsString('google-news-it', $prompt);
        $this->assertStringNotContainsString('china-uncensored', $prompt);
    }

    public function test_tool_source_guard_is_constructor_driven_not_context_driven(): void
    {
        $this->createSeededContentSources();

        /** @var ContentSourceAgentSearchService $service */
        $service = app(ContentSourceAgentSearchService::class);
        $catalog = $service->availableSources(['google-news-it']);
        $tool = new SearchContentSourceTool($service, $catalog);

        $this->assertSame(['google-news-it'], $tool->getProperties()['source']['enum']);

        $result = $tool->execute([
            'source' => 'china-uncensored',
            'query' => 'china',
            'from_date' => '2026-06-01',
            'to_date' => '2026-07-09',
            'limit' => 1,
        ]);

        $this->assertFalse($result['ok']);
        $this->assertSame(['source_not_in_command_catalog'], $result['errors']);
        $this->assertStringContainsString('sourceCatalog: $this->sourceCatalog', (string) file_get_contents(app_path('Ai/Agents/ContentSourceSearchAgent.php')));
        $this->assertStringNotContainsString('allowed_source_keys', (string) file_get_contents(app_path('Support/ContentSourceAgentRunContext.php')));
    }

    public function test_command_tool_only_formats_schema_payload_without_llm_call(): void
    {
        $this->createChinaUncensoredSource();
        $this->fakeYouTubeResponses();

        $exitCode = Artisan::call('countdowns:agent-content-source-search', [
            '--tool-only' => true,
            '--source' => 'china-uncensored',
            '--query' => 'taiwan blockade',
            '--from' => '2026-06-09',
            '--to' => '2026-07-09',
            '--today' => '2026-07-09',
        ]);

        $output = Artisan::output();

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Current date passed to agent: 2026-07-09', $output);
        $this->assertStringContainsString('JSON schema payload:', $output);
        $this->assertStringContainsString('twblockade01', $output);
        $this->assertStringContainsString('China Uncensored Taiwan Video', $output);
        $this->assertStringContainsString('Log run id:', $output);
        $this->assertStringContainsString('content-source-agent.log', $output);
        $this->assertStringContainsString('news-retrieval.log', $output);
    }

    public function test_critical_log_channels_are_configured(): void
    {
        $this->assertSame(storage_path('logs/news-retrieval.log'), config('logging.channels.news_retrieval.path'));
        $this->assertSame(storage_path('logs/content-source-agent.log'), config('logging.channels.content_source_agent.path'));
    }

    private function createChinaUncensoredSource(): ContentSource
    {
        return ContentSource::query()->create([
            'type' => ContentSource::TYPE_YOUTUBE_CHANNEL,
            'provider' => ContentSource::PROVIDER_YOUTUBE,
            'name' => 'China Uncensored',
            'external_id' => 'UCgFP46yVT-GG4o1TgXn-04Q',
            'source_url' => 'https://www.youtube.com/channel/UCgFP46yVT-GG4o1TgXn-04Q',
            'feed_url' => 'https://www.youtube.com/feeds/videos.xml?channel_id=UCgFP46yVT-GG4o1TgXn-04Q',
            'language' => 'en',
            'topics' => ['china', 'taiwan'],
            'keywords' => ['china', 'taiwan'],
            'metadata' => [],
            'weight' => 120,
            'is_global' => false,
            'is_active' => true,
        ]);
    }

    /** @return Collection<int, ContentSource> */
    private function createSeededContentSources(): Collection
    {
        $data = require database_path('patches/countdowns/taiwan_invasion/2026_07_09_010050_seed_taiwan_invasion_content_sources/data.php');
        foreach ($data->contentSources() as $sourceData) {
            ContentSource::query()->create($sourceData);
        }

        return ContentSource::query()->orderBy('id')->get();
    }

    private function fakeAllSourceResponses(): void
    {
        Http::fake([
            'https://www.youtube.com/feeds/videos.xml*' => Http::response($this->feedXml(), 200, ['Content-Type' => 'application/xml']),
            'https://www.youtube.com/oembed*' => Http::response([
                'title' => 'China Uncensored Taiwan Video',
                'author_name' => 'China Uncensored',
                'thumbnail_url' => 'https://i.ytimg.com/vi/twblockade01/hqdefault.jpg',
                'provider_name' => 'YouTube',
            ], 200),
            'https://news.google.com/rss/search*' => Http::response($this->googleNewsRssXml(), 200, ['Content-Type' => 'application/rss+xml']),
            'https://example-news.test/*' => Http::response($this->articleHtml(), 200, ['Content-Type' => 'text/html']),
        ]);
    }

    private function fakeYouTubeResponses(): void
    {
        Http::fake([
            'https://www.youtube.com/feeds/videos.xml?channel_id=UCgFP46yVT-GG4o1TgXn-04Q' => Http::response($this->feedXml(), 200, ['Content-Type' => 'application/xml']),
            'https://www.youtube.com/oembed*' => Http::response([
                'title' => 'China Uncensored Taiwan Video',
                'author_name' => 'China Uncensored',
                'thumbnail_url' => 'https://i.ytimg.com/vi/twblockade01/hqdefault.jpg',
                'provider_name' => 'YouTube',
            ], 200),
        ]);
    }

    private function feedXml(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:yt="http://www.youtube.com/xml/schemas/2015" xmlns:media="http://search.yahoo.com/mrss/">
    <entry>
        <id>yt:video:twblockade01</id>
        <yt:videoId>twblockade01</yt:videoId>
        <title>China threatens Taiwan blockade again</title>
        <published>2026-07-01T12:00:00+00:00</published>
        <media:group>
            <media:description>Analysis about Taiwan blockade risks and the CCP.</media:description>
            <media:thumbnail url="https://i.ytimg.com/vi/twblockade01/hqdefault.jpg" />
        </media:group>
    </entry>
    <entry>
        <id>yt:video:oldvideo99</id>
        <yt:videoId>oldvideo99</yt:videoId>
        <title>Old Taiwan blockade episode</title>
        <published>2026-05-01T12:00:00+00:00</published>
        <media:group>
            <media:description>Old Taiwan blockade discussion.</media:description>
        </media:group>
    </entry>
    <entry>
        <id>yt:video:hongkong77</id>
        <yt:videoId>hongkong77</yt:videoId>
        <title>China and Hong Kong update</title>
        <published>2026-07-03T12:00:00+00:00</published>
        <media:group>
            <media:description>General Hong Kong discussion without the requested island topic.</media:description>
        </media:group>
    </entry>
</feed>
XML;
    }

    private function googleNewsRssXml(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
    <channel>
        <item>
            <title>China and Taiwan tensions rise - Example News</title>
            <source>Example News</source>
            <link>https://news.google.com/rss/articles/example</link>
            <pubDate>Wed, 08 Jul 2026 12:00:00 GMT</pubDate>
            <description><![CDATA[<a href="https://example-news.test/china-taiwan">China and Taiwan tensions rise</a> Article about China, Taiwan and blockade risks.]]></description>
        </item>
    </channel>
</rss>
XML;
    }

    private function articleHtml(): string
    {
        return <<<'HTML'
<!doctype html>
<html>
<head>
    <meta property="og:image" content="https://cdn.example-news.test/preview.jpg">
</head>
<body>Article</body>
</html>
HTML;
    }
}
