<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarePlan;
use App\Models\ServiceUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CarePlanController extends Controller
{
    public function index(Request $request)
    {
        $plans = CarePlan::query()
            ->with(['serviceUser','author','approver'])
            ->when($request->service_user_id, fn($q)=>$q->where('service_user_id',$request->service_user_id))
            ->when($request->status, fn($q)=>$q->where('status',$request->status))
            ->when($request->search, fn($q)=>$q->where('title','like','%'.$request->search.'%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $serviceUsers = ServiceUser::orderBy('first_name')->limit(200)->get();

        return view('backend.admin.care-plans.index', compact('plans','serviceUsers'));
    }

    public function create()
    {
        $serviceUsers = ServiceUser::orderBy('first_name')->limit(200)->get();
        return view('backend.admin.care-plans.create', compact('serviceUsers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_user_id' => ['required','exists:service_users,id'],
            'title'           => ['required','string','max:255'],
            'start_date'      => ['nullable','date'],
            'next_review_date'=> ['nullable','date'],
            'review_frequency'=> ['nullable','string','max:50'],
            'summary'         => ['nullable','string'],
            'action'          => ['nullable', Rule::in(['save_draft','publish'])],
        ]);

        $plan = new CarePlan($data);
        $plan->status   = 'draft';
        $plan->author_id = $request->user()->id;
        $plan->save();

        // Auto-seed standard sections
        $plan->seedDefaultSections();

        if (($data['action'] ?? null) === 'publish') {
            $plan->status = 'active';
            $plan->approved_by = $request->user()->id;
            $plan->approved_at = now();
            $plan->save();
        }

        return redirect()->route('backend.admin.care-plans.show', $plan)
            ->with('success','Care plan created.');
    }

    public function show(CarePlan $care_plan)
    {
        $care_plan->load([
            'serviceUser','author','approver',
            'sections.goals.interventions.assignee'
        ]);

        return view('backend.admin.care-plans.show', compact('care_plan'));
    }

    public function edit(CarePlan $care_plan)
    {
        $serviceUsers = ServiceUser::orderBy('first_name')->limit(200)->get();
        return view('backend.admin.care-plans.edit', compact('care_plan','serviceUsers'));
    }

    public function update(Request $request, CarePlan $care_plan)
    {
        $data = $request->validate([
            'service_user_id' => ['required','exists:service_users,id'],
            'title'           => ['required','string','max:255'],
            'start_date'      => ['nullable','date'],
            'next_review_date'=> ['nullable','date'],
            'review_frequency'=> ['nullable','string','max:50'],
            'summary'         => ['nullable','string'],
            'status'          => ['required', Rule::in(['draft','active','archived'])],
            'action'          => ['nullable', Rule::in(['save','publish'])],
        ]);

        $care_plan->fill($data);

        if (($data['action'] ?? null) === 'publish' && $care_plan->status !== 'active') {
            $care_plan->status = 'active';
            $care_plan->approved_by = $request->user()->id;
            $care_plan->approved_at = now();
        }

        $care_plan->save();

        return redirect()->route('backend.admin.care-plans.show', $care_plan)
            ->with('success','Care plan updated.');
    }

    public function destroy(CarePlan $care_plan)
    {
        $care_plan->delete();
        return redirect()->route('backend.admin.care-plans.index')->with('success','Care plan moved to trash.');
    }
}
