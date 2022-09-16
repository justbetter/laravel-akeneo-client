<?php

namespace JustBetter\AkeneoClient\Contracts;

interface DispatchesEvents
{
    public function dispatch(array $event): void;
}
