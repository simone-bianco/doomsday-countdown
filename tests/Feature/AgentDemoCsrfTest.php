<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Tests\TestCase;

final class AgentDemoCsrfTest extends TestCase
{
    public function test_agent_demo_csrf_failures_are_json_for_json_clients(): void
    {
        $request = Request::create('/agent/demo', 'POST', ['message' => 'hello']);
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $response = app(ExceptionHandler::class)->render($request, new TokenMismatchException('CSRF token mismatch.'));

        $this->assertSame(419, $response->getStatusCode());
        $this->assertStringContainsString('application/json', (string) $response->headers->get('content-type'));
        $this->assertJson($response->getContent());
    }
}
