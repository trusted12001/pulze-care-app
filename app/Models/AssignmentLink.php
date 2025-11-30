<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentLink extends Model
{
    protected $fillable = ['assignment_id', 'link_type', 'link_id'];
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
