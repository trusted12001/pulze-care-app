<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarePlanGoal;
use App\Models\CarePlanIntervention;
use Illuminate\Http\Request;

class CarePlanInterventionController extends Controller
{
    public function store(Request $request, CarePlanGoal $goal)
    {
        $data = $request->validate([
            'description' => ['required','string'],
            'frequency' => ['nullable','string','max:50'],
            'assigned_to_user_id' => ['nullable','exists:users,id'],
            'assigned_to_role' => ['nullable','string','max:50'],
            'link_to_assignment' => ['sometimes','boolean'],
        ]);

        $data['link_to_assignment'] = (bool) ($data['link_to_assignment'] ?? false);

        $goal->interventions()->create($data);
        return back()->with('success','Intervention added.');
    }

    public function update(Request $request, CarePlanIntervention $intervention)
    {
        $data = $request->validate([
            'description' => ['required','string'],
            'frequency' => ['nullable','string','max:50'],
            'assigned_to_user_id' => ['nullable','exists:users,id'],
            'assigned_to_role' => ['nullable','string','max:50'],
            'link_to_assignment' => ['sometimes','boolean'],
            'is_active' => ['sometimes','boolean'],
        ]);

        $intervention->update($data);
        return back()->with('success','Intervention updated.');
    }

    public function destroy(CarePlanIntervention $intervention)
    {
        $intervention->delete();
        return back()->with('success','Intervention removed.');
    }
}
