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

        // Step 2: Create or update the Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'abdussalam.abdul@gmail.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('12345678'),
                'tenant_id' => null,
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Step 3: Create or update a tenant
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'rmbj-care-home'],
            [
                'name' => 'RMBJ Care Home',
                'subscription_status' => 'active',
                'created_by' => $superAdmin->id,
            ]
        );

        // Step 4: Create or update Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'RMBJ',
                'last_name' => 'Admin',
                'password' => Hash::make('12345678'),
                'tenant_id' => $tenant->id,
            ]
        );
        $admin->assignRole('admin');

        // Step 5: Create or update Carer
        $carer = User::firstOrCreate(
            ['email' => 'carer@example.com'],
            [
                'first_name' => 'Carer',
                'last_name' => 'One',
                'password' => Hash::make('12345678'),
                'tenant_id' => $tenant->id,
            ]
        );
        $carer->assignRole('carer');
    }
}