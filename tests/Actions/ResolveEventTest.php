<?php

declare(strict_types=1);

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

final class ResolveEventTest extends TestCase
{
    /**
     * @dataProvider events
     */
    public function test_it_can_resolve_events(string $event, string $class): void
    {
        /** @var ResolveEvent $action */
        $action = app(ResolveEvent::class);

        $resolvedClass = $action->resolve($event);

        $this->assertEquals($class, $resolvedClass);
    }

    public static function events(): \Iterator
    {
        yield [
            'product.created',
            ProductCreatedEvent::class,
        ];
        yield [
            'product.updated',
            ProductUpdatedEvent::class,
        ];
        yield [
            'product.removed',
            ProductRemovedEvent::class,
        ];
        yield [
            'product_model.created',
            ProductModelCreatedEvent::class,
        ];
        yield [
            'product_model.updated',
            ProductModelUpdatedEvent::class,
        ];
        yield [
            'product_model.removed',
            ProductModelRemovedEvent::class,
        ];
    }

    public function test_it_can_throw_exceptions(): void
    {
        $this->expectException(AkeneoException::class);

        /** @var ResolveEvent $action */
        $action = app(ResolveEvent::class);
        $action->resolve('::non-existent::');
    }
}
