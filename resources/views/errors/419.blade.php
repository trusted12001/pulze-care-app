@extends('layouts.guest')

@section('title', 'Session Expired')

@section('content')
    <main class="min-vh-100 d-flex align-items-center justify-content-center px-3 py-5">
        <div class="w-100" style="max-width:560px;">
            <div class="bg-white shadow-sm rounded-4 p-4 p-md-5">

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;background:#fff3cd;">
                        <span style="font-size:22px;">⏳</span>
                    </div>
                    <div>
                        <h1 class="h5 mb-1">Session expired</h1>
                        <p class="text-muted mb-0 small">Error 419 — Page Expired</p>
                    </div>
                </div>

                <p class="text-muted mb-3">
                    Your login session has expired (usually because you were inactive for a while, or you opened the page in
                    another tab).
                    For your security, we’ve ended the session.
                </p>

                <div class="border rounded-3 p-3 mb-4">
                    <p class="mb-2 fw-semibold">What to do next</p>
                    <ol class="mb-0 text-muted small ps-3">
                        <li>Click <strong>Go to Login</strong> to sign in again.</li>
                        <li>If you were submitting a form, refresh the page after logging in and try again.</li>
                        <li>If it keeps happening, close extra tabs and sign in again.</li>
                    </ol>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="{{ route('login') }}" class="btn btn-primary w-100">
                        Go to Login
                    </a>

                    <button type="button" class="btn btn-outline-secondary w-100" onclick="window.location.reload();">
                        Refresh page
                    </button>
                </div>

                <div class="pt-4">
                    <p class="mb-0 small text-muted">
                        Tip: To avoid this, save long form inputs elsewhere before submitting.
                    </p>
                </div>

            </div>

            <div class="text-center mt-3">
                <a href="{{ url('/') }}" class="text-decoration-none small" style="color:#9ca3af;">
                    Back to Home
                </a>
            </div>
        </div>
    </main>
@endsection