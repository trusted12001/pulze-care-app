<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ServiceUser;


class Assignment extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'code',
        'title',
        'description',
        'type',
        'priority',
        'status',
        'location_id',
        'resident_id',
        'created_by',
        'assigned_to',
        'shift_id',
        'window_start',
        'window_end',
        'due_at',
        'requires_gps',
        'requires_signature',
        'requires_photo',
        'recurrence_rule',
        'parent_id',
        'sla_minutes',
        'risk_level',
        'metadata'
    ];


    protected $casts = [
        'window_start' => 'datetime',
        'window_end' => 'datetime',
        'due_at' => 'datetime',
        'requires_gps' => 'boolean',
        'requires_signature' => 'boolean',
        'requires_photo' => 'boolean',
        'metadata' => 'array',
    ];


    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function resident()
    {
        return $this->belongsTo(ServiceUser::class, 'resident_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
    public function parent()
    {
        return $this->belongsTo(Assignment::class, 'parent_id');
    }


    public function events()
    {
        return $this->hasMany(AssignmentEvent::class);
    }
    public function evidence()
    {
        return $this->hasMany(AssignmentEvidence::class);
    }
    public function signoffs()
    {
        return $this->hasMany(AssignmentSignoff::class);
    }
    public function links()
    {
        return $this->hasMany(AssignmentLink::class);
    }
}
