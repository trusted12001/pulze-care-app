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
            'location_id' => ['required', 'exists:locations,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);
        $data['generated_by'] = $request->user()->id;
        $period = RotaPeriod::create($data);
        return redirect()->route('backend.admin.rota-periods.show', $period)->with('success', 'Period created.');
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
    public function generate(RotaPeriod $rota_period, Request $request)
    {
        $templates = \App\Models\ShiftTemplate::where('location_id', $rota_period->location_id)
            ->where('active', true)
            ->get();

        if ($templates->isEmpty()) {
            return back()->with('warning', 'No active shift templates for this location. Create some first.');
        }

        $created = 0;
        $skipped = 0;

        $cursor = \Illuminate\Support\Carbon::parse($rota_period->start_date);
        $end    = \Illuminate\Support\Carbon::parse($rota_period->end_date);

        while ($cursor->lte($end)) {
            $dow = strtolower($cursor->format('D')); // mon,tue,...

            foreach ($templates as $tpl) {
                // Respect days-of-week filter (null = every day)
                $days = $tpl->days();
                if ($days && !in_array($dow, $days)) {
                    continue;
                }

                // Compute date+time in Europe/London → UTC storage
                $start = \Illuminate\Support\Carbon::parse($cursor->toDateString() . ' ' . $tpl->start_time, 'Europe/London')->utc();
                $endAt = \Illuminate\Support\Carbon::parse($cursor->toDateString() . ' ' . $tpl->end_time, 'Europe/London')->utc();
                if ($endAt->lte($start)) {
                    $endAt->addDay();
                } // overnight

                $slots = max(1, (int)($tpl->headcount ?? 1));

                for ($i = 1; $i <= $slots; $i++) {
                    $shift = \App\Models\Shift::firstOrCreate(
                        [
                            'rota_period_id' => $rota_period->id,
                            'location_id'    => $tpl->location_id,
                            'role'           => $tpl->role,
                            'start_at'       => $start,
                            'end_at'         => $endAt,
                            'position_index' => $i,
                        ],
                        [
                            'break_minutes' => (int)($tpl->break_minutes ?? 0),
                            'skills_json'   => $tpl->skills_json,
                            'status'        => $rota_period->status,
                            'notes'         => $tpl->notes,
                        ]
                    );

                    $shift->wasRecentlyCreated ? $created++ : $skipped++;
                }
            }

            $cursor->addDay();
        }

        return back()->with('success', "Shifts generated: {$created}" . ($skipped ? " • Skipped (already existed): {$skipped}" : ''));
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
}
