<?php

declare(strict_types=1);

namespace JustBetter\AkeneoClient\Contracts;

interface ResolvesEvents
{
    public function resolve(string $action): string;
}
