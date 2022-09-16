<?php

namespace JustBetter\AkeneoClient\Tests\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JustBetter\AkeneoClient\Http\Middleware\HMacMiddleware;
use JustBetter\AkeneoClient\Tests\TestCase;

class HMacMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('akeneo.event_secret', '::event-secret::');
    }

    /** @test */
    public function it_can_pass(): void
    {
        $request = $this->fabricateRequest('::event-secret::');

        /** @var HMacMiddleware $middleware */
        $middleware = app(HMacMiddleware::class);

        $passed = false;

        $middleware->handle($request, function () use (&$passed): void {
            $passed = true;
        });

        $this->assertTrue($passed);
    }

    /** @test */
    public function it_can_refuse(): void
    {
        $request = $this->fabricateRequest('::invalid-event-secret::');

        /** @var HMacMiddleware $middleware */
        $middleware = app(HMacMiddleware::class);

        /** @var JsonResponse $response */
        $response = $middleware->handle($request, fn () => $this->fail('Passed middleware'));

        $this->assertEquals(400, $response->getStatusCode());
    }

    protected function fabricateRequest(string $secret): Request
    {
        $content = '::content::';
        $timestamp = (string) time();
        $hmac = $timestamp.'.'.$content;

        $signature = hash_hmac('sha256', $hmac, $secret);

        $request = new Request(content: $content);
        $request->headers->set('X-Akeneo-Request-Signature', $signature);
        $request->headers->set('X-Akeneo-Request-Timestamp', $timestamp);

        return $request;
    }
}
