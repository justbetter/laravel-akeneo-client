<?php

namespace JustBetter\AkeneoClient\Client;

use Akeneo\Pim\ApiClient\Client\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Http\Promise\Promise;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->buildHttpRequest($request)
            ->send($request->getMethod(), $request->getUri())
            ->toPsrResponse();
    }

    public function sendAsync(RequestInterface $request): PromiseInterface|Promise
    {
        /** @var Promise $promise */
        $promise = $this->buildHttpRequest($request)
            ->async()
            ->send($request->getMethod(), $request->getUri());

        return $promise;
    }

    protected function buildHttpRequest(RequestInterface $request): PendingRequest
    {
        $contentTypes = $request->getHeader('Content-Type');

        $headers = $request->getHeaders();

        unset($headers['Content-Type']);

        $contentType = Arr::first(Arr::wrap($contentTypes)) ?? 'application/json';

        /** @var int $timeout */
        $timeout = config('akeneo.timeout');

        /** @var int $connectTimeout */
        $connectTimeout = config('akeneo.connect_timeout');

        return Http::withHeaders($headers)
            ->timeout($timeout)
            ->connectTimeout($connectTimeout)
            ->withBody((string) $request->getBody(), $contentType);
    }
}
