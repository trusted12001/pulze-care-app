<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffLeaveEntitlementRequest;
use App\Http\Requests\UpdateStaffLeaveEntitlementRequest;
use App\Models\StaffLeaveEntitlement;
use App\Models\StaffProfile;

class StaffLeaveEntitlementController extends Controller
{
    private function tenantId(): int { return (int)auth()->user()->tenant_id; }
    private function authorizeProfile(StaffProfile $p): void { abort_unless($p->tenant_id === $this->tenantId(), 404); }
    private function authorizeItem(StaffLeaveEntitlement $i): void { abort_unless($i->tenant_id === $this->tenantId(), 404); }

    public function index(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $items = $staffProfile->leaveEntitlements()
            ->orderByDesc('period_start')
            ->paginate(15);

        $staffProfile->loadCount([
            'disciplinaryRecords','documents',
            'contracts','registrations','employmentChecks','visas',
            'trainingRecords','supervisionsAppraisals','qualifications',
            'occHealthClearances','immunisations',
            'leaveEntitlements','leaveRecords','availabilityPreferences',
            'emergencyContacts','equalityData','adjustments','drivingLicences',
        ]);

        return view('backend.admin.staff-leave-entitlements.index', compact('staffProfile','items'));
    }

    public function create(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);
        $staffProfile->loadCount([
            'disciplinaryRecords','documents',
            'contracts','registrations','employmentChecks','visas',
            'trainingRecords','supervisionsAppraisals','qualifications',
            'occHealthClearances','immunisations',
            'leaveEntitlements','leaveRecords','availabilityPreferences',
            'emergencyContacts','equalityData','adjustments','drivingLicences',
        ]);

        return view('backend.admin.staff-leave-entitlements.create', compact('staffProfile'));
    }

    public function store(StoreStaffLeaveEntitlementRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->leaveEntitlements()->create([
            'tenant_id' => $this->tenantId(),
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
        abort_unless($leave_entitlement->staff_profile_id === $staffProfile->id, 404);

        $staffProfile->loadCount([
            'disciplinaryRecords','documents',
            'contracts','registrations','employmentChecks','visas',
            'trainingRecords','supervisionsAppraisals','qualifications',
            'occHealthClearances','immunisations',
            'leaveEntitlements','leaveRecords','availabilityPreferences',
            'emergencyContacts','equalityData','adjustments','drivingLicences',
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
        abort_unless($leave_entitlement->staff_profile_id === $staffProfile->id, 404);

        $leave_entitlement->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.leave-entitlements.index', $staffProfile)
            ->with('success', 'Leave entitlement updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffLeaveEntitlement $leave_entitlement)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($leave_entitlement);
        abort_unless($leave_entitlement->staff_profile_id === $staffProfile->id, 404);

        $leave_entitlement->delete();

        return back()->with('success', 'Leave entitlement deleted.');
    }
}
