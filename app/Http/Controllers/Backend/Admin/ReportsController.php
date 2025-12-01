<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ServiceUser as Resident;
use App\Models\Location;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AssignmentEvidence;


class ReportsController extends Controller
{
    /**
     * Display the main reports dashboard.
     */
    public function index(Request $request)
    {
        // High-level stats
        $totalStaff = User::count();
        $totalServiceUsers = Resident::count();
        $totalLocations = Location::count();

        $totalAssignments = Assignment::count();
        $completedAssignments = Assignment::whereIn('status', ['verified', 'closed'])->count();

        // Placeholder for now – we’ll wire this to shifts later
        $totalHoursWorked = 0;

        // -------------------------------
        // This Week's Activity Snapshot
        // -------------------------------

        // Week runs Monday → Sunday
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = Carbon::now()->endOfWeek(Carbon::SUNDAY);
        $now         = Carbon::now();

        // Assignments created this week
        $assignmentsCreatedThisWeek = Assignment::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

        // "Started" this week = currently in_progress AND created this week
        $assignmentsStartedThisWeek = Assignment::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('status', 'in_progress')
            ->count();

        // "Submitted" this week = currently submitted AND created this week
        $assignmentsSubmittedThisWeek = Assignment::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('status', 'submitted')
            ->count();

        // Assignments verified/closed this week
        $assignmentsCompletedThisWeek = Assignment::whereBetween('verified_at', [$startOfWeek, $endOfWeek])->count();

        // Overdue assignments – due date in the past, not yet completed
        $overdueAssignments = Assignment::whereNotIn('status', ['verified', 'closed'])
            ->whereNotNull('due_at')
            ->where('due_at', '<', $now)
            ->count();

        // -----------------------------------
        // Staff Performance (This Week)
        // -----------------------------------
        $assignmentsThisWeek = Assignment::with('assignee') // assignee() -> belongsTo(User::class, 'assigned_to')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get();

        $staffPerformance = [];

        foreach ($assignmentsThisWeek as $assignment) {
            $user = $assignment->assignee;

            if (!$user) {
                continue;
            }

            if (!isset($staffPerformance[$user->id])) {
                $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));

                $staffPerformance[$user->id] = [
                    'user_id'         => $user->id,
                    'name'           => $fullName !== '' ? $fullName : 'Unknown',
                    'total_assigned' => 0,
                    'completed'      => 0,
                    'overdue'        => 0,
                ];
            }

            $staffPerformance[$user->id]['total_assigned']++;

            // Completed?
            if (in_array($assignment->status, ['verified', 'closed'])) {
                $staffPerformance[$user->id]['completed']++;
            }

