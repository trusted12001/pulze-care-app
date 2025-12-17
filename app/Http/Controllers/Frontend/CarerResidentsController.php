<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceUser;

class CarerResidentsController extends Controller
{
    public function index(Request $request)
    {
        // For now: show all residents (we’ll later filter by location/assignment/tenant)
        $residents = ServiceUser::query()->latest()->paginate(20);

        return view('frontend.carer.residents.index', compact('residents'));
    }

    public function show(ServiceUser $resident)
    {
        return view('frontend.carer.residents.show', compact('resident'));
    }
}
