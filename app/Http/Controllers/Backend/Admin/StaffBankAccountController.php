<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffBankAccountRequest;
use App\Http\Requests\UpdateStaffBankAccountRequest;
use App\Models\StaffBankAccount;
use App\Models\StaffProfile;
use Illuminate\Http\Request;

class StaffBankAccountController extends Controller
{
    use ResolvesTenantContext;

    private function tenantId(): int
    {
        return $this->tenantIdOrFail();
    }

    private function guardProfile(StaffProfile $staffProfile): void
    {
        $this->authorizeTenantRecord($staffProfile);
    }

    private function guardItem(StaffBankAccount $bankAccount): void
    {
        $this->authorizeTenantRecord($bankAccount);
    }

    public function index(StaffProfile $staffProfile)
    {
        $this->guardProfile($staffProfile);

        $bankAccounts = $staffProfile->bankAccounts()->latest('id')->paginate(15);

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

        return view('backend.admin.staff-bank-accounts.index', compact('staffProfile', 'bankAccounts'));
    }

    public function create(StaffProfile $staffProfile)
    {
        $this->guardProfile($staffProfile);

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

        return view('backend.admin.staff-bank-accounts.create', compact('staffProfile'));
    }

    public function store(StoreStaffBankAccountRequest $request, StaffProfile $staffProfile)
    {
        $this->guardProfile($staffProfile);

        $staffProfile->bankAccounts()->create([
            'tenant_id' => $this->tenantIdOrFail(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.bank-accounts.index', $staffProfile)
            ->with('success', 'Bank account added.');
    }

    public function edit(StaffProfile $staffProfile, StaffBankAccount $bank_account)
    {
        $this->guardProfile($staffProfile);
        $this->guardItem($bank_account);

        abort_unless($bank_account->staff_profile_id === $staffProfile->id, 404);

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

        return view('backend.admin.staff-bank-accounts.edit', compact('staffProfile', 'bank_account'));
    }

    public function update(UpdateStaffBankAccountRequest $request, StaffProfile $staffProfile, StaffBankAccount $bank_account)
    {
        $this->guardProfile($staffProfile);
        $this->guardItem($bank_account);

        abort_unless($bank_account->staff_profile_id === $staffProfile->id, 404);

        $bank_account->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.bank-accounts.index', $staffProfile)
            ->with('success', 'Bank account updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffBankAccount $bank_account)
    {
        $this->guardProfile($staffProfile);
        $this->guardItem($bank_account);

        abort_unless($bank_account->staff_profile_id === $staffProfile->id, 404);

        $bank_account->delete();

        return back()->with('success', 'Bank account deleted.');
    }
}
