<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Models\ServiceUser;
use Illuminate\Http\Request;

class ServiceUserPhotoController extends Controller
{
    use ResolvesTenantContext;

    private function tenantId(): int
    {
        return $this->tenantIdOrFail();
    }

    private function authorizeTenant(ServiceUser $su): void
    {
        $this->authorizeTenantRecord($su);
    }

    public function edit(ServiceUser $service_user)
    {
        $this->authorizeTenant($service_user);

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
            'photo' => ['required', 'file', 'image', 'max:4096'],
        ]);

        $file = $request->file('photo');
        $tenantId = $this->tenantIdOrFail();

        $path = $file->store(
            "documents/tenant_{$tenantId}/service_user_{$service_user->id}",
            'public'
        );

        $service_user->documents()->create([
            'tenant_id'   => $tenantId,
            'category'    => 'Passport Photo',
            'filename'    => $file->getClientOriginalName(),
            'path'        => $path,
            'mime'        => $file->getMimeType() ?? 'image/jpeg',
            'uploaded_by' => auth()->id(),
            'hash'        => hash_file('sha256', $file->getRealPath()),
        ]);

        return redirect()
            ->route('backend.admin.service-users.show', $service_user->id)
            ->with('success', 'Passport photo updated.');
    }
}
