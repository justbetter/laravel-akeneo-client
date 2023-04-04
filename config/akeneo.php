<?php

return [

    'url' => env('AKENEO_URL'),

    'client_id' => env('AKENEO_CLIENT_ID'),

    'secret' => env('AKENEO_SECRET'),

    'username' => env('AKENEO_USERNAME'),

    'password' => env('AKENEO_PASSWORD'),

    'event_secret' => env('AKENEO_EVENT_SECRET'),

    'queue' => 'default',

    'prefix' => 'akeneo',

    'middleware' => [
        \JustBetter\AkeneoClient\Http\Middleware\HMacMiddleware::class,
    ],

    /* Specify the timeout (in seconds) for the request. */
    'timeout' => 30,

    /* Specify the connection timeout (in seconds) for the request. */
    'connect_timeout' => 10,
];
