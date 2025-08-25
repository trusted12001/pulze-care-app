<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private function tenantId(): int
    {
        return (int) auth()->user()->tenant_id;
    }

    protected function authorizeTenant(Location $location): void
    {
        abort_unless($location->tenant_id === $this->tenantId(), 404);
    }

    public function index(Request $request)
    {
        $tenantId = $this->tenantId();
        $q = $request->get('q');

        $query = Location::where('tenant_id', $tenantId)
            ->withCount('serviceUsers')
            ->orderByDesc('id');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('postcode', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $locations = $query->paginate(15)->withQueryString();

        return view('backend.admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('backend.admin.locations.create');
    }

    public function store(StoreLocationRequest $request)
    {
        Location::create([
            'tenant_id' => $this->tenantId(),
            ...$request->validated(),
        ]);

        return redirect()
            ->route('backend.admin.locations.index')
            ->with('success', 'Location created.');
    }

    public function show(Location $location)
    {
        $this->authorizeTenant($location);

        return view('backend.admin.locations.show', compact('location'));
    }

    public function edit(Location $location)
    {
        $this->authorizeTenant($location);

        return view('backend.admin.locations.edit', compact('location'));
    }

    public function update(UpdateLocationRequest $request, Location $location)
    {
        $this->authorizeTenant($location);

        $location->update($request->validated());

        return redirect()
            ->route('backend.admin.locations.index')
            ->with('success', 'Location updated.');
    }

    public function destroy(Location $location)
    {
        $this->authorizeTenant($location);

        $location->delete();

        return back()->with('success', 'Location moved to recycle bin.');
    }

    public function trashed(Request $request)
    {
        $tenantId = $this->tenantId();

        $locations = Location::onlyTrashed()
            ->where('tenant_id', $tenantId)
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('backend.admin.locations.trashed', compact('locations'));
    }

    public function restore(int $id)
    {
        $location = Location::onlyTrashed()->findOrFail($id);
        $this->authorizeTenant($location);
        $location->restore();

        return back()->with('success', 'Location restored.');
    }

    public function forceDelete(int $id)
    {
        $location = Location::onlyTrashed()->findOrFail($id);
        $this->authorizeTenant($location);
        $location->forceDelete();

        return back()->with('success', 'Location permanently deleted.');
    }
}
