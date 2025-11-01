<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiskType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RiskTypeController extends Controller
{
    public function index()
    {
        $types = RiskType::query()->orderBy('name')->paginate(20);
        return view('backend.admin.risk-types.index', compact('types'));
    }

    public function create()
    {
        return view('backend.admin.risk-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required','string','max:255', Rule::unique('risk_types','name')],
            'description' => ['nullable','string'],
        ]);

        RiskType::create($data);

        return redirect()
            ->route('backend.admin.risk-assessments.index')
            ->with('status', 'Risk Type added.');
    }
}
