<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
    <title>Sign In - Pulze</title>

    {{-- Tailwind (CDN for this page). If you use Vite/Mix, swap this for your compiled app.css --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Phosphor icons --}}
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    {{-- Keep your existing global CSS if needed (optional) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="manifest" href="{{ asset('manifest.json') }}" />

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
    <main class="min-h-screen flex items-center justify-center px-4 py-10">

        <section class="w-full max-w-md">
            {{-- Card --}}
            <div class="bg-white/90 backdrop-blur-sm border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
                {{-- Header --}}
                <div class="px-6 pt-6">
                    <div class="flex items-center gap-3">
                        <div class="brand-badge w-10 h-10 rounded-xl flex items-center justify-center">
                            <img src="{{ asset('assets/img/fav-logo.png') }}" alt="Pulze"
                                class="w-7 h-7 object-contain" />
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Sign In</h1>
                            <p class="text-sm text-gray-600">Access your support dashboard and care tools</p>
                        </div>
                    </div>
                </div>

                {{-- Flash / validation --}}
                <div class="px-6 mt-4">
                    @if (session('status'))
                        <div class="mb-3 rounded-lg border border-green-200 bg-green-50 text-green-800 px-3 py-2">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-3 rounded-lg border border-red-200 bg-red-50 text-red-800 px-3 py-2">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}" class="px-6 pb-6 pt-2 space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <i class="ph ph-at absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                placeholder="you@example.com" required autofocus
                                class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition" />
                        </div>
                        @error('email')
                            <p class="form-error text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-orange-700 hover:text-orange-800">Forgot Password?</a>
                        </div>
                        <div class="relative">
                            <i class="ph ph-lock-key absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input id="passwordInput" type="password" name="password" placeholder="••••••••" required
                                class="w-full pl-10 pr-10 py-2.5 rounded-lg border border-gray-300 bg-gray-50 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition" />
                            <button type="button" id="togglePassword"
                                class="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-md text-gray-500 hover:text-gray-700 focus:outline-none"
                                aria-label="Toggle password">
                                <i class="ph ph-eye-closed text-lg"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="form-error text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="remember"
                                class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" />
                            Remember me
                        </label>
                        {{-- Optional link back to site/home --}}
                    </div>

                    {{-- Submit --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 active:bg-orange-800 transition shadow-sm">
                            <i class="ph ph-sign-in"></i>
                            <span>Sign In</span>
                        </button>
                    </div>

                    {{-- Divider --}}
                    <div class="relative my-2">
                        <div class="absolute inset-0 flex items-center">
                            <span class="w-full border-t border-gray-200"></span>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-white px-2 text-xs text-gray-500">or</span>
                        </div>
                    </div>

                    {{-- Register --}}
                    <p class="text-center text-sm text-gray-600">
                        Don’t have an account?
                        <a href="{{ route('register') }}" class="text-orange-700 hover:text-orange-800 font-medium">Sign
                            up</a>
                    </p>
                </form>
            </div>

            {{-- Tiny footer --}}
            <p class="text-center text-xs text-gray-500 mt-4">
                © {{ date('Y') }} Pulze — Secure access
            </p>
        </section>
    </main>

    {{-- Optional JS you already had --}}
    <script src="{{ asset('assets/js/plugins/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/service-worker-settings.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggle = document.getElementById("togglePassword");
            const input = document.getElementById("passwordInput");
            toggle?.addEventListener("click", function () {
                const hidden = input.type === "password";
                input.type = hidden ? "text" : "password";
                this.innerHTML = hidden
                    ? '<i class="ph ph-eye text-lg"></i>'
                    : '<i class="ph ph-eye-closed text-lg"></i>';
            });
        });
    </script>
</body>

</html>