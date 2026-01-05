@extends('layouts.carer')

@section('title', 'Residents')

@section('content')
    @php
        $q = request('q');
    @endphp

    <main class="residents-index-screen position-relative top-0 start-0 end-0 pb-5">

        {{-- ================= HEADER ================= --}}
        <section class="residents-header-section w-100 px-3 px-md-4 pt-3 pb-2">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="flex-grow-1">
                    <h2 class="mb-1 fw-bold" style="font-size: 1.5rem; color: var(--n1); line-height: 1.2;">
                        Residents
                    </h2>
                    <p class="mb-0 small text-muted d-flex align-items-center gap-1" style="font-size: 0.8125rem;">
                        <i class="ph-fill ph-map-pin" style="font-size: 0.875rem;"></i>
                        <span>{{ $currentLocationName ?? 'Your assigned location' }}</span>
                    </p>
                </div>

                <a href="{{ route('frontend.carer.index') }}"
                    class="home-button-header d-flex align-items-center justify-content-center flex-shrink-0 rounded-3 bg-white shadow-sm text-decoration-none"
                    style="width: 44px; height: 44px; transition: all 0.2s ease;">
                    <i class="ph-fill ph-house" style="font-size: 1.25rem; color: var(--p1);"></i>
                </a>
            </div>
        </section>
        {{-- ================= HEADER END ================= --}}

        {{-- ================= SEARCH SECTION ================= --}}
        <section class="search-section w-100 px-3 px-md-4 pt-3 pb-2">
            <form id="residentSearchForm" method="GET" action="{{ route('frontend.residents.index') }}">
                <div class="search-area d-flex justify-content-between align-items-center gap-2 w-100">
                    <div
                        class="search-box d-flex justify-content-start align-items-center gap-2 px-3 py-2 w-100 rounded-4 bg-white shadow-sm border-0"
                        style="transition: all 0.2s ease; border-radius: 16px !important; border: 1.5px solid var(--borderColor);">
                        <div class="flex-center" style="color: var(--n2);">
                            <i class="ph ph-magnifying-glass" style="font-size: 1.125rem;"></i>
                        </div>

                        <input id="residentSearchInput" type="text" name="q" value="{{ $q }}"
                            class="border-0 w-100 bg-transparent small flex-grow-1"
                            style="font-size: 0.875rem; outline: none;"
                            placeholder="Search by name, room, ID…" />

                        @if($q)
                            <button type="button" 
                                onclick="document.getElementById('residentSearchInput').value=''; document.getElementById('residentSearchForm').submit();"
                                class="border-0 bg-transparent p-0 flex-center"
                                style="color: var(--n2); cursor: pointer;">
                                <i class="ph ph-x" style="font-size: 1rem;"></i>
                            </button>
                        @endif
                    </div>

                    <div class="search-button flex-shrink-0">
                        <button class="flex-center text-white border-0" type="submit"
                            style="width: 44px; height: 44px; background-color: var(--p1); transition: all 0.2s ease; border-radius: 16px;"
                            onmouseover="this.style.transform='scale(1.05)'"
                            onmouseout="this.style.transform='scale(1)'">
                            <i class="ph ph-arrow-right" style="font-size: 1.125rem;"></i>
                        </button>
                    </div>
                </div>

                @if($q)
                    <div class="pt-2 d-flex justify-content-between align-items-center">
                        <p class="mb-0 small text-muted d-flex align-items-center gap-1">
                            <i class="ph ph-magnifying-glass" style="font-size: 0.875rem;"></i>
                            <span>Showing results for: <strong>{{ $q }}</strong></span>
                        </p>
                        <a class="small text-decoration-none fw-medium" 
                            href="{{ route('frontend.residents.index') }}"
                            style="color: var(--p1); transition: color 0.2s ease;"
                            onmouseover="this.style.color='var(--n1)'"
                            onmouseout="this.style.color='var(--p1)'">
                            Clear
                        </a>
                    </div>
                @endif
            </form>
        </section>
        {{-- ================= SEARCH END ================= --}}

        {{-- ================= RESIDENTS LIST ================= --}}
        <section class="px-3 px-md-4 pt-3 pb-6 residents-list-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0 fw-semibold" style="font-size: 1rem; color: var(--n1);">
                    All Residents
                </h4>

                {{-- Count --}}
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border px-2 py-1" 
                        style="font-size: 0.75rem; font-weight: 500;">
                        <span id="residentCount">
                            {{ method_exists($residents, 'total') ? $residents->total() : $residents->count() }}
                        </span>
                        <span class="ms-1">{{ (method_exists($residents, 'total') ? $residents->total() : $residents->count()) === 1 ? 'resident' : 'residents' }}</span>
                    </span>
                </div>
            </div>

            <div id="residentLoading" class="text-center py-4" style="display:none;">
                <div class="d-flex flex-column align-items-center gap-2">
                    <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mb-0 small text-muted">Searching…</p>
                </div>
            </div>

            <div id="residentCardsWrap" class="d-flex flex-column gap-3">
                @forelse($residents as $resident)
                    @include('frontend.carer.partials.resident-card', ['resident' => $resident])
                @empty
                    <div class="empty-state p-5 rounded-4 bg-white shadow-sm text-center">
                        <div class="d-flex flex-column align-items-center gap-3">
                            <div class="empty-icon d-flex align-items-center justify-content-center rounded-circle"
                                style="width: 80px; height: 80px; background: rgba(0, 146, 129, 0.1);">
                                <i class="ph ph-users" style="font-size: 2.5rem; color: var(--p1);"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-semibold" style="font-size: 1rem; color: var(--n1);">
                                    @if($q)
                                        No residents found
                                    @else
                                        No residents yet
                                    @endif
                                </h5>
                                <p class="mb-0 small text-muted">
                                    @if($q)
                                        Try adjusting your search terms
                                    @else
                                        Residents will appear here once added
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if(method_exists($residents, 'links') && $residents->hasPages())
                <div id="residentPagination" class="pt-4">
                    <div class="d-flex justify-content-center">
                        {{ $residents->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            @endif
        </section>
        {{-- ================= RESIDENTS LIST END ================= --}}

        {{-- Bottom spacing for footer menu --}}
        <div style="height: 100px;"></div>

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
                        <i class="ph-fill ph-house link-item"></i>
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

    </main>

    @push('styles')
    <style>
        .residents-index-screen {
            min-height: 100vh;
            background: linear-gradient(to bottom, #f8fafc 0%, #ffffff 100%);
        }

        .home-button-header:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        }

        .home-button-header:active {
            transform: scale(0.95);
        }

        .search-box:focus-within {
            border-color: var(--p1) !important;
            box-shadow: 0 0 0 3px rgba(0, 146, 129, 0.1) !important;
            transform: translateY(-1px);
        }

        .empty-state {
            border: 1.5px solid var(--borderColor);
        }
    </style>
    @endpush

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

                    if (loadingEl) {
                        loadingEl.style.display = '';
                        wrap.style.opacity = '0.5';
                    }
                    
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
                        if (pagination) {
                            pagination.innerHTML = newPagination ? newPagination.innerHTML : '';
                        }
                        if (countEl && newCount) {
                            const count = newCount.textContent.trim();
                            const parts = count.split(' ');
                            countEl.textContent = parts[0];
                            const countText = countEl.nextElementSibling;
                            if (countText) {
                                countText.textContent = (parseInt(parts[0]) === 1 ? ' resident' : ' residents');
                            }
                        }

                        // Update URL in address bar (no reload)
                        window.history.replaceState({}, '', url);

                        // Keep focus + caret at end
                        input.focus();
                        const val = input.value;
                        input.setSelectionRange(val.length, val.length);
                    } catch (e) {
                        console.error('Search error:', e);
                    } finally {
                        if (loadingEl) {
                            loadingEl.style.display = 'none';
                            wrap.style.opacity = '1';
                        }
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
                        clearTimeout(t);
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
