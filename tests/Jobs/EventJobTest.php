<?php

namespace JustBetter\AkeneoClient\Tests\Jobs;

use JustBetter\AkeneoClient\Contracts\DispatchesEvents;
use JustBetter\AkeneoClient\Jobs\EventJob;
use JustBetter\AkeneoClient\Tests\TestCase;
use Mockery\MockInterface;

class EventJobTest extends TestCase
{
    /** @test */
    public function it_can_dispatch_events(): void
    {
        $payload = [
            'action' => 'product.updated',
            'data' => [
                'resource' => [
                    'identifier' => '1000',
                ],
            ],
        ];

        $this->mock(DispatchesEvents::class, function (MockInterface $mock) use ($payload): void {
            $mock
                ->shouldReceive('dispatch')
                ->with($payload)
                ->once();
        });

        EventJob::dispatch($payload);
    }
}
