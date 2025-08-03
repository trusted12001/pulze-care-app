<header id="headerBar">
  <h2 class="text-lg font-semibold">@yield('title', 'Pulze Admin')</h2>
  <div>
    <i class="ph ph-bell" style="margin-right: 15px;"></i>
    <i class="ph ph-user" style="margin-right: 15px;"></i>

    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
      <i class="ph ph-sign-out"></i>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>

  </div>
</header>
