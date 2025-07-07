<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Pulze')</title>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>

  @include('layouts.navbar') <!-- if applicable -->

  <main>
    @yield('content')
  </main>

  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
