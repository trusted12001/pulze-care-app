<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskType;

class RiskTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Falls', 'default_guidance' => 'Assess environment, footwear, aids, supervision.'],
            ['name' => 'Choking / Dysphagia', 'default_guidance' => 'Texture modification, positioning, supervision at meals.'],
            ['name' => 'Pressure Ulcer', 'default_guidance' => 'Reposition schedule, mattress, skin checks, hydration.'],
            ['name' => 'Epilepsy / Seizures', 'default_guidance' => 'Triggers, rescue med protocol, observation, post-ictal care.'],
            ['name' => 'Medication Safety', 'default_guidance' => 'MAR checks, double-check high-risk meds, PRN protocols.'],
            ['name' => 'Behavioral Distress / Aggression', 'default_guidance' => 'Triggers, de-escalation plan, positive behavior support.'],
            ['name' => 'Absconding / Missing', 'default_guidance' => 'Observation levels, safe community access, escalation steps.'],
            ['name' => 'Fire Safety', 'default_guidance' => 'Evac plan, PEEP, alarms, drills.'],
            ['name' => 'COSHH (Substances)', 'default_guidance' => 'SDS, storage, PPE, spill response, exposure log.'],
        ];

        foreach ($types as $t) {
            RiskType::firstOrCreate(['name' => $t['name']], $t);
        }
    }
}
