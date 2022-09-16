<?php

namespace JustBetter\AkeneoClient\Actions;

use Illuminate\Support\Facades\Event;
use JustBetter\AkeneoClient\Contracts\DispatchesEvents;
use JustBetter\AkeneoClient\Contracts\ResolvesEvents;

class DispatchEvent implements DispatchesEvents
{
    public function __construct(
        public ResolvesEvents $resolvesEvents
    ) {
    }

    public function dispatch(array $event): void
    {
        $class = $this->resolvesEvents->resolve($event['action']);

        Event::dispatch(
            app($class, [
                'event' => $event,
            ])
        );
    }

    public static function bind(): void
    {
        app()->singleton(DispatchesEvents::class, static::class);
    }
}
