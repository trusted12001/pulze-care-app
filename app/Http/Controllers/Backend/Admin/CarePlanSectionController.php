<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarePlan;
use App\Models\CarePlanSection;
use Illuminate\Http\Request;

class CarePlanSectionController extends Controller
{
    public function store(Request $request, CarePlan $care_plan)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string'],
        ]);

        $order = (int) ($care_plan->sections()->max('display_order') ?? 0) + 1;

        $care_plan->sections()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'display_order' => $order,
        ]);

        return back()->with('success','Section added.');
    }

    public function update(Request $request, CarePlanSection $section)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'is_active' => ['sometimes','boolean'],
        ]);

        $section->update($data);
        return back()->with('success','Section updated.');
    }

    public function destroy(CarePlanSection $section)
    {
        $section->delete();
        return back()->with('success','Section removed.');
    }
}
