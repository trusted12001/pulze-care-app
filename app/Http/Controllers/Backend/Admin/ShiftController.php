<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function assign(Request $request, Shift $shift) {
        $data = $request->validate(['staff_id'=>['required','exists:users,id']]);
        ShiftAssignment::firstOrCreate(['shift_id'=>$shift->id,'staff_id'=>$data['staff_id']], ['status'=>'assigned']);
        return back()->with('success','Staff assigned.');
    }

    public function unassign(Shift $shift, User $user) {
        ShiftAssignment::where('shift_id',$shift->id)->where('staff_id',$user->id)->delete();
        return back()->with('success','Staff unassigned.');
    }
}
