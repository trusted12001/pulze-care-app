@extends('layouts.carer')

@section('title', 'Resident Profile')

@section('content')
    @php
        $residentName = trim(($resident->full_name ?? '') ?: (
            trim(($resident->first_name ?? '') . ' ' . ($resident->last_name ?? ''))
        )) ?: ($resident->name ?? 'Resident');

        $photo = $resident->passport_photo_url
            ?? ($resident->photo_path ? asset('storage/' . $resident->photo_path) : null)
            ?? asset('assets/img/top-doctor-1.png');

        $roomLabel = $resident->room_label
            ?? ($resident->room_number ? ('Room ' . $resident->room_number) : null)
            ?? 'Room not set';

        $locationLabel = optional($resident->location)->name ?? null;

        $dob = $resident->date_of_birth ? \Carbon\Carbon::parse($resident->date_of_birth)->format('d/m/Y') : 'Unknown';
        $age = $resident->date_of_birth ? \Carbon\Carbon::parse($resident->date_of_birth)->age : null;

        $tags = $resident->tags ?? null;
        if (is_string($tags)) {
            $decoded = json_decode($tags, true);
            if (json_last_error() === JSON_ERROR_NONE)
                $tags = $decoded;
        }
        $tags = is_array($tags) ? $tags : [];

        $isActive = ($resident->status ?? 'active') === 'active';
    @endphp

    <main class="resident-profile-screen position-relative top-0 start-0 end-0 pb-5">

        {{-- ================= HEADER WITH BACK BUTTON ================= --}}
        <section class="resident-header-section w-100 px-3 px-md-4 pt-3 pb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('frontend.carer.index') }}" 
                    class="back-button-header d-flex align-items-center gap-2 text-decoration-none">
                    <i class="ph ph-arrow-left" style="font-size: 1.25rem; color: var(--n1);"></i>
                    <span class="fw-medium" style="color: var(--n1); font-size: 0.9375rem;">Back</span>
                </a>
            </div>

            {{-- Resident Profile Header --}}
            <div class="d-flex align-items-center gap-3">
                <div class="resident-avatar-wrapper position-relative">
                    <div class="resident-avatar rounded-circle overflow-hidden" 
                        style="width: 80px; height: 80px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                        <img src="{{ $photo }}" alt="{{ $residentName }}" 
                            class="w-100 h-100" style="object-fit: cover;" />
                    </div>
                    @if($isActive)
                        <span class="position-absolute bottom-0 end-0 rounded-circle border border-white"
                            style="width: 16px; height: 16px; background: #22c55e; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);"></span>
                    @endif
                </div>

                <div class="flex-grow-1 min-w-0">
                    <h2 class="mb-1 fw-bold" style="font-size: 1.5rem; color: var(--n1); line-height: 1.2;">
                        {{ $residentName }}
                    </h2>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge {{ $isActive ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} px-2 py-1"
                            style="font-size: 0.75rem; font-weight: 500;">
                            {{ ucfirst($resident->status ?? 'active') }}
                        </span>
                        @if($age)
                            <span class="text-muted small">{{ $age }} years old</span>
                        @endif
                    </div>
                    <div class="d-flex flex-column gap-1">
                        <p class="mb-0 small text-muted d-flex align-items-center gap-1">
                            <i class="ph-fill ph-map-pin" style="font-size: 0.875rem;"></i>
                            <span>{{ $roomLabel }}</span>
                            @if($locationLabel)
                                <span class="mx-1">•</span>
                                <span>{{ $locationLabel }}</span>
                            @endif
                        </p>
                        <p class="mb-0 small text-muted d-flex align-items-center gap-1">
                            <i class="ph-fill ph-calendar-blank" style="font-size: 0.875rem;"></i>
                            <span>DOB: {{ $dob }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </section>
        {{-- ================= HEADER END ================= --}}

        {{-- ================= QUICK ACTIONS ================= --}}
        <section class="px-3 px-md-4 pt-2 pb-3">
            <h4 class="mb-3 fw-semibold" style="font-size: 1rem; color: var(--n1);">Quick Actions</h4>
            <div class="row g-3">
                <div class="col-6">
                    <a href="#" class="quick-action-card text-decoration-none d-block">
                        <div class="p-3 rounded-4 bg-white shadow-sm border-0 h-100"
                            style="transition: all 0.2s ease; border: 1.5px solid transparent;">
                            <div class="d-flex flex-column align-items-center text-center gap-2">
                                <div class="quick-action-icon d-flex align-items-center justify-content-center rounded-3"
                                    style="width: 48px; height: 48px; background: linear-gradient(135deg, rgba(0, 146, 129, 0.1) 0%, rgba(0, 146, 129, 0.05) 100%);">
                                    <i class="ph ph-user-circle" style="font-size: 1.5rem; color: var(--p1);"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold" style="font-size: 0.875rem; color: var(--n1);">Profile</p>
                                    <p class="mb-0 small text-muted" style="font-size: 0.75rem;">Key details</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-6">
                    <a href="#" class="quick-action-card text-decoration-none d-block">
                        <div class="p-3 rounded-4 bg-white shadow-sm border-0 h-100"
                            style="transition: all 0.2s ease; border: 1.5px solid transparent;">
                            <div class="d-flex flex-column align-items-center text-center gap-2">
                                <div class="quick-action-icon d-flex align-items-center justify-content-center rounded-3"
                                    style="width: 48px; height: 48px; background: linear-gradient(135deg, rgba(0, 146, 129, 0.1) 0%, rgba(0, 146, 129, 0.05) 100%);">
                                    <i class="ph ph-clipboard-text" style="font-size: 1.5rem; color: var(--p1);"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold" style="font-size: 0.875rem; color: var(--n1);">Care Plan</p>
                                    <p class="mb-0 small text-muted" style="font-size: 0.75rem;">Goals & support</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-6">
                    <a href="#" class="quick-action-card text-decoration-none d-block">
                        <div class="p-3 rounded-4 bg-white shadow-sm border-0 h-100"
                            style="transition: all 0.2s ease; border: 1.5px solid transparent;">
                            <div class="d-flex flex-column align-items-center text-center gap-2">
                                <div class="quick-action-icon d-flex align-items-center justify-content-center rounded-3"
                                    style="width: 48px; height: 48px; background: linear-gradient(135deg, rgba(247, 85, 85, 0.1) 0%, rgba(247, 85, 85, 0.05) 100%);">
                                    <i class="ph ph-warning" style="font-size: 1.5rem; color: var(--y300);"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold" style="font-size: 0.875rem; color: var(--n1);">Risk</p>
                                    <p class="mb-0 small text-muted" style="font-size: 0.75rem;">Risks & controls</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-6">
                    <a href="#" class="quick-action-card text-decoration-none d-block">
                        <div class="p-3 rounded-4 bg-white shadow-sm border-0 h-100"
                            style="transition: all 0.2s ease; border: 1.5px solid transparent;">
                            <div class="d-flex flex-column align-items-center text-center gap-2">
                                <div class="quick-action-icon d-flex align-items-center justify-content-center rounded-3"
                                    style="width: 48px; height: 48px; background: linear-gradient(135deg, rgba(0, 146, 129, 0.1) 0%, rgba(0, 146, 129, 0.05) 100%);">
                                    <i class="ph ph-note-pencil" style="font-size: 1.5rem; color: var(--p1);"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold" style="font-size: 0.875rem; color: var(--n1);">DSR</p>
                                    <p class="mb-0 small text-muted" style="font-size: 0.75rem;">Daily notes</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>
        {{-- ================= QUICK ACTIONS END ================= --}}

        {{-- ================= KEY INFORMATION ================= --}}
        <section class="px-3 px-md-4 pt-2 pb-6">
            <h4 class="mb-3 fw-semibold" style="font-size: 1rem; color: var(--n1);">Key Information</h4>
            
            <div class="d-flex flex-column gap-3">
                {{-- Medical Information --}}
                @if($resident->primary_diagnosis || $resident->allergies_summary)
                    <div class="info-card p-3 rounded-4 bg-white shadow-sm">
                        <div class="d-flex align-items-start gap-2 mb-2">
                            <div class="info-icon d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                                style="width: 40px; height: 40px; background: rgba(0, 146, 129, 0.1);">
                                <i class="ph ph-heartbeat" style="font-size: 1.25rem; color: var(--p1);"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-2 fw-semibold" style="font-size: 0.9375rem; color: var(--n1);">Medical</h5>
                                @if($resident->primary_diagnosis)
                                    <p class="mb-1 small" style="color: var(--n2);">
                                        <span class="fw-medium">Diagnosis:</span> {{ $resident->primary_diagnosis }}
                                    </p>
                                @endif
                                @if($resident->allergies_summary)
                                    <p class="mb-0 small" style="color: var(--n2);">
                                        <span class="fw-medium">Allergies:</span> {{ $resident->allergies_summary }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Care Plans --}}
                @if($resident->behaviour_support_plan || $resident->seizure_care_plan || $resident->diabetes_care_plan)
                    <div class="info-card p-3 rounded-4 bg-white shadow-sm">
                        <div class="d-flex align-items-start gap-2 mb-2">
                            <div class="info-icon d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                                style="width: 40px; height: 40px; background: rgba(0, 146, 129, 0.1);">
                                <i class="ph ph-clipboard" style="font-size: 1.25rem; color: var(--p1);"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-2 fw-semibold" style="font-size: 0.9375rem; color: var(--n1);">Care Plans</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($resident->behaviour_support_plan)
                                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">Behaviour Support</span>
                                    @endif
                                    @if($resident->seizure_care_plan)
                                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">Seizure Care</span>
                                    @endif
                                    @if($resident->diabetes_care_plan)
                                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">Diabetes Care</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Risk Indicators --}}
                @if($resident->fall_risk || $resident->choking_risk || $resident->pressure_ulcer_risk || $resident->wander_elopement_risk)
                    <div class="info-card p-3 rounded-4 bg-white shadow-sm">
                        <div class="d-flex align-items-start gap-2 mb-2">
                            <div class="info-icon d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                                style="width: 40px; height: 40px; background: rgba(247, 85, 85, 0.1);">
                                <i class="ph ph-warning" style="font-size: 1.25rem; color: var(--y300);"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-2 fw-semibold" style="font-size: 0.9375rem; color: var(--n1);">Risk Indicators</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($resident->fall_risk)
                                        <span class="badge {{ $resident->fall_risk === 'high' ? 'bg-danger-subtle text-danger' : ($resident->fall_risk === 'medium' ? 'bg-warning-subtle text-warning' : 'bg-info-subtle text-info') }} px-2 py-1" 
                                            style="font-size: 0.75rem;">
                                            Fall Risk: {{ ucfirst($resident->fall_risk) }}
                                        </span>
                                    @endif
                                    @if($resident->choking_risk)
                                        <span class="badge bg-warning-subtle text-warning px-2 py-1" style="font-size: 0.75rem;">
                                            Choking Risk
                                        </span>
                                    @endif
                                    @if($resident->pressure_ulcer_risk)
                                        <span class="badge bg-warning-subtle text-warning px-2 py-1" style="font-size: 0.75rem;">
                                            Pressure Ulcer Risk
                                        </span>
                                    @endif
                                    @if($resident->wander_elopement_risk)
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1" style="font-size: 0.75rem;">
                                            Wander/Elopement Risk
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Tags --}}
                @if(!empty($tags))
                    <div class="info-card p-3 rounded-4 bg-white shadow-sm">
                        <div class="d-flex align-items-start gap-2">
                            <div class="info-icon d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                                style="width: 40px; height: 40px; background: rgba(0, 146, 129, 0.1);">
                                <i class="ph ph-tag" style="font-size: 1.25rem; color: var(--p1);"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-2 fw-semibold" style="font-size: 0.9375rem; color: var(--n1);">Tags</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($tags as $tag)
                                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem; font-weight: 500;">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
        {{-- ================= KEY INFORMATION END ================= --}}

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
        .resident-profile-screen {
            min-height: 100vh;
            background: linear-gradient(to bottom, #f8fafc 0%, #ffffff 100%);
        }

        .back-button-header {
            transition: all 0.2s ease;
        }

        .back-button-header:hover {
            transform: translateX(-2px);
        }

        .back-button-header:active {
            transform: translateX(0);
        }

        .quick-action-card:hover > div {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            border-color: var(--p1) !important;
        }

        .quick-action-card:active > div {
            transform: translateY(0);
        }

        .info-card {
            transition: all 0.2s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
    @endpush

@endsection
