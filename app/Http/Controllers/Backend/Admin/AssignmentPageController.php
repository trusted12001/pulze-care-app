<?php

namespace App\Http\Controllers\Backend\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Assignment, AssignmentEvent, Location, ServiceUser as Resident, User};
use App\Models\ServiceUser;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Assignment\StoreAssignmentRequest;
use Illuminate\Support\Str;


class AssignmentPageController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        // My assignments (only those assigned to the logged-in user)
        $mine = Assignment::with(['resident', 'location'])
            ->where('assigned_to', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(15); // uses ?page=

        // All assignments (tenant-wide / for admin overview)
        $all = Assignment::with(['resident', 'location', 'evidence'])
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'all_page'); // uses ?all_page=

        // Verification queue (only if user has permission)
        $queue = $request->user()->can('assignments.verify')
            ? Assignment::where('status', 'submitted')
            ->latest()
            ->paginate(10, ['*'], 'queue_page') // uses ?queue_page=
            : collect();

        return view('backend.admin.assignments.index', compact('mine', 'all', 'queue'));
    }

    public function create()
    {
        $locations = Location::orderBy('name')->get(['id', 'name']);

        // Service Users (aka Residents)
        $residents = Resident::orderBy('last_name')->get(['id', 'first_name', 'last_name']);

        // Staff: build a display name safely even if some users miss first/last names
        $staff = User::query()
            ->select([
                'id',
                DB::raw("TRIM(CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,''))) as display_name"),
                'email',
            ])
            ->orderBy('display_name')
            ->get();

        return view('backend.admin.assignments.create', compact('locations', 'residents', 'staff'));
    }

    public function show(Assignment $assignment)
    {
        $assignment->load(['resident', 'location', 'evidence']);
        return view('backend.admin.assignments.show', compact('assignment'));
    }


    public function edit(Assignment $assignment)
    {
        // Policy: user must be allowed to update this assignment
        $this->authorize('update', $assignment);

        // Dropdown data (you can refine later)
        $locations = Location::orderBy('name')->get();
        $residents = ServiceUser::orderBy('first_name')->get();
        $users     = User::orderBy('first_name')->get();

        return view('backend.admin.assignments.edit', [
            'assignment' => $assignment,
            'locations'  => $locations,
            'residents'  => $residents,
            'users'      => $users,
        ]);
    }



    public function update(StoreAssignmentRequest $request, Assignment $assignment)
    {
        $this->authorize('update', $assignment);

        $data = $request->validated();

        // We do NOT touch code/created_by/status here – only the editable fields
        unset($data['code'], $data['created_by'], $data['status']);

        $assignment->update($data);

        return redirect()
            ->route('backend.admin.assignments.show', $assignment)
            ->with('success', 'Assignment updated successfully.');
    }



    public function destroy(Assignment $assignment)
    {
        $this->authorize('delete', $assignment);

        $assignment->delete(); // if you later add SoftDeletes, this will respect it

        return redirect()
            ->route('backend.admin.assignments.index')
            ->with('success', 'Assignment deleted successfully.');
    }




    public function verify(\App\Models\Assignment $assignment)
    {
        $assignment->load(['evidence', 'resident', 'location', 'assignee']);
        return view('backend.admin.assignments.verify', compact('assignment'));
    }

    public function store(StoreAssignmentRequest $request)
    {
        $data = $request->validated();

        $data['code']       = 'ASG-' . Str::upper(Str::random(6));
        $data['created_by'] = $request->user()->id;
        $data['status']     = 'scheduled';

        DB::beginTransaction();
        try {
            $assignment = Assignment::create($data);

            AssignmentEvent::create([
                'assignment_id' => $assignment->id,
                'event'         => 'scheduled',
                'actor_id'      => $request->user()->id,
                'payload'       => ['via' => 'web'],
            ]);

            DB::commit();

            // ✅ flash for next request (even if we return JSON)
            session()->flash('success', 'Assignment created successfully.');

            if ($request->expectsJson()) {
                return response()->json([
                    'message'    => 'Assignment created.',
                    'assignment' => $assignment->load('location', 'resident'),
                ], 201);
            }

            return redirect()
                ->route('backend.admin.assignments.index')
                ->with('success', 'Assignment created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to create assignment.',
                ], 500);
            }

            throw $e;
        }
    }
}
