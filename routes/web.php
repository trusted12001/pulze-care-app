<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\SuperAdmin\UserController;
use App\Http\Controllers\Backend\Admin\UserController as AdminUserController;
use App\Http\Controllers\Backend\SuperAdmin\TenantController;

// 🔐 Default route
Route::get('/', function () {
    return view('auth.login');
});

// 🔐 Dashboard (for all authenticated users)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 🔐 Authenticated user profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/operations', fn () => view('operations.index'))->name('operations');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 👑 Super Admin routes
Route::prefix('backend/super-admin')
    ->middleware(['auth', 'role:super-admin'])
    ->name('backend.super-admin.')
    ->group(function () {
        Route::view('/', 'backend.super-admin.index')->name('index');

        // 🧑‍💼 Users management routes
        Route::prefix('users')
            ->name('users.')
            ->group(function () {
                // Custom trash, restore, force-delete
                Route::get('trashed', [UserController::class, 'trashed'])->name('trashed');
                Route::post('{id}/restore', [UserController::class, 'restore'])->name('restore');
                Route::delete('{id}/force-delete', [UserController::class, 'forceDelete'])->name('forceDelete');

                // Standard RESTful routes
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::get('create', [UserController::class, 'create'])->name('create');
                Route::post('/', [UserController::class, 'store'])->name('store');
                Route::get('{user}', [UserController::class, 'show'])->name('show');
                Route::get('{user}/edit', [UserController::class, 'edit'])->name('edit');
                Route::put('{user}', [UserController::class, 'update'])->name('update');
                Route::delete('{user}', [UserController::class, 'destroy'])->name('destroy');
            });


        // ✅ Tenants CRUD & soft delete routes
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
            'show'    => 'tenants.show', // Optional: not used yet, but reserved
        ]);

    });

// 🛠️ Admin routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('backend/admin')
    ->name('backend.admin.')
    ->group(function () {
        Route::view('/', 'backend.admin.index')->name('index');
        Route::get('users/trashed', [AdminUserController::class, 'trashed'])->name('users.trashed');
        Route::post('{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
        Route::delete('{id}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.forceDelete');

        Route::resource('users', AdminUserController::class)->except(['create'])->names([
            'index'   => 'users.index',
            'store'   => 'users.store',
            'show'    => 'users.show',
            'edit'    => 'users.edit',
            'update'  => 'users.update',
            'destroy' => 'users.destroy',
        ]);
    });

// 🧑‍⚕️ Carer routes
Route::middleware(['auth', 'role:carer'])
    ->prefix('frontend')
    ->name('frontend.')
    ->group(function () {
        Route::view('/carer', 'frontend.carer.index')->name('carer.index');
    });

// 📁 Shared admin views
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
