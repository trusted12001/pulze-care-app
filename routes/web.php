<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TenantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/operations', function () {
        return view('operations.index');
    })->name('operations');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ Admin routes (for super-admin only)
Route::middleware(['auth', 'role:super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('sadmins', TenantController::class);
});


Route::middleware(['auth', 'role:super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/sadmins', function () {
        return view('admin.sadmins.index');
    })->name('sadmins.index');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/admins', function () {
        return view('admin.admins.index');
    })->name('admins.index');
});

Route::middleware(['auth', 'role:carer'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/carers', function () {
        return view('admin.carers.index');
    })->name('carers.index');
});

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
