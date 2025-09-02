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
        \Log::info('Tenant check', [
            'user_id' => auth()->id(),
            'user_tenant' => auth()->user()->tenant_id ?? null,
            'su_id' => $su->id,
            'su_tenant' => $su->tenant_id,
        ]);
        abort_unless($su->tenant_id == $this->tenantId(), 404);
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

    public function profile(ServiceUser $service_user)
    {
        $this->authorizeTenant($service_user);
        $service_user->load('location');
        return view('backend.admin.service-users.profile', ['su' => $service_user]);
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


    // public function updateSection(UpdateServiceUserRequest $request, ServiceUser $service_user, string $section)
    // {
    //     $this->authorizeTenant($service_user);

    //     $data = $request->validated();

    //     // Optional: normalize NHS number (strip spaces)
    //     if (array_key_exists('nhs_number', $data) && $data['nhs_number'] !== null) {
    //         $data['nhs_number'] = preg_replace('/\s+/', '', $data['nhs_number']);
    //     }

    //     // Coerce booleans for checkbox fields if your form posts "on"/null
    //     foreach ([
    //         'behaviour_support_plan','seizure_care_plan','diabetes_care_plan','oxygen_therapy',
    //         'wander_elopement_risk','safeguarding_flag','infection_control_flag',
    //         'dols_in_place','lpa_health_welfare','lpa_finance_property',
    //         'interpreter_required',
    //     ] as $boolField) {
    //         if ($request->has($boolField)) {
    //             $data[$boolField] = (bool) $request->boolean($boolField);
    //         }
    //     }

    //     $data['updated_by'] = auth()->id();

    //     $service_user->fill($data)->save();

    //     return back()->with('success', ucfirst(str_replace('_', ' ', $section)).' updated.');
    // }

    public function updateSection(UpdateServiceUserRequest $request, ServiceUser $service_user, string $section)
    {
        $this->authorizeTenant($service_user);

        $data = $request->validated();

        // Normalize NHS number
        if (array_key_exists('nhs_number', $data) && $data['nhs_number'] !== null) {
            $data['nhs_number'] = preg_replace('/\s+/', '', $data['nhs_number']);
        }

        // Coerce booleans
        foreach ([
            'behaviour_support_plan','seizure_care_plan','diabetes_care_plan','oxygen_therapy',
            'wander_elopement_risk','safeguarding_flag','infection_control_flag',
            'dols_in_place','lpa_health_welfare','lpa_finance_property',
            'interpreter_required',
        ] as $boolField) {
            if ($request->has($boolField)) {
                $data[$boolField] = (bool) $request->boolean($boolField);
            }
        }

        // NEW: tags — accept comma string, store valid JSON array
        if ($section === 'tags' && $request->filled('tags')) {
            $raw = $request->string('tags')->toString();

            // split on commas/semicolons, trim, remove empties & dups
            $arr = array_values(array_unique(array_filter(array_map('trim', preg_split('/[,;]+/', $raw)))));

            // store as JSON (valid for CHECK json_valid(tags))
            $data['tags'] = json_encode($arr, JSON_UNESCAPED_UNICODE);
        }

        $data['updated_by'] = auth()->id();

        $service_user->fill($data)->save();

        return back()->with('success', ucfirst(str_replace('_', ' ', $section)).' updated.');
    }

}
