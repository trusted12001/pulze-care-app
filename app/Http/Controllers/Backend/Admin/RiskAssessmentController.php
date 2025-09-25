<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiskAssessment;
use App\Models\RiskType;
use App\Models\ServiceUser;
use App\Enums\RiskStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RiskAssessmentController extends Controller
{
    public function index(Request $request)
    {
        $q = RiskAssessment::query()
            ->with(['serviceUser','riskType','creator','approver'])
            ->when($request->service_user_id, fn($qr) => $qr->where('service_user_id', $request->service_user_id))
            ->when($request->risk_band, fn($qr) => $qr->where('risk_band', $request->risk_band))
            ->when($request->search, fn($qr) => $qr->where('title', 'like', '%'.$request->search.'%'))
            ->latest();

        $assessments = $q->paginate(15)->withQueryString();

        return view('backend.admin.risk-assessments.index', [
            'assessments'   => $assessments,
            'serviceUsers'  => ServiceUser::orderBy('first_name')->limit(200)->get(),
        ]);
    }

    public function create()
    {
        return view('backend.admin.risk-assessments.create', [
            'serviceUsers' => ServiceUser::orderBy('first_name')->limit(200)->get(),
            'riskTypes'    => RiskType::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_user_id' => ['required','exists:service_users,id'],
            'risk_type_id'    => ['required','exists:risk_types,id'],
            'title'           => ['required','string','max:255'],
            'context'         => ['nullable','string'],
            'likelihood'      => ['required','integer','between:1,5'],
            'severity'        => ['required','integer','between:1,5'],
            'next_review_date'=> ['nullable','date'],
            'review_frequency'=> ['nullable','string','max:50'],
            'action'          => ['nullable', Rule::in(['save_draft','publish'])],
        ]);

        $assessment = new RiskAssessment($data);
        $assessment->created_by = $request->user()->id;
        $assessment->status = RiskStatus::Draft->value;
        $assessment->setScoreAndBand();

        if (($data['action'] ?? null) === 'publish') {
            $assessment->markApproved($request->user()->id);
        }

        $assessment->save();

        return redirect()
            ->route('backend.admin.risk-assessments.index')
            ->with('success', 'Risk assessment created.');
    }

    public function show(RiskAssessment $risk_assessment)
    {
        $risk_assessment->load(['serviceUser','riskType','creator','approver']);
        return view('backend.admin.risk-assessments.show', compact('risk_assessment'));
    }

    public function edit(RiskAssessment $risk_assessment)
    {
        return view('backend.admin.risk-assessments.edit', [
            'assessment'   => $risk_assessment,
            'serviceUsers' => ServiceUser::orderBy('first_name')->limit(200)->get(),
            'riskTypes'    => RiskType::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, RiskAssessment $risk_assessment)
    {
        $data = $request->validate([
            'service_user_id' => ['required','exists:service_users,id'],
            'risk_type_id'    => ['required','exists:risk_types,id'],
            'title'           => ['required','string','max:255'],
            'context'         => ['nullable','string'],
            'likelihood'      => ['required','integer','between:1,5'],
            'severity'        => ['required','integer','between:1,5'],
            'next_review_date'=> ['nullable','date'],
            'review_frequency'=> ['nullable','string','max:50'],
            'status'          => ['required', Rule::in(['draft','active','archived'])],
            'action'          => ['nullable', Rule::in(['save','publish'])],
        ]);

        $risk_assessment->fill($data);
        $risk_assessment->setScoreAndBand();

        if (($data['action'] ?? null) === 'publish' && $risk_assessment->status !== 'active') {
            $risk_assessment->markApproved($request->user()->id);
        }

        $risk_assessment->save();

        return redirect()
            ->route('backend.admin.risk-assessments.index')
            ->with('success', 'Risk assessment updated.');
    }

    public function destroy(RiskAssessment $risk_assessment)
    {
        $risk_assessment->delete();
        return back()->with('success', 'Risk assessment moved to trash.');
    }
}
