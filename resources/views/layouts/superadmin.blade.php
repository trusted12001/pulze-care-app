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

<div class="sidebar" id="sidebar" style="height: 100vh; overflow-y: auto;">
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

  {{-- NEW ITEMS --}}

  <a href="{{ url('/feature-access-control') }}" class="{{ request()->is('feature-access-control*') ? 'active' : '' }}">
    <i class="ph ph-lock-key"></i> <span class="menu-label">Feature Access</span>
  </a>

  <a href="{{ url('/customization') }}" class="{{ request()->is('customization*') ? 'active' : '' }}">
    <i class="ph ph-paint-brush-broad"></i> <span class="menu-label">UI Customization</span>
  </a>

  <a href="{{ url('/audit-logs') }}" class="{{ request()->is('audit-logs*') ? 'active' : '' }}">
    <i class="ph ph-clipboard-text"></i> <span class="menu-label">Audit Logs</span>
  </a>

  <a href="{{ url('/support-tickets') }}" class="{{ request()->is('support-tickets*') ? 'active' : '' }}">
    <i class="ph ph-lifebuoy"></i> <span class="menu-label">Support Tickets</span>
  </a>

  <a href="{{ url('/ideas-portal') }}" class="{{ request()->is('ideas-portal*') ? 'active' : '' }}">
    <i class="ph ph-lightbulb"></i> <span class="menu-label">Ideas Portal</span>
  </a>

  <a href="{{ url('/knowledge-base') }}" class="{{ request()->is('knowledge-base*') ? 'active' : '' }}">
    <i class="ph ph-book-bookmark"></i> <span class="menu-label">Knowledge Base</span>
  </a>

  <a href="{{ url('/settings') }}" class="{{ request()->is('settings*') ? 'active' : '' }}">
    <i class="ph ph-gear"></i> <span class="menu-label">Platform Settings</span>
  </a>

  {{-- Log Out --}}
  <a href="{{ route('logout') }}"
     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="ph ph-sign-out"></i> <span class="menu-label">Log Out</span>
  </a>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
  </form>
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
