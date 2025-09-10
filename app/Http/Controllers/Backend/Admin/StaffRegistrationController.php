<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRegistrationRequest;
use App\Http\Requests\UpdateStaffRegistrationRequest;
use App\Models\StaffProfile;
use App\Models\StaffRegistration;

class StaffRegistrationController extends Controller
{
    private function tenantId(): int
    {
        return (int) auth()->user()->tenant_id;
    }

    private function authorizeProfile(StaffProfile $profile): void
    {
        abort_unless($profile->tenant_id === $this->tenantId(), 404);
    }

    private function authorizeRegistration(StaffRegistration $registration): void
    {
        abort_unless($registration->tenant_id === $this->tenantId(), 404);
    }

    public function index(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $registrations = $staffProfile->registrations()->latest('expires_at')->paginate(15);

        return view('backend.admin.staff-registrations.index', compact('staffProfile','registrations'));
    }

    public function create(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);
        return view('backend.admin.staff-registrations.create', compact('staffProfile'));
    }

    public function store(StoreStaffRegistrationRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->registrations()->create([
            'tenant_id' => $this->tenantId(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.registrations.index', $staffProfile)
            ->with('success', 'Registration added.');
    }

    public function edit(StaffProfile $staffProfile, StaffRegistration $registration)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeRegistration($registration);
        abort_unless($registration->staff_profile_id === $staffProfile->id, 404);

        return view('backend.admin.staff-registrations.edit', compact('staffProfile','registration'));
    }

    public function update(UpdateStaffRegistrationRequest $request, StaffProfile $staffProfile, StaffRegistration $registration)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeRegistration($registration);
        abort_unless($registration->staff_profile_id === $staffProfile->id, 404);

        $registration->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.registrations.index', $staffProfile)
            ->with('success', 'Registration updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffRegistration $registration)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeRegistration($registration);
        abort_unless($registration->staff_profile_id === $staffProfile->id, 404);

        $registration->delete();

        return back()->with('success', 'Registration deleted.');
    }
}
