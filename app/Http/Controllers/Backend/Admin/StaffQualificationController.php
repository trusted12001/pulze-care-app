<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffQualificationRequest;
use App\Http\Requests\UpdateStaffQualificationRequest;
use App\Models\StaffProfile;
use App\Models\StaffQualification;
use Illuminate\Http\Request;

class StaffQualificationController extends Controller
{
    private function tenantId(): int { return (int) auth()->user()->tenant_id; }
    private function authorizeProfile(StaffProfile $p): void { abort_unless($p->tenant_id === $this->tenantId(), 404); }
    private function authorizeQualification(StaffQualification $q): void { abort_unless($q->tenant_id === $this->tenantId(), 404); }

    public function index(Request $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $q = trim((string) $request->get('q', ''));
        $quals = $staffProfile->qualifications()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('level', 'like', "%{$q}%")
                        ->orWhere('institution', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('awarded_at')
            ->orderBy('title')
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

        return view('backend.admin.staff-qualifications.index', compact('staffProfile','quals','q'));
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

        return view('backend.admin.staff-qualifications.create', compact('staffProfile'));
    }

    public function store(StoreStaffQualificationRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->qualifications()->create([
            'tenant_id' => $this->tenantId(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.qualifications.index', $staffProfile)
            ->with('success', 'Qualification added.');
    }

    public function edit(StaffProfile $staffProfile, StaffQualification $qualification)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeQualification($qualification);
        abort_unless($qualification->staff_profile_id === $staffProfile->id, 404);

        $staffProfile->loadCount([
            'disciplinaryRecords','documents',
            'contracts','registrations','employmentChecks','visas',
            'trainingRecords','supervisionsAppraisals','qualifications',
            'occHealthClearances','immunisations',
            'leaveEntitlements','leaveRecords','availabilityPreferences',
            'emergencyContacts','equalityData','adjustments','drivingLicences',
        ]);

        return view('backend.admin.staff-qualifications.edit', compact('staffProfile','qualification'));
    }

    public function update(UpdateStaffQualificationRequest $request, StaffProfile $staffProfile, StaffQualification $qualification)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeQualification($qualification);
        abort_unless($qualification->staff_profile_id === $staffProfile->id, 404);

        $qualification->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.qualifications.index', $staffProfile)
            ->with('success', 'Qualification updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffQualification $qualification)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeQualification($qualification);
        abort_unless($qualification->staff_profile_id === $staffProfile->id, 404);

        $qualification->delete();

        return back()->with('success', 'Qualification deleted.');
    }
}
