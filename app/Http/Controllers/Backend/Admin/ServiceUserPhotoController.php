<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceUser;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceUserPhotoController extends Controller
{
    private function tenantId(): int
    {
        return (int) auth()->user()->tenant_id;
    }

    private function authorizeTenant(ServiceUser $su): void
    {
        abort_unless($su->tenant_id === $this->tenantId(), 404);
    }

    public function edit(ServiceUser $service_user)
    {
        $this->authorizeTenant($service_user);

        // Ensure latest Passport Photo is available
        $service_user->load([
            'documents' => function ($q) {
                $q->where('category', 'Passport Photo')->latest('id');
            },
        ]);

        return view('backend.admin.service-users.photo', [
            'su' => $service_user,
        ]);
    }

    public function update(Request $request, ServiceUser $service_user)
    {
        $this->authorizeTenant($service_user);

        $request->validate([
            'photo' => ['required', 'file', 'image', 'max:4096'], // 4MB, image only
        ]);

        $file   = $request->file('photo');
        $tenant = $this->tenantId();

        $path = $file->store(
            "documents/tenant_{$tenant}/service_user_{$service_user->id}",
            'public'
        );

        // Create a new Document record for this Passport Photo
        $service_user->documents()->create([
            'tenant_id'   => $tenant,
            'category'    => 'Passport Photo',
            'filename'    => $file->getClientOriginalName(),
            'path'        => $path,
            'mime'        => $file->getMimeType() ?? 'image/jpeg',
            'uploaded_by' => auth()->id(),
            'hash'        => hash_file('sha256', $file->getRealPath()),
        ]);

        // (Optional) If you want to delete OLD passport photo files, we can
        // add a cleanup step here later. For now, we keep history and only
        // show the latest via passport_photo_url.

        return redirect()
            ->route('backend.admin.service-users.show', $service_user->id)
            ->with('success', 'Passport photo updated.');
    }
}
