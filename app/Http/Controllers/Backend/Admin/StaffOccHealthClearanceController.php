<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffOccHealthClearanceRequest;
use App\Http\Requests\UpdateStaffOccHealthClearanceRequest;
use App\Models\StaffOccHealthClearance;
use App\Models\StaffProfile;
use Illuminate\Http\Request;

class StaffOccHealthClearanceController extends Controller
{
    private function tenantId(): int { return (int) auth()->user()->tenant_id; }
    private function authorizeProfile(StaffProfile $p): void { abort_unless($p->tenant_id === $this->tenantId(), 404); }
    private function authorizeItem(StaffOccHealthClearance $i): void { abort_unless($i->tenant_id === $this->tenantId(), 404); }

    public function index(Request $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $items = $staffProfile->occHealthClearances()
            ->orderByRaw('review_due_at IS NULL, review_due_at ASC')
            ->orderByDesc('assessed_at')
            ->paginate(15);

        $staffProfile->loadCount([
            'disciplinaryRecords','documents',
            'contracts','registrations','employmentChecks','visas',
            'trainingRecords','supervisionsAppraisals','qualifications',
            'occHealthClearances','immunisations',
            'leaveEntitlements','leaveRecords','availabilityPreferences',
            'emergencyContacts','equalityData','adjustments','drivingLicences',
        ]);

        return view('backend.admin.staff-occ-health.index', compact('staffProfile','items'));
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

        return view('backend.admin.staff-occ-health.create', compact('staffProfile'));
    }

    public function store(StoreStaffOccHealthClearanceRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->occHealthClearances()->create([
            'tenant_id' => $this->tenantId(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.occ-health.index', $staffProfile)
            ->with('success', 'Occupational health clearance added.');
    }

    public function edit(StaffProfile $staffProfile, StaffOccHealthClearance $occ_health)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($occ_health);
        abort_unless($occ_health->staff_profile_id === $staffProfile->id, 404);

        $staffProfile->loadCount([
            'disciplinaryRecords','documents',
            'contracts','registrations','employmentChecks','visas',
            'trainingRecords','supervisionsAppraisals','qualifications',
            'occHealthClearances','immunisations',
            'leaveEntitlements','leaveRecords','availabilityPreferences',
            'emergencyContacts','equalityData','adjustments','drivingLicences',
        ]);

        return view('backend.admin.staff-occ-health.edit', compact('staffProfile','occ_health'));
    }

    public function update(UpdateStaffOccHealthClearanceRequest $request, StaffProfile $staffProfile, StaffOccHealthClearance $occ_health)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($occ_health);
        abort_unless($occ_health->staff_profile_id === $staffProfile->id, 404);

        $occ_health->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.occ-health.index', $staffProfile)
            ->with('success', 'Occupational health clearance updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffOccHealthClearance $occ_health)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($occ_health);
        abort_unless($occ_health->staff_profile_id === $staffProfile->id, 404);

        $occ_health->delete();

        return back()->with('success', 'Record deleted.');
    }
}
