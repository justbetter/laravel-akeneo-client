<?php

namespace JustBetter\AkeneoClient\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HMacMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $secret = config('akeneo.event_secret', '');

        /** @var string $signature */
        $signature = $request->header('X-Akeneo-Request-Signature', '');

        /** @var string $timestamp */
        $timestamp = $request->header('X-Akeneo-Request-Timestamp', '');

        $requestBody = $request->getContent();
        $signedPayload = $timestamp.'.'.$requestBody;

        $generatedSignature = hash_hmac('sha256', $signedPayload, $secret);

        if (! hash_equals($signature, $generatedSignature)) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        return $next($request);
    }
}
