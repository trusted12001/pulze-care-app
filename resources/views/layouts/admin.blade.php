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

    adjustSidebarOnResize();
    window.addEventListener('resize', adjustSidebarOnResize);

    document.getElementById('toggleSidebarBtn').addEventListener('click', function () {
      sidebar.classList.toggle('collapsed');
      content.classList.toggle('expanded');
    });
  </script>
</body>
</html>
