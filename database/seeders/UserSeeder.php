<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Step 1: Create roles if they don't already exist
        Role::firstOrCreate(['name' => 'super-admin']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'carer']);

        // Step 2: Create the Super Admin
        $superAdmin = User::create([
            'first_name' => 'Super',
            'last_name'  => 'Admin',
            'email' => 'abdussalam.abdul@gmail.com',
            'password' => Hash::make('12345678'),
            'tenant_id' => null,
        ]);
        $superAdmin->assignRole('super-admin');

        // Step 3: Create a tenant
        $tenant = Tenant::create([
            'name' => 'RMBJ Care Home',
            'slug' => 'rmbj-care-home',
            'subscription_status' => 'active',
            'created_by' => $superAdmin->id,
        ]);

        // Step 4: Create Admin
        $admin = User::create([
            'first_name' => 'RMBJ',
            'last_name'  => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'tenant_id' => $tenant->id,
        ]);
        $admin->assignRole('admin');

        // Step 5: Create Carer
        $carer = User::create([
            'first_name' => 'Carer',
            'last_name'  => 'One',
            'email' => 'carer@example.com',
            'password' => Hash::make('12345678'),
            'tenant_id' => $tenant->id,
        ]);
        $carer->assignRole('carer');
    }
}