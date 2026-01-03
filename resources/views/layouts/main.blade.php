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

        @yield('content')
    </section>

    <script src="{{ asset('assets/js/plugins/swiper-bundle.min.js')}}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/service-worker-settings.js') }}"></script>
</body>

</html>