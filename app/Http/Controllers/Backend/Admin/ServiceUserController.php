<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceUserRequest;
use App\Http\Requests\UpdateServiceUserRequest;
use App\Models\ServiceUser;
use App\Models\Location;
use Illuminate\Http\Request;

class ServiceUserController extends Controller
{
    private function tenantId(): int
    {
        return (int) auth()->user()->tenant_id;
    }

    protected function authorizeTenant(ServiceUser $su): void
    {
        abort_unless($su->tenant_id === $this->tenantId(), 404);
    }

    public function index(Request $request)
    {
        $tenantId = $this->tenantId();

        $serviceUsers = ServiceUser::where('tenant_id', $tenantId)
            ->with('location')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('backend.admin.service-users.index', compact('serviceUsers'));
    }

    public function create()
    {
        $locations = Location::where('tenant_id', $this->tenantId())
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id','name','city','postcode']);

        return view('backend.admin.service-users.create', compact('locations'));
    }

    public function store(StoreServiceUserRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $this->tenantId();
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        ServiceUser::create($data);

        return redirect()
            ->route('backend.admin.service-users.index')
            ->with('success', 'Service user created.');
    }

    public function show(ServiceUser $service_user)
    {
        $this->authorizeTenant($service_user);
        $service_user->load('location');
        return view('backend.admin.service-users.show', ['su' => $service_user]);
    }

    public function edit(ServiceUser $service_user)
    {
        $this->authorizeTenant($service_user);

        $locations = Location::where('tenant_id', $this->tenantId())
            ->where('status','active')
            ->orderBy('name')
            ->get(['id','name','city','postcode']);

        return view('backend.admin.service-users.edit', [
            'su' => $service_user,
            'locations' => $locations,
        ]);
    }

    public function update(UpdateServiceUserRequest $request, ServiceUser $service_user)
    {
        $this->authorizeTenant($service_user);

        $data = $request->validated();
        $data['updated_by'] = auth()->id();

        $service_user->update($data);

        return redirect()
            ->route('backend.admin.service-users.index')
            ->with('success', 'Service user updated.');
    }

    public function destroy(ServiceUser $service_user)
    {
        $this->authorizeTenant($service_user);
        $service_user->delete();

        return back()->with('success', 'Service user moved to recycle bin.');
    }

    public function trashed()
    {
        $tenantId = $this->tenantId();

        $serviceUsers = ServiceUser::onlyTrashed()
            ->where('tenant_id', $tenantId)
            ->orderByDesc('id')
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
}
