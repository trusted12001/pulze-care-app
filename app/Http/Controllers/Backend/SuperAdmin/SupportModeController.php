<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class SupportModeController extends Controller
{
    public function enter(Request $request, Tenant $tenant)
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        session([
            'active_tenant_id' => $tenant->id,
            'support_mode'     => true,
        ]);

        return redirect()
            ->route('backend.admin.index')
            ->with('success', "Support mode activated for {$tenant->name}.");
    }

    public function exit(Request $request)
    {
        abort_unless(auth()->check(), 403);
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        session()->forget([
            'active_tenant_id',
            'support_mode',
        ]);

        return redirect()
            ->route('backend.super-admin.index')
            ->with('success', 'Support mode exited successfully.');
    }
}
