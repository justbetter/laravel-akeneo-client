<?php

namespace JustBetter\AkeneoClient\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use JustBetter\AkeneoClient\Jobs\EventJob;

class EventController extends Controller
{
    public function process(Request $request): JsonResponse
    {
        $request
            ->collect('events')
            ->each(fn (array $event) => EventJob::dispatch($event));

        return response()->json([], 204);
    }
}
