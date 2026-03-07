<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffTrainingRecordRequest;
use App\Http\Requests\UpdateStaffTrainingRecordRequest;
use App\Models\StaffProfile;
use App\Models\StaffTrainingRecord;
use Illuminate\Http\Request;

class StaffTrainingRecordController extends Controller
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

    private function authorizeRecord(StaffTrainingRecord $trainingRecord): void
    {
        $this->authorizeTenantRecord($trainingRecord);
    }

    public function index(Request $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $q = trim((string) $request->get('q', ''));

        $records = $staffProfile->trainingRecords()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('module_title', 'like', "%{$q}%")
                        ->orWhere('module_code', 'like', "%{$q}%")
                        ->orWhere('provider', 'like', "%{$q}%");
                });
            })
            ->orderByRaw('valid_until IS NULL, valid_until ASC')
            ->paginate(15)
            ->withQueryString();

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

        return view('backend.admin.staff-training-records.index', compact('staffProfile', 'records', 'q'));
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

        return view('backend.admin.staff-training-records.create', compact('staffProfile'));
    }

    public function store(StoreStaffTrainingRecordRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->trainingRecords()->create([
            'tenant_id' => $this->tenantIdOrFail(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.training-records.index', $staffProfile)
            ->with('success', 'Training record added.');
    }

    public function edit(StaffProfile $staffProfile, StaffTrainingRecord $training_record)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeRecord($training_record);

        abort_unless($training_record->staff_profile_id === $staffProfile->id, 404);

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
            ->with('success', 'Training record updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffTrainingRecord $training_record)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeRecord($training_record);

        abort_unless($training_record->staff_profile_id === $staffProfile->id, 404);

        $training_record->delete();

        return back()->with('success', 'Training record deleted.');
    }
}
