<?php

use Illuminate\Support\Facades\Route;
use JustBetter\AkeneoClient\Http\Controllers\EventController;

Route::post('event', [EventController::class, 'process'])
    ->name('akeneo.event');
