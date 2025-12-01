@extends('layouts.admin')

@section('title', 'Evidence Report')

@section('content')
    <div class="py-4">

        {{-- Page header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
            <div>
                <h1 class="h4 mb-1 text-dark">Evidence Report</h1>
                <p class="text-muted small mb-0">
                    View all recorded evidence including photos, notes, and GPS captures.
                </p>
            </div>

            <span class="badge bg-primary-subtle text-primary d-inline-flex align-items-center gap-1 px-3 py-2">
                <i class="ph ph-camera"></i>
                <span class="small">Evidence analytics</span>
            </span>
        </div>

        {{-- Summary cards --}}
        <div class="row g-3 g-md-4 mb-4">
            {{-- Total Evidence --}}
            <div class="col-12 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Total Evidence</div>
                            <div class="fs-3 fw-semibold text-dark">{{ number_format($summary['total']) }}</div>
                        </div>
                        <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-camera text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Photos --}}
            <div class="col-12 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Photos</div>
                            <div class="fs-3 fw-semibold text-dark">{{ number_format($summary['photos']) }}</div>
                        </div>
                        <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-image text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="col-12 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Notes</div>
                            <div class="fs-3 fw-semibold text-dark">{{ number_format($summary['notes']) }}</div>
                        </div>
                        <div class="rounded-circle bg-info-subtle d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-note-pencil text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GPS --}}
            <div class="col-12 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">GPS Captures</div>
                            <div class="fs-3 fw-semibold text-dark">{{ number_format($summary['gps']) }}</div>
                        </div>
                        <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-map-pin text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h2 class="h6 mb-0 text-dark">Filters</h2>
                    <a href="{{ route('backend.admin.reports.evidence') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ph ph-arrow-counter-clockwise me-1"></i> Reset
                    </a>
                </div>

                <form method="GET" action="{{ route('backend.admin.reports.evidence') }}">
                    <div class="row g-3">

                        {{-- Date from --}}
                        <div class="col-12 col-md-4 col-lg-2">
                            <label class="form-label small text-muted mb-1">Date from</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="form-control form-control-sm">
                        </div>

                        {{-- Date to --}}
                        <div class="col-12 col-md-4 col-lg-2">
                            <label class="form-label small text-muted mb-1">Date to</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="form-control form-control-sm">
                        </div>

                        {{-- Evidence type --}}
                        <div class="col-12 col-md-4 col-lg-2">
                            <label class="form-label small text-muted mb-1">Type</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach($typeOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(request('type') == $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Staff --}}
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label small text-muted mb-1">Staff</label>
                            <select name="staff_id" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach ($staffOptions as $staff)
                                    @php
                                        $staffName = trim(($staff->first_name ?? '') . ' ' . ($staff->last_name ?? ''));
                                        if ($staffName === '')
                                            $staffName = $staff->email;
                                    @endphp
                                    <option value="{{ $staff->id }}" @selected(request('staff_id') == $staff->id)>
                                        {{ $staffName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Location --}}
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label small text-muted mb-1">Location</label>
                            <select name="location_id" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach ($locationOptions as $loc)
                                    <option value="{{ $loc->id }}" @selected(request('location_id') == $loc->id)>
                                        {{ $loc->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Service User --}}
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label small text-muted mb-1">Service User</label>
                            <select name="service_user_id" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach ($serviceUserOptions as $su)
                                    @php
                                        $suName = trim(($su->first_name ?? '') . ' ' . ($su->last_name ?? '')) ?: 'Service User #' . $su->id;
                                    @endphp
                                    <option value="{{ $su->id }}" @selected(request('service_user_id') == $su->id)>
                                        {{ $suName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Apply Filters --}}
                        <div class="col-12 col-md-6 col-lg-2 d-flex align-items-end">
                            <button class="btn btn-sm btn-primary w-100">
                                <i class="ph ph-funnel-simple me-1"></i> Apply
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Evidence chart: top staff --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <h2 class="h6 mb-1 text-dark">Top Staff by Evidence Collected</h2>
                        <p class="text-muted small mb-0">Top contributors based on evidence count.</p>
                    </div>
                    <span class="text-muted small">Max 10 shown</span>
                </div>

                @if (empty($chartLabels))
                    <p class="text-muted small mb-0">No data available for chart.</p>
                @else
                    <div style="max-width: 800px; height: 260px;">
                        <canvas id="staffEvidenceChart"></canvas>
                    </div>
                @endif
            </div>
        </div>

        {{-- Evidence table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h2 class="h6 mb-0 text-dark">Evidence Items</h2>
                    <span class="text-muted small">{{ number_format($summary['total']) }} total</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th>Type</th>
                                <th>Preview</th>
                                <th>Staff</th>
                                <th>Location</th>
                                <th>Service User</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($evidence as $item)
                                <tr>
                                    <td class="small text-capitalize fw-semibold">
                                        {{ $item->file_type }}
                                    </td>

                                    {{-- Preview --}}
                                    <td>
                                        @php
                                            $path = $item->file_path ?? '';
                                            $fileType = strtolower($item->file_type ?? '');
                                            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                                            $isImage = $path && (
                                                in_array($fileType, ['photo', 'image', 'image/jpeg', 'image/png', 'image/jpg'])
                                                || in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])
                                            );

                                            $hasGps = !is_null($item->lat) && !is_null($item->lng);
                                        @endphp

                                        @if ($isImage)
                                            <img src="{{ asset('storage/' . $path) }}"
                                                style="width: 48px; height: 48px; object-fit: cover;" class="rounded border">
                                        @elseif ($hasGps)
                                            <span class="badge bg-success-subtle text-success small">
                                                <i class="ph ph-map-pin me-1"></i> GPS
                                            </span>
                                        @elseif($path)
                                            <span class="d-inline-flex align-items-center gap-1 text-muted small">
                                                <i class="ph ph-paperclip"></i>
                                                <span>File</span>
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>


                                    {{-- Staff --}}
                                    <td class="small">
                                        @php
                                            $staff = $staffMap->get($item->created_by ?? null);
                                        @endphp

                                        @if ($staff)
                                            {{ trim(($staff->first_name ?? '') . ' ' . ($staff->last_name ?? '')) ?: ($staff->email ?? ('User #' . $staff->id)) }}
                                        @else
                                            <span class="text-muted">Unknown</span>
                                        @endif
                                    </td>


                                    {{-- Location --}}
                                    <td class="small">
                                        {{ optional(optional($item->assignment)->location)->name ?? '—' }}
                                    </td>


                                    {{-- Service User --}}
                                    <td class="small">
                                        @if ($item->assignment->resident)
                                            {{ $item->assignment->resident->first_name }}
                                            {{ $item->assignment->resident->last_name }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    {{-- Date --}}
                                    <td class="small text-nowrap">
                                        {{ \Carbon\Carbon::parse($item->captured_at)->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted small py-4">
                                        No evidence found for current filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            const labels = @json($chartLabels ?? []);
            const data = @json($chartTotals ?? []);

            if (!labels.length) return;

            const ctx = document.getElementById('staffEvidenceChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Evidence Items',
                        data: data,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.parsed.y} items`
                            }
                        }
                    }
                }
            });
        })();
    </script>

@endsection