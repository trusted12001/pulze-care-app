<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShiftTemplate;
use App\Models\Location;
use Illuminate\Http\Request;

class ShiftTemplateController extends Controller
{
    public function index()
    {
        $templates = ShiftTemplate::with('location')->orderBy('name')->paginate(20);
        $locations = Location::orderBy('name')->get();
        return view('backend.admin.shifts.templates.index', compact('templates', 'locations'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['skills_json']       = $this->parseSkills($request->input('skills'));
        $data['days_of_week_json'] = $this->parseDays($request->input('days_of_week', []));
        ShiftTemplate::create($data);
        return back()->with('success', 'Template created.');
    }

    public function edit(ShiftTemplate $shift_template)
    {
        $locations = Location::orderBy('name')->get();
        return view('backend.admin.shifts.templates.edit', compact('shift_template', 'locations'));
    }

    public function update(Request $request, ShiftTemplate $shift_template)
    {
        $data = $this->validateData($request);
        $data['skills_json']       = $this->parseSkills($request->input('skills'));
        $data['days_of_week_json'] = $this->parseDays($request->input('days_of_week', []));
        $shift_template->update($data);
        return redirect()->route('backend.admin.shift-templates.index')->with('success', 'Template updated.');
    }

    public function destroy(ShiftTemplate $shift_template)
    {
        $shift_template->delete();
        return back()->with('success', 'Template removed.');
    }

    public function toggle(ShiftTemplate $shift_template)
    {
        $shift_template->active = !$shift_template->active;
        $shift_template->save();
        return back()->with('success', 'Template ' . ($shift_template->active ? 'activated' : 'deactivated') . '.');
    }

    public function duplicate(Request $request, ShiftTemplate $shift_template)
    {
        $targetLocationId = $request->input('location_id', $shift_template->location_id);
        $copy = $shift_template->replicate();
        $copy->location_id = $targetLocationId;
        $copy->name = $shift_template->name . ' (copy)';
        $copy->push();
        return back()->with('success', 'Template duplicated.');
    }

    // --- helpers
    private function validateData(Request $request): array
    {
        // normalize checkbox fields so they always become 0/1
        $request->merge([
            'paid_flag' => $request->boolean('paid_flag'),
            'active'    => $request->boolean('active'),
        ]);

        $data = $request->validate([
            'location_id'   => ['required', 'exists:locations,id'],
            'name'          => ['required', 'string', 'max:100'],
            'role'          => ['required', 'string', 'max:50'],
            'start_time'    => ['required', 'date_format:H:i'],
            'end_time'      => ['required', 'date_format:H:i'],

            // ✅ make them optional in the form but NEVER NULL in the DB
            'break_minutes' => ['sometimes', 'integer', 'min:0'],
            'headcount'     => ['sometimes', 'integer', 'min:1'],

            'paid_flag'     => ['sometimes', 'boolean'],
            'active'        => ['sometimes', 'boolean'],
            'notes'         => ['nullable', 'string', 'max:5000'],
        ]);

        // ✅ enforce defaults if missing
        $data['break_minutes'] = (int) ($data['break_minutes'] ?? 0);
        $data['headcount']     = (int) ($data['headcount'] ?? 1);

        return $data;
    }

    private function parseSkills(?string $skills): ?array
    {
        if (!$skills) return null;
        return collect(explode(',', $skills))
            ->map(fn($v) => trim($v))
            ->filter()
            ->values()
            ->toArray();
    }

    private function parseDays($days): ?array
    {
        if (empty($days)) return null; // null means All days
        $valid = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
        return collect($days)->map(fn($d) => strtolower($d))->filter(fn($d) => in_array($d, $valid))->values()->toArray();
    }
}
