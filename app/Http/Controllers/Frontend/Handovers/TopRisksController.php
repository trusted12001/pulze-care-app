<?php

namespace App\Http\Controllers\Frontend\Handovers;

use App\Http\Controllers\Controller;
use App\Models\ServiceUser;

class TopRisksController extends Controller
{
    public function __invoke(ServiceUser $service_user)
    {
        $risks = $service_user->riskAssessments()
            ->where('status','active')
            ->orderByRaw("FIELD(risk_band,'high','medium','low')")
            ->orderByDesc('risk_score')
            ->limit(5)
            ->get();

        return view('frontend.carer.partials._top_risks', compact('service_user','risks'));
    }
}
