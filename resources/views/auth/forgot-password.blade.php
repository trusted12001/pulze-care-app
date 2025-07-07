<!-- resources/views/auth/forgot-password.blade.php -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
    <title>Forgot Password - Pulze</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
  </head>
  <body>
    <main class="flex-center h-100">
      <section class="sign-in-area">
        <h2 class="heading-2">Forgot Password</h2>
        <p class="paragraph-small pt-3">
          Enter your email address and we’ll send you a link to reset your password.
        </p>

        <!-- Laravel form -->
        <form method="POST" action="{{ route('password.email') }}" class="input-field-area d-flex flex-column gap-4">
          @csrf

          @if (session('status'))
            <div class="alert alert-success text-green-600 text-sm">
              {{ session('status') }}
            </div>
          @endif

          <div class="input-field-item">
            <p>Email</p>
            <div class="input-field">
              <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="Enter your email"
              />
              @error('email')
                <span class="text-danger text-sm">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="d-flex flex-column gap-8">
            <button type="submit" class="link-button d-block">
              Send Password Reset Link
            </button>
          </div>

          <div class="sign-in-up m-body">
            <a href="{{ route('login') }}">Back to Login</a>
          </div>
        </form>
      </section>
    </main>

    <!-- Js Dependencies -->
    <script src="{{ asset('assets/js/plugins/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/service-worker-settings.js') }}"></script>
  </body>
</html>
