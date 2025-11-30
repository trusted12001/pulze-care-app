<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentSignoff extends Model
{
    protected $fillable = ['assignment_id', 'signer_id', 'role', 'method', 'signed_at'];
    protected $casts = ['signed_at' => 'datetime'];
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
    public function signer()
    {
        return $this->belongsTo(User::class, 'signer_id');
    }
}
