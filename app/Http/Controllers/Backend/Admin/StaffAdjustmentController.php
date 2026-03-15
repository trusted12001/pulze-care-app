<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffAdjustmentRequest;
use App\Http\Requests\UpdateStaffAdjustmentRequest;
use App\Models\StaffAdjustment;
use App\Models\StaffProfile;
use Illuminate\Http\Request;

class StaffAdjustmentController extends Controller
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

    private function authorizeItem(StaffAdjustment $adjustment): void
    {
        $this->authorizeTenantRecord($adjustment);
    }

    public function index(Request $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $q = trim((string) $request->get('q', ''));

        $items = $staffProfile->adjustments()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('type', 'like', "%{$q}%")
                        ->orWhere('notes', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('in_place_from')
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

        return view('backend.admin.staff-adjustments.index', compact('staffProfile', 'items', 'q'));
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

        return view('backend.admin.staff-adjustments.create', compact('staffProfile'));
    }

    public function store(StoreStaffAdjustmentRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->adjustments()->create([
            'tenant_id' => $this->tenantIdOrFail(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.adjustments.index', $staffProfile)
            ->with('success', 'Adjustment added.');
    }

    public function edit(StaffProfile $staffProfile, StaffAdjustment $adjustment)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($adjustment);

        abort_unless((int) $adjustment->staff_profile_id === (int) $staffProfile->id, 404);

        return view('backend.admin.staff-adjustments.edit', compact('staffProfile', 'adjustment'));
    }

    public function update(UpdateStaffAdjustmentRequest $request, StaffProfile $staffProfile, StaffAdjustment $adjustment)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($adjustment);

        abort_unless((int) $adjustment->staff_profile_id === (int) $staffProfile->id, 404);

        $adjustment->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.adjustments.index', $staffProfile)
            ->with('success', 'Adjustment updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffAdjustment $adjustment)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($adjustment);

        abort_unless((int) $adjustment->staff_profile_id === (int) $staffProfile->id, 404);

        $adjustment->delete();

        return back()->with('success', 'Adjustment deleted.');
    }
}
