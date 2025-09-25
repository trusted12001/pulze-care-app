<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarePlanSection;
use App\Models\CarePlanGoal;
use Illuminate\Http\Request;

class CarePlanGoalController extends Controller
{
    public function store(Request $request, CarePlanSection $section)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'success_criteria' => ['nullable','string'],
            'target_date' => ['nullable','date'],
        ]);

        $order = (int) ($section->goals()->max('display_order') ?? 0) + 1;
        $section->goals()->create($data + ['display_order' => $order]);

        return back()->with('success','Goal added.');
    }

    public function update(Request $request, CarePlanGoal $goal)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'success_criteria' => ['nullable','string'],
            'target_date' => ['nullable','date'],
            'status' => ['nullable','string','in:open,in_progress,completed'],
        ]);

        $goal->update($data);
        return back()->with('success','Goal updated.');
    }

    public function destroy(CarePlanGoal $goal)
    {
        $goal->delete();
        return back()->with('success','Goal removed.');
    }
}
