<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffSupervisionAppraisalRequest;
use App\Http\Requests\UpdateStaffSupervisionAppraisalRequest;
use App\Models\StaffProfile;
use App\Models\StaffSupervisionAppraisal;

class StaffSupervisionAppraisalController extends Controller
{
    private function tenantId(): int { return (int) auth()->user()->tenant_id; }
    private function authorizeProfile(StaffProfile $p): void { abort_unless($p->tenant_id === $this->tenantId(), 404); }
    private function authorizeItem(StaffSupervisionAppraisal $i): void { abort_unless($i->tenant_id === $this->tenantId(), 404); }

    public function index(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $items = $staffProfile->supervisionsAppraisals()
            ->orderByDesc('held_at')
            ->paginate(15);

        $staffProfile->loadCount([
            'disciplinaryRecords','documents',
            'contracts','registrations','employmentChecks','visas',
            'trainingRecords','supervisionsAppraisals','qualifications',
            'occHealthClearances','immunisations',
            'leaveEntitlements','leaveRecords','availabilityPreferences',
            'emergencyContacts','equalityData','adjustments','drivingLicences',
        ]);

        $managers = \App\Models\User::where('tenant_id', $this->tenantId())
            ->orderBy('first_name')
            ->get(['id','first_name','last_name','other_names','email']);

        return view('backend.admin.staff-supervisions.index', compact('staffProfile','items','managers'));
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


        $managers = \App\Models\User::where('tenant_id', $this->tenantId())
            ->orderBy('first_name')
            ->get(['id','first_name','last_name','other_names','email']);

        return view('backend.admin.staff-supervisions.create', compact('staffProfile','managers'));
    }

    public function store(StoreStaffSupervisionAppraisalRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->supervisionsAppraisals()->create([
            'tenant_id' => $this->tenantId(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.supervisions.index', $staffProfile)
            ->with('success','Record saved.');
    }

    public function edit(StaffProfile $staffProfile, StaffSupervisionAppraisal $supervision)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($supervision);
        abort_unless($supervision->staff_profile_id === $staffProfile->id, 404);

        $staffProfile->loadCount([
            'disciplinaryRecords','documents',
            'contracts','registrations','employmentChecks','visas',
            'trainingRecords','supervisionsAppraisals','qualifications',
            'occHealthClearances','immunisations',
            'leaveEntitlements','leaveRecords','availabilityPreferences',
            'emergencyContacts','equalityData','adjustments','drivingLicences',
        ]);


        $managers = \App\Models\User::where('tenant_id', $this->tenantId())
            ->orderBy('first_name')
            ->get(['id','first_name','last_name','other_names','email']);

        return view('backend.admin.staff-supervisions.edit', compact('staffProfile','supervision','managers'));
    }

    public function update(UpdateStaffSupervisionAppraisalRequest $request, StaffProfile $staffProfile, StaffSupervisionAppraisal $supervision)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($supervision);
        abort_unless($supervision->staff_profile_id === $staffProfile->id, 404);

        $supervision->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.supervisions.index', $staffProfile)
            ->with('success','Record updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffSupervisionAppraisal $supervision)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($supervision);
        abort_unless($supervision->staff_profile_id === $staffProfile->id, 404);

        $supervision->delete();

        return back()->with('success','Record deleted.');
    }
}
