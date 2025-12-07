<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffProfileRequest;
use App\Http\Requests\UpdateStaffProfileRequest;
use App\Models\StaffProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // add this line if using PDF


class StaffProfileController extends Controller
{
    private function tenantId(): int
    {
        return (int) auth()->user()->tenant_id;
    }

    public function index(Request $request)
    {
        $tenantId = $this->tenantId();
        $search   = trim((string) $request->get('q', ''));

        $profiles = StaffProfile::query()
            ->where('tenant_id', $tenantId)
            ->with(['user'])
            ->when($search !== '', function ($q) use ($search) {
                $like = "%{$search}%";
                $q->where(function ($sub) use ($like) {
                    $sub->where('job_title', 'like', $like)
                        ->orWhereHas('user', function ($uq) use ($like) {
                            $uq->where('email', 'like', $like)
                                ->orWhere('first_name', 'like', $like)
                                ->orWhere('last_name', 'like', $like)
                                ->orWhere('other_names', 'like', $like)
                                ->orWhereRaw("CONCAT_WS(' ', first_name, other_names, last_name) LIKE ?", [$like]);
                        });
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('backend.admin.staff-profiles.index', [
            'profiles' => $profiles,
            'search'   => $search,
        ]);
    }

    public function create()
    {
        $tenantId = $this->tenantId();

        $users = User::query()
            ->where('tenant_id', $tenantId)
            ->whereDoesntHave('staffProfile')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'other_names', 'email']);

        // Optional: Locations & Managers (for new fields)
        $locations = class_exists(\App\Models\Location::class)
            ? \App\Models\Location::where('tenant_id', $tenantId)->orderBy('name')->get(['id', 'name'])
            : collect();

        $managers = User::query()
            ->where('tenant_id', $tenantId)
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'other_names', 'email']);

        return view('backend.admin.staff-profiles.create', compact('users', 'locations', 'managers'));
    }

    public function store(StoreStaffProfileRequest $request)
    {
        StaffProfile::create([
            'tenant_id' => $this->tenantId(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.index')
            ->with('success', 'Staff profile created.');
    }

    public function show(StaffProfile $staffProfile)
    {
        $this->authorizeTenant($staffProfile);

        return view('backend.admin.staff-profiles.show', compact('staffProfile'));
    }

    public function edit(StaffProfile $staffProfile)
    {
        $this->authorizeTenant($staffProfile);

        $tenantId = $this->tenantId();

        $users = User::query()
            ->where('tenant_id', $tenantId)
            ->where(function ($q) use ($staffProfile) {
                $q->whereDoesntHave('staffProfile')
                    ->orWhere('id', $staffProfile->user_id);
            })
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'other_names', 'email']);

        $locations = class_exists(\App\Models\Location::class)
            ? \App\Models\Location::where('tenant_id', $tenantId)->orderBy('name')->get(['id', 'name'])
            : collect();

        $managers = User::query()
            ->where('tenant_id', $tenantId)
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'other_names', 'email']);

        return view('backend.admin.staff-profiles.edit', compact('staffProfile', 'users', 'locations', 'managers'));
    }

    public function update(UpdateStaffProfileRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeTenant($staffProfile);

        $staffProfile->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.index')
            ->with('success', 'Staff profile updated.');
    }

    public function destroy(StaffProfile $staffProfile)
    {
        $this->authorizeTenant($staffProfile);

        $staffProfile->delete();

        return back()->with('success', 'Staff profile moved to recycle bin.');
    }

    public function trashed(Request $request)
    {
        $tenantId = $this->tenantId();
        $search   = trim((string) $request->get('q', ''));

        $profiles = StaffProfile::onlyTrashed()
            ->where('tenant_id', $tenantId)
            ->with('user')
            ->when($search !== '', function ($q) use ($search) {
                $like = "%{$search}%";
                $q->where(function ($sub) use ($like) {
                    $sub->where('job_title', 'like', $like)
                        ->orWhereHas('user', function ($uq) use ($like) {
                            $uq->where('email', 'like', $like)
                                ->orWhere('first_name', 'like', $like)
                                ->orWhere('last_name', 'like', $like)
                                ->orWhere('other_names', 'like', $like)
                                ->orWhereRaw("CONCAT_WS(' ', first_name, other_names, last_name) LIKE ?", [$like]);
                        });
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('backend.admin.staff-profiles.trashed', [
            'profiles' => $profiles,
            'search'   => $search,
        ]);
    }

    public function restore(int $id)
    {
        $profile = StaffProfile::onlyTrashed()->findOrFail($id);
        $this->authorizeTenant($profile);

        $profile->restore();

        return back()->with('success', 'Staff profile restored.');
    }

    public function forceDelete(int $id)
    {
        $profile = StaffProfile::onlyTrashed()->findOrFail($id);
        $this->authorizeTenant($profile);

        $profile->forceDelete();

        return back()->with('success', 'Staff profile permanently deleted.');
    }

    public function print(StaffProfile $staffProfile)
    {
        $this->authorizeTenant($staffProfile);

        $staffProfile->load([
            'user',
            'workLocation',
            'lineManager',
            'contracts',
            'payroll',
            'bankAccounts',
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
            'disciplinaryRecords',
            'documents',
        ]);

        return view('backend.admin.staff-profiles.print', [
            'staff' => $staffProfile,
        ]);
    }

    public function pdf(StaffProfile $staffProfile)
    {
        $this->authorizeTenant($staffProfile);

        $staffProfile->load([
            'user',
            'workLocation',
            'lineManager',
            'contracts',
            'payroll',
            'bankAccounts',
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
            'disciplinaryRecords',
            'documents',
        ]);

        $pdf = Pdf::loadView('backend.admin.staff-profiles.print', [
            'staffProfile' => $staffProfile,
            'isPdf'        => true,
        ])->setPaper('a4', 'portrait');

        $filename = 'staff-profile-' . $staffProfile->id . '.pdf';

        return $pdf->download($filename);
    }


    protected function authorizeTenant(StaffProfile $p): void
    {
        abort_unless($p->tenant_id === $this->tenantId(), 404);
    }
}
