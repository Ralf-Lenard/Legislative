<?php

use App\Http\Controllers\OrdinancesController;
use App\Http\Controllers\ResolutionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/homes', function () {
    return Inertia::render('Home', [
        'canRegister' => Route::has('register'),
    ]);
});


Route::get('/sb', function () {
    return Inertia::render('SB', [
        'canRegister' => Route::has('register'),
    ]);
});

// ordinances
Route::get('/ordinances', [OrdinancesController::class, 'indexUser'])->name('ordinances.indexUser');
Route::middleware('auth')->group(function () {

    Route::post('/ordinances/{id}/request-access', [OrdinancesController::class, 'submitRequest'])
    ->name('ordinances.request-access');
    // Protected download
    Route::get('/ordinances/pdf/{id}', [OrdinancesController::class, 'downloadPdf'])
        ->name('ordinances.download');

});

// resolution
Route::get('/resolutions', [ResolutionController::class, 'indexUser'])->name('resolutions.indexUser');

// sessions
Route::get('/sessions', function () {
    return Inertia::render('Sessions', [
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/sessions-history', function () {
    return Inertia::render('SessionsHistory', [
        'canRegister' => Route::has('register'),
    ]);
});

// Admin

// ordinances
Route::get('/admin-ordinances', [OrdinancesController::class, 'index'])->name('ordinances.index');
Route::post('/admin-ordinances', [OrdinancesController::class, 'store'])->name('ordinances.store');
Route::post('/admin-ordinances/{id}', [OrdinancesController::class, 'update'])->name('ordinances.update');
Route::delete('/ordinances/{id}', [OrdinancesController::class, 'destroy'])->name('ordinances.destroy');
Route::get('/ordinance-request', [OrdinancesController::class, 'indexRequest'])->name('ordinances.indexRequest');
Route::post('/ordinance-request/{id}/approve', [OrdinancesController::class, 'approveDownloadRequest']);
Route::post('/ordinance-request/{id}/reject', [OrdinancesController::class, 'rejectDownloadRequest']);



// resolutions
Route::get('/admin-resolutions', [ResolutionController::class, 'index'])->name('resolutions.index');
Route::post('/admin-resolutions', [ResolutionController::class, 'store'])->name('resolutions.store');
Route::put('/resolutions/{id}', [ResolutionController::class, 'update'])->name('resolutions.update');
Route::delete('/resolutions/{id}', [ResolutionController::class, 'destroy'])->name('resolutions.destroy');
Route::get('/resolution-request', [ResolutionController::class, 'indexRequest'])->name('resolutions.indexRequest');
Route::post('/resolution-request/{id}/approve', [ResolutionController::class, 'approveDownloadRequest']);
Route::post('/resolution-request/{id}/reject', [ResolutionController::class, 'rejectDownloadRequest']);

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
