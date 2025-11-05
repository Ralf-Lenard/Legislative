<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/home', function () {
    return Inertia::render('Home');
});

Route::get('/sb', function () {
    return Inertia::render('SB');
});

Route::get('/ordinances', function () {
    return Inertia::render('Ordinances');
});

Route::get('/resolutions', function () {
    return Inertia::render('Resolutions');
});


Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
