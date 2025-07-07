<!-- resources/views/auth/reset-password.blade.php -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
    <title>Reset Password - Pulze</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
  </head>
  <body>
    <main class="flex-center h-100">
      <section class="sign-in-area">
        <h2 class="heading-2">Reset Password</h2>
        <p class="paragraph-small pt-3">
          Enter a new password below to reset your account.
        </p>

        <form method="POST" action="{{ route('password.store') }}" class="input-field-area d-flex flex-column gap-4">
          @csrf

          <!-- Hidden Token -->
          <input type="hidden" name="token" value="{{ $request->route('token') }}">

          <div class="input-field-item">
            <p>Email</p>
            <div class="input-field">
              <input
                type="email"
                name="email"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                placeholder="Enter your email"
              />
              @error('email')
                <span class="text-danger text-sm">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="input-field-item">
            <p>New Password</p>
            <div class="input-field">
              <input
                type="password"
                name="password"
                required
                placeholder="New password"
              />
              @erro
