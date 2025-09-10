<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffEqualityDataRequest;
use App\Http\Requests\UpdateStaffEqualityDataRequest;
use App\Models\StaffEqualityData;
use App\Models\StaffProfile;

class StaffEqualityDataController extends Controller
{
    private function tenantId(): int { return (int)auth()->user()->tenant_id; }
    private function authorizeProfile(StaffProfile $p): void { abort_unless($p->tenant_id === $this->tenantId(), 404); }
    private function authorizeItem(StaffEqualityData $i): void { abort_unless($i->tenant_id === $this->tenantId(), 404); }

    public function index(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $equality = $staffProfile->equalityData()->first();
        $staffProfile->loadCount([
            'disciplinaryRecords','documents',
            'contracts','registrations','employmentChecks','visas',
            'trainingRecords','supervisionsAppraisals','qualifications',
            'occHealthClearances','immunisations',
            'leaveEntitlements','leaveRecords','availabilityPreferences',
            'emergencyContacts','equalityData','adjustments','drivingLicences',
        ]);

        return view('backend.admin.staff-equality.index', compact('staffProfile','equality'));
    }

    public function create(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);
        abort_if($staffProfile->equalityData()->exists(), 404); // one record only
        return view('backend.admin.staff-equality.create', compact('staffProfile'));
    }

    public function store(StoreStaffEqualityDataRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        abort_if($staffProfile->equalityData()->exists(), 404);

        $staffProfile->equalityData()->create([
            'tenant_id' => $this->tenantId(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.equality.index', $staffProfile)
            ->with('success', 'Equality data saved.');
    }

    public function edit(StaffProfile $staffProfile, StaffEqualityData $equality)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($equality);
        abort_unless($equality->staff_profile_id === $staffProfile->id, 404);

        return view('backend.admin.staff-equality.edit', compact('staffProfile','equality'));
    }

    public function update(UpdateStaffEqualityDataRequest $request, StaffProfile $staffProfile, StaffEqualityData $equality)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($equality);
        abort_unless($equality->staff_profile_id === $staffProfile->id, 404);

        $equality->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.equality.index', $staffProfile)
            ->with('success', 'Equality data updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffEqualityData $equality)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($equality);
        abort_unless($equality->staff_profile_id === $staffProfile->id, 404);

        $equality->delete();

        return back()->with('success', 'Equality data removed.');
    }
}
