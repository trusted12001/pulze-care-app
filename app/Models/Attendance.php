<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'shift_assignment_id','check_in_at','check_in_latlng','within_geofence_in',
        'check_out_at','check_out_latlng','within_geofence_out','variance_minutes','exception_reason'
    ];
    protected $casts = [
        'check_in_at'=>'datetime','check_out_at'=>'datetime',
        'check_in_latlng'=>'array','check_out_latlng'=>'array',
        'within_geofence_in'=>'boolean','within_geofence_out'=>'boolean',
    ];

    public function assignment() { return $this->belongsTo(ShiftAssignment::class, 'shift_assignment_id'); }
}
