<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Security\SecurityHeaders;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AddSecurityHeaders
{
    public function __construct(private readonly SecurityHeaders $headers) {}

    public function handle(Request $request, Closure $next): Response
    {
        return $this->headers->apply($next($request), $request);
    }
}
