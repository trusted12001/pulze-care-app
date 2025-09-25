<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiskAssessment;
use Illuminate\Http\Request;

class RiskReviewController extends Controller
{
    public function store(Request $request, RiskAssessment $risk_assessment)
    {
        $data = $request->validate([
            'review_date'    => ['nullable','date'],
            'reason'         => ['nullable','string','max:50'],
            'likelihood_new' => ['nullable','integer','between:1,5'],
            'severity_new'   => ['nullable','integer','between:1,5'],
            'recommendations'=> ['nullable','string'],
            'outcome'        => ['nullable','string','max:50'],
        ]);

        if (isset($data['likelihood_new'], $data['severity_new'])) {
            $score = (int)$data['likelihood_new'] * (int)$data['severity_new'];
            $band  = $score >= 15 ? 'high' : ($score >= 6 ? 'medium' : 'low');

            $data['score_new'] = $score;
            $data['band_new']  = $band;

            // Apply to assessment
            $risk_assessment->likelihood = (int)$data['likelihood_new'];
            $risk_assessment->severity   = (int)$data['severity_new'];
            $risk_assessment->risk_score = $score;
            $risk_assessment->risk_band  = $band;
            $risk_assessment->save();
        }

        $data['reviewed_by'] = $request->user()->id;
        $risk_assessment->reviews()->create($data);

        return back()->with('success', 'Risk re-scored and review logged.');
    }
}
