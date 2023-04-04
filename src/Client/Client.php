<?php

namespace JustBetter\AkeneoClient\Client;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
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
            ->withBody($request->getBody()->getContents(), $contentType)
            ->send($request->getMethod(), $request->getUri())
            ->toPsrResponse();
    }
}
