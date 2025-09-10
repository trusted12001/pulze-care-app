<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffEmergencyContactRequest;
use App\Http\Requests\UpdateStaffEmergencyContactRequest;
use App\Models\StaffEmergencyContact;
use App\Models\StaffProfile;
use Illuminate\Http\Request;

class StaffEmergencyContactController extends Controller
{
    private function tenantId(): int { return (int)auth()->user()->tenant_id; }
    private function authorizeProfile(StaffProfile $p): void { abort_unless($p->tenant_id === $this->tenantId(), 404); }
    private function authorizeItem(StaffEmergencyContact $i): void { abort_unless($i->tenant_id === $this->tenantId(), 404); }

    public function index(Request $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $q = trim((string)$request->get('q',''));
        $contacts = $staffProfile->emergencyContacts()
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where('name','like',"%{$q}%")
                   ->orWhere('relationship','like',"%{$q}%")
                   ->orWhere('phone','like',"%{$q}%");
            })
            ->orderBy('name')
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

        return view('backend.admin.staff-emergency-contacts.index', compact('staffProfile','contacts','q'));
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

        return view('backend.admin.staff-emergency-contacts.create', compact('staffProfile'));
    }

    public function store(StoreStaffEmergencyContactRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->emergencyContacts()->create([
            'tenant_id' => $this->tenantId(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.emergency-contacts.index', $staffProfile)
            ->with('success', 'Emergency contact added.');
    }

    public function edit(StaffProfile $staffProfile, StaffEmergencyContact $emergency_contact)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($emergency_contact);
        abort_unless($emergency_contact->staff_profile_id === $staffProfile->id, 404);

        return view('backend.admin.staff-emergency-contacts.edit', [
            'staffProfile' => $staffProfile,
            'contact' => $emergency_contact,
        ]);
    }

    public function update(UpdateStaffEmergencyContactRequest $request, StaffProfile $staffProfile, StaffEmergencyContact $emergency_contact)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($emergency_contact);
        abort_unless($emergency_contact->staff_profile_id === $staffProfile->id, 404);

        $emergency_contact->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.emergency-contacts.index', $staffProfile)
            ->with('success', 'Emergency contact updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffEmergencyContact $emergency_contact)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($emergency_contact);
        abort_unless($emergency_contact->staff_profile_id === $staffProfile->id, 404);

        $emergency_contact->delete();

        return back()->with('success', 'Emergency contact deleted.');
    }
}
