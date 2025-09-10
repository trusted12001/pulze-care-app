<header id="headerBar">
  <h2 class="text-lg font-semibold">@yield('title', 'Pulze Admin')</h2>
  <div>



{{-- In a topbar layout --}}
{{-- @auth
  <a href="{{ route('backend.admin.notifications') }}" class="relative">
    <i class="ph ph-bell"></i>
    @if(auth()->user()->unreadNotifications()->count())
      <span class="absolute -top-1 -right-1 inline-flex items-center justify-center text-xs bg-red-600 text-white rounded-full w-5 h-5">
        {{ auth()->user()->unreadNotifications()->count() }}
      </span>
    @endif
  </a>
@endauth --}}


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
