<?php

declare(strict_types=1);

namespace App\Services\Security;

use Illuminate\Http\Request;
use LogicException;
use Symfony\Component\HttpFoundation\Response;

final class SecurityHeaders
{
    public function apply(Response $response, ?Request $request = null): Response
    {
        if (! config('security.headers.enabled', true)) {
            return $response;
        }

        $response->headers->set('X-Content-Type-Options', (string) config('security.headers.x_content_type_options', 'nosniff'));
        $response->headers->set('Referrer-Policy', (string) config('security.headers.referrer_policy', 'strict-origin-when-cross-origin'));
        $response->headers->set('Permissions-Policy', (string) config('security.headers.permissions_policy', 'camera=(), geolocation=(), microphone=()'));
        $response->headers->set('X-Frame-Options', (string) config('security.headers.frame_options', 'DENY'));

        if ($this->shouldNoIndex($request)) {
            $existingValues = $response->headers->all('X-Robots-Tag');
            $existingValue = count($existingValues) === 1
                ? strtolower(trim($existingValues[0]))
                : '';
            $allowedValues = ['noindex, follow', 'noindex, nofollow'];

            $response->headers->set(
                'X-Robots-Tag',
                in_array($existingValue, $allowedValues, true) ? $existingValue : 'noindex, nofollow',
            );
        }

        if (config('security.csp.enabled', true)) {
            $header = config('security.csp.report_only', true)
                ? 'Content-Security-Policy-Report-Only'
                : 'Content-Security-Policy';
            $response->headers->set($header, $this->contentSecurityPolicy());
        }

        if ($this->shouldAddHsts($request)) {
            $response->headers->set('Strict-Transport-Security', $this->hstsValue());
        }

        return $response;
    }

    private function contentSecurityPolicy(): string
    {
        /** @var array<string, array<int, string>> $directives */
        $directives = config('security.csp.directives', []);
        $parts = [];

        foreach ($directives as $directive => $sources) {
            foreach ($sources as $source) {
                if (str_contains($source, '*')) {
                    throw new LogicException("CSP wildcard is not allowed: {$directive} {$source}");
                }
            }

            $parts[] = trim($directive.' '.implode(' ', $sources));
        }

        return implode('; ', $parts);
    }

    private function shouldNoIndex(?Request $request): bool
    {
        $name = $request?->route()?->getName();
        if (! is_string($name)) {
            return false;
        }

        $noIndexRoutes = array_merge(
            config('security.routes.public_noindex', []),
            config('security.routes.admin_private', []),
            config('security.routes.local_only', []),
        );

        return in_array($name, $noIndexRoutes, true);
    }

    private function shouldAddHsts(?Request $request): bool
    {
        return config('security.hsts.enabled', false)
            && config('security.hsts.https_and_subdomains_proven', false)
            && $request?->isSecure() === true;
    }

    private function hstsValue(): string
    {
        $value = 'max-age='.(int) config('security.hsts.max_age', 31536000);

        if (config('security.hsts.include_subdomains', false)) {
            $value .= '; includeSubDomains';
        }

        if (config('security.hsts.preload', false)) {
            $value .= '; preload';
        }

        return $value;
    }
}
