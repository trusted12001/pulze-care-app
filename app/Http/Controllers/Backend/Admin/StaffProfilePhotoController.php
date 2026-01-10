<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffProfilePhotoController extends Controller
{
    private function tenantId(): int
    {
        return (int) auth()->user()->tenant_id;
    }

    private function authorizeProfile(StaffProfile $p): void
    {
        abort_unless($p->tenant_id === $this->tenantId(), 404);
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
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:4096'], // 4MB
        ]);

        $file   = $request->file('photo');
        $tenant = $this->tenantId();

        $path = $file->store("documents/tenant_{$tenant}/staff_profile_{$staffProfile->id}", 'public');

        // Find the current Passport Photo doc (latest). We'll replace it.
        $current = $staffProfile->documents()
            ->where('category', 'Passport Photo')
            ->latest('id')
            ->first();

        // Optional: delete old file to prevent storage buildup
        if ($current?->path && Storage::disk('public')->exists($current->path)) {
            Storage::disk('public')->delete($current->path);
        }

        // If there was an existing Passport Photo doc, update it; otherwise create new.
        if ($current) {
            $current->update([
                'filename'    => $file->getClientOriginalName(),
                'path'        => $path,
                'mime'        => $file->getMimeType() ?? 'application/octet-stream',
                'uploaded_by' => auth()->id(),
                'hash'        => hash_file('sha256', $file->getRealPath()),
            ]);
        } else {
            $staffProfile->documents()->create([
                'tenant_id'   => $tenant,
                'category'    => 'Passport Photo',
                'filename'    => $file->getClientOriginalName(),
                'path'        => $path,
                'mime'        => $file->getMimeType() ?? 'application/octet-stream',
                'uploaded_by' => auth()->id(),
                'hash'        => hash_file('sha256', $file->getRealPath()),
            ]);
        }

        return back()->with('success', 'Profile photo updated successfully.');
    }
}
