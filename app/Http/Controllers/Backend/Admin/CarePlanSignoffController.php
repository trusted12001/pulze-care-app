<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarePlan;
use Illuminate\Http\Request;

class CarePlanSignoffController extends Controller
{
    public function store(Request $request, CarePlan $care_plan)
    {
        $data = $request->validate([
            'role_label' => ['nullable','string','max:50'],
            'pin_last4'  => ['nullable','digits:4'], // Store only last 4 for audit, never full PIN
        ]);

        $care_plan->signoffs()->create([
            'user_id'        => $request->user()->id,
            'role_label'     => $data['role_label'] ?? null,
            'version_at_sign'=> $care_plan->version,
            'signed_at'      => now(),
            'pin_last4'      => $data['pin_last4'] ?? null,
        ]);

        return back()->with('success','Signed off.');
    }
}
