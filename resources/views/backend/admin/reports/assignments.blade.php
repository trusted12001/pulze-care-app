@extends('layouts.admin')

@section('title', 'Assignments Report')

@section('content')
    <div class="py-4">
        {{-- Page header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
            <div>
                <h1 class="h4 mb-1 text-dark">Assignments Report</h1>
                <p class="text-muted small mb-0">
                    Analyse assignments with filters by date, status, staff, service user and location.
                </p>
            </div>

            <span class="badge bg-primary-subtle text-primary d-inline-flex align-items-center gap-1 px-3 py-2">
                <i class="ph ph-clipboard-text"></i>
                <span class="small">Detailed report</span>
            </span>
        </div>

        {{-- Summary cards for current filter --}}
        <div class="row g-3 g-md-4 mb-4">
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Total in selection</div>
                            <div class="fs-3 fw-semibold text-dark">
                                {{ number_format($totalInRange) }}
                            </div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-list-bullets fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Completed (Verified/Closed)</div>
                            <div class="fs-3 fw-semibold text-success">
                                {{ number_format($completedInRange) }}
                            </div>
                            @if($totalInRange > 0)
                                <div class="small text-muted mt-1">
                                    {{ number_format(($completedInRange / max($totalInRange, 1)) * 100, 1) }}% of selection
                                </div>
                            @endif
                        </div>
                        <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-check-circle fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Overdue</div>
                            <div class="fs-3 fw-semibold {{ $overdueInRange > 0 ? 'text-danger' : 'text-dark' }}">
                                {{ number_format($overdueInRange) }}
                            </div>
                            <div class="small mt-1 {{ $overdueInRange > 0 ? 'text-danger' : 'text-muted' }}">
                                {{ $overdueInRange > 0 ? 'Requires attention' : 'All caught up' }}
                            </div>
                        </div>
                        <div class="rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-warning-circle fs-4 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter panel --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h2 class="h6 mb-0 text-dark">Filters</h2>
                    <a href="{{ route('backend.admin.reports.assignments') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ph ph-arrow-counter-clockwise me-1"></i> Reset
                    </a>
                </div>

                <form method="GET" action="{{ route('backend.admin.reports.assignments') }}">
                    <div class="row g-3">
                        {{-- Date from --}}
                        <div class="col-12 col-md-4 col-lg-2">
                            <label for="date_from" class="form-label small text-muted mb-1">Date from</label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                                class="form-control form-control-sm">
                        </div>

                        {{-- Date to --}}
                        <div class="col-12 col-md-4 col-lg-2">
                            <label for="date_to" class="form-label small text-muted mb-1">Date to</label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                                class="form-control form-control-sm">
                        </div>

                        {{-- Status --}}
                        <div class="col-12 col-md-4 col-lg-2">
                            <label for="status" class="form-label small text-muted mb-1">Status</label>
                            <select id="status" name="status" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(request('status') === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Staff --}}
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="staff_id" class="form-label small text-muted mb-1">Staff</label>
                            <select id="staff_id" name="staff_id" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach ($staffOptions as $staff)
                                    @php
                                        $staffName = trim(($staff->first_name ?? '') . ' ' . ($staff->last_name ?? ''));
                                        if ($staffName === '') {
                                            $staffName = $staff->email ?? 'User #' . $staff->id;
                                        }
                                    @endphp
                                    <option value="{{ $staff->id }}"
                                        @selected((string) request('staff_id') === (string) $staff->id)>
                                        {{ $staffName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Location --}}
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="location_id" class="form-label small text-muted mb-1">Location</label>
                            <select id="location_id" name="location_id" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach ($locationOptions as $location)
                                    <option value="{{ $location->id }}"
                                        @selected((string) request('location_id') === (string) $location->id)>
                                        {{ $location->name ?? ('Location #' . $location->id) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Service User --}}
                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="service_user_id" class="form-label small text-muted mb-1">Service User</label>
                            <select id="service_user_id" name="service_user_id" class="form-select form-select-sm">
                                <option value="">All</option>
                                @foreach ($serviceUserOptions as $su)
                                    @php
                                        $suName = trim(($su->first_name ?? '') . ' ' . ($su->last_name ?? ''));
                                        if ($suName === '') {
                                            $suName = 'Service User #' . $su->id;
                                        }
                                    @endphp
                                    <option value="{{ $su->id }}"
                                        @selected((string) request('service_user_id') === (string) $su->id)>
                                        {{ $suName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Submit --}}
                        <div class="col-12 col-md-6 col-lg-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="ph ph-funnel-simple me-1"></i> Apply filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Status Breakdown Chart --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <h2 class="h6 mb-1 text-dark">Status Breakdown</h2>
                        <p class="text-muted small mb-0">
                            Distribution of assignment statuses for the current filter selection.
                        </p>
                    </div>
                    <span class="text-muted small">
                        Total: {{ number_format($totalInRange) }}
                    </span>
                </div>

                @if ($totalInRange === 0 || empty($statusBreakdown))
                    <p class="text-muted small mb-0">
                        No data available for the selected filters.
                    </p>
                @else
                    <div style="max-width: 700px; height: 260px;">
                        <canvas id="assignmentsStatusChart" style="width: 100%; height: 100%;"></canvas>
                    </div>
                @endif
            </div>
        </div>

        {{-- Results table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h2 class="h6 mb-0 text-dark">Results</h2>
                    <span class="text-muted small">
                        Showing {{ $assignments->firstItem() ?? 0 }} – {{ $assignments->lastItem() ?? 0 }} of
                        {{ $assignments->total() }} records
                    </span>
                </div>

                @php
                    $statusBadgeClasses = [
                        'draft' => 'bg-secondary-subtle text-secondary',
                        'scheduled' => 'bg-info-subtle text-info',
                        'in_progress' => 'bg-primary-subtle text-primary',
                        'submitted' => 'bg-warning-subtle text-warning',
                        'verified' => 'bg-success-subtle text-success',
                        'closed' => 'bg-success-subtle text-success',
                        'cancelled' => 'bg-dark-subtle text-dark',
                        'overdue' => 'bg-danger-subtle text-danger',
                    ];
                @endphp

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th scope="col">Code</th>
                                <th scope="col">Title</th>
                                <th scope="col">Service User</th>
                                <th scope="col">Location</th>
                                <th scope="col">Assigned To</th>
                                <th scope="col">Status</th>
                                <th scope="col">Due</th>
                                <th scope="col">Created</th>
                                <th scope="col">Verified At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($assignments as $assignment)
                                @php
                                    $su = $assignment->resident;
                                    $suName = $su ? trim(($su->first_name ?? '') . ' ' . ($su->last_name ?? '')) : null;
                                    $suName = $suName !== '' ? $suName : ($su ? ('Service User #' . $su->id) : '—');

                                    $loc = $assignment->location;
                                    $locName = $loc->name ?? ($loc ? 'Location #' . $loc->id : '—');

                                    $staff = $assignment->assignee;
                                    $staffName = $staff ? trim(($staff->first_name ?? '') . ' ' . ($staff->last_name ?? '')) : null;
                                    $staffName = $staffName !== '' ? $staffName : ($staff ? ($staff->email ?? ('User #' . $staff->id)) : '—');

                                    $status = $assignment->status;
                                    $badgeClass = $statusBadgeClasses[$status] ?? 'bg-secondary-subtle text-secondary';
                                @endphp
                                <tr>
                                    <td class="small">
                                        <span class="fw-semibold">{{ $assignment->code }}</span>
                                    </td>
                                    <td class="small">
                                        {{ $assignment->title }}
                                    </td>
                                    <td class="small">
                                        {{ $suName }}
                                    </td>
                                    <td class="small">
                                        {{ $locName }}
                                    </td>
                                    <td class="small">
                                        {{ $staffName }}
                                    </td>
                                    <td class="small">
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </td>
                                    <td class="small">
                                        {{ $assignment->due_at ? $assignment->due_at->format('d M Y H:i') : '—' }}
                                    </td>
                                    <td class="small">
                                        {{ $assignment->created_at ? $assignment->created_at->format('d M Y H:i') : '—' }}
                                    </td>
                                    <td class="small">
                                        {{ $assignment->verified_at ? $assignment->verified_at->format('d M Y H:i') : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted small py-4">
                                        No assignments found for the selected filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $assignments->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Inline Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            const breakdown = @json($statusBreakdown ?? []);

            if (!breakdown || Object.keys(breakdown).length === 0) {
                return;
            }

            const statusLabelsMap = {
                draft: 'Draft',
                scheduled: 'Scheduled',
                in_progress: 'In Progress',
                submitted: 'Submitted',
                verified: 'Verified',
                closed: 'Closed',
                cancelled: 'Cancelled',
                overdue: 'Overdue'
            };

            const statusOrder = [
                'draft',
                'scheduled',
                'in_progress',
                'submitted',
                'verified',
                'closed',
                'cancelled',
                'overdue'
            ];

            const labels = [];
            const data = [];

            statusOrder.forEach((key) => {
                if (breakdown[key] !== undefined) {
                    labels.push(statusLabelsMap[key] || key);
                    data.push(breakdown[key]);
                }
            });

            const canvas = document.getElementById('assignmentsStatusChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Assignments',
                        data: data,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.parsed.y + ' assignments';
                                }
                            }
                        }
                    }
                }
            });
        })();
    </script>
@endsection