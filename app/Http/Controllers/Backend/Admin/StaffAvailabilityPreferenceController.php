<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffAvailabilityPreferenceRequest;
use App\Http\Requests\UpdateStaffAvailabilityPreferenceRequest;
use App\Models\StaffAvailabilityPreference;
use App\Models\StaffProfile;
use Illuminate\Http\Request;

class StaffAvailabilityPreferenceController extends Controller
{
    use ResolvesTenantContext;

    private array $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    private function tenantId(): int
    {
        return $this->tenantIdOrFail();
    }

    private function authorizeProfile(StaffProfile $staffProfile): void
    {
        $this->authorizeTenantRecord($staffProfile);
    }

    private function authorizeItem(StaffAvailabilityPreference $availability): void
    {
        $this->authorizeTenantRecord($availability);
    }

    public function index(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $items = $staffProfile->availabilityPreferences()
            ->orderBy('day_of_week')
            ->get();

        $byDay = collect($items)->keyBy('day_of_week');

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

        return view('backend.admin.staff-availability.index', [
            'staffProfile' => $staffProfile,
            'items' => $items,
            'days' => $this->days,
            'byDay' => $byDay,
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

        return view('backend.admin.staff-availability.create', [
            'staffProfile' => $staffProfile,
            'days' => $this->days,
        ]);
    }

    public function store(StoreStaffAvailabilityPreferenceRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->availabilityPreferences()->updateOrCreate(
            [
                'tenant_id' => $this->tenantIdOrFail(),
                'day_of_week' => (int) $request->day_of_week,
            ],
            [
                'available_from' => $request->available_from,
                'available_to' => $request->available_to,
                'preference' => $request->preference,
            ]
        );

        return redirect()
            ->route('backend.admin.staff-profiles.availability.index', $staffProfile)
            ->with('success', 'Availability saved.');
    }

    public function edit(StaffProfile $staffProfile, StaffAvailabilityPreference $availability)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($availability);

        abort_unless($availability->staff_profile_id === $staffProfile->id, 404);

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

        return view('backend.admin.staff-availability.edit', [
            'staffProfile' => $staffProfile,
            'availability' => $availability,
            'days' => $this->days,
        ]);
    }

    public function update(UpdateStaffAvailabilityPreferenceRequest $request, StaffProfile $staffProfile, StaffAvailabilityPreference $availability)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($availability);

        abort_unless($availability->staff_profile_id === $staffProfile->id, 404);

        $availability->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.availability.index', $staffProfile)
            ->with('success', 'Availability updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffAvailabilityPreference $availability)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($availability);

        abort_unless($availability->staff_profile_id === $staffProfile->id, 404);

        $availability->delete();

        return back()->with('success', 'Availability removed.');
    }
}
