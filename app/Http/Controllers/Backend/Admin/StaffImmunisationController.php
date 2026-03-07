<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffImmunisationRequest;
use App\Http\Requests\UpdateStaffImmunisationRequest;
use App\Models\StaffImmunisation;
use App\Models\StaffProfile;
use Illuminate\Http\Request;

class StaffImmunisationController extends Controller
{
    use ResolvesTenantContext;

    private array $vaccines = ['HepB', 'MMR', 'Varicella', 'TB_BCG', 'Flu', 'Covid19', 'Tetanus', 'Pertussis', 'Other'];

    private function tenantId(): int
    {
        return $this->tenantIdOrFail();
    }

    private function authorizeProfile(StaffProfile $staffProfile): void
    {
        $this->authorizeTenantRecord($staffProfile);
    }

    private function authorizeItem(StaffImmunisation $immunisation): void
    {
        $this->authorizeTenantRecord($immunisation);
    }

    public function index(Request $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $filterV = $request->string('v')->toString();
        $q = $request->string('q')->toString();

        $items = $staffProfile->immunisations()
            ->when($filterV !== '', fn($query) => $query->where('vaccine', $filterV))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('dose', 'like', "%{$q}%")
                        ->orWhere('notes', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('administered_at')
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

        return view('backend.admin.staff-immunisations.index', [
            'staffProfile' => $staffProfile,
            'items' => $items,
            'vaccines' => $this->vaccines,
            'filterV' => $filterV,
            'q' => $q,
        ]);
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

        return view('backend.admin.staff-immunisations.create', [
            'staffProfile' => $staffProfile,
            'vaccines' => $this->vaccines,
        ]);
    }

    public function store(StoreStaffImmunisationRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->immunisations()->create([
            'tenant_id' => $this->tenantIdOrFail(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.immunisations.index', $staffProfile)
            ->with('success', 'Immunisation record added.');
    }

    public function edit(StaffProfile $staffProfile, StaffImmunisation $immunisation)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($immunisation);

        abort_unless($immunisation->staff_profile_id === $staffProfile->id, 404);

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

        return view('backend.admin.staff-immunisations.edit', [
            'staffProfile' => $staffProfile,
            'immunisation' => $immunisation,
            'vaccines' => $this->vaccines,
        ]);
    }

    public function update(UpdateStaffImmunisationRequest $request, StaffProfile $staffProfile, StaffImmunisation $immunisation)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($immunisation);

        abort_unless($immunisation->staff_profile_id === $staffProfile->id, 404);

        $immunisation->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.immunisations.index', $staffProfile)
            ->with('success', 'Immunisation record updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffImmunisation $immunisation)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($immunisation);

        abort_unless($immunisation->staff_profile_id === $staffProfile->id, 404);

        $immunisation->delete();

        return back()->with('success', 'Immunisation record deleted.');
    }
}
