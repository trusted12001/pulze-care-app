<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarePlan;
use Illuminate\Http\Request;

class CarePlanReviewController extends Controller
{
    public function store(Request $request, CarePlan $care_plan)
    {
        $data = $request->validate([
            'review_date' => ['nullable','date'],
            'reason'      => ['nullable','string','max:50'],
            'summary'     => ['nullable','string'],
            'next_review_date_suggested' => ['nullable','date'],
            'review_frequency_suggested' => ['nullable','string','max:50'],
            'bump_version' => ['sometimes','boolean'],
            'change_note'  => ['nullable','string','max:255'],
        ]);

        $data['reviewed_by'] = $request->user()->id;

        $care_plan->reviews()->create($data);

        // Apply suggested schedule
        if (!empty($data['next_review_date_suggested'])) {
            $care_plan->next_review_date = $data['next_review_date_suggested'];
        }
        if (!empty($data['review_frequency_suggested'])) {
            $care_plan->review_frequency = $data['review_frequency_suggested'];
        }
        $care_plan->save();

        // Optional version bump at review time
        if ($request->boolean('bump_version')) {
            $care_plan->bumpVersion($request->user()->id, $data['change_note'] ?? 'Version bump from review', true);
        }

        return back()->with('success', 'Review logged'.($request->boolean('bump_version') ? ' and version updated.' : '.'));
    }
}
