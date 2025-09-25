<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiskAssessment;
use App\Models\RiskType;
use Illuminate\Http\Request;

class RiskInsightsController extends Controller
{
    public function index(Request $request)
    {
        $band = $request->get('band');
        $type = $request->get('type');
        $overdue = $request->boolean('overdue');

        $q = RiskAssessment::query()->with(['serviceUser','riskType'])->where('status','active');
        if ($band)   $q->where('risk_band', $band);
        if ($type)   $q->where('risk_type_id', $type);
        if ($overdue) $q->overdue();

        $list = $q->latest()->paginate(20)->withQueryString();

        // Stats
        $totals = [
            'all'   => RiskAssessment::where('status','active')->count(),
            'high'  => RiskAssessment::where('status','active')->where('risk_band','high')->count(),
            'med'   => RiskAssessment::where('status','active')->where('risk_band','medium')->count(),
            'low'   => RiskAssessment::where('status','active')->where('risk_band','low')->count(),
            'overdue' => RiskAssessment::where('status','active')->overdue()->count(),
        ];

        $types = RiskType::orderBy('name')->get();

        return view('backend.admin.insights.risks.index', compact('list','totals','types','band','type','overdue'));
    }
}
