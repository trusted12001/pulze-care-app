<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffLeaveRecordRequest;
use App\Http\Requests\UpdateStaffLeaveRecordRequest;
use App\Models\StaffLeaveRecord;
use App\Models\StaffProfile;
use Illuminate\Http\Request;

class StaffLeaveRecordController extends Controller
{
    private function tenantId(): int { return (int)auth()->user()->tenant_id; }
    private function authorizeProfile(StaffProfile $p): void { abort_unless($p->tenant_id === $this->tenantId(), 404); }
    private function authorizeItem(StaffLeaveRecord $i): void { abort_unless($i->tenant_id === $this->tenantId(), 404); }

    public function index(Request $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $type = $request->string('type')->toString();
        $q = trim((string)$request->get('q',''));

        $records = $staffProfile->leaveRecords()
            ->when($type !== '', fn($qq) => $qq->where('type', $type))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($sub) use ($q) {
                    $sub->where('reason','like',"%{$q}%");
                });
            })
            ->orderByDesc('start_date')
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

        return view('backend.admin.staff-leave-records.index', [
            'staffProfile' => $staffProfile,
            'records' => $records,
            'type' => $type,
            'q' => $q,
        ]);
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

        return view('backend.admin.staff-leave-records.create', compact('staffProfile'));
    }

    public function store(StoreStaffLeaveRecordRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->leaveRecords()->create([
            'tenant_id' => $this->tenantId(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.leave-records.index', $staffProfile)
            ->with('success', 'Leave recorded.');
    }

    public function edit(StaffProfile $staffProfile, StaffLeaveRecord $leave_record)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($leave_record);
        abort_unless($leave_record->staff_profile_id === $staffProfile->id, 404);

        $staffProfile->loadCount([
            'disciplinaryRecords','documents',
            'contracts','registrations','employmentChecks','visas',
            'trainingRecords','supervisionsAppraisals','qualifications',
            'occHealthClearances','immunisations',
            'leaveEntitlements','leaveRecords','availabilityPreferences',
            'emergencyContacts','equalityData','adjustments','drivingLicences',
        ]);

        return view('backend.admin.staff-leave-records.edit', [
            'staffProfile' => $staffProfile,
            'record' => $leave_record,
        ]);
    }

    public function update(UpdateStaffLeaveRecordRequest $request, StaffProfile $staffProfile, StaffLeaveRecord $leave_record)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($leave_record);
        abort_unless($leave_record->staff_profile_id === $staffProfile->id, 404);

        $leave_record->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.leave-records.index', $staffProfile)
            ->with('success', 'Leave updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffLeaveRecord $leave_record)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($leave_record);
        abort_unless($leave_record->staff_profile_id === $staffProfile->id, 404);

        $leave_record->delete();

        return back()->with('success', 'Leave deleted.');
    }
}
