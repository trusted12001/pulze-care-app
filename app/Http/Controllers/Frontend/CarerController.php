<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

use App\Models\ServiceUser;
use App\Models\ShiftAssignment;
use App\Models\Location;

class CarerController extends Controller
{
    /**
     * Carer dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $staffProfile = $user?->staffProfile;

        // 1) Determine assignment (active / next) + location to use
        $assignment = $this->resolveAssignmentContext($user);

        $assignmentLabel   = $assignment['assignmentLabel'];
        $assignmentSub     = $assignment['assignmentSub'];
        $activeLocationId  = $assignment['activeLocationId'];   // used for filtering residents on home
        $currentLocationId = $assignment['locationId'];         // can be active OR fallback
        $currentLocationName = $assignment['currentLocationName'];

        // 2) Residents query (home = small subset)
        $residentsQuery = ServiceUser::query()->with('location');
        $residentTable  = $residentsQuery->getModel()->getTable();

        // Tenant filter (only if both columns exist)
        if (
            $staffProfile &&
            Schema::hasColumn('staff_profiles', 'tenant_id') &&
            Schema::hasColumn($residentTable, 'tenant_id')
        ) {
            $residentsQuery->where('tenant_id', $staffProfile->tenant_id);
        }

        /**
         * IMPORTANT HOME RULE:
         * - If we have an ACTIVE location => show residents for that location.
         * - Else if no active shift, you can decide:
         *    A) show none (strict)
         *    B) show next location residents as preview (friendly)
         *
         * We’ll do friendly default: use $currentLocationId (active if present, else next/fallback).
         * If you want strict, change $currentLocationId to $activeLocationId.
         */
        $locationToUse = $currentLocationId;

        if ($locationToUse) {
            if (Schema::hasColumn($residentTable, 'location_id')) {
                $residentsQuery->where('location_id', $locationToUse);
            } elseif (Schema::hasColumn($residentTable, 'current_location_id')) {
                $residentsQuery->where('current_location_id', $locationToUse);
            }
        }

        // Sort nicely
        if (Schema::hasColumn($residentTable, 'first_name')) {
            $residentsQuery->orderBy('first_name')->orderBy('last_name');
        } elseif (Schema::hasColumn($residentTable, 'name')) {
            $residentsQuery->orderBy('name');
        } else {
            $residentsQuery->latest();
        }

        // Home shows a small number
        $residents = $residentsQuery->take(10)->get();

        // Fallback so the dashboard doesn’t look broken
        if ($residents->isEmpty()) {
            $fallback = ServiceUser::query()->with('location');
            $fallbackTable = $fallback->getModel()->getTable();

            if (
                $staffProfile &&
                Schema::hasColumn('staff_profiles', 'tenant_id') &&
                Schema::hasColumn($fallbackTable, 'tenant_id')
            ) {
                $fallback->where('tenant_id', $staffProfile->tenant_id);
            }

            if (Schema::hasColumn($fallbackTable, 'first_name')) {
                $fallback->orderBy('first_name')->orderBy('last_name');
            } elseif (Schema::hasColumn($fallbackTable, 'name')) {
                $fallback->orderBy('name');
            } else {
                $fallback->latest();
            }

            $residents = $fallback->take(10)->get();
        }

