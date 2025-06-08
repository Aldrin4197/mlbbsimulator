<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\PlayerController;

    Route::get('/teams', [TeamController::class, 'index']);
    Route::post('/teams', [TeamController::class, 'store']);
    Route::put('/teams/{id}', [TeamController::class, 'update']);
    Route::delete('/teams/{id}', [TeamController::class, 'destroy']);
    Route::delete('/teams/{id}/with-players', [TeamController::class, 'destroyWithPlayers']);


Route::prefix('players')->group(function () {
    Route::get('/', [PlayerController::class, 'index']);
    Route::post('/', [PlayerController::class, 'store']);    // <== Here
    Route::put('/{player}', [PlayerController::class, 'update']);
    Route::get('/{player}', [PlayerController::class, 'show']);
    Route::delete('/{player}', [PlayerController::class, 'destroy']);
    
});