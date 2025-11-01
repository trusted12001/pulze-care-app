<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiskAssessment;
use App\Models\RiskAssessmentProfile;
use App\Models\RiskType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RiskItemController extends Controller
{
    public function create(RiskAssessmentProfile $profile)
    {
        $riskTypes = class_exists(RiskType::class)
            ? RiskType::query()->orderBy('name')->get(['id','name'])
            : collect();

        // Preselect from query (?risk_type_id=)
        $selectedTypeId = request('risk_type_id');

        return view('backend.admin.risk-items.create', [
            'profile'        => $profile,
            'riskTypes'      => $riskTypes,
            'selectedTypeId' => $selectedTypeId,
        ]);
    }

    public function store(Request $request, RiskAssessmentProfile $profile)
    {
        $data = $request->validate([
            'risk_type_id' => ['nullable','exists:risk_types,id'],
            'title'        => ['nullable','string','max:255'],
            'context'      => ['required','string','max:2000'],
            'likelihood'   => ['required','integer','min:1','max:5'],
            'severity'     => ['required','integer','min:1','max:5'],
            'status'       => ['nullable', Rule::in(['draft','active','archived'])],
        ]);

        RiskAssessment::create([
            'risk_assessment_profile_id' => $profile->id,
            'service_user_id'            => $profile->service_user_id, // handy for legacy queries
            'risk_type_id'               => $data['risk_type_id'] ?? null,
            'title'                      => $data['title'] ?? null,
            'context'                    => $data['context'],
            'likelihood'                 => $data['likelihood'],
            'severity'                   => $data['severity'],
            'status'                     => $data['status'] ?? 'draft',
            'created_by'                 => auth()->id(),
        ]);

        return redirect()
            ->route('backend.admin.risk-assessments.show', $profile)
            ->with('status', 'Risk item added.');
    }
}
