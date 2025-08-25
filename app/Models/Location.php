<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'address_line1',
        'address_line2',
        'city',
        'postcode',
        'country',
        'phone',
        'email',
        'status',
        'lat',
        'lng',
        'geofence_radius_m',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        'geofence_radius_m' => 'integer',
    ];

    public function serviceUsers()
    {
        return $this->hasMany(ServiceUser::class);
    }
}
