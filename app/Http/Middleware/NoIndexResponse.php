<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class NoIndexResponse
{
    public function handle(Request $request, Closure $next, string $directive = 'nofollow'): Response
    {
        $response = $next($request);
        $followDirective = in_array($directive, ['follow', 'nofollow'], true)
            ? $directive
            : 'nofollow';
        $response->headers->set('X-Robots-Tag', 'noindex, '.$followDirective);

        return $response;
    }
}
