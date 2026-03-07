<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffProfilePhotoController extends Controller
{
    use ResolvesTenantContext;

    private function authorizeProfile(StaffProfile $staffProfile): void
    {
        $this->authorizeTenantRecord($staffProfile);
    }

    public function edit(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        return view('backend.admin.staff-profiles.photo_edit', [
            'profile' => $staffProfile,
        ]);
    }

    public function update(Request $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
        ]);

        $file = $request->file('photo');
        $tenantId = $this->tenantIdOrFail();

        $path = $file->store("documents/tenant_{$tenantId}/staff_profile_{$staffProfile->id}", 'public');

        $current = $staffProfile->documents()
            ->where('category', 'Passport Photo')
            ->latest('id')
            ->first();

        if ($current?->path && Storage::disk('public')->exists($current->path)) {
            Storage::disk('public')->delete($current->path);
        }

        $payload = [
            'tenant_id'   => $tenantId,
            'category'    => 'Passport Photo',
            'filename'    => $file->getClientOriginalName(),
            'path'        => $path,
            'mime'        => $file->getMimeType() ?? 'application/octet-stream',
            'uploaded_by' => auth()->id(),
            'hash'        => hash_file('sha256', $file->getRealPath()),
        ];

        if ($current) {
            $current->update($payload);
        } else {
            $staffProfile->documents()->create($payload);
        }

        return back()->with('success', 'Profile photo updated successfully.');
    }
}
