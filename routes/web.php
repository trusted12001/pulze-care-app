<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Super Admin
use App\Http\Controllers\Backend\SuperAdmin\UserController as SuperUserController;
use App\Http\Controllers\Backend\SuperAdmin\TenantController;

// Admin
use App\Http\Controllers\Backend\Admin\UserController as AdminUserController;
use App\Http\Controllers\Backend\Admin\StaffProfileController;
use App\Http\Controllers\Backend\Admin\ServiceUserController;


/*
|--------------------------------------------------------------------------
| Public / Auth basics
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('auth.login'));

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/operations', fn () => view('operations.index'))->name('operations');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| 👑 Super Admin
|--------------------------------------------------------------------------
*/
Route::prefix('backend/super-admin')
    ->middleware(['auth', 'role:super-admin'])
    ->name('backend.super-admin.')
    ->group(function () {
        Route::view('/', 'backend.super-admin.index')->name('index');

        // Users (Super Admin)
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('trashed', [SuperUserController::class, 'trashed'])->name('trashed');
            Route::post('{id}/restore', [SuperUserController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [SuperUserController::class, 'forceDelete'])->name('forceDelete');

            Route::get('/', [SuperUserController::class, 'index'])->name('index');
            Route::get('create', [SuperUserController::class, 'create'])->name('create');
            Route::post('/', [SuperUserController::class, 'store'])->name('store');
            Route::get('{user}', [SuperUserController::class, 'show'])->name('show');
            Route::get('{user}/edit', [SuperUserController::class, 'edit'])->name('edit');
            Route::put('{user}', [SuperUserController::class, 'update'])->name('update');
            Route::delete('{user}', [SuperUserController::class, 'destroy'])->name('destroy');
        });

        // Tenants (Super Admin)
        Route::get('tenants/trashed', [TenantController::class, 'trashed'])->name('tenants.trashed');
        Route::post('tenants/{id}/restore', [TenantController::class, 'restore'])->name('tenants.restore');
        Route::delete('tenants/{id}/force-delete', [TenantController::class, 'forceDelete'])->name('tenants.forceDelete');

        Route::resource('tenants', TenantController::class)->names([
            'index'   => 'tenants.index',
            'create'  => 'tenants.create',
            'store'   => 'tenants.store',
            'edit'    => 'tenants.edit',
            'update'  => 'tenants.update',
            'destroy' => 'tenants.destroy',
            'show'    => 'tenants.show',
        ]);
    });

/*
|--------------------------------------------------------------------------
| 🛠️ Admin
|--------------------------------------------------------------------------
| If you use a tenant middleware, add it here: ['auth','role:admin','tenant']
*/
Route::prefix('backend/admin')
    ->middleware(['auth', 'role:admin'])
    ->name('backend.admin.')
    ->group(function () {
        Route::view('/', 'backend.admin.index')->name('index');

        // Users (Admin)
        Route::get('users/trashed', [AdminUserController::class, 'trashed'])->name('users.trashed');
        Route::post('users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{id}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.forceDelete');

        Route::resource('users', AdminUserController::class)->except(['create'])->names([
            'index'   => 'users.index',
            'store'   => 'users.store',
            'show'    => 'users.show',
            'edit'    => 'users.edit',
            'update'  => 'users.update',
            'destroy' => 'users.destroy',
        ]);

        // ✅ Staff Profiles (Admin) — controller-driven + trash ops
        Route::get('staff-profiles/trashed', [StaffProfileController::class, 'trashed'])->name('staff-profiles.trashed');
        Route::post('staff-profiles/{id}/restore', [StaffProfileController::class, 'restore'])->name('staff-profiles.restore');
        Route::delete('staff-profiles/{id}/force-delete', [StaffProfileController::class, 'forceDelete'])->name('staff-profiles.forceDelete');

        Route::resource('staff-profiles', StaffProfileController::class)->names([
            'index'   => 'staff-profiles.index',
            'create'  => 'staff-profiles.create',
            'store'   => 'staff-profiles.store',
            'show'    => 'staff-profiles.show',
            'edit'    => 'staff-profiles.edit',
            'update'  => 'staff-profiles.update',
            'destroy' => 'staff-profiles.destroy',
        ]);


    // Service Users (Admin)
    // Trashed / restore / force-delete
    Route::get('service-users/trashed', [\App\Http\Controllers\Backend\Admin\ServiceUserController::class, 'trashed'])
        ->name('service-users.trashed');
    Route::post('service-users/{id}/restore', [\App\Http\Controllers\Backend\Admin\ServiceUserController::class, 'restore'])
        ->name('service-users.restore');
    Route::delete('service-users/{id}/force-delete', [\App\Http\Controllers\Backend\Admin\ServiceUserController::class, 'forceDelete'])
        ->name('service-users.forceDelete');

    // Resource
    Route::get('service-users/{service_user}/profile', [\App\Http\Controllers\Backend\Admin\ServiceUserController::class, 'profile'])
    ->name('service-users.profile');

    Route::resource('service-users', \App\Http\Controllers\Backend\Admin\ServiceUserController::class)->names([
        'index'   => 'service-users.index',
        'create'  => 'service-users.create',
        'store'   => 'service-users.store',
        'show'    => 'service-users.show',
        'edit'    => 'service-users.edit',
        'update'  => 'service-users.update',
        'destroy' => 'service-users.destroy',
    ]);


    // Location Setup (Admin)
    Route::get('locations/trashed', [\App\Http\Controllers\Backend\Admin\LocationController::class, 'trashed'])->name('locations.trashed');
    Route::post('locations/{id}/restore', [\App\Http\Controllers\Backend\Admin\LocationController::class, 'restore'])->name('locations.restore');
    Route::delete('locations/{id}/force-delete', [\App\Http\Controllers\Backend\Admin\LocationController::class, 'forceDelete'])->name('locations.forceDelete');

    Route::resource('locations', \App\Http\Controllers\Backend\Admin\LocationController::class)->names([
        'index'   => 'locations.index',
        'create'  => 'locations.create',
        'store'   => 'locations.store',
        'show'    => 'locations.show',
        'edit'    => 'locations.edit',
        'update'  => 'locations.update',
        'destroy' => 'locations.destroy',
    ]);


    // NEW: section-specific update endpoint
    Route::patch('service-users/{service_user:id}/section/{section}',
        [ServiceUserController::class, 'updateSection']
    )->name('service-users.update-section');
});


/*
|--------------------------------------------------------------------------
| 🧑‍⚕️ Carer
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:carer'])
    ->prefix('frontend')
    ->name('frontend.')
    ->group(function () {
        Route::view('/carer', 'frontend.carer.index')->name('carer.index');
    });

require __DIR__.'/auth.php';
