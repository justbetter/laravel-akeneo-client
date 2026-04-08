<?php

declare(strict_types=1);

namespace JustBetter\AkeneoClient\Events;

abstract class AkeneoEvent
{
    public function __construct(
        public array $event
    ) {
        //
    }
}
