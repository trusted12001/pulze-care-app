<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentEvidence extends Model
{
    protected $fillable = ['assignment_id', 'file_path', 'file_type', 'note', 'captured_at', 'lat', 'lng', 'accuracy', 'created_by'];
    protected $casts = ['captured_at' => 'datetime'];
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
