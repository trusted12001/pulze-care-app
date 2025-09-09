<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffProfileRequest;
use App\Http\Requests\UpdateStaffProfileRequest;
use App\Models\StaffProfile;
use App\Models\User;
use Illuminate\Http\Request;

class StaffProfileController extends Controller
{
    /**
     * Convenience helper for current user's tenant id.
     */
    private function tenantId(): int
    {
        return (int) auth()->user()->tenant_id;
    }

    /**
     * List staff profiles (with search + pagination) for current tenant.
     */
    public function index(Request $request)
    {
        $tenantId = $this->tenantId();
        $search   = trim((string) $request->get('q', ''));

        $profiles = StaffProfile::query()
            ->where('tenant_id', $tenantId)
            ->with('user')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('job_title', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
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

    /**
     * Show create form. Lists tenant users who don't yet have a staff profile.
     */
    public function create()
    {
        $users = User::query()
            ->where('tenant_id', $this->tenantId())
            ->whereDoesntHave('staffProfile')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('backend.admin.staff-profiles.create', compact('users'));
    }

    /**
     * Persist a new staff profile.
     */
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

    /**
     * Show a single staff profile (tenant-guarded).
     */
    public function show(StaffProfile $staffProfile)
    {
        $this->authorizeTenant($staffProfile);

        return view('backend.admin.staff-profiles.show', compact('staffProfile'));
    }

    /**
     * Edit form with user selection limited to current tenant.
     */
    public function edit(StaffProfile $staffProfile)
    {
        $this->authorizeTenant($staffProfile);

        $users = User::query()
            ->where('tenant_id', $this->tenantId())
            ->where(function ($q) use ($staffProfile) {
                // Allow current linked user OR anyone without a profile
                $q->whereDoesntHave('staffProfile')
                  ->orWhere('id', $staffProfile->user_id);
            })
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'other_names', 'email']);

        return view('backend.admin.staff-profiles.edit', compact('staffProfile', 'users'));
    }

    /**
     * Update a staff profile.
     */
    public function update(UpdateStaffProfileRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeTenant($staffProfile);

        $staffProfile->update($request->validated());

        return redirect()
            ->route('backend.admin.staff-profiles.index')
            ->with('success', 'Staff profile updated.');
    }

    /**
     * Soft delete to trash.
     */
    public function destroy(StaffProfile $staffProfile)
    {
        $this->authorizeTenant($staffProfile);

        $staffProfile->delete();

        return back()->with('success', 'Staff profile moved to recycle bin.');
    }

    /**
     * List trashed profiles (optional UI).
     */
    public function trashed(Request $request)
    {
        $tenantId = $this->tenantId();
        $search   = trim((string) $request->get('q', ''));

        $profiles = StaffProfile::onlyTrashed()
            ->where('tenant_id', $tenantId)
            ->with('user')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('job_title', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
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

    /**
     * Restore a soft-deleted profile.
     */
    public function restore(int $id)
    {
        $profile = StaffProfile::onlyTrashed()->findOrFail($id);
        $this->authorizeTenant($profile);

        $profile->restore();

        return back()->with('success', 'Staff profile restored.');
    }

    /**
     * Permanently delete a soft-deleted profile.
     */
    public function forceDelete(int $id)
    {
        $profile = StaffProfile::onlyTrashed()->findOrFail($id);
        $this->authorizeTenant($profile);

        $profile->forceDelete();

        return back()->with('success', 'Staff profile permanently deleted.');
    }

    /**
     * Guard every model operation by tenant.
     */
    protected function authorizeTenant(StaffProfile $p): void
    {
        abort_unless($p->tenant_id === $this->tenantId(), 404);
    }
}
