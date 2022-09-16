<?php

namespace JustBetter\AkeneoClient\Contracts;

interface ResolvesEvents
{
    public function resolve(string $action): string;
}
