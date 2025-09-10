<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffDocumentRequest;
use App\Http\Requests\UpdateStaffDocumentRequest;
use App\Models\Document;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffDocumentController extends Controller
{
    private function tenantId(): int { return (int)auth()->user()->tenant_id; }
    private function authorizeProfile(StaffProfile $p): void { abort_unless($p->tenant_id === $this->tenantId(), 404); }
    private function authorizeItem(Document $d): void { abort_unless($d->tenant_id === $this->tenantId(), 404); }

    public function index(Request $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $category = $request->string('category')->toString();
        $q = trim((string)$request->get('q',''));

        $docs = $staffProfile->documents()
            ->when($category !== '', fn($qq) => $qq->where('category', $category))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($sub) use ($q) {
                    $sub->where('filename','like',"%{$q}%")
                        ->orWhere('mime','like',"%{$q}%")
                        ->orWhere('category','like',"%{$q}%");
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $staffProfile->loadCount([
            'disciplinaryRecords','documents',
            'contracts','registrations','employmentChecks','visas',
            'trainingRecords','supervisionsAppraisals','qualifications',
            'occHealthClearances','immunisations',
            'leaveEntitlements','leaveRecords','availabilityPreferences',
            'emergencyContacts','equalityData','adjustments','drivingLicences',
        ]);

        // common categories hint for UI; adjust as you like
        $categories = ['DBS','Visa','Training Cert','Contract','Payroll','Reference','OH','ID','Other'];

        return view('backend.admin.staff-documents.index', [
            'staffProfile' => $staffProfile,
            'docs' => $docs,
            'categories' => $categories,
            'category' => $category,
            'q' => $q,
        ]);
    }

    public function create(StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);
        $categories = ['DBS','Visa','Training Cert','Contract','Payroll','Reference','OH','ID','Other'];
        return view('backend.admin.staff-documents.create', compact('staffProfile','categories'));
    }

    public function store(StoreStaffDocumentRequest $request, StaffProfile $staffProfile)
    {
        $this->authorizeProfile($staffProfile);

        $file = $request->file('file');
        $tenant = $this->tenantId();
        $path = $file->store("documents/tenant_{$tenant}/staff_profile_{$staffProfile->id}", 'public');

        $staffProfile->documents()->create([
            'tenant_id'   => $tenant,
            'category'    => $request->category,
            'filename'    => $file->getClientOriginalName(),
            'path'        => $path,
            'mime'        => $file->getMimeType() ?? 'application/octet-stream',
            'uploaded_by' => auth()->id(),
            'hash'        => hash_file('sha256', $file->getRealPath()),
        ]);

        return redirect()
            ->route('backend.admin.staff-profiles.documents.index', $staffProfile)
            ->with('success', 'Document uploaded.');
    }

    public function edit(StaffProfile $staffProfile, Document $document)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($document);
        abort_unless($document->owner_type === StaffProfile::class && (int)$document->owner_id === (int)$staffProfile->id, 404);

        $categories = ['DBS','Visa','Training Cert','Contract','Payroll','Reference','OH','ID','Other'];
        return view('backend.admin.staff-documents.edit', compact('staffProfile','document','categories'));
    }

    public function update(UpdateStaffDocumentRequest $request, StaffProfile $staffProfile, Document $document)
    {
        $this->authorizeProfile($staffProfile);
        $this->authorizeItem($document);
        abort_unless($document->owner_type === StaffProfile::class && (int)$document->owner_id === (int)$staffProfile->id, 404);

        $data = ['category' => $request->category];

        if ($request->hasFile('file')) {
            // delete old file (optional)
            if ($document->path && Storage::disk('public')->exists($document->path)) {
                Storage::disk('public')->delete($document->path);
            }
            $file = $request->file('file');
            $tenant = $this->tenantId();
            $path = $file->store("documents/tenant_{$tenant}/staff_profile_{$staffProfile->id}", 'public');
            $data += [
                'filename' => $file->getClientOriginalName(),
                'path'     => $path,
                'mime'     => $file->getMimeType() ?? 'application/octet-stream',
                'hash'     => hash_file('sha256', $file->getRealPath()),
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
        abort_unless($document->owner_type === StaffProfile::class && (int)$document->owner_id === (int)$staffProfile->id, 404);

        if ($document->path && Storage::disk('public')->exists($document->path)) {
            Storage::disk('public')->delete($document->path);
        }
        $document->delete();

        return back()->with('success', 'Document deleted.');
    }
}
