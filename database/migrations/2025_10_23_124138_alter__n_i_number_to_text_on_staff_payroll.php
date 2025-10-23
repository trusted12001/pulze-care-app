<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // TEXT can store Laravel's encrypted payload comfortably
        DB::statement('ALTER TABLE staff_payroll MODIFY ni_number TEXT NULL');
    }
    public function down(): void
    {
        // If you previously had VARCHAR(20) or similar, revert accordingly
        DB::statement('ALTER TABLE staff_payroll MODIFY ni_number VARCHAR(20) NULL');
    }
};
