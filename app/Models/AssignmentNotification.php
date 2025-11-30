<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentNotification extends Model
{
    protected $fillable = ['assignment_id', 'channel', 'to_user_id', 'sent_at', 'status', 'details'];
    protected $casts = ['sent_at' => 'datetime', 'details' => 'array'];
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
    public function recipient()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
