<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffDrivingLicenceRequest;
use App\Http\Requests\UpdateStaffDrivingLicenceRequest;
use App\Models\StaffDrivingLicence;
use App\Models\StaffProfile;
use Illuminate\Http\Request;

class StaffDrivingLicenceController extends Controller
{
    private function tenantId(): int { return (int)auth()->user()->tenant_id; }
    private function authorizeProfile(StaffProfile $p): void { abort_unless($p->tenant_id === $this->tenantId(), 404); }
    private function authorizeItem(StaffDrivingLicence $i): void { abort_unless($i->tenant_id === $this->tenantId(), 404); }

    public function index(Request $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $items = $staffProfile->drivingLicences()
            ->orderByRaw('expires_at IS NULL, expires_at ASC')
            ->paginate(15)
            ->withQueryString();

        $staffProfile->loadCount([
            'disciplinaryRecords','documents',
            'contracts','registrations','employmentChecks','visas',
            'trainingRecords','supervisionsAppraisals','qualifications',
            'occHealthClearances','immunisations',
            'leaveEntitlements','leaveRecords','availabilityPreferences',
            'emergencyContacts','equalityData','adjustments','drivingLicences',
        ]);

        return view('backend.admin.staff-driving-licences.index', compact('staffProfile','items'));
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

        return view('backend.admin.staff-driving-licences.create', compact('staffProfile'));
    }

    public function store(StoreStaffDrivingLicenceRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->drivingLicences()->create([
            'tenant_id' => $this->tenantId(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.driving-licences.index', $staffProfile)
            ->with('success', 'Driving licence saved.');
    }

    public function edit(StaffProfile $staffProfile, StaffDrivingLicence $driving_licence)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($driving_licence);
        abort_unless($driving_licence->staff_profile_id === $staffProfile->id, 404);

        return view('backend.admin.staff-driving-licences.edit', compact('staffProfile','driving_licence'));
    }

    public function update(UpdateStaffDrivingLicenceRequest $request, StaffProfile $staffProfile, StaffDrivingLicence $driving_licence)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($driving_licence);
        abort_unless($driving_licence->staff_profile_id === $staffProfile->id, 404);

        $driving_licence->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.driving-licences.index', $staffProfile)
            ->with('success', 'Driving licence updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffDrivingLicence $driving_licence)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($driving_licence);
        abort_unless($driving_licence->staff_profile_id === $staffProfile->id, 404);

        $driving_licence->delete();

        return back()->with('success', 'Driving licence deleted.');
    }
}
