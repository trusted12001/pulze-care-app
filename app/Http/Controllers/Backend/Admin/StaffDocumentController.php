<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Concerns\ResolvesTenantContext;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffDocumentRequest;
use App\Http\Requests\UpdateStaffDocumentRequest;
use App\Models\Document;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffDocumentController extends Controller
{
    use ResolvesTenantContext;

    private function tenantId(): int
    {
        return $this->tenantIdOrFail();
    }

    private function authorizeProfile(StaffProfile $staffProfile): void
    {
        $this->authorizeTenantRecord($staffProfile);
    }

    private function authorizeItem(Document $document): void
    {
        $this->authorizeTenantRecord($document);
    }

    /**
     * Central place for allowed categories.
     * "Passport Photo" is reserved for profile avatar.
     */
    protected function categories(): array
    {
        return [
            'Passport Photo',
            'DBS',
            'Visa',
            'Training Cert',
            'Contract',
            'Payroll',
            'Reference',
            'OH',
            'ID',
            'Other',
        ];
    }

    public function index(Request $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $category = $request->string('category')->toString();
        $q = trim((string) $request->get('q', ''));

        $docs = $staffProfile->documents()
            ->with('uploadedBy')
            ->when($category !== '', fn($query) => $query->where('category', $category))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('filename', 'like', "%{$q}%")
                        ->orWhere('mime', 'like', "%{$q}%")
                        ->orWhere('category', 'like', "%{$q}%");
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $staffProfile->loadCount([
            'disciplinaryRecords',
            'documents',
            'contracts',
            'registrations',
            'employmentChecks',
            'visas',
            'trainingRecords',
            'supervisionsAppraisals',
            'qualifications',
            'occHealthClearances',
            'immunisations',
            'leaveEntitlements',
            'leaveRecords',
            'availabilityPreferences',
            'emergencyContacts',
            'equalityData',
            'adjustments',
            'drivingLicences',
        ]);

        return view('backend.admin.staff-documents.index', [
            'staffProfile' => $staffProfile,
            'docs' => $docs,
            'categories' => $this->categories(),
            'category' => $category,
            'q' => $q,
        ]);
    }

    public function create(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);
        $categories = $this->categories();

        return view('backend.admin.staff-documents.create', compact('staffProfile', 'categories'));
    }

    public function store(StoreStaffDocumentRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $file = $request->file('file');
        $tenantId = $this->tenantIdOrFail();
        $path = $file->store("documents/tenant_{$tenantId}/staff_profile_{$staffProfile->id}", 'public');

        $staffProfile->documents()->create([
            'tenant_id' => $tenantId,
            'category' => $request->category,
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime' => $file->getMimeType() ?? 'application/octet-stream',
            'uploaded_by' => auth()->id(),
            'hash' => hash_file('sha256', $file->getRealPath()),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.documents.index', $staffProfile)
            ->with('success', 'Document uploaded.');
    }

    public function edit(StaffProfile $staffProfile, Document $document)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($document);

        abort_unless(
            $document->owner_type === StaffProfile::class && (int) $document->owner_id === (int) $staffProfile->id,
            404
        );

        $categories = $this->categories();

        return view('backend.admin.staff-documents.edit', compact('staffProfile', 'document', 'categories'));
    }

    public function update(UpdateStaffDocumentRequest $request, StaffProfile $staffProfile, Document $document)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($document);

        abort_unless(
            $document->owner_type === StaffProfile::class && (int) $document->owner_id === (int) $staffProfile->id,
            404
        );

        $data = [
            'category' => $request->category,
        ];

        if ($request->hasFile('file')) {
            if ($document->path && Storage::disk('public')->exists($document->path)) {
                Storage::disk('public')->delete($document->path);
            }

            $file = $request->file('file');
            $tenantId = $this->tenantIdOrFail();
            $path = $file->store("documents/tenant_{$tenantId}/staff_profile_{$staffProfile->id}", 'public');

            $data += [
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'mime' => $file->getMimeType() ?? 'application/octet-stream',
                'hash' => hash_file('sha256', $file->getRealPath()),
            ];
        }

        $document->update($data);

        return redirect()
            ->route('backend.admin.staff-profiles.documents.index', $staffProfile)
            ->with('success', 'Document updated.');
    }

    public function destroy(StaffProfile $staffProfile, Document $document)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($document);

        abort_unless(
            $document->owner_type === StaffProfile::class && (int) $document->owner_id === (int) $staffProfile->id,
            404
        );

        if ($document->path && Storage::disk('public')->exists($document->path)) {
            Storage::disk('public')->delete($document->path);
        }

        $document->delete();

        return back()->with('success', 'Document deleted.');
    }
}
