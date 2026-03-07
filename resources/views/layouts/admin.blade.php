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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    @include('layouts.partials.admin-sidebar')

    <div class="main-content" id="mainContent">
        @include('layouts.partials.admin-header')


        @if (session('warning'))
            <div class="mb-4 rounded bg-red-50 text-orange-800 px-4 py-3">
                {{ session('warning') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 rounded bg-green-50 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded bg-red-50 text-red-800 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif


        @if ($errors->any())
            <div class="mb-4 rounded bg-red-50 text-red-800 px-4 py-3">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="main-body">

            @if(session('support_mode') && session('active_tenant_id') && auth()->user()?->hasRole('super-admin'))
                @php
                    $activeTenant = \App\Models\Tenant::find(session('active_tenant_id'));
                @endphp

                @if($activeTenant)
                    <div class="alert alert-warning d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <strong>Support Mode Active</strong>
                            - Currently, you are technically supporting:
                            <strong>{{ $activeTenant->name }}</strong>
                        </div>

                        <form action="{{ route('backend.super-admin.support-mode.exit') }}" method="POST" class="mb-0">
                            @csrf
                            <button type="submit"
                                class="rounded bg-red-600 px-3 py-1 text-sm font-medium text-white hover:bg-red-700">
                                Exit Support Mode
                            </button>
                        </form>
                    </div>
                @endif
            @endif

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

        adjustSidebarOnResize();
        window.addEventListener('resize', adjustSidebarOnResize);

        document.getElementById('toggleSidebarBtn').addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('expanded');
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const button = document.getElementById("userMenuButton");
            const menu = document.getElementById("userMenuDropdown");

            if (button && menu) {
                button.addEventListener("click", function (e) {
                    e.stopPropagation();
                    menu.style.display = menu.style.display === "block" ? "none" : "block";
                });

                document.addEventListener("click", function (e) {
                    if (!button.contains(e.target) && !menu.contains(e.target)) {
                        menu.style.display = "none";
                    }
                });
            }
        });
    </script>
</body>

</html>