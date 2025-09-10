<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffTrainingRecordRequest;
use App\Http\Requests\UpdateStaffTrainingRecordRequest;
use App\Models\StaffProfile;
use App\Models\StaffTrainingRecord;
use Illuminate\Http\Request;

class StaffTrainingRecordController extends Controller
{
    private function tenantId(): int { return (int) auth()->user()->tenant_id; }
    private function authorizeProfile(StaffProfile $p): void { abort_unless($p->tenant_id === $this->tenantId(), 404); }
    private function authorizeRecord(StaffTrainingRecord $r): void { abort_unless($r->tenant_id === $this->tenantId(), 404); }

    public function index(Request $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $q = trim((string)$request->get('q', ''));
        $records = $staffProfile->trainingRecords()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('module_title','like',"%{$q}%")
                      ->orWhere('module_code','like',"%{$q}%")
                      ->orWhere('provider','like',"%{$q}%");
            })
            ->orderByRaw('valid_until IS NULL, valid_until ASC')
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


        return view('backend.admin.staff-training-records.index', compact('staffProfile','records','q'));
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

        return view('backend.admin.staff-training-records.create', compact('staffProfile'));
    }

    public function store(StoreStaffTrainingRecordRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->trainingRecords()->create([
            'tenant_id' => $this->tenantId(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.training-records.index', $staffProfile)
            ->with('success','Training record added.');
    }

    public function edit(StaffProfile $staffProfile, StaffTrainingRecord $training_record)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeRecord($training_record);
        abort_unless($training_record->staff_profile_id === $staffProfile->id, 404);

        $staffProfile->loadCount([
            'disciplinaryRecords','documents',
            'contracts','registrations','employmentChecks','visas',
            'trainingRecords','supervisionsAppraisals','qualifications',
            'occHealthClearances','immunisations',
            'leaveEntitlements','leaveRecords','availabilityPreferences',
            'emergencyContacts','equalityData','adjustments','drivingLicences',
        ]);


        return view('backend.admin.staff-training-records.edit', [
            'staffProfile' => $staffProfile,
            'record' => $training_record,
        ]);
    }

    public function update(UpdateStaffTrainingRecordRequest $request, StaffProfile $staffProfile, StaffTrainingRecord $training_record)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeRecord($training_record);
        abort_unless($training_record->staff_profile_id === $staffProfile->id, 404);

        $training_record->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.training-records.index', $staffProfile)
            ->with('success','Training record updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffTrainingRecord $training_record)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeRecord($training_record);
        abort_unless($training_record->staff_profile_id === $staffProfile->id, 404);

        $training_record->delete();

        return back()->with('success','Training record deleted.');
    }
}
