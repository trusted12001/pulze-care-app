@extends('layouts.carer')

@section('title', 'My Rota')

@section('content')
    @php
        use Carbon\Carbon;

        $user = $user ?? auth()->user();
        $displayName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->name ?? 'Carer');
        $today = Carbon::now('Europe/London');
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
                    <h3 class="heading-3 pb-1 mb-0">My Rota</h3>
                    <p class="d-inline-flex gap-2 location justify-content-start align-items-center mb-0 small text-muted">
                        {{ $displayName }}
                    </p>
                </div>
            </div>

            <div class="d-flex justify-content-end align-items-center header-right gap-2 flex-wrap">
                <a href="{{ route('frontend.carer.index') }}"
                    class="p-2 flex-center rounded-circle bg-white shadow-sm text-decoration-none">
                    <i class="ph ph-caret-left fs-5"></i>
                </a>

                <a href="{{ route('dashboard') }}"
                    class="p-2 flex-center rounded-circle bg-white shadow-sm text-decoration-none">
                    <i class="ph ph-gauge fs-5"></i>
                </a>
            </div>
        </section>

        {{-- Intro --}}
        <section class="px-4 pt-3">
            <p class="date mb-1">{{ $today->format('l, F j') }}</p>
            <h2 class="heading-2 pt-1 pb-2">Published rota periods</h2>
            <p class="small text-muted">
                Tap a period below to see the full rota — including all staff on each shift.
            </p>
        </section>

        {{-- Period cards --}}
        <section class="px-4 pt-3 pb-5">
            @forelse($periods as $period)
                @php
                    // Adjust these to your column names
                    $start = $period->start_date ?? $period->starts_at ?? null;
                    $end = $period->end_date ?? $period->ends_at ?? null;
                    $startDate = $start ? Carbon::parse($start) : null;
                    $endDate = $end ? Carbon::parse($end) : null;

                    $label = $period->name
                        ?? ($startDate && $endDate
                            ? $startDate->format('d M') . ' - ' . $endDate->format('d M Y')
                            : 'Rota #' . $period->id);
                @endphp

                <a href="{{ route('frontend.rota.show', $period) }}" class="text-decoration-none d-block mb-3">
                    <div class="p-3 rounded-3 bg-white shadow-sm h-100">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0">{{ $label }}</h6>
                            <span class="badge bgMainColor text-white text-uppercase small">
                                {{ ucfirst($period->status ?? 'published') }}
                            </span>
                        </div>

                        <p class="mb-1 small text-muted">
                            @if ($startDate && $endDate)
                                {{ $startDate->format('D d M Y') }} – {{ $endDate->format('D d M Y') }}
                            @endif
                        </p>

                        <p class="mb-0 small text-muted d-flex align-items-center gap-2">
                            <i class="ph ph-map-pin"></i>
                            {{ $period->location->name ?? 'All locations / Not set' }}
                        </p>
                    </div>
                </a>
            @empty
                <p class="small text-muted mt-3">
                    No published rota periods are available yet.
                </p>
            @endforelse

            <div class="mt-3">
                {{ $periods->links() }}
            </div>
        </section>

    </main>
@endsection