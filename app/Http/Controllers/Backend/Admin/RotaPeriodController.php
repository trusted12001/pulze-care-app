<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\RotaPeriod;
use App\Models\ShiftTemplate;
use App\Models\Shift;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Models\Timesheet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;


class RotaPeriodController extends Controller
{
    public function index(Request $request)
    {
        $periods = RotaPeriod::with('location')->latest()->paginate(20);
        $locations = Location::orderBy('name')->get();
        return view('backend.admin.shifts.periods.index', compact('periods', 'locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'start_date'  => ['required', 'date'],
            'end_date'    => [
                'required',
                'date',
                'after_or_equal:start_date',

                // ✅ prevent exact duplicate period for same location
                Rule::unique('rota_periods')->where(function ($q) use ($request) {
                    return $q->where('location_id', $request->location_id)
                        ->whereDate('start_date', $request->start_date)
                        ->whereDate('end_date', $request->end_date);
                }),
            ],
        ], [
            'end_date.unique' => 'This rota period already exists for the selected location and dates.',
        ]);

        $data['generated_by'] = $request->user()->id;
        $data['status'] = $data['status'] ?? 'draft';

        $period = RotaPeriod::create($data);

        return redirect()
            ->route('backend.admin.rota-periods.show', $period)
            ->with('success', 'Period created.');
    }

    public function show(RotaPeriod $rota_period)
    {
        $rota_period->load('location', 'shifts.assignments.staff');

        $templates = ShiftTemplate::where('location_id', $rota_period->location_id)
            ->where('active', true)
            ->orderBy('name')
            ->get();

        // Load all users that are staff
        $users = User::orderBy('first_name')->orderBy('last_name')->get();

        return view('backend.admin.shifts.periods.show', compact('rota_period', 'templates', 'users'));
    }


    // Generate shifts from templates across the period (simple headcount=1 demo)
    public function generate(Request $request, RotaPeriod $rota_period)
    {
        // Block regenerate if shifts already exist
        if ($rota_period->shifts()->exists()) {
            return back()->with('error', 'This rota-period already has shifts. Delete existing Rota Period(s) first if you want to re-generate shifts.');
        }

        $templates = ShiftTemplate::query()
            ->where('location_id', $rota_period->location_id)
            ->where('active', true)
            ->orderBy('name')
            ->get();

        if ($templates->isEmpty()) {
            return back()->with('error', 'No active shift templates found for this location.');
        }

        $created = 0;

        // Debug counters (so user always gets feedback)
        $skippedByDayMismatch = 0;
        $skippedMissingTimes  = 0;
        $skippedInvalidHeadcount = 0;

        try {
            DB::transaction(function () use (
                $rota_period,
                $templates,
                &$created,
                &$skippedByDayMismatch,
                &$skippedMissingTimes,
                &$skippedInvalidHeadcount
            ) {
                $startDate = $rota_period->start_date->copy()->startOfDay();
                $endDate   = $rota_period->end_date->copy()->startOfDay();

                for ($day = $startDate->copy(); $day->lte($endDate); $day->addDay()) {

                    // mon/tue/wed/thu/fri/sat/sun
                    $dow = strtolower($day->format('D'));

                    foreach ($templates as $t) {

                        // ✅ respect days_of_week_json if set
                        $days = $t->days(); // ['mon','tue',...] or null
                        if (is_array($days) && !in_array($dow, $days, true)) {
                            $skippedByDayMismatch++;
                            continue;
                        }

                        $startTime = $t->start_time;
                        $endTime   = $t->end_time;

                        if (!$startTime || !$endTime) {
                            $skippedMissingTimes++;
                            continue;
                        }

                        $startAt = $day->copy()->setTimeFromTimeString($startTime);
                        $endAt   = $day->copy()->setTimeFromTimeString($endTime);

                        // Overnight shift -> end next day
                        if ($endAt->lte($startAt)) {
                            $endAt = $endAt->addDay();
                        }

                        $role = $t->role ?: ($t->name ?: 'Shift');

                        $headcount = (int) ($t->headcount ?? 1);
                        if ($headcount < 1) {
                            $skippedInvalidHeadcount++;
                            $headcount = 1;
                        }

                        for ($i = 1; $i <= $headcount; $i++) {
                            Shift::create([
                                'rota_period_id' => $rota_period->id,
                                'location_id'    => $rota_period->location_id,
                                'role'           => $role,
                                'start_at'       => $startAt,
                                'end_at'         => $endAt,
                                'break_minutes'  => (int) ($t->break_minutes ?? 0),
                                'skills_json'    => $t->skills_json ?? null,
                                'status'         => 'draft',
                                'notes'          => $t->notes ?? null,
                                'position_index' => $i,
                            ]);

                            $created++;
                        }
                    }
                }
            });

            // ✅ Always return a message (even when created = 0)
            if ($created > 0) {
                return back()->with('success', "Generated {$created} shifts from templates.");
            }

            $reasons = [];

            if ($skippedByDayMismatch > 0) {
                $reasons[] = "{$skippedByDayMismatch} skipped because template days_of_week did not match the rota dates.";
            }
            if ($skippedMissingTimes > 0) {
                $reasons[] = "{$skippedMissingTimes} skipped because templates are missing start_time or end_time.";
            }
            if ($skippedInvalidHeadcount > 0) {
                $reasons[] = "{$skippedInvalidHeadcount} templates had headcount < 1 (auto-corrected to 1).";
            }

            $reasonText = $reasons
                ? implode(' ', $reasons)
                : 'No shifts were generated. Please confirm the templates are active, have valid times, and match the selected days.';

            return back()->with('error', $reasonText);
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Generation failed: ' . $e->getMessage());
        }
    }



