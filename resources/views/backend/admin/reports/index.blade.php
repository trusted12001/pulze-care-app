@extends('layouts.admin')

@section('title', 'Reports Overview')

@section('content')
    <div class="reports-page py-4">
        {{-- Page header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-2">
            <div>
                <h1 class="h3 mb-1 text-dark">
                    Reports Overview
                </h1>
                <p class="text-muted small mb-0">
                    High-level metrics across staff, service users, locations and assignments.
                </p>
            </div>

            <span class="badge bg-success-subtle text-success d-inline-flex align-items-center gap-1 px-3 py-2">
                <i class="ph ph-chart-line-up"></i>
                <span class="small">Report at a glance</span>
            </span>
        </div>

        {{-- Top Stat Cards --}}
        <div class="row g-3 g-md-4 mb-4">
            {{-- Total Staff --}}
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Total Staff</div>
                            <div class="fs-3 fw-semibold text-dark">
                                {{ number_format($totalStaff) }}
                            </div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-users-three fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Service Users --}}
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Service Users</div>
                            <div class="fs-3 fw-semibold text-dark">
                                {{ number_format($totalServiceUsers) }}
                            </div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-person-simple-run fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Locations --}}
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Locations</div>
                            <div class="fs-3 fw-semibold text-dark">
                                {{ number_format($totalLocations) }}
                            </div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-map-pin-line fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Assignments Created --}}
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Assignments Created</div>
                            <div class="fs-3 fw-semibold text-dark">
                                {{ number_format($totalAssignments) }}
                            </div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-clipboard-text fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Completed Assignments --}}
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Completed (Verified/Closed)</div>
                            <div class="fs-3 fw-semibold text-dark">
                                {{ number_format($completedAssignments) }}
                            </div>
                            @if ($totalAssignments > 0)
                                <div class="small text-success mt-1">
                                    {{ number_format(($completedAssignments / max($totalAssignments, 1)) * 100, 1) }}% of all
                                    assignments
                                </div>
                            @endif
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-check-circle fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Hours Worked (placeholder) --}}
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase text-muted small mb-1">Total Hours Worked</div>
                            <div class="fs-3 fw-semibold text-dark">
                                {{ number_format($totalHoursWorked, 1) }} hrs
                            </div>
                            <div class="small text-muted mt-1">
                                Approximation • will be linked to shifts later
                            </div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ph ph-clock-afternoon fs-4 text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- This Week's Activity Snapshot --}}
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <h2 class="h6 mb-0 text-dark">This Week’s Activity</h2>
            <span class="badge bg-primary-subtle text-primary small">
                <i class="ph ph-calendar-check me-1"></i>
                {{ \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->format('d M') }} –
                {{ \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::SUNDAY)->format('d M') }}
            </span>
        </div>

        <div class="row g-3 g-md-4 mb-4">
            {{-- Created this week --}}
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small mb-1">Created</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fs-4 fw-semibold text-dark">
                                {{ number_format($assignmentsCreatedThisWeek) }}
                            </div>
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                style="width: 38px; height: 38px;">
                                <i class="ph ph-file-plus fs-5 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Started this week --}}
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small mb-1">Started</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fs-4 fw-semibold text-dark">
                                {{ number_format($assignmentsStartedThisWeek) }}
                            </div>
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                style="width: 38px; height: 38px;">
                                <i class="ph ph-play-circle fs-5 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submitted this week --}}
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small mb-1">Submitted</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fs-4 fw-semibold text-dark">
                                {{ number_format($assignmentsSubmittedThisWeek) }}
                            </div>
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                style="width: 38px; height: 38px;">
                                <i class="ph ph-upload-simple fs-5 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Completed this week --}}
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small mb-1">Completed</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fs-4 fw-semibold text-dark">
                                {{ number_format($assignmentsCompletedThisWeek) }}
                            </div>
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                style="width: 38px; height: 38px;">
                                <i class="ph ph-flag-checkered fs-5 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Overdue --}}
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small mb-1">Overdue</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fs-4 fw-semibold {{ $overdueAssignments > 0 ? 'text-danger' : 'text-dark' }}">
                                {{ number_format($overdueAssignments) }}
                            </div>
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                style="width: 38px; height: 38px;">
                                <i class="ph ph-warning-circle fs-5 text-danger"></i>
                            </div>
                        </div>
                        @if($overdueAssignments > 0)
                            <div class="small text-danger mt-1">Needs attention</div>
                        @else
                            <div class="small text-muted mt-1">All caught up</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Spacer / future metric (e.g. average completion time) --}}
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex flex-column justify-content-center text-center text-muted small">
                        <i class="ph ph-dots-three-outline fs-4 mb-1"></i>
                        Future metric
                    </div>
                </div>
            </div>
        </div>



        {{-- Staff Performance (This Week) --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2 class="h6 mb-1 text-dark">Staff Performance (This Week)</h2>
                        <p class="text-muted small mb-0">
                            Top staff based on assignments created this week and their completion status.
                        </p>
                    </div>
                    <span class="badge bg-info-subtle text-info small d-inline-flex align-items-center gap-1">
                        <i class="ph ph-users-three"></i>
                        <span>Quick overview</span>
                    </span>
                </div>

                @if (count($staffPerformance) === 0)
                    <p class="text-muted small mb-0">
                        No staff activity recorded for this week yet.
                    </p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr class="text-muted small">
                                    <th scope="col">Staff</th>
                                    <th scope="col" class="text-center">Assigned</th>
                                    <th scope="col" class="text-center">Completed</th>
                                    <th scope="col" class="text-center">Overdue</th>
                                    <th scope="col" class="text-center">Completion Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staffPerformance as $row)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;">
                                                    <i class="ph ph-user fs-6 text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark small">
                                                        {{ $row['name'] }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        ID: {{ $row['user_id'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-semibold text-dark">
                                                {{ $row['total_assigned'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-semibold text-success">
                                                {{ $row['completed'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
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
                                        <td class="text-center">
                                            <span class="badge
                                                                                                                        @if ($row['completion_rate'] >= 80)
                                                                                                                            bg-success-subtle text-success
                                                                                                                        @elseif ($row['completion_rate'] >= 50)
                                                                                                                            bg-warning-subtle text-warning
                                                                                                                        @else
                                                                                                                            bg-danger-subtle text-danger
                                                                                                                        @endif
                                                                                                                        small">
                                                {{ number_format($row['completion_rate'], 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>


        {{-- Quick Navigation Tiles --}}
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <h2 class="h6 mb-0 text-dark">Quick Reports</h2>
            <span class="text-muted small">
                Jump straight into detailed views.
            </span>
        </div>

        <div class="row g-3 g-md-4 mb-4">
            {{-- Assignments Report --}}
            <div class="col-12 col-md-4 col-xl-4">
                <a href="{{ route('backend.admin.reports.assignments') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-shadow-sm">
                        <div class="card-body d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="ph ph-clipboard-text text-primary fs-5"></i>
                            </div>
                            <div>
                                <h3 class="h6 mb-1 text-dark">Assignments Report</h3>
                                <p class="text-muted small mb-0">
                                    Filter and analyse assignments by status, location, staff and date.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Staff Performance --}}
            <div class="col-12 col-md-4 col-xl-4">
                <a href="{{ route('backend.admin.reports.staff') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-shadow-sm">
                        <div class="card-body d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="ph ph-users-three text-success fs-5"></i>
                            </div>
                            <div>
                                <h3 class="h6 mb-1 text-dark">Staff Performance</h3>
                                <p class="text-muted small mb-0">
                                    See completion rates, overdue work and workload by staff member.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Service User Activity --}}
            <div class="col-12 col-md-4 col-xl-4">
                <a href="{{ route('backend.admin.reports.service-users') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-shadow-sm">
                        <div class="card-body d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-info-subtle d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="ph ph-person-arms-spread text-info fs-5"></i>
                            </div>
                            <div>
                                <h3 class="h6 mb-1 text-dark">Service User Activity</h3>
                                <p class="text-muted small mb-0">
                                    Track how often service users are included in assignments and interactions.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Location Workload --}}
            <div class="col-12 col-md-4 col-xl-4">
                <a href="{{ route('backend.admin.reports.locations') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-shadow-sm">
                        <div class="card-body d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="ph ph-map-pin-line text-warning fs-5"></i>
                            </div>
                            <div>
                                <h3 class="h6 mb-1 text-dark">Location Workload</h3>
                                <p class="text-muted small mb-0">
                                    Compare assignments across locations to balance workload.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Evidence Report --}}
            <div class="col-12 col-md-4 col-xl-4">
                <a href="{{ route('backend.admin.reports.evidence') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-shadow-sm">
                        <div class="card-body d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="ph ph-image-square text-secondary fs-5"></i>
                            </div>
                            <div>
                                <h3 class="h6 mb-1 text-dark">Evidence Report</h3>
                                <p class="text-muted small mb-0">
                                    Review photo and GPS evidence used to verify care activities.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Hours & Shifts --}}
            <div class="col-12 col-md-4 col-xl-4">
                <a href="{{ route('backend.admin.reports.hours') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-shadow-sm">
                        <div class="card-body d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-dark-subtle d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="ph ph-clock-afternoon text-dark fs-5"></i>
                            </div>
                            <div>
                                <h3 class="h6 mb-1 text-dark">Hours & Shift Logs</h3>
                                <p class="text-muted small mb-0">
                                    Future link into shift management and hours worked per staff.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>



        {{-- Placeholder for future charts / deeper reports --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <h2 class="h6 mb-1 text-dark">
                        Activity & Workload Snapshots
                    </h2>
                    <p class="text-muted small mb-0">
                        This area will soon show charts for assignments by status, staff performance, service user
                        activity, locations workload, and evidence usage.
                    </p>
                </div>
                <span class="badge bg-light text-muted d-inline-flex align-items-center gap-1 px-3 py-2">
                    <i class="ph ph-chart-pie-slice"></i>
                    <span class="small">Coming soon</span>
                </span>
            </div>
        </div>
    </div>
@endsection