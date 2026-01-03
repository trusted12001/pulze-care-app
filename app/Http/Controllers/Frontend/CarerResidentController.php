<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ServiceUser;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class CarerResidentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $staffProfile = $user?->staffProfile;

        // Determine location (same logic style as CarerController)
        $locationId = null;

        if ($staffProfile && Schema::hasColumn('staff_profiles', 'location_id')) {
            $locationId = $staffProfile->location_id;
        }

        $currentLocationName = $locationId
            ? optional(Location::find($locationId))->name
            : 'Your assigned location';

        $q = trim((string) $request->query('q'));

        $query = ServiceUser::query()->with('location');

        // Tenant filter (if you use it)
        if (
            $staffProfile
            && Schema::hasColumn('staff_profiles', 'tenant_id')
            && Schema::hasColumn($query->getModel()->getTable(), 'tenant_id')
        ) {
            $query->where('tenant_id', $staffProfile->tenant_id);
        }

        // Location filter (if available)
        if ($locationId) {
            if (Schema::hasColumn($query->getModel()->getTable(), 'location_id')) {
                $query->where('location_id', $locationId);
            } elseif (Schema::hasColumn($query->getModel()->getTable(), 'current_location_id')) {
                $query->where('current_location_id', $locationId);
            }
        }

        // Search
        if ($q !== '') {
            $table = $query->getModel()->getTable();

            $query->where(function ($w) use ($q, $table) {
                // ID search
                if (ctype_digit($q)) {
                    $w->orWhere($table . '.id', (int) $q);
                }

                // Name search
                if (Schema::hasColumn($table, 'first_name')) {
                    $w->orWhere($table . '.first_name', 'like', "%{$q}%")
                        ->orWhere($table . '.last_name', 'like', "%{$q}%")
                        ->orWhereRaw("CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,'')) LIKE ?", ["%{$q}%"]);
                } elseif (Schema::hasColumn($table, 'name')) {
                    $w->orWhere($table . '.name', 'like', "%{$q}%");
                }

                // Room search
                if (Schema::hasColumn($table, 'room_number')) {
                    $w->orWhere($table . '.room_number', 'like', "%{$q}%");
                }
            });
        }

        // Sort nicely
        $table = $query->getModel()->getTable();
        if (Schema::hasColumn($table, 'first_name')) {
            $query->orderBy('first_name')->orderBy('last_name');
        } elseif (Schema::hasColumn($table, 'name')) {
            $query->orderBy('name');
        } else {
            $query->latest();
        }

        $residents = $query->paginate(5)->withQueryString();

        return view('frontend.carer.residents.index', [
            'currentLocationName' => $currentLocationName,
            'residents' => $residents,
        ]);
    }

    public function show(ServiceUser $resident)
    {
        // For now, just return a simple show page or reuse an existing profile view later
        return view('frontend.carer.residents.show', compact('resident'));
    }
}
