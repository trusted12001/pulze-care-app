<header id="headerBar">
    <h2>@yield('title')</h2>
    <div>
        <div style="position: relative; display: inline-block; margin-right:15px;">

            <button id="userMenuButton" style="background:none;border:none;cursor:pointer;">
                <i class="ph ph-user"></i>
            </button>

            <div id="userMenuDropdown"
                style="display:none; position:absolute; right:0; top:35px; background:white; border:1px solid #ddd; border-radius:6px; min-width:160px; box-shadow:0 4px 12px rgba(0,0,0,0.08);">

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