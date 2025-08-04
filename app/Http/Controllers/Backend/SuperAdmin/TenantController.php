<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::latest()->whereNull('deleted_at')->paginate(10);
        return view('backend.super-admin.tenants.index', compact('tenants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email|max:255',
            'phone'  => 'nullable|string|max:50',
            'address'=> 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['subscription_status'] = 'active';
        $data['created_by'] = Auth::id();

        Tenant::create($data);

        return redirect()->route('backend.super-admin.tenants.index')->with('success', 'Tenant created successfully.');
    }

    public function edit($id)
    {
        $tenant = Tenant::findOrFail($id);
        return view('backend.super-admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug,' . $tenant->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $tenant->update($data);

        return redirect()->route('backend.super-admin.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete(); // Soft delete
        return redirect()->route('backend.super-admin.tenants.index')->with('success', 'Tenant moved to trash.');
    }

    public function trashed()
    {
        $tenants = Tenant::onlyTrashed()->paginate(10);
        return view('backend.super-admin.tenants.trashed', compact('tenants'));
    }

    public function restore($id)
    {
        $tenant = Tenant::onlyTrashed()->findOrFail($id);
        $tenant->restore();

        return redirect()->route('backend.super-admin.tenants.trashed')->with('success', 'Tenant restored successfully.');
    }

    public function forceDelete($id)
    {
        $tenant = Tenant::onlyTrashed()->findOrFail($id);
        $tenant->forceDelete();

        return redirect()->route('backend.super-admin.tenants.trashed')->with('success', 'Tenant permanently deleted.');
    }
}
