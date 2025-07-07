<!-- resources/views/auth/register.blade.php -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
    <title>Sign Up - Pulze</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
  </head>
  <body>
    <main class="flex-center h-100">
      <section class="sign-in-area">
        <h2 class="heading-2">Sign Up</h2>
        <p class="paragraph-small pt-3">
          Create your Pulze account to start managing care and shifts.
        </p>

        <form method="POST" action="{{ route('register') }}" class="input-field-area d-flex flex-column gap-4">
          @csrf

          <div class="input-field-item">
            <p>Full Name</p>
            <div class="input-field">
              <input type="text" name="name" value="{{ old('name') }}" required placeholder="Your full name" />
              @error('name')
                <span class="text-danger text-sm">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="input-field-item">
            <p>Email</p>
            <div class="input-field">
              <input type="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email" />
              @error('email')
                <span class="text-danger text-sm">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="input-field-item">
            <p>Password</p>
            <div class="input-field">
              <input type="password" name="password" required placeholder="********" />
              @error('password')
                <span class="text-danger text-sm">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="input-field-item">
            <p>Confirm Password</p>
            <div class="input-field">
              <input type="password" name="password_confirmation" required placeholder="Re-enter your password" />
              @error('password_confirmation')
                <span class="text-danger text-sm">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="d-flex flex-column gap-8">
            <button type="submit" class="link-button d-block">Create Account</button>
          </div>

          <div class="sign-in-up m-body">
            Already registered? <a href="{{ route('login') }}">Sign In</a> here
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
