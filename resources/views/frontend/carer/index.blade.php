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

    <main class="home-screen position-relative top-0 start-0 end-0 pb-5">

        {{-- ================= HEADER ================= --}}
        <section class="home-header-section w-100 px-3 px-md-4 pt-3 pb-2">
            <div class="d-flex justify-content-between align-items-center gap-2 gap-md-3">
                <div class="d-flex justify-content-start align-items-center gap-2 gap-md-3 flex-grow-1 min-w-0">
                    <div class="avatar rounded-circle overflow-hidden flex-shrink-0" style="width: 48px; height: 48px;">
                        <img src="{{ $avatarUrl }}" alt="{{ $displayName }}" class="w-100 h-100"
                            style="object-fit: cover;" />
                    </div>

                    <div class="flex-grow-1 min-w-0">
                        <h3 class="heading-3 mb-1 fw-semibold" style="font-size: 1.25rem; line-height: 1.3;">
                            Hi, {{ $displayName }} 👋
                        </h3>
                        <p class="d-flex align-items-center gap-1 mb-0 small text-muted" style="font-size: 0.8125rem;">
                            <i class="ph-fill ph-map-pin" style="font-size: 0.875rem;"></i>
                            <span class="text-truncate">{{ $currentLocationName }}</span>
                        </p>
                    </div>
                </div>

                {{-- Notification Icon --}}
                <button type="button" 
                    class="notification-header-btn flex-center bg-transparent border-0 flex-shrink-0"
                    id="notificationModalOpenButton"
                    style="position: relative;">
                    <i class="ph ph-bell" style="font-size: 1.5rem; color: var(--n1);"></i>
                    {{-- Notification Badge --}}
                    <span class="notification-badge position-absolute rounded-circle"></span>
                </button>
            </div>
        </section>
        {{-- ================= HEADER END ============= --}}

        {{-- ================= SEARCH RESIDENT ================= --}}
        <section class="search-section w-100 px-3 px-md-4 pt-3 pb-2">
            <p class="date mb-2 fw-medium" style="font-size: 0.875rem; color: var(--n2);">
                {{ $today->format('l, F j') }}
            </p>

            <div class="search-area d-flex justify-content-between align-items-center gap-2 w-100">
                <div
                    class="search-box d-flex justify-content-start align-items-center gap-2 px-3 py-2 w-100 rounded-4 bg-white shadow-sm border-0"
                    style="transition: all 0.2s ease; border-radius: 16px !important;">
                    <div class="flex-center" style="color: var(--n2);">
                        <i class="ph ph-magnifying-glass" style="font-size: 1.125rem;"></i>
                    </div>
                    <input type="text" id="residentSearchInput" 
                        class="border-0 w-100 bg-transparent small flex-grow-1"
                        style="font-size: 0.875rem; outline: none;"
                        placeholder="Search residents by name, ID or room…" 
                        value="{{ request('q') }}" />
                </div>

                <div class="search-button flex-shrink-0">
                    <button class="flex-center text-white border-0"
                        style="width: 44px; height: 44px; background-color: var(--p1); transition: all 0.2s ease; border-radius: 16px;"
                        id="filterModalOpenButton" type="button"
                        onmouseover="this.style.transform='scale(1.05)'"
                        onmouseout="this.style.transform='scale(1)'">
                        <i class="ph ph-sliders-horizontal" style="font-size: 1.125rem;"></i>
                    </button>
                </div>
            </div>
        </section>
        {{-- ================= SEARCH END ================= --}}


        {{-- ================= RESIDENTS SECTION ================= --}}
        <section class="px-3 px-md-4 pt-3 pb-6 top-doctor-area">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h4 class="mb-0 fw-semibold" style="font-size: 1rem; color: var(--n1);">
                        Residents
                        @if(isset($residents) && $residents->count() > 0)
                            <span class="text-muted fw-normal" style="font-size: 0.875rem;">
                                ({{ $residents->count() }})
                            </span>
                        @endif
                    </h4>
                </div>
                <a href="{{ route('frontend.residents.index') }}" 
                    class="view-all text-decoration-none fw-medium"
                    style="font-size: 0.875rem; color: var(--p1); transition: color 0.2s ease;"
                    onmouseover="this.style.color='var(--n1)'"
                    onmouseout="this.style.color='var(--p1)'">
                    View all
                </a>
            </div>

            <div class="d-flex flex-column gap-3 pt-2">
                <div id="residentCardsWrap" class="d-flex flex-column gap-3">
                    @isset($residents)
                        @forelse($residents as $resident)
                            @include('frontend.carer.partials.resident-card', ['resident' => $resident])
                        @empty
                            <div class="text-center py-5">
                                <i class="ph ph-users" style="font-size: 3rem; color: var(--n40); opacity: 0.5;"></i>
                                <p class="text-muted small mb-0 pt-3">No residents to display yet.</p>
                            </div>
                        @endforelse
                    @endisset
                </div>
            </div>

            <div class="pt-3" style="margin-bottom: 100px">
                <button id="loadMoreResidentsBtn" 
                    class="w-100 btn btn-outline-secondary fw-medium py-2"
                    style="font-size: 0.875rem; transition: all 0.2s ease; border-radius: 16px; border-width: 2px;"
                    type="button">
                    Load more
                </button>

                <p id="loadMoreHint" class="small text-muted text-center mt-2 mb-0"></p>
            </div>

        </section>
        {{-- ================= RESIDENTS END ================= --}}

        {{-- ================= FOOTER MENU ================= --}}
        <div class="footer-menu-area">
            <div class="footer-menu flex justify-content-center align-items-center">
                <div class="d-flex justify-content-between align-items-center px-3 px-md-4 h-100 w-100">

                    <a href="#" class="flex-center text-decoration-none footer-menu-link">
                        <i class="ph ph-list link-item"></i>
                    </a>

                    {{-- Profile – placeholder for now, wire later --}}
                    <a href="#" class="flex-center text-decoration-none footer-menu-link">
                        <i class="ph ph-user-circle link-item"></i>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="flex-center text-decoration-none bg-transparent border-0 footer-menu-link">
                            <i class="ph ph-sign-out link-item"></i>
                        </button>
                    </form>

                    {{-- Home --}}
                    <a href="{{ route('frontend.carer.index') }}" class="flex-center text-decoration-none footer-menu-link">
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