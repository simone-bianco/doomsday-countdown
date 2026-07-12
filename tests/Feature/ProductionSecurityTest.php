<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Security\SecurityHeaders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LogicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Tests\TestCase;

final class ProductionSecurityTest extends TestCase
{
    public function test_every_application_route_is_classified_once(): void
    {
        $categories = collect(config('security.routes'));
        $classified = $categories->flatten()->values();

        $this->assertSame($classified->count(), $classified->unique()->count(), 'A route name appears in multiple security categories.');

        $applicationRoutes = collect(Route::getRoutes()->getRoutes())
            ->filter(static fn ($route): bool => str_starts_with($route->getActionName(), 'App\\'))
            ->map(static fn ($route): ?string => $route->getName())
            ->filter()
            ->unique()
            ->values();

        $this->assertEqualsCanonicalizing($applicationRoutes->all(), $classified->all());
        $this->assertContains('agent.demo', config('security.routes.local_only'));
        $this->assertContains('backoffice.users.destroy', config('security.routes.admin_private'));
    }

    public function test_production_route_table_never_registers_demo_agent(): void
    {
        $process = new Process(
            [PHP_BINARY, 'artisan', 'route:list', '--json', '--except-vendor'],
            base_path(),
            [
                'APP_ENV' => 'production',
                'DEMO_AGENT_ENABLED' => 'true',
                'DB_CONNECTION' => 'sqlite',
                'DB_DATABASE' => ':memory:',
            ],
        );
        $process->setTimeout(30);
        $process->run();

        $output = $process->getOutput().$process->getErrorOutput();
        $this->assertTrue($process->isSuccessful(), $output);

        $routes = json_decode($process->getOutput(), true, flags: JSON_THROW_ON_ERROR);
        $names = array_column($routes, 'name');
        $uris = array_column($routes, 'uri');

        $this->assertNotContains('agent.demo', $names);
        $this->assertNotContains('agent/demo', $uris);
        $this->assertContains('sitemap', $names);
        $this->assertContains('robots', $names);
    }

    public function test_legal_routes_preserve_follow_while_login_keeps_safe_nofollow(): void
    {
        $privacy = $this->get('/privacy?lang=en');
        $privacy->assertOk()->assertHeader('X-Robots-Tag', 'noindex, follow');
        $this->assertSecurityHeaders($privacy->baseResponse);

        $cookies = $this->get('/cookie-policy?lang=it');
        $cookies->assertOk()->assertHeader('X-Robots-Tag', 'noindex, follow');
        $this->assertSecurityHeaders($cookies->baseResponse);

        $login = $this->get('/login');
        $login->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->assertSecurityHeaders($login->baseResponse);
    }

    public function test_noindex_routes_only_preserve_exact_allowlisted_robots_headers(): void
    {
        $headers = app(SecurityHeaders::class);
        $request = $this->requestForRoute('login');
        $cases = [
            [null, 'noindex, nofollow'],
            ['', 'noindex, nofollow'],
            ['index, follow', 'noindex, nofollow'],
            ['arbitrary', 'noindex, nofollow'],
            ['noindex, nofollow', 'noindex, nofollow'],
            [' NOINDEX, FOLLOW ', 'noindex, follow'],
        ];

        foreach ($cases as [$existing, $expected]) {
            $response = new Response;
            if ($existing !== null) {
                $response->headers->set('X-Robots-Tag', $existing);
            }

            $headers->apply($response, $request);
            $this->assertSame($expected, $response->headers->get('X-Robots-Tag'), (string) $existing);
            $this->assertSecurityHeaders($response);
        }

        $multiValue = new Response;
        $multiValue->headers->set('X-Robots-Tag', ['noindex, follow', 'arbitrary']);
        $headers->apply($multiValue, $request);
        $this->assertSame('noindex, nofollow', $multiValue->headers->get('X-Robots-Tag'));
    }

    public function test_demo_controller_source_does_not_echo_raw_errors_or_input(): void
    {
        $source = file_get_contents(app_path('Http/Controllers/Agent/DemoAgentController.php'));
        $this->assertIsString($source);
        $this->assertStringContainsString('report($exception)', $source);
        $this->assertStringContainsString('temporarily unavailable', $source);
        $this->assertStringNotContainsString('getMessage()', $source);
        $this->assertStringNotContainsString("'payload'", $source);
        $this->assertStringNotContainsString("'message' => \$data->message", $source);
    }

    private function requestForRoute(string $name): Request
    {
        $route = Route::getRoutes()->getByName($name);
        if ($route === null) {
            throw new LogicException("Route [{$name}] is not registered.");
        }

        $request = Request::create($route->uri(), 'GET');
        $request->setRouteResolver(static fn () => $route);

        return $request;
    }

    private function assertSecurityHeaders(Response $response): void
    {
        $this->assertSame('nosniff', $response->headers->get('X-Content-Type-Options'));
        $this->assertSame('strict-origin-when-cross-origin', $response->headers->get('Referrer-Policy'));
        $this->assertSame('DENY', $response->headers->get('X-Frame-Options'));
        $this->assertStringContainsString('camera=()', (string) $response->headers->get('Permissions-Policy'));
        $csp = (string) $response->headers->get('Content-Security-Policy-Report-Only');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString('https://www.googletagmanager.com', $csp);
        $this->assertStringContainsString('https://www.youtube.com', $csp);
        $this->assertStringNotContainsString('*', $csp);
        $this->assertFalse($response->headers->has('Strict-Transport-Security'));
    }
}
