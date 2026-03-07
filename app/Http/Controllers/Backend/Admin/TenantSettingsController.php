<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenantSettingsController extends Controller
{
    use ResolvesTenantContext;

    /**
     * Resolve the tenant from the current effective tenant context.
     */
    private function getTenant(): Tenant
    {
        return Tenant::findOrFail($this->tenantIdOrFail());
    }

    public function edit()
    {
        $tenant = $this->getTenant();

        $settings = TenantSetting::firstOrCreate(
            ['tenant_id' => $tenant->id],
            ['office_address' => $tenant->address]
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

        $settings->office_address = $validated['office_address'] ?? null;

        if ($request->hasFile('logo')) {
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
