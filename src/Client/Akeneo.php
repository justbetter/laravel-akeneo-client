<?php

namespace JustBetter\AkeneoClient\Client;

use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Illuminate\Support\Facades\Http;
use JustBetter\AkeneoClient\Exceptions\AkeneoException;

/** @mixin AkeneoPimClientInterface */
class Akeneo
{
    protected AkeneoPimClientInterface $client;

    public function __construct(string $url, string $clientId, string $secret, string $username, string $password)
    {
        $clientBuilder = new ClientBuilder($url);

        $this->client = $clientBuilder->buildAuthenticatedByPassword(
            clientId: $clientId,
            secret: $secret,
            username: $username,
            password: $password
        );
    }

    public function __call(string $method, array $args): mixed
    {
        $callable = [$this->client, $method];

        if (! is_callable($callable)) {
            throw new AkeneoException('Method "'.$method.'" is not callable');
        }

        return call_user_func_array($callable, $args);
    }

    public static function fake(): void
    {
        config()->set('akeneo', [
            'url' => 'akeneo',
            'client_id' => '::client-id::',
            'secret' => '::secret::',
            'username' => '::username::',
            'password' => '::password::',
            'webhook_secret' => '::webhook-secret::',
        ]);

        Http::fake([
            'akeneo/api/oauth/v1/token' => Http::response([
                'access_token' => '::access-token::',
                'expires_in' => 3600,
                'token_type' => 'bearer',
                'scope' => null,
                'refresh_token' => '::refresh-token::',
            ]),
        ]);
    }
}
