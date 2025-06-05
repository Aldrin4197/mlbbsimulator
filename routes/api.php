<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TeamController;

Route::get('/teams', [TeamController::class, 'index']);  // For fetching teams
Route::post('/teams', [TeamController::class, 'store']); // For creating teams
Route::delete('/teams/{id}', [TeamController::class, 'destroy']); // For deleting teams