    public function publish(RotaPeriod $rota_period, Request $request)
    {
        $rota_period->status = 'published';
        $rota_period->published_at = now();
        $rota_period->save();

        // cascade status to shifts
        $rota_period->shifts()->update(['status' => 'published']);

        // TODO: emit event (RotaPublished) for Assignments module later
        return back()->with('success', 'Rota published.');
    }

    public function table(RotaPeriod $rota_period)
    {
        $rota_period->load('location', 'shifts.assignments.staff');

        $days = $this->buildRotaDays($rota_period);

        return view('backend.admin.shifts.periods.table', [
            'rota_period' => $rota_period,
            'days'        => $days,
        ]);
    }

    public function print(RotaPeriod $rota_period)
    {
        $rota_period->load('location', 'shifts.assignments.staff');

        $days = $this->buildRotaDays($rota_period);

        return view('backend.admin.shifts.periods.print', [
            'rota_period' => $rota_period,
            'days'        => $days,
        ]);
    }

    protected function buildRotaDays(RotaPeriod $rota_period): array
    {
        $days = [];

        foreach ($rota_period->shifts as $shift) {
            // Convert to UK time for grouping
            $startUk = $shift->start_at->setTimezone('Europe/London');
            $endUk   = $shift->end_at->setTimezone('Europe/London');

            $dateKey = $startUk->toDateString();

            if (!isset($days[$dateKey])) {
                $days[$dateKey] = [
                    'date' => $startUk->copy(),
                    'segments' => [
                        'morning'   => [],
                        'afternoon' => [],
                        'night'     => [],
                    ],
                ];
            }

            // Decide which segment the shift belongs to based on start time
            $hour = (int) $startUk->format('H');

            if ($hour < 14) {
                $segment = 'morning';      // e.g. 07:00–14:00
            } elseif ($hour < 21) {
                $segment = 'afternoon';    // e.g. 14:00–21:00
            } else {
                $segment = 'night';        // e.g. 21:00–07:00
            }

            // Build label & staff names
            $staffNames = $shift->assignments
                ->map(function ($a) {
                    if (!$a->staff) {
                        return null;
                    }
                    return trim($a->staff->first_name . ' ' . $a->staff->last_name . ' ' . $a->staff->other_names);
                })
                ->filter()
                ->values()
                ->all();

            $label = $shift->role . ' ' . $startUk->format('H:i') . '–' . $endUk->format('H:i');

            $days[$dateKey]['segments'][$segment][] = [
                'label' => $label,
                'staff' => $staffNames,
            ];
        }

        ksort($days); // sort by date ascending

        return $days;
    }

    public function destroy(RotaPeriod $rotaPeriod): RedirectResponse
    {
        // 1) Block delete if any timesheet exists for shifts in this rota period
        $hasTimesheets = Timesheet::query()
            ->whereIn(
                'shift_id',
                Shift::query()
                    ->where('rota_period_id', $rotaPeriod->id)
                    ->select('id')
            )
            ->exists();

        if ($hasTimesheets) {
            return back()->with('error', 'Cannot delete this rota period because timesheets already exist for one or more shifts.');
        }

        // 2) Safe delete: remove shifts + assignments + the period itself (transaction)
        DB::transaction(function () use ($rotaPeriod) {
            $shiftIds = Shift::query()
                ->where('rota_period_id', $rotaPeriod->id)
                ->pluck('id');

            // delete shift assignments first (if you have this table/model)
            DB::table('shift_assignments')->whereIn('shift_id', $shiftIds)->delete();

            // delete shifts
            Shift::query()->whereIn('id', $shiftIds)->delete();

            // delete the rota period
            $rotaPeriod->delete();
        });

        return back()->with('success', 'Rota period deleted successfully.');
    }


    public function edit(RotaPeriod $rota_period)
    {
        $locations = Location::orderBy('name')->get();

        return view('backend.admin.shifts.periods.edit', [
            'rota_period' => $rota_period,
            'locations'   => $locations,
        ]);
    }

    public function update(Request $request, RotaPeriod $rota_period): RedirectResponse
    {
        $data = $request->validate([
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        // ✅ prevent exact duplicate period for same location (excluding current record)
        $duplicate = RotaPeriod::query()
            ->where('location_id', $data['location_id'])
            ->whereDate('start_date', $data['start_date'])
            ->whereDate('end_date', $data['end_date'])
            ->whereKeyNot($rota_period->id)
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->with('error', 'This rota period already exists for the selected location and dates.');
        }

        // Optional: block edits if there are timesheets already (you said we’ll fine-tune later)
        // $hasTimesheets = Timesheet::query()
        //     ->whereIn('shift_id', Shift::where('rota_period_id', $rota_period->id)->select('id'))
        //     ->exists();
        // if ($hasTimesheets) return back()->with('error', 'Cannot edit because timesheets exist.');

        $rota_period->update($data);

        return redirect()
            ->route('backend.admin.rota-periods.index')
            ->with('success', 'Rota period updated successfully.');
    }
}
