<?php

namespace JustBetter\AkeneoClient\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use JustBetter\AkeneoClient\Contracts\DispatchesEvents;

class EventJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public array $event
    ) {
        $this->onQueue(config('akeneo.queue'));
    }

    public function handle(DispatchesEvents $dispatchesEvents): void
    {
        $dispatchesEvents->dispatch($this->event);
    }
}
