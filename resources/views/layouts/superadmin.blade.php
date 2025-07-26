<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Pulze SuperAdmin')</title>
  <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
  <link rel="stylesheet" href="{{ asset('assets/css/sadmin.css') }}" />
</head>
<body>

<div class="sidebar" id="sidebar">
  <div class="p-4">
    <button class="toggle-btn" id="toggleSidebarBtn">
      <i class="ph ph-list"></i>
    </button>
  </div>
  <nav>
    <a href="{{ url('/manage-tenants') }}" class="{{ request()->is('manage-tenants*') ? 'active' : '' }}">
      <i class="ph ph-buildings"></i> <span class="menu-label">Manage Tenants</span>
    </a>
    <a href="{{ url('/tenants-profile') }}" class="{{ request()->is('tenants-profile*') ? 'active' : '' }}">
      <i class="ph ph-identification-badge"></i> <span class="menu-label">Tenants Profile</span>
    </a>
    <a href="{{ url('/modules') }}" class="{{ request()->is('modules*') ? 'active' : '' }}">
      <i class="ph ph-puzzle-piece"></i> <span class="menu-label">Manage Modules</span>
    </a>
    <a href="{{ url('/subscriptions') }}" class="{{ request()->is('subscriptions*') ? 'active' : '' }}">
      <i class="ph ph-wallet"></i> <span class="menu-label">Subscriptions</span>
    </a>
    <a href="{{ url('/transactions') }}" class="{{ request()->is('transactions*') ? 'active' : '' }}">
      <i class="ph ph-bank"></i> <span class="menu-label">Transactions</span>
    </a>
    <a href="{{ url('/reports') }}" class="{{ request()->is('reports*') ? 'active' : '' }}">
      <i class="ph ph-chart-bar"></i> <span class="menu-label">Reports</span>
    </a>
  </nav>
</div>

<div class="main-content" id="mainContent">
  <header id="headerBar">
    <h2>@yield('title')</h2>
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




<footer class="text-center mt-5 py-3" style="font-size: 14px; color: #6b7280;">
  &copy; {{ date('Y') }} <strong>Pulze</strong>. All rights reserved. | Designed with ❤️ for Care Teams
</footer>

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

  window.addEventListener('resize', adjustSidebarOnResize);
  adjustSidebarOnResize();

  document.getElementById('toggleSidebarBtn').addEventListener('click', function () {
    sidebar.classList.toggle('collapsed');
    content.classList.toggle('expanded');
  });
</script>

</body>
</html>
