<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffVisaRequest;
use App\Http\Requests\UpdateStaffVisaRequest;
use App\Models\StaffProfile;
use App\Models\StaffVisa;

class StaffVisaController extends Controller
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

    private function authorizeVisa(StaffVisa $visa): void
    {
        $this->authorizeTenantRecord($visa);
    }

    public function index(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $visas = $staffProfile->visas()
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

        return view('backend.admin.staff-visas.index', compact('staffProfile', 'visas'));
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

        return view('backend.admin.staff-visas.create', compact('staffProfile', 'verifiers'));
    }

    public function store(StoreStaffVisaRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->visas()->create([
            'tenant_id' => $this->tenantIdOrFail(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.visas.index', $staffProfile)
            ->with('success', 'Visa/Right-to-Work record added.');
    }

    public function edit(StaffProfile $staffProfile, StaffVisa $visa)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeVisa($visa);

        abort_unless((int) $visa->staff_profile_id === (int) $staffProfile->id, 404);

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

        return view('backend.admin.staff-visas.edit', compact('staffProfile', 'visa', 'verifiers'));
    }

    public function update(UpdateStaffVisaRequest $request, StaffProfile $staffProfile, StaffVisa $visa)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeVisa($visa);

        abort_unless((int) $visa->staff_profile_id === (int) $staffProfile->id, 404);

        $visa->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.visas.index', $staffProfile)
            ->with('success', 'Visa record updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffVisa $visa)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeVisa($visa);

        abort_unless((int) $visa->staff_profile_id === (int) $staffProfile->id, 404);

        $visa->delete();

        return back()->with('success', 'Visa record deleted.');
    }
}
