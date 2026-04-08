<?php

declare(strict_types=1);

namespace JustBetter\AkeneoClient\Contracts;

interface DispatchesEvents
{
    public function dispatch(array $event): void;
}
