<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Doomsday\Locale\PublicLocaleCookie;
use App\Services\Doomsday\Locale\PublicLocaleResolution;
use App\Services\Doomsday\Locale\PublicLocaleResolver;
use App\Services\Doomsday\Seo\PublicSeoService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResolvePublicLocale
{
    /** @var array<int, string> */
    private const PUBLIC_HTML_ROUTES = [
        'home',
        'about',
        'privacy',
        'cookie-policy',
        'countdowns.show',
    ];

    /** @var array<int, string> */
    private const REDIRECT_QUERY_ALLOWLIST = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'ref',
    ];

    public function __construct(
        private readonly PublicLocaleResolver $resolver,
        private readonly PublicLocaleCookie $localeCookie,
        private readonly PublicSeoService $seoService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $resolution = $this->resolver->resolve($request);
        $this->resolver->apply($request, $resolution);

        if ($this->shouldRedirect($request, $resolution)) {
            $response = redirect()->to($this->redirectTarget($request, $resolution->locale), Response::HTTP_FOUND);
            $response->headers->set('Cache-Control', 'private, no-store, max-age=0');
        } else {
            $response = $next($request);
        }

        $response->headers->set('Content-Language', $resolution->locale);

        if (in_array($resolution->source, ['accept-language', 'trusted-country', 'default'], true)) {
            $this->appendVary($response, 'Accept-Language');
        }
        if ($resolution->source === 'cookie') {
            $this->appendVary($response, 'Cookie');
        }

        if ($resolution->source === 'query' && $this->isPublicHtmlRoute($request)) {
            $response->headers->setCookie($this->localeCookie->make($request, $resolution->locale));
        }

        return $response;
    }

    private function shouldRedirect(Request $request, PublicLocaleResolution $resolution): bool
    {
        return $resolution->source !== 'query'
            && in_array($request->getMethod(), ['GET', 'HEAD'], true)
            && $this->isPublicHtmlRoute($request)
            && ! $request->expectsJson()
            && ! $request->wantsJson();
    }

    private function isPublicHtmlRoute(Request $request): bool
    {
        $name = $request->route()?->getName();
        if (! is_string($name) || ! in_array($name, self::PUBLIC_HTML_ROUTES, true)) {
            return false;
        }

        if ($name !== 'countdowns.show') {
            return true;
        }

        $slug = (string) $request->route('slug');

        return $slug !== '' && $this->seoService->publishedCountdown($request, $slug) !== null;
    }

    private function redirectTarget(Request $request, string $locale): string
    {
        $query = ['lang' => $locale];

        foreach (self::REDIRECT_QUERY_ALLOWLIST as $key) {
            $value = $request->query($key);
            if (is_string($value) && $value !== '' && mb_strlen($value) <= 255) {
                $query[$key] = $value;
            }
        }

        return $request->getPathInfo().'?'.http_build_query($query, '', '&', PHP_QUERY_RFC3986);
    }

    private function appendVary(Response $response, string $value): void
    {
        $existing = array_filter(array_map('trim', explode(',', (string) $response->headers->get('Vary', ''))));
        if (! in_array($value, $existing, true)) {
            $existing[] = $value;
        }

        $response->headers->set('Vary', implode(', ', $existing));
    }
}
