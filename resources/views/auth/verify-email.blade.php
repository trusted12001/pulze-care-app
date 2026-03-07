<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
    <title>Verify Email - Pulze</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="manifest" href="{{ asset('manifest.json') }}" />

    <style>
        .brand-badge {
            background: linear-gradient(135deg, rgba(251, 146, 60, 0.15), rgba(245, 158, 11, 0.15));
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-orange-50 to-amber-50 antialiased">
    <main class="min-h-screen flex items-center justify-center px-4 py-10">
        <section class="w-full max-w-md">
            <div class="bg-white/90 backdrop-blur-sm border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
                <div class="px-6 pt-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-700 brand-badge w-10 h-10 rounded-xl flex items-center justify-center">
                            <img src="{{ asset('assets/img/fav-logo.png') }}" alt="Pulze" class="w-7 h-7 object-contain" />
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-green-900">Verify Your Email</h1>
                            <p class="text-sm text-gray-600">Please confirm your email address to continue</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 mt-4 space-y-3">
                    <div class="rounded-lg border border-amber-200 bg-amber-50 text-amber-800 px-3 py-3 text-sm">
                        Thanks for signing up. Before getting started, please verify your email address by clicking the link we just emailed you.
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="rounded-lg border border-green-200 bg-green-50 text-green-800 px-3 py-2 text-sm">
                            A new verification link has been sent to the email address you provided during registration.
                        </div>
                    @endif
                </div>

                <div class="px-6 pb-6 pt-4 space-y-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 active:bg-orange-800 transition shadow-sm">
                            <i class="ph ph-paper-plane-tilt"></i>
                            <span>Resend Verification Email</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="ph ph-sign-out"></i>
                            <span>Log Out</span>
                        </button>
                    </form>
                </div>
            </div>

            <p class="text-center text-xs text-gray-500 mt-4">
                © {{ date('Y') }} Pulze — Secure access
            </p>
        </section>
    </main>

    <script src="{{ asset('assets/js/plugins/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/service-worker-settings.js') }}"></script>
</body>

</html>