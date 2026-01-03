@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

    @php
        use Illuminate\Support\Facades\Route as RouteFacade;

        $user = auth()->user();

        // Display name
        $displayName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->name ?? 'Carer');

        // Location label (can be overridden from controller)
        $currentLocationName = $currentLocationName ?? 'Your assigned location';

        // Today’s date in UK time
        $today = \Carbon\Carbon::now('Europe/London');

        // Try to get staff profile + passport photo
        // assumes User -> hasOne StaffProfile
        $staffProfile = $user?->staffProfile;
        $avatarUrl = $staffProfile?->passport_photo_url ?? asset('assets/img/user_img.png');
    @endphp

    <h2 class="heading-2">Welcome to Your Dashboard</h2>
    <p class="paragraph-small pt-3">
        You're logged in to Pulze! Use the menu to access available modules.
    </p>

    @can('view operations')
        <a href="{{ route('operations') }}" class="link-button d-inline-block mt-4">Go to Operations</a>
    @endcan

    {{-- ================= QUICK ACTIONS ================= --}}
    <section class="px-4 pt-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Quick actions</h6>
        </div>

        <div class="row g-3">
            {{-- My Shifts – only show if route exists --}}
            @if (RouteFacade::has('shifts.index'))
                <div class="col-6">
                    <a href="{{ route('shifts.index') }}" class="d-block text-decoration-none">
                        <div class="p-3 rounded-3 bg-white shadow-sm h-100 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bgMainColor text-white">Today</span>
                                <i class="ph ph-calendar-check fs-5 textMainColor"></i>
                            </div>
                            <p class="mb-0 small text-muted">My Shifts</p>
                            <p class="mb-0 fw-semibold">View &amp; start shifts</p>
                        </div>
                    </a>
                </div>
            @endif

            {{-- ✅ My Rota – always link to frontend.rota.index --}}
            {{-- Rota (list of published rota periods) --}}
            @if (RouteFacade::has('frontend.rota.index'))
                <div class="col-6">
                    <a href="{{ route('frontend.rota.index') }}" class="d-block text-decoration-none">
                        Rotas
                        <p class="mb-0 fw-semibold">All Rotas</p>
                        Published
                    </a>
                </div>
            @endif

            {{-- My Rota (opens current rota period and highlights me) --}}
            @if (RouteFacade::has('frontend.rota.current'))
                <div class="col-6">
                    <a href="{{ route('frontend.rota.current') }}" class="d-block text-decoration-none">
                        ...
                        <p class="mb-0 fw-semibold">This week</p>
                        ...
                    </a>
                </div>
            @endif


            {{-- Residents – placeholder for now --}}
            <div class="col-6">
                <a href="{{ route('frontend.residents.index') }}" class="d-block text-decoration-none">
                    <div class="p-3 rounded-3 bg-white shadow-sm h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-info-subtle text-info">Care</span>
                            <i class="ph ph-users-three fs-5 text-info"></i>
                        </div>
                        <p class="mb-0 small text-muted">Residents</p>
                        <p class="mb-0 fw-semibold">Profiles &amp; plans</p>
                    </div>
                </a>
            </div>

            {{-- Care Dashboard – uses your existing dashboard route --}}
            <div class="col-6">
                <a href="{{ route('dashboard') }}" class="d-block text-decoration-none">
                    <div class="p-3 rounded-3 bg-white shadow-sm h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-secondary-subtle text-secondary">Overview</span>
                            <i class="ph ph-gauge fs-5 text-secondary"></i>
                        </div>
                        <p class="mb-0 small text-muted">Care Dashboard</p>
                        <p class="mb-0 fw-semibold">Insights &amp; stats</p>
                    </div>
                </a>
            </div>
        </div>
    </section>
    {{-- ================= QUICK ACTIONS END ========== --}}

@endsection