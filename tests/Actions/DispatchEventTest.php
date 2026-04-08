<?php

declare(strict_types=1);

namespace JustBetter\AkeneoClient\Tests\Actions;

use Illuminate\Support\Facades\Event;
use JustBetter\AkeneoClient\Actions\DispatchEvent;
use JustBetter\AkeneoClient\Contracts\ResolvesEvents;
use JustBetter\AkeneoClient\Events\ProductCreatedEvent;
use JustBetter\AkeneoClient\Tests\TestCase;
use Mockery\MockInterface;

final class DispatchEventTest extends TestCase
{
    public function test_it_can_dispatch_events(): void
    {
        Event::fake();

        $payload = [
            'action' => 'product.created',
            'data' => [
                'resource' => [
                    'identifier' => '1000',
                ],
            ],
        ];

        $this->mock(ResolvesEvents::class, function (MockInterface $mock): void {
            $mock
                ->shouldReceive('resolve')
                ->with('product.created')
                ->once()
                ->andReturn(ProductCreatedEvent::class);
        });

        /** @var DispatchEvent $action */
        $action = app(DispatchEvent::class);
        $action->dispatch($payload);

        Event::assertDispatched(ProductCreatedEvent::class, fn (ProductCreatedEvent $event): bool => $event->event === $payload);
    }
}
