<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Check if an index already exists (MySQL/MariaDB). */
    private function indexExists(string $table, string $index): bool
    {
        $row = DB::selectOne(
            "SELECT 1 FROM information_schema.statistics
             WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ? LIMIT 1",
            [$table, $index]
        );
        return (bool) $row;
    }

    public function up(): void
    {
        Schema::table('staff_profiles', function (Blueprint $table) {
            // Columns: add only if missing
            if (!Schema::hasColumn('staff_profiles', 'employment_type')) {
                $table->enum('employment_type', ['employee','worker','contractor','bank','agency'])
                    ->default('employee')->after('employment_status');
            }
            if (!Schema::hasColumn('staff_profiles', 'engagement_basis')) {
                $table->enum('engagement_basis', ['full_time','part_time','casual','zero_hours'])
                    ->default('full_time')->after('employment_type');
            }
            if (!Schema::hasColumn('staff_profiles', 'hire_date')) {
                $table->date('hire_date')->nullable()->after('engagement_basis');
            }
            if (!Schema::hasColumn('staff_profiles', 'start_in_post')) {
                $table->date('start_in_post')->nullable()->after('hire_date');
            }
            if (!Schema::hasColumn('staff_profiles', 'end_in_post')) {
                $table->date('end_in_post')->nullable()->after('start_in_post');
            }
            if (!Schema::hasColumn('staff_profiles', 'work_location_id')) {
                $table->unsignedBigInteger('work_location_id')->nullable()->after('end_in_post');
            }
            if (!Schema::hasColumn('staff_profiles', 'line_manager_user_id')) {
                $table->unsignedBigInteger('line_manager_user_id')->nullable()->after('work_location_id');
            }
            if (!Schema::hasColumn('staff_profiles', 'dbs_update_service')) {
                $table->boolean('dbs_update_service')->default(false)->after('dbs_issued_at');
            }
            // ✅ Date of Birth (nullable for legacy rows; required via validation on create)
            if (!Schema::hasColumn('staff_profiles', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('job_title');
            }
        });

        // Indexes: add only if missing
        if (!$this->indexExists('staff_profiles', 'staff_profiles_tenant_status_type_idx')) {
            Schema::table('staff_profiles', function (Blueprint $table) {
                $table->index(['tenant_id','employment_status','employment_type'], 'staff_profiles_tenant_status_type_idx');
            });
        }
        if (!$this->indexExists('staff_profiles', 'staff_profiles_work_location_idx')) {
            Schema::table('staff_profiles', function (Blueprint $table) {
                $table->index('work_location_id', 'staff_profiles_work_location_idx');
            });
        }
        if (!$this->indexExists('staff_profiles', 'staff_profiles_line_manager_idx')) {
            Schema::table('staff_profiles', function (Blueprint $table) {
                $table->index('line_manager_user_id', 'staff_profiles_line_manager_idx');
            });
        }

        // Standardise ENUM to match UI: active/on_leave/inactive
        DB::statement("
            ALTER TABLE staff_profiles
            MODIFY employment_status ENUM('active','on_leave','inactive') NOT NULL DEFAULT 'active'
        ");
    }

    public function down(): void
    {
        // Drop indexes if they exist
        $drop = function (string $name) {
            if (DB::selectOne(
                "SELECT 1 FROM information_schema.statistics
                 WHERE table_schema = DATABASE() AND table_name = 'staff_profiles' AND index_name = ? LIMIT 1",
                [$name]
            )) {
                Schema::table('staff_profiles', function (Blueprint $table) use ($name) {
                    $table->dropIndex($name);
                });
            }
        };

        $drop('staff_profiles_tenant_status_type_idx');
        $drop('staff_profiles_work_location_idx');
        $drop('staff_profiles_line_manager_idx');

        // Drop added columns (only if present)
        Schema::table('staff_profiles', function (Blueprint $table) {
            foreach ([
                'employment_type','engagement_basis','hire_date','start_in_post','end_in_post',
                'work_location_id','line_manager_user_id','dbs_update_service','date_of_birth'
            ] as $col) {
                if (Schema::hasColumn('staff_profiles', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        // (Optional) revert ENUM
        // DB::statement("ALTER TABLE staff_profiles
        //     MODIFY employment_status ENUM('active','on_leave','terminated') NOT NULL DEFAULT 'active'");
    }
};
