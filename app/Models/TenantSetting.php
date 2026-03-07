<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantSetting extends Model
{
    protected $fillable = [
        'tenant_id',
        'logo_path',
        'office_address',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getLogoUrlAttribute(): string
    {
        // If uploaded logo exists, use storage url. Otherwise fallback to your default public logo.
        if (!empty($this->logo_path)) {
            return asset('storage/' . ltrim($this->logo_path, '/'));
        }

        return asset('assets/logos/rmbj_logo_png.png');
    }


    public function settings()
    {
        return $this->hasOne(\App\Models\TenantSetting::class);
    }
}
