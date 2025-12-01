<div class="sidebar" id="sidebar" style="height: 100vh; overflow-y: auto;">
    <div class="p-4">
        <button class="toggle-btn" id="toggleSidebarBtn">
            <i class="ph ph-list"></i>
        </button>
    </div>
    <nav>
        <a href="{{ route('backend.admin.index') }}" class="{{ request()->is('admin*') ? 'active' : '' }}">
            <i class="ph ph-gauge"></i> <span class="menu-label">Dashboard</span>
        </a>

        <a href="{{ route('backend.admin.users.index') }}"
            class="{{ request()->is('manage-staff*') ? 'active' : (request()->is('backend/admin/users*') ? 'active' : '') }}">
            <i class="ph ph-users"></i> <span class="menu-label">Staff Accounts</span>
        </a>

        <a href="{{ route('backend.admin.staff-profiles.index') }}"
            class="{{ request()->is('staff-profile*') ? 'active' : (request()->is('backend/admin/staff-profile*') ? 'active' : '') }}">
            <i class="ph ph-user-circle"></i> <span class="menu-label">Staff Profile</span>
        </a>

        {{-- NEW: Location Setup (after Staff Profile) --}}
        <a href="{{ route('backend.admin.locations.index') }}"
            class="{{ request()->routeIs('backend.admin.locations.*') ? 'active' : (request()->is('backend/admin/locations*') ? 'active' : '') }}">
            <i class="ph ph-map-pin"></i> <span class="menu-label">Location Setup</span>
        </a>

        <a href="{{ route('backend.admin.service-users.index') }}"
            class="{{ request()->is('service-users*') ? 'active' : (request()->is('backend/admin/service-users*') ? 'active' : '') }}">
            <i class="ph ph-users-three"></i> <span class="menu-label">Service Users</span>
        </a>

        {{-- <a href="{{ url('/assignments') }}" class="{{ request()->is('assignments*') ? 'active' : '' }}">
            <i class="ph ph-handshake"></i> <span class="menu-label">Assignments</span>
        </a> --}}

        <a href="{{ route('backend.admin.assignments.index') }}"
            class="{{ request()->routeIs('assignments.*') ? 'active' : '' }}">
            <i class="ph ph-handshake"></i>
            <span class="menu-label">Assignments</span>
        </a>


        <a href="{{ route('backend.admin.care-plans.index') }}"
            class="{{ request()->is('care-plans*') ? 'active' : (request()->is('backend/admin/care-plans*') ? 'active' : '') }}">
            <i class="ph ph-notebook"></i> <span class="menu-label">Care Plans</span>
        </a>
        <a href="{{ route('backend.admin.risk-assessments.index') }}"
            class="{{ request()->is('risk-assessments*') ? 'active' : (request()->is('backend/admin/risk-assessments*') ? 'active' : '') }}">
            <i class="ph ph-shield-warning"></i> <span class="menu-label">Risk Assessments</span>
        </a>
        <a href="{{ url('/timesheets') }}" class="{{ request()->is('timesheets*') ? 'active' : '' }}">
            <i class="ph ph-clock"></i> <span class="menu-label">Timesheets</span>
        </a>
        <a href="{{ route('backend.admin.shift-templates.index') }}"
            class="{{ request()->is('shift-templates*') ? 'active' : (request()->is('backend/admin/shift-templates*') ? 'active' : '') }}">
            <i class="ph ph-list-checks"></i><span class="menu-label">Shift Templates</span>
        </a>

        <a href="{{ route('backend.admin.rota-periods.index') }}" class="{{ (
    request()->is('rota-periods*') ||
    request()->is('backend/admin/rota-periods*') ||
    request()->is('shifts*') ||
    request()->is('backend/admin/shifts*')
) ? 'active' : '' }}">
            <i class="ph ph-calendar-check"></i>
            <span class="menu-label">Shift Rota</span>
        </a>


        {{-- <a href="{{ url('/subscriptions') }}" class="{{ request()->is('subscriptions*') ? 'active' : '' }}">
            <i class="ph ph-credit-card"></i> <span class="menu-label">Subscriptions</span>
        </a>
        <a href="{{ url('/payment-history') }}" class="{{ request()->is('payment-history*') ? 'active' : '' }}">
            <i class="ph ph-receipt"></i> <span class="menu-label">Payment History</span>
        </a> --}}


        <a href="{{ route('backend.admin.reports.index') }}" class="{{ request()->is('reports*') ? 'active' : '' }}">
            <i class="ph ph-chart-bar"></i> <span class="menu-label">Reports</span>
        </a>
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="{{ request()->is('logout') ? 'active' : '' }}">
            <i class="ph ph-sign-out"></i> <span class="menu-label">Log Out</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>
</div>