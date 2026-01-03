<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Pulze'))</title>

    <!-- Fonts -->
    <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('./assets/css/plugins/swiper.min.css') }}" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Small tweaks to ensure no conflict with old .d-flex, etc. */
        .form-error {
            font-size: 0.875rem;
        }

        .brand-badge {
            background: linear-gradient(135deg, rgba(251, 146, 60, 0.15), rgba(245, 158, 11, 0.15));
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-orange-50 to-amber-50 antialiased">
    {{-- Background (similar “modern login” look) --}}
    <div class="min-h-screen flex items-center justify-center px-4 py-10">

        {{-- Subtle pattern overlay --}}
        <div class="absolute inset-0 opacity-20 pointer-events-none">
        </div>

        {{-- Main card --}}
        <div class="relative w-full max-w-lg">


            {{-- Flash messages (optional but helpful for login/session screens) --}}
            @if(session('success'))
                <div class="mb-4 rounded-xl bg-green-50 p-3 text-sm text-green-700 ring-1 ring-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-4 rounded-xl bg-yellow-50 p-3 text-sm text-yellow-800 ring-1 ring-yellow-200">
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-xl bg-red-50 p-3 text-sm text-red-700 ring-1 ring-red-200">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ✅ IMPORTANT: your pages should use @section('content') --}}
            @yield('content')

            {{-- Footer --}}
            <p class="mt-6 text-center text-xs text-white/60">
                © {{ date('Y') }} {{ config('app.name', 'Pulze') }}. All rights reserved.
            </p>
        </div>
</body>

</html>