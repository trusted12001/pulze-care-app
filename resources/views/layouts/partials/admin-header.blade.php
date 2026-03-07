<header id="headerBar">
    <h2 class="text-lg font-semibold">@yield('title', 'Pulze Admin')</h2>
    <div>



        {{-- In a topbar layout --}}
        {{-- @auth
        <a href="{{ route('backend.admin.notifications') }}" class="relative">
            <i class="ph ph-bell"></i>
            @if(auth()->user()->unreadNotifications()->count())
            <span
                class="absolute -top-1 -right-1 inline-flex items-center justify-center text-xs bg-red-600 text-white rounded-full w-5 h-5">
                {{ auth()->user()->unreadNotifications()->count() }}
            </span>
            @endif
        </a>
        @endauth --}}


        <div style="position: relative; display: inline-block; margin-right:15px;">
            <button id="userMenuButton" style="background:none;border:none;cursor:pointer;">
                <i class="ph ph-user"></i>
            </button>

            <div id="userMenuDropdown"
                style="display:none; position:absolute; right:0; top:35px; background:white; border:1px solid #ddd; border-radius:6px; min-width:160px; box-shadow:0 4px 12px rgba(0,0,0,0.08); z-index:9999;">

                <a href="{{ route('account.settings.edit') }}"
                    style="display:block;padding:10px 15px;text-decoration:none;color:#333;">
                    My Account
                </a>

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    style="display:block;padding:10px 15px;text-decoration:none;color:#333;">
                    Log Out
                </a>
            </div>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

    </div>
</header>