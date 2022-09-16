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

        return Http::withHeaders($headers)
            ->withBody($request->getBody()->getContents(), $contentType)
            ->send($request->getMethod(), $request->getUri())
            ->toPsrResponse();
    }
}
