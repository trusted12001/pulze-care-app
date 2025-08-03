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
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>

    @include('layouts.partials.superadmin-sidebar')

    <div class="main-content" id="mainContent">

        @include('layouts.partials.superadmin-header')

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
