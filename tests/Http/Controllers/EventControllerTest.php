<?php

namespace JustBetter\AkeneoClient\Tests\Http\Controllers;

use Illuminate\Support\Facades\Bus;
use JustBetter\AkeneoClient\Jobs\EventJob;
use JustBetter\AkeneoClient\Tests\TestCase;

class EventControllerTest extends TestCase
{
    /** @test */
    public function it_can_dispatch_jobs(): void
    {
        Bus::fake();

        $this
            ->withoutMiddleware()
            ->post('akeneo/event', [
                'events' => [
                    [
                        'action' => 'product.created',
                        'data' => [
                            'resource' => [
                                'identifier' => '1000',
                            ],
                        ],
                    ],
                    [
                        'action' => 'product.updated',
                        'data' => [
                            'resource' => [
                                'identifier' => '2000',
                            ],
                        ],
                    ],
                ],
            ]);

        Bus::assertDispatched(EventJob::class, 2);
    }
}
