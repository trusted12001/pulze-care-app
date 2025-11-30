<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ShiftAssignment;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function checkIn(Request $request, ShiftAssignment $assignment) {
        $data = $request->validate([
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],
        ]);
        $attendance = $assignment->attendance ?: new Attendance(['shift_assignment_id'=>$assignment->id]);
        $attendance->check_in_at = now();
        $attendance->check_in_latlng = isset($data['lat']) ? ['lat'=>$data['lat'],'lng'=>$data['lng']] : null;
        // TODO: within_geofence_in = compute against location geofence if available
        $attendance->within_geofence_in = true;
        $attendance->save();

        return back()->with('success','Checked in.');
    }

    public function checkOut(Request $request, ShiftAssignment $assignment) {
        $data = $request->validate([
            'lat' => ['nullable','numeric'],
            'lng' => ['nullable','numeric'],
            'exception_reason' => ['nullable','string','max:120'],
        ]);
        $attendance = $assignment->attendance ?: new Attendance(['shift_assignment_id'=>$assignment->id]);
        $attendance->check_out_at = now();
        $attendance->check_out_latlng = isset($data['lat']) ? ['lat'=>$data['lat'],'lng'=>$data['lng']] : null;
        $attendance->within_geofence_out = true;

        // simple variance calc
        $planned = $assignment->shift->duration_minutes;
        $actual  = $attendance->check_in_at && $attendance->check_out_at
            ? $attendance->check_in_at->diffInMinutes($attendance->check_out_at)
            : null;
        $attendance->variance_minutes = $actual ? ($actual - $planned) : null;
        $attendance->exception_reason = $data['exception_reason'] ?? $attendance->exception_reason;
        $attendance->save();

        return back()->with('success','Checked out.');
    }
}
