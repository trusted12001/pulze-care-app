<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceUserRequest;
use App\Http\Requests\UpdateServiceUserRequest;
use App\Models\Location;
use App\Models\ServiceUser;
use Illuminate\Http\Request;

class ServiceUserController extends Controller
{
    use ResolvesTenantContext;

    private function tenantId(): int
    {
        return $this->tenantIdOrFail();
    }

    protected function authorizeTenant(ServiceUser $su): void
    {
        \Log::info('Tenant check', [
            'user_id' => auth()->id(),
            'effective_tenant' => $this->tenantIdOrFail(),
            'user_tenant' => auth()->user()->tenant_id ?? null,
            'su_id' => $su->id,
            'su_tenant' => $su->tenant_id,
            'support_mode' => session('support_mode', false),
            'active_tenant_id' => session('active_tenant_id'),
        ]);

        $this->authorizeTenantRecord($su);
    }

    public function index(Request $request)
    {
        $tenantId = $this->tenantIdOrFail();

        $serviceUsers = ServiceUser::where('tenant_id', $tenantId)
            ->with('location')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('backend.admin.service-users.index', compact('serviceUsers'));
    }

    public function create()
    {
        $tenantId = $this->tenantIdOrFail();

        $locations = Location::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'city', 'postcode']);

        return view('backend.admin.service-users.create', compact('locations'));
    }

    public function store(StoreServiceUserRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $this->tenantIdOrFail();
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

        $service_user->load([
            'location',
            'documents' => function ($q) {
                $q->where('category', 'Passport Photo')->latest('id');
            },
        ]);

        return view('backend.admin.service-users.show', ['su' => $service_user]);
    }

    public function profile(ServiceUser $service_user)
    {
        $this->authorizeTenant($service_user);

        $service_user->load([
            'location',
            'documents' => function ($q) {
                $q->where('category', 'Passport Photo')->latest('id');
            },
        ]);

        return view('backend.admin.service-users.profile', ['su' => $service_user]);
    }

    public function edit(ServiceUser $service_user)
    {
        $this->authorizeTenant($service_user);

        $tenantId = $this->tenantIdOrFail();

        $locations = Location::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'city', 'postcode']);

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
        $tenantId = $this->tenantIdOrFail();

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

    public function print(ServiceUser $service_user)
    {
        $this->authorizeTenant($service_user);

        $service_user->load([
            'location',
            'passportPhoto',
        ]);

        return view('backend.admin.service-users.print', [
            'su' => $service_user,
        ]);
    }

    public function updateSection(UpdateServiceUserRequest $request, ServiceUser $service_user, string $section)
    {
        $this->authorizeTenant($service_user);

        $data = $request->validated();

        if (array_key_exists('nhs_number', $data) && $data['nhs_number'] !== null) {
            $data['nhs_number'] = preg_replace('/\s+/', '', $data['nhs_number']);
        }

        foreach (
            [
                'behaviour_support_plan',
                'seizure_care_plan',
                'diabetes_care_plan',
                'oxygen_therapy',
                'wander_elopement_risk',
                'safeguarding_flag',
                'infection_control_flag',
                'dols_in_place',
                'lpa_health_welfare',
                'lpa_finance_property',
                'interpreter_required',
            ] as $boolField
        ) {
            if ($request->has($boolField)) {
                $data[$boolField] = (bool) $request->boolean($boolField);
            }
        }

        if ($section === 'tags' && $request->filled('tags')) {
            $raw = $request->string('tags')->toString();

            $arr = array_values(array_unique(array_filter(array_map('trim', preg_split('/[,;]+/', $raw)))));

            $data['tags'] = json_encode($arr, JSON_UNESCAPED_UNICODE);
        }

        $data['updated_by'] = auth()->id();

        $service_user->fill($data)->save();

        return back()->with('success', ucfirst(str_replace('_', ' ', $section)) . ' updated.');
    }
}
