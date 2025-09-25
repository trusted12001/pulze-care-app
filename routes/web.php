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

use App\Http\Controllers\Backend\Admin\StaffAdjustmentController;
use App\Http\Controllers\Backend\Admin\StaffDrivingLicenceController;

use App\Http\Controllers\Backend\Admin\StaffContractController;
use App\Http\Controllers\Backend\Admin\StaffRegistrationController;
use App\Http\Controllers\Backend\Admin\StaffEmploymentCheckController;
use App\Http\Controllers\Backend\Admin\StaffVisaController;
use App\Http\Controllers\Backend\Admin\StaffTrainingRecordController;
use App\Http\Controllers\Backend\Admin\StaffSupervisionAppraisalController;
use App\Http\Controllers\Backend\Admin\StaffQualificationController;
use App\Http\Controllers\Backend\Admin\StaffOccHealthClearanceController;
use App\Http\Controllers\Backend\Admin\StaffImmunisationController;
use App\Http\Controllers\Backend\Admin\StaffPayrollController;
use App\Http\Controllers\Backend\Admin\StaffBankAccountController;
use App\Http\Controllers\Backend\Admin\StaffLeaveRecordController;
use App\Http\Controllers\Backend\Admin\StaffLeaveEntitlementController;
use App\Http\Controllers\Backend\Admin\StaffAvailabilityPreferenceController;
use App\Http\Controllers\Backend\Admin\StaffEmergencyContactController;
use App\Http\Controllers\Backend\Admin\StaffEqualityDataController;
use App\Http\Controllers\Backend\Admin\StaffDisciplinaryRecordController;
use App\Http\Controllers\Backend\Admin\StaffDocumentController;
use App\Http\Controllers\Backend\Admin\RiskAssessmentController;

use App\Http\Controllers\Backend\Admin\CarePlanController;
use App\Http\Controllers\Backend\Admin\CarePlanSectionController;
use App\Http\Controllers\Backend\Admin\CarePlanGoalController;
use App\Http\Controllers\Backend\Admin\CarePlanInterventionController;



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



        // Nested resources under staff-profiles
        Route::resource('staff-profiles.contracts', StaffContractController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.registrations', StaffRegistrationController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.employment-checks', StaffEmploymentCheckController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.visas', StaffVisaController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.training-records', StaffTrainingRecordController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.supervisions', StaffSupervisionAppraisalController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.qualifications', StaffQualificationController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.occ-health', StaffOccHealthClearanceController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);


        Route::resource('staff-profiles.immunisations', StaffImmunisationController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.payroll', StaffPayrollController::class)
            ->parameters(['staff-profiles' => 'staffProfile', 'payroll' => 'payroll'])
            ->except(['show']);

        Route::resource('staff-profiles.bank-accounts', StaffBankAccountController::class)
            ->parameters(['staff-profiles' => 'staffProfile', 'bank-accounts' => 'bank_account'])
            ->except(['show']);

        Route::resource('staff-profiles.leave-records', StaffLeaveRecordController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.leave-entitlements', StaffLeaveEntitlementController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.availability', StaffAvailabilityPreferenceController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.adjustments', StaffAdjustmentController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.driving-licences', StaffDrivingLicenceController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.disciplinary', StaffDisciplinaryRecordController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.documents', StaffDocumentController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);


    // backend.admin.staff-profiles.emergency-contacts.index, etc.)
        Route::resource('staff-profiles.emergency-contacts', StaffEmergencyContactController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.equality', StaffEqualityDataController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.adjustments', StaffAdjustmentController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.driving-licences', StaffDrivingLicenceController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.disciplinary', StaffDisciplinaryRecordController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);

        Route::resource('staff-profiles.documents', StaffDocumentController::class)
            ->parameters(['staff-profiles' => 'staffProfile'])
            ->except(['show']);


        // Risk Assessments (Admin)
        Route::resource('risk-assessments', RiskAssessmentController::class)->names([
            'index'   => 'risk-assessments.index',
            'create'  => 'risk-assessments.create',
            'store'   => 'risk-assessments.store',
            'show'    => 'risk-assessments.show',
            'edit'    => 'risk-assessments.edit',
            'update'  => 'risk-assessments.update',
            'destroy' => 'risk-assessments.destroy',
        ]);


        // Care Plans
        Route::resource('care-plans', CarePlanController::class)->names([
            'index'   => 'care-plans.index',
            'create'  => 'care-plans.create',
            'store'   => 'care-plans.store',
            'show'    => 'care-plans.show',
            'edit'    => 'care-plans.edit',
            'update'  => 'care-plans.update',
            'destroy' => 'care-plans.destroy',
        ]);

        // Nested ops
        Route::post('care-plans/{care_plan}/sections', [CarePlanSectionController::class, 'store'])
            ->name('care-plans.sections.store');
        Route::put('sections/{section}', [CarePlanSectionController::class, 'update'])
            ->name('sections.update');
        Route::delete('sections/{section}', [CarePlanSectionController::class, 'destroy'])
            ->name('sections.destroy');

        Route::post('sections/{section}/goals', [CarePlanGoalController::class, 'store'])
            ->name('sections.goals.store');
        Route::put('goals/{goal}', [CarePlanGoalController::class, 'update'])
            ->name('goals.update');
        Route::delete('goals/{goal}', [CarePlanGoalController::class, 'destroy'])
            ->name('goals.destroy');

        Route::post('goals/{goal}/interventions', [CarePlanInterventionController::class, 'store'])
            ->name('goals.interventions.store');
        Route::put('interventions/{intervention}', [CarePlanInterventionController::class, 'update'])
            ->name('interventions.update');
        Route::delete('interventions/{intervention}', [CarePlanInterventionController::class, 'destroy'])
            ->name('interventions.destroy');

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
