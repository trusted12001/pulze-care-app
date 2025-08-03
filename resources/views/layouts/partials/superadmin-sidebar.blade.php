<div class="sidebar" id="sidebar" style="height: 100vh; overflow-y: auto;">
  <div class="p-4">
    <button class="toggle-btn" id="toggleSidebarBtn">
      <i class="ph ph-list"></i>
    </button>
  </div>
  <nav>
    <a href="{{ route('backend.super-admin.index') }}" class="{{ request()->is('manage-users*') ? 'active' : '' }}">
        <i class="ph ph-gauge"></i> <span class="menu-label">Dashboard</span>
    </a>

    <a href="{{ route('backend.super-admin.users.index') }}" class="{{ request()->is('manage-users*') ? 'active' : '' }}">
        <i class="ph ph-users"></i> <span class="menu-label">Manage Users</span>
    </a>

    {{-- Add other links here (no change) --}}
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

    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      <i class="ph ph-sign-out"></i> <span class="menu-label">Log Out</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>
  </nav>
</div>
