<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
    <title>Sign In - Pulze</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="manifest" href="{{ asset('manifest.json') }}" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
  </head>
  <body>
    <main class="flex-center h-100">
      <section class="sign-in-area">
        <h2 class="heading-2">Sign In</h2>
        <p class="paragraph-small pt-3">
          Sign in to access your support dashboard and care tools.
        </p>

        <!-- Replace form action and CSRF -->
        <form method="POST" action="{{ route('login') }}" class="input-field-area d-flex flex-column gap-4">
          @csrf
          <div class="input-field-item">
            <p>Email</p>
            <div class="input-field">
              <input type="email" name="email" placeholder="Email" required autofocus />
              @error('email')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="input-field-item">
            <p>Password</p>
            <div class="d-flex justify-content-between align-items-center input-field">
              <input type="password" name="password" placeholder="******" required />
              <i class="ph ph-eye-closed"></i>
              @error('password')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="d-flex flex-column gap-8">
            <a href="{{ route('password.request') }}" class="d-block text-end fw-semibold">Forgot Password?</a>
            <button type="submit" class="link-button d-block">Sign In</button>
          </div>

          <div class="position-relative continue-with">
            <img src="{{ asset('assets/img/line.png') }}" class="line-left position-absolute" />
            <img src="{{ asset('assets/img/line.png') }}" class="line-right position-absolute" />
          </div>


          <div class="sign-in-up m-body">
            Don't have an account? <a href="{{ route('register') }}">Sign Up</a> here
          </div>
        </form>
      </section>
    </main>

    <script src="{{ asset('assets/js/plugins/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/service-worker-settings.js') }}"></script>
  </body>
</html>
