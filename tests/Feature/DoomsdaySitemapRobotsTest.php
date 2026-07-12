<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\CountdownSeverity;
use App\Enums\CountdownStatus;
use App\Http\Middleware\NoIndexResponse;
use App\Models\Countdown;
use App\Models\User;
use App\Services\Doomsday\Seo\PublicSitemapService;
use Carbon\CarbonImmutable;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class DoomsdaySitemapRobotsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.url' => 'https://doomsday-clock.com',
            'ai-starter.backoffice_path' => 'backoffice',
        ]);
    }

    public function test_sitemap_contains_only_localized_public_pages_with_factual_lastmod_and_reciprocal_alternates(): void
    {
        $modified = CarbonImmutable::parse('2026-07-08 06:07:08', 'UTC');
        $published = $this->countdown('published-sitemap', true);
        $this->countdown('hidden-sitemap', false);
        DB::table('countdowns')->where('id', $published->id)->update(['updated_at' => $modified]);

        $xml = app(PublicSitemapService::class)->render();
        $dom = new DOMDocument;
        $this->assertTrue($dom->loadXML($xml));
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('sm', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xpath->registerNamespace('xhtml', 'http://www.w3.org/1999/xhtml');
        $urls = $xpath->query('//sm:url');

        $this->assertNotFalse($urls);
        $this->assertCount(24, $urls);
        $locations = [];
        foreach ($urls as $url) {
            $this->assertInstanceOf(DOMElement::class, $url);
            $loc = $xpath->query('sm:loc', $url)?->item(0)?->textContent;
            $this->assertNotNull($loc);
            $locations[] = $loc;
            $alternates = $xpath->query('xhtml:link', $url);
            $this->assertNotFalse($alternates);
            $this->assertCount(9, $alternates);
        }

        $this->assertContains('https://doomsday-clock.com/?lang=en', $locations);
        $this->assertContains('https://doomsday-clock.com/about?lang=pl', $locations);
        $this->assertContains('https://doomsday-clock.com/countdowns/published-sitemap?lang=it', $locations);
        $this->assertStringNotContainsString('hidden-sitemap', $xml);
        $this->assertStringNotContainsString('/login', $xml);
        $this->assertStringNotContainsString('overview-data', $xml);
        $this->assertStringNotContainsString('/backoffice', $xml);

        $countdownNode = $this->urlNode($xpath, 'https://doomsday-clock.com/countdowns/published-sitemap?lang=en');
        $this->assertNotNull($countdownNode);
        $this->assertSame($modified->toAtomString(), $xpath->query('sm:lastmod', $countdownNode)?->item(0)?->textContent);

        $homeNode = $this->urlNode($xpath, 'https://doomsday-clock.com/?lang=en');
        $this->assertSame($modified->toAtomString(), $xpath->query('sm:lastmod', $homeNode)?->item(0)?->textContent);

        $aboutNode = $this->urlNode($xpath, 'https://doomsday-clock.com/about?lang=en');
        $this->assertSame(0, $xpath->query('sm:lastmod', $aboutNode)?->count());
    }

    public function test_sitemap_and_robots_routes_use_production_origin_and_correct_content_types(): void
    {
        $sitemapResponse = $this->get('/sitemap.xml')->assertOk();
        $robotsResponse = $this->get('/robots.txt')->assertOk();

        $this->assertSame('application/xml; charset=UTF-8', $sitemapResponse->headers->get('Content-Type'));
        $this->assertStringContainsString('https://doomsday-clock.com/?lang=en', $sitemapResponse->getContent());
        $this->assertSame('text/plain; charset=UTF-8', $robotsResponse->headers->get('Content-Type'));
        $this->assertSame(implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /login',
            'Disallow: /agent/',
            'Disallow: /*-data',
            'Disallow: /backoffice/',
            '',
            'Sitemap: https://doomsday-clock.com/sitemap.xml',
            '',
        ]), $robotsResponse->getContent());
    }

    public function test_noindex_middleware_allows_only_follow_or_nofollow_with_safe_default(): void
    {
        $request = Request::create('/robots-directive-probe', 'GET');
        $middleware = app(NoIndexResponse::class);

        $follow = $middleware->handle($request, fn (): mixed => response('follow'), 'follow');
        $nofollow = $middleware->handle($request, fn (): mixed => response('nofollow'), 'nofollow');
        $default = $middleware->handle($request, fn (): mixed => response('default'));
        $arbitrary = $middleware->handle($request, fn (): mixed => response('arbitrary'), 'index, follow, custom');

        $this->assertSame('noindex, follow', $follow->headers->get('X-Robots-Tag'));
        $this->assertSame('noindex, nofollow', $nofollow->headers->get('X-Robots-Tag'));
        $this->assertSame('noindex, nofollow', $default->headers->get('X-Robots-Tag'));
        $this->assertSame('noindex, nofollow', $arbitrary->headers->get('X-Robots-Tag'));
        $this->assertStringNotContainsString('custom', (string) $arbitrary->headers->get('X-Robots-Tag'));
    }

    public function test_legal_login_and_backoffice_routes_emit_matching_meta_and_header_directives(): void
    {
        $privacy = $this->get('/privacy?lang=en')->assertOk();
        $cookies = $this->get('/cookie-policy?lang=it')->assertOk();
        $login = $this->get('/login?lang=en')->assertOk();
        $admin = User::factory()->admin()->create();
        $backoffice = $this->actingAs($admin)->get('/backoffice?lang=en')->assertOk();

        foreach ([
            [$privacy, 'noindex, follow'],
            [$cookies, 'noindex, follow'],
            [$login, 'noindex, nofollow'],
            [$backoffice, 'noindex, nofollow'],
        ] as [$response, $expected]) {
            $this->assertSame($expected, $response->headers->get('X-Robots-Tag'));
            $this->assertSame($expected, $response->inertiaProps('seo.robots'));
        }
    }

    public function test_json_route_is_noindex_without_changing_payload(): void
    {
        $this->countdown('sample', true);

        $response = $this->getJson('/countdowns/sample/overview-data?lang=en')->assertOk();

        $this->assertSame('noindex, nofollow', $response->headers->get('X-Robots-Tag'));
        $this->assertSame('sample', $response->json('data.slug'));
        $this->assertSame('en', $response->headers->get('Content-Language'));
    }

    public function test_static_robots_file_is_absent_so_dynamic_route_cannot_be_shadowed(): void
    {
        $this->assertFileDoesNotExist(public_path('robots.txt'));
    }

    private function urlNode(DOMXPath $xpath, string $location): ?DOMElement
    {
        $nodes = $xpath->query('//sm:url');
        if ($nodes === false) {
            return null;
        }

        foreach ($nodes as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            if ($xpath->query('sm:loc', $node)?->item(0)?->textContent === $location) {
                return $node;
            }
        }

        return null;
    }

    private function countdown(string $slug, bool $published): Countdown
    {
        return Countdown::query()->create([
            'slug' => $slug,
            'title' => ['en' => str($slug)->replace('-', ' ')->title()->toString()],
            'summary' => ['en' => $slug.' summary'],
            'description' => ['en' => $slug.' description'],
            'severity' => CountdownSeverity::High,
            'status' => CountdownStatus::Active,
            'target_date' => CarbonImmutable::parse('2030-01-01 00:00:00', 'UTC'),
            'image_path' => 'images/doomsday/society_collapse_separate.png',
            'sort_order' => 1,
            'is_published' => $published,
        ]);
    }
}
