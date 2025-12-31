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
                <p class="mb-0 small text-muted">
                    {{ method_exists($residents, 'total') ? $residents->total() : $residents->count() }}
                </p>
            </div>

            <div class="d-flex flex-column gap-3 pt-3">
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
                <div class="pt-4">
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
                if (!form || !input) return;

                let t = null;

                // Debounced submit as you type (Home-like behaviour)
                input.addEventListener('input', function () {
                    clearTimeout(t);
                    t = setTimeout(() => {
                        // Always go to page 1 when searching
                        const url = new URL(form.action, window.location.origin);
                        const q = (input.value || '').trim();
                        if (q) url.searchParams.set('q', q);

                        // If you later add other filters, preserve them like this:
                        // new URLSearchParams(window.location.search).forEach((v,k)=>{ if(k!=='q' && k!=='page') url.searchParams.set(k,v) });

                        window.location.href = url.toString();
                    }, 350);
                });

                // Enter key should submit immediately
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        form.submit();
                    }
                });
            })();
        </script>
    @endpush
@endsection