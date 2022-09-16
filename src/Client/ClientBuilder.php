<?php

namespace JustBetter\AkeneoClient\Client;

use Akeneo\Pim\ApiClient\AkeneoPimClientBuilder;

/**
 * This builder will use Laravel's HTTP client which will ease testing.
 */
class ClientBuilder extends AkeneoPimClientBuilder
{
    public function __construct(string $baseUri)
    {
        parent::__construct($baseUri);

        $this->httpClient = new Client();
    }
}
