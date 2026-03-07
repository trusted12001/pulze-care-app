<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffEmploymentCheckRequest;
use App\Http\Requests\UpdateStaffEmploymentCheckRequest;
use App\Models\StaffEmploymentCheck;
use App\Models\StaffProfile;

class StaffEmploymentCheckController extends Controller
{
    use ResolvesTenantContext;

    private function tenantId(): int
    {
        return $this->tenantIdOrFail();
    }

    private function authorizeProfile(StaffProfile $staffProfile): void
    {
        $this->authorizeTenantRecord($staffProfile);
    }

    private function authorizeCheck(StaffEmploymentCheck $employmentCheck): void
    {
        $this->authorizeTenantRecord($employmentCheck);
    }

    public function index(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $checks = $staffProfile->employmentChecks()
            ->orderByRaw('expires_at IS NULL, expires_at ASC')
            ->paginate(15);

        $staffProfile->loadCount([
            'disciplinaryRecords',
            'documents',
            'contracts',
            'registrations',
            'employmentChecks',
            'visas',
            'trainingRecords',
            'supervisionsAppraisals',
            'qualifications',
            'occHealthClearances',
            'immunisations',
            'leaveEntitlements',
            'leaveRecords',
            'availabilityPreferences',
            'emergencyContacts',
            'equalityData',
            'adjustments',
            'drivingLicences',
        ]);

        return view('backend.admin.staff-employment-checks.index', compact('staffProfile', 'checks'));
    }

    public function create(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->loadCount([
            'disciplinaryRecords',
            'documents',
            'contracts',
            'registrations',
            'employmentChecks',
            'visas',
            'trainingRecords',
            'supervisionsAppraisals',
            'qualifications',
            'occHealthClearances',
            'immunisations',
            'leaveEntitlements',
            'leaveRecords',
            'availabilityPreferences',
            'emergencyContacts',
            'equalityData',
            'adjustments',
            'drivingLicences',
        ]);

        $verifiers = \App\Models\User::where('tenant_id', $this->tenantIdOrFail())
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'other_names', 'email']);

        return view('backend.admin.staff-employment-checks.create', compact('staffProfile', 'verifiers'));
    }

    public function store(StoreStaffEmploymentCheckRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->employmentChecks()->create([
            'tenant_id' => $this->tenantIdOrFail(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.employment-checks.index', $staffProfile)
            ->with('success', 'Employment check recorded.');
    }

    public function edit(StaffProfile $staffProfile, StaffEmploymentCheck $employment_check)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeCheck($employment_check);

        abort_unless($employment_check->staff_profile_id === $staffProfile->id, 404);

        $staffProfile->loadCount([
            'disciplinaryRecords',
            'documents',
            'contracts',
            'registrations',
            'employmentChecks',
            'visas',
            'trainingRecords',
            'supervisionsAppraisals',
            'qualifications',
            'occHealthClearances',
            'immunisations',
            'leaveEntitlements',
            'leaveRecords',
            'availabilityPreferences',
            'emergencyContacts',
            'equalityData',
            'adjustments',
            'drivingLicences',
        ]);

        $verifiers = \App\Models\User::where('tenant_id', $this->tenantIdOrFail())
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'other_names', 'email']);

        return view('backend.admin.staff-employment-checks.edit', [
            'staffProfile' => $staffProfile,
            'check' => $employment_check,
            'verifiers' => $verifiers,
        ]);
    }

    public function update(UpdateStaffEmploymentCheckRequest $request, StaffProfile $staffProfile, StaffEmploymentCheck $employment_check)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeCheck($employment_check);

        abort_unless($employment_check->staff_profile_id === $staffProfile->id, 404);

        $employment_check->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.employment-checks.index', $staffProfile)
            ->with('success', 'Employment check updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffEmploymentCheck $employment_check)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeCheck($employment_check);

        abort_unless($employment_check->staff_profile_id === $staffProfile->id, 404);

        $employment_check->delete();

        return back()->with('success', 'Employment check deleted.');
    }
}
