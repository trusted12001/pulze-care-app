<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Pulze')</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('./assets/css/plugins/swiper.min.css') }}" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
  </head>
<body>

  {{-- @include('layouts.navbar') <!-- if applicable --> --}}

      <section class="sign-in-area">
        @yield('content')
      </section>

    <script src="{{ asset('assets/js/plugins/swiper-bundle.min.js')}}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/service-worker-settings.js') }}"></script>
</body>
</html>
