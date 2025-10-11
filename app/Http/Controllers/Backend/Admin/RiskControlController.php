<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiskAssessment;
use App\Models\RiskControl;
use App\Models\AssignmentTask;
use Illuminate\Http\Request;

class RiskControlController extends Controller
{
    public function store(Request $request, RiskAssessment $risk_assessment)
    {
        $data = $request->validate([
            'control_type'        => ['nullable','string','max:50'],
            'description'         => ['required','string'],
            'mandatory'           => ['sometimes','boolean'],
            'evidence_type'       => ['nullable','string','max:50'],
            'frequency'           => ['nullable','string','max:50'],
            'assigned_to_user_id' => ['nullable','exists:users,id'],
            'assigned_to_role'    => ['nullable','string','max:50'],
            'link_to_assignment'  => ['sometimes','boolean'],
        ]);

        $control = $risk_assessment->controls()->create([
            ...$data,
            'mandatory' => (bool) ($data['mandatory'] ?? false),
            'link_to_assignment' => (bool) ($data['link_to_assignment'] ?? false),
        ]);

        // Simple demo integration: create one pending task when linked
        if ($control->link_to_assignment) {
            AssignmentTask::firstOrCreate(
                ['risk_control_id' => $control->id],
                [
                    'title' => $control->description,
                    'service_user_id' => $risk_assessment->service_user_id,
                    'assignee_user_id' => $control->assigned_to_user_id,
                    'frequency' => $control->frequency,
                    'evidence_type' => $control->evidence_type,
                    'due_at' => now()->endOfDay(),
                    'status' => 'pending',
                ]
            );
        }

        return back()->with('success', 'Control added.');
    }

    public function update(Request $request, RiskControl $control)
    {
        $data = $request->validate([
            'control_type'        => ['nullable','string','max:50'],
            'description'         => ['required','string'],
            'mandatory'           => ['sometimes','boolean'],
            'evidence_type'       => ['nullable','string','max:50'],
            'frequency'           => ['nullable','string','max:50'],
            'assigned_to_user_id' => ['nullable','exists:users,id'],
            'assigned_to_role'    => ['nullable','string','max:50'],
            'link_to_assignment'  => ['sometimes','boolean'],
            'is_active'           => ['sometimes','boolean'],
            'effectiveness_rating'=> ['nullable','integer','between:1,5'],
        ]);

        $wasLinked = $control->link_to_assignment;
        $control->update($data);

        // Keep the demo task in sync
        if ($control->link_to_assignment) {
            $task = $control->task ?: new AssignmentTask(['risk_control_id' => $control->id]);
            $task->fill([
                'title' => $control->description,
                'service_user_id' => $control->assessment->service_user_id,
                'assignee_user_id' => $control->assigned_to_user_id,
                'frequency' => $control->frequency,
                'evidence_type' => $control->evidence_type,
                'due_at' => $task->due_at ?? now()->endOfDay(),
                'status' => $task->status ?? 'pending',
            ])->save();
        } elseif ($wasLinked && $control->task) {
            $control->task->delete();
        }

        return back()->with('success', 'Control updated.');
    }

    public function destroy(RiskControl $control)
    {
        if ($control->task) { $control->task->delete(); }
        $control->delete();
        return back()->with('success', 'Control removed.');
    }
}