            // Overdue? (not completed + due_at in the past)
            if (
                !in_array($assignment->status, ['verified', 'closed']) &&
                !is_null($assignment->due_at) &&
                $assignment->due_at < $now
            ) {
                $staffPerformance[$user->id]['overdue']++;
            }
        }

        // Add completion_rate and convert to indexed array
        foreach ($staffPerformance as &$row) {
            if ($row['total_assigned'] > 0) {
                $row['completion_rate'] = round(($row['completed'] / $row['total_assigned']) * 100, 1);
            } else {
                $row['completion_rate'] = 0;
            }
        }
        unset($row);

        // Sort by completed desc, then by name
        usort($staffPerformance, function ($a, $b) {
            if ($a['completed'] === $b['completed']) {
                return strcmp($a['name'], $b['name']);
            }
            return $b['completed'] <=> $a['completed'];
        });

        // Show only top 6 in the quick report
        $staffPerformance = array_slice($staffPerformance, 0, 6);

        return view('backend.admin.reports.index', compact(
            'totalStaff',
            'totalServiceUsers',
            'totalLocations',
            'totalAssignments',
            'completedAssignments',
            'totalHoursWorked',
            'assignmentsCreatedThisWeek',
            'assignmentsStartedThisWeek',
            'assignmentsSubmittedThisWeek',
            'assignmentsCompletedThisWeek',
            'overdueAssignments',
            'staffPerformance'
        ));
    }

    /**
     * Stub pages for deeper reports – we’ll flesh these out later.
     */
    public function assignmentsReport(Request $request)
    {
        $status        = $request->input('status');
        $staffId       = $request->input('staff_id');
        $locationId    = $request->input('location_id');
        $serviceUserId = $request->input('service_user_id');
        $dateFrom      = $request->input('date_from');
        $dateTo        = $request->input('date_to');

        // Status options for filter + labels
        $statusOptions = [
            'draft'        => 'Draft',
            'scheduled'    => 'Scheduled',
            'in_progress'  => 'In Progress',
            'submitted'    => 'Submitted',
            'verified'     => 'Verified',
            'closed'       => 'Closed',
            'cancelled'    => 'Cancelled',
            'overdue'      => 'Overdue',
        ];

        // Base query with relationships
        $baseQuery = Assignment::with(['assignee', 'resident', 'location'])
            ->orderByDesc('created_at');

        // Apply filters
        if ($status && isset($statusOptions[$status])) {
            $baseQuery->where('status', $status);
        }

        if ($staffId) {
            $baseQuery->where('assigned_to', $staffId);
        }

        if ($locationId) {
            $baseQuery->where('location_id', $locationId);
        }

        if ($serviceUserId) {
            $baseQuery->where('resident_id', $serviceUserId);
        }

        if ($dateFrom) {
            $baseQuery->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $baseQuery->whereDate('created_at', '<=', $dateTo);
        }

        // Clone for summary stats
        $summaryQuery = clone $baseQuery;

        $totalInRange = (clone $summaryQuery)->count();

        $completedInRange = (clone $summaryQuery)
            ->whereIn('status', ['verified', 'closed'])
            ->count();

        $overdueInRange = (clone $summaryQuery)
            ->whereNotIn('status', ['verified', 'closed'])
            ->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->count();

        // Status breakdown for chart (based on current filters)
        $statusBreakdown = (clone $summaryQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Paginated list for table
        $assignments = (clone $baseQuery)->paginate(20)->withQueryString();

        // Filter dropdown data
        $staffOptions = User::whereIn('id', Assignment::select('assigned_to')->distinct())
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $locationOptions = Location::orderBy('name')->get();

        $serviceUserOptions = Resident::orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('backend.admin.reports.assignments', compact(
            'assignments',
            'statusOptions',
            'staffOptions',
            'locationOptions',
            'serviceUserOptions',
            'totalInRange',
            'completedInRange',
            'overdueInRange',
            'statusBreakdown'
        ));
    }


    public function staffReport(Request $request)
    {
        $status     = $request->input('status');
        $staffId    = $request->input('staff_id');
        $locationId = $request->input('location_id');
        $dateFrom   = $request->input('date_from');
        $dateTo     = $request->input('date_to');

        // Status options for filter + labels
        $statusOptions = [
            'draft'        => 'Draft',
            'scheduled'    => 'Scheduled',
            'in_progress'  => 'In Progress',
            'submitted'    => 'Submitted',
            'verified'     => 'Verified',
            'closed'       => 'Closed',
            'cancelled'    => 'Cancelled',
            'overdue'      => 'Overdue',
        ];

        // Base assignments query with relationships
        $baseQuery = Assignment::with(['assignee', 'location'])
            ->orderByDesc('created_at');

        // Apply filters
        if ($status && isset($statusOptions[$status])) {
            $baseQuery->where('status', $status);
        }

        if ($staffId) {
            $baseQuery->where('assigned_to', $staffId);
        }

        if ($locationId) {
            $baseQuery->where('location_id', $locationId);
        }

        if ($dateFrom) {
            $baseQuery->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $baseQuery->whereDate('created_at', '<=', $dateTo);
        }

        // Get filtered assignments
        $assignments = $baseQuery->get();
        $now = now();

        // Aggregate per staff
        $staffPerformance = [];

        foreach ($assignments as $assignment) {
            $user = $assignment->assignee;

            if (!$user) {
                continue;
            }

            $id = $user->id;

            if (!isset($staffPerformance[$id])) {
                $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                if ($fullName === '') {
                    $fullName = $user->email ?? ('User #' . $user->id);
                }

                $staffPerformance[$id] = [
                    'user_id'           => $user->id,
                    'name'              => $fullName,
                    'total_assigned'    => 0,
                    'completed'         => 0,
                    'overdue'           => 0,
                    'last_assignment_at' => null,
                ];
            }

            $staffPerformance[$id]['total_assigned']++;

            // Completed?
            if (in_array($assignment->status, ['verified', 'closed'])) {
                $staffPerformance[$id]['completed']++;
            }

            // Overdue? (not completed + due_at in the past)
            if (
                !in_array($assignment->status, ['verified', 'closed']) &&
                !is_null($assignment->due_at) &&
                $assignment->due_at < $now
            ) {
                $staffPerformance[$id]['overdue']++;
            }

            // Last assignment date (max created_at)
            if ($assignment->created_at) {
                if (
                    is_null($staffPerformance[$id]['last_assignment_at']) ||
                    $assignment->created_at->gt($staffPerformance[$id]['last_assignment_at'])
                ) {
                    $staffPerformance[$id]['last_assignment_at'] = $assignment->created_at;
                }
            }
        }

        // Compute completion_rate & active, convert to indexed array
        $totalAssignmentsInRange = 0;
        $totalCompletedInRange   = 0;
        $totalOverdueInRange     = 0;

        foreach ($staffPerformance as &$row) {
            $totalAssignmentsInRange += $row['total_assigned'];
            $totalCompletedInRange   += $row['completed'];
            $totalOverdueInRange     += $row['overdue'];

            $row['active'] = $row['total_assigned'] - $row['completed'];

            if ($row['total_assigned'] > 0) {
                $row['completion_rate'] = round(($row['completed'] / $row['total_assigned']) * 100, 1);
            } else {
                $row['completion_rate'] = 0;
            }
        }
        unset($row);

        $staffPerformance = array_values($staffPerformance);

        // Sort: best completion rate, then total_assigned desc, then name
        usort($staffPerformance, function ($a, $b) {
            if ($a['completion_rate'] === $b['completion_rate']) {
                if ($a['total_assigned'] === $b['total_assigned']) {
                    return strcmp($a['name'], $b['name']);
                }
                return $b['total_assigned'] <=> $a['total_assigned'];
            }
            return $b['completion_rate'] <=> $a['completion_rate'];
        });

        // Summary stats for header cards
        $totalStaffWithAssignments = count($staffPerformance);
        $overallCompletionRate = $totalAssignmentsInRange > 0
            ? round(($totalCompletedInRange / $totalAssignmentsInRange) * 100, 1)
            : 0;

        // Filter dropdown data
        $staffOptions = User::whereIn('id', Assignment::select('assigned_to')->distinct())
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $locationOptions = Location::orderBy('name')->get();

        // Data for chart – take top 10 staff by completion rate
        $chartData = array_slice($staffPerformance, 0, 10);

        $chartLabels = [];
        $chartCompletionRates = [];

        foreach ($chartData as $row) {
            $chartLabels[] = $row['name'];
            $chartCompletionRates[] = $row['completion_rate'] ?? 0;
        }

        return view('backend.admin.reports.staff', compact(
            'staffPerformance',
            'statusOptions',
            'staffOptions',
            'locationOptions',
            'totalStaffWithAssignments',
            'totalAssignmentsInRange',
            'totalCompletedInRange',
            'totalOverdueInRange',
            'overallCompletionRate',
            'chartLabels',
            'chartCompletionRates'
        ));
    }



    public function serviceUsersReport(Request $request)
    {
        $status        = $request->input('status');
        $staffId       = $request->input('staff_id');
        $locationId    = $request->input('location_id');
        $serviceUserId = $request->input('service_user_id');
        $dateFrom      = $request->input('date_from');
        $dateTo        = $request->input('date_to');

        // Status options for filter + labels
        $statusOptions = [
            'draft'        => 'Draft',
            'scheduled'    => 'Scheduled',
            'in_progress'  => 'In Progress',
            'submitted'    => 'Submitted',
            'verified'     => 'Verified',
            'closed'       => 'Closed',
            'cancelled'    => 'Cancelled',
            'overdue'      => 'Overdue',
        ];

        // Base assignments query with relationships
        $baseQuery = Assignment::with(['resident', 'assignee', 'location'])
            ->orderByDesc('created_at');

        // Apply filters
        if ($status && isset($statusOptions[$status])) {
            $baseQuery->where('status', $status);
        }

        if ($staffId) {
            $baseQuery->where('assigned_to', $staffId);
        }

        if ($locationId) {
            $baseQuery->where('location_id', $locationId);
        }

        if ($serviceUserId) {
            $baseQuery->where('resident_id', $serviceUserId);
        }

        if ($dateFrom) {
            $baseQuery->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $baseQuery->whereDate('created_at', '<=', $dateTo);
        }

        // Get filtered assignments
        $assignments = $baseQuery->get();
        $now = now();

        // Aggregate per service user
        $serviceUserPerformance = [];

        foreach ($assignments as $assignment) {
            $su = $assignment->resident;

            if (!$su) {
                continue;
            }

            $id = $su->id;

            if (!isset($serviceUserPerformance[$id])) {
                $fullName = trim(($su->first_name ?? '') . ' ' . ($su->last_name ?? ''));
                if ($fullName === '') {
                    $fullName = 'Service User #' . $su->id;
                }

                $serviceUserPerformance[$id] = [
                    'service_user_id'   => $su->id,
                    'name'              => $fullName,
                    'total_assignments' => 0,
                    'completed'         => 0,
                    'overdue'           => 0,
                    'last_assignment_at' => null,
                ];
            }

            $serviceUserPerformance[$id]['total_assignments']++;

            // Completed?
            if (in_array($assignment->status, ['verified', 'closed'])) {
                $serviceUserPerformance[$id]['completed']++;
            }

            // Overdue? (not completed + due_at in the past)
            if (
                !in_array($assignment->status, ['verified', 'closed']) &&
                !is_null($assignment->due_at) &&
                $assignment->due_at < $now
            ) {
                $serviceUserPerformance[$id]['overdue']++;
            }

            // Last assignment date (max created_at)
            if ($assignment->created_at) {
                if (
                    is_null($serviceUserPerformance[$id]['last_assignment_at']) ||
                    $assignment->created_at->gt($serviceUserPerformance[$id]['last_assignment_at'])
                ) {
                    $serviceUserPerformance[$id]['last_assignment_at'] = $assignment->created_at;
                }
            }
        }

        // Compute summary stats and convert to indexed array
        $totalAssignmentsInRange  = 0;
        $totalCompletedInRange    = 0;
        $totalOverdueInRange      = 0;

        foreach ($serviceUserPerformance as &$row) {
            $totalAssignmentsInRange += $row['total_assignments'];
            $totalCompletedInRange   += $row['completed'];
            $totalOverdueInRange     += $row['overdue'];

            if ($row['total_assignments'] > 0) {
                $row['completion_rate'] = round(($row['completed'] / $row['total_assignments']) * 100, 1);
            } else {
                $row['completion_rate'] = 0;
            }
        }
        unset($row);

        $serviceUserPerformance = array_values($serviceUserPerformance);

        // Sort: most active first (total assignments), then by overdue ascending, then by name
        usort($serviceUserPerformance, function ($a, $b) {
            if ($a['total_assignments'] === $b['total_assignments']) {
                if ($a['overdue'] === $b['overdue']) {
                    return strcmp($a['name'], $b['name']);
                }
                return $a['overdue'] <=> $b['overdue'];
            }
            return $b['total_assignments'] <=> $a['total_assignments'];
        });

        // Summary stats
        $totalServiceUsersWithAssignments = count($serviceUserPerformance);
        $overallCompletionRate = $totalAssignmentsInRange > 0
            ? round(($totalCompletedInRange / $totalAssignmentsInRange) * 100, 1)
            : 0;

        // Filter dropdown data
        $staffOptions = User::whereIn('id', Assignment::select('assigned_to')->distinct())
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $locationOptions = Location::orderBy('name')->get();

        $serviceUserOptions = Resident::orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Data for chart – top 10 most active service users
        $chartData = array_slice($serviceUserPerformance, 0, 10);

        $chartLabels = [];
        $chartAssignments = [];

        foreach ($chartData as $row) {
            $chartLabels[] = $row['name'];
            $chartAssignments[] = $row['total_assignments'] ?? 0;
        }

        return view('backend.admin.reports.service-users', compact(
            'serviceUserPerformance',
            'statusOptions',
            'staffOptions',
            'locationOptions',
            'serviceUserOptions',
            'totalServiceUsersWithAssignments',
            'totalAssignmentsInRange',
            'totalCompletedInRange',
            'totalOverdueInRange',
            'overallCompletionRate',
            'chartLabels',
            'chartAssignments'
        ));
    }

    public function locationsReport(Request $request)
    {
        $status     = $request->input('status');
        $staffId    = $request->input('staff_id');
        $locationId = $request->input('location_id');
        $dateFrom   = $request->input('date_from');
        $dateTo     = $request->input('date_to');

        // Status options for filter + labels
        $statusOptions = [
            'draft'        => 'Draft',
            'scheduled'    => 'Scheduled',
            'in_progress'  => 'In Progress',
            'submitted'    => 'Submitted',
            'verified'     => 'Verified',
            'closed'       => 'Closed',
            'cancelled'    => 'Cancelled',
            'overdue'      => 'Overdue',
        ];

        // Base assignments query with relationships
        $baseQuery = Assignment::with(['location', 'assignee', 'resident'])
            ->orderByDesc('created_at');

        // Apply filters
        if ($status && isset($statusOptions[$status])) {
            $baseQuery->where('status', $status);
        }

        if ($staffId) {
            $baseQuery->where('assigned_to', $staffId);
        }

        if ($locationId) {
            $baseQuery->where('location_id', $locationId);
        }

        if ($dateFrom) {
            $baseQuery->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $baseQuery->whereDate('created_at', '<=', $dateTo);
        }

        // Get filtered assignments
        $assignments = $baseQuery->get();
        $now = now();

        // Aggregate per location
        $locationPerformance = [];

        foreach ($assignments as $assignment) {
            $loc = $assignment->location;

            if (!$loc) {
                continue;
            }

            $id = $loc->id;

            if (!isset($locationPerformance[$id])) {
                $name = $loc->name ?? ('Location #' . $loc->id);

                $locationPerformance[$id] = [
                    'location_id'        => $loc->id,
                    'name'               => $name,
                    'total_assignments'  => 0,
                    'completed'          => 0,
                    'overdue'            => 0,
                    'staff_ids'          => [],
                    'last_assignment_at' => null,
                ];
            }

            $locationPerformance[$id]['total_assignments']++;

            // Completed?
            if (in_array($assignment->status, ['verified', 'closed'])) {
                $locationPerformance[$id]['completed']++;
            }

            // Overdue? (not completed + due_at in the past)
            if (
                !in_array($assignment->status, ['verified', 'closed']) &&
                !is_null($assignment->due_at) &&
                $assignment->due_at < $now
            ) {
                $locationPerformance[$id]['overdue']++;
            }

            // Track staff IDs (for staff_count)
            if (!empty($assignment->assigned_to)) {
                $locationPerformance[$id]['staff_ids'][] = $assignment->assigned_to;
            }

            // Last assignment date (max created_at)
            if ($assignment->created_at) {
                if (
                    is_null($locationPerformance[$id]['last_assignment_at']) ||
                    $assignment->created_at->gt($locationPerformance[$id]['last_assignment_at'])
                ) {
                    $locationPerformance[$id]['last_assignment_at'] = $assignment->created_at;
                }
            }
        }

        // Compute summary stats & staff_count, then convert to indexed array
        $totalAssignmentsInRange = 0;
        $totalCompletedInRange   = 0;
        $totalOverdueInRange     = 0;

        foreach ($locationPerformance as &$row) {
            $totalAssignmentsInRange += $row['total_assignments'];
            $totalCompletedInRange   += $row['completed'];
            $totalOverdueInRange     += $row['overdue'];

            // Distinct staff per location
            $row['staff_count'] = count(array_unique($row['staff_ids'] ?? []));
            unset($row['staff_ids']);

            if ($row['total_assignments'] > 0) {
                $row['completion_rate'] = round(($row['completed'] / $row['total_assignments']) * 100, 1);
            } else {
                $row['completion_rate'] = 0;
            }
        }
        unset($row);

        $locationPerformance = array_values($locationPerformance);

        // Sort: most busy locations first, then lower overdue, then name
        usort($locationPerformance, function ($a, $b) {
            if ($a['total_assignments'] === $b['total_assignments']) {
                if ($a['overdue'] === $b['overdue']) {
                    return strcmp($a['name'], $b['name']);
                }
                return $a['overdue'] <=> $b['overdue'];
            }
            return $b['total_assignments'] <=> $a['total_assignments'];
        });

        // Summary stats
        $totalLocationsWithAssignments = count($locationPerformance);
        $overallCompletionRate = $totalAssignmentsInRange > 0
            ? round(($totalCompletedInRange / $totalAssignmentsInRange) * 100, 1)
            : 0;

        // Filter dropdown data
        $staffOptions = User::whereIn('id', Assignment::select('assigned_to')->distinct())
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $locationOptions = Location::orderBy('name')->get();

        // Chart data – top 10 busiest locations
        $chartData = array_slice($locationPerformance, 0, 10);

        $chartLabels = [];
        $chartAssignments = [];

        foreach ($chartData as $row) {
            $chartLabels[] = $row['name'];
            $chartAssignments[] = $row['total_assignments'] ?? 0;
        }

        return view('backend.admin.reports.locations', compact(
            'locationPerformance',
            'statusOptions',
            'staffOptions',
            'locationOptions',
            'totalLocationsWithAssignments',
            'totalAssignmentsInRange',
            'totalCompletedInRange',
            'totalOverdueInRange',
            'overallCompletionRate',
            'chartLabels',
            'chartAssignments'
        ));
    }


    public function evidenceReport(Request $request)
    {
        $staffId       = $request->input('staff_id');
        $locationId    = $request->input('location_id');
        $serviceUserId = $request->input('service_user_id');
        $dateFrom      = $request->input('date_from');
        $dateTo        = $request->input('date_to');
        $type          = $request->input('type'); // photo, note, gps

        // Evidence types
        $typeOptions = [
            'photo' => 'Photo Evidence',
            'note'  => 'Notes Only',
            'gps'   => 'GPS-location Evidence'
        ];

        // Base query – NOTE: no "creator" relation here
        $baseQuery = AssignmentEvidence::with([
            'assignment.location',
            'assignment.resident',
        ])
            ->orderByDesc('captured_at');

        /** FILTERS **/
        if ($staffId) {
            $baseQuery->where('created_by', $staffId);
        }

        if ($locationId) {
            $baseQuery->whereHas('assignment', function ($q) use ($locationId) {
                $q->where('location_id', $locationId);
            });
        }

        if ($serviceUserId) {
            $baseQuery->whereHas('assignment', function ($q) use ($serviceUserId) {
                $q->where('resident_id', $serviceUserId);
            });
        }

        if ($type && isset($typeOptions[$type])) {
            $baseQuery->where('file_type', $type);
        }

        if ($dateFrom) {
            $baseQuery->whereDate('captured_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $baseQuery->whereDate('captured_at', '<=', $dateTo);
        }

        // Get filtered evidence
        $evidence = $baseQuery->get();

        /*********************************
         *  STAFF MAP (created_by -> User)
         ********************************/
        $staffIds = $evidence->pluck('created_by')->filter()->unique()->all();

        $staffMap = $staffIds
            ? User::whereIn('id', $staffIds)->get()->keyBy('id')
            : collect();

        /*********************************
         *  SUMMARY COUNTS
         ********************************/
        $summary = [
            'total'   => $evidence->count(),
            'photos'  => $evidence->where('file_type', 'photo')->count(),
            'notes'   => $evidence->where('file_type', 'note')->count(),
            'gps'     => $evidence->where('file_type', 'gps')->count(),
        ];

        /*********************************
         *  AGGREGATION: EVIDENCE PER STAFF
         ********************************/
        $staffBreakdown = [];

        foreach ($evidence as $ev) {
            if (!$ev->created_by) {
                continue;
            }

            $u = $staffMap->get($ev->created_by);
            if (!$u) {
                continue;
            }

            $uid = $u->id;

            if (!isset($staffBreakdown[$uid])) {
                $fullName = trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? ''));
                if ($fullName === '') {
                    $fullName = $u->email ?? ('User #' . $uid);
                }

                $staffBreakdown[$uid] = [
                    'staff_id' => $uid,
                    'name'     => $fullName,
                    'total'    => 0,
                    'photos'   => 0,
                    'notes'    => 0,
                    'gps'      => 0,
                ];
            }

            $staffBreakdown[$uid]['total']++;

            if ($ev->file_type === 'photo') $staffBreakdown[$uid]['photos']++;
            if ($ev->file_type === 'note')  $staffBreakdown[$uid]['notes']++;
            if ($ev->file_type === 'gps')   $staffBreakdown[$uid]['gps']++;
        }

        // Convert to indexed array & sort
        $staffBreakdown = array_values($staffBreakdown);

        usort($staffBreakdown, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        // CHART: top 10 staff by evidence count
        $chartData = array_slice($staffBreakdown, 0, 10);

        $chartLabels = [];
        $chartTotals = [];

        foreach ($chartData as $row) {
            $chartLabels[] = $row['name'];
            $chartTotals[] = $row['total'];
        }

        /*********************************
         *  FILTER DROPDOWNS
         ********************************/
        $staffOptions = User::orderBy('first_name')->orderBy('last_name')->get();
        $locationOptions = Location::orderBy('name')->get();
        $serviceUserOptions = Resident::orderBy('last_name')->orderBy('first_name')->get();

        return view('backend.admin.reports.evidence', compact(
            'evidence',
            'typeOptions',
            'staffOptions',
            'locationOptions',
            'serviceUserOptions',
            'summary',
            'staffBreakdown',
            'chartLabels',
            'chartTotals',
            'staffMap'
        ));
    }


    public function hoursReport()
    {
        return view('backend.admin.reports.hours');
    }
}
