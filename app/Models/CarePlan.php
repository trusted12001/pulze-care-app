<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarePlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service_user_id','title','status','version',
        'start_date','next_review_date','review_frequency','summary',
        'author_id','approved_by','approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'start_date' => 'date',
        'next_review_date' => 'date',
    ];

    public function serviceUser() { return $this->belongsTo(ServiceUser::class); }
    public function author()      { return $this->belongsTo(User::class, 'author_id'); }
    public function approver()    { return $this->belongsTo(User::class, 'approved_by'); }

    public function sections()    { return $this->hasMany(CarePlanSection::class)->orderBy('display_order'); }

    // Helper to seed standard sections on creation
    public function seedDefaultSections(): void
    {
        $defaults = [
            'Identity & Inclusion','Health','Nutrition','Medication',
            'Mobility','Communication','Personal Care','Emotional Wellbeing',
            'Risk Management','Preferences'
        ];
        $i = 1;
        foreach ($defaults as $name) {
            $this->sections()->create([
                'name' => $name,
                'display_order' => $i++,
            ]);
        }
    }




    public function reviews()   { return $this->hasMany(\App\Models\CarePlanReview::class)->latest(); }
    public function versions()  { return $this->hasMany(\App\Models\CarePlanVersion::class)->orderByDesc('version'); }
    public function signoffs()  { return $this->hasMany(\App\Models\CarePlanSignoff::class)->latest(); }

    /** Create a new version row + optional snapshot */
    public function bumpVersion(?int $approvedBy = null, ?string $note = null, bool $withSnapshot = false): \App\Models\CarePlanVersion
    {
        $this->version = ($this->version ?? 1) + 1;
        $this->status  = 'active';
        $this->approved_by = $approvedBy;
        $this->approved_at = now();
        $this->save();

        $payload = [
            'care_plan' => $this->only(['title','status','version','start_date','next_review_date','review_frequency','summary']),
            'sections'  => $this->sections()->with(['goals.interventions'])->get()->toArray(),
        ];

        return $this->versions()->create([
            'version'     => $this->version,
            'approved_by' => $approvedBy,
            'approved_at' => $this->approved_at,
            'change_note' => $note,
            'snapshot'    => $withSnapshot ? $payload : null,
        ]);
    }

}
