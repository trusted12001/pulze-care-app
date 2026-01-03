@extends('layouts.carer')

@section('title', 'Residents Profiles & Plans')

@section('content')
    @php
        $q = request('q');
    @endphp

    <main class="home-screen position-relative top-0 start-0 end-0 pb-7">

        {{-- Header --}}
        <section class="d-flex justify-content-between align-items-center home-header-section w-100 px-4 pt-3">
            <div>
                <h3 class="heading-3 pb-1 mb-0">Residents Profiles & Plans</h3>
                <p class="mb-0 small text-muted">
                    {{ $currentLocationName ?? 'Your assigned location' }}
                </p>
            </div>

            <a href="{{ route('frontend.carer.index') }}"
                class="p-2 flex-center rounded-circle bg-white shadow-sm text-decoration-none">
                <i class="ph ph-house fs-5"></i>
            </a>
        </section>

        {{-- Search --}}
        <section class="search-section w-100 px-4 pt-4">
            <form id="residentSearchForm" method="GET" action="{{ route('frontend.residents.index') }}">
                <div class="search-area d-flex justify-content-between align-items-center gap-2 w-100">
                    <div
                        class="search-box d-flex justify-content-start align-items-center gap-2 px-3 py-2 w-100 rounded-3 bg-white shadow-sm">
                        <div class="flex-center">
                            <i class="ph ph-magnifying-glass"></i>
                        </div>

                        <input id="residentSearchInput" type="text" name="q" value="{{ $q }}"
                            class="border-0 w-100 bg-transparent small" placeholder="Search by name, room, ID…" />
                    </div>

                    <div class="search-button">
                        <button class="flex-center rounded-3 bgMainColor text-white border-0 px-3 py-2" type="submit">
                            <i class="ph ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                @if($q)
                    <div class="pt-2 d-flex justify-content-between align-items-center">
                        <p class="mb-0 small text-muted">Showing results for: <strong>{{ $q }}</strong></p>
                        <a class="small text-decoration-none" href="{{ route('frontend.residents.index') }}">Clear</a>
                    </div>
                @endif
            </form>
        </section>

        {{-- Residents list --}}
        <section class="px-4 pt-4 pb-6 top-doctor-area">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Residents</h3>

                {{-- Count --}}
                <p class="mb-0 small text-muted">
                    <span id="residentCount">
                        {{ method_exists($residents, 'total') ? $residents->total() : $residents->count() }}
                    </span>
                </p>
            </div>

            <div id="residentLoading" class="small text-muted pt-2" style="display:none;">
                Searching…
            </div>

            <div id="residentCardsWrap" class="d-flex flex-column gap-3 pt-3">
                @forelse($residents as $resident)
                    @include('frontend.carer.partials.resident-card', ['resident' => $resident])
                @empty
                    <div class="p-3 rounded-3 bg-white shadow-sm">
                        <p class="mb-0 text-muted small">No residents found.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if(method_exists($residents, 'links'))
                <div id="residentPagination" class="pt-4">
                    {{ $residents->withQueryString()->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </section>


    </main>


    @push('scripts')
        <script>
            (function () {
                const form = document.getElementById('residentSearchForm');
                const input = document.getElementById('residentSearchInput');

                const wrap = document.getElementById('residentCardsWrap');
                const pagination = document.getElementById('residentPagination');
                const countEl = document.getElementById('residentCount');
                const loadingEl = document.getElementById('residentLoading');

                if (!form || !input || !wrap) return;

                let t = null;
                let controller = null;

                async function fetchResults(url) {
                    // Abort previous request
                    if (controller) controller.abort();
                    controller = new AbortController();

                    loadingEl && (loadingEl.style.display = '');
                    try {
                        const res = await fetch(url, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            signal: controller.signal
                        });

                        // If session expired, Laravel may return 419/redirect html.
                        if (!res.ok) throw new Error('Request failed');

                        const html = await res.text();
                        const doc = new DOMParser().parseFromString(html, 'text/html');

                        const newWrap = doc.querySelector('#residentCardsWrap');
                        const newPagination = doc.querySelector('#residentPagination');
                        const newCount = doc.querySelector('#residentCount');

                        if (newWrap) wrap.innerHTML = newWrap.innerHTML;
                        if (pagination) pagination.innerHTML = newPagination ? newPagination.innerHTML : '';
                        if (countEl && newCount) countEl.textContent = newCount.textContent;

                        // Update URL in address bar (no reload)
                        window.history.replaceState({}, '', url);

                        // Keep focus + caret at end
                        input.focus();
                        const val = input.value;
                        input.setSelectionRange(val.length, val.length);
                    } finally {
                        loadingEl && (loadingEl.style.display = 'none');
                    }
                }

                function buildUrl(q) {
                    const url = new URL(form.action, window.location.origin);

                    // Preserve other query params except q/page
                    const current = new URLSearchParams(window.location.search);
                    current.forEach((v, k) => {
                        if (k !== 'q' && k !== 'page') url.searchParams.set(k, v);
                    });

                    if (q) url.searchParams.set('q', q);
                    return url.toString();
                }

                // Debounced search (NO full page refresh)
                input.addEventListener('input', function () {
                    clearTimeout(t);
                    t = setTimeout(() => {
                        fetchResults(buildUrl((input.value || '').trim()));
                    }, 300);
                });

                // Enter: immediate search
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        fetchResults(buildUrl((input.value || '').trim()));
                    }
                });

                // Handle pagination clicks via AJAX too
                document.addEventListener('click', function (e) {
                    const a = e.target.closest('#residentPagination a');
                    if (!a) return;
                    e.preventDefault();
                    fetchResults(a.href);
                });

            })();
        </script>
    @endpush
@endsection