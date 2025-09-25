<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarePlan;

class CarePlanPrintController extends Controller
{
    public function __invoke(CarePlan $care_plan)
    {
        $care_plan->load(['serviceUser','author','approver','sections.goals.interventions.assignee']);
        return view('backend.admin.care-plans.print', compact('care_plan'));
    }
}