        return view('frontend.carer.index', [
            'user' => $user,

            // Header assignment UI
            'assignmentLabel' => $assignmentLabel,
            'assignmentSub'   => $assignmentSub,

            // Backward compatibility with your earlier variable
            'currentLocationName' => $currentLocationName,

            // Data
            'residents' => $residents,

            // Debug/optional
            'currentLocationId' => $currentLocationId,
            'activeLocationId'  => $activeLocationId,
        ]);
    }

    /**
     * Load more residents (AJAX) for home page
     */
    public function loadMoreResidents(Request $request)
    {
        $user = $request->user();
        $staffProfile = $user?->staffProfile;

        $assignment = $this->resolveAssignmentContext($user);
        $locationId = $assignment['locationId']; // use same rule as Home

        // filters
        $q = trim((string) $request->query('q', ''));
        $offset = (int) $request->query('offset', 0);
        $limit  = (int) $request->query('limit', 10);
        $limit  = max(1, min($limit, 30)); // safety

        $residentsQuery = ServiceUser::query()->with('location');
        $residentTable  = $residentsQuery->getModel()->getTable();

        // Tenant filter
        if (
            $staffProfile &&
            Schema::hasColumn('staff_profiles', 'tenant_id') &&
            Schema::hasColumn($residentTable, 'tenant_id')
        ) {
            $residentsQuery->where('tenant_id', $staffProfile->tenant_id);
        }

        // Location filter
        if ($locationId) {
            if (Schema::hasColumn($residentTable, 'location_id')) {
                $residentsQuery->where('location_id', $locationId);
            } elseif (Schema::hasColumn($residentTable, 'current_location_id')) {
                $residentsQuery->where('current_location_id', $locationId);
            }
        }

        // Search
        if ($q !== '') {
            $residentsQuery->where(function ($qq) use ($q, $residentTable) {
                // ID
                if (ctype_digit($q)) {
                    $qq->orWhere($residentTable . '.id', (int) $q);
                }

                // Names
                if (Schema::hasColumn($residentTable, 'first_name')) {
                    $qq->orWhere($residentTable . '.first_name', 'like', "%{$q}%")
                        ->orWhere($residentTable . '.last_name', 'like', "%{$q}%")
                        ->orWhereRaw(
                            "CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,'')) LIKE ?",
                            ["%{$q}%"]
                        );
                } elseif (Schema::hasColumn($residentTable, 'name')) {
                    $qq->orWhere($residentTable . '.name', 'like', "%{$q}%");
                }

                // Room
                if (Schema::hasColumn($residentTable, 'room_number')) {
                    $qq->orWhere($residentTable . '.room_number', 'like', "%{$q}%");
                }
            });
        }

        // Sort
        if (Schema::hasColumn($residentTable, 'first_name')) {
            $residentsQuery->orderBy('first_name')->orderBy('last_name');
        } elseif (Schema::hasColumn($residentTable, 'name')) {
            $residentsQuery->orderBy('name');
        } else {
            $residentsQuery->latest();
        }

        $residents = $residentsQuery
            ->skip($offset)
            ->take($limit)
            ->get();

        $html = '';
        foreach ($residents as $resident) {
            $html .= view('frontend.carer.partials.resident-card', compact('resident'))->render();
        }

        return response()->json([
            'count'       => $residents->count(),
            'html'        => $html,
            'next_offset' => $offset + $residents->count(),
            'has_more'    => $residents->count() === $limit,
        ]);
    }

    /**
     * Shared logic:
     * Determines active shift, next shift, location id, and header strings.
     *
     * Assumptions:
     * - Shifts are stored in UTC (start_at/end_at)
     * - UI is UK time (Europe/London)
     */
    private function resolveAssignmentContext($user): array
    {
        $staffProfile = $user?->staffProfile;

        $nowUk = Carbon::now('Europe/London');
        $todayUkStart = $nowUk->copy()->startOfDay();
        $todayUkEnd   = $nowUk->copy()->endOfDay();

        // Convert day boundaries to UTC for querying DB
        $todayUtcStart = $todayUkStart->copy()->timezone('UTC');
        $todayUtcEnd   = $todayUkEnd->copy()->timezone('UTC');

        $activeShift = null;
        $nextShift   = null;

        // Pull today's shifts for this staff (shift overlaps today)
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

            // Determine active / next using UK time for comparison
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

        // Decide which location id to use for "current context"
        // - If active shift => use it
        // - Else if next shift today => use it
        // - Else fallback to staff profile location_id
        $activeLocationId = $activeShift?->location_id;

        $locationId = $activeShift?->location_id
            ?: $nextShift?->location_id
            ?: (($staffProfile && Schema::hasColumn('staff_profiles', 'location_id')) ? $staffProfile->location_id : null);

        $currentLocationName = $locationId
            ? optional(Location::find($locationId))->name
            : 'Your assigned location';

        // Build header label text
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
            $assignmentSub   = 'Next: '
                . ($nextShift->location?->name ?? $currentLocationName)
                . ' (' . $startUk->format('H:i') . '–' . $endUk->format('H:i') . ')';
        } else {
            $assignmentLabel = 'No active assignment';
            $assignmentSub   = 'No more shifts today';
        }

        return [
            'assignmentLabel'     => $assignmentLabel,
            'assignmentSub'       => $assignmentSub,
            'activeLocationId'    => $activeLocationId,
            'locationId'          => $locationId,
            'currentLocationName' => $currentLocationName,
        ];
    }
}
