<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/teams', function () {
    return Inertia::render('Teams/Index');
})->name('teams.index');


Route::get('/players', function () {
    return Inertia::render('Players/Index');
})->name('players.index');


Route::get('/draft', function () {
    return Inertia::render('Draft/Index');
})->name('draft.index');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
