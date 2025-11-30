<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentEvent extends Model
{
    protected $fillable = ['assignment_id', 'event', 'payload', 'actor_id'];
    protected $casts = ['payload' => 'array'];
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
