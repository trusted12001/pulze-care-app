<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // We switch to TEXT to safely store Laravel-encrypted payloads (base64 JSON)
        if (Schema::hasColumn('staff_bank_accounts', 'sort_code')) {
            DB::statement('ALTER TABLE staff_bank_accounts MODIFY sort_code TEXT NULL');
        }
        if (Schema::hasColumn('staff_bank_accounts', 'account_number')) {
            DB::statement('ALTER TABLE staff_bank_accounts MODIFY account_number TEXT NULL');
        }
        // Handle either spelling: "roll" (likely correct) or "role" (typo)
        if (Schema::hasColumn('staff_bank_accounts', 'building_society_roll')) {
            DB::statement('ALTER TABLE staff_bank_accounts MODIFY building_society_roll TEXT NULL');
        } elseif (Schema::hasColumn('staff_bank_accounts', 'building_society_role')) {
            DB::statement('ALTER TABLE staff_bank_accounts MODIFY building_society_role TEXT NULL');
        }
    }

    public function down(): void
    {
        // Revert to reasonable VARCHAR sizes if you ever remove encryption
        if (Schema::hasColumn('staff_bank_accounts', 'sort_code')) {
            DB::statement('ALTER TABLE staff_bank_accounts MODIFY sort_code VARCHAR(32) NULL');
        }
        if (Schema::hasColumn('staff_bank_accounts', 'account_number')) {
            DB::statement('ALTER TABLE staff_bank_accounts MODIFY account_number VARCHAR(34) NULL'); // IBAN max 34
        }
        if (Schema::hasColumn('staff_bank_accounts', 'building_society_roll')) {
            DB::statement('ALTER TABLE staff_bank_accounts MODIFY building_society_roll VARCHAR(64) NULL');
        } elseif (Schema::hasColumn('staff_bank_accounts', 'building_society_role')) {
            DB::statement('ALTER TABLE staff_bank_accounts MODIFY building_society_role VARCHAR(64) NULL');
        }
    }
};
