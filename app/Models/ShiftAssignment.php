<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftAssignment extends Model
{
    protected $fillable = ['shift_id','staff_id','status','notes'];
    public function shift()   { return $this->belongsTo(Shift::class); }
    public function staff()   { return $this->belongsTo(User::class, 'staff_id'); }
    public function attendance() { return $this->hasOne(Attendance::class); }
}
