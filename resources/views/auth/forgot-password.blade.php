<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
    <title>Forgot Password - Pulze</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="manifest" href="{{ asset('manifest.json') }}" />

    <style>
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
            <div class="bg-white/90 backdrop-blur-sm border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
                <div class="px-6 pt-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-700 brand-badge w-10 h-10 rounded-xl flex items-center justify-center">
                            <img src="{{ asset('assets/img/fav-logo.png') }}" alt="Pulze" class="w-7 h-7 object-contain" />
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-green-900">Forgot Password</h1>
                            <p class="text-sm text-gray-600">Enter your email to receive a reset link</p>
                        </div>
                    </div>
                </div>

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

                <form method="POST" action="{{ route('password.email') }}" class="px-6 pb-6 pt-2 space-y-4">
                    @csrf

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

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 active:bg-orange-800 transition shadow-sm">
                            <i class="ph ph-paper-plane-tilt"></i>
                            <span>Send Password Reset Link</span>
                        </button>
                    </div>

                    <div class="relative my-2">
                        <div class="absolute inset-0 flex items-center">
                            <span class="w-full border-t border-gray-200"></span>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-white px-2 text-xs text-gray-500">or</span>
                        </div>
                    </div>

                    <p class="text-center text-sm text-gray-600">
                        Remembered your password?
                        <a href="{{ route('login') }}" class="text-orange-700 hover:text-orange-800 font-medium">Back to Sign In</a>
                    </p>
                </form>
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