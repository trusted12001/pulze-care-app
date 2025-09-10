<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id','owner_type','owner_id',
        'category','filename','path','mime','uploaded_by','hash',
    ];

    public function owner() {
        return $this->morphTo();
    }

    // Convenience accessor for public URL
    public function getUrlAttribute(): string {
        return asset('storage/'.$this->path);
    }
}
