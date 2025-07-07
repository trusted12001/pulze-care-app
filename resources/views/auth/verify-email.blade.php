<!-- resources/views/auth/verify-email.blade.php -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
    <title>Verify Email - Pulze</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
  </head>
  <body>
    <main class="flex-center h-100">
      <section class="sign-in-area">
        <h2 class="heading-2">Verify Your Email</h2>
        <p class="paragraph-small pt-3">
          Thanks for signing up! Please verify your email address by clicking the link we just emailed you.
        </p>

        @if (session('status') == 'verification-link-sent')
          <div class="alert alert-success text-green-600 text-sm mt-3">
            A new verification link has been sent to the email address you provided during registration.
          </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="input-field-area d-flex flex-column gap-3 mt-4">
          @csrf
          <button type="submit" class="link-button d-block">
            Resend Verification Email
          </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
          @csrf
          <button type="submit" class="d-block text-center text-sm underline text-gray-600">
            Log Out
          </button>
        </form>
      </section>
    </main>

    <!-- Js Dependencies -->
    <script src="{{ asset('assets/js/plugins/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/service-worker-settings.js') }}"></script>
  </body>
</html>
