<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfficialController;
use App\Http\Controllers\OrdinancesController;
use App\Http\Controllers\ResolutionController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Models\Notification;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canRegister' => Features::enabled(Features::registration()),
//     ]);
// })->name('home');

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canRegister' => Features::enabled(Features::registration()),
//     ]);
// })->name('home');

Route::get('/', [HomeController::class, 'welcome'])->name('home');
Route::get('/dashboard', [HomeController::class, 'indexAdmin'])->middleware(['auth', 'admin_or_super'])->name('dashboard');


Route::get('/terms-of-service', function () {
    return Inertia::render('TermsOfService');
});

Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
});

// Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->middleware(['auth', 'verified'])->name('admin.dashboard');


Route::get('/sanguniang-bayan-members', [OfficialController::class, 'indexUser']);

// ordinances
Route::get('/ordinances', [OrdinancesController::class, 'indexUser'])->name('ordinances.indexUser');
Route::middleware('auth')->group(function () {

    Route::post('/ordinances/{id}/request-access', [OrdinancesController::class, 'submitRequest'])->name('ordinances.request-access');
    // Protected download
    Route::get('/ordinance/download/{id}', [OrdinancesController::class, 'download'])->name('ordinance.download');
});

// resolution
Route::get('/resolutions', [ResolutionController::class, 'indexUser'])->name('resolutions.indexUser');

Route::middleware('auth')->group(function () {

    Route::post('/resolutions/{id}/request-access', [ResolutionController::class, 'submitResolutionRequest'])->name('resolutions.request-access');
    // Protected download
    Route::get('/resolution/download/{id}', [ResolutionController::class, 'download'])->name('resolution.download');
});

// sessions
Route::get('/sessions', [SessionController::class, 'indexUser'])->name('sessions.indexUser');
Route::get('/session-details/{id}', [SessionController::class, 'showUser'])->name('sessions.showUser');

Route::get('/announcement-&-news', function () {
    return Inertia::render('User/Announcement', [
        'canRegister' => Route::has('register'),
    ]);
});

// profile 
// profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('user.profile.edit');

    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('user.profile.update');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('user.profile.password');
});


// ADMIN
Route::middleware(['auth', 'admin_or_super'])->group(function () {

    // ordinances
    Route::get('/admin-ordinances', [OrdinancesController::class, 'index'])->name('ordinances.index');
    Route::post('/admin-ordinances', [OrdinancesController::class, 'store'])->name('ordinances.store');
    Route::put('/admin-ordinances/{id}', [OrdinancesController::class, 'update'])->name('ordinances.update');
    Route::delete('/ordinances/{id}', [OrdinancesController::class, 'destroy'])->name('ordinances.destroy');
    Route::get('/ordinance-request', [OrdinancesController::class, 'indexRequest'])->name('ordinances.indexRequest');
    Route::post('/ordinance-request/{id}/approve', [OrdinancesController::class, 'approveDownloadRequest']);
    Route::post('/ordinance-request/{id}/reject', [OrdinancesController::class, 'rejectDownloadRequest']);

    // resolutions
    Route::get('/admin-resolutions', [ResolutionController::class, 'index'])->name('resolutions.index');
    Route::post('/admin-resolutions', [ResolutionController::class, 'store'])->name('resolutions.store');
    Route::put('/admin-resolutions/{id}', [ResolutionController::class, 'update'])->name('resolutions.update');
    Route::delete('/admin-resolutions/{id}', [ResolutionController::class, 'destroy'])->name('resolutions.destroy');
    Route::get('/resolution-request', [ResolutionController::class, 'indexRequest'])->name('resolutions.indexRequest');
    Route::post('/resolution-request/{id}/approve', [ResolutionController::class, 'approveDownloadRequest']);
    Route::post('/resolution-request/{id}/reject', [ResolutionController::class, 'rejectDownloadRequest']);

    // sesion
    Route::get('/admin-sessions', [SessionController::class, 'index'])->name('sessions.index');
    Route::post('/admin-sessions', [SessionController::class, 'store'])->name('sessions.store');
    Route::put('/admin-sessions/{session}', [SessionController::class, 'update'])->name('sessions.update');
    Route::delete('/admin-sessions/{session}', [SessionController::class, 'destroy'])->name('sessions.destroy');

    //officials
    Route::get('/admin-officials', [OfficialController::class, 'index'])->name('officials.index');
    Route::post('/admin-officials', [OfficialController::class, 'store'])->name('officials.store');
    Route::put('/admin-officials/{id}', [OfficialController::class, 'update'])->name('officials.update');
    Route::delete('/admin-officials/{id}', [OfficialController::class, 'destroy'])->name('officials.destroy');

    // Profile settings
    Route::get('/profile-settings', [ProfileController::class, 'editAdmin'])->name('admin.profile-settings');
    // Update Admin Profile
    Route::post('/admin/profile/update', [ProfileController::class, 'updateAdmin'])->name('admin.profile.update');
    // Admin Password (optional: same as user password route)
    Route::post('/admin/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');

    Route::get('/home-content', [HomeController::class, 'IndexAdminPageContent'])->name('admin.IndexAdminPageContent');
    Route::post('/page-content', [HomeController::class, 'store'])->name('admin.page-content.store');

    // Update existing page content
    Route::put('/page-content/{id}', [HomeController::class, 'update'])
        ->name('admin.page-content.update');
    });

// super admin

Route::middleware(['auth', 'super_admin'])->group(function () {
    Route::get('/super-admin-users', [SuperAdminController::class, 'index'])->name('superadmin.users');
    Route::post('/super-admin/promote/{id}', [SuperAdminController::class, 'promoteToAdmin'])->name('superadmin.promoteToAdmin');
    Route::post('/super-admin/demote/{id}', [SuperAdminController::class, 'promoteToUser'])->name('superadmin.promoteToUser');
    Route::post('/super-admin/ban-user/{id}', [SuperAdminController::class, 'banUser'])->name('superadmin.banUser');
    Route::post('/super-admin/unban-user/{id}', [SuperAdminController::class, 'unbanUser'])->name('superadmin.unbanUser');
    Route::delete('/super-admin-users/{id}', [SuperAdminController::class, 'destroy'])->name('superadmin.destroy');
});

// notifications
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
Route::delete('/notifications/{id}', [NotificationController::class, 'delete']);
Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll']);


require __DIR__ . '/settings.php';
