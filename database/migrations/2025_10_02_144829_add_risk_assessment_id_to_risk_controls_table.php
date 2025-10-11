<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) Add the column (nullable to avoid issues if table has rows)
        Schema::table('risk_controls', function (Blueprint $table) {
            if (!Schema::hasColumn('risk_controls', 'risk_assessment_id')) {
                $table->unsignedBigInteger('risk_assessment_id')->nullable()->after('id');
            }
        });

        // 2) Add an index + FK (will enforce when not null)
        Schema::table('risk_controls', function (Blueprint $table) {
            $table->index('risk_assessment_id', 'risk_controls_ra_idx');

            // Name the FK so it's easy to drop if needed
            $table->foreign('risk_assessment_id', 'risk_controls_ra_fk')
                  ->references('id')->on('risk_assessments')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('risk_controls', function (Blueprint $table) {
            // Drop FK + index + column (order matters)
            if (Schema::hasColumn('risk_controls', 'risk_assessment_id')) {
                $table->dropForeign('risk_controls_ra_fk');
                $table->dropIndex('risk_controls_ra_idx');
                $table->dropColumn('risk_assessment_id');
            }
        });
    }
};
