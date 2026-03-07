<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Prevent Laravel from redirecting to a previously intended URL
        $request->session()->forget('url.intended');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            return redirect()
                ->route('backend.super-admin.index')
                ->with('success', "Welcome back, {$user->first_name}!");
        }

        if ($user->hasRole('admin')) {
            return redirect()
                ->route('backend.admin.index')
                ->with('success', "Welcome back, {$user->first_name}!");
        }

        if ($user->hasRole('carer')) {
            return redirect()
                ->route('frontend.carer.index')
                ->with('success', "Welcome back, {$user->first_name}!");
        }

        return redirect('/dashboard')
            ->with('warning', 'You are logged in, but your account role is not recognised. Please contact the administrator.');
    }



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $name = Auth::user()?->name;

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', $name ? "You have been logged out successfully, {$name}." : "You have been logged out successfully.");
    }
}
