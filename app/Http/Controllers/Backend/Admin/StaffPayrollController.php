<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffPayrollRequest;
use App\Http\Requests\UpdateStaffPayrollRequest;
use App\Models\StaffPayroll;
use App\Models\StaffProfile;
use Illuminate\Http\Request;

class StaffPayrollController extends Controller
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

    private function guardPayroll(StaffPayroll $payroll): void
    {
        $this->authorizeTenantRecord($payroll);
    }

    public function index(StaffProfile $staffProfile)
    {
        $this->guardProfile($staffProfile);

        $payroll = $staffProfile->payroll;

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

        return view('backend.admin.staff-payroll.index', compact('staffProfile', 'payroll'));
    }

    public function create(StaffProfile $staffProfile)
    {
        $this->guardProfile($staffProfile);

        abort_if($staffProfile->payroll, 403, 'Payroll already exists. Edit instead.');

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

        return view('backend.admin.staff-payroll.create', compact('staffProfile'));
    }

    public function store(StoreStaffPayrollRequest $request, StaffProfile $staffProfile)
    {
        $this->guardProfile($staffProfile);

        abort_if($staffProfile->payroll, 403, 'Payroll already exists.');

        $staffProfile->payroll()->create([
            'tenant_id' => $this->tenantIdOrFail(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.payroll.index', $staffProfile)
            ->with('success', 'Payroll created.');
    }

    public function edit(StaffProfile $staffProfile, StaffPayroll $payroll)
    {
        $this->guardProfile($staffProfile);
        $this->guardPayroll($payroll);

        abort_unless($payroll->staff_profile_id === $staffProfile->id, 404);

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

        return view('backend.admin.staff-payroll.edit', compact('staffProfile', 'payroll'));
    }

    public function update(UpdateStaffPayrollRequest $request, StaffProfile $staffProfile, StaffPayroll $payroll)
    {
        $this->guardProfile($staffProfile);
        $this->guardPayroll($payroll);

        abort_unless($payroll->staff_profile_id === $staffProfile->id, 404);

        $data = $request->validated();

        if (empty($data['ni_number'])) {
            unset($data['ni_number']);
        }

        $payroll->update($data);

        return redirect()
            ->route('backend.admin.staff-profiles.payroll.index', $staffProfile)
            ->with('success', 'Payroll updated.');
    }

    public function destroy(StaffProfile $staffProfile, StaffPayroll $payroll)
    {
        $this->guardProfile($staffProfile);
        $this->guardPayroll($payroll);

        abort_unless($payroll->staff_profile_id === $staffProfile->id, 404);

        $payroll->delete();

        return back()->with('success', 'Payroll removed.');
    }
}
