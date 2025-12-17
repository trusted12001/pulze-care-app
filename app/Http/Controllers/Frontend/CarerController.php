<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

use App\Models\ServiceUser;     // ✅ keep this if your resident model is ServiceUser
use App\Models\ShiftAssignment;
use App\Models\Location;

class CarerController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $staffProfile = $user?->staffProfile;

        $locationId = null;

        /**
         * IMPORTANT:
         * Most apps store start_at/end_at in UTC in DB.
         * So compute "today" in UK time, then convert to UTC for DB query.
         */
        $todayUkStart = Carbon::now('Europe/London')->startOfDay();
        $todayUkEnd   = Carbon::now('Europe/London')->endOfDay();

        $todayUtcStart = $todayUkStart->copy()->timezone('UTC');
        $todayUtcEnd   = $todayUkEnd->copy()->timezone('UTC');

        // A) Try location from today's assigned shift
        if (class_exists(ShiftAssignment::class) && Schema::hasTable('shift_assignments')) {

            $assignment = ShiftAssignment::query()
                ->where('staff_id', $user->id)
                ->whereHas('shift', function ($q) use ($todayUtcStart, $todayUtcEnd) {
                    // shift overlaps today
                    $q->where(function ($qq) use ($todayUtcStart, $todayUtcEnd) {
                        $qq->whereBetween('start_at', [$todayUtcStart, $todayUtcEnd])
                            ->orWhereBetween('end_at', [$todayUtcStart, $todayUtcEnd])
                            ->orWhere(function ($q3) use ($todayUtcStart, $todayUtcEnd) {
                                $q3->where('start_at', '<=', $todayUtcStart)
                                    ->where('end_at', '>=', $todayUtcEnd);
                            });
                    });
                })
                ->with(['shift:id,location_id,start_at,end_at'])
                ->latest('id')
                ->first();

            $locationId = $assignment?->shift?->location_id;
        }

        // B) fallback: staff_profiles.location_id
        if (!$locationId && $staffProfile && Schema::hasColumn('staff_profiles', 'location_id')) {
            $locationId = $staffProfile->location_id;
        }

        $currentLocationName = $locationId
            ? optional(Location::find($locationId))->name
            : 'Your assigned location';

        // -------------------------
        // Residents query (safe)
        // -------------------------
        $residentsQuery = ServiceUser::query();
        $residentTable = $residentsQuery->getModel()->getTable();

        // Tenant filter (only if both columns exist)
        if (
            $staffProfile &&
            Schema::hasColumn('staff_profiles', 'tenant_id') &&
            Schema::hasColumn($residentTable, 'tenant_id')
        ) {
            $residentsQuery->where('tenant_id', $staffProfile->tenant_id);
        }

        // Location filter (only if we actually have a locationId)
        if ($locationId) {
            if (Schema::hasColumn($residentTable, 'location_id')) {
                $residentsQuery->where('location_id', $locationId);
            } elseif (Schema::hasColumn($residentTable, 'current_location_id')) {
                $residentsQuery->where('current_location_id', $locationId);
            }
        }

        // Order nicely
        if (Schema::hasColumn($residentTable, 'first_name')) {
            $residentsQuery->orderBy('first_name')->orderBy('last_name');
        } elseif (Schema::hasColumn($residentTable, 'name')) {
            $residentsQuery->orderBy('name');
        } else {
            $residentsQuery->latest();
        }

        // Take 12 for dashboard
        $residents = $residentsQuery->take(12)->get();

        /**
         * ✅ Fallback:
         * If filters returned 0, show something (tenant-scoped if possible).
         * This prevents the dashboard from looking “broken”.
         */
        if ($residents->isEmpty()) {
            $fallback = ServiceUser::query();
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

            $residents = $fallback->take(12)->get();
        }

        return view('frontend.carer.index', [
            'user' => $user,
            'currentLocationName' => $currentLocationName,
            'residents' => $residents,
            'currentLocationId' => $locationId, // handy for debugging/UI later
        ]);
    }

    public function loadMoreResidents(Request $request)
    {
        $user = $request->user();
        $staffProfile = $user?->staffProfile;

        // Same logic as index() for location
        $locationId = null;

        if (class_exists(\App\Models\ShiftAssignment::class) && \Illuminate\Support\Facades\Schema::hasTable('shift_assignments')) {
            $todayStart = \Illuminate\Support\Carbon::now('Europe/London')->startOfDay();
            $todayEnd   = \Illuminate\Support\Carbon::now('Europe/London')->endOfDay();

            $assignment = \App\Models\ShiftAssignment::query()
                ->where('staff_id', $user->id)
                ->whereHas('shift', function ($q) use ($todayStart, $todayEnd) {
                    $q->whereBetween('start_at', [$todayStart, $todayEnd])
                        ->orWhereBetween('end_at', [$todayStart, $todayEnd])
                        ->orWhere(function ($qq) use ($todayStart, $todayEnd) {
                            $qq->where('start_at', '<=', $todayStart)
                                ->where('end_at', '>=', $todayEnd);
                        });
                })
                ->with(['shift:id,location_id,start_at,end_at'])
                ->orderByDesc('id')
                ->first();

            $locationId = $assignment?->shift?->location_id;
        }

        if (!$locationId && $staffProfile && \Illuminate\Support\Facades\Schema::hasColumn('staff_profiles', 'location_id')) {
            $locationId = $staffProfile->location_id;
        }

        // filters
        $q = trim((string) $request->query('q', ''));
        $offset = (int) $request->query('offset', 0);
        $limit  = (int) $request->query('limit', 10);
        $limit  = max(1, min($limit, 30)); // safety

        $residentsQuery = \App\Models\ServiceUser::query();

        if (
            $staffProfile
            && \Illuminate\Support\Facades\Schema::hasColumn($residentsQuery->getModel()->getTable(), 'tenant_id')
            && \Illuminate\Support\Facades\Schema::hasColumn('staff_profiles', 'tenant_id')
        ) {
            $residentsQuery->where('tenant_id', $staffProfile->tenant_id);
        }

        if ($locationId) {
            if (\Illuminate\Support\Facades\Schema::hasColumn($residentsQuery->getModel()->getTable(), 'location_id')) {
                $residentsQuery->where('location_id', $locationId);
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn($residentsQuery->getModel()->getTable(), 'current_location_id')) {
                $residentsQuery->where('current_location_id', $locationId);
            }
        }

        if ($q !== '') {
            $residentsQuery->where(function ($qq) use ($q, $residentsQuery) {
                if (\Illuminate\Support\Facades\Schema::hasColumn($residentsQuery->getModel()->getTable(), 'first_name')) {
                    $qq->where('first_name', 'like', "%{$q}%")
                        ->orWhere('last_name', 'like', "%{$q}%");
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn($residentsQuery->getModel()->getTable(), 'room_number')) {
                    $qq->orWhere('room_number', 'like', "%{$q}%");
                }
                $qq->orWhere('id', $q); // allow ID search
            });
        }

        $residents = $residentsQuery
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->skip($offset)
            ->take($limit)
            ->get();

        $html = '';
        foreach ($residents as $resident) {
            $html .= view('frontend.carer.partials.resident-card', compact('resident'))->render();
        }

        return response()->json([
            'count' => $residents->count(),
            'html'  => $html,
            'next_offset' => $offset + $residents->count(),
            'has_more' => $residents->count() === $limit,
        ]);
    }
}
