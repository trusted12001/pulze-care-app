<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff_profiles', function (Blueprint $table) {
            // Employment type & basis
            $table->enum('employment_type', ['employee','worker','contractor','bank','agency'])
                ->default('employee')
                ->after('employment_status');

            $table->enum('engagement_basis', ['full_time','part_time','casual','zero_hours'])
                ->default('full_time')
                ->after('employment_type');

            // Dates
            $table->date('hire_date')->nullable()->after('engagement_basis');
            $table->date('start_in_post')->nullable()->after('hire_date');
            $table->date('end_in_post')->nullable()->after('start_in_post');

            // Org structure
            $table->unsignedBigInteger('work_location_id')->nullable()->after('end_in_post');
            $table->unsignedBigInteger('line_manager_user_id')->nullable()->after('work_location_id');

            // Compliance flag
            $table->boolean('dbs_update_service')->default(false)->after('dbs_issued_at');

            // Optional work email already exists in your table; we’ll keep `phone` as-is (no new work_phone)

            // Indexes (named so we can drop them reliably)
            $table->index(['tenant_id','employment_status','employment_type'], 'staff_profiles_tenant_status_type_idx');
            $table->index('work_location_id', 'staff_profiles_work_location_idx');
            $table->index('line_manager_user_id', 'staff_profiles_line_manager_idx');
        });

        // NOTE: If you already have a `locations` table and want strict FKs, you can add them
        // in a separate migration after verifying table names:
        // Schema::table('staff_profiles', function (Blueprint $table) {
        //     $table->foreign('work_location_id')->references('id')->on('locations')->nullOnDelete();
        //     $table->foreign('line_manager_user_id')->references('id')->on('users')->nullOnDelete();
        // });
    }

    public function down(): void
    {
        Schema::table('staff_profiles', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex('staff_profiles_tenant_status_type_idx');
            $table->dropIndex('staff_profiles_work_location_idx');
            $table->dropIndex('staff_profiles_line_manager_idx');

            // Then columns
            $table->dropColumn([
                'employment_type',
                'engagement_basis',
                'hire_date',
                'start_in_post',
                'end_in_post',
                'work_location_id',
                'line_manager_user_id',
                'dbs_update_service',
            ]);
        });
    }
};
