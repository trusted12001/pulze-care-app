@extends('layouts.carer')

@section('title', 'Resident')

@section('content')
    <main class="home-screen position-relative top-0 start-0 end-0 pb-7">

        {{-- Header --}}
        <section class="d-flex justify-content-between align-items-center home-header-section w-100 px-4 pt-3">
            <div>
                <h3 class="heading-3 pb-1 mb-0">Resident</h3>
                <p class="mb-0 small text-muted">{{ $currentLocationName ?? 'Your assigned location' }}</p>
            </div>

            <a href="{{ url()->previous() }}" class="p-2 flex-center rounded-3 bg-white shadow-sm text-decoration-none">
                Back
            </a>
        </section>

        {{-- Resident card (same as Home style) --}}
        <section class="px-4 pt-4">
            @includeIf('frontend.carer.partials.resident-card', ['resident' => $resident])
        </section>

        {{-- Quick actions (placeholders for now) --}}
        <section class="px-4 pt-4 pb-6">
            <div class="row g-3">
                <div class="col-6">
                    <div class="p-3 rounded-3 bg-white shadow-sm">
                        <p class="mb-1 small text-muted">Profile</p>
                        <p class="mb-0 fw-semibold">Key details</p>
                    </div>
                </div>

                <div class="col-6">
                    <div class="p-3 rounded-3 bg-white shadow-sm">
                        <p class="mb-1 small text-muted">Care Plan</p>
                        <p class="mb-0 fw-semibold">Goals & support</p>
                    </div>
                </div>

                <div class="col-6">
                    <div class="p-3 rounded-3 bg-white shadow-sm">
                        <p class="mb-1 small text-muted">Risk</p>
                        <p class="mb-0 fw-semibold">Risks & controls</p>
                    </div>
                </div>

                <div class="col-6">
                    <div class="p-3 rounded-3 bg-white shadow-sm">
                        <p class="mb-1 small text-muted">DSR</p>
                        <p class="mb-0 fw-semibold">Daily notes (next)</p>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection