<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfficialController;
use App\Http\Controllers\OrdinancesController;
use App\Http\Controllers\ResolutionController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScholarController;
use App\Http\Controllers\SuperAdminController;
use App\Models\Notification;
use App\Http\Controllers\AssistanceController;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

// welcome page
Route::get('/terms-of-service', function () {
    return Inertia::render('TermsOfService');
});
Route::get('/privacy-policy', function () {
    return Inertia::render('PrivacyPolicy');
});


Route::get('/dashboard', [HomeController::class, 'indexAdmin'])->middleware(['auth', 'admin_or_super'])->name('dashboard');

Route::get('/', [HomeController::class, 'welcome'])->name('home');

// organizational chart
Route::get('/organizational-chart', [OfficialController::class, 'indexUser']);

// ordinances
Route::get('/citizens-charter/ordinances', [OrdinancesController::class, 'indexUser'])
    ->name('ordinances.indexUser');

// resolutions
Route::get('/citizens-charter/resolutions', [ResolutionController::class, 'indexUser'])
    ->name('resolutions.indexUser');

// sessions
Route::get('/legislative-sessions', [SessionController::class, 'indexUser'])
    ->name('sessions.indexUser');

Route::get('/legislative-session-details/{id}', [SessionController::class, 'showUser'])
    ->name('sessions.showUser');

Route::get('/library', [BookController::class, 'indexUser']);

// announcements
// Route::get('/citizens-charter', function () {
//     return Inertia::render('User/CitizenChart', [
//         'canRegister' => Route::has('register'),
//     ]);
// });

// Route::get('/citizens-charter/public-assistance', [AssistanceController::class, 'indexUser'])->name('assistances.public');
Route::get('/citizens-charter', [AssistanceController::class, 'citizenCharter']);



// =======================
// PROTECTED ROUTES
// LOGIN + NOT BANNED
// =======================
Route::middleware(['auth', 'check.banned'])->group(function () {

    // ordinances
    Route::post('/ordinances/{id}/request-access',
        [OrdinancesController::class, 'submitRequest']
    )->name('ordinances.request-access');

    Route::get('/ordinance/download/{id}',
        [OrdinancesController::class, 'download']
    )->name('ordinance.download');


    // resolutions
    Route::post('/resolutions/{id}/request-access',
        [ResolutionController::class, 'submitResolutionRequest']
    )->name('resolutions.request-access');

    Route::get('/resolution/download/{id}',
        [ResolutionController::class, 'download']
    )->name('resolution.download');


    // profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('user.profile.edit');

    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('user.profile.update');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('user.profile.password');

    Route::get('/document-requests', [ProfileController::class, 'documentRequest'])->name('document.requests');
});


// ADMIN
Route::middleware(['auth', 'admin_or_super', 'check.banned'])->group(function () {

    // ordinances
    Route::get('/admin-ordinances', [OrdinancesController::class, 'index'])->name('ordinances.index');
    Route::post('/admin-ordinances', [OrdinancesController::class, 'store'])->name('ordinances.store');
    Route::put('/admin-ordinances/{id}', [OrdinancesController::class, 'update'])->name('ordinances.update');
    Route::delete('/ordinances/{id}', [OrdinancesController::class, 'destroy'])->name('ordinances.destroy');
    Route::get('/ordinance-request', [OrdinancesController::class, 'indexRequest'])->name('ordinances.indexRequest');

    // resolutions
    Route::get('/admin-resolutions', [ResolutionController::class, 'index'])->name('resolutions.index');
    Route::post('/admin-resolutions', [ResolutionController::class, 'store'])->name('resolutions.store');
    Route::put('/admin-resolutions/{id}', [ResolutionController::class, 'update'])->name('resolutions.update');
    Route::delete('/admin-resolutions/{id}', [ResolutionController::class, 'destroy'])->name('resolutions.destroy');
    Route::get('/resolution-request', [ResolutionController::class, 'indexRequest'])->name('resolutions.indexRequest');
   
    // sesion
    Route::get('/admin-sessions', [SessionController::class, 'index'])->name('sessions.index');
    Route::post('/admin-sessions', [SessionController::class, 'store'])->name('sessions.store');
    Route::put('/admin-sessions/{session}', [SessionController::class, 'update'])->name('sessions.update');
    Route::delete('/admin-sessions/{session}', [SessionController::class, 'destroy'])->name('sessions.destroy');

    //officials
    Route::get('/admin-organizational-chart', [OfficialController::class, 'index'])->name('officials.index');
    Route::post('/admin-officials', [OfficialController::class, 'store'])->name('officials.store');
    Route::put('/admin-officials/{id}', [OfficialController::class, 'update'])->name('officials.update');
    Route::delete('/admin-officials/{id}', [OfficialController::class, 'destroy'])->name('officials.destroy');

    //library
    Route::get('/admin-library', [BookController::class, 'index'])->name('books.index');
    Route::post('/admin-library', [BookController::class, 'store'])->name('books.store');
    Route::put('/admin-library/{id}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

    // assisstance
    Route::get('/admin-assistances', [AssistanceController::class, 'index'])->name('assistances.index');
    Route::post('/admin-assistance', [AssistanceController::class, 'store'])->name('assistances.store');
    Route::put('/admin-assistance/{id}', [AssistanceController::class, 'update'])->name('assistances.update');
    Route::delete('/admin-assistance/{id}', [AssistanceController::class, 'destroy'])->name('assistances.destroy');



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


    // REQUEST APPROVE AND REJECT

    // RESOLUTION
    Route::post('/resolution-request/{id}/approve', [ResolutionController::class, 'approveDownloadRequest']);
    Route::post('/resolution-request/{id}/reject', [ResolutionController::class, 'rejectDownloadRequest']);

    // ORDINANCE
    Route::post('/ordinance-request/{id}/approve', [OrdinancesController::class, 'approveDownloadRequest']);
    Route::post('/ordinance-request/{id}/reject', [OrdinancesController::class, 'rejectDownloadRequest']);
});

// notifications
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
Route::delete('/notifications/{id}', [NotificationController::class, 'delete']);
Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll']);


require __DIR__ . '/settings.php';
