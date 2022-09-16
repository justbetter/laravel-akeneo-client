<?php

namespace JustBetter\AkeneoClient\Tests\Actions;

use JustBetter\AkeneoClient\Actions\ResolveEvent;
use JustBetter\AkeneoClient\Events\ProductCreatedEvent;
use JustBetter\AkeneoClient\Events\ProductModelCreatedEvent;
use JustBetter\AkeneoClient\Events\ProductModelRemovedEvent;
use JustBetter\AkeneoClient\Events\ProductModelUpdatedEvent;
use JustBetter\AkeneoClient\Events\ProductRemovedEvent;
use JustBetter\AkeneoClient\Events\ProductUpdatedEvent;
use JustBetter\AkeneoClient\Exceptions\AkeneoException;
use JustBetter\AkeneoClient\Tests\TestCase;

class ResolveEventTest extends TestCase
{
    /**
     * @test
     * @dataProvider events
     */
    public function it_can_resolve_events(string $event, string $class): void
    {
        /** @var ResolveEvent $action */
        $action = app(ResolveEvent::class);

        $resolvedClass = $action->resolve($event);

        $this->assertEquals($class, $resolvedClass);
    }

    public function events(): array
    {
        return [
            [
                'product.created',
                ProductCreatedEvent::class,
            ],
            [
                'product.updated',
                ProductUpdatedEvent::class,
            ],
            [
                'product.removed',
                ProductRemovedEvent::class,
            ],
            [
                'product_model.created',
                ProductModelCreatedEvent::class,
            ],
            [
                'product_model.updated',
                ProductModelUpdatedEvent::class,
            ],
            [
                'product_model.removed',
                ProductModelRemovedEvent::class,
            ],
        ];
    }

    /** @test */
    public function it_can_throw_exceptions(): void
    {
        $this->expectException(AkeneoException::class);

        /** @var ResolveEvent $action */
        $action = app(ResolveEvent::class);
        $action->resolve('::non-existent::');
    }
}
