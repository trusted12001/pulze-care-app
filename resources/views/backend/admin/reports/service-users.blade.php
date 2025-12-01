@extends('layouts.admin')

@section('title', 'Service User Activity Report')

@section('content')
    <div class="py-4">
        {{-- Page header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
            <div>
                <h1 class="h4 mb-1 text-dark">Service User Activity Report</h1>
                <p class="text-muted small mb-0">
                    See how often service users are included in assignments, and their completion and overdue patterns.
                </p>
            </div>

            <span class="badge bg-info-subtle text-info d-inline-flex align-items-center gap-1 px-3 py-2">
                <i class="ph ph-person-arms-spread"></i>
                <span class="small">Service user insights</span>
            </span>
        </div>

        {{-- Summary cards --}}
        <div class="row g-3 g-md-4 mb-4">
            {{-- Service users with assignments --}}
            <div class="col-12 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Service users in selection</div>
                            <div class="fs-3 fw-semibold text-dark">
                                {{ number_format($totalServiceUsersWithAssignments) }}
                            </div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-users-three fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total assignments --}}
            <div class="col-12 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Assignments in selection</div>
                            <div class="fs-3 fw-semibold text-dark">
                                {{ number_format($totalAssignmentsInRange) }}
                            </div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-clipboard-text fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Overall completion rate --}}
            <div class="col-12 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Overall completion rate</div>
                            <div class="fs-3 fw-semibold text-success">
                                {{ number_format($overallCompletionRate, 1) }}%
                            </div>
                        </div>
                        <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-check-circle fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total overdue --}}
            <div class="col-12 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Total overdue</div>
                            <div class="fs-3 fw-semibold {{ $totalOverdueInRange > 0 ? 'text-danger' : 'text-dark' }}">
                                {{ number_format($totalOverdueInRange) }}
                            </div>
                            <div class="small mt-1 {{ $totalOverdueInRange > 0 ? 'text-danger' : 'text-muted' }}">
                                {{ $totalOverdueInRange > 0 ? 'Requires attention' : 'All caught up' }}
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
                    <a href="{{ route('backend.admin.reports.service-users') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ph ph-arrow-counter-clockwise me-1"></i> Reset
                    </a>
                </div>

                <form method="GET" action="{{ route('backend.admin.reports.service-users') }}">
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

        {{-- Activity chart --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <h2 class="h6 mb-1 text-dark">Most Active Service Users</h2>
                        <p class="text-muted small mb-0">
                            Top service users by number of assignments in the current selection.
                        </p>
                    </div>
                    <span class="text-muted small">
                        Max 10 service users shown
                    </span>
                </div>

                @if (empty($chartLabels))
                    <p class="text-muted small mb-0">
                        No service user data available for the selected filters.
                    </p>
                @else
                    <div style="max-width: 800px; height: 260px;">
                        <canvas id="serviceUserActivityChart" style="width: 100%; height: 100%;"></canvas>
                    </div>
                @endif
            </div>
        </div>

        {{-- Service user activity table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h2 class="h6 mb-0 text-dark">Service user breakdown</h2>
                    <span class="text-muted small">
                        {{ number_format($totalServiceUsersWithAssignments) }} service users listed
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th scope="col">Service User</th>
                                <th scope="col" class="text-center">Total Assignments</th>
                                <th scope="col" class="text-center">Completed</th>
                                <th scope="col" class="text-center">Overdue</th>
                                <th scope="col" class="text-center">Completion Rate</th>
                                <th scope="col" class="text-center">Last Assignment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($serviceUserPerformance as $row)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="ph ph-user fs-6 text-info"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark small">
                                                    {{ $row['name'] }}
                                                </div>
                                                <div class="text-muted small">
                                                    ID: {{ $row['service_user_id'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center small">
                                        <span class="fw-semibold text-dark">
                                            {{ $row['total_assignments'] }}
                                        </span>
                                    </td>
                                    <td class="text-center small">
                                        <span class="fw-semibold text-success">
                                            {{ $row['completed'] }}
                                        </span>
                                    </td>
                                    <td class="text-center small">
                                        @if ($row['overdue'] > 0)
                                            <span class="fw-semibold text-danger">
                                                {{ $row['overdue'] }}
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                0
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center small">
                                        <span class="badge
                                                    @if ($row['completion_rate'] >= 80)
                                                        bg-success-subtle text-success
                                                    @elseif ($row['completion_rate'] >= 50)
                                                        bg-warning-subtle text-warning
                                                    @else
                                                        bg-danger-subtle text-danger
                                                    @endif
                                                    ">
                                            {{ number_format($row['completion_rate'], 1) }}%
                                        </span>
                                    </td>
                                    <td class="text-center small">
                                        @if (!empty($row['last_assignment_at']))
                                            {{ $row['last_assignment_at']->format('d M Y H:i') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted small py-4">
                                        No service user activity found for the selected filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- Inline Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            const labels = @json($chartLabels ?? []);
            const data = @json($chartAssignments ?? []);

            if (!labels.length || !data.length) {
                return;
            }

            const canvas = document.getElementById('serviceUserActivityChart');
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