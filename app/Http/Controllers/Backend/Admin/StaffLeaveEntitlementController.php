<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffLeaveEntitlementRequest;
use App\Http\Requests\UpdateStaffLeaveEntitlementRequest;
use App\Models\StaffLeaveEntitlement;
use App\Models\StaffProfile;

class StaffLeaveEntitlementController extends Controller
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

    private function authorizeItem(StaffLeaveEntitlement $leaveEntitlement): void
    {
        $this->authorizeTenantRecord($leaveEntitlement);
    }

    public function index(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $items = $staffProfile->leaveEntitlements()
            ->orderByDesc('period_start')
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

        return view('backend.admin.staff-leave-entitlements.index', compact('staffProfile', 'items'));
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

        return view('backend.admin.staff-leave-entitlements.create', compact('staffProfile'));
    }

    public function store(StoreStaffLeaveEntitlementRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->leaveEntitlements()->create([
            'tenant_id' => $this->tenantIdOrFail(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.leave-entitlements.index', $staffProfile)
            ->with('success', 'Leave entitlement added.');
    }

    public function edit(StaffProfile $staffProfile, StaffLeaveEntitlement $leave_entitlement)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($leave_entitlement);

        abort_unless((int) $leave_entitlement->staff_profile_id === (int) $staffProfile->id, 404);

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

        return view('backend.admin.staff-leave-entitlements.edit', [
            'staffProfile' => $staffProfile,
            'entitlement' => $leave_entitlement,
        ]);
    }

    public function update(UpdateStaffLeaveEntitlementRequest $request, StaffProfile $staffProfile, StaffLeaveEntitlement $leave_entitlement)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($leave_entitlement);

        abort_unless((int) $leave_entitlement->staff_profile_id === (int) $staffProfile->id, 404);

        $leave_entitlement->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.leave-entitlements.index', $staffProfile)
            ->with('success', 'Leave entitlement updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffLeaveEntitlement $leave_entitlement)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($leave_entitlement);

        abort_unless((int) $leave_entitlement->staff_profile_id === (int) $staffProfile->id, 404);

        $leave_entitlement->delete();

        return back()->with('success', 'Leave entitlement deleted.');
    }
}
