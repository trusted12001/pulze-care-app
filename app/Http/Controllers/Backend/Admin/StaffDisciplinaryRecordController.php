<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffDisciplinaryRecordRequest;
use App\Http\Requests\UpdateStaffDisciplinaryRecordRequest;
use App\Models\StaffDisciplinaryRecord;
use App\Models\StaffProfile;
use Illuminate\Http\Request;

class StaffDisciplinaryRecordController extends Controller
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

    private function authorizeItem(StaffDisciplinaryRecord $disciplinary): void
    {
        $this->authorizeTenantRecord($disciplinary);
    }

    public function index(Request $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $type = $request->string('type')->toString();
        $q = trim((string) $request->get('q', ''));

        $items = $staffProfile->disciplinaryRecords()
            ->when($type !== '', fn($query) => $query->where('type', $type))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('summary', 'like', "%{$q}%")
                        ->orWhere('outcome', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('opened_at')
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

        return view('backend.admin.staff-disciplinary-records.index', [
            'staffProfile' => $staffProfile,
            'items' => $items,
            'type' => $type,
            'q' => $q,
        ]);
    }

    public function create(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        return view('backend.admin.staff-disciplinary-records.create', compact('staffProfile'));
    }

    public function store(StoreStaffDisciplinaryRecordRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->disciplinaryRecords()->create([
            'tenant_id' => $this->tenantIdOrFail(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.disciplinary.index', $staffProfile)
            ->with('success', 'Disciplinary record added.');
    }

    public function edit(StaffProfile $staffProfile, StaffDisciplinaryRecord $disciplinary)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($disciplinary);

        abort_unless((int) $disciplinary->staff_profile_id === (int) $staffProfile->id, 404);

        return view('backend.admin.staff-disciplinary-records.edit', [
            'staffProfile' => $staffProfile,
            'record' => $disciplinary,
        ]);
    }

    public function update(UpdateStaffDisciplinaryRecordRequest $request, StaffProfile $staffProfile, StaffDisciplinaryRecord $disciplinary)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($disciplinary);

        abort_unless((int) $disciplinary->staff_profile_id === (int) $staffProfile->id, 404);

        $disciplinary->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.disciplinary.index', $staffProfile)
            ->with('success', 'Disciplinary record updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffDisciplinaryRecord $disciplinary)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($disciplinary);

        abort_unless((int) $disciplinary->staff_profile_id === (int) $staffProfile->id, 404);

        $disciplinary->delete();

        return back()->with('success', 'Disciplinary record deleted.');
    }
}
