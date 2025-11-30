<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assignment\{StoreAssignmentRequest, StartAssignmentRequest, SubmitAssignmentRequest, VerifyAssignmentRequest};
use App\Models\{Assignment, AssignmentEvent, AssignmentEvidence};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class AssignmentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $q = Assignment::query()->with(['resident', 'location'])
            ->when($request->boolean('mine'), fn($qq) => $qq->where('assigned_to', $request->user()->id))
            ->when($date = $request->get('date'), function ($qq) use ($date) {
                if ($date === 'today') {
                    $qq->whereDate('window_start', '<=', now())->whereDate('window_end', '>=', now());
                }
            })
            ->orderBy('due_at');
        return response()->json($q->paginate(20));
    }


    public function store(StoreAssignmentRequest $request)
    {
        $data = $request->validated();
        $data['code'] = 'ASG-' . Str::upper(Str::random(6));
        $data['created_by'] = $request->user()->id;
        $data['status'] = 'scheduled';
        $a = Assignment::create($data);
        AssignmentEvent::create(['assignment_id' => $a->id, 'event' => 'scheduled', 'actor_id' => $request->user()->id, 'payload' => ['via' => 'api']]);
        return response()->json($a->fresh(), 201);
    }


    public function show(Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        return response()->json($assignment->load(['events.actor', 'evidence', 'signoffs']));
    }



    public function update(StoreAssignmentRequest $request, Assignment $assignment)
    {
        // Policy: assignments.update
        $this->authorize('update', $assignment);

        // Don't allow editing once it's already been "finalised"
        if (in_array($assignment->status, ['submitted', 'verified', 'closed'])) {
            return response()->json([
                'ok'      => false,
                'message' => 'Cannot edit an assignment that has already been submitted or verified.',
            ], 422);
        }

        $data = $request->validated();

        $assignment->update($data);

        AssignmentEvent::create([
            'assignment_id' => $assignment->id,
            'event'         => 'updated',
            'actor_id'      => $request->user()->id,
            'payload'       => $data,
        ]);

        return response()->json([
            'ok'         => true,
            'assignment' => $assignment->fresh(['resident', 'location']),
        ]);
    }


    /**
     * Soft delete (cancel) an assignment.
     */
    public function destroy(Request $request, Assignment $assignment)
    {
        // Policy: assignments.delete
        $this->authorize('delete', $assignment);

        // Optional: you may stop deletion if already in_progress/submitted/etc.
        if (in_array($assignment->status, ['in_progress', 'submitted', 'verified', 'closed'])) {
            return response()->json([
                'ok'      => false,
                'message' => 'Cannot delete an assignment that is already in progress or completed.',
            ], 422);
        }

        $assignment->delete();

        AssignmentEvent::create([
            'assignment_id' => $assignment->id,
            'event'         => 'deleted',
            'actor_id'      => $request->user()->id,
            'payload'       => [],
        ]);

        return response()->json(['ok' => true]);
    }


    public function start(StartAssignmentRequest $request, Assignment $assignment)
    {
        // ✅ Only the assignee can start
        abort_if(
            (int) $request->user()->id !== (int) $assignment->assigned_to,
            403,
            'Only the assignee can start this assignment.'
        );

        // validate status + window
        abort_if($assignment->status !== 'scheduled', 422, 'Cannot start: status not scheduled');

        $now = now();
        if ($assignment->window_start && $assignment->window_end) {
            abort_if(
                ! $now->between($assignment->window_start, $assignment->window_end),
                422,
                'Outside start window'
            );
        }

        // TODO: shift + GPS check (placeholder, integrate with your Shift + Geofence services)

        $assignment->update(['status' => 'in_progress']);

        AssignmentEvent::create([
            'assignment_id' => $assignment->id,
            'event'         => 'started',
            'actor_id'      => $request->user()->id,
            'payload'       => $request->only('lat', 'lng', 'accuracy'),
        ]);

        return response()->json(['ok' => true]);
    }



    public function submit(SubmitAssignmentRequest $request, Assignment $assignment)
    {
        // ✅ Only the assignee can submit
        abort_if(
            (int) $request->user()->id !== (int) $assignment->assigned_to,
            403,
            'Only the assignee can submit this assignment.'
        );

        abort_if($assignment->status !== 'in_progress', 422, 'Cannot submit: status not in_progress');

        DB::transaction(function () use ($request, $assignment) {
            // -------- 1) Store photo evidence safely (no mass-assignment) --------
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $file) {
                    $path = $file->store('assignments/' . $assignment->id, 'public');

                    $evidence = new AssignmentEvidence();
                    $evidence->assignment_id = $assignment->id;
                    $evidence->file_path     = $path;
                    $evidence->file_type     = $file->getClientMimeType();
                    $evidence->note          = $request->input('note');
                    $evidence->captured_at   = now();
                    $evidence->lat           = $request->input('lat');
                    $evidence->lng           = $request->input('lng');
                    $evidence->accuracy      = $request->input('accuracy');
                    $evidence->created_by    = $request->user()->id;
                    $evidence->save();
                }
            }

            // -------- 2) Safely update metadata + answers --------
            // Make sure we always merge arrays, even if metadata is null or string
            $existingMeta = $assignment->metadata;

            if (!is_array($existingMeta)) {
                $existingMeta = $existingMeta ? (array) $existingMeta : [];
            }

            $newMeta = array_merge($existingMeta, [
                'answers' => $request->input('answers', []),
            ]);

            $assignment->update([
                'status'   => 'submitted',
                'metadata' => $newMeta,
            ]);

            // -------- 3) Log event --------
            AssignmentEvent::create([
                'assignment_id' => $assignment->id,
                'event'         => 'submitted',
                'actor_id'      => $request->user()->id,
                'payload'       => [
                    'note' => $request->input('note'),
                ],
            ]);
        });

        return response()->json(['ok' => true]);
    }



    public function verify(Request $request, Assignment $assignment)
    {
        // 1) Authorise
        $this->authorize('verify', $assignment);

        // 2) Only allow verifying submitted assignments
        if ($assignment->status !== 'submitted') {
            return redirect()
                ->route('backend.admin.assignments.show', $assignment)
                ->with('warning', 'Only submitted assignments can be verified.');
        }

        // 3) Validate optional comment
        $data = $request->validate([
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($assignment, $request, $data) {
            // If you have an AssignmentVerification model, you can log here.
            // For now we just update the assignment.

            $assignment->status      = 'verified';   // or 'closed' if that’s your final state
            $assignment->verified_by = $request->user()->id ?? null;
            $assignment->verified_at = now();

            // If you have a `verification_comment` column, set it:
            if (array_key_exists('comment', $data)) {
                $assignment->verification_comment = $data['comment'];
            }

            $assignment->save();
        });

        return redirect()
            ->route('backend.admin.assignments.show', $assignment)
            ->with('success', 'Assignment verified and closed successfully.');
    }


    public function timeline(Assignment $assignment)
    {
        $this->authorize('view', $assignment);
        return response()->json($assignment->events()->with('actor')->latest()->get());
    }
}
