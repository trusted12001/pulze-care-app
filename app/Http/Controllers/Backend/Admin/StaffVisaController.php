<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffVisaRequest;
use App\Http\Requests\UpdateStaffVisaRequest;
use App\Models\StaffProfile;
use App\Models\StaffVisa;

class StaffVisaController extends Controller
{
    private function tenantId(): int
    {
        return (int) auth()->user()->tenant_id;
    }

    private function authorizeProfile(StaffProfile $profile): void
    {
        abort_unless($profile->tenant_id === $this->tenantId(), 404);
    }

    private function authorizeVisa(StaffVisa $visa): void
    {
        abort_unless($visa->tenant_id === $this->tenantId(), 404);
    }

    public function index(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $visas = $staffProfile->visas()
            ->orderByRaw('expires_at IS NULL, expires_at ASC')
            ->paginate(15);

        $staffProfile->loadCount(['contracts','registrations','employmentChecks','visas']);

        return view('backend.admin.staff-visas.index', compact('staffProfile','visas'));
    }

    public function create(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);
        $staffProfile->loadCount(['contracts','registrations','employmentChecks','visas']);

        $verifiers = \App\Models\User::where('tenant_id', $this->tenantId())
            ->orderBy('first_name')
            ->get(['id','first_name','last_name','other_names','email']);

        return view('backend.admin.staff-visas.create', compact('staffProfile','verifiers'));
    }

    public function store(StoreStaffVisaRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->visas()->create([
            'tenant_id' => $this->tenantId(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.visas.index', $staffProfile)
            ->with('success', 'Visa/Right-to-Work record added.');
    }

    public function edit(StaffProfile $staffProfile, StaffVisa $visa)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeVisa($visa);
        abort_unless($visa->staff_profile_id === $staffProfile->id, 404);

        $staffProfile->loadCount(['contracts','registrations','employmentChecks','visas']);

        $verifiers = \App\Models\User::where('tenant_id', $this->tenantId())
            ->orderBy('first_name')
            ->get(['id','first_name','last_name','other_names','email']);

        return view('backend.admin.staff-visas.edit', compact('staffProfile','visa','verifiers'));
    }

    public function update(UpdateStaffVisaRequest $request, StaffProfile $staffProfile, StaffVisa $visa)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeVisa($visa);
        abort_unless($visa->staff_profile_id === $staffProfile->id, 404);

        $visa->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.visas.index', $staffProfile)
            ->with('success', 'Visa record updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffVisa $visa)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeVisa($visa);
        abort_unless($visa->staff_profile_id === $staffProfile->id, 404);

        $visa->delete();

        return back()->with('success', 'Visa record deleted.');
    }
}
