<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Pulze')</title>
  <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}" />
</head>
<body>

<div class="sidebar" id="sidebar" style="height: 100vh; overflow-y: auto;">
  <div class="p-4">
    <button class="toggle-btn" id="toggleSidebarBtn">
      <i class="ph ph-list"></i>
    </button>
  </div>
    <nav>
        <a href="{{ url('/manage-staff') }}" class="{{ request()->is('manage-staff*') ? 'active' : '' }}" aria-label="Manage Staff">
            <i class="ph ph-users"></i> <span class="menu-label">Manage Staff</span>
        </a>
        <a href="{{ url('/staff-profile') }}" class="{{ request()->is('staff-profile*') ? 'active' : '' }}" aria-label="Staff Profile">
            <i class="ph ph-user-circle"></i> <span class="menu-label">Staff Profile</span>
        </a>
        <a href="{{ url('/service-users') }}" class="{{ request()->is('service-users*') ? 'active' : '' }}" aria-label="Service Users">
            <i class="ph ph-users-three"></i> <span class="menu-label">Service Users</span>
        </a>
        <a href="{{ url('/assignments') }}" class="{{ request()->is('assignments*') ? 'active' : '' }}" aria-label="Assignments">
            <i class="ph ph-handshake"></i> <span class="menu-label">Assignments</span>
        </a>
        <a href="{{ url('/care-plans') }}" class="{{ request()->is('care-plans*') ? 'active' : '' }}" aria-label="Care Plans">
            <i class="ph ph-notebook"></i> <span class="menu-label">Care Plans</span>
        </a>
        <a href="{{ url('/risk-assessments') }}" class="{{ request()->is('risk-assessments*') ? 'active' : '' }}" aria-label="Risk Assessments">
            <i class="ph ph-shield-warning"></i> <span class="menu-label">Risk Assessments</span>
        </a>
        <a href="{{ url('/timesheets') }}" class="{{ request()->is('timesheets*') ? 'active' : '' }}" aria-label="Timesheets">
            <i class="ph ph-clock"></i> <span class="menu-label">Timesheets</span>
        </a>
        <a href="{{ url('/shift-rota') }}" class="{{ request()->is('shift-rota*') ? 'active' : '' }}" aria-label="Shift Rota">
            <i class="ph ph-calendar-check"></i> <span class="menu-label">Shift Rota</span>
        </a>
        <a href="{{ url('/health-info') }}" class="{{ request()->is('health-info*') ? 'active' : '' }}" aria-label="Health Info">
            <i class="ph ph-heartbeat"></i> <span class="menu-label">Health Info</span>
        </a>
        <a href="{{ url('/subscriptions') }}" class="{{ request()->is('subscriptions*') ? 'active' : '' }}" aria-label="Subscriptions">
            <i class="ph ph-credit-card"></i> <span class="menu-label">Subscriptions</span>
        </a>
        <a href="{{ url('/payment-history') }}" class="{{ request()->is('payment-history*') ? 'active' : '' }}" aria-label="Payment History">
            <i class="ph ph-receipt"></i> <span class="menu-label">Payment History</span>
        </a>
        <a href="{{ url('/reports') }}" class="{{ request()->is('reports*') ? 'active' : '' }}" aria-label="Reports">
            <i class="ph ph-chart-bar"></i> <span class="menu-label">Reports</span>
        </a>
        <a href="{{ url('/urgent-cases') }}" class="{{ request()->is('urgent-cases*') ? 'active' : '' }}" aria-label="Urgent Cases">
            <i class="ph ph-warning"></i> <span class="menu-label">Urgent Cases</span>
        </a>

        <a href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        class="{{ request()->is('logout') ? 'active' : '' }}"
        aria-label="Logout">
            <i class="ph ph-sign-out"></i> <span class="menu-label">Log Out</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

    </nav>
</div>

<div class="main-content" id="mainContent">
  <header id="headerBar">
    <h2 class="text-lg font-semibold">@yield('title', 'Pulze Admin')</h2>
    <div>
      <i class="ph ph-bell" style="margin-right: 15px;"></i>
      <i class="ph ph-user" style="margin-right: 15px;"></i>
      <i class="ph ph-sign-out"></i>
    </div>
  </header>

  <div class="main-body">
    @yield('content')
  </div>
</div>



<script>
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('mainContent');

  function adjustSidebarOnResize() {
    if (window.innerWidth < 768) {
      sidebar.classList.add('collapsed');
      content.classList.add('expanded');
    } else {
      sidebar.classList.remove('collapsed');
      content.classList.remove('expanded');
    }
  }

  // On load
  adjustSidebarOnResize();

  // On resize
  window.addEventListener('resize', adjustSidebarOnResize);

  // Manual toggle
  document.getElementById('toggleSidebarBtn').addEventListener('click', function () {
    sidebar.classList.toggle('collapsed');
    content.classList.toggle('expanded');
  });
</script>


</body>
</html>
