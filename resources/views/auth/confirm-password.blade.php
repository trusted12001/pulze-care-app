<!-- resources/views/auth/confirm-password.blade.php -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
    <title>Confirm Password - Pulze</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
  </head>
  <body>
    <main class="flex-center h-100">
      <section class="sign-in-area">
        <h2 class="heading-2">Confirm Password</h2>
        <p class="paragraph-small pt-3">
          This is a secure area of the application. Please confirm your password before continuing.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}" class="input-field-area d-flex flex-column gap-4">
          @csrf

          <div class="input-field-item">
            <p>Password</p>
            <div class="input-field">
              <input
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
              />
              @error('password')
                <span class="text-danger text-sm">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div
