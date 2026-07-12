<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Middleware\ResolvePublicLocale;
use App\Services\Doomsday\Cache\DoomsdayCacheKeys;
use App\Services\Doomsday\Locale\PublicLocaleCookie;
use App\Services\Doomsday\Locale\PublicLocaleResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as LaravelRoute;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

final class DoomsdayPublicLocaleTest extends TestCase
{
    use RefreshDatabase;

    private string $originalCacheStore;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalCacheStore = (string) config('cache.default');
        config([
            'cache.default' => 'array',
            'doomsday.cache.enabled' => true,
            'doomsday.locale.trusted_country.enabled' => false,
        ]);
        Cache::setDefaultDriver('array');
        Cache::flush();
    }

    protected function tearDown(): void
    {
        Cache::flush();
        config([
            'cache.default' => $this->originalCacheStore,
            'doomsday.locale.trusted_country.enabled' => false,
        ]);
        Cache::setDefaultDriver($this->originalCacheStore);
        app()->setLocale((string) config('app.locale', 'en'));

        parent::tearDown();
    }

    public function test_explicit_query_wins_over_cookie_browser_and_trusted_country(): void
    {
        config(['doomsday.locale.trusted_country.enabled' => true]);
        $request = $this->namedRequest(
            '/about?lang=fr',
            'about',
            ['HTTP_ACCEPT_LANGUAGE' => 'it-IT,it;q=0.9'],
            [PublicLocaleCookie::NAME => 'de'],
        );
        $request->attributes->set('trusted_country_code', 'IT');

        $resolution = app(PublicLocaleResolver::class)->resolve($request);

        $this->assertSame('fr', $resolution->locale);
        $this->assertSame('query', $resolution->source);
    }

    public function test_cookie_wins_over_accept_language_and_accept_language_honors_quality_and_region_fallback(): void
    {
        $cookieRequest = $this->namedRequest(
            '/about',
            'about',
            ['HTTP_ACCEPT_LANGUAGE' => 'it-IT,it;q=0.9'],
            [PublicLocaleCookie::NAME => 'de'],
        );
        $cookieResolution = app(PublicLocaleResolver::class)->resolve($cookieRequest);
        $this->assertSame('de', $cookieResolution->locale);
        $this->assertSame('cookie', $cookieResolution->source);
        $cookieRedirect = app(ResolvePublicLocale::class)->handle($cookieRequest, fn (): mixed => response('unreachable'));
        $this->assertSame(302, $cookieRedirect->getStatusCode());
        $this->assertSame('Cookie', $cookieRedirect->headers->get('Vary'));
        $this->assertSame('max-age=0, no-store, private', $cookieRedirect->headers->get('Cache-Control'));

        $browserRequest = $this->namedRequest(
            '/about',
            'about',
            ['HTTP_ACCEPT_LANGUAGE' => 'de-DE;q=0.4, it-IT;q=0.9, fr;q=0.8'],
        );
        $browserResolution = app(PublicLocaleResolver::class)->resolve($browserRequest);
        $this->assertSame('it', $browserResolution->locale);
        $this->assertSame('accept-language', $browserResolution->source);
    }

    public function test_spoofed_country_header_is_ignored_and_disabled_adapter_never_reads_country(): void
    {
        $spoofed = $this->namedRequest('/about', 'about', ['HTTP_CF_IPCOUNTRY' => 'IT']);
        $this->assertSame('en', app(PublicLocaleResolver::class)->resolve($spoofed)->locale);

        $disabled = $this->namedRequest('/about', 'about');
        $disabled->attributes->set('trusted_country_code', 'IT');
        $this->assertSame('en', app(PublicLocaleResolver::class)->resolve($disabled)->locale);

        config(['doomsday.locale.trusted_country.enabled' => true]);
        $trusted = $this->namedRequest('/about', 'about');
        $trusted->attributes->set('trusted_country_code', 'IT');
        $resolution = app(PublicLocaleResolver::class)->resolve($trusted);
        $this->assertSame('it', $resolution->locale);
        $this->assertSame('trusted-country', $resolution->source);
    }

    public function test_neutral_public_html_redirects_temporarily_without_loop_and_persists_only_explicit_choice(): void
    {
        $neutral = $this->namedRequest(
            '/about?utm_source=search&debug=1',
            'about',
            ['HTTP_ACCEPT_LANGUAGE' => 'it-IT,it;q=0.9', 'HTTP_ACCEPT' => 'text/html'],
        );
        $redirect = app(ResolvePublicLocale::class)->handle($neutral, fn (): mixed => response('unreachable'));

        $this->assertSame(302, $redirect->getStatusCode());
        $this->assertStringEndsWith('/about?lang=it&utm_source=search', (string) $redirect->headers->get('Location'));
        $this->assertSame('it', $redirect->headers->get('Content-Language'));
        $this->assertSame('Accept-Language', $redirect->headers->get('Vary'));
        $this->assertSame('max-age=0, no-store, private', $redirect->headers->get('Cache-Control'));
        $this->assertSame([], $redirect->headers->getCookies());

        $explicit = $this->namedRequest('/about?lang=fr', 'about', ['HTTP_ACCEPT' => 'text/html']);
        $response = app(ResolvePublicLocale::class)->handle(
            $explicit,
            fn (): mixed => response('<html lang="'.app()->getLocale().'">'),
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('<html lang="fr">', $response->getContent());
        $this->assertSame('fr', $response->headers->get('Content-Language'));
        $cookie = collect($response->headers->getCookies())->first(fn ($candidate): bool => $candidate->getName() === PublicLocaleCookie::NAME);
        $this->assertNotNull($cookie);
        $this->assertSame('fr', $cookie->getValue());
    }

    public function test_json_backoffice_assets_and_health_never_redirect(): void
    {
        $cases = [
            ['/countdowns/sample/overview-data', 'countdowns.data.overview', ['HTTP_ACCEPT' => 'application/json']],
            ['/backoffice', 'backoffice.index', ['HTTP_ACCEPT' => 'text/html']],
            ['/build/assets/app.js', null, ['HTTP_ACCEPT' => '*/*']],
            ['/up', null, ['HTTP_ACCEPT' => 'text/html']],
        ];

        foreach ($cases as [$uri, $routeName, $server]) {
            $request = $this->namedRequest($uri, $routeName, array_merge($server, [
                'HTTP_ACCEPT_LANGUAGE' => 'it-IT,it;q=0.9',
            ]));
            $response = app(ResolvePublicLocale::class)->handle($request, fn (): mixed => response('ok'));

            $this->assertSame(200, $response->getStatusCode(), $uri);
            $this->assertSame('ok', $response->getContent(), $uri);
            $this->assertSame('it', $response->headers->get('Content-Language'), $uri);
        }
    }

    public function test_explicit_locale_variants_are_cache_isolated_and_shared_with_inertia(): void
    {
        config(['app.url' => 'https://doomsday-clock.com']);

        $english = $this->get('/?lang=en')->assertOk();
        $italian = $this->get('/?lang=it')->assertOk();

        $this->assertSame('en', $english->inertiaProps('locale'));
        $this->assertSame('it', $italian->inertiaProps('locale'));
        $this->assertSame('en', $english->inertiaProps('page.current_locale'));
        $this->assertSame('it', $italian->inertiaProps('page.current_locale'));
        $this->assertSame('it', $italian->headers->get('Content-Language'));
        $localeCookie = collect($italian->headers->getCookies())
            ->first(fn ($candidate): bool => $candidate->getName() === PublicLocaleCookie::NAME);
        $this->assertNotNull($localeCookie);
        $italian->assertCookie(PublicLocaleCookie::NAME, 'it');
        $this->assertSame('/', $localeCookie->getPath());
        $this->assertTrue($localeCookie->isHttpOnly());
        $this->assertSame('lax', strtolower((string) $localeCookie->getSameSite()));
        $this->assertNotSame(DoomsdayCacheKeys::index('en'), DoomsdayCacheKeys::index('it'));
        $this->assertNotNull(Cache::get(DoomsdayCacheKeys::index('en')));
        $this->assertNotNull(Cache::get(DoomsdayCacheKeys::index('it')));
    }

    public function test_public_controllers_no_longer_parse_lang_independently(): void
    {
        foreach ([
            'app/Http/Controllers/Web/HomePageController.php',
            'app/Http/Controllers/Web/AboutPageController.php',
            'app/Http/Controllers/Web/CountdownShowPageController.php',
            'app/Http/Controllers/Web/CountdownDataController.php',
            'app/Http/Controllers/Web/LegalPolicyPageController.php',
        ] as $path) {
            $source = (string) file_get_contents(base_path($path));
            $this->assertStringNotContainsString("query('lang')", $source, $path);
            $this->assertStringNotContainsString('normalizeLocale(', $source, $path);
        }
    }

    /** @param array<string, string> $server @param array<string, string> $cookies */
    private function namedRequest(string $uri, ?string $routeName, array $server = [], array $cookies = []): Request
    {
        $request = Request::create($uri, 'GET', [], $cookies, [], $server);
        if (! array_key_exists('HTTP_ACCEPT_LANGUAGE', $server)) {
            $request->headers->remove('Accept-Language');
        }
        $route = new LaravelRoute(['GET'], ltrim($request->getPathInfo(), '/') ?: '/', fn (): null => null);
        if ($routeName !== null) {
            $route->name($routeName);
        }
        $route->bind($request);
        $request->setRouteResolver(static fn (): LaravelRoute => $route);

        return $request;
    }
}
