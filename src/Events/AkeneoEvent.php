<?php

namespace JustBetter\AkeneoClient\Events;

abstract class AkeneoEvent
{
    public function __construct(
        public array $event
    ) {
        //
    }
}
