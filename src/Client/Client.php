<?php

namespace JustBetter\AkeneoClient\Client;

use Akeneo\Pim\ApiClient\Client\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Http\Promise\Promise;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use JustBetter\AkeneoClient\Exceptions\AkeneoException;
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

    public function sendAsyncRequest(RequestInterface $request): PromiseInterface|Promise
    {
        $promise = $this->buildHttpRequest($request)
            ->async()
            ->getPromise();

        if (is_null($promise)) {
            throw new AkeneoException('Unable to send async request, empty promise given!');
        }

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
