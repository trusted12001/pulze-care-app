<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\RotaPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;


class CarerRotaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tenantId = $user?->staffProfile?->tenant_id;

        $query = RotaPeriod::query()
            ->with('location')
            ->where('status', 'published')
            ->orderByDesc('start_date');

        if ($tenantId && Schema::hasColumn('rota_periods', 'tenant_id')) {
            $query->where('tenant_id', $tenantId);
        }

        $periods = $query->paginate(10);

        return view('frontend.carer.rota.index', compact('periods', 'user'));
    }

    // ✅ My Rota shortcut → open current active published rota
    public function current(Request $request)
    {
        $user = $request->user();
        $tenantId = $user?->staffProfile?->tenant_id;
        $today = Carbon::now('Europe/London')->toDateString();

        $query = RotaPeriod::query()
            ->where('status', 'published')
            ->forDate($today)
            ->orderByDesc('start_date');

        if ($tenantId && Schema::hasColumn('rota_periods', 'tenant_id')) {
            $query->where('tenant_id', $tenantId);
        }

        $period = $query->first();

        if (!$period) {
            return redirect()->route('frontend.rota.index')
                ->with('warning', 'No published rota found for this week.');
        }

        return redirect()->route('frontend.rota.show', $period);
    }

    public function show(Request $request, RotaPeriod $rota_period)
    {
        $user = $request->user();
        $tenantId = $user?->staffProfile?->tenant_id;

        if ($tenantId && Schema::hasColumn('rota_periods', 'tenant_id')) {
            if ((int) $rota_period->tenant_id !== (int) $tenantId) {
                abort(403, 'You are not allowed to view this rota.');
            }
        }

        // ✅ Load SHIFTS (not entries) + assignments + users (staff)
        $rota_period->load([
            'location',
            'shifts.location',
            'shifts.assignments.staff' => function ($q) {
                $q->select('id', 'first_name', 'last_name');
            },
        ]);

        $shifts = $rota_period->shifts->sortBy(fn($s) => optional($s->start_at)->timestamp ?? 0);

        return view('frontend.carer.rota.show', [
            'rotaPeriod' => $rota_period,
            'shifts'     => $shifts,
            'user'       => $user,
        ]);
    }
}
