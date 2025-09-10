<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffContractRequest;
use App\Http\Requests\UpdateStaffContractRequest;
use App\Models\StaffContract;
use App\Models\StaffProfile;
use Illuminate\Http\Request;

class StaffContractController extends Controller
{
    private function tenantId(): int
    {
        return (int) auth()->user()->tenant_id;
    }

    private function authorizeProfile(StaffProfile $profile): void
    {
        abort_unless($profile->tenant_id === $this->tenantId(), 404);
    }

    private function authorizeContract(StaffContract $contract): void
    {
        abort_unless($contract->tenant_id === $this->tenantId(), 404);
    }

    public function index(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $contracts = $staffProfile->contracts()->latest('start_date')->paginate(15);

        return view('backend.admin.staff-contracts.index', compact('staffProfile','contracts'));
    }

    public function create(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);
        return view('backend.admin.staff-contracts.create', compact('staffProfile'));
    }

    public function store(StoreStaffContractRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $staffProfile->contracts()->create([
            'tenant_id' => $this->tenantId(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.contracts.index', $staffProfile)
            ->with('success', 'Contract created.');
    }

    public function edit(StaffProfile $staffProfile, StaffContract $contract)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeContract($contract);
        abort_unless($contract->staff_profile_id === $staffProfile->id, 404);

        return view('backend.admin.staff-contracts.edit', compact('staffProfile','contract'));
    }

    public function update(UpdateStaffContractRequest $request, StaffProfile $staffProfile, StaffContract $contract)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeContract($contract);
        abort_unless($contract->staff_profile_id === $staffProfile->id, 404);

        $contract->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.contracts.index', $staffProfile)
            ->with('success', 'Contract updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffContract $contract)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeContract($contract);
        abort_unless($contract->staff_profile_id === $staffProfile->id, 404);

        $contract->delete();

        return back()->with('success', 'Contract deleted.');
    }
}
