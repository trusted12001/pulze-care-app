<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotaEntry extends Model
{
    use HasFactory;

    /**
     * IMPORTANT:
     * Make sure this matches your actual table name.
     * If your table is called "rota_rows" or "rota_items", change this.
     */
    protected $table = 'shifts';

    protected $fillable = [
        'rota_period_id',
        'staff_id',
        'date',
        'start_time',
        'end_time',
        'role',
        'notes',
    ];

    public function rotaPeriod()
    {
        return $this->belongsTo(RotaPeriod::class, 'rota_period_id');
    }

    /**
     * Link to whoever the shift is for:
     * - If rota rows reference StaffProfile, leave as StaffProfile::class
     * - If they reference users directly, change to User::class
     */
    public function staff()
    {
        return $this->belongsTo(StaffProfile::class, 'staff_id');
        // or:
        // return $this->belongsTo(User::class, 'staff_id');
    }
}
