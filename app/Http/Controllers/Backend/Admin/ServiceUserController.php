<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceUserRequest;
use App\Http\Requests\UpdateServiceUserRequest;
use App\Models\ServiceUser;
use Illuminate\Http\Request;

class ServiceUserController extends Controller
{
    private function tenantId(): int
    {
        return (int) auth()->user()->tenant_id;
    }

    public function index(Request $request)
    {
        $tenantId = $this->tenantId();
        $search = trim((string) $request->get('q', ''));

        $serviceUsers = ServiceUser::query()
            ->where('tenant_id', $tenantId)
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('preferred_name', 'like', "%{$search}%")
                      ->orWhere('nhs_number', 'like', "%{$search}%")
                      ->orWhere('gp_practice_name', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('backend.admin.service-users.index', [
            'serviceUsers' => $serviceUsers,
        ]);
    }

    public function create()
    {
        return view('backend.admin.service-users.create');
    }

    public function store(StoreServiceUserRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $this->tenantId();
        $data['created_by'] = auth()->id();

        ServiceUser::create($data);

        return redirect()
            ->route('backend.admin.service-users.index')
            ->with('success', 'Service user created.');
    }

    public function show(ServiceUser $serviceUser)
    {
        $this->authorizeTenant($serviceUser);
        return view('backend.admin.service-users.show', compact('serviceUser'));
    }

    public function edit(ServiceUser $serviceUser)
    {
        $this->authorizeTenant($serviceUser);
        return view('backend.admin.service-users.edit', compact('serviceUser'));
    }

    public function update(UpdateServiceUserRequest $request, ServiceUser $serviceUser)
    {
        $this->authorizeTenant($serviceUser);

        $data = $request->validated();
        $data['updated_by'] = auth()->id();

        $serviceUser->update($data);

        return redirect()
            ->route('backend.admin.service-users.index')
            ->with('success', 'Service user updated.');
    }

    public function destroy(ServiceUser $serviceUser)
    {
        $this->authorizeTenant($serviceUser);
        $serviceUser->delete();

        return back()->with('success', 'Service user moved to recycle bin.');
    }

    public function trashed(Request $request)
    {
        $tenantId = $this->tenantId();

        $serviceUsers = ServiceUser::onlyTrashed()
            ->where('tenant_id', $tenantId)
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('backend.admin.service-users.trashed', compact('serviceUsers'));
    }

    public function restore(int $id)
    {
        $su = ServiceUser::onlyTrashed()->findOrFail($id);
        $this->authorizeTenant($su);
        $su->restore();

        return back()->with('success', 'Service user restored.');
    }

    public function forceDelete(int $id)
    {
        $su = ServiceUser::onlyTrashed()->findOrFail($id);
        $this->authorizeTenant($su);
        $su->forceDelete();

        return back()->with('success', 'Service user permanently deleted.');
    }

    protected function authorizeTenant(ServiceUser $su): void
    {
        abort_unless($su->tenant_id === $this->tenantId(), 404);
    }
}
