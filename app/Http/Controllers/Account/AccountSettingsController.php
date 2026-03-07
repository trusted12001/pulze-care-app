<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AccountSettingsController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        return view('account.settings', [
            'user' => $user,
            'layout' => $this->resolveLayout($user),
        ]);
    }

    protected function resolveLayout($user): string
    {
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('super-admin')) {
                return 'layouts.superadmin';
            }

            if ($user->hasRole('admin')) {
                return 'layouts.admin';
            }

            if ($user->hasRole('carer')) {
                return 'layouts.carer';
            }
        }

        return 'layouts.admin';
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:255'],
            'address_line_1' => ['nullable', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'town_city' => ['nullable', 'string', 'max:255'],
            'county' => ['nullable', 'string', 'max:255'],
            'postcode' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Contact information updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('password_success', 'Password updated successfully.');
    }
}
