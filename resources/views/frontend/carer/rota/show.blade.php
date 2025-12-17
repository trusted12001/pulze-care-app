@extends('layouts.carer')

@section('title', 'Rota Details')

@section('content')
    @php
        use Carbon\Carbon;

        $user = $user ?? auth()->user();
        $displayName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->name ?? 'Carer');
        $currentUserId = $user->id;

        $startDate = $rotaPeriod->start_date ? Carbon::parse($rotaPeriod->start_date) : null;
        $endDate = $rotaPeriod->end_date ? Carbon::parse($rotaPeriod->end_date) : null;

        // Group shifts by date (from start_at)
        $grouped = $shifts->groupBy(function ($shift) {
            return optional($shift->start_at)->toDateString() ?? 'unknown';
        });
    @endphp

    <main class="home-screen position-relative top-0 start-0 end-0 pb-7">

        {{-- Header --}}
        <section class="d-flex justify-content-between align-items-center home-header-section w-100 px-4 pt-3">
            <div class="d-flex justify-content-start align-items-center gap-3">
                <div class="avatar rounded-circle overflow-hidden flex-center">
                    @php
                        $staffProfile = $user?->staffProfile;
                        $avatarUrl = $staffProfile?->passport_photo_url ?? asset('assets/img/user_img.png');
                    @endphp
                    <img src="{{ $avatarUrl }}" alt="{{ $displayName }}" class="img-fluid"
                        style="width: 52px; height: 52px; object-fit: cover;" />
                </div>

                <div>
                    <h3 class="heading-3 pb-1 mb-0">Rota</h3>
                    <p class="d-inline-flex gap-2 location justify-content-start align-items-center mb-0 small text-muted">
                        {{ $displayName }}
                    </p>
                </div>
            </div>

            <div class="d-flex justify-content-end align-items-center header-right gap-2 flex-wrap">
                <a href="{{ route('frontend.rota.index') }}"
                    class="p-2 flex-center rounded-circle bg-white shadow-sm text-decoration-none">
                    <i class="ph ph-caret-left fs-5"></i>
                </a>

                <button type="button" onclick="window.print()"
                    class="p-2 flex-center rounded-circle bg-white shadow-sm border-0">
                    <i class="ph ph-printer fs-5"></i>
                </button>
            </div>
        </section>

        {{-- Summary --}}
        <section class="px-4 pt-3">
            <h2 class="heading-2 pb-1">{{ $rotaPeriod->name ?? 'Rota #' . $rotaPeriod->id }}</h2>

            @if ($startDate && $endDate)
                <p class="small text-muted mb-1">
                    {{ $startDate->format('D d M Y') }} – {{ $endDate->format('D d M Y') }}
                </p>
            @endif

            <p class="small text-muted mb-0 d-flex align-items-center gap-2">
                <i class="ph ph-map-pin"></i>
                {{ $rotaPeriod->location->name ?? 'All locations / Not set' }}
            </p>

            <p class="small text-muted mt-1">
                Status: <strong>{{ ucfirst($rotaPeriod->status ?? 'published') }}</strong>
            </p>

            <p class="small text-muted mt-2">
                <span class="badge bg-primary-subtle text-primary me-1">&nbsp;</span>
                Your shifts are highlighted in blue.
            </p>
        </section>

        {{-- Full rota table grouped by day --}}
        <section class="px-4 pt-3 pb-5">
            @forelse($grouped as $dateKey => $dayShifts)
                @php
                    $day = $dateKey !== 'unknown' ? Carbon::parse($dateKey) : null;
                @endphp

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="mb-0">
                            {{ $day ? $day->format('l d M Y') : 'Unknown date' }}
                        </h6>
                    </div>

                    <div class="bg-white rounded-3 shadow-sm overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Role</th>
                                        <th>Staff</th>
                                        <th>Time</th>
                                        <th>Location</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dayShifts as $shift)
                                        @php
                                            $start = $shift->start_at ? Carbon::parse($shift->start_at)->format('H:i') : '—';
                                            $end = $shift->end_at ? Carbon::parse($shift->end_at)->format('H:i') : '—';

                                            // staff list from assignments
                                            $staffNames = collect($shift->assignments ?? [])
                                                ->map(function ($a) {
                                                    $u = $a->staff ?? null;
                                                    return $u ? (trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?: $u->name) : null;
                                                })
                                                ->filter()
                                                ->values();

                                            $isMine = collect($shift->assignments ?? [])
                                                ->contains(fn($a) => (int) ($a->staff_id ?? 0) === (int) $currentUserId);
                                        @endphp

                                        <tr @class(['table-primary' => $isMine])>
                                            <td>{{ $shift->role ?? 'Shift' }}</td>
                                            <td>
                                                @if($staffNames->count())
                                                    {{ $staffNames->implode(', ') }}
                                                @else
                                                    <span class="text-muted small">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>{{ $start }} – {{ $end }}</td>
                                            <td>{{ $shift->location->name ?? $rotaPeriod->location->name ?? '—' }}</td>
                                            <td class="small text-muted">{{ $shift->notes ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <p class="small text-muted mt-3">No shifts found for this rota period.</p>
            @endforelse
        </section>

    </main>
@endsection