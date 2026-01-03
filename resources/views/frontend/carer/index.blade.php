@extends('layouts.carer')

@section('title', 'Carer Home')

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

    {{-- Display Session Messages --}}
    @if (session('warning'))
        <div class="mb-4 rounded bg-red-50 text-orange-800 px-4 py-3">
            {{ session('warning') }}
        </div>
    @endif


    @if (session('error'))
        <div class="mb-4 rounded bg-red-50 text-red-800 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    {{-- Optional admin links (kept from your original) --}}
    <div class="mt-3 mb-2">
        @can('manage shifts')
            @if (RouteFacade::has('shifts.index'))
                <a href="{{ route('shifts.index') }}" class="link-button d-inline-block mt-2 me-2">
                    Manage Shifts
                </a>
            @endif
        @endcan

        @can('view dashboard')
            <a href="{{ route('dashboard') }}" class="link-button d-inline-block mt-2">
                View Dashboard
            </a>
        @endcan
    </div>

    {{-- ================= PRELOADER ================= --}}
    <div class="preloader active">
        <div class="flex-center h-100 bgMainColor">
            <div class="main-container flex-center h-100 flex-column">
                <div class="wave-animation">
                    <img src="{{ asset('assets/img/fav.png') }}" alt="Pulze icon" />
                    <div class="waves wave-1"></div>
                    <div class="waves wave-2"></div>
                    <div class="waves wave-3"></div>
                </div>

                <div class="pt-4">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Pulze logo" />
                </div>
            </div>
        </div>
    </div>
    {{-- ================= PRELOADER END ============= --}}

    <main class="home-screen position-relative top-0 start-0 end-0 pb-7">

        {{-- ================= HEADER ================= --}}
        <section class="d-flex justify-content-between align-items-center home-header-section w-100 px-4 pt-3">
            <div class="d-flex justify-content-start align-items-center gap-3">
                <div class="avatar rounded-circle overflow-hidden flex-center">
                    <img src="{{ $avatarUrl }}" alt="{{ $displayName }}" class="img-fluid"
                        style="width: 52px; height: 52px; object-fit: cover;" />
                </div>

                <div>
                    <h3 class="heading-3 pb-1 mb-0">Hi, {{ $displayName }} 👋</h3>
                    <p class="d-inline-flex gap-2 location justify-content-start align-items-center mb-0 small text-muted">
                        {{ $currentLocationName }}
                        <i class="ph-fill ph-map-pin"></i>
                    </p>
                </div>
            </div>
        </section>
        {{-- ================= HEADER END ============= --}}

        {{-- ================= SEARCH RESIDENT ================= --}}
        <section class="search-section w-100 px-4 pt-4">
            <p class="date mb-1">{{ $today->format('l, F j') }}</p>

            <div class="search-area d-flex justify-content-between align-items-center gap-2 w-100">
                <div
                    class="search-box d-flex justify-content-start align-items-center gap-2 px-3 py-2 w-100 rounded-3 bg-white shadow-sm">
                    <div class="flex-center">
                        <i class="ph ph-magnifying-glass"></i>
                    </div>
                    {{-- You can wire this to a residents search route later --}}
                    <input type="text" id="residentSearchInput" class="border-0 w-100 bg-transparent small"
                        placeholder="Search residents by name, ID or room…" value="{{ request('q') }}" />
                </div>

                <div class="search-button">
                    <button class="flex-center rounded-3 bgMainColor text-white border-0 px-3 py-2"
                        id="filterModalOpenButton" type="button">
                        <i class="ph ph-sliders-horizontal"></i>
                    </button>
                </div>
            </div>
        </section>
        {{-- ================= SEARCH END ================= --}}


        {{-- ================= TOP RESIDENTS (dynamic ready) ================= --}}
        <section class="px-4 pt-4 pb-6 top-doctor-area">
            <div class="d-flex justify-content-between align-items-center">
                <p class="small text-muted">
                    Residents found? {{ isset($residents) ? 'YES' : 'NO' }}
                    @if(isset($residents))
                        | Count: {{ $residents->count() }}
                    @endif
                </p>
                <a href="{{ route('frontend.residents.index') }}" class="view-all btn btn-link p-0 small"
                    id="topDoctorModalOpenButton" type="button">View all</a>
            </div>


            <div class="d-flex flex-column gap-3 pt-3">
                <div id="residentCardsWrap" class="d-flex flex-column gap-3 pt-3">
                    @isset($residents)
                        @forelse($residents as $resident)
                            @include('frontend.carer.partials.resident-card', ['resident' => $resident])
                        @empty
                            <p class="text-muted small mb-0 pt-2">No residents to display yet.</p>
                        @endforelse
                    @endisset
                </div>
            </div>

            <div class="pt-3" style="margin-bottom: 120px">
                <button id="loadMoreResidentsBtn" class="w-100 btn btn-outline-secondary rounded-3" type="button">
                    Load more
                </button>

                <p id="loadMoreHint" class="small text-muted text-center mt-2 mb-0"></p>
            </div>



        </section>
        {{-- ================= TOP RESIDENTS END ================= --}}

        {{-- ================= FOOTER MENU ================= --}}
        <div class="footer-menu-area">
            <div class="footer-menu flex justify-content-center align-items-center">
                <div class="d-flex justify-content-between align-items-center px-4 h-100 w-100">

                    <a href="#" class="flex-center text-decoration-none">
                        <i class="ph ph-list link-item"></i>
                    </a>

                    <button type="button" class="flex-center text-decoration-none bg-transparent border-0"
                        id="notificationModalOpenButtonFooter">
                        <i class="ph ph-bell link-item"></i>
                    </button>

                    {{-- Profile – placeholder for now, wire later --}}
                    <a href="#" class="flex-center text-decoration-none">
                        <i class="ph ph-user-circle link-item"></i>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="flex-center text-decoration-none bg-transparent border-0">
                            <i class="ph ph-sign-out link-item"></i>
                        </button>
                    </form>

                    {{-- Home --}}
                    <a href="{{ route('frontend.carer.index') }}" class="flex-center text-decoration-none">
                        <i class="ph-fill ph-house link-item active"></i>
                    </a>

                </div>

                <div class="plus-icon position-absolute">
                    <div class="position-relative">
                        <img src="{{ asset('assets/img/plus-icon-bg.png') }}" alt="" />
                        <i class="ph ph-plus"></i>
                    </div>
                </div>
            </div>
        </div>
        {{-- ================= FOOTER MENU END ================= --}}

        {{-- ================== MODALS ================== --}}
        @includeIf('frontend.carer.partials.notification-modal')
        @includeIf('frontend.carer.partials.favourite-modal')
        @includeIf('frontend.carer.partials.filter-modal')
        @includeIf('frontend.carer.partials.top-doctor-modal')
        @includeIf('frontend.carer.partials.speciality-modal')

    </main>


    @push('scripts')
        <script>
            (function () {
                const wrap = document.getElementById('residentCardsWrap');
                const btn = document.getElementById('loadMoreResidentsBtn');
                const hint = document.getElementById('loadMoreHint');
                const input = document.getElementById('residentSearchInput');

                if (!wrap || !btn || !input) return;

                let offset = wrap.querySelectorAll('.top-doctor-item').length;
                let loading = false;

                async function fetchMore(reset = false) {
                    if (loading) return;
                    loading = true;

                    const q = (input.value || '').trim();

                    if (reset) {
                        offset = 0;
                        wrap.innerHTML = '';
                        hint.textContent = '';
                        btn.disabled = false;
                        btn.textContent = 'Load more';
                    }

                    btn.disabled = true;
                    btn.textContent = 'Loading...';

                    const url = new URL("{{ route('frontend.carer.residents.load-more') }}", window.location.origin);
                    url.searchParams.set('offset', offset);
                    url.searchParams.set('limit', 10);
                    if (q) url.searchParams.set('q', q);

                    try {
                        const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
                        const data = await res.json();

                        if (data.html) wrap.insertAdjacentHTML('beforeend', data.html);

                        offset = data.next_offset ?? offset;

                        if (!data.has_more) {
                            btn.style.display = 'none';
                            hint.textContent = 'No more residents.';
                        } else {
                            btn.style.display = '';
                            btn.disabled = false;
                            btn.textContent = 'Load more';
                            hint.textContent = '';
                        }
                    } catch (e) {
                        console.error(e);
                        btn.disabled = false;
                        btn.textContent = 'Load more';
                        hint.textContent = 'Could not load more residents.';
                    } finally {
                        loading = false;
                    }
                }

                // Load more
                btn.addEventListener('click', () => fetchMore(false));

                // Filter (debounced)
                let t = null;
                input.addEventListener('input', () => {
                    clearTimeout(t);
                    t = setTimeout(() => {
                        btn.style.display = '';
                        fetchMore(true);
                    }, 350);
                });
            })();
        </script>
    @endpush

@endsection