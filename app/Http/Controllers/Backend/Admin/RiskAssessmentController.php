<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiskAssessment;           // Item rows (keep)
use App\Models\RiskAssessmentProfile;    // NEW parent
use App\Models\ServiceUser;
use Illuminate\Contracts\Database\Query\Builder as DBBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\RiskType;
use Barryvdh\DomPDF\Facade\Pdf; // ensure barryvdh/laravel-dompdf is installed

class RiskAssessmentController extends Controller
{
    /**
     * INDEX: Cumulative (one row per Service User) → now based on PROFILES.
     */
    public function index(Request $request)
    {
        $search        = trim((string) $request->get('search'));
        $serviceUserId = $request->get('service_user_id');
        $status        = $request->get('status'); // draft|active|archived or null

        $serviceUsers = ServiceUser::query()
            ->orderBy('last_name')->orderBy('first_name')
            ->get(['id','first_name','last_name']);

        // Latest profile per service_user_id after filters
        $latestIdsSub = DB::table('risk_assessment_profiles as rap')
            ->selectRaw('MAX(rap.id) as max_id')
            ->when($serviceUserId, fn (DBBuilder $q) => $q->where('rap.service_user_id', $serviceUserId))
            ->when($status,        fn (DBBuilder $q) => $q->where('rap.status', $status))
            ->when($search,        fn (DBBuilder $q) => $q->where('rap.title', 'like', "%{$search}%"))
            ->groupBy('rap.service_user_id');

        $profiles = RiskAssessmentProfile::query()
            ->with(['serviceUser:id,first_name,last_name'])
            ->joinSub($latestIdsSub, 'latest', 'risk_assessment_profiles.id', '=', 'latest.max_id')
            ->orderByDesc('risk_assessment_profiles.created_at')
            ->select('risk_assessment_profiles.*')
            ->paginate(15)
            ->withQueryString();

        return view('backend.admin.risk-assessments.index', [
            'assessments'  => $profiles,     // keep variable name to avoid blade changes
            'serviceUsers' => $serviceUsers,
        ]);
    }

    /**
     * CREATE: create a new PROFILE (header/basic info).
     */
    public function create()
    {
        $serviceUsers = ServiceUser::query()
            ->orderBy('last_name')->orderBy('first_name')
            ->get(['id','first_name','last_name']);

        return view('backend.admin.risk-assessments.create', [
            'serviceUsers' => $serviceUsers,
        ]);
    }

    /**
     * STORE: persist a PROFILE (header/basic info).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'service_user_id'  => ['required','exists:service_users,id'],
            'title'            => ['required','string','max:255'],
            'start_date'       => ['nullable','date'],
            'next_review_date' => ['nullable','date','after_or_equal:start_date'],
            'review_frequency' => ['nullable','string','max:100'],
            'summary'          => ['nullable','string'],
            'action'           => ['nullable', Rule::in(['save','save_draft','publish'])],
        ]);

        $action = $data['action'] ?? 'save_draft';
        $status = match ($action) {
            'publish' => RiskAssessmentProfile::STATUS_ACTIVE,
            'save'    => $request->input('status', RiskAssessmentProfile::STATUS_DRAFT),
            default   => RiskAssessmentProfile::STATUS_DRAFT,
        };

        $profile = RiskAssessmentProfile::create([
            'service_user_id'  => $data['service_user_id'],
            'title'            => $data['title'],
            'status'           => $status,
            'start_date'       => $data['start_date'] ?? null,
            'next_review_date' => $data['next_review_date'] ?? null,
            'review_frequency' => $data['review_frequency'] ?? null,
            'summary'          => $data['summary'] ?? null,
            'created_by_id'    => auth()->id(),
            'updated_by_id'    => auth()->id(),
        ]);

        return redirect()
            ->route('backend.admin.risk-assessments.show', $profile) // same route name, now bound to Profile
            ->with('status', $status === RiskAssessmentProfile::STATUS_ACTIVE
                ? 'Risk Assessment created and published.'
                : 'Risk Assessment saved as draft.');
    }

    /**
     * SHOW: open PROFILE; later render its item list (`$profile->assessments`).
     */
    public function show(RiskAssessmentProfile $riskAssessment)
    {
        // Load relations
        $riskAssessment->load([
            'serviceUser',
            'assessments.riskType',   // each item knows its type
            'creator', 'updater',
        ]);

        // All risk types (for accordion list). If you only want types present on this profile, filter below instead.
        $riskTypes = RiskType::query()->orderBy('name')->get(['id','name']);

        // Group the profile’s items by risk_type_id for fast rendering
        $itemsByType = $riskAssessment->assessments->groupBy('risk_type_id');

        return view('backend.admin.risk-assessments.show', [
            'assessment'  => $riskAssessment,
            'riskTypes'   => $riskTypes,
            'itemsByType' => $itemsByType,
        ]);
    }

    /**
     * EDIT PROFILE
     */
    // ✅ EDIT should accept a RiskAssessmentProfile, not RiskAssessment
    public function edit(RiskAssessmentProfile $riskAssessment)
    {
        $serviceUsers = ServiceUser::query()
            ->orderBy('last_name')->orderBy('first_name')
            ->get(['id','first_name','last_name']);

        return view('backend.admin.risk-assessments.edit', [
            'riskAssessment' => $riskAssessment,
            'serviceUsers'   => $serviceUsers,
        ]);
    }
    /**
     * UPDATE PROFILE
     */
    // ✅ UPDATE should accept a RiskAssessmentProfile and use its columns
    public function update(Request $request, RiskAssessmentProfile $riskAssessment)
    {
        $data = $request->validate([
            'title'            => ['required','string','max:255'],
            'service_user_id'  => ['required','integer','exists:service_users,id'],
            'start_date'       => ['nullable','date'],              // profile column
            'next_review_date' => ['nullable','date','after_or_equal:start_date'],
            'review_frequency' => ['nullable','string','max:100'],
            'summary'          => ['nullable','string'],
            'status'           => ['nullable','in:draft,active,archived'],
        ]);

        $riskAssessment->fill($data);
        $riskAssessment->updated_by_id = auth()->id();
        $riskAssessment->save();

        return redirect()
            ->route('backend.admin.risk-assessments.index')
            ->with('success', 'Risk Assessment updated successfully.');
    }
    /**
     * DESTROY PROFILE (soft delete)
     */
    // ✅ DESTROY should accept a RiskAssessmentProfile
    public function destroy(RiskAssessmentProfile $riskAssessment)
    {
        $riskAssessment->delete();

        return redirect()
            ->route('backend.admin.risk-assessments.index')
            ->with('success', 'Risk Assessment deleted successfully.');
    }


    /**
     * Print/PDF of the risk assessment profile + its items
     */
    // ✅ PRINT is already correct; ensure param name matches {riskAssessment}
    public function print(RiskAssessmentProfile $riskAssessment)
    {
        $riskAssessment->load(['serviceUser', 'assessments.riskType', 'creator', 'updater']);

        $pdf = Pdf::loadView('backend.admin.risk-assessments.print', [
            'assessment' => $riskAssessment,
        ]);

        $file = 'Risk_Assessment_'.$riskAssessment->id.'.pdf';
        return $pdf->stream($file);
    }
}
