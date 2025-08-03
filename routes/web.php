<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TenantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\SuperAdmin\UserController;

// Default route
Route::get('/', function () {
    return view('auth.login');
});

// Dashboard (for all authenticated users)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    Route::get('/operations', fn () => view('operations.index'))->name('operations');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Super Admin routes
Route::prefix('backend/super-admin')
    ->middleware(['auth', 'role:super-admin'])
    ->name('backend.super-admin.')
    ->group(function () {
        Route::view('/', 'backend.super-admin.index')->name('index');

        // ✅ RESTful UserController routes
        Route::resource('users', UserController::class)->names([
            'index' => 'users.index',
            'store' => 'users.store',
            'create' => 'users.create',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
            'show' => 'users.show',
        ]);
    });


// Admin routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('backend')
    ->name('backend.')
    ->group(function () {
        Route::view('/admin', 'backend.admin.index')->name('admin.index');
    });

// Carer routes
Route::middleware(['auth', 'role:carer'])
    ->prefix('frontend')
    ->name('frontend.')
    ->group(function () {
        Route::view('/carer', 'frontend.carer.index')->name('carer.index');
    });

// Shared admin views (temporary until migrated properly)
Route::middleware(['auth'])->group(function () {
    Route::view('/manage-staff', 'admin.manage-staff.index')->name('manage.staff');
    Route::view('/staff-profile', 'admin.staff-profile.index')->name('staff.profile');
    Route::view('/assignments', 'admin.assignments.index')->name('assignments');
    Route::view('/service-users', 'admin.service-users.index')->name('service.users');
    Route::view('/timesheets', 'admin.timesheets.index')->name('timesheets');
    Route::view('/reports', 'admin.reports.index')->name('reports');
    Route::view('/urgent-cases', 'admin.urgent-cases.index')->name('urgent.cases');
});

require __DIR__.'/auth.php';
