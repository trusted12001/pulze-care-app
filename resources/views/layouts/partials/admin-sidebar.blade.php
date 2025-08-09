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

    <a href="{{route('backend.admin.users.index')}}" class="{{ request()->is('manage-staff*') ? 'active' : '' }}">
        <i class="ph ph-users"></i> <span class="menu-label">Manage Staff</span>
    </a>
    <a href="{{ url('/staff-profile') }}" class="{{ request()->is('staff-profile*') ? 'active' : '' }}">
        <i class="ph ph-user-circle"></i> <span class="menu-label">Staff Profile</span>
    </a>
    <a href="{{ url('/service-users') }}" class="{{ request()->is('service-users*') ? 'active' : '' }}">
        <i class="ph ph-users-three"></i> <span class="menu-label">Service Users</span>
    </a>
    <a href="{{ url('/assignments') }}" class="{{ request()->is('assignments*') ? 'active' : '' }}">
        <i class="ph ph-handshake"></i> <span class="menu-label">Assignments</span>
    </a>
    <a href="{{ url('/care-plans') }}" class="{{ request()->is('care-plans*') ? 'active' : '' }}">
        <i class="ph ph-notebook"></i> <span class="menu-label">Care Plans</span>
    </a>
    <a href="{{ url('/risk-assessments') }}" class="{{ request()->is('risk-assessments*') ? 'active' : '' }}">
        <i class="ph ph-shield-warning"></i> <span class="menu-label">Risk Assessments</span>
    </a>
    <a href="{{ url('/timesheets') }}" class="{{ request()->is('timesheets*') ? 'active' : '' }}">
        <i class="ph ph-clock"></i> <span class="menu-label">Timesheets</span>
    </a>
    <a href="{{ url('/shift-rota') }}" class="{{ request()->is('shift-rota*') ? 'active' : '' }}">
        <i class="ph ph-calendar-check"></i> <span class="menu-label">Shift Rota</span>
    </a>
    <a href="{{ url('/health-info') }}" class="{{ request()->is('health-info*') ? 'active' : '' }}">
        <i class="ph ph-heartbeat"></i> <span class="menu-label">Health Info</span>
    </a>
    <a href="{{ url('/subscriptions') }}" class="{{ request()->is('subscriptions*') ? 'active' : '' }}">
        <i class="ph ph-credit-card"></i> <span class="menu-label">Subscriptions</span>
    </a>
    <a href="{{ url('/payment-history') }}" class="{{ request()->is('payment-history*') ? 'active' : '' }}">
        <i class="ph ph-receipt"></i> <span class="menu-label">Payment History</span>
    </a>
    <a href="{{ url('/reports') }}" class="{{ request()->is('reports*') ? 'active' : '' }}">
        <i class="ph ph-chart-bar"></i> <span class="menu-label">Reports</span>
    </a>
    <a href="{{ url('/urgent-cases') }}" class="{{ request()->is('urgent-cases*') ? 'active' : '' }}">
        <i class="ph ph-warning"></i> <span class="menu-label">Urgent Cases</span>
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
