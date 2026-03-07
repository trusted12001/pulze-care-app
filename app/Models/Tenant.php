<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TenantSetting;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'subscription_status',
        'email',
        'phone',
        'address',
        'status',
        'created_by',
    ];


    public function settings()
    {
        return $this->hasOne(TenantSetting::class, 'tenant_id');
    }
}
