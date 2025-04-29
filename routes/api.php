<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::prefix('shows')->group(function() {
    Route::get('/', [EventController::class, 'index']);
    Route::get('/{showId}/events', [EventController::class, 'events']);
});

Route::prefix('events')->group(function() {
    Route::get('/{eventId}/places', [EventController::class, 'places']);
    Route::post('/{eventId}/reserve', [EventController::class, 'reserve']);
});
