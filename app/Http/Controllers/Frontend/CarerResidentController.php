<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ServiceUser;
use App\Models\Location;
use App\Models\ShiftAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class CarerResidentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $staffProfile = $user?->staffProfile;

        // 1) Assignment context (same idea as CarerController)
        $assignment = $this->resolveAssignmentContext($user);

        $assignmentLabel     = $assignment['assignmentLabel'];
        $assignmentSub       = $assignment['assignmentSub'];
        $currentLocationName = $assignment['currentLocationName'];
        $locationId          = $assignment['locationId'];

        // 2) Search
        $q = trim((string) $request->query('q', ''));

        // 3) Residents query
        $query = ServiceUser::query()->with('location');
        $table = $query->getModel()->getTable();

        // Tenant filter (if columns exist)
        if (
            $staffProfile &&
            Schema::hasColumn('staff_profiles', 'tenant_id') &&
            Schema::hasColumn($table, 'tenant_id')
        ) {
            $query->where('tenant_id', $staffProfile->tenant_id);
        }

        // Location filter (optional: keep same behavior as Home)
        if ($locationId) {
            if (Schema::hasColumn($table, 'location_id')) {
                $query->where('location_id', $locationId);
            } elseif (Schema::hasColumn($table, 'current_location_id')) {
                $query->where('current_location_id', $locationId);
            }
        }

        // Search filters
        if ($q !== '') {
            $query->where(function ($w) use ($q, $table) {
                if (ctype_digit($q)) {
                    $w->orWhere($table . '.id', (int) $q);
                }

                if (Schema::hasColumn($table, 'first_name')) {
                    $w->orWhere($table . '.first_name', 'like', "%{$q}%")
                        ->orWhere($table . '.last_name', 'like', "%{$q}%")
                        ->orWhereRaw(
                            "CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,'')) LIKE ?",
                            ["%{$q}%"]
                        );
                } elseif (Schema::hasColumn($table, 'name')) {
                    $w->orWhere($table . '.name', 'like', "%{$q}%");
                }

                if (Schema::hasColumn($table, 'room_number')) {
                    $w->orWhere($table . '.room_number', 'like', "%{$q}%");
                }
            });
        }

        // Sort nicely
        if (Schema::hasColumn($table, 'first_name')) {
            $query->orderBy('first_name')->orderBy('last_name');
        } elseif (Schema::hasColumn($table, 'name')) {
            $query->orderBy('name');
        } else {
            $query->latest();
        }

        // Pagination (your view already supports it)
        $residents = $query->paginate(20)->withQueryString();

        return view('frontend.carer.residents.index', [
            'residents' => $residents,
            'q' => $q,

            // ✅ these power your "Your assigned location" header
            'assignmentLabel' => $assignmentLabel,
            'assignmentSub' => $assignmentSub,
            'currentLocationName' => $currentLocationName,

            // optional debug
            'currentLocationId' => $locationId,
        ]);
    }

    public function show(ServiceUser $resident)
    {
        return view('frontend.carer.residents.show', compact('resident'));
    }

    /**
     * Same assignment logic as CarerController:
     * Active shift > Next shift today > staff profile location > fallback label
     */
    private function resolveAssignmentContext($user): array
    {
        $staffProfile = $user?->staffProfile;

        $nowUk = Carbon::now('Europe/London');
        $todayUkStart = $nowUk->copy()->startOfDay();
        $todayUkEnd   = $nowUk->copy()->endOfDay();

        // DB usually stores times in UTC
        $todayUtcStart = $todayUkStart->copy()->timezone('UTC');
        $todayUtcEnd   = $todayUkEnd->copy()->timezone('UTC');

        $activeShift = null;
        $nextShift   = null;

        if (class_exists(ShiftAssignment::class) && Schema::hasTable('shift_assignments')) {
            $shifts = ShiftAssignment::query()
                ->where('staff_id', $user->id)
                ->whereHas('shift', function ($q) use ($todayUtcStart, $todayUtcEnd) {
                    $q->where(function ($qq) use ($todayUtcStart, $todayUtcEnd) {
                        $qq->whereBetween('start_at', [$todayUtcStart, $todayUtcEnd])
                            ->orWhereBetween('end_at', [$todayUtcStart, $todayUtcEnd])
                            ->orWhere(function ($q3) use ($todayUtcStart, $todayUtcEnd) {
                                $q3->where('start_at', '<=', $todayUtcStart)
                                    ->where('end_at', '>=', $todayUtcEnd);
                            });
                    });
                })
                ->with(['shift.location'])
                ->get()
                ->map(fn($a) => $a->shift)
                ->filter()
                ->sortBy('start_at')
                ->values();

            $activeShift = $shifts->first(function ($shift) use ($nowUk) {
                $startUk = Carbon::parse($shift->start_at)->timezone('Europe/London');
                $endUk   = Carbon::parse($shift->end_at)->timezone('Europe/London');
                return $nowUk->between($startUk, $endUk);
            });

            if (!$activeShift) {
                $nextShift = $shifts->first(function ($shift) use ($nowUk) {
                    $startUk = Carbon::parse($shift->start_at)->timezone('Europe/London');
                    return $startUk->gt($nowUk);
                });
            }
        }

        $locationId = $activeShift?->location_id
            ?: $nextShift?->location_id
            ?: (($staffProfile && Schema::hasColumn('staff_profiles', 'location_id')) ? $staffProfile->location_id : null);

        $currentLocationName = $locationId
            ? optional(Location::find($locationId))->name
            : 'Your assigned location';

        $assignmentLabel = 'Your assigned location';
        $assignmentSub   = null;

        if ($activeShift) {
            $startUk = Carbon::parse($activeShift->start_at)->timezone('Europe/London');
            $endUk   = Carbon::parse($activeShift->end_at)->timezone('Europe/London');

            $assignmentLabel = 'On duty at';
            $assignmentSub   = ($activeShift->location?->name ?? $currentLocationName)
                . ' (' . $startUk->format('H:i') . '–' . $endUk->format('H:i') . ')';
        } elseif ($nextShift) {
            $startUk = Carbon::parse($nextShift->start_at)->timezone('Europe/London');
            $endUk   = Carbon::parse($nextShift->end_at)->timezone('Europe/London');

            $assignmentLabel = 'No active assignment';
            $assignmentSub   = 'Next: ' . ($nextShift->location?->name ?? $currentLocationName)
                . ' (' . $startUk->format('H:i') . '–' . $endUk->format('H:i') . ')';
        } else {
            $assignmentLabel = 'No active assignment';
            $assignmentSub   = 'No more shifts today';
        }

        return [
            'assignmentLabel' => $assignmentLabel,
            'assignmentSub' => $assignmentSub,
            'locationId' => $locationId,
            'currentLocationName' => $currentLocationName,
        ];
    }
}
