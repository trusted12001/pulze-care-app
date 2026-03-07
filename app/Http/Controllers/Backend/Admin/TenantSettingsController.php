<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TenantSettingsController extends Controller
{
    /**
     * NOTE:
     * This assumes your admin user is tied to a tenant somehow.
     * Adjust getTenant() to match your actual structure.
     */
    private function getTenant(): Tenant
    {
        // OPTION A (recommended): if users table has tenant_id
        if (Auth::user() && isset(Auth::user()->tenant_id) && Auth::user()->tenant_id) {
            return Tenant::findOrFail(Auth::user()->tenant_id);
        }

        // OPTION B: fallback to first tenant (dev/demo)
        return Tenant::query()->firstOrFail();
    }

    public function edit()
    {
        $tenant = $this->getTenant();

        $settings = TenantSetting::firstOrCreate(
            ['tenant_id' => $tenant->id],
            ['office_address' => $tenant->address] // optional: start with tenant address
        );

        return view('backend.admin.settings.edit', compact('tenant', 'settings'));
    }

    public function update(Request $request)
    {
        $tenant = $this->getTenant();

        $settings = TenantSetting::firstOrCreate(['tenant_id' => $tenant->id]);

        $validated = $request->validate([
            'office_address' => ['nullable', 'string', 'max:2000'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        // Update office address
        $settings->office_address = $validated['office_address'] ?? null;

        // Handle logo upload (replace old)
        if ($request->hasFile('logo')) {
            // delete old logo (only if it was an uploaded one)
            if (!empty($settings->logo_path) && Storage::disk('public')->exists($settings->logo_path)) {
                Storage::disk('public')->delete($settings->logo_path);
            }

            $path = $request->file('logo')->store('tenant-logos', 'public');
            $settings->logo_path = $path;
        }

        $settings->save();

        return redirect()
            ->route('backend.admin.settings.edit')
            ->with('success', 'Settings updated successfully.');
    }
}
