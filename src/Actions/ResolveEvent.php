<?php

namespace JustBetter\AkeneoClient\Actions;

use Illuminate\Support\Str;
use JustBetter\AkeneoClient\Contracts\ResolvesEvents;
use JustBetter\AkeneoClient\Exceptions\AkeneoException;

class ResolveEvent implements ResolvesEvents
{
    public function resolve(string $action): string
    {
        /** @var string $eventName */
        $eventName = Str::of($action)
            ->replace('.', '_')
            ->studly();

        $class = 'JustBetter\AkeneoClient\Events\\'.$eventName.'Event';

        if (! class_exists($class)) {
            throw new AkeneoException('Class "'.$class.'" does not exist.');
        }

        return $class;
    }

    public static function bind(): void
    {
        app()->singleton(ResolvesEvents::class, static::class);
    }
}
